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
            background: url('{{ asset('img/banner/inquire.png') }}') center 65% / cover no-repeat;
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
            border-color: #dc2626;
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.12);
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

        /* Dark modal alert (matches the login/register confirmation modal) */
        .ps-overlay{position:fixed;inset:0;background:rgba(6,13,26,.75);backdrop-filter:blur(6px);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px}
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
function showContactAlert(type, title, text) {
    const cfg = {
        success: { glow: 'rgba(34,197,94,.13)', ring: 'rgba(34,197,94,.5),rgba(134,239,172,.3)', inner: 'rgba(34,197,94,.1)', border: 'rgba(34,197,94,.3)', stroke: '#4ade80', b: ['#4ade8066','#4ade8059','rgba(255,255,255,.18)'], h: ['rgba(255,255,255,.25)','#4ade8066','#4ade804d'] },
        error:   { glow: 'rgba(220,38,38,.13)', ring: 'rgba(220,38,38,.5),rgba(252,165,165,.3)', inner: 'rgba(220,38,38,.1)', border: 'rgba(220,38,38,.3)', stroke: '#f87171', b: ['#f8717166','#f8717159','rgba(255,255,255,.18)'], h: ['rgba(255,255,255,.25)','#f8717166','#f871714d'] },
    };
    const nc = cfg[type] || cfg.error;

    document.getElementById('contact-ps-overlay')?.remove();

    const iconSvg = type === 'success'
        ? `<polyline class="ps-warn" style="stroke:${nc.stroke};fill:none" points="10,22 18,30 32,14"/>`
        : `<line class="ps-warn" style="stroke:${nc.stroke}" x1="13" y1="13" x2="29" y2="29"/><line class="ps-warn" style="stroke:${nc.stroke}" x1="29" y1="13" x2="13" y2="29"/>`;

    const overlay = document.createElement('div');
    overlay.className = 'ps-overlay';
    overlay.id = 'contact-ps-overlay';
    overlay.innerHTML = `
        <div class="ps-modal">
            <div class="ps-hdr" id="contact-ps-hdr">
                <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                <span class="ps-parish">St. John the Baptist Parish</span>
                <span class="ps-dot"></span>
                <span class="ps-loc">Tiaong, Quezon</span>
            </div>
            <div class="ps-body" id="contact-ps-body">
                <div class="ps-glow" style="background:radial-gradient(circle,${nc.glow} 0%,transparent 70%)"></div>
                <div class="ps-icon">
                    <div class="ps-ring" style="background:conic-gradient(${nc.ring},transparent 58%)"></div>
                    <div class="ps-inner" style="background:${nc.inner};border:1.5px solid ${nc.border}">
                        <svg viewBox="0 0 42 42">${iconSvg}</svg>
                    </div>
                </div>
                <h2 class="ps-title">${title}</h2>
                <p class="ps-sub">${text}</p>
                <div class="ps-btns">
                    <button type="button" class="ps-btn-solo" style="box-shadow:none">OK</button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);

    function spawnDots(el, cols, count, cls) {
        for (let i = 0; i < count; i++) {
            const p = document.createElement('div');
            const s = Math.random() * 6 + 4;
            p.className = cls;
            p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
            el.appendChild(p);
        }
    }
    spawnDots(document.getElementById('contact-ps-hdr'), nc.h, 10, 'ps-hdr-particle');
    spawnDots(document.getElementById('contact-ps-body'), nc.b, 20, 'ps-particle');

    overlay.querySelector('.ps-btn-solo').addEventListener('click', () => overlay.remove());
    overlay.addEventListener('click', function (e) { if (false) this.remove(); });
}

(function ($) {
    'use strict';

    $(document).ready(function () {
        const form = $('#contact-form');

        if (!form.length) {
            return;
        }

        // The shared theme bundle's ajax-form.js also binds a submit handler
        // to this same form id, which double-submits the message and clears
        // the fields before this handler's dark modal alert can show — drop
        // it so only this page's own handler (below) runs.
        form.off('submit');

        const submitButton = form.find('button[type="submit"]');
        const originalButtonHtml = submitButton.html();

        form.on('submit', function (event) {
            event.preventDefault();

            const formElement = this;

            if (!formElement.checkValidity()) {
                formElement.reportValidity();
                return;
            }

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
                    showContactAlert('success', 'Message Sent', response.message || 'Thank you for reaching out! Our parish staff will respond as soon as possible.');
                } else {
                    showContactAlert('error', 'Error', response.message || 'An unexpected error occurred. Please try again later.');
                }
            })
            .fail(function (jqXHR) {
                let message = 'We could not send your message. Please try again later.';

                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    message = jqXHR.responseJSON.message;
                }

                showContactAlert('error', 'Error', message);
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