@php
    $customerIsLoggedIn = (bool) session('customer_id');

    $loggedInCustomer = [
        'name' => session('customer_name', 'Member'),
        'email' => session('customer_email', ''),
        'phone' => session('customer_phone', ''),
    ];

    if (!function_exists('getMonthName')) {
        function getMonthName(int $month): string {
            return date('F', mktime(0, 0, 0, $month, 1));
        }
    }

    $userBaptismForecast = $userBaptismForecast ?? 0;
$userWeddingForecast = $userWeddingForecast ?? 0;
$userFuneralForecast = $userFuneralForecast ?? 0;

$userBaptismPeak = $userBaptismPeak ?? null;
$userWeddingPeak = $userWeddingPeak ?? null;
$userFuneralPeak = $userFuneralPeak ?? null;

    if ($errors->any()) {
        $reservationNotificationsJson = [
            [
                'icon' => 'error',
                'title' => 'Please check your reservation',
                'text' => $errors->first(),
            ]
        ];
    } else {
        $reservationNotificationsJson = session('reservation_notifications', []);
    }

    $formData = [
        'reservation-name-first' => old('reservation-name-first', ''),
        'reservation-name-middle' => old('reservation-name-middle', ''),
        'reservation-name-last' => old('reservation-name-last', ''),
        'reservation-name-suffix' => old('reservation-name-suffix', ''),
        'reservation-name' => old('name', $loggedInCustomer['name']),
        'reservation-gender' => old('reservation-gender', ''),
        'reservation-email' => old('email', $loggedInCustomer['email']),
        'reservation-phone' => old('phone', $loggedInCustomer['phone']),
        'reservation-type' => old('event_type', 'Baptism'),
        'reservation-date' => old('date', old('reservation_date', '')),
        'reservation-time' => old('reservation_time', ''),
        'reservation-notes' => old('notes', ''),
        'wedding-bride-name-first' => old('wedding-bride-name-first', ''),
        'wedding-bride-name-middle' => old('wedding-bride-name-middle', ''),
        'wedding-bride-name-last' => old('wedding-bride-name-last', ''),
        'wedding-bride-name-suffix' => old('wedding-bride-name-suffix', ''),
        'wedding-groom-name-first' => old('wedding-groom-name-first', ''),
        'wedding-groom-name-middle' => old('wedding-groom-name-middle', ''),
        'wedding-groom-name-last' => old('wedding-groom-name-last', ''),
        'wedding-groom-name-suffix' => old('wedding-groom-name-suffix', ''),
        'wedding-seminar-date' => old('wedding-seminar-date', ''),
        'wedding-sacrament-details' => old('wedding-sacrament-details', ''),
        'funeral-deceased-name-first' => old('funeral-deceased-name-first', ''),
        'funeral-deceased-name-middle' => old('funeral-deceased-name-middle', ''),
        'funeral-deceased-name-last' => old('funeral-deceased-name-last', ''),
        'funeral-deceased-name-suffix' => old('funeral-deceased-name-suffix', ''),
        'funeral-marital-status' => old('funeral-marital-status', ''),
    ];

    $selectedWeddingRequirements = old('wedding-requirements', []);
    if (!is_array($selectedWeddingRequirements)) {
        $selectedWeddingRequirements = [];
    }

    $funeralMaritalStatusOptions = [
        'married_not_baptized' => 'Married (not baptized in the church)',
        'single' => 'Single / Unmarried',
    ];

    $attachmentRequirementSets = [
        'Baptism' => [
            'id' => 'baptism-attachments',
            'title' => 'Required Baptism documents',
            'description' => 'Upload clear scans or photos of the following requirements. Accepted formats: PDF, JPG, PNG (max 5MB each).',
            'documents' => [
                'baptism_file' => ['label' => 'Birth certificate / Baptism document (PDF, JPG, PNG)'],
            ],
            'notes' => [
                'Choose one godfather and one godmother as major sponsors (proxies are not allowed).',
                'Major sponsors must be practicing Catholics in good standing.',
                'Suggested church donation: P800.',
                'Please bring original documents to the parish office on the day of baptism.',
            ],
        ],
        'Wedding' => [
            'id' => 'wedding-attachments',
            'title' => 'Required Wedding documents',
            'description' => 'Upload scanned copies of the following pre-marriage requirements. Accepted formats: PDF, JPG, PNG (max 5MB each).',
            'documents' => [
                'wedding_file1' => ['label' => 'Wedding Requirement 1 / Baptismal certificate'],
                'wedding_file2' => ['label' => 'Wedding Requirement 2 / Marriage license or seminar certificate'],
            ],
            'notes' => ['Submit photocopies with the original documents to the parish office when requested.'],
        ],
        'Funeral' => [
            'id' => 'funeral-attachments',
            'title' => 'Required Funeral documents',
            'description' => 'Upload the document that matches the marital status selected above. Accepted formats: PDF, JPG, PNG (max 5MB).',
            'documents' => [
                'funeral_file' => ['label' => 'Funeral document / Marriage contract or baptismal certificate'],
            ],
            'notes' => [],
        ],
    ];

    $weddingRequirementChecklist = [
        'baptismal-certificate' => 'Baptismal Certificate (for marriage purposes)',
        'confirmation-certificate' => 'Confirmation Certificate (for marriage purposes)',
        'marriage-permit' => 'Marriage Permit',
        'marriage-banns' => 'Marriage Banns',
        'marriage-license' => 'Marriage License',
        'seminar-certificate' => 'Certificate of Seminar',
        'sponsors-list' => 'Listahan ng Ninong at Ninang (apat na pares na minimum)',
    ];

    $reservationGender = $formData['reservation-gender'];
    $showWeddingCoupleFields = in_array($reservationGender, ['male', 'female'], true);
    $weddingCoupleOrder = $reservationGender === 'male' ? ['groom', 'bride'] : ['bride', 'groom'];

    $shouldOpenReservationModal = old() ? true : false;
    $shouldDisplayReservationForm = true;
    $prefilledReservationDate = $formData['reservation-date'];
    $approvedReservationsJson = collect($approvedReservations ?? [])->values()->toArray();
    $reservationUsageJson = [];

foreach ($approvedReservationsJson as $dayGroup) {
    $dateKey = $dayGroup['date'] ?? null;

    if (!$dateKey || empty($dayGroup['reservations']) || !is_array($dayGroup['reservations'])) {
        continue;
    }

    foreach ($dayGroup['reservations'] as $reservationItem) {
        $eventType = strtolower($reservationItem['eventType'] ?? '');
        $timeSlot = trim(str_replace('–', '-', $reservationItem['preferredTime'] ?? ''));
        $status = strtolower($reservationItem['status'] ?? '');

        if ($eventType === '' || $timeSlot === '') {
            continue;
        }

        if ($status !== 'approved') {
    continue;
}
        if (!isset($reservationUsageJson[$dateKey])) {
            $reservationUsageJson[$dateKey] = [];
        }

        if (!isset($reservationUsageJson[$dateKey][$eventType])) {
            $reservationUsageJson[$dateKey][$eventType] = [];
        }

        $reservationUsageJson[$dateKey][$eventType][] = $timeSlot;
    }
}
 

@endphp
<!doctype html>
<html class="no-js" lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Reservations | St. John the Baptist Parish</title>
    <meta name="description" content="Reserve a sacrament or church service at St. John the Baptist Parish in Tiaong, Quezon.">
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
    <link rel="stylesheet" href="{{ asset('css/reservation-overrides.css') }}">

    <style>
        .reservation_insights_area { padding-top: 10px; padding-bottom: 35px; }
        .reservation-insights-modern { max-width: 1220px; margin: 0 auto; background: linear-gradient(145deg, #ffffff 0%, #f8fbff 100%); border: 1px solid rgba(99, 102, 241, 0.12); border-radius: 28px; padding: 34px; box-shadow: 0 22px 60px rgba(15, 23, 42, 0.08); position: relative; overflow: visible; }
        .reservation-insights-modern::before { content: ""; position: absolute; top: -60px; right: -60px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(79, 70, 229, 0.12) 0%, rgba(79, 70, 229, 0) 70%); border-radius: 50%; }
.reservation-insights-top {
    display: flex;
    justify-content: flex-start; /* 👈 THIS FIXES OVERLAP */
    align-items: flex-start;
    gap: 30px;
    margin-bottom: 28px;
    position: relative;
    z-index: 1;
}
        .reservation-insights-heading { max-width: 620px; }
        .reservation-insights-chip { display: inline-block; padding: 8px 16px; border-radius: 999px; background: rgba(79, 70, 229, 0.10); color: #4338ca; font-size: 12px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 14px; }
        .reservation-insights-heading h3 { margin: 0 0 10px; font-size: 42px; line-height: 1.15; color: #111827; font-weight: 700; }
        .reservation-insights-heading p { margin: 0; font-size: 17px; line-height: 1.8; color: #6b7280; }

       .reservation-insights-mini-stats {
    display: flex;
    gap: 16px;
    flex-wrap: nowrap;
}

.reservation-insights-bottom {
    margin-top: 30px; /* 👈 adjust this value if you want more space */
}

.insight-tip-box {
    display: flex;
    align-items: center;
    gap: 14px;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.08), rgba(14, 165, 233, 0.08));
    border: 1px solid rgba(99, 102, 241, 0.10);
    border-radius: 18px;
    padding: 18px 20px;
}

.tip-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 92px;
    height: 38px;
    border-radius: 999px;
    background: #4338ca;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.insight-tip-box p {
    margin: 0;
    font-size: 15px;
    line-height: 1.7;
    color: #374151;
    font-weight: 500;
}

.mini-stat-card {
    flex: 1;
    min-width: 150px;
    background: #ffffff;
    border: 1px solid rgba(148, 163, 184, 0.18);
    border-radius: 20px;
    padding: 18px 20px;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
}

.mini-stat-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #4f46e5;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 8px;
}

.mini-stat-card strong {
    display: block;
    font-size: 32px;
    line-height: 1;
    color: #1f2937;
    margin-bottom: 6px;
}

.mini-stat-card small {
    color: #6b7280;
    font-size: 13px;
}

.reservation-insights-grid {
    display: flex;
    gap: 22px;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    padding: 4px 2px 18px;
    position: relative;
    z-index: 1;
}

.reservation-insights-grid::-webkit-scrollbar {
    height: 8px;
}

.reservation-insights-grid::-webkit-scrollbar-thumb {
    background: rgba(99, 102, 241, 0.22);
    border-radius: 999px;
}

.insight-modern-card {
    display: flex;
    gap: 18px;
    align-items: flex-start;
    background: #ffffff;
    border-radius: 22px;
    padding: 24px;
    border: 1px solid rgba(148, 163, 184, 0.18);
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
    min-height: 230px;
    min-width: calc(50% - 11px);
    scroll-snap-align: start;
}

.insight-baptism { border-top: 4px solid #22c55e; }
.insight-wedding { border-top: 4px solid #ef4444; }
.insight-funeral { border-top: 4px solid #4b5563; }

.insight-card-icon {
    width: 56px;
    height: 56px;
    min-width: 56px;
    border-radius: 18px;
    color: #ffffff;
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

.insight-baptism .insight-card-icon {
    background: #22c55e;
    box-shadow: 0 8px 18px rgba(34, 197, 94, 0.14);
}

.insight-wedding .insight-card-icon {
    background: #ef4444;
    box-shadow: 0 8px 18px rgba(239, 68, 68, 0.14);
}

.insight-funeral .insight-card-icon {
    background: #4b5563;
    box-shadow: 0 8px 18px rgba(75, 85, 99, 0.14);
}

.insight-card-content {
    flex: 1;
}

.insight-card-content h4 {
    margin: 2px 0 16px;
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
}

.insight-card-content ul {
    margin: 0 0 14px;
    padding: 0;
    list-style: none;
}

.insight-card-content ul li {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(148, 163, 184, 0.14);
}

.insight-card-content ul li span {
    color: #6b7280;
    font-size: 15px;
}

.insight-card-content ul li strong {
    color: #111827;
    font-size: 16px;
    font-weight: 700;
    text-align: right;
}

.insight-note {
    margin: 14px 0 0;
    color: #4b5563;
    font-size: 16px;
    line-height: 1.8;
}

@media (max-width: 991px) {
    .reservation-insights-top {
        flex-direction: column;
    }

    .reservation-insights-mini-stats {
        width: 100%;
        min-width: 0;
        overflow-x: auto;
        padding-bottom: 8px;
    }

    .mini-stat-card {
       min-width: 0;
padding: 18px 16px;
    }

    .reservation-insights-heading h3 {
        font-size: 34px;
    }

    .insight-modern-card {
        min-width: 82%;
    }
}

@media (max-width: 767px) {
    .reservation-insights-modern {
        padding: 22px;
        border-radius: 22px;
    }

    .reservation-insights-heading h3 {
        font-size: 28px;
    }

    .reservation-insights-heading p {
        font-size: 15px;
    }

    .insight-modern-card {
        min-width: 92%;
        padding: 20px;
    }

    .insight-card-content h4 {
        font-size: 24px;
    }

    .insight-card-content ul li {
        flex-direction: column;
        align-items: flex-start;
    }

    .insight-card-content ul li strong {
        text-align: left;
    }

.tip-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 92px;
    height: 38px;
    border-radius: 999px;
    background: #4338ca;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.insight-tip-box p {
    margin: 0;
    font-size: 15px;
    line-height: 1.7;
    color: #374151;
    font-weight: 500;
}
}

        .reservation-insights-grid {
    display: flex;
    gap: 22px;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    padding: 4px 2px 18px;
    position: relative;
    z-index: 1;
}

.reservation-insights-grid::-webkit-scrollbar {
    height: 8px;
}

.reservation-insights-grid::-webkit-scrollbar-thumb {
    background: rgba(99, 102, 241, 0.25);
    border-radius: 999px;
}

.insight-modern-card {
    min-width: calc(50% - 11px);
    scroll-snap-align: start;
}

.insight-card-icon {
    width: 56px;
    height: 56px;
    min-width: 56px;
    border-radius: 18px;
    color: #ffffff;
    font-size: 26px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

.insight-baptism .insight-card-icon {
    background: #22c55e;
    box-shadow: 0 6px 14px rgba(34, 197, 94, 0.15);
}

.insight-wedding .insight-card-icon {
    background: #ef4444;
    box-shadow: 0 6px 14px rgba(239, 68, 68, 0.15);
}

.insight-funeral .insight-card-icon {
    background: #4b5563;
    box-shadow: 0 6px 14px rgba(75, 85, 99, 0.15);
}

@media (max-width: 991px) {
    .insight-modern-card {
        min-width: 82%;
    }
}

@media (max-width: 767px) {
    .insight-modern-card {
        min-width: 92%;
    }
}
    </style>
</head>

<body class="reservation-page">
    @include('partials.header')

    <div class="bradcam_area breadcam_bg">
        <h3>Make a Reservation</h3>
    </div>

    <section class="reservation_intro pt-120 pb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <div class="section_title mb-40">
                        <span>Plan your celebration or service</span>
                        <h3>Reserve a sacrament, liturgy, or pastoral service</h3>
                        <p>Please complete the form below with as much detail as possible. A member of our pastoral staff will follow up within two business days to confirm availability and discuss next steps.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($customerIsLoggedIn)
        <section class="reservation_insights_area pb-60">
            <div class="container">
                <div class="reservation-insights-modern">
                    <div class="reservation-insights-top">
                        <div class="reservation-insights-heading">
                            <span class="reservation-insights-chip">Predictive Analytics</span>
                            <h3>Plan smarter before you reserve</h3>
                            <p>Forecast-based booking insights for baptism and wedding reservations, designed to help you choose a better schedule.</p>
                        </div>
                        <div class="reservation-insights-mini-stats">
    <div class="mini-stat-card">
        <span class="mini-stat-label">Baptism Forecast</span>
        <strong>{{ (int) $userBaptismForecast }}</strong>
        <small>next-month bookings</small>
    </div>

    <div class="mini-stat-card">
        <span class="mini-stat-label">Wedding Forecast</span>
        <strong>{{ (int) $userWeddingForecast }}</strong>
        <small>next-month bookings</small>
    </div>

    <div class="mini-stat-card">
        <span class="mini-stat-label">Funeral Forecast</span>
        <strong>{{ (int) $userFuneralForecast }}</strong>
        <small>next-month bookings</small>
    </div>
</div>
</div>

<div class="reservation-insights-grid">
    <div class="insight-modern-card insight-baptism">
        <div class="insight-card-icon">✝</div>
        <div class="insight-card-content">
            <h4>Baptism Insights</h4>
            <ul>
                <li><span>Predicted next-month demand</span><strong>{{ (int) $userBaptismForecast }} booking(s)</strong></li>
                <li><span>Peak month</span><strong>{{ $userBaptismPeak ? getMonthName((int) $userBaptismPeak['month']) : 'No data yet' }}</strong></li>
            </ul>
            <p class="insight-note">
                @if($userBaptismPeak)
                    Baptism reservations are usually highest in {{ getMonthName((int) $userBaptismPeak['month']) }}.
                @else
                    There is not enough historical baptism data yet.
                @endif
            </p>
        </div>
    </div>

    <div class="insight-modern-card insight-wedding">
        <div class="insight-card-icon">♥</div>
        <div class="insight-card-content">
            <h4>Wedding Insights</h4>
            <ul>
                <li><span>Predicted next-month demand</span><strong>{{ (int) $userWeddingForecast }} booking(s)</strong></li>
                <li><span>Peak month</span><strong>{{ $userWeddingPeak ? getMonthName((int) $userWeddingPeak['month']) : 'No data yet' }}</strong></li>
            </ul>
            <p class="insight-note">
                @if($userWeddingPeak)
                    Wedding reservations are usually highest in {{ getMonthName((int) $userWeddingPeak['month']) }}.
                @else
                    There is not enough historical wedding data yet.
                @endif
            </p>
        </div>
    </div>

    <div class="insight-modern-card insight-funeral">
        <div class="insight-card-icon">⚰</div>
        <div class="insight-card-content">
            <h4>Funeral Insights</h4>
            <ul>
                <li><span>Predicted next-month demand</span><strong>{{ (int) $userFuneralForecast }} booking(s)</strong></li>
                <li><span>Peak month</span><strong>{{ $userFuneralPeak ? getMonthName((int) $userFuneralPeak['month']) : 'No data yet' }}</strong></li>
            </ul>
            <p class="insight-note">
                @if($userFuneralPeak)
                    Funeral reservations are usually highest in {{ getMonthName((int) $userFuneralPeak['month']) }}.
                @else
                    There is not enough historical funeral data yet.
                @endif
            </p>
        </div>
    </div>
</div>
                    </div>
                    <div class="reservation-insights-bottom">
                        <div class="insight-tip-box">
                            <span class="tip-badge">Smart Tip</span>
                            <p>Choose less busy months if you want a better chance of getting your preferred schedule and faster confirmation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="reservation_form_area pb-120">
        <div class="container-fluid reservation_form_container">
            @if(!$customerIsLoggedIn)
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-9 col-xl-7">
                        <div class="alert alert-info text-center" role="alert">
                            Please <a href="{{ route('login') }}" class="alert-link">log in</a> or
                            <a href="{{ route('register') }}" class="alert-link">create an account</a> to submit a reservation request online.
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="reservation_calendar mb-5">
                        <h4 class="mb-4">Availability Preview</h4>
                        <p class="mb-4">Dates with a badge already have at least one reservation on the parish calendar. You can still choose them if an additional slot fits your celebration—just open the day to review the details before submitting your request.</p>
                        <div class="calendar_legend mb-3"><span><span class="legend booked"></span> Reservation on file</span></div>
                        <div class="availability_calendar" id="availability-calendar"></div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9 col-xl-7">
                    <div class="reservation_form_cta text-center p-5">
                        <h4 class="mb-3">Ready to request a sacrament?</h4>
                        <p class="mb-4">We now collect reservation details and required documents directly inside the reservation window. Choose a date on the calendar or use the button below to begin your request.</p>
                        @if($customerIsLoggedIn)
                            <button type="button" class="boxed-btn3" data-toggle="modal" data-target="#reservationDayModal">Start a Reservation</button>
                        @else
                            <a class="boxed-btn3" href="{{ route('login') }}">Log in to start</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="reservation_faq pb-120" style="padding-bottom: 20px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="section_title text-center mb-40">
                        <span>Be prepared</span>
                        <h3>What happens after I submit a request?</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><div class="single_about_info text-center"><h4>We review your request</h4><p>Our staff checks the parish calendar and confirms priest availability.</p></div></div>
                        <div class="col-md-4"><div class="single_about_info text-center"><h4>We connect with you</h4><p>Expect a call or email within two business days to discuss preparation steps.</p></div></div>
                        <div class="col-md-4"><div class="single_about_info text-center"><h4>We finalize the details</h4><p>Together we complete the required forms, schedule rehearsals, and plan the liturgy.</p></div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <div class="modal fade reservation_day_modal" id="reservationDayModal" tabindex="-1" role="dialog" aria-labelledby="reservationDayModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content reservation-modal">
                <div class="modal-header reservation-modal__header">
                    <div class="reservation-modal__title-group">
                        <span class="reservation-modal__eyebrow">Sacrament reservations</span>
                        <h5 class="modal-title" id="reservationDayModalLabel" style="color:white;">Start a Reservation</h5>
                        <p class="reservation-modal__subtitle" style="color:white;">Choose an available celebration date, share the details, and upload the required documents—all in one elegant flow.</p>
                    </div>
                    <button type="button" class="close reservation-modal__close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body reservation-modal__body">
                    <div class="reservation-modal__body-inner">
                        <div class="reservation-modal__progress">
                            <div class="reservation-modal__progress-item"><span class="reservation-modal__progress-number">1</span><span class="reservation-modal__progress-text">Choose an available date</span></div>
                            <div class="reservation-modal__progress-item"><span class="reservation-modal__progress-number">2</span><span class="reservation-modal__progress-text">Share your celebration details</span></div>
                            <div class="reservation-modal__progress-item"><span class="reservation-modal__progress-number">3</span><span class="reservation-modal__progress-text">Attach the required documents</span></div>
                        </div>

                        <div data-reservation-messages>
                            <noscript>
                                @if(session('success'))
                                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                                @endif
                                @if($errors->any())
                                    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                                @endif
                            </noscript>
                        </div>

                        <div class="reservation_modal_content reservation-modal__form">
                            @if(!$customerIsLoggedIn)
                                <div class="reservation_modal_sidebar mb-4">
                                    <h6 class="text-uppercase text-muted">Availability preview</h6>
                                    <div data-reservation-availability>
                                        <p class="mb-2">Create a free account or log in to request a sacrament online.</p>
                                        <p class="small text-muted mb-0">Once signed in you can choose an available date and submit your reservation details.</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a class="boxed-btn3 mb-3" href="{{ route('login') }}">Log in to reserve</a>
                                    <p class="mb-0">Need an account? <a href="{{ route('register') }}">Create one in minutes</a>.</p>
                                </div>
                            @else
                                <div class="reservation_modal_sidebar mb-4">
                                    <h6 class="text-uppercase text-muted">Availability preview</h6>
                                    <div data-reservation-availability>
                                        <p class="mb-2">Select a date on the calendar to see existing approved reservations and prefill the request form.</p>
                                        <p class="small text-muted mb-0">Dates without a <span class="badge badge-danger">Booked</span> tag remain open for requests.</p>
                                    </div>
                                </div>

                                <button type="button" class="boxed-btn3 w-100 mb-4 {{ $shouldDisplayReservationForm ? 'd-none' : '' }}" data-reservation-start>
                                    Make a Reservation
                                </button>

                                <form id="reservation-form"
                                  class="reservation_form {{ $shouldDisplayReservationForm ? '' : 'd-none' }}"
                                    method="POST" 
                                        action="{{ route('reservation.store') }}"
                                          enctype="multipart/form-data"
                                              data-server-handled="true"
                                                data-reservation-form>
                                    @csrf

                                    <input type="hidden" name="name" id="laravel-name-field" value="{{ old('name', session('customer_name')) }}">
                                    <input type="hidden" name="email" id="laravel-email-field" value="{{ old('email', session('customer_email')) }}">
                                    <input type="hidden" name="phone" id="laravel-phone-field" value="{{ old('phone', session('customer_phone')) }}">
                                    <input type="hidden" name="event_type" id="laravel-event-type-field" value="{{ old('event_type', $formData['reservation-type']) }}">
                                    <input type="hidden" name="reservation_date" id="laravel-date-field" value="{{ old('reservation_date', $formData['reservation-date']) }}">
                                    <input type="hidden" name="reservation_time" id="laravel-time-field" value="{{ old('reservation_time', $formData['reservation-time']) }}">
                                    <input type="hidden" name="notes" id="laravel-notes-field" value="{{ old('notes', $formData['reservation-notes']) }}">
                                    <input type="hidden" name="customer_id" value="{{ session('customer_id') }}">

                                    <div class="form-group">
                                        <label class="d-block" for="reservation-name-first">Name of person reserving *</label>
                                        <div class="form-row">
                                            <div class="col-sm-6 mb-3"><input type="text" id="reservation-name-first" name="reservation-name-first" class="form-control" placeholder="First name" required autocomplete="given-name" value="{{ $formData['reservation-name-first'] }}"></div>
                                            <div class="col-sm-6 mb-3"><input type="text" id="reservation-name-middle" name="reservation-name-middle" class="form-control" placeholder="Middle name (optional)" autocomplete="additional-name" value="{{ $formData['reservation-name-middle'] }}"></div>
                                            <div class="col-sm-6 mb-3"><input type="text" id="reservation-name-last" name="reservation-name-last" class="form-control" placeholder="Last name" required autocomplete="family-name" value="{{ $formData['reservation-name-last'] }}"></div>
                                            <div class="col-sm-6 mb-3"><input type="text" id="reservation-name-suffix" name="reservation-name-suffix" class="form-control" placeholder="Suffix (optional)" autocomplete="honorific-suffix" value="{{ $formData['reservation-name-suffix'] }}"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="d-block">Gender</label>
                                        <div class="custom-control custom-radio custom-control-inline mb-2">
                                            <input type="radio" id="reservation-gender-male" name="reservation-gender" class="custom-control-input" value="male" @checked($formData['reservation-gender'] === 'male')>
                                            <label class="custom-control-label" for="reservation-gender-male">Male</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline mb-2">
                                            <input type="radio" id="reservation-gender-female" name="reservation-gender" class="custom-control-input" value="female" @checked($formData['reservation-gender'] === 'female')>
                                            <label class="custom-control-label" for="reservation-gender-female">Female</label>
                                        </div>
                                        <small class="form-text text-muted">Select the gender.</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="reservation-email">Email *</label>
                                        <input type="email" id="reservation-email" name="reservation-email" class="form-control" placeholder="name@example.com" required value="{{ $formData['reservation-email'] }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="reservation-phone">Contact number *</label>
                                        <input type="tel" id="reservation-phone" name="reservation-phone" class="form-control" placeholder="(042) 545-9244" required value="{{ $formData['reservation-phone'] }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="d-block">Event type *</label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="reservation-type-baptism" name="reservation-type" class="custom-control-input" value="Baptism" required @checked($formData['reservation-type'] === 'Baptism')>
                                            <label class="custom-control-label" for="reservation-type-baptism">Baptism</label>
                                        </div>
                                        <div class="custom-control custom-radio mt-2">
                                            <input type="radio" id="reservation-type-wedding" name="reservation-type" class="custom-control-input" value="Wedding" @checked($formData['reservation-type'] === 'Wedding')>
                                            <label class="custom-control-label" for="reservation-type-wedding">Wedding</label>
                                        </div>
                                        <div class="custom-control custom-radio mt-2">
                                            <input type="radio" id="reservation-type-funeral" name="reservation-type" class="custom-control-input" value="Funeral" @checked($formData['reservation-type'] === 'Funeral')>
                                            <label class="custom-control-label" for="reservation-type-funeral">Funeral <small class="d-block text-muted">Coordinate with the parish office a day before burial</small></label>
                                        </div>
                                    </div>

                                    <input type="hidden" id="reservation-date" name="reservation-date" value="{{ $formData['reservation-date'] }}">

                                    <div class="form-group">
                                        <label for="reservation-time">Preferred time *</label>
                                        <select id="reservation-time" name="reservation-time" class="form-control" required style="height: 50px;" data-initial-value="{{ $formData['reservation-time'] }}">
                                            <option value="">Select a time</option>
                                        </select>
                                        <small class="form-text text-muted" data-reservation-time-help>Choose an event type and calendar date to see available times.</small>
                                    </div>

                                    <div id="wedding-details" class="reservation_attachment_box mb-4">
                                        <h6 class="mb-3">Wedding information</h6>
                                        <div id="wedding-gender-notice" class="alert alert-info mb-4 {{ $showWeddingCoupleFields ? 'd-none' : '' }}" role="status">
                                            Please select a gender to continue.
                                        </div>
                                        <div id="wedding-couple-fields" class="wedding-couple-fields mb-4 {{ $showWeddingCoupleFields ? '' : 'd-none' }}">
                                            @foreach($weddingCoupleOrder as $weddingPerson)
                                                @if($weddingPerson === 'bride')
                                                    <div class="form-group" data-wedding-person="bride">
                                                        <label class="d-block" for="wedding-bride-name-first">Bride's name *</label>
                                                        <div class="form-row">
                                                            <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="wedding-bride-name-first" name="wedding-bride-name-first" placeholder="First name" autocomplete="section-wedding given-name" value="{{ $formData['wedding-bride-name-first'] }}" data-wedding-required="true"></div>
                                                            <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="wedding-bride-name-middle" name="wedding-bride-name-middle" placeholder="Middle name (optional)" autocomplete="section-wedding additional-name" value="{{ $formData['wedding-bride-name-middle'] }}"></div>
                                                            <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="wedding-bride-name-last" name="wedding-bride-name-last" placeholder="Last name" autocomplete="section-wedding family-name" value="{{ $formData['wedding-bride-name-last'] }}" data-wedding-required="true"></div>
                                                            <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="wedding-bride-name-suffix" name="wedding-bride-name-suffix" placeholder="Suffix (optional)" autocomplete="section-wedding honorific-suffix" value="{{ $formData['wedding-bride-name-suffix'] }}"></div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="form-group" data-wedding-person="groom">
                                                        <label class="d-block" for="wedding-groom-name-first">Groom's name *</label>
                                                        <div class="form-row">
                                                            <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="wedding-groom-name-first" name="wedding-groom-name-first" placeholder="First name" autocomplete="section-wedding-groom given-name" value="{{ $formData['wedding-groom-name-first'] }}" data-wedding-required="true"></div>
                                                            <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="wedding-groom-name-middle" name="wedding-groom-name-middle" placeholder="Middle name (optional)" autocomplete="section-wedding-groom additional-name" value="{{ $formData['wedding-groom-name-middle'] }}"></div>
                                                            <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="wedding-groom-name-last" name="wedding-groom-name-last" placeholder="Last name" autocomplete="section-wedding-groom family-name" value="{{ $formData['wedding-groom-name-last'] }}" data-wedding-required="true"></div>
                                                            <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="wedding-groom-name-suffix" name="wedding-groom-name-suffix" placeholder="Suffix (optional)" autocomplete="section-wedding-groom honorific-suffix" value="{{ $formData['wedding-groom-name-suffix'] }}"></div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        <div class="form-group">
                                            <label for="wedding-seminar-date">Seminar date *</label>
                                            <input type="text" class="form-control" id="wedding-seminar-date" name="wedding-seminar-date" placeholder="Select seminar date" value="{{ $formData['wedding-seminar-date'] }}" data-wedding-required="true" style="font-size: 15px;">
                                            <small class="form-text text-muted">Choose a seminar date that is between one and five days before your wedding day.</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="wedding-sacrament-details">Kumpisa / Kumpil / Binyag details</label>
                                            <textarea class="form-control" id="wedding-sacrament-details" name="wedding-sacrament-details" rows="3" placeholder="Parishes or dates for confession, confirmation, and baptism">{{ $formData['wedding-sacrament-details'] }}</textarea>
                                        </div>

                                        <div class="form-group mb-0">
                                            <h6 class="mb-2">Mga kailangan bago ikasal *</h6>
                                            <p class="small text-muted">Please confirm that you have prepared the following requirements.</p>
                                            @foreach($weddingRequirementChecklist as $requirementKey => $requirementLabel)
                                                @php $inputId = 'wedding-requirement-' . preg_replace('/[^A-Za-z0-9_-]/', '-', $requirementKey); @endphp
                                                <div class="custom-control custom-checkbox mb-1">
                                                    <input type="checkbox" class="custom-control-input" id="{{ $inputId }}" name="wedding-requirements[]" value="{{ $requirementKey }}" @checked(in_array($requirementKey, $selectedWeddingRequirements, true)) data-wedding-required="true" data-wedding-checkbox="true">
                                                    <label class="custom-control-label" for="{{ $inputId }}">{{ $requirementLabel }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div id="funeral-details" class="reservation_attachment_box mb-4">
                                        <h6 class="mb-3">Funeral information</h6>
                                        <div class="alert alert-warning small" role="alert">Arrange or reserve the funeral schedule at the parish office at least one day before the burial to avoid delays or declined requests.</div>
                                        <div class="form-group">
                                            <label class="d-block" for="funeral-deceased-name-first">Name of the deceased *</label>
                                            <div class="form-row">
                                                <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="funeral-deceased-name-first" name="funeral-deceased-name-first" placeholder="First name" autocomplete="section-funeral given-name" value="{{ $formData['funeral-deceased-name-first'] }}" data-funeral-required="true"></div>
                                                <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="funeral-deceased-name-middle" name="funeral-deceased-name-middle" placeholder="Middle name (optional)" autocomplete="section-funeral additional-name" value="{{ $formData['funeral-deceased-name-middle'] }}"></div>
                                                <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="funeral-deceased-name-last" name="funeral-deceased-name-last" placeholder="Last name" autocomplete="section-funeral family-name" value="{{ $formData['funeral-deceased-name-last'] }}" data-funeral-required="true"></div>
                                                <div class="col-sm-6 col-lg-3 mb-3"><input type="text" class="form-control" id="funeral-deceased-name-suffix" name="funeral-deceased-name-suffix" placeholder="Suffix (optional)" autocomplete="section-funeral honorific-suffix" value="{{ $formData['funeral-deceased-name-suffix'] }}"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="funeral-marital-status">Marital status of the deceased *</label>
                                            <select class="form-control" id="funeral-marital-status" style="height: 50px;" name="funeral-marital-status" data-funeral-required="true" data-funeral-marital-select="true">
                                                <option value="">Select status</option>
                                                @foreach($funeralMaritalStatusOptions as $statusValue => $statusLabel)
                                                    <option value="{{ $statusValue }}" @selected($formData['funeral-marital-status'] === $statusValue)>{{ $statusLabel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    @foreach($attachmentRequirementSets as $eventType => $attachmentSet)
                                        @php
                                            $documents = $attachmentSet['documents'] ?? [];
                                            $sectionId = $attachmentSet['id'] ?? 'attachments-' . strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $eventType));
                                            $shouldShowSection = $shouldDisplayReservationForm && $formData['reservation-type'] === $eventType;
                                        @endphp
                                        @if(!empty($documents))
                                            <div class="reservation_attachment_box mb-4" id="{{ $sectionId }}" data-attachment-section="{{ $eventType }}" style="{{ $shouldShowSection ? '' : 'display: none;' }}">
                                                <h6 class="mb-3 pt-3">{{ $attachmentSet['title'] ?? 'Required documents' }}</h6>
                                                @if(!empty($attachmentSet['description']))
                                                    <p class="small text-muted">{{ $attachmentSet['description'] }}</p>
                                                @endif
                                                @foreach($documents as $fieldName => $documentConfig)
                                                    @php
                                                        $inputId = (string) $fieldName;
                                                        $label = $documentConfig['label'] ?? $inputId;
                                                        $accept = $documentConfig['accept'] ?? '.pdf,.jpg,.jpeg,.png';
                                                        $conditional = $documentConfig['conditional'] ?? null;
                                                        $conditionalField = is_array($conditional) ? ($conditional['field'] ?? '') : '';
                                                        $conditionalValue = is_array($conditional) ? ($conditional['value'] ?? '') : '';
                                                    @endphp
                                                    <div class="form-group"
                                                         data-attachment-field="{{ $fieldName }}"
                                                         @if($conditionalField !== '')
                                                             data-attachment-conditional-field="{{ $conditionalField }}"
                                                             data-attachment-conditional-value="{{ $conditionalValue }}"
                                                         @endif>
                                                        <label for="{{ $inputId }}">{{ $label }} *</label>
                                                        <input type="file" class="form-control-file" id="{{ $inputId }}" name="{{ $fieldName }}" accept="{{ $accept }}">
                                                    </div>
                                                @endforeach
                                                @if(!empty($attachmentSet['notes']))
                                                    <ul class="small pl-3 text-left mb-0">
                                                        @foreach($attachmentSet['notes'] as $note)
                                                            <li>{{ $note }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach

                                    <div class="form-group" data-reservation-form-toggle-target {{ $shouldDisplayReservationForm ? '' : 'hidden' }}>
                                        <label for="reservation-notes">Additional notes or requests</label>
                                        <textarea id="reservation-notes" name="reservation-notes" class="form-control" rows="4" placeholder="Tell us about your celebration">{{ $formData['reservation-notes'] }}</textarea>
                                    </div>

                                    <div class="alert alert-warning small mt-3 d-none" role="alert" data-reservation-form-toggle-target {{ $shouldDisplayReservationForm ? '' : 'hidden' }} data-reservation-time-warning></div>

                                    <button type="submit"
                         class="boxed-btn3 w-100"
                                  data-reservation-form-toggle-target
                         {{ $shouldDisplayReservationForm ? '' : 'hidden' }}
                                data-reservation-submit>
                                        <span>Submit Reservation Request</span>
                                        <span class="spinner-border spinner-border-sm ml-2 align-middle d-none" role="status" aria-hidden="true" data-loading-spinner></span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.approvedReservations = @json($approvedReservationsJson);
        window.shouldOpenReservationModal = @json($shouldOpenReservationModal);
        window.prefilledReservationDate = @json($prefilledReservationDate);
        window.shouldDisplayReservationForm = @json($shouldDisplayReservationForm);
        window.reservationUsage = @json($reservationUsageJson);
        window.reservationNotifications = @json($reservationNotificationsJson);
    </script>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('reservation_notifications') || session('auth_notification'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let notifications = [];

                @if(session('reservation_notifications'))
                    notifications = notifications.concat(@json(session('reservation_notifications')));
                @endif

                @if(session('auth_notification'))
                    notifications.push(@json(session('auth_notification')));
                @endif

                notifications.forEach(function (notification) {
                    Swal.fire({
                        icon: notification.icon || 'info',
                        title: notification.title || '',
                        text: notification.text || '',
                        confirmButtonColor: '#009DFF'
                    });
                });
            });
        </script>
    @endif

    <script src="{{ asset('js/reservations.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('reservation-form');
            if (!form) return;

            form.addEventListener('submit', function () {
                const first = document.getElementById('reservation-name-first')?.value.trim() || '';
                const middle = document.getElementById('reservation-name-middle')?.value.trim() || '';
                const last = document.getElementById('reservation-name-last')?.value.trim() || '';
                const suffix = document.getElementById('reservation-name-suffix')?.value.trim() || '';
                const fullName = [first, middle, last].filter(Boolean).join(' ') + (suffix ? ', ' + suffix : '');

                document.getElementById('laravel-name-field').value = fullName;
                document.getElementById('laravel-email-field').value = document.getElementById('reservation-email')?.value || '';
                document.getElementById('laravel-phone-field').value = document.getElementById('reservation-phone')?.value || '';
                document.getElementById('laravel-event-type-field').value = document.querySelector('input[name="reservation-type"]:checked')?.value || '';
                document.getElementById('laravel-date-field').value = document.getElementById('reservation-date')?.value || '';
                document.getElementById('laravel-time-field').value = document.getElementById('reservation-time')?.value || '';
                document.getElementById('laravel-notes-field').value = document.getElementById('reservation-notes')?.value || '';
            });
        });
    </script>
</body>
</html>
