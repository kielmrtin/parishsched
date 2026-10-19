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
     * Requirement status of a saved reservation from its attachment rows.
     * Files uploaded by the mobile app have no field_key, so each of those
     * counts toward one still-missing requirement.
     *
     * @param  array<int, array{field_key?: ?string}>  $attachments
     * @return array{required: int, uploaded: int, complete: bool, missing: string[]}
     */
    public static function status(?string $eventType, array $attachments): array
    {
        $required = self::uploadsFor($eventType);
        $keys     = array_map(fn ($a) => (string) ($a['field_key'] ?? ''), $attachments);
        $unkeyed  = count(array_filter($keys, fn ($k) => $k === ''));

        $missing = array_values(array_diff_key($required, array_flip($keys)));
        $missing = array_slice($missing, min($unkeyed, count($missing)));

        $uploaded = count($required) - count($missing);

        return [
            'required' => count($required),
            'uploaded' => $uploaded,
            'complete' => count($missing) === 0,
            'missing'  => $missing,
        ];
    }
}
