<style>
    /* Donations icon before Home: an outlined box like the
       My Reservations button. Red on white headers, white on the dark
       homepage header (the script below tells them apart). */
    #navigation > li.nav-give { margin-right: 30px; }
    #navigation > li.nav-give > a {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        width: 38px; height: 38px; padding: 0 !important; border-radius: 6px;
        border: 2px solid currentColor; position: relative; top: 11px;
        transition: background .2s, color .2s, border-color .2s;
    }
    #navigation > li.nav-give > a::before { display: none !important; }
    .nav-give-icon { width: 20px; height: 20px; flex-shrink: 0; transition: transform .2s ease; }
    #navigation > li.nav-give > a:hover .nav-give-icon { transform: translateY(-1px) scale(1.08); }
    .nav-on-light #navigation > li.nav-give > a { color: #dc2626 !important; border-color: #dc2626; }
    .nav-on-light #navigation > li.nav-give > a:hover { background: rgba(220,38,38,.1); }
    .nav-on-dark #navigation > li.nav-give > a { color: #fff !important; border-color: #fff; }
    .nav-on-dark #navigation > li.nav-give > a:hover { background: rgba(255,255,255,.15); }
    .nav-give-text { display: none; }
    /* the phone menu (slicknav copy) shows the words next to the icon */
    .slicknav_nav .nav-give-text { display: inline; }
    .slicknav_nav .nav-give-icon { width: 20px; height: 20px; }
    .slicknav_nav li.nav-give a { display: flex; align-items: center; gap: 8px; color: #dc2626 !important; }

    /* Sticky bar: when it comes back after scrolling down, glide in from fully
       above the screen instead of starting half-visible and jumping
       (desktop; login/register keep their always-fixed bar). */
    @media (min-width: 992px) {
        html body:not(.auth-body) #sticky-header.sticky {
            top: 0 !important;
            transform: none;
            animation: ps-nav-in .45s cubic-bezier(.22,.61,.36,1) both;
        }
    }
    @keyframes ps-nav-in { from { transform: translateY(-100%); } to { transform: translateY(0); } }
    @media (prefers-reduced-motion: reduce) { html body #sticky-header.sticky { animation: none; } }
</style>
<header>
    <style>
        .home-header .myres-icon-btn { color:#fff !important; border-color:#fff !important; }
        .home-header .myres-icon-btn:hover { background:rgba(255,255,255,.15) !important; }
    </style>
    <div class="header-area {{ request()->is('/') || request()->is('about') ? 'home-header' : '' }} {{ request()->is('login') || request()->is('register') ? 'auth-header' : '' }}">
        <div id="sticky-header" class="main-header-area">
            <div class="container-fluid p-0">
                <div class="row align-items-center no-gutters">

                    <!-- LEFT MENU -->
                    <div class="col-xl-5 col-lg-6 d-none d-lg-block order-lg-1">
                        <div class="main-menu d-none d-lg-block">
                            <nav>
                                <ul id="navigation">
                                    <li class="nav-give"><a class="{{ request()->routeIs('donate.*') ? 'active' : '' }}" href="{{ route('donate.index') }}" title="Donations" aria-label="Donations"><svg class="nav-give-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path transform="translate(7.2 .2) scale(.52)" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/><path d="M1.2 14.2c1.4-.9 3-1.4 4.7-1.4 1.4 0 2.7.4 3.9 1l1.6.8h4.1c.8 0 1.4.6 1.4 1.4 0 .2 0 .3-.1.5l3.8-1.9c.7-.3 1.5-.1 1.9.6.3.6.1 1.3-.4 1.7l-6.4 4.3c-.7.5-1.6.7-2.5.6L6 21.2c-.5-.1-1-.1-1.5 0l-3.3.5v-7.5z"/></svg><span class="nav-give-text">Donations</span></a></li>
                                    <li><a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                                    <li><a class="{{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a></li>
                                    <li><a class="{{ request()->is('schedule') ? 'active' : '' }}" href="{{ url('/schedule') }}">Schedule</a></li>
                                    <li><a class="{{ request()->is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">Inquire</a></li>
                                    @if(session('customer_id'))
                                        <li class="d-lg-none"><a href="{{ route('reservation.index') }}">Reserve Now</a></li>
                                        <li class="d-lg-none"><a href="{{ route('reservation.my') }}">My Reservations</a></li>
                                        <li class="d-lg-none"><a href="{{ route('logout') }}">Log out ({{ session('customer_name') }})</a></li>
                                    @else
                                        <li class="d-lg-none"><a href="{{ route('login') }}">Login</a></li>
                                        <li class="d-lg-none"><a href="{{ route('register') }}">Create Account</a></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <!-- CENTER LOGO (desktop only) -->
                    <div class="col-xl-2 col-lg-2 d-none d-lg-flex order-lg-2 align-items-center justify-content-center">
                        <div class="logo-img">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('img/logo-160.webp') }}"
                                     alt="St. John the Baptist Parish logo"
                                     style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block;">
                            </a>
                        </div>
                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="col-xl-5 col-lg-4 d-none d-lg-block order-lg-3">
                        <div class="book_room">

                            <div class="socail_links">
                                <ul>
                                    <li>
                                        <a href="https://www.facebook.com/officialstjohnthebaptistparishtiaong"
                                           target="_blank" rel="noopener">
                                            <i class="fa fa-facebook-square"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="mailto:stjohnbaptisttiaongparish@gmail.com">
                                            <i class="fa fa-envelope"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="tel:+63425459244">
                                            <i class="fa fa-phone"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>

     @if(session('customer_id'))
    <div class="user-info d-flex flex-column align-items-end">

        <!-- NAME + BUTTON (aligned like nav) -->
        <div class="d-flex align-items-center">
            <span class="signed-text mr-3">
                Signed in as <strong>{{ session('customer_name') }}</strong>
            </span>

            <a class="boxed-btn3 reserve-btn" href="{{ route('reservation.index') }}">
                Reserve Now
            </a>

            <a href="{{ route('reservation.my') }}" title="My Reservations" class="myres-icon-btn" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border:2px solid #dc2626;border-radius:6px;color:#dc2626;font-size:1rem;margin-left:8px;text-decoration:none;transition:background .2s,color .2s;" onmouseover="this.style.background='rgba(220,38,38,.1)';" onmouseout="this.style.background='transparent';">
                <i class="fa fa-calendar-check-o"></i>
            </a>
        </div>

        <!-- LOGOUT BELOW NAME -->
        <a href="{{ route('logout') }}" class="logout-text-link">
            Log out
        </a>

    </div>
@else
    <div class="book_btn d-none d-lg-flex align-items-center">
        <a class="boxed-btn3" href="{{ route('login') }}">Login</a>
        <a class="boxed-btn3 ml-2" href="{{ route('register') }}">Create Account</a>
    </div>
@endif

                        </div>
                    </div>

                    <!-- MOBILE BRAND + MENU -->
                    <div class="col-12 d-lg-none d-flex align-items-center justify-content-end mobile-brand-row">
                        <a href="{{ route('home') }}" class="mobile-brand-text">ParishSched</a>
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>
<script>
// Light or dark header? Checked on load and while scrolling, since the
// header turns white when it becomes sticky.
(function () {
    var header = document.getElementById('sticky-header');
    if (!header) return;
    // Only the bar itself and its wrapper count: when both are see-through
    // the bar sits on a dark hero photo.
    function isLight(el) {
        for (var i = 0; el && i < 2; i++, el = el.parentElement) {
            var m = getComputedStyle(el).backgroundColor.match(/[\d.]+/g);
            if (m && (m.length < 4 || +m[3] > 0.5)) return (0.299 * m[0] + 0.587 * m[1] + 0.114 * m[2]) > 186;
        }
        return false;
    }
    function update() {
        var light = isLight(header);
        header.classList.toggle('nav-on-light', light);
        header.classList.toggle('nav-on-dark', !light);
    }
    update();

    // Where the bar sits in the page flow (white-header pages), keep its space
    // when it turns sticky so the content below doesn't jump up.
    var area = header.closest('.header-area');
    function reserveSpace() {
        if (!area || header.classList.contains('sticky')) return;
        var pos = getComputedStyle(area).position;
        area.style.minHeight = (pos === 'absolute' || pos === 'fixed') ? '' : header.offsetHeight + 'px';
    }
    reserveSpace();
    window.addEventListener('resize', reserveSpace);
    window.addEventListener('load', reserveSpace);

    window.addEventListener('scroll', function () { requestAnimationFrame(update); }, { passive: true });
    window.addEventListener('load', update);
    header.addEventListener('transitionend', update);
})();
</script>
