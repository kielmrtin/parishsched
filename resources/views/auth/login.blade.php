<!doctype html>
<html class="no-js" lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Customer Login | St. John the Baptist Parish</title>
    <meta name="description" content="Access your St. John the Baptist Parish reservation account for Tiaong, Quezon.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.jpg') }}">

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
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-body">
    <header>
        <div class="header-area">
            <div id="sticky-header" class="main-header-area">
                <div class="container-fluid p-0">
                    <div class="row align-items-center no-gutters">
                        <div class="col-xl-5 col-lg-6 d-none d-lg-block order-lg-1">
                            <div class="main-menu d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <li><a href="{{ route('home') }}">Home</a></li>
                                        <li><a href="{{ route('about') }}">About</a></li>
                                        <li><a href="{{ route('schedule') }}">Schedule</a></li>
                                        <li><a href="{{ route('contact') }}">Inquire</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-2 col-6 order-lg-2 d-flex align-items-center justify-content-center">
                            <div class="logo-img">
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset('img/about/about_1.jpg') }}" height="60" alt="St. John the Baptist Parish logo" style="border-radius: 50px;">
                                </a>
                            </div>
                        </div>

                        <div class="col-xl-5 col-lg-4 d-none d-lg-block order-lg-3">
                            <div class="book_room">
                                <div class="socail_links">
                                    <ul>
                                        <li>
                                            <a href="https://www.facebook.com/officialstjohnthebaptistparishtiaong" target="_blank" rel="noopener" aria-label="Facebook">
                                                <i class="fa fa-facebook-square"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="mailto:stjohnbaptisttiaongparish@gmail.com" aria-label="Email">
                                                <i class="fa fa-envelope"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="tel:+63425459244" aria-label="Call">
                                                <i class="fa fa-phone"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="book_btn d-none d-lg-block">
                                    <a class="boxed-btn3" href="{{ route('register') }}">Create an account</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 d-lg-none d-flex justify-content-end">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="bradcam_area breadcam_bg" style="background: #563e9e;">
        <h3 style="color: white;">Customer Login</h3>
    </div>

    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-11">
                    <div class="auth-card">
                        <div class="row no-gutters">
                            <div class="col-md-5 auth-card__media">
                                <div class="auth-card__media-inner">
                                    <span class="auth-badge"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Welcome back</span>
                                    <h3 style="color: white;">Effortless reservations</h3>
                                    <p>Sign in to manage your sacramental reservations with ease and stay connected with the parish.</p>
                                    <ul class="auth-benefits">
                                        <li>Track upcoming ceremonies and commitments</li>
                                        <li>Update family information in moments</li>
                                        <li>Receive confirmations straight to your inbox</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="auth-card__content">
                                    <h3 class="text-center mb-3">Customer Login</h3>
                                    <p class="text-center mb-4">Access your reservation requests and saved details anytime, anywhere.</p>


                                    <form class="auth-form" method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <div class="form-group">
                                            <label for="email">Email address</label>
                                            <div class="input-with-icon">
                                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="name@example.com" required
                                                    value="{{ old('email') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <div class="input-with-icon">
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                            </div>
                                        </div>

                                        <div class="auth-forgot-link">
                                            <a href="{{ route('password.request') }}">Forgot your password?</a>
                                        </div>

                                        <button type="submit" class="auth-button">
                                            <span>Log in</span>
                                            <span class="spinner-border spinner-border-sm align-middle ml-2 d-none" role="status" aria-hidden="true" data-loading-spinner></span>
                                        </button>
                                    </form>

                                    <div class="auth-help">
                                        <i class="fa fa-life-ring" aria-hidden="true"></i>
                                        <span>Need assistance? <a href="{{ route('contact') }}">Reach out to our parish team</a>.</span>
                                    </div>

                                    <p class="auth-footer-text">Don't have an account yet? <a href="{{ route('register') }}">Create one in a minute</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

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
    <script src="{{ asset('js/contact.js') }}"></script>
    <script src="{{ asset('js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('js/jquery.form.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/mail-script.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('auth_notification'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const notif = @json(session('auth_notification'));

    Swal.fire({
        icon: notif.icon || 'info',
        title: notif.title || '',
        text: notif.text || '',
        confirmButtonColor: '#009DFF'
    });
});
</script>
@endif

</body>
</html>