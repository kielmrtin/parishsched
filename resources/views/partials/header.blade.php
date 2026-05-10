<header>
    <div class="header-area {{ request()->is('/') || request()->is('about') ? 'home-header' : '' }}">
        <div id="sticky-header" class="main-header-area">
            <div class="container-fluid p-0">
                <div class="row align-items-center no-gutters">

                    <!-- LEFT MENU -->
                    <div class="col-xl-5 col-lg-6 d-none d-lg-block order-lg-1">
                        <div class="main-menu d-none d-lg-block">
                            <nav>
                                <ul id="navigation">
                                    <li><a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                                    <li><a class="{{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a></li>
                                    <li><a class="{{ request()->is('schedule') ? 'active' : '' }}" href="{{ url('/schedule') }}">Schedule</a></li>
                                    <li><a class="{{ request()->is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">Inquire</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <!-- CENTER LOGO -->
                    <div class="col-xl-2 col-lg-2 col-6 order-lg-2 d-flex align-items-center justify-content-center">
                        <div class="logo-img">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('img/about/about_1.jpg') }}"
                                     height="60"
                                     alt="St. John the Baptist Parish logo"
                                     style="border-radius: 50px;">
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

                    <!-- MOBILE MENU -->
                    <div class="col-6 d-lg-none d-flex justify-content-end">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>