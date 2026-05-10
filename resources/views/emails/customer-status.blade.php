<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Status Update</title>
</head>

@php
$statusText = strtolower($status ?? 'active');

if ($statusText === 'disabled') {
    $heroGradient = 'linear-gradient(135deg,#dc2626,#ef4444,#f87171)';
    $badgeColor = 'rgba(248,113,113,.25)';
    $title = 'Your account has been disabled';
    $subtitle = 'Your account has been restricted by the administrator.';
    $label = 'Disabled';
}
else {
    $heroGradient = 'linear-gradient(135deg,#16a34a,#22c55e,#4ade80)';
    $badgeColor = 'rgba(34,197,94,.25)';
    $title = 'Your account has been re-enabled';
    $subtitle = 'You can now access your account again.';
    $label = 'Active';
}
@endphp

<body style="margin:0; padding:30px 0; background:#f3f4f6; font-family:'Segoe UI',Arial; color:#e5e7eb;">

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="center">

<table width="100%" style="
    max-width:600px;
    background:#111;
    border:2px solid #111;
    border-radius:28px;
    overflow:hidden;
">

<!-- HERO -->
<tr>
<td style="
    padding:36px;
    background:{{ $heroGradient }};
    color:white;
    border-top-left-radius:26px;
    border-top-right-radius:26px;
">

<div style="font-size:12px; letter-spacing:3px; text-transform:uppercase; font-weight:700;">
Account Update
</div>

<h1 style="margin-top:12px; font-size:28px; font-weight:800;">
{{ $title }}
</h1>

<p style="margin-top:10px; font-size:16px;">
{{ $subtitle }}
</p>

<div style="margin-top:20px;">
<span style="padding:8px 18px; border-radius:999px; background:{{ $badgeColor }}; font-size:12px; font-weight:700;">
{{ $label }}
</span>
</div>

</td>
</tr>

<!-- BODY -->
<tr>
<td style="padding:30px;">

<p>Hello {{ $name ?? 'User' }},</p>

@if($statusText === 'disabled')
<p>Your account has been disabled by the administrator.</p>

<p><strong>Reason:</strong><br>
{{ $reason ?: 'No reason provided.' }}</p>
@else
<p>Your account has been successfully re-enabled. You may now log in again.</p>
@endif

</td>
</tr>

<!-- FOOTER -->
<tr>
<td style="padding:20px; text-align:center; color:#64748b;">
ParishSched • St. John the Baptist Parish
</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>