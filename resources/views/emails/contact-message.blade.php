<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Inquiry</title>

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
                            New Inquiry
                        </div>

                        <h1 style="margin:16px 0 0; font-size:31px; line-height:1.18; font-weight:800;">
                            A new message was received
                        </h1>

                        <p style="margin:18px 0 0; font-size:17px; line-height:1.6; color:#eef2ff;">
                            Someone submitted an inquiry through the ParishSched contact form.
                        </p>

                        <div style="margin-top:26px;">
                            <span style="display:inline-block; padding:8px 18px; border-radius:999px; background:rgba(49,46,129,.45); color:#ffffff; font-size:12px; letter-spacing:2px; text-transform:uppercase; font-weight:700;">
                                Contact Message
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="email-body" style="padding:34px 34px; background:#111111;">

                        <p style="margin:0 0 28px; color:#cbd5e1; font-size:16px; line-height:1.7;">
                            Hi there, a new inquiry was submitted on the parish website.
                            Review the details below and respond when available.
                        </p>

                        <div style="border:1px solid #2f333a; border-radius:18px; overflow:hidden;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                <tr>
                                    <td colspan="2" style="padding:18px 24px; background:#1b1f24; color:#cbd5e1; font-size:13px; letter-spacing:2px; text-transform:uppercase; font-weight:800;">
                                        Inquiry Summary
                                    </td>
                                </tr>

                                <tr>
                                    <td class="summary-label" style="padding:17px 24px; width:38%; border-top:1px solid #2f333a; color:#94a3b8; font-size:12px; letter-spacing:2px; text-transform:uppercase; font-weight:800; vertical-align:top;">
                                        Name
                                    </td>
                                    <td class="summary-value" style="padding:17px 24px; border-top:1px solid #2f333a; color:#f8fafc; font-size:15px; font-weight:700; line-height:1.6;">
                                        {{ $name ?? '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="summary-label" style="padding:17px 24px; width:38%; border-top:1px solid #2f333a; color:#94a3b8; font-size:12px; letter-spacing:2px; text-transform:uppercase; font-weight:800; vertical-align:top;">
                                        Email
                                    </td>
                                    <td class="summary-value" style="padding:17px 24px; border-top:1px solid #2f333a; color:#60a5fa; font-size:15px; font-weight:700; line-height:1.6;">
                                        {{ $email ?? '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="summary-label" style="padding:17px 24px; width:38%; border-top:1px solid #2f333a; color:#94a3b8; font-size:12px; letter-spacing:2px; text-transform:uppercase; font-weight:800; vertical-align:top;">
                                        Phone
                                    </td>
                                    <td class="summary-value" style="padding:17px 24px; border-top:1px solid #2f333a; color:#f8fafc; font-size:15px; font-weight:700; line-height:1.6;">
                                        {{ $phone ?? 'Not provided' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="summary-label" style="padding:17px 24px; width:38%; border-top:1px solid #2f333a; color:#94a3b8; font-size:12px; letter-spacing:2px; text-transform:uppercase; font-weight:800; vertical-align:top;">
                                        Message
                                    </td>
                                    <td class="summary-value" style="padding:17px 24px; border-top:1px solid #2f333a; color:#f8fafc; font-size:15px; font-weight:600; line-height:1.7;">
                                        {{ $message ?? '—' }}
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <p style="margin:28px 0 0; color:#cbd5e1; font-size:16px; line-height:1.7;">
                            You may reply directly to this email or contact the sender using the provided details.
                        </p>

                    </td>
                </tr>

                <tr>
                    <td style="padding:20px 32px; background:#161616; text-align:center; color:#64748b; font-size:13px;">
                        ParishSched • Contact Inquiry
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>
</body>
</html>