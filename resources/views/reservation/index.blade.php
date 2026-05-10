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
