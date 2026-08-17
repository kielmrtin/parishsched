<!doctype html>
<html class="no-js" lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Sign In | St. John the Baptist Parish</title>
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
            /* 140px top pushes content below the 124px absolute nav bar */
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
            max-width: 370px;
        }

        /* Brand */
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

        /* Tabs */
        .auth-tabs {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 26px;
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

        /* Form */
        .auth-heading {
            font-family: 'Raleway', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .auth-sub {
            font-size: .84rem;
            color: #94a3b8;
            margin-bottom: 22px;
        }

        .auth-alert {
            border-radius: 10px;
            font-size: .84rem;
            margin-bottom: 16px;
        }

        .auth-form .form-group { margin-bottom: 15px; }

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

        .auth-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .auth-remember {
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: 'Raleway', sans-serif;
            font-size: .8rem;
            color: #64748b;
            cursor: pointer;
            user-select: none;
        }

        .auth-remember input { accent-color: #dc2626; }

        .auth-forgot {
            font-family: 'Raleway', sans-serif;
            font-size: .8rem;
            font-weight: 600;
            color: #dc2626;
            text-decoration: none;
        }

        .auth-forgot:hover { text-decoration: underline; color: #b91c1c; }

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
            margin-bottom: 14px;
        }

        .auth-submit:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }

        .auth-switch {
            text-align: center;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            color: #94a3b8;
            margin-top: 8px;
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
    .ps-parish{font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#fff}
    .ps-dot{width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.2);flex-shrink:0}
    .ps-loc{font-size:.68rem;color:rgba(255,255,255,.32);letter-spacing:.04em}
    .ps-body{padding:40px 32px 36px;display:flex;flex-direction:column;align-items:center;text-align:center;position:relative;overflow:hidden}
    .ps-glow{position:absolute;width:280px;height:280px;background:radial-gradient(circle,rgba(245,158,11,.13) 0%,transparent 70%);top:-70px;left:50%;transform:translateX(-50%);pointer-events:none;animation:ps-pulse 3s ease-in-out infinite}
    @keyframes ps-pulse{0%,100%{opacity:.7;transform:translateX(-50%) scale(1)}50%{opacity:1;transform:translateX(-50%) scale(1.15)}}
    .ps-particle{position:absolute;border-radius:50%;pointer-events:none;animation:ps-float 4s ease-in infinite;opacity:0}
    @keyframes ps-float{0%{opacity:0;transform:translateY(0) scale(0)}20%{opacity:.45}100%{opacity:0;transform:translateY(-200px) scale(1.1)}}
    .ps-hdr-particle{position:absolute;border-radius:50%;pointer-events:none;animation:ps-hfloat 4s ease-in infinite;opacity:0}
    @keyframes ps-hfloat{0%{opacity:0;transform:translateY(0) scale(0)}15%{opacity:.6}100%{opacity:0;transform:translateY(-50px) scale(1.2)}}
    .ps-icon{position:relative;width:82px;height:82px;margin-bottom:24px;z-index:2}
    .ps-ring{position:absolute;inset:-5px;border-radius:50%;background:conic-gradient(rgba(245,158,11,.5) 0%,rgba(253,211,100,.3) 40%,transparent 58%);animation:ps-spin 3s linear infinite}
    @keyframes ps-spin{to{transform:rotate(360deg)}}
    .ps-inner{position:absolute;inset:0;border-radius:50%;background:rgba(245,158,11,.1);border:1.5px solid rgba(245,158,11,.3);display:flex;align-items:center;justify-content:center;animation:ps-ipop .5s .15s cubic-bezier(.34,1.56,.64,1) both;backdrop-filter:blur(8px)}
    @keyframes ps-ipop{from{transform:scale(0)}to{transform:scale(1)}}
    .ps-inner svg{width:38px;height:38px}
    .ps-warn{stroke:#fbbf24;stroke-width:2.6;stroke-linecap:round;fill:none;stroke-dasharray:80;stroke-dashoffset:80;animation:ps-draw .6s .45s ease forwards}
    @keyframes ps-draw{to{stroke-dashoffset:0}}
    .ps-title{font-size:1.5rem;font-weight:900;color:#fff;letter-spacing:-.03em;margin-bottom:9px;z-index:2;animation:ps-up .4s .5s both}
    .ps-sub{font-size:.86rem;color:rgba(255,255,255,.45);line-height:1.68;max-width:270px;margin-bottom:26px;z-index:2;animation:ps-up .4s .6s both}
    @keyframes ps-up{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .ps-btns{display:flex;gap:10px;width:100%;z-index:2;animation:ps-up .4s .7s both;justify-content:center}
    .ps-btn-solo{width:100%;padding:13px;border-radius:11px;border:none;background:#009DFF;color:#fff;font-family:'Raleway',system-ui,sans-serif;font-size:.86rem;font-weight:800;cursor:pointer;box-shadow:0 6px 20px rgba(220,38,38,.4);transition:all .15s}
    .ps-btn-solo:hover{background:#0088e0;transform:translateY(-1px)}
    </style>
</head>

<body class="auth-body">
@if(session('auth_notification'))
@php $notif = session('auth_notification'); @endphp
<div class="ps-overlay" id="ps-overlay">
  <div class="ps-modal">
    <div class="ps-hdr" id="ps-hdr-lr">
      <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 2v16M2 10h16" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
      <span class="ps-parish">St. John the Baptist Parish</span>
      <span class="ps-dot"></span>
      <span class="ps-loc">Tiaong, Quezon</span>
    </div>
    <div class="ps-body" id="ps-body-lr">
      <div class="ps-glow"></div>
      <div class="ps-icon">
        <div class="ps-ring"></div>
        <div class="ps-inner">
          <svg viewBox="0 0 42 42">
            <line class="ps-warn" x1="21" y1="10" x2="21" y2="24"/>
            <circle class="ps-warn" cx="21" cy="31" r="1.5" fill="#fbbf24" stroke="none" style="animation:none;stroke-dasharray:none;stroke-dashoffset:0;opacity:0;animation:ps-up .3s .9s both"/>
          </svg>
        </div>
      </div>
      <h2 class="ps-title">{{ $notif['title'] }}</h2>
      <p class="ps-sub">{{ $notif['text'] }}</p>
      <div class="ps-btns">
        <button class="ps-btn-solo" onclick="document.getElementById('ps-overlay').remove()">OK</button>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const colors = ['rgba(245,158,11,.4)','rgba(253,211,100,.35)','rgba(255,255,255,.18)'];
  const hColors = ['rgba(255,255,255,.25)','rgba(245,158,11,.4)','rgba(253,211,100,.3)'];
  function spawnDots(el, cols, count, cls) {
    for (let i = 0; i < count; i++) {
      const p = document.createElement('div');
      const s = Math.random()*6+4;
      p.className = cls;
      p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
      el.appendChild(p);
    }
  }
  spawnDots(document.getElementById('ps-hdr-lr'), hColors, 10, 'ps-hdr-particle');
  spawnDots(document.getElementById('ps-body-lr'), colors, 20, 'ps-particle');
  document.getElementById('ps-overlay').addEventListener('click', function(e) {
    if (e.target === this) this.remove();
  });
});
</script>
@endif

    @include('partials.header')

    <div class="auth-split">

        <!-- LEFT PANEL -->
        <div class="auth-photo-side">
            <div class="auth-photo-top">
                <span class="ap-badge">
                    <i class="fa fa-lock"></i> Welcome Back
                </span>
                <h2>Effortless<br>reservations.</h2>
                <p>Sign in to manage your sacramental reservations with ease and stay connected with the parish.</p>
                <ul class="ap-benefits">
                    <li>Track upcoming ceremonies and commitments</li>
                    <li>Update family information in moments</li>
                    <li>Receive confirmations straight to your inbox</li>
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
                    <a class="auth-tab-link active" href="{{ route('login') }}">Sign In</a>
                    <a class="auth-tab-link" href="{{ route('register') }}">Create Account</a>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger auth-alert">{{ session('error') }}</div>
                @endif

                <form class="auth-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <div class="iw">
                            <i class="fa fa-envelope-o"></i>
                            <input type="email" id="email" name="email"
                                placeholder="Enter your email" required value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <div class="iw">
                            <i class="fa fa-lock"></i>
                            <input type="password" id="password" name="password"
                                placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="auth-meta">
                        <label class="auth-remember">
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        <a class="auth-forgot" href="{{ route('password.request') }}">Forgot password?</a>
                    </div>

                    <button type="submit" class="auth-submit">Log in</button>
                </form>

                <p class="auth-switch">
                    Don't have an account? <a href="{{ route('register') }}">Sign up</a>
                </p>

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
