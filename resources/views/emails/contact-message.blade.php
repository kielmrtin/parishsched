<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Inquiry — ParishSched</title>
    <style>
        @media screen and (max-width:600px) {
            body { margin:0 !important; padding:0 !important; }
            .email-card { width:100% !important; max-width:100% !important; }
            .email-hero, .email-footer { border-radius:0 !important; }
            .email-hero { padding:20px 22px !important; }
            .email-body { padding:18px 20px !important; }
            .summary-label, .summary-value { display:block !important; width:100% !important; box-sizing:border-box !important; }
            .summary-label { padding:11px 16px 3px !important; border-bottom:0 !important; }
            .summary-value { padding:3px 16px 11px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background:transparent;font-family:'Segoe UI',Arial,sans-serif;color:#e5e7eb;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:transparent;">
    <tr>
        <td align="center">

            <table width="100%" cellpadding="0" cellspacing="0" class="email-card" style="max-width:400px;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="background:#111111;padding:0;border-radius:14px;overflow:hidden;">

                        <!-- HERO -->
                        <div class="email-hero" style="padding:22px 24px;background:linear-gradient(135deg,#6d28d9 0%,#7c3aed 50%,#a78bfa 100%);color:#ffffff;border-radius:14px 14px 0 0;">

                            <div style="font-size:9px;letter-spacing:2.5px;text-transform:uppercase;font-weight:700;opacity:.85;">
                                Contact Inquiry
                            </div>

                            <h1 style="margin:10px 0 0;font-size:17px;line-height:1.3;font-weight:800;">
                                New Message Received
                            </h1>

                            <p style="margin:10px 0 0;font-size:12.5px;line-height:1.55;color:#ede9fe;">
                                Someone submitted an inquiry through the ParishSched contact form. Please review and respond at your earliest convenience.
                            </p>

                            <div style="margin-top:14px;">
                                <span style="display:inline-block;padding:5px 13px;border-radius:999px;background:rgba(0,0,0,.2);color:#ffffff;font-size:9.5px;letter-spacing:1.2px;text-transform:uppercase;font-weight:700;">
                                    Awaiting Response
                                </span>
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="email-body" style="padding:20px 22px;">

                            <p style="margin:0 0 18px;color:#cbd5e1;font-size:12.5px;line-height:1.6;">
                                The following inquiry was submitted on the parish website.
                            </p>

                            <!-- Status highlight -->
                            <div style="background:#1e1030;border-radius:10px;padding:12px 16px;margin-bottom:14px;">
                                <div style="font-size:9.5px;letter-spacing:1.2px;text-transform:uppercase;color:#a78bfa;font-weight:800;">
                                    From
                                </div>
                                <div style="margin-top:6px;font-size:15px;font-weight:800;color:#ffffff;">
                                    {{ $name ?? '—' }}
                                </div>
                                <div style="margin-top:3px;font-size:12px;color:#c4b5fd;">
                                    {{ $email ?? '' }}@if(!empty($phone)) &bull; {{ $phone }}@endif
                                </div>
                            </div>

                            <!-- Details table -->
                            <div style="border:1px solid #2f333a;border-radius:10px;overflow:hidden;margin-bottom:18px;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                    <tr>
                                        <td colspan="2" style="padding:11px 16px;background:#1b1f24;color:#cbd5e1;font-size:10.5px;letter-spacing:1.2px;text-transform:uppercase;font-weight:800;">
                                            Inquiry Details
                                        </td>
                                    </tr>
                                    @foreach([
                                        'Name'    => $name ?? '—',
                                        'Email'   => $email ?? '—',
                                        'Phone'   => $phone ?: 'Not provided',
                                        'Message' => $bodyMessage ?? '—',
                                    ] as $lbl => $val)
                                    <tr>
                                        <td class="summary-label" style="padding:11px 16px;width:38%;border-top:1px solid #2f333a;color:#94a3b8;font-size:9.5px;letter-spacing:1.2px;text-transform:uppercase;font-weight:800;vertical-align:top;">
                                            {{ $lbl }}
                                        </td>
                                        <td class="summary-value" style="padding:11px 16px;border-top:1px solid #2f333a;color:#f8fafc;font-size:12.5px;font-weight:700;line-height:1.6;">
                                            {{ $val }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>

                            <p style="margin:0;color:#94a3b8;font-size:11px;line-height:1.55;">
                                You may reply directly to this email or contact the sender using the details above.
                            </p>

                        </div>

                        <!-- FOOTER -->
                        <div class="email-footer" style="padding:13px 22px;background:#161616;text-align:center;color:#64748b;font-size:10.5px;border-radius:0 0 14px 14px;">
                            ParishSched &bull; St. John the Baptist Parish &bull; Tiaong, Quezon
                        </div>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
