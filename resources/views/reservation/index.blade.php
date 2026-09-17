@php
    $customerIsLoggedIn = (bool) session('customer_id');

    $loggedInCustomer = [
        'name' => session('customer_name', 'Member'),
        'email' => session('customer_email', ''),
        'phone' => session('customer_phone', ''),
    ];

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
        'baptism-child-name-first' => old('baptism-child-name-first', ''),
        'baptism-child-name-middle' => old('baptism-child-name-middle', ''),
        'baptism-child-name-last' => old('baptism-child-name-last', ''),
        'baptism-child-dob' => old('baptism-child-dob', ''),
        'baptism-father-name' => old('baptism-father-name', ''),
        'baptism-mother-name' => old('baptism-mother-name', ''),
    ];

    $selectedWeddingRequirements = old('wedding-requirements', []);
    if (!is_array($selectedWeddingRequirements)) {
        $selectedWeddingRequirements = [];
    }

    $funeralMaritalStatusOptions = [
        'single' => 'Single / Unmarried',
        'married_baptized' => 'Married (baptized in the church)',
        'married_not_baptized' => 'Married (not baptized in the church)',
        'widowed' => 'Widowed',
        'annulled' => 'Annulled',
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
    $prefilledReservationDate = $formData['reservation-date'];
    $shouldDisplayReservationForm = !empty($prefilledReservationDate) || old() ? true : false;
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

        if (!in_array($status, ['approved', 'pending', 'booked'], true)) {
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
    <link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reservation-overrides.css') }}">
</head>

<body class="reservation-page schedule-page">
    @include('partials.header')

    <div class="res-wrapper">

        {{-- Page bar --}}
        <div class="res-page-bar">
            <div>
                <div class="res-crumb">Home &rsaquo; Reservation</div>
                <h1 class="res-page-title">Parish Calendar &amp; Reservations</h1>
            </div>
            <div class="res-page-hint">
                <span class="res-hint-dot"></span>
                Click any open date on the calendar to begin
            </div>
        </div>

        {{-- Two-column layout --}}
        <div class="res-layout">

            {{-- LEFT: Calendar --}}
            <div class="res-cal-panel">
                <div id="availability-calendar" class="res-calendar-host availability_calendar"></div>
                <div class="res-cal-legend">
                    <div class="res-legend-item"><span class="res-legend-pulse" style="background:#22c55e;box-shadow:0 0 0 0 rgba(34,197,94,0.5);animation:legend-pulse-green 2s ease-out infinite;"></span> Today</div>
                    <div class="res-legend-item"><span class="res-legend-pulse" style="background:#f59e0b;box-shadow:0 0 0 0 rgba(245,158,11,0.5);animation:legend-pulse-yellow 2s ease-out infinite;"></span> Pending</div>
                    <div class="res-legend-item"><span class="res-legend-pulse" style="background:#ef4444;box-shadow:0 0 0 0 rgba(239,68,68,0.5);animation:legend-pulse-red 2s ease-out infinite;"></span> Has reservation</div>
                    <div class="res-legend-item"><span class="res-legend-dot" style="background:#cbd5e1;"></span> Past / unavailable</div>
                </div>
            </div>

            {{-- RIGHT: Form panel --}}
            <div class="res-form-panel" id="reservationDayModal">

                @if(!$customerIsLoggedIn)
                    <div class="res-login-prompt">
                        <div class="res-empty-icon">
                            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="10.5" width="16" height="10" rx="2.5"/>
                                <path d="M7.5 10.5V7a4.5 4.5 0 0 1 9 0v3.5"/>
                            </svg>
                        </div>
                        <h3 class="res-empty-h">Sign in to reserve</h3>
                        <p class="res-empty-p">
                            Please <a href="{{ route('login') }}">log in</a> or
                            <a href="{{ route('register') }}">create an account</a>
                            to submit a reservation request online.
                        </p>
                    </div>
                @else
                    {{-- Empty state --}}
                    <div class="res-empty-state" id="res-empty-state" {{ $shouldDisplayReservationForm ? 'style=display:none' : '' }}>
                        <div class="res-empty-icon">
                            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4.5" width="18" height="16" rx="2.5"/>
                                <path d="M3 9.5h18M8 2.5v4M16 2.5v4"/>
                                <path d="M8.5 14.5h.01M12 14.5h.01M15.5 14.5h.01M8.5 17.5h.01M12 17.5h.01"/>
                            </svg>
                        </div>
                        <h3 class="res-empty-h">Select a date</h3>
                        <p class="res-empty-p">Click any open date on the calendar to begin your reservation request.</p>
                        @if(session('customer_id'))
                            <a class="res-my-link" href="{{ route('reservation.my') }}">
                                <i class="fa fa-calendar-check-o"></i> View my reservations
                            </a>
                        @endif
                    </div>

                    {{-- Form content --}}
                    <div class="res-form-content" id="res-form-content" {{ $shouldDisplayReservationForm ? '' : 'style=display:none' }}>

                        {{-- Date strip --}}
                        <div class="res-date-strip">
                            <div>
                                <div class="modal-title res-date-main" id="res-date-display">
                                    {{ $prefilledReservationDate ? \Carbon\Carbon::parse($prefilledReservationDate)->format('F j, Y') : '—' }}
                                </div>
                                <div class="res-date-sub" id="res-date-sub">
                                    {{ $prefilledReservationDate ? \Carbon\Carbon::parse($prefilledReservationDate)->format('l') . ' · Open for requests' : '—' }}
                                </div>
                            </div>
                        </div>

                        {{-- Availability notice (populated by JS) --}}
                        <div class="res-availability-notice" data-reservation-availability></div>

                        {{-- Server messages --}}
                        <div data-reservation-messages>
                            @if($errors->any())
                                <div class="alert alert-danger mx-4 mt-3" role="alert">{{ $errors->first() }}</div>
                            @endif
                        </div>

                        <div class="res-form-body">

                            <form id="reservation-form"
                                class="reservation_form"
                                method="POST"
                                action="{{ route('reservation.store') }}"
                                enctype="multipart/form-data"
                                data-server-handled="true"
                                data-reservation-form>
                                @csrf

                                <input type="hidden" name="name"             id="laravel-name-field"       value="{{ old('name',             session('customer_name')) }}">
                                <input type="hidden" name="email"            id="laravel-email-field"      value="{{ old('email',            session('customer_email')) }}">
                                <input type="hidden" name="phone"            id="laravel-phone-field"      value="{{ old('phone',            session('customer_phone')) }}">
                                <input type="hidden" name="event_type"       id="laravel-event-type-field" value="{{ old('event_type',       $formData['reservation-type']) }}">
                                <input type="hidden" name="reservation_date" id="laravel-date-field"       value="{{ old('reservation_date', $formData['reservation-date']) }}">
                                <input type="hidden" name="reservation_time" id="laravel-time-field"       value="{{ old('reservation_time', $formData['reservation-time']) }}">
                                <input type="hidden" name="notes"            id="laravel-notes-field"      value="{{ old('notes',            $formData['reservation-notes']) }}">
                                <input type="hidden" name="customer_id"      value="{{ session('customer_id') }}">
                                <input type="hidden" id="reservation-date"   name="reservation-date"       value="{{ $formData['reservation-date'] }}">

                                {{-- SACRAMENT TYPE --}}
                                <div class="res-section">
                                    <div class="res-section-lbl">Sacrament</div>
                                    <div class="res-type-list">
                                        <label class="res-type-item" data-type="baptism" for="reservation-type-baptism">
                                            <span class="res-ti-icon"><i class="fa fa-plus"></i></span>
                                            <span class="res-ti-text">
                                                <span class="res-ti-label">Baptism</span>
                                                <span class="res-ti-sub">Welcoming a new soul</span>
                                            </span>
                                            <span class="res-ti-check">✓</span>
                                            <input type="radio" id="reservation-type-baptism" name="reservation-type" class="res-type-radio" value="Baptism" required @checked($formData['reservation-type'] === 'Baptism')>
                                        </label>
                                        <label class="res-type-item" data-type="wedding" for="reservation-type-wedding">
                                            <span class="res-ti-icon"><i class="fa fa-heart"></i></span>
                                            <span class="res-ti-text">
                                                <span class="res-ti-label">Wedding</span>
                                                <span class="res-ti-sub">Celebrating your union</span>
                                            </span>
                                            <span class="res-ti-check">✓</span>
                                            <input type="radio" id="reservation-type-wedding" name="reservation-type" class="res-type-radio" value="Wedding" @checked($formData['reservation-type'] === 'Wedding')>
                                        </label>
                                        <label class="res-type-item" data-type="funeral" for="reservation-type-funeral">
                                            <span class="res-ti-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="currentColor"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg></span>
                                            <span class="res-ti-text">
                                                <span class="res-ti-label">Funeral Mass</span>
                                                <span class="res-ti-sub">Honoring a life lived</span>
                                            </span>
                                            <span class="res-ti-check">✓</span>
                                            <input type="radio" id="reservation-type-funeral" name="reservation-type" class="res-type-radio" value="Funeral" @checked($formData['reservation-type'] === 'Funeral')>
                                        </label>
                                    </div>
                                </div>


                                {{-- TIME --}}
                                <div class="res-section">
                                    <div class="res-section-lbl">Available times</div>
                                    {{-- Native select hidden; JS still uses it for state --}}
                                    <select id="reservation-time" name="reservation-time" class="res-time-select form-control"
                                            data-initial-value="{{ $formData['reservation-time'] }}"
                                            style="display:none" tabindex="-1" aria-hidden="true">
                                        <option value="">Select a time</option>
                                    </select>
                                    {{-- Custom styled dropdown --}}
                                    <div class="res-csl" id="reservation-time-custom">
                                        <button type="button" class="res-csl-btn" id="reservation-time-btn">
                                            <span class="res-csl-label" id="reservation-time-label">Select a time</span>
                                            <i class="fa fa-chevron-down res-csl-arrow"></i>
                                        </button>
                                        <ul class="res-csl-list" id="reservation-time-list"></ul>
                                    </div>
                                    <small class="form-text text-muted mt-1" data-reservation-time-help>Choose a ceremony type and date first.</small>
                                </div>

                                {{-- YOUR INFORMATION --}}
                                <div class="res-section">
                                    <div class="res-section-lbl">Your information</div>
                                    <div class="res-field-grid">
                                        <div class="res-fg">
                                            <label for="reservation-name-first">First name</label>
                                            <input type="text" id="reservation-name-first" name="reservation-name-first" class="form-control" placeholder="First name" required autocomplete="given-name" value="{{ $formData['reservation-name-first'] }}">
                                        </div>
                                        <div class="res-fg">
                                            <label for="reservation-name-last">Last name</label>
                                            <input type="text" id="reservation-name-last" name="reservation-name-last" class="form-control" placeholder="Last name" required autocomplete="family-name" value="{{ $formData['reservation-name-last'] }}">
                                        </div>
                                        <div class="res-fg">
                                            <label for="reservation-name-middle">Middle name</label>
                                            <input type="text" id="reservation-name-middle" name="reservation-name-middle" class="form-control" placeholder="Middle name" required autocomplete="additional-name" value="{{ $formData['reservation-name-middle'] }}">
                                        </div>
                                        <div class="res-fg">
                                            <label for="reservation-name-suffix">Suffix (optional)</label>
                                            <input type="text" id="reservation-name-suffix" name="reservation-name-suffix" class="form-control" placeholder="Jr., III, etc." autocomplete="honorific-suffix" value="{{ $formData['reservation-name-suffix'] }}">
                                        </div>
                                        <div class="res-fg">
                                            <label for="reservation-email">Email</label>
                                            <input type="email" id="reservation-email" name="reservation-email" class="form-control" placeholder="name@example.com" required value="{{ $formData['reservation-email'] }}">
                                        </div>
                                        <div class="res-fg">
                                            <label for="reservation-phone">Contact number</label>
                                            <input type="tel" id="reservation-phone" name="reservation-phone" class="form-control" placeholder="(042) 545-9244" required value="{{ $formData['reservation-phone'] }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- BAPTISM DETAILS --}}
                                <div id="baptism-details" class="res-section" @if($formData['reservation-type'] !== 'Baptism') style="display:none" @endif>
                                    <div class="res-section-lbl">Child's information</div>
                                    <div class="res-field-grid">
                                        <div class="res-fg">
                                            <label for="baptism-child-name-first">First name</label>
                                            <input type="text" id="baptism-child-name-first" name="baptism-child-name-first" class="form-control" placeholder="First name" data-baptism-required="true" value="{{ $formData['baptism-child-name-first'] }}">
                                        </div>
                                        <div class="res-fg">
                                            <label for="baptism-child-name-last">Last name</label>
                                            <input type="text" id="baptism-child-name-last" name="baptism-child-name-last" class="form-control" placeholder="Last name" data-baptism-required="true" value="{{ $formData['baptism-child-name-last'] }}">
                                        </div>
                                        <div class="res-fg">
                                            <label for="baptism-child-name-middle">Middle name</label>
                                            <input type="text" id="baptism-child-name-middle" name="baptism-child-name-middle" class="form-control" placeholder="Middle name" data-baptism-required="true" value="{{ $formData['baptism-child-name-middle'] }}">
                                        </div>
                                        <div class="res-fg">
                                            <label for="baptism-child-name-suffix">Suffix (optional)</label>
                                            <input type="text" id="baptism-child-name-suffix" name="baptism-child-name-suffix" class="form-control" placeholder="Jr., III, etc." value="{{ old('baptism-child-name-suffix', '') }}">
                                        </div>
                                        <div class="res-fg res-fg--dob">
                                            <label for="baptism-child-dob">Date of birth</label>
                                            <div class="dob-cal-wrap" id="dob-cal-wrap">
                                                <div class="dob-input-group">
                                                    <input type="text" id="baptism-child-dob" class="form-control" placeholder="MM/DD/YYYY" data-baptism-required="true" autocomplete="off" value="{{ $formData['baptism-child-dob'] ? \Carbon\Carbon::parse($formData['baptism-child-dob'])->format('m/d/Y') : '' }}">
                                                    <button type="button" class="dob-cal-icon-btn" onclick="dobCalToggle()" tabindex="-1">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                    </button>
                                                </div>
                                                <input type="hidden" id="baptism-child-dob-raw" name="baptism-child-dob" value="{{ $formData['baptism-child-dob'] }}">
                                                <div class="dob-cal-popup" id="dob-cal-popup" style="display:none;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="res-section-lbl" style="margin-top:18px">Parents</div>
                                    <div class="res-field-grid">
                                        <div class="res-fg">
                                            <label for="baptism-father-name">Father's full name</label>
                                            <input type="text" id="baptism-father-name" name="baptism-father-name" class="form-control" placeholder="Full name" data-baptism-required="true" value="{{ $formData['baptism-father-name'] }}">
                                        </div>
                                        <div class="res-fg">
                                            <label for="baptism-mother-name">Mother's full name</label>
                                            <input type="text" id="baptism-mother-name" name="baptism-mother-name" class="form-control" placeholder="Full name" data-baptism-required="true" value="{{ $formData['baptism-mother-name'] }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- WEDDING DETAILS --}}
                                <div id="wedding-details" class="reservation_attachment_box res-section" @if($formData['reservation-type'] !== 'Wedding') style="display:none" @endif>
                                    <div class="res-section-lbl">Groom's name</div>
                                    <div class="res-field-grid">
                                        <div class="res-fg">
                                            <label for="wedding-groom-name-first">First name</label>
                                            <input type="text" class="form-control" id="wedding-groom-name-first" name="wedding-groom-name-first" placeholder="First name" value="{{ $formData['wedding-groom-name-first'] }}" data-wedding-required="true">
                                        </div>
                                        <div class="res-fg">
                                            <label for="wedding-groom-name-last">Last name</label>
                                            <input type="text" class="form-control" id="wedding-groom-name-last" name="wedding-groom-name-last" placeholder="Last name" value="{{ $formData['wedding-groom-name-last'] }}" data-wedding-required="true">
                                        </div>
                                        <div class="res-fg">
                                            <label for="wedding-groom-name-middle">Middle name</label>
                                            <input type="text" class="form-control" id="wedding-groom-name-middle" name="wedding-groom-name-middle" placeholder="Middle name" value="{{ $formData['wedding-groom-name-middle'] }}" data-wedding-required="true">
                                        </div>
                                        <div class="res-fg">
                                            <label for="wedding-groom-name-suffix">Suffix (optional)</label>
                                            <input type="text" class="form-control" id="wedding-groom-name-suffix" name="wedding-groom-name-suffix" placeholder="Jr., III, etc." value="{{ $formData['wedding-groom-name-suffix'] }}">
                                        </div>
                                    </div>

                                    <div class="res-section-lbl" style="margin-top:18px">Bride's name</div>
                                    <div class="res-field-grid">
                                        <div class="res-fg">
                                            <label for="wedding-bride-name-first">First name</label>
                                            <input type="text" class="form-control" id="wedding-bride-name-first" name="wedding-bride-name-first" placeholder="First name" value="{{ $formData['wedding-bride-name-first'] }}" data-wedding-required="true">
                                        </div>
                                        <div class="res-fg">
                                            <label for="wedding-bride-name-last">Last name</label>
                                            <input type="text" class="form-control" id="wedding-bride-name-last" name="wedding-bride-name-last" placeholder="Last name" value="{{ $formData['wedding-bride-name-last'] }}" data-wedding-required="true">
                                        </div>
                                        <div class="res-fg">
                                            <label for="wedding-bride-name-middle">Middle name</label>
                                            <input type="text" class="form-control" id="wedding-bride-name-middle" name="wedding-bride-name-middle" placeholder="Middle name" value="{{ $formData['wedding-bride-name-middle'] }}" data-wedding-required="true">
                                        </div>
                                        <div class="res-fg">
                                            <label for="wedding-bride-name-suffix">Suffix (optional)</label>
                                            <input type="text" class="form-control" id="wedding-bride-name-suffix" name="wedding-bride-name-suffix" placeholder="Jr., III, etc." value="{{ $formData['wedding-bride-name-suffix'] }}">
                                        </div>
                                    </div>

                                    <div class="res-section-lbl" style="margin-top:18px">Pre-Cana seminar date</div>
                                    <div class="res-field-grid" style="grid-template-columns:1fr;">
                                        <div class="res-fg res-fg--dob">
                                            <div class="dob-cal-wrap" id="seminar-cal-wrap">
                                                <div class="dob-input-group">
                                                    <input type="text" id="wedding-seminar-date-display" class="form-control" placeholder="MM/DD/YYYY" autocomplete="off" value="{{ $formData['wedding-seminar-date'] ? \Carbon\Carbon::parse($formData['wedding-seminar-date'])->format('m/d/Y') : '' }}">
                                                    <button type="button" class="dob-cal-icon-btn" onclick="seminarCalToggle()" tabindex="-1">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                    </button>
                                                </div>
                                                <input type="hidden" id="wedding-seminar-date" name="wedding-seminar-date" data-wedding-required="true" value="{{ $formData['wedding-seminar-date'] }}">
                                                <div class="dob-cal-popup" id="seminar-cal-popup" style="display:none;"></div>
                                            </div>
                                            <small class="form-text text-muted mt-1">Required before your wedding. Pre-Cana seminars are usually held within a year of the wedding date.</small>
                                        </div>
                                    </div>

                                    <div class="res-section-lbl" style="margin-top:18px">Kumpisal / Kumpil / Binyag details</div>
                                    <div class="form-group">
                                        <textarea class="form-control" id="wedding-sacrament-details" name="wedding-sacrament-details" rows="2" placeholder="Parishes or dates for confession, confirmation, and baptism">{{ $formData['wedding-sacrament-details'] }}</textarea>
                                    </div>

                                    <div class="res-section-lbl" style="margin-top:4px">Mga kailangan bago ikasal</div>
                                    <p class="small text-muted mb-2">Confirm you have prepared the following requirements.</p>
                                    @foreach($weddingRequirementChecklist as $requirementKey => $requirementLabel)
                                        @php $inputId = 'wedding-requirement-' . preg_replace('/[^A-Za-z0-9_-]/', '-', $requirementKey); @endphp
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input type="checkbox" class="custom-control-input" id="{{ $inputId }}" name="wedding-requirements[]" value="{{ $requirementKey }}" @checked(in_array($requirementKey, $selectedWeddingRequirements, true)) data-wedding-required="true" data-wedding-checkbox="true">
                                            <label class="custom-control-label" for="{{ $inputId }}">{{ $requirementLabel }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- FUNERAL DETAILS --}}
                                <div id="funeral-details" class="reservation_attachment_box res-section">
                                    <h6 class="mb-3">Funeral information</h6>
                                    <div class="alert alert-warning small" role="alert">Arrange the funeral schedule at the parish office at least one day before burial.</div>
                                    <div class="form-group">
                                        <label class="d-block" for="funeral-deceased-name-first">Name of the deceased</label>
                                        <div class="form-row">
                                            <div class="col-6 mb-2"><input type="text" class="form-control" id="funeral-deceased-name-first" name="funeral-deceased-name-first" placeholder="First" value="{{ $formData['funeral-deceased-name-first'] }}" data-funeral-required="true"></div>
                                            <div class="col-6 mb-2"><input type="text" class="form-control" id="funeral-deceased-name-middle" name="funeral-deceased-name-middle" placeholder="Middle" value="{{ $formData['funeral-deceased-name-middle'] }}"></div>
                                            <div class="col-6 mb-2"><input type="text" class="form-control" id="funeral-deceased-name-last" name="funeral-deceased-name-last" placeholder="Last" value="{{ $formData['funeral-deceased-name-last'] }}" data-funeral-required="true"></div>
                                            <div class="col-6 mb-2"><input type="text" class="form-control" id="funeral-deceased-name-suffix" name="funeral-deceased-name-suffix" placeholder="Suffix (optional)" value="{{ $formData['funeral-deceased-name-suffix'] }}"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="funeral-marital-status">Marital status of the deceased</label>
                                        <select class="res-time-select form-control" id="funeral-marital-status" name="funeral-marital-status" data-funeral-required="true" data-funeral-marital-select="true" style="display:none">
                                            <option value="">Select status</option>
                                            @foreach($funeralMaritalStatusOptions as $statusValue => $statusLabel)
                                                <option value="{{ $statusValue }}" @selected($formData['funeral-marital-status'] === $statusValue)>{{ $statusLabel }}</option>
                                            @endforeach
                                        </select>
                                        <div class="res-csl" id="funeral-marital-custom">
                                            <button type="button" class="res-csl-btn" id="funeral-marital-btn">
                                                <span class="res-csl-label" id="funeral-marital-label">Select status</span>
                                                <i class="fa fa-chevron-down res-csl-arrow"></i>
                                            </button>
                                            <ul class="res-csl-list" id="funeral-marital-list"></ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- ATTACHMENTS --}}
                                @foreach($attachmentRequirementSets as $eventType => $attachmentSet)
                                    @php
                                        $documents = $attachmentSet['documents'] ?? [];
                                        $sectionId = $attachmentSet['id'] ?? 'attachments-' . strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $eventType));
                                        $shouldShowSection = $shouldDisplayReservationForm && $formData['reservation-type'] === $eventType;
                                    @endphp
                                    @if(!empty($documents))
                                        <div class="reservation_attachment_box res-section" id="{{ $sectionId }}" data-attachment-section="{{ $eventType }}" style="{{ $shouldShowSection ? '' : 'display:none;' }}">
                                            <div class="res-section-lbl">{{ $attachmentSet['title'] ?? 'Required documents' }}</div>
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
                                                    <label for="{{ $inputId }}">{{ $label }}</label>
                                                    <div class="res-file">
                                                        <input type="file" class="res-file-input" id="{{ $inputId }}" name="{{ $fieldName }}" accept="{{ $accept }}">
                                                        <label for="{{ $inputId }}" class="res-file-btn">
                                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                                            <span class="res-file-name">Choose file</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if(!empty($attachmentSet['notes']))
                                                <ul class="small pl-3 mb-0">
                                                    @foreach($attachmentSet['notes'] as $note)<li>{{ $note }}</li>@endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach

                                {{-- NOTES --}}
                                <div class="res-section res-section--notes" data-reservation-form-toggle-target {{ $shouldDisplayReservationForm ? '' : 'hidden' }}>
                                    <div class="res-section-lbl">Additional notes</div>
                                    <textarea id="reservation-notes" name="reservation-notes" class="form-control" rows="3" placeholder="Tell us about your celebration" style="border-radius:8px;font-size:.84rem;border:1.5px solid #e2e8f0;">{{ $formData['reservation-notes'] }}</textarea>
                                </div>

                                {{-- UNAVAILABLE NOTICE --}}
                                <div class="alert alert-warning small d-none" role="alert" data-reservation-time-warning></div>

                                {{-- SUBMIT --}}
                                <button type="submit"
                                    class="res-submit"
                                    data-reservation-form-toggle-target
                                    {{ $shouldDisplayReservationForm ? '' : 'hidden' }}
                                    data-reservation-submit>
                                    <span>Submit Reservation Request</span>
                                    <span class="spinner-border spinner-border-sm ml-2 align-middle d-none" role="status" aria-hidden="true" data-loading-spinner></span>
                                </button>

                            </form>
                        </div>{{-- /res-form-body --}}
                    </div>{{-- /res-form-content --}}
                @endif

            </div>{{-- /res-form-panel --}}
        </div>{{-- /res-layout --}}
    </div>{{-- /res-wrapper --}}

    {{-- footer hidden on reservation page --}}

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
    @if(session('reservation_notifications'))
    @php $rnotif = session('reservation_notifications')[0] ?? []; @endphp
    <div class="ps-overlay" id="ps-res-overlay">
      <div class="ps-modal">
        <div class="ps-hdr" id="ps-res-hdr">
          <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
          <span class="ps-parish">St. John the Baptist Parish</span>
          <span class="ps-dot"></span>
          <span class="ps-loc">Tiaong, Quezon</span>
        </div>
        <div class="ps-body" id="ps-res-body">
          <div class="ps-glow"></div>
          <div class="ps-icon">
            <div class="ps-ring"></div>
            <div class="ps-inner">
              <svg viewBox="0 0 42 42"><polyline class="ps-chk" points="9,22 17,30 33,12"/></svg>
            </div>
          </div>
          <h2 class="ps-title">Reservation Submitted</h2>
          <p class="ps-sub">Your request has been received. The parish office will review and confirm your reservation shortly.</p>
          <div class="ps-badge"><span class="ps-badge-dot"></span>Pending parish confirmation</div>
          <div class="ps-divider"></div>
          <div class="ps-btns">
            <button class="ps-btn-out" onclick="document.getElementById('ps-res-overlay').remove()">View Calendar</button>
            <button class="ps-btn-prim" onclick="document.getElementById('ps-res-overlay').remove()">Done</button>
          </div>
        </div>
      </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      const gColors = ['#dc2626','#fca5a5','rgba(255,255,255,.18)','rgba(220,38,38,.35)'];
      const hColors = ['rgba(255,255,255,.25)','rgba(220,38,38,.4)','#fca5a5'];
      function spawnDots(el, cols, count, cls) {
        for (let i = 0; i < count; i++) {
          const p = document.createElement('div');
          const s = Math.random()*6+4;
          p.className = cls;
          p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
          el.appendChild(p);
        }
      }
      const hdr = document.getElementById('ps-res-hdr');
      const bdy = document.getElementById('ps-res-body');
      if (hdr) spawnDots(hdr, hColors, 10, 'ps-hdr-particle');
      if (bdy) spawnDots(bdy, gColors, 22, 'ps-particle');
      const overlay = document.getElementById('ps-res-overlay');
      if (overlay) overlay.addEventListener('click', function(e) { if (e.target === this) this.remove(); });
    });
    </script>
    @endif

    @if(session('auth_notification'))
    @php $notif = session('auth_notification'); $isLogin = str_contains($notif['title'] ?? '', 'Login'); @endphp
    {{-- Inline custom auth modal (same as app.blade) --}}
    <div class="ps-overlay" id="ps-overlay">
      <div class="ps-modal">
        <div class="ps-hdr" id="ps-hdr-res">
          <svg style="width:18px;height:18px;flex-shrink:0" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
          <span class="ps-parish">St. John the Baptist Parish</span>
          <span class="ps-dot"></span>
          <span class="ps-loc">Tiaong, Quezon</span>
        </div>
        <div class="ps-body" id="ps-body-res">
          <div class="ps-glow"></div>
          <div class="ps-icon">
            <div class="ps-ring"></div>
            <div class="ps-inner">
              <svg viewBox="0 0 42 42"><polyline class="ps-chk" points="9,22 17,30 33,12"/></svg>
            </div>
          </div>
          <h2 class="ps-title">{{ $notif['title'] }}</h2>
          <p class="ps-sub">{{ $notif['text'] }}</p>
          @if($isLogin && session('customer_name'))
          <div class="ps-chip"><span class="ps-chip-dot"></span>{{ session('customer_name') }}</div>
          <div class="ps-btns">
            <button class="ps-btn-out" onclick="document.getElementById('ps-overlay').remove()">Close</button>
            <button class="ps-btn-prim" onclick="document.getElementById('ps-overlay').remove()">Continue</button>
          </div>
          @else
          <div class="ps-btns" style="justify-content:center">
            <button class="ps-btn-solo" onclick="document.getElementById('ps-overlay').remove()">OK</button>
          </div>
          @endif
        </div>
      </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      const colors = ['#009DFF','#60C4FF','rgba(255,255,255,.2)','rgba(34,197,94,.4)'];
      const hColors = ['rgba(255,255,255,.25)','#60C4FF','#009DFF'];
      function spawnDots(el, cols, count) {
        for (let i = 0; i < count; i++) {
          const p = document.createElement('div');
          const s = Math.random()*6+4;
          p.className = 'ps-particle';
          p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
          el.appendChild(p);
        }
      }
      const hdr = document.getElementById('ps-hdr-res');
      const bdy = document.getElementById('ps-body-res');
      if (hdr) spawnDots(hdr, hColors, 10);
      if (bdy) spawnDots(bdy, colors, 20);
      const overlay = document.getElementById('ps-overlay');
      if (overlay) overlay.addEventListener('click', function(e) { if (e.target === this) this.remove(); });
    });
    </script>
    @endif

    <script src="{{ asset('js/reservations.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var panel    = document.getElementById('reservationDayModal');
        var notice   = document.querySelector('[data-reservation-availability]');

        // The actual scroll container is .res-form-body (flex:1 inside .res-form-content)
        function getScrollEl() {
            return panel ? panel.querySelector('.res-form-body') : null;
        }

        // Scroll lock: trap wheel events so only the form body scrolls
        if (panel) {
            panel.addEventListener('wheel', function (e) {
                var el = getScrollEl();
                if (!el) return;
                var delta    = e.deltaY;
                var atTop    = el.scrollTop <= 0;
                var atBottom = el.scrollTop + el.clientHeight >= el.scrollHeight - 1;
                if ((delta < 0 && atTop) || (delta > 0 && atBottom)) return;
                e.preventDefault();
                el.scrollTop += delta;
            }, { passive: false });
        }

        // Fade + collapse availability notice as the form body scrolls
        function bindFade() {
            var el = getScrollEl();
            if (!el || el._fadeBound) return;
            el._fadeBound = true;
            if (notice) {
                notice.style.overflow   = 'hidden';
                notice.style.transition = 'opacity .15s, max-height .2s';
            }
            el.addEventListener('scroll', function () {
                var scrollTop = el.scrollTop;
                if (!notice) return;
                // Capture full height lazily so it's measured after content renders
                var fullH = notice.scrollHeight;
                var fadeDistance = Math.max(fullH * 0.7, 60);
                var op = Math.max(0, 1 - scrollTop / fadeDistance);
                notice.style.opacity = op;
                if (op >= 1) {
                    notice.style.maxHeight = '';   // let it breathe when fully visible
                } else {
                    notice.style.maxHeight = (fullH * op) + 'px';
                }
            });
        }

        bindFade();
        setTimeout(bindFade, 400);
    });
    </script>
    <script>
    (function() {
        const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        const MON_ABB = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const today = new Date(); today.setHours(23,59,59,999);
        let calYear  = new Date().getFullYear();
        let calMonth = new Date().getMonth();
        let calSelected = null;
        let calView = 'day'; // 'day' | 'month' | 'year'

        const initVal = document.getElementById('baptism-child-dob-raw')?.value;
        if (initVal) {
            const d = new Date(initVal + 'T00:00:00');
            if (!isNaN(d)) { calYear = d.getFullYear(); calMonth = d.getMonth(); calSelected = { y: calYear, m: calMonth, d: d.getDate() }; }
        }

        const pad = n => String(n).padStart(2,'0');

        function render() {
            const popup = document.getElementById('dob-cal-popup');
            if (!popup) return;
            if (calView === 'month') { popup.innerHTML = buildMonthView(); return; }
            if (calView === 'year')  { popup.innerHTML = buildYearView();  return; }
            popup.innerHTML = buildDayView();
        }

        function buildDayView() {
            const firstDay = new Date(calYear, calMonth, 1).getDay();
            const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
            const now = new Date();
            let cells = '';
            for (let i = 0; i < firstDay; i++) cells += `<span class="dob-cal-day is-blank"></span>`;
            for (let d = 1; d <= daysInMonth; d++) {
                const isT = now.getFullYear()===calYear && now.getMonth()===calMonth && now.getDate()===d;
                const isF = new Date(calYear, calMonth, d) > today;
                const isS = calSelected && calSelected.y===calYear && calSelected.m===calMonth && calSelected.d===d;
                let cls = 'dob-cal-day' + (isT?' is-today':'') + (isS?' is-selected':'') + (isF?' is-disabled':'');
                cells += `<button type="button" class="${cls}" ${isF?'disabled':''} data-y="${calYear}" data-m="${calMonth}" data-d="${d}" onclick="dobCalPick(this)">${d}</button>`;
            }
            const total = firstDay + daysInMonth;
            const next = total % 7 === 0 ? 0 : 7 - (total % 7);
            for (let i = 0; i < next; i++) cells += `<span class="dob-cal-day is-blank"></span>`;
            return `<div class="dob-cal-header">
                <button type="button" class="dob-cal-nav" onclick="dobCalShift(-1)">&#8249;</button>
                <div class="dob-cal-hdr-labels">
                    <button type="button" class="dob-cal-hdr-btn" onclick="dobCalSetView('month')">${MONTHS[calMonth]}</button>
                    <button type="button" class="dob-cal-hdr-btn" onclick="dobCalSetView('year')">${calYear}</button>
                </div>
                <button type="button" class="dob-cal-nav" onclick="dobCalShift(1)">&#8250;</button>
            </div>
            <div class="dob-cal-days-row"><span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span></div>
            <div class="dob-cal-grid">${cells}</div>
            <div class="dob-cal-footer">
                <button type="button" class="dob-cal-today-btn" onclick="dobCalSelectToday()">Today</button>
                <button type="button" class="dob-cal-clear-btn" onclick="dobCalClear()">Clear</button>
            </div>`;
        }

        function buildMonthView() {
            const now = new Date();
            let cells = '';
            for (let m = 0; m < 12; m++) {
                const isT = now.getFullYear()===calYear && now.getMonth()===m;
                const isS = calSelected && calSelected.y===calYear && calSelected.m===m;
                cells += `<button type="button" class="dob-cal-mcell${isT?' is-today':''}${isS?' is-selected':''}" onclick="dobCalPickMonth(${m})">${MON_ABB[m]}</button>`;
            }
            return `<div class="dob-cal-header">
                <button type="button" class="dob-cal-nav" onclick="dobCalShift(-1)">&#8249;</button>
                <button type="button" class="dob-cal-hdr-btn" onclick="dobCalSetView('year')">${calYear}</button>
                <button type="button" class="dob-cal-nav" onclick="dobCalShift(1)">&#8250;</button>
            </div>
            <div class="dob-cal-month-grid">${cells}</div>`;
        }

        function buildYearView() {
            const now = new Date();
            const startYear = Math.floor(calYear / 12) * 12;
            let cells = '';
            for (let y = startYear; y < startYear + 12; y++) {
                const isT = now.getFullYear()===y;
                const isS = calSelected && calSelected.y===y;
                cells += `<button type="button" class="dob-cal-ycell${isT?' is-today':''}${isS?' is-selected':''}" onclick="dobCalPickYear(${y})">${y}</button>`;
            }
            return `<div class="dob-cal-header">
                <button type="button" class="dob-cal-nav" onclick="dobCalShift(-1)">&#8249;</button>
                <span class="dob-cal-hdr-range">${startYear} – ${startYear+11}</span>
                <button type="button" class="dob-cal-nav" onclick="dobCalShift(1)">&#8250;</button>
            </div>
            <div class="dob-cal-year-grid">${cells}</div>`;
        }

        window.dobCalToggle = function() {
            const popup = document.getElementById('dob-cal-popup');
            if (!popup) return;
            if (popup.style.display !== 'none') { popup.style.display = 'none'; return; }
            calView = 'day';
            render();
            popup.style.display = 'block';
        };

        window.dobCalShift = function(dir) {
            if (calView === 'day')   { calMonth += dir; if (calMonth>11){calMonth=0;calYear++;}if(calMonth<0){calMonth=11;calYear--;} }
            else if (calView==='month') { calYear += dir; }
            else { calYear = Math.floor(calYear/12)*12 + dir*12; }
            render();
        };

        window.dobCalSetView = function(v) { calView = v; render(); };

        window.dobCalPickMonth = function(m) { calMonth = m; calView = 'day'; render(); };

        window.dobCalPickYear = function(y) { calYear = y; calView = 'month'; render(); };

        window.dobCalPick = function(el) {
            if (el.disabled) return;
            let y = parseInt(el.dataset.y), m = parseInt(el.dataset.m), d = parseInt(el.dataset.d);
            const nd = new Date(y, m, d); if (nd > today) return;
            y = nd.getFullYear(); m = nd.getMonth(); d = nd.getDate();
            calYear = y; calMonth = m; calSelected = { y, m, d };
            const raw = document.getElementById('baptism-child-dob-raw');
            if (raw) raw.value = `${y}-${pad(m+1)}-${pad(d)}`;
            const disp = document.getElementById('baptism-child-dob');
            if (disp) disp.value = `${pad(m+1)}/${pad(d)}/${y}`;
            document.getElementById('dob-cal-popup').style.display = 'none';
        };

        window.dobCalSelectToday = function() {
            const now = new Date();
            const fake = { dataset:{y:now.getFullYear(),m:now.getMonth(),d:now.getDate()}, disabled:false };
            dobCalPick(fake);
        };

        window.dobCalClear = function() {
            calSelected = null;
            const raw = document.getElementById('baptism-child-dob-raw'); if (raw) raw.value = '';
            const disp = document.getElementById('baptism-child-dob'); if (disp) disp.value = '';
            document.getElementById('dob-cal-popup').style.display = 'none';
        };

        // Parse typed date on blur
        document.addEventListener('DOMContentLoaded', function() {
            const inp = document.getElementById('baptism-child-dob');
            if (!inp) return;
            inp.addEventListener('blur', function() {
                const v = this.value.trim(); if (!v) return;
                const m1 = v.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
                const m2 = v.match(/^(\d{4})-(\d{2})-(\d{2})$/);
                let nd = null;
                if (m1) nd = new Date(+m1[3], +m1[1]-1, +m1[2]);
                else if (m2) nd = new Date(v+'T00:00:00');
                else { const t = new Date(v); if (!isNaN(t)) nd = t; }
                if (nd && !isNaN(nd) && nd <= today) {
                    const y=nd.getFullYear(), mo=nd.getMonth(), d=nd.getDate();
                    calYear=y; calMonth=mo; calSelected={y,m:mo,d};
                    const raw=document.getElementById('baptism-child-dob-raw');
                    if (raw) raw.value=`${y}-${pad(mo+1)}-${pad(d)}`;
                    this.value=`${pad(mo+1)}/${pad(d)}/${y}`;
                } else if (v) {
                    this.value='';
                    const raw=document.getElementById('baptism-child-dob-raw'); if (raw) raw.value='';
                    calSelected=null;
                }
            });
        });

        // Close on outside click — but NOT when the click was on a re-rendered element
        document.addEventListener('click', function(e) {
            if (!document.contains(e.target)) return; // element was removed by innerHTML re-render
            const wrap = document.getElementById('dob-cal-wrap');
            if (wrap && !wrap.contains(e.target)) {
                const popup = document.getElementById('dob-cal-popup');
                if (popup) popup.style.display = 'none';
            }
        });
    })();
    </script>
    <script>
    /* Seminar date calendar — valid range: 1–5 days before wedding date */
    (function() {
        const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        const MON_ABB = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const today = new Date();
        let calYear  = today.getFullYear();
        let calMonth = today.getMonth();
        let calSelected = null;
        let calView = 'day';

        const initVal = document.getElementById('wedding-seminar-date')?.value;
        if (initVal) {
            const d = new Date(initVal + 'T00:00:00');
            if (!isNaN(d)) { calYear = d.getFullYear(); calMonth = d.getMonth(); calSelected = { y: calYear, m: calMonth, d: d.getDate() }; }
        }

        const pad = n => String(n).padStart(2,'0');

        function getSeminarRange() {
            const rdInput = document.getElementById('reservation-date');
            if (!rdInput || !rdInput.value) return null;
            const parts = rdInput.value.split('-');
            if (parts.length !== 3) return null;
            const wedding = new Date(+parts[0], +parts[1]-1, +parts[2]);
            if (isNaN(wedding.getTime())) return null;
            const max = new Date(wedding.getTime());
            max.setDate(max.getDate() - 1);
            const min = new Date(wedding.getTime());
            min.setDate(min.getDate() - 5);
            return { min, max };
        }

        function render() {
            const popup = document.getElementById('seminar-cal-popup');
            if (!popup) return;
            if (calView === 'month') { popup.innerHTML = buildMonthView(); return; }
            if (calView === 'year')  { popup.innerHTML = buildYearView();  return; }
            popup.innerHTML = buildDayView();
        }

        function buildDayView() {
            const range = getSeminarRange();
            const firstDay = new Date(calYear, calMonth, 1).getDay();
            const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
            const now = new Date();
            let cells = '';
            for (let i = 0; i < firstDay; i++) cells += `<span class="dob-cal-day is-blank"></span>`;
            for (let d = 1; d <= daysInMonth; d++) {
                const thisDate = new Date(calYear, calMonth, d);
                const isT = now.getFullYear()===calYear && now.getMonth()===calMonth && now.getDate()===d;
                const isS = calSelected && calSelected.y===calYear && calSelected.m===calMonth && calSelected.d===d;
                const isDisabled = range ? (thisDate < range.min || thisDate > range.max) : false;
                cells += `<button type="button" class="dob-cal-day${isT?' is-today':''}${isS?' is-selected':''}${isDisabled?' is-disabled':''}" ${isDisabled?'disabled':''} data-y="${calYear}" data-m="${calMonth}" data-d="${d}" onclick="seminarCalPick(this)">${d}</button>`;
            }
            const total = firstDay + daysInMonth;
            const next = total % 7 === 0 ? 0 : 7 - (total % 7);
            for (let i = 0; i < next; i++) cells += `<span class="dob-cal-day is-blank"></span>`;
            return `<div class="dob-cal-header">
                <button type="button" class="dob-cal-nav" onclick="seminarCalShift(-1)">&#8249;</button>
                <div class="dob-cal-hdr-labels">
                    <button type="button" class="dob-cal-hdr-btn" onclick="seminarCalSetView('month')">${MONTHS[calMonth]}</button>
                    <button type="button" class="dob-cal-hdr-btn" onclick="seminarCalSetView('year')">${calYear}</button>
                </div>
                <button type="button" class="dob-cal-nav" onclick="seminarCalShift(1)">&#8250;</button>
            </div>
            <div class="dob-cal-days-row"><span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span></div>
            <div class="dob-cal-grid">${cells}</div>
            <div class="dob-cal-footer">
                <button type="button" class="dob-cal-today-btn" onclick="seminarCalSelectToday()">Today</button>
                <button type="button" class="dob-cal-clear-btn" onclick="seminarCalClear()">Clear</button>
            </div>`;
        }

        function buildMonthView() {
            const range = getSeminarRange();
            const now = new Date(); let cells = '';
            for (let m = 0; m < 12; m++) {
                const isT = now.getFullYear()===calYear && now.getMonth()===m;
                const isS = calSelected && calSelected.y===calYear && calSelected.m===m;
                let isDisabled = false;
                if (range) {
                    const monthStart = new Date(calYear, m, 1);
                    const monthEnd = new Date(calYear, m + 1, 0);
                    isDisabled = monthEnd < range.min || monthStart > range.max;
                }
                cells += `<button type="button" class="dob-cal-mcell${isT?' is-today':''}${isS?' is-selected':''}${isDisabled?' is-disabled':''}" ${isDisabled?'disabled':''} onclick="seminarCalPickMonth(${m})">${MON_ABB[m]}</button>`;
            }
            return `<div class="dob-cal-header">
                <button type="button" class="dob-cal-nav" onclick="seminarCalShift(-1)">&#8249;</button>
                <button type="button" class="dob-cal-hdr-btn" onclick="seminarCalSetView('year')">${calYear}</button>
                <button type="button" class="dob-cal-nav" onclick="seminarCalShift(1)">&#8250;</button>
            </div>
            <div class="dob-cal-month-grid">${cells}</div>`;
        }

        function buildYearView() {
            const range = getSeminarRange();
            const now = new Date(); const startYear = Math.floor(calYear / 12) * 12; let cells = '';
            for (let y = startYear; y < startYear + 12; y++) {
                const isT = now.getFullYear()===y; const isS = calSelected && calSelected.y===y;
                let isDisabled = false;
                if (range) {
                    const yearStart = new Date(y, 0, 1);
                    const yearEnd = new Date(y, 11, 31);
                    isDisabled = yearEnd < range.min || yearStart > range.max;
                }
                cells += `<button type="button" class="dob-cal-ycell${isT?' is-today':''}${isS?' is-selected':''}${isDisabled?' is-disabled':''}" ${isDisabled?'disabled':''} onclick="seminarCalPickYear(${y})">${y}</button>`;
            }
            return `<div class="dob-cal-header">
                <button type="button" class="dob-cal-nav" onclick="seminarCalShift(-1)">&#8249;</button>
                <span class="dob-cal-hdr-range">${startYear} – ${startYear+11}</span>
                <button type="button" class="dob-cal-nav" onclick="seminarCalShift(1)">&#8250;</button>
            </div>
            <div class="dob-cal-year-grid">${cells}</div>`;
        }

        window.seminarCalToggle = function() {
            const popup = document.getElementById('seminar-cal-popup');
            if (!popup) return;
            if (popup.style.display !== 'none') { popup.style.display = 'none'; return; }
            // Jump to the valid range's month when opening with no selection
            const range = getSeminarRange();
            if (range && !calSelected) { calYear = range.min.getFullYear(); calMonth = range.min.getMonth(); }
            calView = 'day'; render(); popup.style.display = 'block';
        };
        window.seminarCalShift = function(dir) {
            if (calView==='day')    { calMonth+=dir; if(calMonth>11){calMonth=0;calYear++;}if(calMonth<0){calMonth=11;calYear--;} }
            else if (calView==='month') { calYear+=dir; }
            else { calYear = Math.floor(calYear/12)*12 + dir*12; }
            render();
        };
        window.seminarCalSetView   = function(v) { calView = v; render(); };
        window.seminarCalPickMonth = function(m) { calMonth = m; calView = 'day'; render(); };
        window.seminarCalPickYear  = function(y) { calYear = y; calView = 'month'; render(); };
        window.seminarCalPick = function(el) {
            if (el.disabled || el.classList.contains('is-disabled')) return;
            let y=parseInt(el.dataset.y), m=parseInt(el.dataset.m), d=parseInt(el.dataset.d);
            const nd=new Date(y,m,d); y=nd.getFullYear(); m=nd.getMonth(); d=nd.getDate();
            const range = getSeminarRange();
            if (range) { const picked=new Date(y,m,d); if (picked<range.min||picked>range.max) return; }
            calYear=y; calMonth=m; calSelected={y,m,d};
            const raw=document.getElementById('wedding-seminar-date');
            if (raw) raw.value=`${y}-${pad(m+1)}-${pad(d)}`;
            const disp=document.getElementById('wedding-seminar-date-display');
            if (disp) disp.value=`${pad(m+1)}/${pad(d)}/${y}`;
            document.getElementById('seminar-cal-popup').style.display='none';
        };
        window.seminarCalSelectToday = function() {
            const now = new Date();
            const range = getSeminarRange();
            const todayDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            if (range && (todayDate < range.min || todayDate > range.max)) return;
            const y=now.getFullYear(), m=now.getMonth(), d=now.getDate();
            calYear=y; calMonth=m; calSelected={y,m,d};
            const raw=document.getElementById('wedding-seminar-date');
            if (raw) raw.value=`${y}-${pad(m+1)}-${pad(d)}`;
            const disp=document.getElementById('wedding-seminar-date-display');
            if (disp) disp.value=`${pad(m+1)}/${pad(d)}/${y}`;
            document.getElementById('seminar-cal-popup').style.display='none';
        };
        window.seminarCalClear = function() {
            calSelected=null;
            const raw=document.getElementById('wedding-seminar-date'); if (raw) raw.value='';
            const disp=document.getElementById('wedding-seminar-date-display'); if (disp) disp.value='';
            const popup=document.getElementById('seminar-cal-popup'); if (popup) popup.style.display='none';
        };
        window.seminarCalRefresh = function() {
            // Clear selection if it falls outside the updated range, then re-render
            if (calSelected) {
                const range = getSeminarRange();
                if (range) {
                    const sel = new Date(calSelected.y, calSelected.m, calSelected.d);
                    if (sel < range.min || sel > range.max) {
                        calSelected = null;
                        const raw = document.getElementById('wedding-seminar-date'); if (raw) raw.value = '';
                        const disp = document.getElementById('wedding-seminar-date-display'); if (disp) disp.value = '';
                    }
                }
            }
            const popup = document.getElementById('seminar-cal-popup');
            if (popup && popup.style.display !== 'none') render();
        };

        // Parse typed seminar date on blur
        document.addEventListener('DOMContentLoaded', function() {
            const inp = document.getElementById('wedding-seminar-date-display');
            if (!inp) return;
            inp.addEventListener('blur', function() {
                const v = this.value.trim(); if (!v) return;
                const m1 = v.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
                const m2 = v.match(/^(\d{4})-(\d{2})-(\d{2})$/);
                let nd = null;
                if (m1) nd = new Date(+m1[3], +m1[1]-1, +m1[2]);
                else if (m2) nd = new Date(v+'T00:00:00');
                else { const t = new Date(v); if (!isNaN(t)) nd = t; }
                if (nd && !isNaN(nd)) {
                    const range = getSeminarRange();
                    const picked = new Date(nd.getFullYear(), nd.getMonth(), nd.getDate());
                    if (range && (picked < range.min || picked > range.max)) {
                        this.value = '';
                        const raw = document.getElementById('wedding-seminar-date'); if (raw) raw.value = '';
                        calSelected = null;
                    } else {
                        const y=nd.getFullYear(), mo=nd.getMonth(), d=nd.getDate();
                        calYear=y; calMonth=mo; calSelected={y,m:mo,d};
                        const raw=document.getElementById('wedding-seminar-date');
                        if (raw) raw.value=`${y}-${pad(mo+1)}-${pad(d)}`;
                        this.value=`${pad(mo+1)}/${pad(d)}/${y}`;
                    }
                } else if (v) {
                    this.value='';
                    const raw=document.getElementById('wedding-seminar-date'); if (raw) raw.value='';
                    calSelected=null;
                }
            });
        });

        document.addEventListener('click', function(e) {
            if (!document.contains(e.target)) return;
            const wrap = document.getElementById('seminar-cal-wrap');
            if (wrap && !wrap.contains(e.target)) {
                const popup = document.getElementById('seminar-cal-popup');
                if (popup) popup.style.display = 'none';
            }
        });
    })();
    </script>

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

    <script>
    (function () {
        var sel    = document.getElementById('reservation-time');
        var wrap   = document.getElementById('reservation-time-custom');
        var btn    = document.getElementById('reservation-time-btn');
        var label  = document.getElementById('reservation-time-label');
        var list   = document.getElementById('reservation-time-list');
        if (!sel || !wrap || !btn || !list) return;

        function sync() {
            var opts = sel.options;
            var val  = sel.value;

            /* update the button label */
            var chosen = sel.options[sel.selectedIndex];
            label.textContent = chosen ? chosen.textContent : 'Select a time';

            /* rebuild list */
            list.innerHTML = '';
            for (var i = 0; i < opts.length; i++) {
                var opt = opts[i];
                var li  = document.createElement('li');
                li.textContent    = opt.textContent;
                li.dataset.value  = opt.value;
                if (opt.value === val && opt.value !== '') li.classList.add('is-selected');
                if (opt.value === '' || opt.disabled)      li.classList.add('is-placeholder');
                (function (v, disabled) {
                    li.addEventListener('click', function () {
                        if (disabled || v === '') return;
                        sel.value = v;
                        sel.dispatchEvent(new Event('change', { bubbles: true }));
                        close();
                    });
                })(opt.value, !!opt.disabled);
                list.appendChild(li);
            }

            btn.disabled = !!sel.disabled;
            if (sel.disabled) close();
        }

        function open()   { wrap.classList.add('is-open');    btn.classList.add('is-open'); }
        function close()  { wrap.classList.remove('is-open'); btn.classList.remove('is-open'); }
        function toggle() { wrap.classList.contains('is-open') ? close() : open(); }

        btn.addEventListener('click', function (e) { e.stopPropagation(); if (!btn.disabled) toggle(); });
        document.addEventListener('click', function (e) { if (!wrap.contains(e.target)) close(); });
        sel.addEventListener('change', sync);

        /* watch for options being added/removed by reservations.js */
        new MutationObserver(sync).observe(sel, { childList: true, subtree: true, attributes: true });

        sync();
    })();

    (function () {
        var sel   = document.getElementById('funeral-marital-status');
        var wrap  = document.getElementById('funeral-marital-custom');
        var btn   = document.getElementById('funeral-marital-btn');
        var label = document.getElementById('funeral-marital-label');
        var list  = document.getElementById('funeral-marital-list');
        if (!sel || !wrap || !btn || !list) return;

        function sync() {
            var val    = sel.value;
            var chosen = sel.options[sel.selectedIndex];
            label.textContent = (chosen && chosen.value !== '') ? chosen.textContent : 'Select status';
            list.innerHTML = '';
            for (var i = 0; i < sel.options.length; i++) {
                var opt = sel.options[i];
                var li  = document.createElement('li');
                li.textContent   = opt.textContent;
                li.dataset.value = opt.value;
                if (opt.value === val && opt.value !== '') li.classList.add('is-selected');
                if (opt.value === '')                      li.classList.add('is-placeholder');
                (function (v) {
                    li.addEventListener('click', function () {
                        if (v === '') return;
                        sel.value = v;
                        sel.dispatchEvent(new Event('change', { bubbles: true }));
                        close();
                    });
                })(opt.value);
                list.appendChild(li);
            }
        }

        function open()   { wrap.classList.add('is-open');    btn.classList.add('is-open'); }
        function close()  { wrap.classList.remove('is-open'); btn.classList.remove('is-open'); }
        function toggle() { wrap.classList.contains('is-open') ? close() : open(); }

        btn.addEventListener('click', function (e) { e.stopPropagation(); toggle(); });
        document.addEventListener('click', function (e) { if (!wrap.contains(e.target)) close(); });
        sel.addEventListener('change', sync);
        new MutationObserver(sync).observe(sel, { childList: true, subtree: true, attributes: true });
        sync();
    })();
    </script>
</body>
</html>
