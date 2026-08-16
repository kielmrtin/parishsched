<!doctype html>
<html class="no-js" lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Create Account | St. John the Baptist Parish</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        html { scrollbar-gutter: stable; }

        body.auth-body {
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .auth-split {
            display: flex;
            min-height: 100vh;
        }

        .auth-photo-side {
            flex: 0 0 46%;
            background:
                linear-gradient(160deg, rgba(127,29,29,.52) 0%, rgba(185,28,28,.44) 50%, rgba(127,29,29,.56) 100%),
                url('{{ asset('img/banner/bradcam3.jpg') }}') center center / cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 175px 48px 52px;
        }

        .auth-photo-top {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .auth-photo-bottom {
            font-size: .72rem;
            color: rgba(255,255,255,.35);
            letter-spacing: .06em;
        }

        .ap-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,.14);
            border: 1px solid rgba(255,255,255,.26);
            border-radius: 999px;
            padding: 8px 20px;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: rgba(255,255,255,.9);
            margin-bottom: 22px;
        }

        .ap-badge i { color: #dc2626; }

        .auth-photo-top h2 {
            font-family: 'Raleway', sans-serif;
            font-size: 2.3rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 14px;
        }

        .auth-photo-top > p {
            font-size: .88rem;
            color: rgba(255,255,255,.62);
            line-height: 1.75;
            max-width: 330px;
            margin-bottom: 0;
        }

        .ap-benefits {
            list-style: none;
            padding: 0;
            margin: 22px 0 0;
            display: flex;
            flex-direction: column;
            gap: 11px;
        }

        .ap-benefits li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            font-weight: 600;
            color: rgba(255,255,255,.92);
        }

        .ap-benefits li::before {
            content: '';
            width: 7px;
            height: 7px;
            min-width: 7px;
            background: rgba(255,255,255,.8);
            border-radius: 50%;
        }

        /* ─── RIGHT PANEL ─── */
        .auth-form-side {
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 140px 64px 52px;
            background: #fff;
            overflow-y: auto;
        }

        .auth-inner {
            width: 100%;
            max-width: 430px;
            padding-bottom: 32px;
        }

        .auth-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 24px;
        }

        .parish-logo {
            display: block;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e8c97a;
            box-shadow: 0 4px 14px rgba(139,26,26,.2);
            margin-bottom: 10px;
        }

        .auth-brand-name {
            font-family: 'Raleway', sans-serif;
            font-size: .88rem;
            font-weight: 800;
            color: #0f172a;
        }

        .auth-brand-loc {
            font-size: .74rem;
            color: #94a3b8;
            margin-top: 3px;
        }

        .auth-tabs {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 20px;
        }

        .auth-tab-link {
            flex: 1;
            padding: 10px 0;
            text-align: center;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            font-weight: 700;
            color: #94a3b8;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: color .2s, border-color .2s;
        }

        .auth-tab-link.active { color: #dc2626; border-bottom-color: #dc2626; }
        .auth-tab-link:hover  { color: #dc2626; text-decoration: none; }

        .auth-heading {
            font-family: 'Raleway', sans-serif;
            font-size: 1.3rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .auth-sub {
            font-size: .84rem;
            color: #94a3b8;
            margin-bottom: 16px;
        }

        .auth-alert {
            border-radius: 10px;
            font-size: .83rem;
            margin-bottom: 14px;
            padding: 10px 14px;
        }

        .auth-alert ul { margin: 0; padding-left: 16px; }

        .auth-row {
            display: flex;
            gap: 12px;
        }

        .auth-row .form-group { flex: 1; min-width: 0; }

        .auth-form .form-group { margin-bottom: 13px; }

        .auth-form label {
            display: block;
            font-family: 'Raleway', sans-serif;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 6px;
        }

        .iw { position: relative; }

        .iw > i {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            color: #cbd5e1;
            font-size: .85rem;
            pointer-events: none;
            z-index: 1;
        }

        .iw input {
            display: block;
            width: 100%;
            padding: 10px 13px 10px 37px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            font-family: 'Raleway', sans-serif;
            font-size: .87rem;
            color: #1a2a4a;
            outline: none;
            box-sizing: border-box;
            min-height: 42px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .iw input:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220,38,38,.12);
            background: #fff;
        }

        /* Phone + OTP */
        .otp-wrap {
            display: flex;
            gap: 8px;
        }

        .otp-phone {
            position: relative;
            flex: 1;
        }

        .otp-phone > i {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            color: #cbd5e1;
            font-size: .85rem;
            pointer-events: none;
            z-index: 1;
        }

        .otp-phone input {
            display: block;
            width: 100%;
            padding: 10px 13px 10px 37px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            font-family: 'Raleway', sans-serif;
            font-size: .87rem;
            color: #1a2a4a;
            outline: none;
            box-sizing: border-box;
            min-height: 42px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .otp-phone input:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220,38,38,.12);
            background: #fff;
        }

        #sendOtpBtn {
            flex-shrink: 0;
            padding: 0 14px;
            min-height: 42px;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .78rem;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
            transition: background .2s, transform .15s;
        }

        #sendOtpBtn:hover:not(:disabled) { background: #b91c1c; transform: translateY(-1px); }
        #sendOtpBtn:disabled { background: #94a3b8; cursor: not-allowed; }

        #otpCountdown {
            font-family: 'Raleway', sans-serif;
            font-size: .72rem;
            color: #94a3b8;
            min-height: 16px;
            margin-top: 4px;
        }

        .auth-submit {
            display: block;
            width: 100%;
            padding: 12px;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .9rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(220,38,38,.3);
            transition: background .2s, transform .15s;
            margin-top: 6px;
            margin-bottom: 12px;
        }

        .auth-submit:hover:not(:disabled) {
            background: #b91c1c;
            transform: translateY(-1px);
        }

        .auth-submit:disabled {
            background: #94a3b8;
            box-shadow: none;
            cursor: not-allowed;
        }

        .auth-switch {
            text-align: center;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            color: #94a3b8;
        }

        .auth-switch a { color: #dc2626; font-weight: 700; text-decoration: none; }
        .auth-switch a:hover { text-decoration: underline; }

        .boxed-btn3 { background: #dc2626 !important; border-color: #dc2626 !important; }
        .boxed-btn3:hover { background: #fff !important; color: #dc2626 !important; border-color: #dc2626 !important; }
    </style>
</head>

<body class="auth-body">

    @include('partials.header')

    <div class="auth-split">

        <!-- LEFT PANEL -->
        <div class="auth-photo-side">
            <div class="auth-photo-top">
                <span class="ap-badge">
                    <i class="fa fa-users"></i> Join the Community
                </span>
                <h2>Join the Parish<br>community.</h2>
                <p>Create your account and enjoy a seamless way to book sacraments and stay connected with the parish.</p>
                <ul class="ap-benefits">
                    <li>Book baptisms, weddings, and more</li>
                    <li>Receive reminders and schedule updates</li>
                    <li>Manage your reservations anytime</li>
                </ul>
            </div>

            <div class="auth-photo-bottom">
                St. John the Baptist Parish &middot; Tiaong, Quezon
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="auth-form-side">
            <div class="auth-inner">

                <div class="auth-brand">
                    <img class="parish-logo" src="{{ asset('img/about/about_1.jpg') }}" alt="Parish Logo">
                    <div class="auth-brand-name">St. John the Baptist Parish</div>
                    <div class="auth-brand-loc">Tiaong, Quezon</div>
                </div>

                <div class="auth-tabs">
                    <a class="auth-tab-link" href="{{ route('login') }}">Sign In</a>
                    <a class="auth-tab-link active" href="{{ route('register') }}">Create Account</a>
                </div>

                <h3 class="auth-heading">Create your account</h3>
                <p class="auth-sub">Fill in your details to get started with parish reservations.</p>

                @if ($errors->any())
                    <div class="alert alert-danger auth-alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="auth-form" method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="auth-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <div class="iw">
                                <i class="fa fa-user-o"></i>
                                <input type="text" id="name" name="name"
                                    placeholder="Full name" required value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <div class="iw">
                                <i class="fa fa-envelope-o"></i>
                                <input type="email" id="email" name="email"
                                    placeholder="Email" required value="{{ old('email') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <div class="otp-wrap">
                            <div class="otp-phone">
                                <i class="fa fa-phone"></i>
                                <input type="text" id="phone" name="phone"
                                    placeholder="09XXXXXXXXX" required value="{{ old('phone') }}">
                            </div>
                            <button type="button" id="sendOtpBtn">Send OTP</button>
                        </div>
                        <div id="otpCountdown"></div>
                    </div>

                    <div class="form-group">
                        <label for="otp_code">Verification Code *</label>
                        <div class="iw">
                            <i class="fa fa-shield"></i>
                            <input type="text" id="otp_code" name="otp_code"
                                placeholder="6-digit code" maxlength="6" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Home Address *</label>
                        <div class="iw">
                            <i class="fa fa-map-marker"></i>
                            <input type="text" id="address" name="address"
                                placeholder="Barangay, Municipality, Province" required value="{{ old('address') }}">
                        </div>
                    </div>

                    <div class="auth-row">
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <div class="iw">
                                <i class="fa fa-lock"></i>
                                <input type="password" id="password" name="password"
                                    placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm *</label>
                            <div class="iw">
                                <i class="fa fa-lock"></i>
                                <input type="password" id="password_confirmation"
                                    name="password_confirmation" placeholder="Repeat" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="auth-submit" id="createAccountBtn" disabled>
                        Create Account
                    </button>
                </form>

                <p class="auth-switch">
                    Already have an account? <a href="{{ route('login') }}">Sign in</a>
                </p>

            </div>
        </div>

    </div>

    <script src="{{ asset('js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('auth_notification'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const n = @json(session('auth_notification'));
        Swal.fire({ icon: n.icon||'info', title: n.title||'', text: n.text||'', confirmButtonColor: '#dc2626' });
    });
    </script>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const otpInput   = document.getElementById('otp_code');
        const phoneInput = document.getElementById('phone');
        const submitBtn  = document.getElementById('createAccountBtn');
        const countdown  = document.getElementById('otpCountdown');
        let timer = null;

        sendOtpBtn.addEventListener('click', function () {
            const phone = phoneInput.value.trim();
            if (!phone) {
                Swal.fire({ icon: 'warning', title: 'Phone required', text: 'Please enter your phone number first.', confirmButtonColor: '#dc2626' });
                return;
            }
            sendOtpBtn.disabled = true;

            fetch('{{ route('register.sendOtp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ phone: phone })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'OTP Sent', text: data.message || 'Check your phone for the code.', confirmButtonColor: '#dc2626' });
                    startCountdown(30);
                } else {
                    Swal.fire({ icon: 'error', title: 'Failed', text: data.message || 'Could not send OTP. Try again.', confirmButtonColor: '#dc2626' });
                    sendOtpBtn.disabled = false;
                }
            })
            .catch(() => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Network error. Please try again.', confirmButtonColor: '#dc2626' });
                sendOtpBtn.disabled = false;
            });
        });

        function startCountdown(seconds) {
            clearInterval(timer);
            let rem = seconds;
            countdown.textContent = 'Resend available in ' + rem + 's';
            timer = setInterval(function () {
                rem--;
                if (rem <= 0) {
                    clearInterval(timer);
                    countdown.textContent = '';
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.textContent = 'Resend OTP';
                } else {
                    countdown.textContent = 'Resend available in ' + rem + 's';
                }
            }, 1000);
        }

        otpInput.addEventListener('input', function () {
            submitBtn.disabled = otpInput.value.trim().length !== 6;
        });
    });
    </script>

</body>
</html>
