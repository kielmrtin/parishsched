<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Status Update</title>
    <style>
        @media screen and (max-width:600px) {
            body { margin:0 !important; padding:0 !important; }
            .email-wrap { padding:0 !important; }
            .email-card { width:100% !important; max-width:100% !important; border-radius:0 !important; }
            .email-hero { padding:34px 24px !important; border-radius:0 !important; }
            .email-body { padding:28px 22px !important; }
            .summary-label,
            .summary-value {
                display:block !important;
                width:100% !important;
                box-sizing:border-box !important;
            }
            .summary-label { padding:16px 22px 4px !important; border-bottom:0 !important; }
            .summary-value { padding:4px 22px 16px !important; }
        }
    </style>
</head>

@php
$statusText = strtolower($status ?? 'active');

if ($statusText === 'disabled') {
    $heroGradient  = 'linear-gradient(135deg,#dc2626 0%,#ef4444 50%,#f87171 100%)';
    $badgeColor    = 'rgba(248,113,113,.25)';
    $statusBg      = '#2f0f0f';
    $statusAccent  = '#ef4444';
    $title         = 'Your account has been disabled';
    $subtitle      = 'Your account has been temporarily restricted by the parish administrator.';
    $label         = 'Disabled';
    $bodyLine      = 'Your access to ParishSched has been suspended. If you believe this is a mistake, please contact the parish office directly.';
} else {
    $heroGradient  = 'linear-gradient(135deg,#16a34a 0%,#22c55e 50%,#4ade80 100%)';
    $badgeColor    = 'rgba(34,197,94,.25)';
    $statusBg      = '#0f2f1c';
    $statusAccent  = '#22c55e';
    $title         = 'Your account has been re-enabled';
    $subtitle      = 'You can now access your account again.';
    $label         = 'Active';
    $bodyLine      = 'Your account has been successfully re-enabled. You may now log in again.';
}
@endphp

<body style="margin:0;padding:0;background:transparent;font-family:'Segoe UI',Arial,sans-serif;color:#e5e7eb;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:transparent;">
    <tr>
        <td align="center">

            <table width="100%" cellpadding="0" cellspacing="0" class="email-card"
                   style="max-width:600px;background:#111111;border-radius:24px;overflow:hidden;box-shadow:0 28px 60px rgba(0,0,0,.35);">

                <!-- HERO -->
                <tr>
                    <td class="email-hero"
                        style="padding:36px 34px;background:{{ $heroGradient }};color:#ffffff;">

                        <div style="font-size:12px;letter-spacing:4px;text-transform:uppercase;font-weight:700;opacity:.85;">
                            Account Update
                        </div>

                        <h1 style="margin:16px 0 0;font-size:31px;line-height:1.18;font-weight:800;">
                            {{ $title }}
                        </h1>

                        <p style="margin:18px 0 0;font-size:17px;line-height:1.6;color:#eef2ff;">
                            {{ $subtitle }}
                        </p>

                        <div style="margin-top:26px;">
                            <span style="display:inline-block;padding:8px 18px;border-radius:999px;background:{{ $badgeColor }};color:#ffffff;font-size:12px;letter-spacing:2px;text-transform:uppercase;font-weight:700;">
                                {{ $label }}
                            </span>
                        </div>
                    </td>
                </tr>

                <!-- BODY -->
                <tr>
                    <td class="email-body" style="padding:34px 34px;background:#111111;">

                                        <p style="margin:0 0 20px;color:#e5e7eb;font-size:16px;line-height:1.7;">
                            Hello {{ $name ?? 'there' }},
                        </p>

                        <p style="margin:0 0 28px;color:#cbd5e1;font-size:16px;line-height:1.7;">
                            {{ $bodyLine }}
                        </p>

                        <!-- Status highlight box -->
                        <div style="background:{{ $statusBg }};border-radius:18px;padding:20px;margin-bottom:24px;">
                            <div style="font-size:12px;letter-spacing:2px;text-transform:uppercase;color:{{ $statusAccent }};font-weight:800;">
                                Account Status
                            </div>
                            <div style="margin-top:10px;font-size:24px;font-weight:800;color:#ffffff;">
                                {{ $label }}
                            </div>
                        </div>

                        <!-- Summary table -->
                        <div style="border:1px solid #2f333a;border-radius:18px;overflow:hidden;margin-bottom:28px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                <tr>
                                    <td colspan="2" style="padding:18px 24px;background:#1b1f24;color:#cbd5e1;font-size:13px;letter-spacing:2px;text-transform:uppercase;font-weight:800;">
                                        Account Details
                                    </td>
                                </tr>

                                @php
                                $rows = ['Name' => $name ?? '—', 'Status' => $label];
                                if ($statusText === 'disabled' && !empty($reason)) {
                                    $rows['Reason'] = $reason;
                                }
                                @endphp

                                @foreach($rows as $rowLabel => $value)
                                <tr>
                                    <td class="summary-label" style="padding:17px 24px;width:38%;border-top:1px solid #2f333a;color:#94a3b8;font-size:12px;letter-spacing:2px;text-transform:uppercase;font-weight:800;">
                                        {{ $rowLabel }}
                                    </td>
                                    <td class="summary-value" style="padding:17px 24px;border-top:1px solid #2f333a;color:{{ $rowLabel === 'Status' ? $statusAccent : '#f8fafc' }};font-size:15px;font-weight:700;">
                                        {{ $value ?: '—' }}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>

                        @if($statusText === 'disabled')
                        <p style="margin:0;color:#cbd5e1;font-size:15px;line-height:1.7;">
                            To appeal this decision or request more information, please visit or call the parish office.
                        </p>
                        @else
                        <p style="margin:0;color:#cbd5e1;font-size:15px;line-height:1.7;">
                            If you experience any issues logging in, please contact the parish office for assistance.
                        </p>
                        @endif

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
