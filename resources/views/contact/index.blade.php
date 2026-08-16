<!doctype html>
<html class="no-js" lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Inquire | St. John the Baptist Parish</title>
    <meta name="description" content="Reach out to St. John the Baptist Parish for reservations, sacramental requests, and pastoral care support in Tiaong, Quezon.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS here -->
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
    <link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">

    <style>
        
        .logo-img img {
            border-radius: 50px;
            height: 60px;
            width: 60px;
            object-fit: cover;
        }

        /* ---- HERO BANNER ---- */
        .contact-hero {
            position: relative;
            background: url('{{ asset('img/banner/bradcam3.jpg') }}') center center / cover no-repeat;
            overflow: hidden;
            padding: 80px 24px 96px;
            text-align: center;
            isolation: isolate;
        }

        .contact-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.52);
            pointer-events: none;
            z-index: 0;
        }

        .contact-hero::after { display: none; }

        .contact-hero-inner {
            position: relative;
            z-index: 1;
            max-width: 680px;
            margin: 0 auto;
        }

        .contact-hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: #fca5a5;
            background: rgba(220,38,38,.2);
            border: 1px solid rgba(220,38,38,.4);
            padding: 7px 18px;
            border-radius: 999px;
            margin-bottom: 26px;
        }

        .contact-hero h1 {
            font-size: clamp(2rem, 3.5vw + 1rem, 3rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.18;
            margin-bottom: 18px;
        }

        .contact-hero h1 em {
            font-style: normal;
            color: #fff;
        }

        .contact-hero-sub {
            font-size: 1.05rem;
            color: rgba(255,255,255,.72);
            line-height: 1.75;
            max-width: 520px;
            margin: 0 auto;
        }

        .contact-hero-wave {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            line-height: 0;
            z-index: 1;
        }

        /* CONTACT PAGE UI - SAME AS YOUR OLD PAGE */
        .contact-section {
            position: relative;
            background: #eef2fb;
            padding: 50px 0 100px;
            overflow: hidden;
        }

        .contact-section::before,
        .contact-section::after {
            display: none;
        }

        .contact-section .container {
            position: relative;
            z-index: 2;
        }

        .contact-intro {
            max-width: 760px;
            margin: 0 auto 3rem;
        }

        .contact-intro .section-subtitle {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #2e69ff;
            font-weight: 600;
        }

        .contact-intro h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .contact-wrapper {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 32px 80px rgba(10, 31, 68, 0.12);
            overflow: hidden;
        }

        .contact-form-panel,
        .contact-details-panel {
            padding: 3rem;
        }

        .contact-details-panel {
            background: linear-gradient(160deg, #7f1d1d 0%, #dc2626 100%);
            color: #ffffff;
        }

        .contact-details-panel h3,
        .contact-details-panel p,
        .contact-details-panel li,
        .contact-details-panel a {
            color: #ffffff;
        }

        .contact-details-panel a {
            text-decoration: underline;
        }

        .contact-details-panel .contact-card {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 1.5rem;
            backdrop-filter: blur(6px);
            box-shadow: 0 16px 32px rgba(5, 22, 54, 0.2);
            margin-bottom: 1.5rem;
        }

        .contact-details-panel .contact-card:last-child {
            margin-bottom: 0;
        }

        .contact-icon-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }

        .contact_form label {
            font-weight: 600;
            color: #2a2a2a;
        }

        .contact_form .form-control {
            border-radius: 14px;
            border: 1px solid rgba(19, 52, 119, 0.18);
            padding: 0.85rem 1.1rem;
            font-size: 1rem;
            box-shadow: none;
            transition: all 0.2s ease;
        }

        .contact_form .form-control:focus {
            border-color: #2e69ff;
            box-shadow: 0 0 0 0.2rem rgba(46, 105, 255, 0.12);
        }

        .contact-highlight-grid {
            margin-top: 3.5rem;
        }

        .highlight-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 24px 60px rgba(10, 31, 68, 0.08);
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .highlight-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 32px 80px rgba(10, 31, 68, 0.16);
        }

        .highlight-card h4 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #133477;
        }

        .highlight-card p {
            color: #4f5d75;
            margin-bottom: 0;
        }

        .highlight-card a {
            font-weight: 600;
            color: #2e69ff;
        }

        .highlight-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(46, 105, 255, 0.12);
            color: #2e69ff;
            font-size: 1.3rem;
            margin-bottom: 1.2rem;
        }

        @media (max-width: 991.98px) {
            .contact-form-panel,
            .contact-details-panel {
                padding: 2.2rem;
            }

            .contact-details-panel {
                border-radius: 0 0 24px 24px;
            }
        }

        @media (max-width: 767.98px) {
            .contact-intro h2 {
                font-size: 2.1rem;
            }

            .contact-form-panel,
            .contact-details-panel {
                padding: 2rem 1.6rem;
            }

            .highlight-card {
                padding: 1.8rem;
            }
        }
    </style>
</head>

<body class="schedule-page" style="overflow-x:hidden;">

@include('partials.header')

<section class="contact-hero">
    <div class="contact-hero-inner">
        <div class="contact-hero-eyebrow">
            <i class="fa fa-paper-plane" style="color:#fff;"></i> Parish Inquiries
        </div>
        <h1>We are here to <em>listen,<br>guide, and pray</em> with you</h1>
        <p class="contact-hero-sub">Share your intentions, plan a celebration, or simply say hello. Our parish team in Tiaong is ready to accompany you with warmth and compassion.</p>
    </div>
    <div class="contact-hero-wave">
        <svg viewBox="0 0 1440 56" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="height:40px;width:100%;display:block;">
            <path d="M0,56 L0,28 Q360,0 720,28 Q1080,56 1440,28 L1440,56 Z" fill="#eef2fb"/>
        </svg>
    </div>
</section>

<section class="contact-section">
    <div class="container">

        <div class="contact-wrapper">
            <div class="row g-0">

                <!-- FORM SIDE -->
                <div class="col-lg-6 contact-form-panel">
                    <h3 class="mb-4">Send us a message</h3>
                    <p class="mb-4">
                        Let us know how we can support you. Messages go directly to our parish staff who respond within one business day.
                    </p>

                    <form class="contact_form" id="contact-form" action="{{ route('contact.send') }}" method="post" novalidate>
                        @csrf

                        <div class="form-group">
                            <label for="contact-name">Name *</label>
                            <input class="form-control" type="text" id="contact-name" name="name" placeholder="Full name" required>
                        </div>

                        <div class="form-group">
                            <label for="contact-email">Email *</label>
                            <input class="form-control" type="email" id="contact-email" name="email" placeholder="name@example.com" required>
                        </div>

                        <div class="form-group">
                            <label for="contact-phone">Phone</label>
                            <input class="form-control" type="tel" id="contact-phone" name="phone" placeholder="(042) 545-9244">
                        </div>

                        <div class="form-group">
                            <label for="contact-message">Message *</label>
                            <textarea class="form-control" id="contact-message" name="message" rows="5" placeholder="How can we help you?" required></textarea>
                        </div>

                        <button type="submit" class="boxed-btn3">
                            <i class="fa fa-send"></i> Send Message
                        </button>

                        <div id="contact-success" class="alert alert-success mt-4 d-none" role="alert" tabindex="-1">
                            Thank you for reaching out! Our parish staff will respond as soon as possible.
                        </div>

                        <div id="contact-error" class="alert alert-danger mt-4 d-none" role="alert" tabindex="-1">
                            We could not send your message. Please try again later or contact us by phone.
                        </div>
                    </form>
                </div>

                <!-- DETAILS SIDE -->
                <div class="col-lg-6 contact-details-panel">
                    <h3 class="mb-4">Visit the parish office</h3>
                    <p class="mb-4">
                        Find us along Maharlika Highway near the Tiaong town plaza. Tricycles and jeepneys pass the church regularly, and parking is available beside the parish hall.
                    </p>

                    <div class="contact-card">
                        <div class="contact-icon-circle" aria-hidden="true">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <h5 class="mb-2" style="color: white;">Our Address</h5>
                        <p class="mb-0">
                            Maharlika Highway, Barangay Poblacion II,<br>
                            Tiaong, Quezon, Philippines
                        </p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon-circle" aria-hidden="true">
                            <i class="fa fa-phone"></i>
                        </div>
                        <h5 class="mb-2" style="color: white;">Call or Message</h5>
                        <p class="mb-0">
                            <a href="tel:+63425459244">(042) 545-9244</a><br>
                            <a href="mailto:stjohnbaptisttiaongparish@gmail.com">
                                stjohnbaptisttiaongparish@gmail.com
                            </a>
                        </p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon-circle" aria-hidden="true">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <h5 class="mb-2" style="color: white;">Office Hours</h5>
                        <p class="mb-0">
                            Tuesday – Sunday<br>
                            8:00 AM – 5:00 PM (or by appointment)
                        </p>
                    </div>

                    <div class="mt-4">
                        <div class="mapouter">
                            <div class="gmap_canvas">
                                <iframe
                                    width="100%"
                                    height="260"
                                    src="https://maps.google.com/maps?q=St.%20John%20The%20Baptist%20Parish%20Tiaong%20Quezon&t=&z=15&ie=UTF8&iwloc=&output=embed"
                                    frameborder="0"
                                    scrolling="no"
                                    marginheight="0"
                                    marginwidth="0"
                                    title="Map to St. John the Baptist Parish">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
@include('partials.footer')

<!-- JS here -->
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

<script>
(function ($) {
    'use strict';

    $(document).ready(function () {
        const form = $('#contact-form');
        const success = $('#contact-success');
        const error = $('#contact-error');

        if (!form.length) {
            return;
        }

        const submitButton = form.find('button[type="submit"]');
        const originalButtonHtml = submitButton.html();

        form.on('submit', function (event) {
            event.preventDefault();

            const formElement = this;

            if (!formElement.checkValidity()) {
                formElement.reportValidity();
                return;
            }

            success.addClass('d-none');
            error.addClass('d-none');

            submitButton
                .prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method') || 'POST',
                data: form.serialize(),
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .done(function (response) {
                if (response && response.success) {
                    formElement.reset();

                    success
                        .text(response.message || 'Thank you for reaching out! Our parish staff will respond as soon as possible.')
                        .removeClass('d-none')
                        .focus();
                } else {
                    error
                        .text(response.message || 'An unexpected error occurred. Please try again later.')
                        .removeClass('d-none')
                        .focus();
                }
            })
            .fail(function (jqXHR) {
                let message = 'We could not send your message. Please try again later.';

                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    message = jqXHR.responseJSON.message;
                }

                error.text(message).removeClass('d-none').focus();
            })
            .always(function () {
                submitButton.prop('disabled', false).html(originalButtonHtml);
            });
        });
    });
})(jQuery);
</script>

</body>
</html>