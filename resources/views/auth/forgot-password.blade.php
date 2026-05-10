<!doctype html>
<html class="no-js" lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Forgot Password | St. John the Baptist Parish</title>
    <meta name="description" content="Reset your St. John the Baptist Parish customer password to regain access to reservations.">
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
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-body">

@include('partials.header')

<div class="bradcam_area breadcam_bg" style="background: #563e9e;">
    <h3 style="color: white;">Forgot Password</h3>
</div>

<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="auth-card">
                    <div class="row no-gutters">

                        <!-- LEFT -->
                        <div class="col-md-5 auth-card__media">
                            <div class="auth-card__media-inner">
                                <span class="auth-badge">
                                    <i class="fa fa-envelope-open"></i> Reset access
                                </span>
                                <h3>We'll help you get back in</h3>
                                <p>Request a secure link to update your password and return to managing your sacramental reservations.</p>
                                <ul class="auth-benefits">
                                    <li>Keep your reservation details safe</li>
                                    <li>Update access in just a few steps</li>
                                    <li>Receive instant confirmation via email</li>
                                </ul>
                            </div>
                        </div>

                        <!-- RIGHT -->
                        <div class="col-md-7">
                            <div class="auth-card__content">

                                <h3 class="text-center mb-3">Forgot your password?</h3>
                                <p class="text-center mb-4">
                                    Enter the email associated with your account and we'll send a reset link within moments.
                                </p>

                                {{-- ERROR --}}
                                @if(session('error'))
                                    <div class="alert alert-danger auth-alert">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                {{-- SUCCESS --}}
                                @if(session('success'))
                                    <div class="alert alert-success auth-alert">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <form class="auth-form"
                                      method="POST"
                                      action="{{ route('password.email') }}"
                                      data-loading-form>

                                    @csrf

                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <div class="input-with-icon">
                                            <i class="fa fa-envelope"></i>
                                            <input type="email"
                                                   class="form-control"
                                                   id="email"
                                                   name="email"
                                                   placeholder="name@example.com"
                                                   required
                                                   value="{{ old('email') }}">
                                        </div>
                                    </div>

                                    <button type="submit" class="auth-button" data-loading-button>
                                        <span>Send reset link</span>
                                        <span class="spinner-border spinner-border-sm align-middle ml-2 d-none"
                                              role="status"
                                              aria-hidden="true"
                                              data-loading-spinner></span>
                                    </button>

                                </form>

                                <div class="auth-help">
                                 <a href="{{ route('login') }}" class="back-arrow">
                                 <i class="fa fa-arrow-left"></i>
                                 </a>
                                    <span>
                                        Remembered your password?
                                        <a href="{{ route('login') }}" class="auth-link">Return to sign in.</a>
                                    </span>
                                </div>

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
<script src="{{ asset('js/form-loading.js') }}"></script>

</body>
</html>