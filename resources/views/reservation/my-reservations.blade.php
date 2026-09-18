<!doctype html>
<html class="no-js" lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>My Reservations | St. John the Baptist Parish</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gijgo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            overflow-x: hidden !important;
            overscroll-behavior-x: none;
            background: #f4f7fb;
        }

        /* ---- WHITE HEADER ---- */
        body .header-area {
            position: relative !important;
            background: #ffffff !important;
            padding-top: 0 !important;
        }
        body .main-header-area { background: #ffffff !important; padding: 18px 150px !important; }
        body .main-header-area .row { min-height: 88px; align-items: center !important; }
        body .main-header-area .main-menu { padding: 0 !important; }
        body .main-menu ul li a { color: #1F1F1F !important; }
        body .main-menu ul li a::before { background: #1F1F1F !important; }
        body .socail_links ul li a { color: #1F1F1F !important; }
        body .signed-text, body .signed-text strong, body .logout-text-link { color: #1F1F1F !important; }
        body .book_room { height: 100%; align-items: center !important; }
        body .user-info { margin-right: 25px; }
        body .logo-img img { border-radius: 50px; height: 60px !important; width: 60px; object-fit: cover; }

        /* ---- PAGE HERO ---- */
        .myres-hero {
            background: linear-gradient(rgba(0,0,0,.52), rgba(0,0,0,.48)),
                        url("{{ asset('img/banner/myreservations.jpg') }}") center/cover no-repeat;
            padding: 72px 0 0;
            color: #fff;
            position: relative;
        }
        .myres-hero-body { padding-bottom: 56px; }
        .myres-hero-wave { display: block; width: 100%; line-height: 0; }
        .myres-hero-wave svg { display: block; width: 100%; }

        .myres-hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(255,255,255,.75);
            margin-bottom: 1rem;
        }
        .myres-hero-kicker::before,
        .myres-hero-kicker::after {
            content: '';
            display: inline-block;
            width: 32px;
            height: 1px;
            background: rgba(255,255,255,.5);
        }

        .myres-hero h1 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1rem;
            color: #fff;
        }

        .myres-hero .hero-desc {
            font-size: 1.05rem;
            color: rgba(255,255,255,.82);
            max-width: 520px;
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        .myres-hero-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .myres-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            background: #dc2626;
            color: #fff !important;
            font-weight: 400;
            font-size: .85rem;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            padding: .85rem 2rem;
            border: none;
            text-decoration: none !important;
            transition: background .2s, transform .15s;
        }
        .myres-btn-primary:hover { background: #b91c1c; transform: translateY(-1px); }

        .myres-btn-outline {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            background: transparent;
            color: #fff !important;
            font-weight: 400;
            font-size: .85rem;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            padding: .83rem 1.8rem;
            border: 1px solid rgba(255,255,255,.7);
            text-decoration: none !important;
            transition: border-color .2s, background .2s;
        }
        .myres-btn-outline:hover { border-color: #fff; background: rgba(255,255,255,.08); }

        /* ---- CONTENT ---- */
        .myres-section { padding: 48px 0 72px; }

        /* ---- STATUS BADGE ---- */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
            padding: .3rem .75rem;
            border-radius: 50px;
        }
        .status-badge.pending  { background: #fff8e1; color: #b45309; }
        .status-badge.approved { background: #e6f9f0; color: #157a45; }
        .status-badge.declined { background: #fdecea; color: #b91c1c; }
        .status-badge.booked   { background: #e8f0fe; color: #1a56db; }

        /* ---- PULSE DOT ---- */
        .res-pulse-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; display:inline-block; }
        .res-pulse-dot.dot-pending  { background:#eab308; box-shadow:0 0 0 0 rgba(234,179,8,.6);  animation:res-pulse-y 1.8s cubic-bezier(.4,0,.6,1) infinite; }
        .res-pulse-dot.dot-approved { background:#16a34a; box-shadow:0 0 0 0 rgba(22,163,74,.6);  animation:res-pulse-g 1.8s cubic-bezier(.4,0,.6,1) infinite; }
        .res-pulse-dot.dot-declined { background:#dc2626; box-shadow:0 0 0 0 rgba(220,38,38,.6);  animation:res-pulse-r 1.8s cubic-bezier(.4,0,.6,1) infinite; }
        @keyframes res-pulse-y { 0%,100%{box-shadow:0 0 0 0 rgba(234,179,8,.6)} 50%{box-shadow:0 0 0 5px rgba(234,179,8,0)} }
        @keyframes res-pulse-g { 0%,100%{box-shadow:0 0 0 0 rgba(22,163,74,.6)} 50%{box-shadow:0 0 0 5px rgba(22,163,74,0)} }
        @keyframes res-pulse-r { 0%,100%{box-shadow:0 0 0 0 rgba(220,38,38,.6)} 50%{box-shadow:0 0 0 5px rgba(220,38,38,0)} }

        /* ---- RESERVATION CARD ---- */
        .res-card {
            position: relative;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(10,31,68,.08);
            padding: 1.7rem 1.9rem 1.6rem 2rem;
            margin-bottom: 1.2rem;
            transition: box-shadow .2s;
            overflow: hidden;
        }
        .res-card::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 5px;
            background: var(--accent, #eab308);
        }
        .res-card:hover { box-shadow: 0 8px 32px rgba(10,31,68,.13); }

        .res-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .9rem;
            margin-bottom: 1.15rem;
        }
        .res-card-heading { display: flex; align-items: center; gap: .85rem; }

        .res-icon-avatar {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .res-icon-avatar.wedding  { background: #fce4ec; }
        .res-icon-avatar.baptism  { background: rgba(22,163,74,.12); }
        .res-icon-avatar.funeral  { background: rgba(100,116,139,.13); }
        .res-icon-avatar.default  { background: #eceff1; }

        .res-title { font-size: 1.05rem; font-weight: 800; color: #0f172a; letter-spacing: -.01em; }
        .res-subtitle { font-size: .82rem; color: #94a3b8; margin-top: 1px; }

        .res-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            font-size: .87rem;
            color: #475569;
            margin-bottom: 1.2rem;
        }
        .res-meta-item { display: flex; align-items: center; gap: .4rem; }
        .res-meta-sep { width: 3px; height: 3px; border-radius: 50%; background: #cbd5e1; margin: 0 .7rem; flex-shrink: 0; }
        .res-meta i { opacity: .55; }

        /* ---- REVIEW-PROGRESS STEPPER ---- */
        .res-stepper { display: flex; align-items: center; margin-bottom: 1.25rem; }
        .res-step { display: flex; align-items: center; gap: .5rem; flex-shrink: 0; }
        .res-step-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .res-step-label { font-size: .74rem; font-weight: 700; white-space: nowrap; }
        .res-step-line { flex: 1; height: 2px; margin: 0 8px; min-width: 20px; }

        .res-admin-note {
            background: #f8fafc;
            border-left: 3px solid #cbd5e1;
            border-radius: 0 8px 8px 0;
            padding: .65rem 1rem;
            font-size: .875rem;
            color: #475569;
            margin-bottom: .75rem;
        }
        .res-admin-note strong { color: #334155; }

        /* ---- CARD ACTIONS ---- */
        .res-actions { display: flex; gap: .6rem; flex-wrap: wrap; margin-top: .9rem; }
        .btn-contact-parish {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            font-size: .82rem;
            font-weight: 700;
            color: #fff;
            background: #dc2626;
            border: none;
            border-radius: 9px;
            padding: .55rem 1.05rem;
            font-family: inherit;
            box-shadow: 0 6px 16px rgba(220,38,38,.25);
            text-decoration: none;
            cursor: pointer;
        }
        .btn-contact-parish:hover { background: #b91c1c; color: #fff; }

        /* ---- EMPTY STATE ---- */
        .myres-empty {
            text-align: center;
            padding: 4rem 2rem;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(10,31,68,.07);
        }
        .myres-empty-icon {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1.2rem;
        }
        .myres-empty h4 { color: #334155; font-weight: 700; margin-bottom: .5rem; }
        .myres-empty p  { color: #64748b; max-width: 380px; margin: 0 auto 1.6rem; }

        /* ---- SUMMARY / FILTER PILLS ---- */
        .res-summary {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            margin-bottom: 1.8rem;
        }
        .res-summary-chip {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            border: 1.5px solid transparent;
            border-radius: 50px;
            padding: .55rem 1.1rem;
            font-size: .85rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: transform .12s;
        }
        .res-summary-chip:hover { transform: translateY(-1px); }
        .res-summary-chip .chip-count {
            border-radius: 50px;
            padding: .1rem .55rem;
            font-size: .78rem;
            font-weight: 700;
        }
        .res-summary-chip.all       { background: #dc2626; border-color: #dc2626; color: #fff; }
        .res-summary-chip.all       .chip-count { background: rgba(255,255,255,.28); color: #fff; }
        .res-summary-chip.pending   { background: #fff8e1; border-color: #fde68a; color: #b45309; }
        .res-summary-chip.pending   .chip-count { background: rgba(180,83,9,.16); color: #b45309; }
        .res-summary-chip.approved  { background: #e6f9f0; border-color: #a7e8c8; color: #157a45; }
        .res-summary-chip.approved  .chip-count { background: rgba(21,122,69,.16); color: #157a45; }
        .res-summary-chip.declined  { background: #fdecea; border-color: #f6b8b3; color: #b91c1c; }
        .res-summary-chip.declined  .chip-count { background: rgba(185,28,28,.16); color: #b91c1c; }
        .res-summary-chip.is-active { box-shadow: 0 0 0 2px rgba(15,23,42,.18); }

        /* ---- CANCEL FORM ---- */
        .cancel-form-wrap {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
            display: none;
        }
        .cancel-form-wrap textarea {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: .7rem 1rem;
            font-size: .9rem;
            resize: vertical;
            margin-bottom: .75rem;
            color: #334155;
        }
        .cancel-form-wrap textarea:focus { outline: none; border-color: #e11d48; box-shadow: 0 0 0 3px rgba(225,29,72,.1); }
        .btn-cancel-req {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .82rem;
            font-weight: 600;
            color: #e11d48;
            background: #fff0f3;
            border: 1px solid #fecdd3;
            border-radius: 8px;
            padding: .4rem .9rem;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s;
        }
        .btn-cancel-req:hover { background: #ffe4e6; color: #be123c; }
        .btn-cancel-submit {
            background: #e11d48;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .5rem 1.2rem;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-cancel-submit:hover { background: #be123c; }
        .btn-cancel-back { background: none; border: none; color: #94a3b8; font-size: .85rem; cursor: pointer; margin-left: .5rem; }
        .cancellation-pending-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .78rem;
            font-weight: 700;
            color: #b45309;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 50px;
            padding: .3rem .8rem;
            margin-top: .75rem;
        }

        @media (max-width: 576px) {
            .res-card { padding: 1.2rem 1.1rem; }
            .myres-hero h1 { font-size: 1.6rem; }
        }

        /* Dark modal alert (matches the login/register confirmation modal) */
        .ps-overlay{position:fixed;inset:0;background:rgba(6,13,26,.75);backdrop-filter:blur(6px);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px;animation:ps-fade-in .25s ease both}
        @keyframes ps-fade-in{from{opacity:0}to{opacity:1}}
        .ps-modal{width:100%;max-width:440px;background:#0f172a;border-radius:22px;overflow:hidden;box-shadow:0 40px 90px rgba(0,0,0,.7),0 0 0 1px rgba(255,255,255,.07);animation:ps-pop .5s cubic-bezier(.34,1.56,.64,1) both;position:relative;font-family:'Raleway',system-ui,sans-serif}
        @keyframes ps-pop{from{opacity:0;transform:scale(.88) translateY(28px)}to{opacity:1;transform:scale(1) translateY(0)}}
        .ps-hdr{background:rgba(255,255,255,.04);border-bottom:1px solid rgba(255,255,255,.07);padding:16px 24px;display:flex;align-items:center;gap:9px;position:relative;overflow:hidden}
        .ps-cross{width:18px;height:18px;flex-shrink:0}
        .ps-parish{font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#fff;white-space:nowrap}
        .ps-dot{width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.2);flex-shrink:0}
        .ps-loc{font-size:.68rem;color:rgba(255,255,255,.32);letter-spacing:.04em;white-space:nowrap}
        .ps-body{padding:40px 32px 36px;display:flex;flex-direction:column;align-items:center;text-align:center;position:relative;overflow:hidden}
        .ps-glow{position:absolute;width:280px;height:280px;top:-70px;left:50%;transform:translateX(-50%);pointer-events:none;animation:ps-pulse 3s ease-in-out infinite}
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
        .ps-warn{stroke-width:2.6;stroke-linecap:round;fill:none;stroke-dasharray:80;stroke-dashoffset:80;animation:ps-draw .6s .45s ease forwards}
        @keyframes ps-draw{to{stroke-dashoffset:0}}
        .ps-title{font-size:1.5rem;font-weight:900;color:#fff;letter-spacing:-.03em;margin-bottom:9px;z-index:2;animation:ps-up .4s .5s both}
        .ps-sub{font-size:.86rem;color:rgba(255,255,255,.45);line-height:1.68;max-width:270px;margin-bottom:26px;z-index:2;animation:ps-up .4s .6s both}
        @keyframes ps-up{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .ps-btns{display:flex;gap:10px;width:100%;z-index:2;animation:ps-up .4s .7s both;justify-content:center}
        .ps-btn-solo{width:100%;padding:13px;border-radius:11px;border:none;background:#dc2626;color:#fff;font-family:'Raleway',system-ui,sans-serif;font-size:.86rem;font-weight:800;cursor:pointer;box-shadow:0 6px 20px rgba(220,38,38,.4);transition:all .15s}
        .ps-btn-solo:hover{background:#b91c1c;transform:translateY(-1px)}
    </style>
</head>

<body>

@include('partials.header')

<div class="myres-hero">
    <div class="container myres-hero-body">
        <div class="myres-hero-kicker">
            <span>St. John the Baptist Parish</span>
        </div>
        <h1>My Reservations</h1>
        <p class="hero-desc">
            Track and manage your sacrament reservation requests. Our parish team will review and respond to each submission as soon as possible.
        </p>
        <div class="myres-hero-actions">
            <a href="{{ route('reservation.index') }}" class="myres-btn-primary">
                <i class="fa fa-calendar-plus-o"></i> New Reservation
            </a>
            <a href="{{ url('/schedule') }}" class="myres-btn-outline">
                <i class="fa fa-clock-o"></i> View Schedule
            </a>
            <a href="{{ url('/contact') }}" class="myres-btn-outline">
                <i class="fa fa-envelope-o"></i> Contact Parish
            </a>
        </div>
    </div>
    <div class="myres-hero-wave">
        <svg viewBox="0 0 1440 56" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,56 L0,28 Q360,0 720,28 Q1080,56 1440,28 L1440,56 Z" fill="#f4f7fb"/>
        </svg>
    </div>
</div>

<section class="myres-section">
    <div class="container">

        @if(session('auth_notification'))
        @php
            $notif = session('auth_notification');
            $nType = $notif['icon'] ?? 'success';
            $nCfg = [
                'success' => ['glow'=>'rgba(34,197,94,.13)',  'ring'=>'rgba(34,197,94,.5),rgba(134,239,172,.3)',  'inner'=>'rgba(34,197,94,.1)',  'border'=>'rgba(34,197,94,.3)',  'stroke'=>'#4ade80', 'h'=>['rgba(255,255,255,.25)','#4ade8066','#4ade804d'], 'b'=>['#4ade8066','#4ade8059','rgba(255,255,255,.18)']],
                'error'   => ['glow'=>'rgba(220,38,38,.13)', 'ring'=>'rgba(220,38,38,.5),rgba(252,165,165,.3)', 'inner'=>'rgba(220,38,38,.1)', 'border'=>'rgba(220,38,38,.3)', 'stroke'=>'#f87171', 'h'=>['rgba(255,255,255,.25)','#f8717166','#f871714d'], 'b'=>['#f8717166','#f8717159','rgba(255,255,255,.18)']],
            ];
            $nc = $nCfg[$nType] ?? $nCfg['error'];
        @endphp
        <div class="ps-overlay" id="myres-ps-overlay">
            <div class="ps-modal">
                <div class="ps-hdr" id="myres-ps-hdr">
                    <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                    <span class="ps-parish">St. John the Baptist Parish</span>
                    <span class="ps-dot"></span>
                    <span class="ps-loc">Tiaong, Quezon</span>
                </div>
                <div class="ps-body" id="myres-ps-body">
                    <div class="ps-glow" style="background:radial-gradient(circle,{{ $nc['glow'] }} 0%,transparent 70%)"></div>
                    <div class="ps-icon">
                        <div class="ps-ring" style="background:conic-gradient({{ $nc['ring'] }},transparent 58%)"></div>
                        <div class="ps-inner" style="background:{{ $nc['inner'] }};border:1.5px solid {{ $nc['border'] }}">
                            <svg viewBox="0 0 42 42">
                                @if($nType === 'success')
                                    <polyline class="ps-warn" style="stroke:{{ $nc['stroke'] }};fill:none" points="10,22 18,30 32,14"/>
                                @else
                                    <line class="ps-warn" style="stroke:{{ $nc['stroke'] }}" x1="13" y1="13" x2="29" y2="29"/>
                                    <line class="ps-warn" style="stroke:{{ $nc['stroke'] }}" x1="29" y1="13" x2="13" y2="29"/>
                                @endif
                            </svg>
                        </div>
                    </div>
                    <h2 class="ps-title">{{ $notif['title'] }}</h2>
                    <p class="ps-sub">{{ $notif['text'] }}</p>
                    <div class="ps-btns">
                        <button type="button" class="ps-btn-solo" style="box-shadow:none" onclick="document.getElementById('myres-ps-overlay').remove()">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const colors  = ['{{ $nc['b'][0] }}','{{ $nc['b'][1] }}','{{ $nc['b'][2] }}'];
            const hColors = ['{{ $nc['h'][0] }}','{{ $nc['h'][1] }}','{{ $nc['h'][2] }}'];
            function spawnDots(el, cols, count, cls) {
                for (let i = 0; i < count; i++) {
                    const p = document.createElement('div');
                    const s = Math.random()*6+4;
                    p.className = cls;
                    p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
                    el.appendChild(p);
                }
            }
            const hdr = document.getElementById('myres-ps-hdr');
            const bdy = document.getElementById('myres-ps-body');
            if (hdr) spawnDots(hdr, hColors, 10, 'ps-hdr-particle');
            if (bdy) spawnDots(bdy, colors, 20, 'ps-particle');
            const overlay = document.getElementById('myres-ps-overlay');
            if (overlay) overlay.addEventListener('click', function (e) { if (false) this.remove(); });
        });
        </script>
        @endif

        @php
            $pending  = collect($reservations)->where('status', 'pending')->count();
            $approved = collect($reservations)->where('status', 'approved')->count();
            $declined = collect($reservations)->where('status', 'declined')->count();

            $typeIcons = [
                'wedding' => 'fa-heart',
                'baptism' => 'fa-plus',
            ];
        @endphp

        @if(count($reservations) > 0)

            <div class="res-summary">
                <button type="button" class="res-summary-chip all is-active" onclick="filterReservations('all', this)">
                    All <span class="chip-count">{{ count($reservations) }}</span>
                </button>
                @if($pending > 0)
                <button type="button" class="res-summary-chip pending" onclick="filterReservations('pending', this)">
                    <span class="res-pulse-dot dot-pending"></span> Pending <span class="chip-count">{{ $pending }}</span>
                </button>
                @endif
                @if($approved > 0)
                <button type="button" class="res-summary-chip approved" onclick="filterReservations('approved', this)">
                    <span class="res-pulse-dot dot-approved"></span> Approved <span class="chip-count">{{ $approved }}</span>
                </button>
                @endif
                @if($declined > 0)
                <button type="button" class="res-summary-chip declined" onclick="filterReservations('declined', this)">
                    <span class="res-pulse-dot dot-declined"></span> Declined <span class="chip-count">{{ $declined }}</span>
                </button>
                @endif
            </div>

            <div id="res-cards-list">

            @foreach($reservations as $res)
                @php
                    $type      = strtolower($res['event_type'] ?? 'other');
                    $status    = strtolower($res['status'] ?? 'pending');
                    $icon      = $typeIcons[$type] ?? 'fa-calendar';
                    $dateStr   = !empty($res['reservation_date'])
                        ? \Carbon\Carbon::parse($res['reservation_date'])->format('l, F j, Y')
                        : '—';
                    $submittedStr = !empty($res['created_at'])
                        ? \Carbon\Carbon::parse($res['created_at'])->format('M j, Y')
                        : null;
                    $adminNote = trim($res['admin_note'] ?? '');

                    $accentHex = match($status) {
                        'approved'  => '#16a34a',
                        'declined'  => '#dc2626',
                        'cancelled' => '#94a3b8',
                        'booked'    => '#1a56db',
                        default     => '#eab308',
                    };

                    $stateColor = ['done' => '#16a34a', 'current' => '#eab308', 'upcoming' => '#e2e8f0', 'bad' => '#dc2626', 'muted' => '#94a3b8'];
                    $stateText  = ['done' => '#16a34a', 'current' => '#b45309', 'upcoming' => '#cbd5e1', 'bad' => '#dc2626', 'muted' => '#94a3b8'];

                    $stepperSteps = match($status) {
                        'approved'  => [['label' => 'Submitted', 'state' => 'done'], ['label' => 'Reviewed', 'state' => 'done'], ['label' => 'Confirmed', 'state' => 'done']],
                        'declined'  => [['label' => 'Submitted', 'state' => 'done'], ['label' => 'Reviewed', 'state' => 'done'], ['label' => 'Declined', 'state' => 'bad']],
                        'cancelled' => [['label' => 'Submitted', 'state' => 'done'], ['label' => 'Reviewed', 'state' => 'done'], ['label' => 'Cancelled', 'state' => 'muted']],
                        'booked'    => [['label' => 'Submitted', 'state' => 'done'], ['label' => 'Reviewed', 'state' => 'done'], ['label' => 'Booked', 'state' => 'done']],
                        default     => [['label' => 'Submitted', 'state' => 'done'], ['label' => 'Under Review', 'state' => 'current'], ['label' => 'Decision', 'state' => 'upcoming']],
                    };

                @endphp

                <div class="res-card" data-status="{{ in_array($status, ['approved','declined']) ? $status : 'pending' }}" style="--accent: {{ $accentHex }};">
                    <div class="res-card-top">
                        <div class="res-card-heading">
                            <div class="res-icon-avatar {{ in_array($type, ['wedding','baptism','funeral']) ? $type : 'default' }}">
                                @if($type === 'wedding')
                                    <svg width="21" height="21" viewBox="0 0 24 24" fill="#880e4f" stroke="none"><path d="M12 21s-8.5-5.2-11-10C-.6 6.8 2 3 6 3c2.2 0 3.9 1.2 6 3.6C14.1 4.2 15.8 3 18 3c4 0 6.6 3.8 5 8-2.5 4.8-11 10-11 10z"/></svg>
                                @elseif($type === 'baptism')
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18M7 8h10"/></svg>
                                @elseif($type === 'funeral')
                                    <svg width="19" height="19" viewBox="0 0 24 24" fill="#475569" stroke="none"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg>
                                @else
                                    <i class="fa {{ $typeIcons[$type] ?? 'fa-calendar' }}" style="color:#37474f;font-size:1.1rem;"></i>
                                @endif
                            </div>
                            <div>
                                <div class="res-title">{{ ucfirst($type) }} Reservation</div>
                                @if(!empty($res['name']))
                                    <div class="res-subtitle">Requested by {{ $res['name'] }}</div>
                                @endif
                            </div>
                        </div>
                        <span class="status-badge {{ in_array($status, ['approved','declined','booked']) ? $status : 'pending' }}">
                            <span class="res-pulse-dot dot-{{ in_array($status, ['approved','declined']) ? $status : 'pending' }}"></span>
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                    @if($submittedStr)
                        <div style="margin-top:-.6rem;margin-bottom:.9rem;"><small class="text-muted" style="font-size:.8rem;">Submitted {{ $submittedStr }}</small></div>
                    @endif

                    <div class="res-meta">
                        <span class="res-meta-item"><i class="fa fa-calendar"></i> {{ $dateStr }}</span>
                        @if(!empty($res['reservation_time']))
                            <span class="res-meta-sep"></span>
                            <span class="res-meta-item"><i class="fa fa-clock-o"></i> {{ $res['reservation_time'] }}</span>
                        @endif
                    </div>

                    <div class="res-stepper">
                        @foreach($stepperSteps as $i => $step)
                            <div class="res-step">
                                <div class="res-step-dot" style="background: {{ $stateColor[$step['state']] }};{{ $step['state'] === 'current' ? ' box-shadow:0 0 0 4px '.$stateColor[$step['state']].'2e;' : '' }}">
                                    @if($step['state'] === 'done')
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    @elseif($step['state'] === 'bad')
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                    @elseif($step['state'] === 'current')
                                        <span style="width:7px;height:7px;border-radius:50%;background:#fff;display:block;"></span>
                                    @endif
                                </div>
                                <span class="res-step-label" style="color: {{ $stateText[$step['state']] }};">{{ $step['label'] }}</span>
                            </div>
                            @if(!$loop->last)
                                <div class="res-step-line" style="background: {{ $stateColor[$stepperSteps[$i + 1]['state']] }};"></div>
                            @endif
                        @endforeach
                    </div>

                    @if($adminNote !== '')
                        <div class="res-admin-note" style="border-left-color: {{ $accentHex }};">
                            <strong><i class="fa fa-comment-o"></i> Parish office note:</strong>
                            {{ $adminNote }}
                        </div>
                    @endif

                    @if($status === 'pending')
                        <p class="mt-2 mb-0" style="font-size:.82rem;color:#94a3b8;">
                            <i class="fa fa-info-circle"></i>
                            Your request is being reviewed. You will receive an email once the parish office responds.
                        </p>
                    @elseif($status === 'approved')
                        <p class="mt-2 mb-0" style="font-size:.82rem;color:#157a45;">
                            <i class="fa fa-check-circle"></i>
                            Your reservation is confirmed. Please contact the parish office for next steps.
                        </p>
                    @elseif($status === 'declined')
                        <p class="mt-2 mb-0" style="font-size:.82rem;color:#b91c1c;">
                            <i class="fa fa-phone"></i>
                            Your request was not approved. Please <a href="{{ url('/contact') }}" style="color:#b91c1c;">contact the parish office</a> for assistance.
                        </p>
                    @elseif($status === 'cancelled')
                        <p class="mt-2 mb-0" style="font-size:.82rem;color:#64748b;">
                            <i class="fa fa-times-circle"></i>
                            This reservation has been cancelled.
                        </p>
                    @endif

                    <div class="res-actions">
                        @if($status === 'declined')
                            <a href="{{ url('/contact') }}" class="btn-contact-parish">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.1-8.7A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 2 .7 3a2 2 0 0 1-.4 2.1L8 10.2a16 16 0 0 0 6 6l1.4-1.4a2 2 0 0 1 2.1-.4c1 .4 2 .6 3 .7a2 2 0 0 1 1.5 2z"/></svg>
                                Contact Parish Office
                            </a>
                        @endif
                        @if(in_array($status, ['pending', 'approved']) && empty($res['cancellation_requested']))
                            <button class="btn-cancel-req" onclick="toggleCancelForm('cancel-{{ $res['id'] }}')">
                                <i class="fa fa-times"></i> Request Cancellation
                            </button>
                        @endif
                    </div>

                    @if(in_array($status, ['pending', 'approved']))
                        @if(!empty($res['cancellation_requested']))
                            <div class="cancellation-pending-badge">
                                <i class="fa fa-clock-o"></i> Cancellation request pending — awaiting parish office review
                            </div>
                        @else
                            <div class="cancel-form-wrap" id="cancel-{{ $res['id'] }}">
                                <form method="POST" action="{{ route('reservation.cancel', $res['id']) }}">
                                    @csrf
                                    <textarea name="cancel_reason" rows="3" placeholder="Reason for cancellation (optional)…"></textarea>
                                    <button type="submit" class="btn-cancel-submit">
                                        <i class="fa fa-send"></i> Submit Request
                                    </button>
                                    <button type="button" class="btn-cancel-back" onclick="toggleCancelForm('cancel-{{ $res['id'] }}')">
                                        Never mind
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
            </div>

        @else
            <div class="myres-empty">
                <div class="myres-empty-icon"><i class="fa fa-calendar-o"></i></div>
                <h4>No reservations yet</h4>
                <p>You haven't submitted any sacrament reservation requests. When you do, they'll appear here with their current status.</p>
                <a href="{{ route('reservation.index') }}" class="boxed-btn3">
                    <i class="fa fa-calendar-plus-o"></i> Make a Reservation
                </a>
            </div>
        @endif

    </div>
</section>

@include('partials.footer')

<script>
function toggleCancelForm(id) {
    var el = document.getElementById(id);
    el.style.display = el.style.display === 'block' ? 'none' : 'block';
}

function filterReservations(status, btn) {
    document.querySelectorAll('.res-summary-chip').forEach(function (chip) {
        chip.classList.remove('is-active');
    });
    btn.classList.add('is-active');

    var cards = document.querySelectorAll('#res-cards-list .res-card');
    cards.forEach(function (card) {
        card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
    });
}
</script>
<script src="{{ asset('js/vendor/modernizr-3.5.0.min.js') }}"></script>
<script src="{{ asset('js/vendor/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('js/ajax-form.js') }}"></script>
<script src="{{ asset('js/waypoints.min.js') }}"></script>
<script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('js/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('js/scrollIt.js') }}"></script>
<script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>
<script src="{{ asset('js/wow.min.js') }}"></script>
<script src="{{ asset('js/nice-select.min.js') }}"></script>
<script src="{{ asset('js/jquery.slicknav.min.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/plugins.js') }}"></script>
<script src="{{ asset('js/gijgo.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>

</body>
</html>
