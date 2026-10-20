<?php

namespace App\Support;

/**
 * Which documents each sacrament must upload with the request (panel
 * revision: "verify which requirements are needed" / "automatic checking of
 * requirements"). Used by the reservation form, the server-side validation
 * in ReservationController::store(), and the admin Complete/Missing badge.
 */
class ReservationRequirements
{
    /** @return array<string, array<string, string>> Event type => [field_key => label] */
    public static function uploads(): array
    {
        return [
            'Baptism' => [
                'baptism_file' => 'Birth certificate / Baptism document (PDF, JPG, PNG)',
            ],
            'Wedding' => [
                'wedding_file1' => 'Wedding Requirement 1 / Baptismal certificate',
                'wedding_file2' => 'Wedding Requirement 2 / Marriage license or seminar certificate',
            ],
            'Funeral' => [
                'funeral_file' => 'Funeral document / Marriage contract or baptismal certificate',
            ],
        ];
    }

    /** @return array<string, string> field_key => label for one event type (case-insensitive). */
    public static function uploadsFor(?string $eventType): array
    {
        foreach (self::uploads() as $type => $fields) {
            if (strcasecmp($type, trim((string) $eventType)) === 0) {
                return $fields;
            }
        }

        return [];
    }

    /**
     * Wedding documents, one row each on the form (groom, bride, couple).
     * Only the two baptismal certificates must be attached to submit; the
     * rest can be handed in later ("to follow", panel revision D4-5).
     *
     * @return array<string, array{label: string, owner: string, required: bool, hint: string}>
     */
    public static function weddingDocuments(): array
    {
        $person = fn (string $owner) => [
            "{$owner}_baptismal"    => ['label' => 'Baptismal Certificate', 'owner' => $owner, 'required' => true, 'hint' => 'For marriage purposes'],
            "{$owner}_confirmation" => ['label' => 'Confirmation Certificate', 'owner' => $owner, 'required' => false, 'hint' => 'For marriage purposes'],
            "{$owner}_permit"       => ['label' => 'Marriage Permit', 'owner' => $owner, 'required' => false, 'hint' => 'Only if from another parish'],
            "{$owner}_banns"        => ['label' => 'Marriage Banns', 'owner' => $owner, 'required' => false, 'hint' => 'Posted at your home parish'],
        ];

        return $person('groom') + $person('bride') + [
            'license'  => ['label' => 'Marriage License', 'owner' => 'couple', 'required' => false, 'hint' => 'From the municipal civil registrar'],
            'seminar'  => ['label' => 'Certificate of Seminar', 'owner' => 'couple', 'required' => false, 'hint' => 'Pre-Cana seminar, attended together'],
            'sponsors' => ['label' => 'Listahan ng Ninong at Ninang', 'owner' => 'couple', 'required' => false, 'hint' => 'Apat na pares na minimum'],
        ];
    }

    /** "Groom · Baptismal Certificate", or the plain label for couple documents. */
    public static function weddingLabel(string $key): string
    {
        $doc = self::weddingDocuments()[$key] ?? null;
        if (!$doc) {
            return $key;
        }

        return $doc['owner'] === 'couple' ? $doc['label'] : ucfirst($doc['owner']) . ' · ' . $doc['label'];
    }

    /**
     * When "to follow" wedding documents are due: 30 days before the wedding,
     * or 7 days before when the wedding is closer than that (never before today).
     */
    public static function weddingDueDate(string $weddingDate, ?string $today = null): string
    {
        $today = $today ?? now()->toDateString();
        $wedding = \Carbon\Carbon::parse($weddingDate)->startOfDay();

        foreach ([30, 7] as $days) {
            $due = $wedding->copy()->subDays($days)->toDateString();
            if ($due >= $today) {
                return $due;
            }
        }

        return max($today, $wedding->toDateString());
    }

    /** Weddings booked on the website since the "to follow" change carry a due date. */
    public static function usesFollowList(?string $eventType, $details): bool
    {
        if (is_string($details)) {
            $details = json_decode($details, true) ?? [];
        }

        return strcasecmp(trim((string) $eventType), 'Wedding') === 0
            && !empty($details['wedding']['documents_due']);
    }

    /**
     * Requirement status of a saved reservation from its attachment rows.
     * Files uploaded by the mobile app have no field_key, so each of those
     * counts toward one still-missing requirement.
     *
     * Weddings with a "to follow" list count every wedding document: each is
     * done when its file is attached or staff marked it received at the office.
     *
     * @param  array<int, array{field_key?: ?string}>  $attachments
     * @return array{required: int, uploaded: int, complete: bool, missing: string[], to_follow: array<string, string>, due: ?string, overdue: bool}
     */
    public static function status(?string $eventType, array $attachments, $details = []): array
    {
        if (self::usesFollowList($eventType, $details)) {
            if (is_string($details)) {
                $details = json_decode($details, true) ?? [];
            }
            $done = array_merge(
                array_map(fn ($a) => (string) ($a['field_key'] ?? ''), $attachments),
                (array) ($details['wedding']['documents_received'] ?? [])
            );
            $docs = self::weddingDocuments();
            $toFollow = [];
            foreach ($docs as $key => $doc) {
                if (!in_array($key, $done, true)) {
                    $toFollow[$key] = self::weddingLabel($key);
                }
            }
            $due = (string) $details['wedding']['documents_due'];

            return [
                'required'  => count($docs),
                'uploaded'  => count($docs) - count($toFollow),
                'complete'  => !$toFollow,
                'missing'   => array_values($toFollow),
                'to_follow' => $toFollow,
                'due'       => $due,
                'overdue'   => $toFollow && now()->toDateString() > $due,
            ];
        }

        $required = self::uploadsFor($eventType);
        $keys     = array_map(fn ($a) => (string) ($a['field_key'] ?? ''), $attachments);
        $unkeyed  = count(array_filter($keys, fn ($k) => $k === ''));

        $missing = array_values(array_diff_key($required, array_flip($keys)));
        $missing = array_slice($missing, min($unkeyed, count($missing)));

        $uploaded = count($required) - count($missing);

        return [
            'required'  => count($required),
            'uploaded'  => $uploaded,
            'complete'  => count($missing) === 0,
            'missing'   => $missing,
            'to_follow' => [],
            'due'       => null,
            'overdue'   => false,
        ];
    }
}
