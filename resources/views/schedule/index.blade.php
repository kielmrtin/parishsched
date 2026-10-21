<!doctype html>
<html class="no-js" lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Schedule | St. John the Baptist Parish</title>
    <meta name="description" content="Stay up to date on Mass times, novenas, and special celebrations happening at St. John the Baptist Parish in Tiaong, Quezon.">
    <meta property="og:title" content="Schedule | St. John the Baptist Parish">
    <meta property="og:description" content="Stay up to date on Mass times, novenas, and special celebrations happening at St. John the Baptist Parish in Tiaong, Quezon.">
    <meta property="og:image" content="{{ asset('img/banner/bradcam3.jpg') }}">
    <meta property="og:type" content="website">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>html { scroll-behavior: smooth; }</style>

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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ file_exists(public_path('css/style.css')) ? filemtime(public_path('css/style.css')) : 1 }}">
    <link rel="stylesheet" href="{{ asset('css/schedule.css') }}?v={{ file_exists(public_path('css/schedule.css')) ? filemtime(public_path('css/schedule.css')) : 1 }}">
    @include('partials.modal-theme-css')
</head>

<body class="schedule-page">

@include('partials.header')

@include('partials.page-hero', [
    'image'    => asset('img/banner/sched_main.webp'),
    'position' => 'center 35%',
    'title'    => 'Sacrament Schedule',
    'desc'     => 'Browse available time windows for Weddings, Baptisms, and Funeral Masses celebrated at St. John the Baptist Parish.',
    'buttons'  => [
        ['label' => 'Reserve now', 'href' => route('reservation.index'), 'icon' => 'fa-calendar-plus-o', 'primary' => true],
        ['label' => 'Contact parish', 'href' => url('/contact'), 'icon' => 'fa-envelope-o'],
    ],
    'meta'     => ['3 sacrament types', '5 time windows', '6 days a week'],
    'wave'     => '#f7f9ff',
])

<section class="schedule_filters">
    <div class="container">
        <p class="schedule_filter_label">Filter by sacrament type</p>
        <div class="filter_buttons">
            <button type="button" class="filter_btn active" data-filter="all" aria-pressed="true">
                <span class="filter_icon"><i class="fa fa-th-large"></i></span>
                <span>All Schedules</span>
            </button>
            <button type="button" class="filter_btn" data-filter="wedding" aria-pressed="false">
                <span class="filter_icon"><i class="fa fa-heart"></i></span>
                <span>Weddings</span>
            </button>
            <button type="button" class="filter_btn" data-filter="baptism" aria-pressed="false">
                <span class="filter_icon"><i class="fa fa-plus"></i></span>
                <span>Baptisms</span>
            </button>
            <button type="button" class="filter_btn" data-filter="funeral" aria-pressed="false">
                <span class="filter_icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="currentColor"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg></span>
                <span>Funerals</span>
            </button>
            <button type="button" class="filter_btn" data-filter="kumpil" aria-pressed="false">
                <span class="filter_icon"><i class="fa fa-fire"></i></span>
                <span>Kumpil</span>
            </button>
            <button type="button" class="filter_btn" data-filter="communion" aria-pressed="false">
                <span class="filter_icon"><svg viewBox="0 0 20 22" width="1em" height="1em" fill="currentColor"><path d="M3 1h14l-1.5 6a5.5 5.5 0 01-11 0zM9 13h2v5h4v3H5v-3h4z"/></svg></span>
                <span>First Communion</span>
            </button>
        </div>
    </div>
</section>

<section class="schedule_events pb-80">
    <div class="container">
        <div class="schedule_section_head">
            <h2 class="schedule_section_title">Available Time Windows</h2>
            <p class="schedule_section_sub">These are the regular sacrament schedules at our parish. Contact the parish office to confirm availability for your preferred date.</p>
        </div>
        <div class="row" id="schedule-events">

            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="wedding">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge"><i class="fa fa-heart"></i> Wedding</span>
                        <span class="schedule_day_chip">Mon – Sat</span>
                    </div>
                    <div class="schedule_card_time">7:30 – 10:00 AM</div>
                    <div class="schedule_card_rule"></div>
                    <h4 class="schedule_card_title">Morning Wedding Reservations</h4>
                    <ul class="schedule_details">
                        <li>Primary window for nuptial Masses and church ceremonies.</li>
                        <li>Submit complete wedding requirements through the reservation form.</li>
                        <li>Coordinate rehearsal details with the parish office after confirmation.</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="wedding">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge"><i class="fa fa-heart"></i> Wedding</span>
                        <span class="schedule_day_chip">Mon – Sat</span>
                    </div>
                    <div class="schedule_card_time">3:00 – 5:00 PM</div>
                    <div class="schedule_card_rule"></div>
                    <h4 class="schedule_card_title">Overflow Afternoon Weddings</h4>
                    <ul class="schedule_details">
                        <li>Opens when morning schedules are filled to accommodate additional couples.</li>
                        <li>Ideal for celebrations needing later preparations or travel time.</li>
                        <li>Confirm availability with the parish office during your reservation.</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="baptism">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge"><i class="fa fa-plus"></i> Baptism</span>
                        <span class="schedule_day_chip">Sat &amp; Sun</span>
                    </div>
                    <div class="schedule_card_time">11:00 AM – 12:00 PM</div>
                    <div class="schedule_card_rule"></div>
                    <h4 class="schedule_card_title">Weekend Baptism Celebrations</h4>
                    <ul class="schedule_details">
                        <li>Group baptisms take place after the late morning Mass.</li>
                        <li>Parents and the ninong and ninang should review the required documents in advance.</li>
                        <li>Please arrive early for check-in and catechesis before the rite.</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="funeral">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width=".82em" height=".82em" fill="currentColor" style="vertical-align:-.05em;"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg> Funeral</span>
                        <span class="schedule_day_chip">Sun &amp; Mon</span>
                    </div>
                    <div class="schedule_card_time">1:00 &amp; 2:00 PM</div>
                    <div class="schedule_card_rule"></div>
                    <h4 class="schedule_card_title">Afternoon Funeral Masses</h4>
                    <ul class="schedule_details">
                        <li>Select a 1:00 PM or 2:00 PM liturgy when coordinating with the parish office.</li>
                        <li>Ideal for families expecting out-of-town arrivals on the weekend.</li>
                        <li>Finalize the reservation at least one day before burial to avoid delays.</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="funeral">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width=".82em" height=".82em" fill="currentColor" style="vertical-align:-.05em;"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg> Funeral</span>
                        <span class="schedule_day_chip">Tue – Sat</span>
                    </div>
                    <div class="schedule_card_time">8:00 · 9:00 · 10:00 AM</div>
                    <div class="schedule_card_rule"></div>
                    <h4 class="schedule_card_title">Morning Funeral Liturgies</h4>
                    <ul class="schedule_details">
                        <li>Three morning slots are offered for weekday funeral Masses.</li>
                        <li>Choose the time that best aligns with cemetery or memorial plans.</li>
                        <li>Share any procession details with the office when submitting documents.</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="kumpil">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge"><i class="fa fa-fire"></i> Kumpil</span>
                        <span class="schedule_day_chip">Reserve 7 days ahead</span>
                    </div>
                    <div class="schedule_card_time">To be announced</div>
                    <div class="schedule_card_rule"></div>
                    <h4 class="schedule_card_title">Kumpil (Confirmation)</h4>
                    <ul class="schedule_details">
                        <li>The parish office will announce the Kumpil days and times. Reserve your preferred date at least 7 days ahead.</li>
                        <li>Bring the baptismal certificate, and the ninong or ninang's proof of kumpil or church marriage contract. Only 1 ninong or 1 ninang.</li>
                        <li>Attend the seminar first, then make your kumpisal with the priest before the kumpil.</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="communion">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge"><svg viewBox="0 0 20 22" width=".82em" height=".9em" style="vertical-align:-.05em;" fill="currentColor"><path d="M3 1h14l-1.5 6a5.5 5.5 0 01-11 0zM9 13h2v5h4v3H5v-3h4z"/></svg> First Communion</span>
                        <span class="schedule_day_chip">Per school</span>
                    </div>
                    <div class="schedule_card_time">Set with the school</div>
                    <div class="schedule_card_rule"></div>
                    <h4 class="schedule_card_title">First Communion by School</h4>
                    <ul class="schedule_details">
                        <li>Scheduled per school with the catechist. The school arranges the date with the parish office.</li>
                        <li>Each child needs a baptismal certificate.</li>
                        <li>Children make their kumpisal with the priest before the First Communion.</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="schedule_cta_section">
    <div class="container">
        <div class="schedule_cta_inner">
            <span class="schedule_cta_kicker"><i class="fa fa-check-circle"></i> Ready to proceed?</span>
            <h3>Reserve your sacrament with our parish office</h3>
            <p>Submit a reservation request online and our pastoral team will reach out to confirm your preferred schedule and walk you through the requirements.</p>
            <div class="schedule_cta_actions">
                <a href="{{ url('/reservation') }}" class="schedule_cta_btn">
                    <i class="fa fa-calendar-check-o"></i> Book a Reservation
                </a>
                <a href="{{ url('/contact') }}" class="schedule_cta_link">
                    Contact the parish office <i class="fa fa-long-arrow-right"></i>
                </a>
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
<script src="{{ asset('js/main.js') }}?v={{ file_exists(public_path('js/main.js')) ? filemtime(public_path('js/main.js')) : 1 }}"></script>
<script src="{{ asset('js/schedule.js') }}?v={{ file_exists(public_path('js/schedule.js')) ? filemtime(public_path('js/schedule.js')) : 1 }}"></script>
<script>
(function () {
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry, i) {
            if (entry.isIntersecting) {
                var el = entry.target;
                var delay = el.dataset.delay || 0;
                setTimeout(function () {
                    el.classList.add('is-visible');
                }, delay);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.06 });

    // Stagger each row of three cards evenly.
    document.querySelectorAll('.schedule_item').forEach(function (el, i) {
        el.dataset.delay = i % 3 * 120;
        observer.observe(el);
    });

    document.querySelectorAll('.schedule_section_head, .schedule_cta_inner').forEach(function (el) {
        observer.observe(el);
    });
})();
</script>

@include('partials.reveal')
@include('partials.help')
@include('partials.modal-theme')
@include('partials.doves')
</body>
</html>