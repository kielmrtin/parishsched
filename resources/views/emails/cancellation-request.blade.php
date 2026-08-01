<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cancellation Request</title>
    <style>
        @media screen and (max-width:600px) {
            body { margin:0 !important; padding:0 !important; }
            .email-card { width:100% !important; max-width:100% !important; border-radius:0 !important; }
            .email-hero { padding:34px 24px !important; border-radius:0 !important; }
            .email-body { padding:28px 22px !important; }
            .summary-label, .summary-value { display:block !important; width:100% !important; box-sizing:border-box !important; }
            .summary-label { padding:16px 22px 4px !important; border-bottom:0 !important; }
            .summary-value { padding:4px 22px 16px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background:transparent;font-family:'Segoe UI',Arial,sans-serif;color:#e5e7eb;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:transparent;">
    <tr>
        <td align="center">

            <table width="100%" cellpadding="0" cellspacing="0" class="email-card"
                   style="max-width:600px;background:#111111;border-radius:24px;overflow:hidden;box-shadow:0 28px 60px rgba(0,0,0,.35);">

                <!-- HERO -->
                <tr>
                    <td class="email-hero"
                        style="padding:36px 34px;background:linear-gradient(135deg,#b45309 0%,#d97706 50%,#fbbf24 100%);color:#ffffff;">

                        <div style="font-size:12px;letter-spacing:4px;text-transform:uppercase;font-weight:700;opacity:.85;">
                            Action Required
                        </div>

                        <h1 style="margin:16px 0 0;font-size:31px;line-height:1.18;font-weight:800;">
                            Cancellation Request
                        </h1>

                        <p style="margin:18px 0 0;font-size:17px;line-height:1.6;color:#fff8e1;">
                            A customer has requested to cancel their reservation. Please review and respond in the admin panel.
                        </p>

                        <div style="margin-top:26px;">
                            <span style="display:inline-block;padding:8px 18px;border-radius:999px;background:rgba(0,0,0,.2);color:#ffffff;font-size:12px;letter-spacing:2px;text-transform:uppercase;font-weight:700;">
                                Pending Review
                            </span>
                        </div>
                    </td>
                </tr>

                <!-- BODY -->
                <tr>
                    <td class="email-body" style="padding:34px 34px;background:#111111;">

                        <p style="margin:0 0 28px;color:#cbd5e1;font-size:16px;line-height:1.7;">
                            The following reservation has a cancellation request waiting for your approval.
                        </p>

                        <!-- Status highlight -->
                        <div style="background:#2f1f0a;border-radius:18px;padding:20px;margin-bottom:18px;">
                            <div style="font-size:12px;letter-spacing:2px;text-transform:uppercase;color:#fbbf24;font-weight:800;">
                                Request Status
                            </div>
                            <div style="margin-top:10px;font-size:24px;font-weight:800;color:#ffffff;">
                                Awaiting Review
                            </div>
                        </div>

                        <!-- Details table -->
                        <div style="border:1px solid #2f333a;border-radius:18px;overflow:hidden;margin-bottom:28px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                <tr>
                                    <td colspan="2" style="padding:18px 24px;background:#1b1f24;color:#cbd5e1;font-size:13px;letter-spacing:2px;text-transform:uppercase;font-weight:800;">
                                        Reservation Details
                                    </td>
                                </tr>
                                @foreach([
                                    'Customer'   => $customerName ?? '—',
                                    'Event Type' => ucfirst($eventType ?? '—'),
                                    'Date'       => $eventDate ?? '—',
                                    'Reason'     => $reason ?: 'No reason provided',
                                ] as $lbl => $val)
                                <tr>
                                    <td class="summary-label" style="padding:17px 24px;width:38%;border-top:1px solid #2f333a;color:#94a3b8;font-size:12px;letter-spacing:2px;text-transform:uppercase;font-weight:800;">
                                        {{ $lbl }}
                                    </td>
                                    <td class="summary-value" style="padding:17px 24px;border-top:1px solid #2f333a;color:#f8fafc;font-size:15px;font-weight:700;">
                                        {{ $val }}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>

                        <p style="margin:0;color:#94a3b8;font-size:14px;line-height:1.7;">
                            Log in to the admin panel to approve or deny this request. The customer will be notified by email of your decision.
                        </p>

                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td style="padding:20px 32px;background:#161616;text-align:center;color:#64748b;font-size:13px;">
                        ParishSched &bull; St. John the Baptist Parish
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
