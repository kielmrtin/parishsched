<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Blocks a second active reservation for the same person and sacrament
 * (panel revision: "reservation detection for name").
 *
 * The "person" is who receives the sacrament: the child for a baptism, the
 * groom and the bride for a wedding, the deceased for a funeral, the person
 * being confirmed for a kumpil. Names are
 * compared as one normalized full name (first + middle + last + suffix), so
 * people who share a first name but have a different surname are never
 * blocked. Only pending/approved/booked reservations count.
 *
 * supabase/reservation_name_guard.sql enforces the same rule inside the
 * database for the Flutter app, which inserts into Supabase directly.
 */
class ReservationNameGuard
{
    private const ACTIVE_STATUSES = ['pending', 'approved', 'booked'];

    /** Lowercase, strip accents (ñ → n) and punctuation, collapse spaces. */
    public static function normalize(?string $value): string
    {
        $value = mb_strtolower(trim((string) $value));
        $value = strtr($value, [
            'ñ' => 'n', 'á' => 'a', 'à' => 'a', 'â' => 'a', 'ä' => 'a', 'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i', 'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'ö' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        ]);
        $value = preg_replace('/[.,]/', ' ', $value);

        return trim(preg_replace('/\s+/', ' ', $value));
    }

    private static function fullName(array $parts): string
    {
        return self::normalize(implode(' ', array_map(fn ($p) => (string) $p, $parts)));
    }

    /**
     * Normalized full names of everyone receiving the sacrament in one
     * reservation. Understands the website's nested `details` and the
     * mobile app's flat keys.
     *
     * @return array<string, string> role => normalized full name (empty names dropped)
     */
    public static function subjectNames(string $eventType, array $details): array
    {
        $type  = strtolower(trim($eventType));
        $block = is_array($details[$type] ?? null) ? $details[$type] : [];
        $pick  = fn (string $key) => $block[$key] ?? $details[$key] ?? '';
        $names = [];

        // Flat first_name/last_name from the app is the person reserving, not
        // the child or the deceased, so it is deliberately never used here.
        if ($type === 'baptism') {
            $names['child'] = self::fullName([$pick('child_first'), $pick('child_middle'), $pick('child_last'), $pick('child_suffix')]);
        } elseif ($type === 'wedding') {
            $names['groom'] = self::fullName([$pick('groom_first'), $pick('groom_middle'), $pick('groom_last'), $pick('groom_suffix')]);
            $names['bride'] = self::fullName([$pick('bride_first'), $pick('bride_middle'), $pick('bride_last'), $pick('bride_suffix')]);
        } elseif ($type === 'kumpil') {
            $names['confirmand'] = self::fullName([$pick('confirmand_first'), $pick('confirmand_middle'), $pick('confirmand_last'), $pick('confirmand_suffix')]);
        } elseif ($type === 'funeral') {
            $split = self::fullName([$pick('deceased_first'), $pick('deceased_middle'), $pick('deceased_last'), $pick('deceased_suffix')]);
            $names['deceased'] = $split !== '' ? $split : self::normalize($pick('deceased_name'));
        }

        return array_filter($names, fn ($n) => $n !== '');
    }

    /**
     * Returns the first active reservation of the same sacrament that shares
     * a subject name, or null when there is none.
     *
     * @return array{reservation: array, role: string, display: string}|null
     */
    public function findDuplicate(string $eventType, array $details, array $displayNames = []): ?array
    {
        $wanted = self::subjectNames($eventType, $details);
        if (!$wanted) {
            return null;
        }

        $key = config('services.supabase.service_role_key');
        $response = Http::withHeaders(['apikey' => $key, 'Authorization' => 'Bearer ' . $key])
            ->get(config('services.supabase.url') . '/rest/v1/reservations', [
                'select'     => 'id,event_type,reservation_date,reservation_time,status,details',
                'event_type' => 'ilike.' . trim($eventType),
                'status'     => 'in.(' . implode(',', self::ACTIVE_STATUSES) . ')',
            ]);

        if (!$response->successful()) {
            // The database trigger still blocks the insert if this lookup fails.
            return null;
        }

        foreach ($response->json() ?? [] as $existing) {
            $existingNames = self::subjectNames($existing['event_type'] ?? $eventType, (array) ($existing['details'] ?? []));
            foreach ($wanted as $role => $name) {
                if (in_array($name, $existingNames, true)) {
                    return [
                        'reservation' => $existing,
                        'role'        => $role,
                        'display'     => $displayNames[$role] ?? ucwords($name),
                    ];
                }
            }
        }

        return null;
    }

    /** Customer-facing message for a duplicate found by findDuplicate(). */
    public static function message(string $eventType, array $duplicate): string
    {
        $type   = strtolower($eventType);
        $status = strtolower($duplicate['reservation']['status'] ?? 'pending') === 'pending' ? 'pending' : 'approved';
        $date   = !empty($duplicate['reservation']['reservation_date'])
            ? \Carbon\Carbon::parse($duplicate['reservation']['reservation_date'])->format('M j, Y')
            : null;
        $who    = $duplicate['display'];

        $lead = match ($type) {
            'wedding' => "{$who} already has an active wedding reservation",
            'funeral' => "A funeral for {$who} is already on record",
            'kumpil'  => "A kumpil for {$who} is already on record",
            default   => "A baptism for {$who} is already on record",
        };

        return $lead . " ({$status}" . ($date ? ", {$date}" : '') . '). '
            . 'Each person can only have one active ' . $type . ' reservation. '
            . 'Please contact the parish office if this is a mistake.';
    }
}
