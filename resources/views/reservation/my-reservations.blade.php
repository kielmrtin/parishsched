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
            background: #009ddc;
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
        .myres-btn-primary:hover { background: #007bb5; transform: translateY(-1px); }

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

        /* ---- RESERVATION CARD ---- */
        .res-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(10,31,68,.08);
            padding: 1.6rem 1.8rem;
            margin-bottom: 1.2rem;
            transition: box-shadow .2s;
        }
        .res-card:hover { box-shadow: 0 8px 32px rgba(10,31,68,.13); }

        .res-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .res-type-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            padding: .3rem .85rem;
            border-radius: 50px;
        }
        .res-type-badge.wedding  { background: #fce4ec; color: #880e4f; }
        .res-type-badge.baptism  { background: rgba(22,163,74,.12); color: #16a34a; }
        .res-type-badge.funeral  { background: rgba(100,116,139,.13); color: #475569; }
        .res-type-badge.default  { background: #eceff1; color: #37474f; }

        .res-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem 1.4rem;
            font-size: .9rem;
            color: #64748b;
            margin-bottom: .9rem;
        }
        .res-meta span { display: flex; align-items: center; gap: .35rem; }
        .res-meta i { opacity: .65; }

        .res-admin-note {
            background: #f8fafc;
            border-left: 3px solid #cbd5e1;
            border-radius: 0 8px 8px 0;
            padding: .65rem 1rem;
            font-size: .875rem;
            color: #475569;
            margin-top: .75rem;
        }
        .res-admin-note strong { color: #334155; }

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

        /* ---- SUMMARY CHIPS ---- */
        .res-summary {
            display: flex;
            gap: .6rem;
            flex-wrap: wrap;
            margin-bottom: 1.8rem;
        }
        .res-summary-chip {
            background: #fff;
            border-radius: 50px;
            padding: .4rem 1rem;
            font-size: .83rem;
            font-weight: 600;
            color: #475569;
            box-shadow: 0 2px 8px rgba(10,31,68,.07);
        }
        .res-summary-chip span { font-weight: 700; }
        .res-summary-chip.pending  span { color: #b45309; }
        .res-summary-chip.approved span { color: #157a45; }
        .res-summary-chip.declined span { color: #b91c1c; }

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

        @if(session('success'))
            <div class="alert alert-success mb-4" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-4" role="alert">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
            </div>
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
                <div class="res-summary-chip">
                    <span>{{ count($reservations) }}</span> total
                </div>
                @if($pending > 0)
                <div class="res-summary-chip pending">
                    <span>{{ $pending }}</span> pending
                </div>
                @endif
                @if($approved > 0)
                <div class="res-summary-chip approved">
                    <span>{{ $approved }}</span> approved
                </div>
                @endif
                @if($declined > 0)
                <div class="res-summary-chip declined">
                    <span>{{ $declined }}</span> declined
                </div>
                @endif
            </div>

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
                    $statusIcon = match($status) {
                        'approved' => 'fa-check-circle',
                        'declined' => 'fa-times-circle',
                        'booked'   => 'fa-calendar-check-o',
                        default    => 'fa-clock-o',
                    };
                @endphp

                <div class="res-card">
                    <div class="res-card-top">
                        <div class="d-flex align-items-center gap-2" style="gap:.6rem;display:flex;flex-wrap:wrap;">
                            <span class="res-type-badge {{ in_array($type, ['wedding','baptism','funeral']) ? $type : 'default' }}">
                                @if($type === 'funeral')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width=".82em" height=".82em" fill="currentColor" style="vertical-align:-.05em;"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg>
                                @else
                                    <i class="fa {{ $typeIcons[$type] ?? 'fa-calendar' }}"></i>
                                @endif
                                {{ ucfirst($type) }}
                            </span>
                            <span class="status-badge {{ in_array($status, ['approved','declined','booked']) ? $status : 'pending' }}">
                                <i class="fa {{ $statusIcon }}"></i> {{ ucfirst($status) }}
                            </span>
                        </div>
                        @if($submittedStr)
                            <small class="text-muted" style="font-size:.8rem;">Submitted {{ $submittedStr }}</small>
                        @endif
                    </div>

                    <div class="res-meta">
                        <span><i class="fa fa-calendar"></i> {{ $dateStr }}</span>
                        @if(!empty($res['reservation_time']))
                            <span><i class="fa fa-clock-o"></i> {{ $res['reservation_time'] }}</span>
                        @endif
                        @if(!empty($res['name']))
                            <span><i class="fa fa-user"></i> {{ $res['name'] }}</span>
                        @endif
                    </div>

                    @if($adminNote !== '')
                        <div class="res-admin-note">
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

                    @if(in_array($status, ['pending', 'approved']))
                        @if(!empty($res['cancellation_requested']))
                            <div class="cancellation-pending-badge">
                                <i class="fa fa-clock-o"></i> Cancellation request pending — awaiting parish office review
                            </div>
                        @else
                            <div class="mt-3">
                                <button class="btn-cancel-req" onclick="toggleCancelForm('cancel-{{ $res['id'] }}')">
                                    <i class="fa fa-times"></i> Request Cancellation
                                </button>
                            </div>
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
