<!doctype html>
<html class="no-js" lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Reset Password | St. John the Baptist Parish</title>
    <meta name="description" content="Choose a new password to access your St. John the Baptist Parish customer account.">
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
    <h3 style="color: white;">Reset Password</h3>
</div>

<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="auth-card">
                    <div class="row no-gutters">
                        <div class="col-md-5 auth-card__media">
                            <div class="auth-card__media-inner">
                                <span class="auth-badge"><i class="fa fa-shield" aria-hidden="true"></i> Secure update</span>
                                <h3>Choose a strong new password</h3>
                                <p>Protect your parish reservation account with a refreshed password and continue planning important celebrations.</p>
                                <ul class="auth-benefits">
                                    <li>Safeguard your reservation history</li>
                                    <li>Keep family details protected</li>
                                    <li>Continue coordinating sacraments with ease</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="auth-card__content">
                                <h3 class="text-center mb-3">Create a new password</h3>
                                <p class="text-center mb-4">Enter and confirm your new password below to secure your account.</p>

                                @if(session('error'))
                                    <div class="alert alert-danger auth-alert" role="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger auth-alert" role="alert">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                @if(session('success'))
                                    <div class="alert alert-success auth-alert" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if($canShowForm)
                                    <form class="auth-form" method="POST" action="{{ route('password.update') }}" data-loading-form>
                                        @csrf

                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <div class="form-group">
                                            <label for="password">New password</label>
                                            <div class="input-with-icon">
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="confirm_password">Confirm new password</label>
                                            <div class="input-with-icon">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                            </div>
                                        </div>

                                        <button type="submit" class="auth-button" data-loading-button>
                                            <span>Update password</span>
                                            <span class="spinner-border spinner-border-sm align-middle ml-2 d-none"
                                                  role="status"
                                                  aria-hidden="true"
                                                  data-loading-spinner></span>
                                        </button>
                                    </form>
                                @else
                                    <div class="auth-help">
                                        <i class="fa fa-life-ring" aria-hidden="true"></i>
                                        <span>
                                            Need a new link?
                                            <a href="{{ route('password.request') }}">Request another password reset email</a>.
                                        </span>
                                    </div>
                                @endif

                                <p class="auth-footer-text">
                                    Remembered your password?
                                    <a href="{{ route('login') }}">Return to the login page</a>.
                                </p>
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