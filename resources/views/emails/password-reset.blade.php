<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <style>
        @media screen and (max-width:600px) {
            body { margin:0 !important; padding:0 !important; }
            .email-card { width:100% !important; max-width:100% !important; }
            .email-hero, .email-footer { border-radius:0 !important; }
            .email-hero { padding:20px 22px !important; }
            .email-body { padding:18px 20px !important; }
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

                        <!-- HERO (radius on a div, not the td — some mail clients ignore border-radius on table cells) -->
                        <div class="email-hero" style="padding:22px 24px;background:linear-gradient(135deg,#b91c1c 0%,#dc2626 50%,#f87171 100%);color:#ffffff;border-radius:14px 14px 0 0;">

                            <div style="font-size:9px;letter-spacing:2.5px;text-transform:uppercase;font-weight:700;opacity:.85;">
                                Security Notice
                            </div>

                            <h1 style="margin:10px 0 0;font-size:17px;line-height:1.3;font-weight:800;">
                                Reset your password
                            </h1>

                            <p style="margin:10px 0 0;font-size:12.5px;line-height:1.55;color:#fee2e2;">
                                @if(!empty($by_admin))
                                    The parish administrator has requested a password reset for your account.
                                @else
                                    We received a request to reset the password for your account.
                                @endif
                            </p>

                            <div style="margin-top:14px;">
                                <span style="display:inline-block;padding:5px 13px;border-radius:999px;background:rgba(248,113,113,.25);color:#ffffff;font-size:9.5px;letter-spacing:1.2px;text-transform:uppercase;font-weight:700;">
                                    Action Required
                                </span>
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="email-body" style="padding:20px 22px;">

                            <p style="margin:0 0 14px;color:#e5e7eb;font-size:12.5px;line-height:1.6;">
                                Hello {{ $name ?? 'there' }},
                            </p>

                            <p style="margin:0 0 20px;color:#cbd5e1;font-size:12.5px;line-height:1.6;">
                                @if(!empty($by_admin))
                                    The administrator has initiated a password reset on your behalf. Click the button below to set a new password for your account.
                                @else
                                    Click the button below to reset your password. If you did not request this, you can safely ignore this email — your password will remain unchanged.
                                @endif
                            </p>

                            <!-- CTA Button -->
                            <div style="text-align:center;margin:0 0 20px;">
                                <a href="{{ $reset_url }}"
                                   style="display:inline-block;padding:11px 24px;background:linear-gradient(135deg,#b91c1c,#dc2626);color:#ffffff;font-size:12.5px;font-weight:700;text-decoration:none;border-radius:9px;letter-spacing:.3px;">
                                    Reset My Password
                                </a>
                            </div>

                            <!-- Expiry notice -->
                            <div style="background:#1b1f24;border-radius:10px;padding:12px 16px;margin-bottom:18px;">
                                <div style="color:#f8fafc;font-size:11.5px;font-weight:700;margin-bottom:3px;">Link expires in 1 hour</div>
                                <div style="color:#94a3b8;font-size:11px;line-height:1.5;">
                                    This reset link will expire 1 hour after it was sent. If it has expired, you can request a new one from the login page.
                                </div>
                            </div>

                            <!-- Plain URL fallback -->
                            <div style="background:#0f172a;border:1px solid #2f333a;border-radius:10px;padding:12px 16px;margin-bottom:18px;">
                                <div style="color:#64748b;font-size:9px;letter-spacing:1.2px;text-transform:uppercase;font-weight:700;margin-bottom:6px;">
                                    Or copy this link
                                </div>
                                <div style="color:#f87171;font-size:10.5px;word-break:break-all;line-height:1.5;">
                                    {{ $reset_url }}
                                </div>
                            </div>

                            <p style="margin:0;color:#64748b;font-size:10.5px;line-height:1.55;">
                                If you did not request a password reset, please contact the parish office immediately.
                            </p>

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
