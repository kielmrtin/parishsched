<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Storage;      

class ReservationService
{
    const UNKNOWN_SLOT = '__unknown__';

    public function getWeddingChecklist(): array
    {
        return [
            'baptismal-certificate'   => 'Baptismal Certificate (for marriage purposes)',
            'confirmation-certificate'=> 'Confirmation Certificate (for marriage purposes)',
            'marriage-permit'         => 'Marriage Permit',
            'marriage-banns'          => 'Marriage Banns',
            'marriage-license'        => 'Marriage License',
            'seminar-certificate'     => 'Certificate of Seminar',
            'sponsors-list'           => 'Listahan ng Ninong at Ninang (apat na pares na minimum)',
        ];
    }

    public function getFuneralMaritalStatusOptions(): array
    {
        return [
            'married_not_baptized' => 'Married (not baptized in the church)',
            'single'               => 'Single / Unmarried',
        ];
    }

    public function getAttachmentRequirementSets(): array
    {
        return [
            'Baptism' => [
                'title'       => 'Required Baptism documents',
                'description' => 'Upload clear scans or photos of the following requirements. Accepted formats: PDF, JPG, PNG (max 5MB each).',
                'documents'   => [
                    'baptism-birth-certificate'      => ['label' => 'Birth certificate of the child (Xerox)'],
                    'baptism-parent-marriage-contract'=> ['label' => 'Marriage contract of parents (Xerox)'],
                ],
                'notes' => [
                    'Choose one godfather and one godmother as major sponsors (proxies are not allowed).',
                    'Major sponsors must be practicing Catholics in good standing.',
                    'Suggested church donation: P800.',
                    'Please bring original documents to the parish office on the day of baptism.',
                ],
            ],
            'Wedding' => [
                'title'       => 'Required Wedding documents',
                'description' => 'Upload scanned copies of the following pre-marriage requirements. Accepted formats: PDF, JPG, PNG (max 5MB each).',
                'documents'   => [
                    'wedding-bride-baptismal'         => ['label' => "Bride's baptismal certificate (for marriage purposes)"],
                    'wedding-groom-baptismal'         => ['label' => "Groom's baptismal certificate (for marriage purposes)"],
                    'wedding-marriage-license'        => ['label' => 'Marriage license'],
                    'wedding-seminar-certificate-file'=> ['label' => 'Pre-Cana / marriage preparation seminar certificate'],
                ],
                'notes' => ['Submit photocopies with the original documents to the parish office when requested.'],
            ],
            'Funeral' => [
                'title'       => 'Required Funeral documents',
                'description' => 'Upload the document that matches the marital status selected above. Accepted formats: PDF, JPG, PNG (max 5MB).',
                'documents'   => [
                    'funeral-marriage-contract'      => [
                        'label'       => 'Marriage contract of the deceased (if married but not baptized in the church)',
                        'conditional' => ['field' => 'funeral-marital-status', 'value' => 'married_not_baptized'],
                    ],
                    'funeral-baptismal-certificate'  => [
                        'label'       => 'Baptismal certificate of the deceased (if single or unmarried)',
                        'conditional' => ['field' => 'funeral-marital-status', 'value' => 'single'],
                    ],
                ],
            ],
        ];
    }

    public function splitFullName(string $fullName): array
    {
        $normalized = trim(preg_replace('/\s+/u', ' ', $fullName));
        $components = ['first' => '', 'middle' => '', 'last' => '', 'suffix' => ''];

        if ($normalized === '') return $components;

        $commaParts = array_map('trim', explode(',', $normalized));
        if (count($commaParts) > 1) {
            $components['suffix'] = array_pop($commaParts);
            $normalized = trim(implode(' ', $commaParts));
        }

        $tokens = preg_split('/\s+/u', $normalized) ?: [];
        $suffixPatterns = ['jr', 'jr.', 'sr', 'sr.', 'ii', 'iii', 'iv', 'v', 'vi'];
        $last = end($tokens);
        if ($last && in_array(strtolower(rtrim((string)$last, '.')), $suffixPatterns, true)) {
            if ($components['suffix'] === '') {
                $components['suffix'] = (string) array_pop($tokens);
            }
        }

        $count = count($tokens);
        if ($count === 1) { $components['first'] = $tokens[0]; }
        elseif ($count >= 2) {
            $components['first'] = array_shift($tokens);
            $components['last'] = array_pop($tokens);
            if (!empty($tokens)) $components['middle'] = implode(' ', $tokens);
        }

        return array_map(fn($v) => trim(preg_replace('/\s+/u', ' ', $v) ?? $v), $components);
    }

    public function updateFullName(array &$data): void
    {
        $this->updateNameComponents($data, 'reservation-name', 'reservation-name');
    }

    public function updateRelatedNames(array &$data): void
    {
        $this->updateNameComponents($data, 'wedding-bride-name', 'wedding-bride-name');
        $this->updateNameComponents($data, 'wedding-groom-name', 'wedding-groom-name');
        $this->updateNameComponents($data, 'funeral-deceased-name', 'funeral-deceased-name');
    }

    private function updateNameComponents(array &$data, string $prefix, string $combined): void
    {
        $normalize = fn($v) => trim(preg_replace('/\s+/u', ' ', $v) ?? $v);
        $first  = $normalize((string)($data["{$prefix}-first"]  ?? ''));
        $middle = $normalize((string)($data["{$prefix}-middle"] ?? ''));
        $last   = $normalize((string)($data["{$prefix}-last"]   ?? ''));
        $suffix = $normalize((string)($data["{$prefix}-suffix"] ?? ''));

        $data["{$prefix}-first"]  = $first;
        $data["{$prefix}-middle"] = $middle;
        $data["{$prefix}-last"]   = $last;
        $data["{$prefix}-suffix"] = $suffix;

        $parts = array_filter([$first, $middle, $last]);
        $full  = implode(' ', $parts);
        if ($full && $suffix) $full .= ', ' . $suffix;
        elseif ($suffix)      $full = $suffix;
        $data[$combined] = $full;
    }

    public function getUsageSummary(bool $force = false): array
    {
        static $cache = null;
        if (!$force && is_array($cache)) return $cache;

        $rows = DB::table('reservations')
            ->select('event_type', 'reservation_date as preferred_date', 'reservation_time as preferred_time', 'status')
            ->get();

        $summary = [];
        foreach ($rows as $row) {
            if (in_array(strtolower($row->status ?? ''), ['declined', 'canceled', 'cancelled'], true)) continue;
            $date = $this->normalizeDate($row->preferred_date);
            if (!$date) continue;
            $time = $this->normalizeTimeSlot($row->event_type, $row->preferred_time ?? '');
            $summary[$date][$row->event_type][] = $time;
        }

        $cache = $summary;
        return $summary;
    }

    public function loadApprovedGroupedByDate(): array
    {
        $rows = DB::table('reservations')
            ->select('name', 'event_type', 'reservation_date as preferred_date', 'reservation_time as preferred_time', 'status')
            ->where('status', 'approved')
            ->orderBy('reservation_date')->orderBy('reservation_time')
            ->get();

        $grouped = [];
        foreach ($rows as $row) {
            $date = $this->normalizeDate($row->preferred_date);
            if (!$date) continue;
            $time = trim($row->preferred_time ?? '');
            if ($time) {
                $normalized = $this->normalizeTimeSlot($row->event_type, $time);
                if ($normalized !== self::UNKNOWN_SLOT) $time = $normalized;
            }
            if (!array_key_exists($date, $grouped)) {
                $grouped[$date] = ['date' => $date, 'reservations' => []];
            }
            $grouped[$date]['reservations'][] = [
                'name'          => trim($row->name ?? ''),
                'eventType'     => trim($row->event_type ?? ''),
                'preferredTime' => $time,
            ];
        }

        return array_values($grouped);
    }

    public function normalizeDate($input): ?string
    {
        $trimmed = trim((string)$input);
        if (!$trimmed) return null;

        foreach (['Y-m-d', 'm/d/Y', 'm/d/y'] as $format) {
            $dt = DateTime::createFromFormat($format, $trimmed);
            if ($dt) {
                $errors = DateTime::getLastErrors();
                if (!$errors || ($errors['warning_count'] === 0 && $errors['error_count'] === 0)) {
                    return $dt->format('Y-m-d');
                }
            }
        }
        return null;
    }

    public function normalizeTimeSlot(string $eventType, string $time): string
    {
        $key = strtolower($eventType);
        $trimmed = trim($time);

        if ($key === 'baptism') return '11:00 AM - 12:00 PM';

        $parsed = $this->parseTime($trimmed);

        if ($key === 'wedding') {
            if ($parsed) {
                return (int)$parsed->format('G') < 12 ? '7:30 AM - 10:00 AM' : '3:00 PM - 5:00 PM';
            }
            $upper = strtoupper($trimmed);
            if (str_contains($upper, '3:00') || str_contains($upper, '15:') || str_contains($upper, '5:00')) {
                return '3:00 PM - 5:00 PM';
            }
            return $trimmed === '' ? self::UNKNOWN_SLOT : '7:30 AM - 10:00 AM';
        }

        if ($key === 'funeral') {
            if ($parsed) return $parsed->format('g:i A');
            if (preg_match('/([0-9]{1,2}:[0-9]{2})/', strtoupper($trimmed), $m)) {
                $dt = DateTime::createFromFormat('H:i', $m[1]);
                if ($dt) return $dt->format('g:i A');
            }
            return $trimmed === '' ? self::UNKNOWN_SLOT : $trimmed;
        }

        if ($trimmed === '') return self::UNKNOWN_SLOT;
        return $parsed ? $parsed->format('g:i A') : $trimmed;
    }

    private function parseTime(string $value): ?DateTime
    {
        $trimmed = trim($value);
        if (!$trimmed) return null;
        $primary = trim(preg_split('/\s*-\s*/', $trimmed)[0] ?? '');
        if (!$primary) return null;

        $formats = ['g:i A', 'g:iA', 'g A', 'gA', 'H:i', 'H:i:s', 'G:i', 'G:i:s'];
        foreach ($formats as $f) {
            foreach ([strtoupper($primary), $primary] as $candidate) {
                $dt = DateTime::createFromFormat($f, $candidate);
                if ($dt) {
                    $e = DateTime::getLastErrors();
                    if (!$e || ($e['warning_count'] === 0 && $e['error_count'] === 0)) return $dt;
                }
            }
        }

        $ts = strtotime($trimmed);
        if ($ts !== false) {
            $dt = new DateTime();
            $dt->setTimestamp($ts);
            return $dt;
        }
        return null;
    }

    public function validateSubmission(array $formData, array $selectedRequirements, Request $request): array
    {
        $checklist = $this->getWeddingChecklist();
        $attachmentSets = $this->getAttachmentRequirementSets();
        $error = '';
        $normalizedDate = null;

        if (empty($formData['reservation-name-first']) || empty($formData['reservation-name-last'])) {
            $error = 'Please enter the first and last name of the person reserving.';
        } elseif (!filter_var($formData['reservation-email'], FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (empty($formData['reservation-phone'])) {
            $error = 'Please provide a contact number.';
        } elseif (empty($formData['reservation-type'])) {
            $error = 'Please select an event type.';
        } elseif (!array_key_exists($formData['reservation-type'], $attachmentSets)) {
            $error = 'The selected event type is not supported at this time.';
        } elseif (empty($formData['reservation-date'])) {
            $error = 'Please choose a date from the calendar.';
        } elseif (empty($formData['reservation-time'])) {
            $error = 'Please choose a preferred time.';
        } else {
            $normalizedDate = $this->normalizeDate($formData['reservation-date']);
            if (!$normalizedDate) {
                $error = 'Please choose a valid reservation date.';
            } else {
                $selected = DateTime::createFromFormat('Y-m-d', $normalizedDate);
                if ($selected) {
                    $selected->setTime(0, 0, 0);
                    if ($selected < new DateTime('today')) {
                        $error = 'Please choose a reservation date that is not in the past.';
                    }
                }
            }
        }

        if (!$error && $normalizedDate) {
            $usage = $this->getUsageSummary(true);
            $availability = $this->determineAvailableSlots($formData['reservation-type'], $normalizedDate, $usage);
            $availableValues = array_column($availability['slots'], 'value');

            if (empty($availableValues)) {
                $error = $availability['reason'] === 'day_not_allowed'
                    ? match($formData['reservation-type']) {
                        'Wedding'  => 'Weddings may be scheduled Monday through Saturday. Please choose another date.',
                        'Baptism'  => 'Baptisms are available on Saturdays and Sundays only. Please select a weekend date.',
                        default    => 'The selected event type is not available on that day. Please choose another date.',
                    }
                    : match($formData['reservation-type']) {
                        'Wedding'  => 'All wedding slots for this date have been reserved. Please choose another date.',
                        'Baptism'  => 'The baptism schedule for this date is already reserved. Please pick a different weekend date.',
                        default    => 'All funeral times for this date are fully booked. Please choose another available date.',
                    };
            } elseif (!in_array($formData['reservation-time'], $availableValues, true)) {
                $error = 'The selected time is no longer available. Please choose another available slot.';
            }
        }

        if (!$error) {
            if ($formData['reservation-type'] === 'Wedding') {
                if (empty($formData['wedding-bride-name-first']) || empty($formData['wedding-bride-name-last']) ||
                    empty($formData['wedding-groom-name-first']) || empty($formData['wedding-groom-name-last'])) {
                    $error = 'Please provide the names of both individuals getting married.';
                } elseif (empty($formData['wedding-seminar-date'])) {
                    $error = 'Please enter the seminar date.';
                } else {
                    $seminarDt = DateTime::createFromFormat('Y-m-d', $formData['wedding-seminar-date']);
                    $reservationDt = $normalizedDate ? DateTime::createFromFormat('Y-m-d', $normalizedDate) : null;
                    if (!$seminarDt) {
                        $error = 'Please enter a valid seminar date.';
                    } elseif ($reservationDt) {
                        $seminarDt->setTime(0,0,0); $reservationDt->setTime(0,0,0);
                        $earliest = (clone $reservationDt)->modify('-5 days');
                        $latest   = (clone $reservationDt)->modify('-1 day');
                        if ($seminarDt < $earliest || $seminarDt > $latest) {
                            $error = 'The seminar date must be between one and five days before your wedding date.';
                        }
                    }
                }
                if (!$error && count(array_diff(array_keys($checklist), $selectedRequirements)) > 0) {
                    $error = 'Please confirm all pre-wedding requirements.';
                }
            } elseif ($formData['reservation-type'] === 'Funeral') {
                $maritalOptions = $this->getFuneralMaritalStatusOptions();
                if (empty($formData['funeral-deceased-name-first']) || empty($formData['funeral-deceased-name-last'])) {
                    $error = 'Please provide the name of the deceased.';
                } elseif (!array_key_exists($formData['funeral-marital-status'], $maritalOptions)) {
                    $error = 'Please select the marital status of the deceased.';
                }
            }
        }

        return [$error, $normalizedDate];
    }

    public function determineAvailableSlots(string $eventType, string $date, array $usage): array
    {
        $result = ['slots' => [], 'reason' => ''];
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        if (!$dt) return $result;

        $dow = (int)$dt->format('w');
        $key = strtolower($eventType);
        $taken = (array)($usage[$date][$eventType] ?? []);

        if ($key === 'wedding') {
            if ($dow === 0) { $result['reason'] = 'day_not_allowed'; return $result; }
            if (in_array(self::UNKNOWN_SLOT, $taken, true)) { $result['reason'] = 'fully_booked'; return $result; }
            $takenSet = array_flip($taken);
            $morning   = '7:30 AM - 10:00 AM';
            $afternoon = '3:00 PM - 5:00 PM';
            if (!isset($takenSet[$morning]))   $result['slots'][] = ['value' => $morning,   'label' => '7:30 AM – 10:00 AM'];
            if (isset($takenSet[$morning]) && !isset($takenSet[$afternoon])) $result['slots'][] = ['value' => $afternoon, 'label' => '3:00 PM – 5:00 PM'];
            if (empty($result['slots'])) $result['reason'] = !empty($taken) ? 'fully_booked' : 'day_not_allowed';
        } elseif ($key === 'baptism') {
            if (!in_array($dow, [0, 6], true)) { $result['reason'] = 'day_not_allowed'; return $result; }
            if (in_array(self::UNKNOWN_SLOT, $taken, true)) { $result['reason'] = 'fully_booked'; return $result; }
            $slot = '11:00 AM - 12:00 PM';
            if (!in_array($slot, $taken, true)) $result['slots'][] = ['value' => $slot, 'label' => '11:00 AM – 12:00 PM'];
            else $result['reason'] = 'fully_booked';
        } elseif ($key === 'funeral') {
            if (in_array(self::UNKNOWN_SLOT, $taken, true)) { $result['reason'] = 'fully_booked'; return $result; }
            $base = in_array($dow, [0, 1], true) ? ['1:00 PM', '2:00 PM'] : ['8:00 AM', '9:00 AM', '10:00 AM'];
            $takenSet = array_flip($taken);
            foreach ($base as $s) {
                if (!isset($takenSet[$s])) $result['slots'][] = ['value' => $s, 'label' => $s];
            }
            if (empty($result['slots'])) $result['reason'] = 'fully_booked';
        }

        return $result;
    }

    public function determineRequiredAttachments(string $eventType, array $formData): array
    {
        $sets = $this->getAttachmentRequirementSets();
        if (!isset($sets[$eventType]['documents'])) return [];

        $required = [];
        foreach ($sets[$eventType]['documents'] as $field => $config) {
            $isRequired = true;
            if (isset($config['conditional'])) {
                $cf = $config['conditional']['field'] ?? '';
                $cv = $config['conditional']['value'] ?? null;
                $fv = (string)($formData[$cf] ?? '');
                $isRequired = is_array($cv) ? in_array($fv, array_map('strval', $cv), true) : ($cv !== null ? $fv === (string)$cv : $fv !== '');
            }
            if ($isRequired) $required[$field] = $config['label'] ?? $field;
        }

        return $required;
    }

    public function handleFileUploads(Request $request, array $required): array
    {
        $error = '';
        $uploaded = [];
        $max = 5 * 1024 * 1024;
        $allowed = ['application/pdf' => 'pdf', 'image/jpeg' => 'jpg', 'image/png' => 'png'];

        foreach ($required as $field => $label) {
            if (!$request->hasFile($field)) {
                $error = "Please upload the required document: {$label}.";
                break;
            }
            $file = $request->file($field);
            if ($file->getSize() > $max) { $error = "Each document must be 5MB or smaller. \"{$label}\" exceeds the size limit."; break; }
            if (!array_key_exists($file->getMimeType(), $allowed)) { $error = "Only PDF, JPG, and PNG files are accepted for \"{$label}\"."; break; }

            $ext = $allowed[$file->getMimeType()];
            $baseName = preg_replace('/[^A-Za-z0-9_-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $finalName = ($baseName ?: 'document') . '_' . uniqid('', true) . '.' . $ext;

            $file->storeAs('reservations', $finalName, 'public');

            $uploaded[] = [
                'field'       => $field,
                'label'       => $label,
                'filename'    => $finalName,
                'stored_path' => 'reservations/' . $finalName,
            ];
        }

        if ($error) { $this->cleanupFiles($uploaded); return [$error, []]; }
        return ['', $uploaded];
    }

    public function saveReservation($customer, array $formData, string $normalizedDate, array $uploadedFiles, array $selectedRequirements): int
    {
        $checklist = $this->getWeddingChecklist();
        $maritalOptions = $this->getFuneralMaritalStatusOptions();

        // Append structured notes
        $notes = trim($formData['reservation-notes'] ?? '');

        if ($formData['reservation-type'] === 'Wedding') {
            $lines = [
                'Wedding details:',
                '- Bride: ' . $formData['wedding-bride-name'],
                '- Groom: ' . $formData['wedding-groom-name'],
            ];
            if (!empty($formData['wedding-seminar-date'])) $lines[] = '- Seminar date: ' . $formData['wedding-seminar-date'];
            $lines[] = '- Kumpisa/Kumpil/Binyag details: ' . ($formData['wedding-sacrament-details'] ?: 'Not specified');
            if (!empty($selectedRequirements)) {
                $labels = array_map(fn($k) => $checklist[$k] ?? $k, $selectedRequirements);
                $lines[] = '- Requirements confirmed: ' . implode(', ', $labels);
            }
            $notes = ($notes ? $notes . "\n\n" : '') . implode("\n", $lines);
        } elseif ($formData['reservation-type'] === 'Funeral') {
            $lines = [
                'Funeral details:',
                '- Deceased: ' . $formData['funeral-deceased-name'],
                '- Marital status: ' . ($maritalOptions[$formData['funeral-marital-status']] ?? $formData['funeral-marital-status']),
                '- Reminder: Arrange the schedule at the parish office at least a day before the burial.',
            ];
            $notes = ($notes ? $notes . "\n\n" : '') . implode("\n", $lines);
        }

        $reservationId = DB::table('reservations')->insertGetId([
            'customer_id'     => $customer->id,
            'name'            => $formData['reservation-name'],
            'email'           => $formData['reservation-email'],
            'phone'           => $formData['reservation-phone'],
            'event_type'      => $formData['reservation-type'],
            'reservation_date'=> $normalizedDate,
            'reservation_time'=> $formData['reservation-time'],
            'notes'           => $notes,
        ]);

        if (!empty($uploadedFiles)) {
            $rows = array_map(fn($f) => [
                'reservation_id' => $reservationId,
                'field_key'      => $f['field'],
                'label'          => $f['label'],
                'file_name'      => $f['filename'],
                'stored_path'    => $f['stored_path'],
            ], $uploadedFiles);
            DB::table('reservation_attachments')->insert($rows);
        }

        return $reservationId;
    }

    public function buildReservationDetails(array $formData, string $date, array $files, array $requirements): array
    {
        $checklist     = $this->getWeddingChecklist();
        $maritalOptions = $this->getFuneralMaritalStatusOptions();

        $details = [
            'name'           => e($formData['reservation-name']),
            'email'          => e($formData['reservation-email']),
            'email_raw'      => $formData['reservation-email'],
            'phone'          => e($formData['reservation-phone']),
            'event_type'     => e($formData['reservation-type']),
            'preferred_date' => e($date),
            'preferred_time' => e($formData['reservation-time']),
            'notes_html'     => $formData['reservation-notes'] ? nl2br(e($formData['reservation-notes'])) : '<em>No additional notes provided.</em>',
            'notes_text'     => strip_tags($formData['reservation-notes'] ?? '') ?: 'No additional notes provided.',
            'attachments'    => $files,
        ];

        if ($formData['reservation-type'] === 'Wedding') {
            $details['wedding_details'] = [
                'bride_name'       => e($formData['wedding-bride-name']),
                'groom_name'       => e($formData['wedding-groom-name']),
                'seminar_date'     => e($formData['wedding-seminar-date']),
                'sacrament_details'=> e($formData['wedding-sacrament-details']),
                'requirements'     => array_map(fn($k) => e($checklist[$k] ?? $k), $requirements),
            ];
        } elseif ($formData['reservation-type'] === 'Funeral') {
            $details['funeral_details'] = [
                'deceased_name'  => e($formData['funeral-deceased-name']),
                'marital_status' => e($maritalOptions[$formData['funeral-marital-status']] ?? ''),
                'office_reminder'=> e('Arrange the schedule at the parish office at least a day before the burial.'),
            ];
        }

        return $details;
    }

    public function cleanupFiles(array $files): void
    {
        foreach ($files as $f) {
            $path = $f['stored_path'] ?? '';
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}