<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <style>
        @media screen and (max-width:600px) {
            body { margin:0 !important; padding:0 !important; }
            .email-card { width:100% !important; max-width:100% !important; border-radius:0 !important; }
            .email-hero { padding:34px 24px !important; border-radius:0 !important; }
            .email-body { padding:28px 22px !important; }
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
                        style="padding:36px 34px;background:linear-gradient(135deg,#4f46e5 0%,#6366f1 50%,#818cf8 100%);color:#ffffff;">

                        <div style="font-size:12px;letter-spacing:4px;text-transform:uppercase;font-weight:700;opacity:.85;">
                            Security Notice
                        </div>

                        <h1 style="margin:16px 0 0;font-size:31px;line-height:1.18;font-weight:800;">
                            Reset your password
                        </h1>

                        <p style="margin:18px 0 0;font-size:17px;line-height:1.6;color:#eef2ff;">
                            @if(!empty($by_admin))
                                The parish administrator has requested a password reset for your account.
                            @else
                                We received a request to reset the password for your account.
                            @endif
                        </p>

                        <div style="margin-top:26px;">
                            <span style="display:inline-block;padding:8px 18px;border-radius:999px;background:rgba(129,140,248,.25);color:#ffffff;font-size:12px;letter-spacing:2px;text-transform:uppercase;font-weight:700;">
                                Action Required
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
                            @if(!empty($by_admin))
                                The administrator has initiated a password reset on your behalf. Click the button below to set a new password for your account.
                            @else
                                Click the button below to reset your password. If you did not request this, you can safely ignore this email — your password will remain unchanged.
                            @endif
                        </p>

                        <!-- CTA Button -->
                        <div style="text-align:center;margin:0 0 28px;">
                            <a href="{{ $reset_url }}"
                               style="display:inline-block;padding:16px 36px;background:linear-gradient(135deg,#4f46e5,#6366f1);color:#ffffff;font-size:16px;font-weight:700;text-decoration:none;border-radius:12px;letter-spacing:.3px;">
                                Reset My Password
                            </a>
                        </div>

                        <!-- Expiry notice -->
                        <div style="background:#1b1f24;border-radius:14px;padding:18px 22px;margin-bottom:28px;">
                            <div style="color:#f8fafc;font-size:14px;font-weight:700;margin-bottom:4px;">Link expires in 1 hour</div>
                            <div style="color:#94a3b8;font-size:13px;line-height:1.6;">
                                This reset link will expire 1 hour after it was sent. If it has expired, you can request a new one from the login page.
                            </div>
                        </div>

                        <!-- Plain URL fallback -->
                        <div style="background:#0f172a;border:1px solid #2f333a;border-radius:12px;padding:16px 20px;margin-bottom:28px;">
                            <div style="color:#64748b;font-size:11px;letter-spacing:2px;text-transform:uppercase;font-weight:700;margin-bottom:8px;">
                                Or copy this link
                            </div>
                            <div style="color:#818cf8;font-size:12px;word-break:break-all;line-height:1.6;">
                                {{ $reset_url }}
                            </div>
                        </div>

                        <p style="margin:0;color:#64748b;font-size:13px;line-height:1.7;">
                            If you did not request a password reset, please contact the parish office immediately.
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
