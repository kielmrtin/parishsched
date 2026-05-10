<!doctype html>
<html class="no-js" lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Create an Account | St. John the Baptist Parish</title>
    <meta name="description" content="Register for a St. John the Baptist Parish reservation account in Tiaong, Quezon.">
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

    <style>
.otp-send-form {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    margin-bottom: 14px;
}

.otp-send-btn {
    border-radius: 999px;
    font-weight: 700;
    padding: 7px 18px;
}

.otp-sent-text {
    color: #16a34a;
    font-size: 13px;
    font-weight: 700;
}

.auth-button:disabled {
    opacity: 0.55;
    cursor: not-allowed;
    filter: grayscale(20%);
}
</style>

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
                                    <a class="boxed-btn3" href="{{ route('login') }}">Back to login</a>
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
        <h3 style="color: white;">Create an Account</h3>
    </div>

    <section class="auth-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-11">
                    <div class="auth-card">
                        <div class="row no-gutters">
                            <div class="col-md-5 auth-card__media">
                                <div class="auth-card__media-inner">
                                    <span class="auth-badge"><i class="fa fa-star" aria-hidden="true"></i> Join the community</span>
                                    <h3 style="color: white;">Create your parish account</h3>
                                    <p>Register once to streamline every future reservation and stay informed about parish life.</p>
                                    <ul class="auth-benefits">
                                        <li>Manage baptism, wedding, and mass bookings</li>
                                        <li>Store your family information securely</li>
                                        <li>Receive reminders for upcoming celebrations</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="auth-card__content">
                                    <h3 class="text-center mb-3">Create an Account</h3>
                                    <p class="text-center mb-4">We’ll save your details securely so every reservation request takes only a moment.</p>

                                    @if ($errors->any())
                                        <div class="alert alert-danger auth-alert" role="alert">
                                            <ul class="mb-0 pl-3 text-left">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form class="auth-form" method="POST" action="{{ route('register') }}">
                                        @csrf

                                        <div class="form-group">
                                            <label for="name">Full name</label>
                                            <div class="input-with-icon">
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Your full name"
                                                    required value="{{ old('name') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email address</label>
                                            <div class="input-with-icon">
                                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="name@example.com" required value="{{ old('email') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
    <label for="phone">Phone number</label>
    <div class="input-with-icon">
        <i class="fa fa-phone" aria-hidden="true"></i>
        <input type="text" required
               class="form-control"
               id="phone"
               name="phone"
               placeholder="09XXXXXXXXX"
               value="{{ old('phone') }}">
    </div>
</div>

<div class="otp-send-form">
    <button type="button" id="sendOtpBtn" class="btn btn-sm btn-outline-primary otp-send-btn">
        Send OTP
    </button>

    <span id="otpSentText" class="otp-sent-text d-none">
        ✓ OTP sent
    </span>
</div>

<div class="form-group">
    <label for="otp_code">Phone verification code</label>
    <div class="input-with-icon">
        <i class="fa fa-shield" aria-hidden="true"></i>
        <input type="text" required
               class="form-control"
               id="otp_code"
               name="otp_code"
               placeholder="Enter 6-digit OTP"
               maxlength="6"
               value="{{ old('otp_code') }}">
    </div>
    <small class="form-text text-muted">Click Send OTP first, then enter the code sent to your phone.</small>
</div>


                                        <div class="form-group">
                                            <label for="address">Home address</label>
                                            <div class="input-with-icon input-with-icon--textarea">
                                                <i class="fa fa-home" aria-hidden="true"></i>
                                                <textarea class="form-control" id="address" name="address" required
                                                    placeholder="Street, city, province">{{ old('address') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="password">Password</label>
                                                <div class="input-with-icon">
                                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                                    <input type="password" class="form-control" id="password" name="password" required>
                                                </div>
                                                <small class="form-text text-muted">Must be at least 8 characters.</small>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="confirm_password">Confirm password</label>
                                                <div class="input-with-icon">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                    <input type="password" class="form-control" id="confirm_password" name="password_confirmation" required>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="auth-button" id="createAccountBtn" disabled>
    <span>Create account</span>
    <span class="spinner-border spinner-border-sm align-middle ml-2 d-none" role="status" aria-hidden="true" data-loading-spinner></span>
</button>
                                    </form>

                                    <div class="auth-help">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        <span>By signing up you’ll receive updates about parish events and reservations.</span>
                                    </div>

                                    <p class="auth-footer-text">Already registered? <a href="{{ route('login') }}">Log in to your account</a>.</p>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('phone');
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const otpSentText = document.getElementById('otpSentText');
    const otpInput = document.getElementById('otp_code');
    const createBtn = document.getElementById('createAccountBtn');

    sendOtpBtn.addEventListener('click', function () {
        const phone = phoneInput.value.trim();

        if (!phone) {
            alert('Please enter your phone number first.');
            return;
        }

        sendOtpBtn.disabled = true;
        sendOtpBtn.textContent = 'Sending...';

        fetch("{{ route('register.sendOtp') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(response => {
            if (!response.ok) throw new Error('OTP failed');
            otpSentText.classList.remove('d-none');

            let count = 30;
            const timer = setInterval(() => {
                sendOtpBtn.textContent = 'Resend in ' + count + 's';
                count--;

                if (count < 0) {
                    clearInterval(timer);
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.textContent = 'Resend OTP';
                }
            }, 1000);
        })
        .catch(() => {
            sendOtpBtn.disabled = false;
            sendOtpBtn.textContent = 'Send OTP';
            alert('Failed to send OTP. Please try again.');
        });
    });

    otpInput.addEventListener('input', function () {
        createBtn.disabled = otpInput.value.trim().length !== 6;
    });
});
</script>
    
    
</body>
</html>