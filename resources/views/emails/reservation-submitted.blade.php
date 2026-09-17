<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Reservation Submitted</title>

    <style>
        @media screen and (max-width:600px) {
            body { margin: 0 !important; padding: 0 !important; }
            .email-wrap { padding: 0 !important; }
            .email-card { width: 100% !important; max-width: 100% !important; }
            .email-hero, .email-footer { border-radius: 0 !important; }
            .email-hero { padding: 20px 22px !important; }
            .email-body { padding: 18px 20px !important; }

            .stack-column {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 0 10px !important;
            }

            .summary-label,
            .summary-value {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .summary-label { padding: 11px 16px 3px !important; border-bottom: 0 !important; }
            .summary-value { padding: 3px 16px 11px !important; }
        }
    </style>
</head>

<body style="margin:0; padding:0; background:transparent; font-family:'Segoe UI',Arial,sans-serif; color:#e5e7eb;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:transparent;">
    <tr>
        <td align="center" class="email-wrap" style="padding:0;">

            <table width="100%" cellpadding="0" cellspacing="0" class="email-card" style="max-width:400px;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="background:#111111;padding:0;border-radius:14px;overflow:hidden;">

                        <!-- HERO -->
                        <div class="email-hero" style="padding:22px 24px; background:linear-gradient(135deg,#5b4df5 0%,#5d6df7 45%,#4aa3ff 100%); color:#ffffff; border-radius:14px 14px 0 0;">

                            <div style="font-size:9px; letter-spacing:2.5px; text-transform:uppercase; font-weight:700; opacity:.85;">
                                New Reservation
                            </div>

                            <h1 style="margin:10px 0 0; font-size:17px; line-height:1.3; font-weight:800;">
                                A new reservation just arrived
                            </h1>

                            <p style="margin:10px 0 0; font-size:12.5px; line-height:1.55; color:#eef2ff;">
                                Review the request below and follow up when you're ready.
                            </p>

                            <div style="margin-top:14px;">
                                <span style="display:inline-block; padding:5px 13px; border-radius:999px; background:rgba(49,46,129,.45); color:#ffffff; font-size:9.5px; letter-spacing:1.2px; text-transform:uppercase; font-weight:700;">
                                    {{ $data['event_type'] ?? 'Reservation' }}
                                </span>
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="email-body" style="padding:20px 22px;">

                            <p style="margin:0 0 14px; color:#e5e7eb; font-size:12.5px; line-height:1.6;">
                                Hi there,
                            </p>

                            <p style="margin:0 0 18px; color:#cbd5e1; font-size:12.5px; line-height:1.6;">
                                A new reservation was submitted on the parish website.
                                Use the summary below to review the request and coordinate the next steps.
                            </p>

                            <div style="margin-bottom:16px;">
                                <span style="display:inline-block; padding:6px 14px; border-radius:999px; background:#263042; color:#c4b5fd; font-size:9.5px; letter-spacing:1.2px; text-transform:uppercase; font-weight:800;">
                                    Awaiting Review
                                </span>
                            </div>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
                                <tr>
                                    <td class="stack-column" style="padding:0 6px 10px 0;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#1f2937; border-radius:10px;">
                                            <tr>
                                                <td style="padding:12px 15px;">
                                                    <div style="font-size:9.5px; letter-spacing:1.2px; text-transform:uppercase; color:#c4b5fd; font-weight:800;">
                                                        Reservation Date
                                                    </div>
                                                    <div style="margin-top:6px; font-size:15px; font-weight:800; color:#ffffff;">
                                                        {{ $data['reservation_date'] ?? 'To be confirmed' }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>

                                    <td class="stack-column" style="padding:0 0 10px 6px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#17303a; border-radius:10px;">
                                            <tr>
                                                <td style="padding:12px 15px;">
                                                    <div style="font-size:9.5px; letter-spacing:1.2px; text-transform:uppercase; color:#7dd3fc; font-weight:800;">
                                                        Preferred Time
                                                    </div>
                                                    <div style="margin-top:6px; font-size:15px; font-weight:800; color:#ffffff;">
                                                        {{ $data['reservation_time'] ?? 'To be confirmed' }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <div style="border:1px solid #2f333a; border-radius:10px; overflow:hidden;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                    <tr>
                                        <td colspan="2" style="padding:11px 16px; background:#1b1f24; color:#cbd5e1; font-size:10.5px; letter-spacing:1.2px; text-transform:uppercase; font-weight:800;">
                                            Reservation Summary
                                        </td>
                                    </tr>

                                    @php
                                        $eventType = strtolower($data['event_type'] ?? '');
                                        $eventFields = [];
                                        if ($eventType === 'baptism' && !empty($data['baptism'])) {
                                            $b = $data['baptism'];
                                            $eventFields = [
                                                "Child's Name" => $b['child_name'] ?? trim(($b['child_first'] ?? '') . ' ' . ($b['child_last'] ?? '')),
                                                "Child's Birth Date" => $b['child_dob'] ?? '',
                                                "Father's Name" => $b['father_name'] ?? '',
                                                "Mother's Name" => $b['mother_name'] ?? '',
                                            ];
                                        } elseif ($eventType === 'wedding' && !empty($data['wedding'])) {
                                            $w = $data['wedding'];
                                            $eventFields = [
                                                'Groom' => trim(implode(' ', array_filter([$w['groom_first'] ?? '', $w['groom_middle'] ?? '', $w['groom_last'] ?? '']))) . (!empty($w['groom_suffix']) ? ', ' . $w['groom_suffix'] : ''),
                                                'Bride' => trim(implode(' ', array_filter([$w['bride_first'] ?? '', $w['bride_middle'] ?? '', $w['bride_last'] ?? '']))) . (!empty($w['bride_suffix']) ? ', ' . $w['bride_suffix'] : ''),
                                                'Pre-Cana Seminar Date' => $w['seminar_date'] ?? '',
                                                'Sacrament Details' => $w['sacrament_details'] ?? '',
                                            ];
                                        } elseif ($eventType === 'funeral' && !empty($data['funeral'])) {
                                            $f = $data['funeral'];
                                            $eventFields = [
                                                'Deceased Name' => trim(implode(' ', array_filter([$f['deceased_first'] ?? '', $f['deceased_middle'] ?? '', $f['deceased_last'] ?? '']))) . (!empty($f['deceased_suffix']) ? ', ' . $f['deceased_suffix'] : ''),
                                                'Marital Status' => $f['marital_status'] ?? '',
                                            ];
                                        }
                                    @endphp
                                    @foreach(array_merge([
                                        'Name' => $data['name'] ?? '',
                                        'Email' => $data['email'] ?? '',
                                        'Phone' => $data['phone'] ?? '',
                                        'Event Type' => $data['event_type'] ?? '',
                                        'Preferred Date' => $data['reservation_date'] ?? '',
                                        'Preferred Time' => $data['reservation_time'] ?? '',
                                    ], $eventFields, [
                                        'Notes' => $data['notes'] ?? 'No additional notes provided.',
                                    ]) as $label => $value)
                                        <tr>
                                            <td class="summary-label" style="padding:11px 16px; width:38%; border-top:1px solid #2f333a; color:#94a3b8; font-size:9.5px; letter-spacing:1.2px; text-transform:uppercase; font-weight:800; vertical-align:top;">
                                                {{ $label }}
                                            </td>
                                            <td class="summary-value" style="padding:11px 16px; border-top:1px solid #2f333a; color:#f8fafc; font-size:12.5px; font-weight:700; line-height:1.5;">
                                                {{ $value ?: '—' }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if(!empty($files))
                                        <tr>
                                            <td class="summary-label" style="padding:11px 16px; width:38%; border-top:1px solid #2f333a; color:#94a3b8; font-size:9.5px; letter-spacing:1.2px; text-transform:uppercase; font-weight:800; vertical-align:top;">
                                                Uploaded Documents
                                            </td>
                                            <td class="summary-value" style="padding:11px 16px; border-top:1px solid #2f333a; color:#f8fafc; font-size:12.5px; line-height:1.55;">
                                                <ul style="margin:0; padding-left:16px;">
                                                    @foreach($files as $file)
                                                        <li>{{ $file['label'] ?? 'Attachment' }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>

                            <p style="margin:20px 0 0; color:#cbd5e1; font-size:12.5px; line-height:1.6;">
                                Please review this request in the admin dashboard and update the reservation status when ready.
                            </p>
                        </div>

                        <!-- FOOTER -->
                        <div class="email-footer" style="padding:13px 22px; background:#161616; text-align:center; color:#64748b; font-size:10.5px; border-radius:0 0 14px 14px;">
                            St. John the Baptist Parish • Reservation desk
                        </div>

                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
</body>
</html>
