<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reservation Confirmation</title>

    <style>
        @media screen and (max-width:600px) {
            body {
                margin: 0 !important;
                padding: 0 !important;
            }

            .email-wrap {
                padding: 0 !important;
            }

            .email-card {
                width: 100% !important;
                max-width: 100% !important;
                border-radius: 0 !important;
            }

            .email-hero {
                padding: 34px 24px !important;
                border-radius: 0 !important;
            }

            .email-body {
                padding: 28px 22px !important;
            }

            .stack-column {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 0 14px !important;
            }

            .summary-label,
            .summary-value {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .summary-label {
                padding: 16px 22px 4px !important;
                border-bottom: 0 !important;
            }

            .summary-value {
                padding: 4px 22px 16px !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; background:transparent; font-family:'Segoe UI',Arial,sans-serif; color:#e5e7eb;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:transparent;">
    <tr>
        <td align="center" class="email-wrap" style="padding:0;">

            <table width="100%" cellpadding="0" cellspacing="0" class="email-card"
                   style="max-width:600px; background:#111111; border-radius:24px; overflow:hidden; box-shadow:0 28px 60px rgba(0,0,0,.35);">

                <tr>
                    <td class="email-hero"
                        style="padding:36px 34px; background:linear-gradient(135deg,#5b4df5 0%,#5d6df7 45%,#4aa3ff 100%); color:#ffffff;">

                        <div style="font-size:12px; letter-spacing:4px; text-transform:uppercase; font-weight:700; opacity:.85;">
                            Reservation Received
                        </div>

                        <h1 style="margin:16px 0 0; font-size:31px; line-height:1.18; font-weight:800;">
                            Your reservation request was submitted
                        </h1>

                        <p style="margin:18px 0 0; font-size:17px; line-height:1.6; color:#eef2ff;">
                            Please wait for parish confirmation. We will notify you once your request has been reviewed.
                        </p>

                        <div style="margin-top:26px;">
                            <span style="display:inline-block; padding:8px 18px; border-radius:999px; background:rgba(49,46,129,.45); color:#ffffff; font-size:12px; letter-spacing:2px; text-transform:uppercase; font-weight:700;">
                                {{ $data['event_type'] ?? 'Reservation' }}
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="email-body" style="padding:34px 34px; background:#111111;">

                        <p style="margin:0 0 20px; color:#e5e7eb; font-size:16px; line-height:1.7;">
                            Hello {{ $data['name'] ?? 'there' }},
                        </p>

                        <p style="margin:0 0 28px; color:#cbd5e1; font-size:16px; line-height:1.7;">
                            We received your reservation request. Please review the details below while waiting for approval.
                        </p>

                        <div style="margin-bottom:26px;">
                            <span style="display:inline-block; padding:9px 18px; border-radius:999px; background:#263042; color:#c4b5fd; font-size:12px; letter-spacing:2px; text-transform:uppercase; font-weight:800;">
                                Pending Review
                            </span>
                        </div>

                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td class="stack-column" style="padding:0 8px 14px 0;">
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background:#1f2937; border-radius:16px;">
                                        <tr>
                                            <td style="padding:18px 20px;">
                                                <div style="font-size:12px; letter-spacing:2px; text-transform:uppercase; color:#c4b5fd; font-weight:800;">
                                                    Reservation Date
                                                </div>
                                                <div style="margin-top:10px; font-size:20px; font-weight:800; color:#ffffff;">
                                                    {{ $data['reservation_date'] ?? 'To be confirmed' }}
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td class="stack-column" style="padding:0 0 14px 8px;">
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background:#17303a; border-radius:16px;">
                                        <tr>
                                            <td style="padding:18px 20px;">
                                                <div style="font-size:12px; letter-spacing:2px; text-transform:uppercase; color:#7dd3fc; font-weight:800;">
                                                    Preferred Time
                                                </div>
                                                <div style="margin-top:10px; font-size:20px; font-weight:800; color:#ffffff;">
                                                    {{ $data['reservation_time'] ?? 'To be confirmed' }}
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <div style="border:1px solid #2f333a; border-radius:18px; overflow:hidden;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                <tr>
                                    <td colspan="2" style="padding:18px 24px; background:#1b1f24; color:#cbd5e1; font-size:13px; letter-spacing:2px; text-transform:uppercase; font-weight:800;">
                                        Reservation Summary
                                    </td>
                                </tr>

                                @foreach([
                                    'Name' => $data['name'] ?? '',
                                    'Email' => $data['email'] ?? '',
                                    'Phone' => $data['phone'] ?? '',
                                    'Event Type' => $data['event_type'] ?? '',
                                    'Preferred Date' => $data['reservation_date'] ?? '',
                                    'Preferred Time' => $data['reservation_time'] ?? '',
                                    'Notes' => $data['notes'] ?? 'No additional notes provided.',
                                ] as $label => $value)
                                    <tr>
                                        <td class="summary-label" style="padding:17px 24px; width:38%; border-top:1px solid #2f333a; color:#94a3b8; font-size:12px; letter-spacing:2px; text-transform:uppercase; font-weight:800; vertical-align:top;">
                                            {{ $label }}
                                        </td>
                                        <td class="summary-value" style="padding:17px 24px; border-top:1px solid #2f333a; color:#f8fafc; font-size:15px; font-weight:700; line-height:1.6;">
                                            {{ $value ?: '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <p style="margin:28px 0 0; color:#cbd5e1; font-size:16px; line-height:1.7;">
                            God bless,<br>
                            <strong>St. John the Baptist Parish</strong>
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:20px 32px; background:#161616; text-align:center; color:#64748b; font-size:13px;">
                        ParishSched • St. John the Baptist Parish
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>
</body>
</html>