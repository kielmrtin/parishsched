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
    .ps-overlay{position:fixed;inset:0;background:rgba(6,13,26,.75);backdrop-filter:blur(6px);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px}
    .ps-modal{width:100%;max-width:440px;background:#0f172a;border-radius:22px;overflow:hidden;box-shadow:0 40px 90px rgba(0,0,0,.7),0 0 0 1px rgba(255,255,255,.07);animation:ps-pop .5s cubic-bezier(.34,1.56,.64,1) both;position:relative;font-family:'Raleway',system-ui,sans-serif}
    @keyframes ps-pop{from{opacity:0;transform:scale(.88) translateY(28px)}to{opacity:1;transform:scale(1) translateY(0)}}
    .ps-hdr{background:rgba(255,255,255,.04);border-bottom:1px solid rgba(255,255,255,.07);padding:16px 24px;display:flex;align-items:center;gap:9px;position:relative;overflow:hidden}
    .ps-cross{width:18px;height:18px;flex-shrink:0}
    .ps-parish{font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#fff;white-space:nowrap}
    .ps-dot{width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.2);flex-shrink:0}
    .ps-loc{font-size:.68rem;color:rgba(255,255,255,.32);letter-spacing:.04em;white-space:nowrap}
    .ps-body{padding:40px 32px 36px;display:flex;flex-direction:column;align-items:center;text-align:center;position:relative;overflow:hidden}
    .ps-glow{position:absolute;width:280px;height:280px;top:-70px;left:50%;transform:translateX(-50%);pointer-events:none;animation:ps-pulse 3s ease-in-out infinite;border-radius:50%}
    @keyframes ps-pulse{0%,100%{opacity:.7;transform:translateX(-50%) scale(1)}50%{opacity:1;transform:translateX(-50%) scale(1.15)}}
    .ps-particle{position:absolute;border-radius:50%;pointer-events:none;animation:ps-float 4s ease-in infinite;opacity:0}
    @keyframes ps-float{0%{opacity:0;transform:translateY(0) scale(0)}20%{opacity:.45}100%{opacity:0;transform:translateY(-200px) scale(1.1)}}
    .ps-hdr-particle{position:absolute;border-radius:50%;pointer-events:none;animation:ps-hfloat 4s ease-in infinite;opacity:0}
    @keyframes ps-hfloat{0%{opacity:0;transform:translateY(0) scale(0)}15%{opacity:.6}100%{opacity:0;transform:translateY(-50px) scale(1.2)}}
    .ps-icon{position:relative;width:82px;height:82px;margin-bottom:24px;z-index:2}
    .ps-ring{position:absolute;inset:-5px;border-radius:50%;animation:ps-spin 3s linear infinite}
    @keyframes ps-spin{to{transform:rotate(360deg)}}
    .ps-inner{position:absolute;inset:0;border-radius:50%;display:flex;align-items:center;justify-content:center;animation:ps-ipop .5s .15s cubic-bezier(.34,1.56,.64,1) both;backdrop-filter:blur(8px)}
    @keyframes ps-ipop{from{transform:scale(0)}to{transform:scale(1)}}
    .ps-inner svg{width:38px;height:38px}
    .ps-stroke{stroke-width:2.6;stroke-linecap:round;fill:none;stroke-dasharray:80;stroke-dashoffset:80;animation:ps-draw .6s .45s ease forwards}
    @keyframes ps-draw{to{stroke-dashoffset:0}}
    .ps-title{font-size:1.5rem;font-weight:900;color:#fff;letter-spacing:-.03em;margin-bottom:9px;z-index:2;animation:ps-up .4s .5s both}
    .ps-sub{font-size:.86rem;color:rgba(255,255,255,.45);line-height:1.68;max-width:270px;margin-bottom:26px;z-index:2;animation:ps-up .4s .6s both}
    @keyframes ps-up{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .ps-btns{display:flex;gap:10px;width:100%;z-index:2;animation:ps-up .4s .7s both;justify-content:center}
    .ps-btn-solo{width:100%;padding:13px;border-radius:11px;border:none;background:#dc2626;color:#fff;font-family:'Raleway',system-ui,sans-serif;font-size:.86rem;font-weight:800;cursor:pointer;box-shadow:0 6px 20px rgba(220,38,38,.4);transition:all .15s}
    .ps-btn-solo:hover{background:#b91c1c;transform:translateY(-1px)}
    </style>
</head>

<body class="auth-body">
@if(session('auth_notification'))
@php
    $notif   = session('auth_notification');
    $nType   = $notif['icon'] ?? 'warning';
    $nColors = [
        'success' => ['glow'=>'rgba(34,197,94,.13)',  'ring'=>'rgba(34,197,94,.5),rgba(134,239,172,.3)',   'inner'=>'rgba(34,197,94,.1)',  'border'=>'rgba(34,197,94,.3)',  'stroke'=>'#4ade80', 'h1'=>'rgba(255,255,255,.25)','h2'=>'#4ade8066','h3'=>'#4ade804d', 'b1'=>'#4ade8066','b2'=>'#4ade8059','b3'=>'rgba(255,255,255,.18)'],
        'error'   => ['glow'=>'rgba(220,38,38,.13)',  'ring'=>'rgba(220,38,38,.5),rgba(252,165,165,.3)',   'inner'=>'rgba(220,38,38,.1)',  'border'=>'rgba(220,38,38,.3)',  'stroke'=>'#f87171', 'h1'=>'rgba(255,255,255,.25)','h2'=>'#f8717166','h3'=>'#f871714d', 'b1'=>'#f8717166','b2'=>'#f8717159','b3'=>'rgba(255,255,255,.18)'],
        'warning' => ['glow'=>'rgba(245,158,11,.13)', 'ring'=>'rgba(245,158,11,.5),rgba(253,211,100,.3)', 'inner'=>'rgba(245,158,11,.1)', 'border'=>'rgba(245,158,11,.3)', 'stroke'=>'#fbbf24', 'h1'=>'rgba(255,255,255,.25)','h2'=>'#fbbf2466','h3'=>'#fbbf244d', 'b1'=>'#fbbf2466','b2'=>'#fbbf2459','b3'=>'rgba(255,255,255,.18)'],
    ];
    $nc = $nColors[$nType] ?? $nColors['warning'];
@endphp
<div class="ps-overlay" id="ps-overlay-notif">
  <div class="ps-modal">
    <div class="ps-hdr" id="ps-hdr-notif">
      <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
      <span class="ps-parish">St. John the Baptist Parish</span>
      <span class="ps-dot"></span>
      <span class="ps-loc">Tiaong, Quezon</span>
    </div>
    <div class="ps-body" id="ps-body-notif">
      <div class="ps-glow" style="background:radial-gradient(circle,{{ $nc['glow'] }} 0%,transparent 70%)"></div>
      <div class="ps-icon">
        <div class="ps-ring" style="background:conic-gradient({{ $nc['ring'] }},transparent 58%)"></div>
        <div class="ps-inner" style="background:{{ $nc['inner'] }};border:1.5px solid {{ $nc['border'] }}">
          <svg viewBox="0 0 42 42">
            @if($nType === 'success')
              <polyline class="ps-stroke" style="stroke:{{ $nc['stroke'] }}" points="10,22 18,30 32,14"/>
            @elseif($nType === 'error')
              <line class="ps-stroke" style="stroke:{{ $nc['stroke'] }}" x1="13" y1="13" x2="29" y2="29"/>
              <line class="ps-stroke" style="stroke:{{ $nc['stroke'] }}" x1="29" y1="13" x2="13" y2="29"/>
            @else
              <line class="ps-stroke" style="stroke:{{ $nc['stroke'] }}" x1="21" y1="10" x2="21" y2="24"/>
              <circle cx="21" cy="31" r="1.5" fill="{{ $nc['stroke'] }}" style="opacity:0;animation:ps-up .3s .9s both"/>
            @endif
          </svg>
        </div>
      </div>
      <h2 class="ps-title">{{ $notif['title'] ?? '' }}</h2>
      <p class="ps-sub">{{ $notif['text'] ?? '' }}</p>
      <div class="ps-btns">
        <button class="ps-btn-solo" onclick="document.getElementById('ps-overlay-notif').remove()">OK</button>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hColors = ['{{ $nc['h1'] }}','{{ $nc['h2'] }}','{{ $nc['h3'] }}'];
    const bColors = ['{{ $nc['b1'] }}','{{ $nc['b2'] }}','{{ $nc['b3'] }}'];
    function spawnDots(elId, cols, count, cls) {
        const c = document.getElementById(elId); if (!c) return;
        for (let i = 0; i < count; i++) {
            const p = document.createElement('div'), s = Math.random()*6+4;
            p.className = cls;
            p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
            c.appendChild(p);
        }
    }
    spawnDots('ps-hdr-notif', hColors, 10, 'ps-hdr-particle');
    spawnDots('ps-body-notif', bColors, 20, 'ps-particle');
    document.getElementById('ps-overlay-notif').addEventListener('click', function(e) { if (false) this.remove(); });
});
</script>
@endif

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
                        <label for="otp_code">Verification Code</label>
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

                    <button type="submit" class="auth-submit" id="createAccountBtn">
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

    <script>
    function showPsModal(type, title, text) {
        const existing = document.getElementById('ps-overlay');
        if (existing) existing.remove();

        const cfg = {
            success: { glow: 'rgba(34,197,94,.13)',  ring: 'rgba(34,197,94,.5),rgba(134,239,172,.3)', inner: 'rgba(34,197,94,.1)', border: 'rgba(34,197,94,.3)', stroke: '#4ade80', icon: '<polyline class="ps-stroke" style="stroke:#4ade80" points="10,22 18,30 32,14"/>' },
            error:   { glow: 'rgba(220,38,38,.13)',   ring: 'rgba(220,38,38,.5),rgba(252,165,165,.3)', inner: 'rgba(220,38,38,.1)', border: 'rgba(220,38,38,.3)', stroke: '#f87171', icon: '<line class="ps-stroke" style="stroke:#f87171" x1="13" y1="13" x2="29" y2="29"/><line class="ps-stroke" style="stroke:#f87171" x1="29" y1="13" x2="13" y2="29"/>' },
            warning: { glow: 'rgba(245,158,11,.13)',  ring: 'rgba(245,158,11,.5),rgba(253,211,100,.3)', inner: 'rgba(245,158,11,.1)', border: 'rgba(245,158,11,.3)', stroke: '#fbbf24', icon: '<line class="ps-stroke" style="stroke:#fbbf24" x1="21" y1="10" x2="21" y2="24"/><circle cx="21" cy="31" r="1.5" fill="#fbbf24" style="opacity:0;animation:ps-up .3s .9s both"/>' },
        };
        const c = cfg[type] || cfg.warning;

        const hColors = ['rgba(255,255,255,.25)', c.stroke + '66', c.stroke + '4d'];
        const bColors = [c.stroke + '66', c.stroke + '59', 'rgba(255,255,255,.18)'];

        const el = document.createElement('div');
        el.id = 'ps-overlay';
        el.className = 'ps-overlay';
        el.innerHTML = `
          <div class="ps-modal">
            <div class="ps-hdr" id="ps-hdr-otp">
              <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
              <span class="ps-parish">St. John the Baptist Parish</span>
              <span class="ps-dot"></span>
              <span class="ps-loc">Tiaong, Quezon</span>
            </div>
            <div class="ps-body" id="ps-body-otp">
              <div class="ps-glow" style="background:radial-gradient(circle,${c.glow} 0%,transparent 70%)"></div>
              <div class="ps-icon">
                <div class="ps-ring" style="background:conic-gradient(${c.ring},transparent 58%)"></div>
                <div class="ps-inner" style="background:${c.inner};border:1.5px solid ${c.border}">
                  <svg viewBox="0 0 42 42">${c.icon}</svg>
                </div>
              </div>
              <h2 class="ps-title">${title}</h2>
              <p class="ps-sub">${text}</p>
              <div class="ps-btns">
                <button class="ps-btn-solo" onclick="document.getElementById('ps-overlay').remove()">OK</button>
              </div>
            </div>
          </div>`;
        document.body.appendChild(el);

        function spawnDots(elId, cols, count, cls) {
            const container = document.getElementById(elId);
            if (!container) return;
            for (let i = 0; i < count; i++) {
                const p = document.createElement('div');
                const s = Math.random()*6+4;
                p.className = cls;
                p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
                container.appendChild(p);
            }
        }
        spawnDots('ps-hdr-otp', hColors, 10, 'ps-hdr-particle');
        spawnDots('ps-body-otp', bColors, 20, 'ps-particle');

        el.addEventListener('click', function(e) { if (false) this.remove(); });
    }
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const phoneInput = document.getElementById('phone');
        const countdown  = document.getElementById('otpCountdown');
        let timer = null;

        sendOtpBtn.addEventListener('click', function () {
            const phone = phoneInput.value.trim();
            if (!phone) {
                showPsModal('warning', 'Phone Required', 'Please enter your phone number first.');
                return;
            }
            sendOtpBtn.disabled = true;

            fetch('{{ route('register.sendOtp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ phone: phone })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showPsModal('success', 'OTP Sent', data.message || 'Check your phone for the code.');
                    startCountdown(30);
                } else {
                    showPsModal('error', 'Failed', data.message || 'Could not send OTP. Try again.');
                    sendOtpBtn.disabled = false;
                }
            })
            .catch(() => {
                showPsModal('error', 'Network Error', 'Network error. Please try again.');
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

    });
    </script>

</body>
</html>
