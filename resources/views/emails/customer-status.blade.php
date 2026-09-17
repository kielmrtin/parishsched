<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Status Update</title>
    <style>
        @media screen and (max-width:600px) {
            body { margin:0 !important; padding:0 !important; }
            .email-wrap { padding:0 !important; }
            .email-card { width:100% !important; max-width:100% !important; }
            .email-hero, .email-footer { border-radius:0 !important; }
            .email-hero { padding:20px 22px !important; }
            .email-body { padding:18px 20px !important; }
            .summary-label,
            .summary-value {
                display:block !important;
                width:100% !important;
                box-sizing:border-box !important;
            }
            .summary-label { padding:11px 16px 3px !important; border-bottom:0 !important; }
            .summary-value { padding:3px 16px 11px !important; }
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

            <table width="100%" cellpadding="0" cellspacing="0" class="email-card" style="max-width:400px;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="background:#111111;padding:0;border-radius:14px;overflow:hidden;">

                        <!-- HERO -->
                        <div class="email-hero" style="padding:22px 24px;background:{{ $heroGradient }};color:#ffffff;border-radius:14px 14px 0 0;">

                            <div style="font-size:9px;letter-spacing:2.5px;text-transform:uppercase;font-weight:700;opacity:.85;">
                                Account Update
                            </div>

                            <h1 style="margin:10px 0 0;font-size:17px;line-height:1.3;font-weight:800;">
                                {{ $title }}
                            </h1>

                            <p style="margin:10px 0 0;font-size:12.5px;line-height:1.55;color:#eef2ff;">
                                {{ $subtitle }}
                            </p>

                            <div style="margin-top:14px;">
                                <span style="display:inline-block;padding:5px 13px;border-radius:999px;background:{{ $badgeColor }};color:#ffffff;font-size:9.5px;letter-spacing:1.2px;text-transform:uppercase;font-weight:700;">
                                    {{ $label }}
                                </span>
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="email-body" style="padding:20px 22px;">

                            <p style="margin:0 0 14px;color:#e5e7eb;font-size:12.5px;line-height:1.6;">
                                Hello {{ $name ?? 'there' }},
                            </p>

                            <p style="margin:0 0 18px;color:#cbd5e1;font-size:12.5px;line-height:1.6;">
                                {{ $bodyLine }}
                            </p>

                            <!-- Status highlight box -->
                            <div style="background:{{ $statusBg }};border-radius:10px;padding:12px 16px;margin-bottom:14px;">
                                <div style="font-size:9.5px;letter-spacing:1.2px;text-transform:uppercase;color:{{ $statusAccent }};font-weight:800;">
                                    Account Status
                                </div>
                                <div style="margin-top:6px;font-size:15px;font-weight:800;color:#ffffff;">
                                    {{ $label }}
                                </div>
                            </div>

                            <!-- Summary table -->
                            <div style="border:1px solid #2f333a;border-radius:10px;overflow:hidden;margin-bottom:18px;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                    <tr>
                                        <td colspan="2" style="padding:11px 16px;background:#1b1f24;color:#cbd5e1;font-size:10.5px;letter-spacing:1.2px;text-transform:uppercase;font-weight:800;">
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
                                        <td class="summary-label" style="padding:11px 16px;width:38%;border-top:1px solid #2f333a;color:#94a3b8;font-size:9.5px;letter-spacing:1.2px;text-transform:uppercase;font-weight:800;">
                                            {{ $rowLabel }}
                                        </td>
                                        <td class="summary-value" style="padding:11px 16px;border-top:1px solid #2f333a;color:{{ $rowLabel === 'Status' ? $statusAccent : '#f8fafc' }};font-size:12.5px;font-weight:700;">
                                            {{ $value ?: '—' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>

                            @if($statusText === 'disabled')
                            <p style="margin:0;color:#cbd5e1;font-size:12.5px;line-height:1.6;">
                                To appeal this decision or request more information, please visit or call the parish office.
                            </p>
                            @else
                            <p style="margin:0;color:#cbd5e1;font-size:12.5px;line-height:1.6;">
                                If you experience any issues logging in, please contact the parish office for assistance.
                            </p>
                            @endif

                        </div>

                        <!-- FOOTER -->
                        <div class="email-footer" style="padding:13px 22px;background:#161616;text-align:center;color:#64748b;font-size:10.5px;border-radius:0 0 14px 14px;">
                            ParishSched &bull; St. John the Baptist Parish
                        </div>

                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
</body>
</html>
