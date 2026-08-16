<!doctype html>
<html class="no-js" lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Forgot Password | St. John the Baptist Parish</title>
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

        /* ─── LEFT PANEL ─── */
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
            padding: 175px 64px 52px;
            background: #fff;
            overflow-y: auto;
        }

        .auth-inner {
            width: 100%;
            max-width: 370px;
        }

        .auth-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 28px;
        }

        .parish-logo {
            display: block;
            width: 66px;
            height: 66px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e8c97a;
            box-shadow: 0 4px 14px rgba(139,26,26,.2);
            margin-bottom: 12px;
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

        .auth-heading {
            font-family: 'Raleway', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            text-align: center;
        }

        .auth-sub {
            font-size: .84rem;
            color: #94a3b8;
            margin-bottom: 28px;
            line-height: 1.6;
            text-align: center;
        }

        .auth-alert {
            border-radius: 10px;
            font-size: .84rem;
            margin-bottom: 16px;
        }

        .auth-form .form-group { margin-bottom: 16px; }

        .auth-form label {
            display: block;
            font-family: 'Raleway', sans-serif;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 7px;
        }

        .iw { position: relative; }

        .iw i {
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
            padding: 11px 14px 11px 38px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            font-family: 'Raleway', sans-serif;
            font-size: .88rem;
            color: #1a2a4a;
            outline: none;
            box-sizing: border-box;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .iw input:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220,38,38,.12);
            background: #fff;
        }

        .auth-submit {
            display: block;
            width: 100%;
            padding: 13px;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .92rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(220,38,38,.3);
            transition: background .2s, transform .15s;
            margin-bottom: 20px;
        }

        .auth-submit:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }

        .auth-back {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            color: #94a3b8;
            text-decoration: none;
            transition: color .2s;
        }

        .auth-back i { color: #dc2626; font-size: .72rem; }
        .auth-back:hover { color: #dc2626; text-decoration: none; }
        .auth-back span a { color: #dc2626; font-weight: 700; text-decoration: none; font-size: .82rem; }

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
                    <i class="fa fa-envelope-o"></i> Reset Access
                </span>
                <h2>We'll help you<br>get back in.</h2>
                <p>Request a secure link to update your password and return to managing your sacramental reservations.</p>
                <ul class="ap-benefits">
                    <li>Keep your reservation details safe</li>
                    <li>Update access in just a few steps</li>
                    <li>Receive instant confirmation via email</li>
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

                <h3 class="auth-heading">Forgot your password?</h3>
                <p class="auth-sub">Enter the email associated with your account and we'll send a reset link within moments.</p>

                @if(session('error'))
                    <div class="alert alert-danger auth-alert">{{ session('error') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success auth-alert">{{ session('success') }}</div>
                @endif

                <form class="auth-form" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <div class="iw">
                            <i class="fa fa-envelope-o"></i>
                            <input type="email" id="email" name="email"
                                placeholder="name@example.com" required value="{{ old('email') }}">
                        </div>
                    </div>

                    <button type="submit" class="auth-submit">Send Reset Link</button>
                </form>

                <a class="auth-back" href="{{ route('login') }}">
                    <i class="fa fa-arrow-left"></i>
                    Remembered your password?
                </a>


            </div>
        </div>

    </div>

    <script src="{{ asset('js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

</body>
</html>
