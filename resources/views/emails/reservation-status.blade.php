<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reservation Status Update</title>

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
$statusText = strtolower($status ?? 'pending');

if ($statusText === 'approved') {
    $heroGradient = 'linear-gradient(135deg,#16a34a 0%,#22c55e 50%,#4ade80 100%)';
    $badgeColor = 'rgba(34,197,94,.25)';
    $title = 'Your reservation is approved';
    $subtitle = 'Great news! Your reservation request has been approved and is now on our schedule.';
    $label = 'Approved';
}
elseif ($statusText === 'declined') {
    $heroGradient = 'linear-gradient(135deg,#dc2626 0%,#ef4444 50%,#f87171 100%)';
    $badgeColor = 'rgba(248,113,113,.25)';
    $title = 'We have an update on your reservation';
    $subtitle = 'After reviewing your request, we are unable to confirm the reservation as submitted.';
    $label = 'Declined';
}
elseif ($statusText === 'cancelled') {
    $heroGradient = 'linear-gradient(135deg,#475569 0%,#64748b 50%,#94a3b8 100%)';
    $badgeColor = 'rgba(148,163,184,.25)';
    $title = 'Your reservation has been cancelled';
    $subtitle = 'Your cancellation request has been approved. Your reservation is now cancelled.';
    $label = 'Cancelled';
}
elseif ($statusText === 'cancellation_denied') {
    $heroGradient = 'linear-gradient(135deg,#b45309 0%,#d97706 50%,#fbbf24 100%)';
    $badgeColor = 'rgba(251,191,36,.25)';
    $title = 'Your cancellation request was not approved';
    $subtitle = 'The parish office has reviewed your request and your reservation will remain as scheduled.';
    $label = 'Cancellation Denied';
}
else {
    $heroGradient = 'linear-gradient(135deg,#eab308 0%,#facc15 50%,#fde047 100%)';
    $badgeColor = 'rgba(250,204,21,.25)';
    $title = 'Your reservation is pending';
    $subtitle = 'Your reservation is currently under review.';
    $label = 'Pending';
}
@endphp

<body style="margin:0; padding:0; background:transparent; font-family:'Segoe UI',Arial,sans-serif; color:#e5e7eb;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:transparent;">
    <tr>
        <td align="center">

            <table width="100%" cellpadding="0" cellspacing="0" class="email-card" style="max-width:400px;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="background:#111111;padding:0;border-radius:14px;overflow:hidden;">

                        <!-- HERO -->
                        <div class="email-hero" style="padding:22px 24px; background:{{ $heroGradient }}; color:#ffffff; border-radius:14px 14px 0 0;">

                            <div style="font-size:9px; letter-spacing:2.5px; text-transform:uppercase; font-weight:700; opacity:.85;">
                                Reservation Update
                            </div>

                            <h1 style="margin:10px 0 0; font-size:17px; line-height:1.3; font-weight:800;">
                               {{ $title }}
                            </h1>

                            <p style="margin:10px 0 0; font-size:12.5px; line-height:1.55; color:#eef2ff;">
                                {{ $subtitle }}
                            </p>

                            <div style="margin-top:14px;">
                                <span style="display:inline-block; padding:5px 13px; border-radius:999px; background:{{ $badgeColor }}; color:#ffffff; font-size:9.5px; letter-spacing:1.2px; text-transform:uppercase; font-weight:700;">
                                    {{ $label }}
                                </span>
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="email-body" style="padding:20px 22px;">

                            <p style="margin:0 0 14px; color:#e5e7eb; font-size:12.5px; line-height:1.6;">
                                Hello {{ $name ?? 'there' }},
                            </p>

                            <p style="margin:0 0 18px; color:#cbd5e1; font-size:12.5px; line-height:1.6;">
                                Please review the latest update for your reservation below.
                            </p>

                            @php
if ($statusText === 'approved') {
    $statusBg = '#0f2f1c';
    $statusAccent = '#22c55e';
} elseif ($statusText === 'declined') {
    $statusBg = '#2f0f0f';
    $statusAccent = '#ef4444';
} elseif ($statusText === 'cancelled') {
    $statusBg = '#1e2530';
    $statusAccent = '#94a3b8';
} elseif ($statusText === 'cancellation_denied') {
    $statusBg = '#2f1f0a';
    $statusAccent = '#fbbf24';
} else {
    $statusBg = '#2f2a0f';
    $statusAccent = '#facc15';
}
@endphp

<div style="background:{{ $statusBg }}; border-radius:10px; padding:12px 16px; margin-bottom:14px;">

    <div style="font-size:9.5px; letter-spacing:1.2px; text-transform:uppercase; color:{{ $statusAccent }}; font-weight:800;">
        Reservation Status
    </div>

    <div style="margin-top:6px; font-size:15px; font-weight:800; color:#ffffff;">
        {{ $label }}
    </div>

</div>

                            <div style="border:1px solid #2f333a; border-radius:10px; overflow:hidden;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                    <tr>
                                        <td colspan="2" style="padding:11px 16px; background:#1b1f24; color:#cbd5e1; font-size:10.5px; letter-spacing:1.2px; text-transform:uppercase; font-weight:800;">
                                            Reservation Summary
                                        </td>
                                    </tr>

                                    @php
$summaryRows = [
    'Current Status' => $label,
    'Name'           => $name ?? '',
    'Event Type'     => $event_type ?? '',
    'Preferred Date' => $reservation_date ?? '',
    'Preferred Time' => $reservation_time ?? '',
];
if ($statusText === 'approved' && !empty($priest_name)) {
    $summaryRows['Assigned Priest'] = $priest_name;
}
$summaryRows['Admin Note'] = $admin_note ?? 'No additional note provided.';
@endphp
                                    @foreach($summaryRows as $labelName => $value)
<tr>
    <td class="summary-label" style="padding:11px 16px; width:38%; border-top:1px solid #2f333a; color:#94a3b8; font-size:9.5px; letter-spacing:1.2px; text-transform:uppercase; font-weight:800;">
        {{ $labelName }}
    </td>
    <td class="summary-value" style="padding:11px 16px; border-top:1px solid #2f333a; color:#f8fafc; font-size:12.5px; font-weight:700;">
        {{ $value ?: '—' }}
    </td>
</tr>
@endforeach
                                </table>
                            </div>

                            <p style="margin:20px 0 0; color:#cbd5e1; font-size:12.5px; line-height:1.6;">
                                If any detail looks incorrect, please contact the parish office.
                            </p>

                        </div>

                        <!-- FOOTER -->
                        <div class="email-footer" style="padding:13px 22px; background:#161616; text-align:center; color:#64748b; font-size:10.5px; border-radius:0 0 14px 14px;">
                            ParishSched • St. John the Baptist Parish
                        </div>

                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
</body>
</html>
