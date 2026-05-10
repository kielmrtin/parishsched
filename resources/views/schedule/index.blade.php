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
    <link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
</head>

<body class="schedule-page">

@include('partials.header')

<section class="schedule_intro pt-120 pb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <div class="section_title mb-40">
                    <span>STAY UP-TO-DATE</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="schedule_filters pb-60" style="background-color: #f9f9ff;">
    <div class="container" style="padding-top: 20px; padding-bottom: 20px;">
        <div class="filter_buttons text-center">
            <button type="button" class="filter_btn active" data-filter="all" aria-pressed="true">
                <span class="filter_icon"><i class="fa fa-calendar"></i></span>
                <span>All Reservation Types</span>
            </button>
            <button type="button" class="filter_btn" data-filter="wedding" aria-pressed="false">
                <span class="filter_icon"><i class="fa fa-heart"></i></span>
                <span>Weddings</span>
            </button>
            <button type="button" class="filter_btn" data-filter="baptism" aria-pressed="false">
                <span class="filter_icon"><i class="fa fa-tint"></i></span>
                <span>Baptisms</span>
            </button>
            <button type="button" class="filter_btn" data-filter="funeral" aria-pressed="false">
                <span class="filter_icon"><i class="fa fa-leaf"></i></span>
                <span>Funerals</span>
            </button>
        </div>
    </div>
</section>

<section class="schedule_events pb-120">
    <div class="container">
        <div class="row" id="schedule-events">
            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="wedding">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge schedule_badge--wedding"><i class="fa fa-heart"></i> Wedding</span>
                        <span class="schedule_time"><i class="fa fa-clock-o"></i> 7:30 AM – 10:00 AM</span>
                    </div>
                    <div class="schedule_card_body">
                        <span class="event_date">Monday – Saturday</span>
                        <h4>Morning Wedding Reservations</h4>
                        <ul class="schedule_details">
                            <li>Primary window for nuptial Masses and church ceremonies.</li>
                            <li>Submit complete wedding requirements through the reservation form.</li>
                            <li>Coordinate rehearsal details with the parish office after confirmation.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="wedding">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge schedule_badge--wedding"><i class="fa fa-heart"></i> Wedding</span>
                        <span class="schedule_time"><i class="fa fa-clock-o"></i> 3:00 PM – 5:00 PM</span>
                    </div>
                    <div class="schedule_card_body">
                        <span class="event_date">Monday – Saturday</span>
                        <h4>Overflow Afternoon Weddings</h4>
                        <ul class="schedule_details">
                            <li>Opens when morning schedules are filled to accommodate additional couples.</li>
                            <li>Ideal for celebrations needing later preparations or travel time.</li>
                            <li>Confirm availability with the parish office during your reservation.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="baptism">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge schedule_badge--baptism"><i class="fa fa-tint"></i> Baptism</span>
                        <span class="schedule_time"><i class="fa fa-clock-o"></i> 11:00 AM – 12:00 PM</span>
                    </div>
                    <div class="schedule_card_body">
                        <span class="event_date">Saturday &amp; Sunday</span>
                        <h4>Weekend Baptism Celebrations</h4>
                        <ul class="schedule_details">
                            <li>Group baptisms take place after the late morning Mass.</li>
                            <li>Parents and sponsors should review required documents in advance.</li>
                            <li>Please arrive early for check-in and catechesis before the rite.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="funeral">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge schedule_badge--funeral"><i class="fa fa-leaf"></i> Funeral</span>
                        <span class="schedule_time"><i class="fa fa-clock-o"></i> 1:00 PM &amp; 2:00 PM</span>
                    </div>
                    <div class="schedule_card_body">
                        <span class="event_date">Sunday &amp; Monday</span>
                        <h4>Afternoon Funeral Masses</h4>
                        <ul class="schedule_details">
                            <li>Select a 1:00 PM or 2:00 PM liturgy when coordinating with the parish office.</li>
                            <li>Ideal for families expecting out-of-town arrivals on the weekend.</li>
                            <li>Finalize the reservation at least one day before burial to avoid delays.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 schedule_item" data-type="funeral">
                <div class="schedule_card h-100">
                    <div class="schedule_card_header">
                        <span class="schedule_badge schedule_badge--funeral"><i class="fa fa-leaf"></i> Funeral</span>
                        <span class="schedule_time"><i class="fa fa-clock-o"></i> 8:00 AM · 9:00 AM · 10:00 AM</span>
                    </div>
                    <div class="schedule_card_body">
                        <span class="event_date">Tuesday – Saturday</span>
                        <h4>Morning Funeral Liturgies</h4>
                        <ul class="schedule_details">
                            <li>Three morning slots are offered for weekday funeral Masses.</li>
                            <li>Choose the time that best aligns with cemetery or memorial plans.</li>
                            <li>Share any procession details with the office when submitting documents.</li>
                        </ul>
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
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/schedule.js') }}"></script>

</body>
</html>