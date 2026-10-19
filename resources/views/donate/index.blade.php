@extends('layouts.app')

@section('title', 'Donations | St. John the Baptist Parish')

@push('preloads')
<style>
    body { background: #f4f7fb; overflow-x: hidden; }

    /* ---- WHITE HEADER (same as My Reservations) ---- */
    body .header-area { position: relative !important; background: #ffffff !important; padding-top: 0 !important; }
    body .main-header-area { background: #ffffff !important; padding: 18px 150px !important; }
    body .main-header-area .row { min-height: 88px; align-items: center !important; }
    body .main-header-area .main-menu { padding: 0 !important; }
    body .main-menu ul li a { color: #1F1F1F !important; }
    body .main-menu ul li a::before { background: #1F1F1F !important; }
    body .socail_links ul li a { color: #1F1F1F !important; }
    body .signed-text, body .signed-text strong, body .logout-text-link { color: #1F1F1F !important; }
    body .book_room { height: 100%; align-items: center !important; }
    body .user-info { margin-right: 25px; }
    body .logo-img img { border-radius: 50px; height: 60px !important; width: 60px; object-fit: cover; }
    /* keep the bar's space when it turns sticky so the page doesn't jump */
    body .header-area { min-height: var(--give-nav-h, 0px); }

    /* ---- PAGE HERO (same as My Reservations) ---- */
    .give-hero { background: linear-gradient(rgba(0,0,0,.52), rgba(0,0,0,.48)), url("{{ asset('img/banner/myreservations.webp') }}") center/cover no-repeat; padding: 72px 0 0; color: #fff; position: relative; }
    .give-hero-body { padding-bottom: 56px; }
    .give-hero-wave { display: block; width: 100%; line-height: 0; }
    .give-hero-wave svg { display: block; width: 100%; }
    .give-kicker { display: inline-flex; align-items: center; gap: .5rem; font-size: .78rem; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: rgba(255,255,255,.75); margin-bottom: 1rem; }
    .give-kicker::before, .give-kicker::after { content: ''; display: inline-block; width: 32px; height: 1px; background: rgba(255,255,255,.5); }
    .give-hero h1 { font-size: 3rem; font-weight: 800; line-height: 1.15; margin-bottom: 1rem; color: #fff; }
    .give-hero .hero-desc { font-size: 1.05rem; color: rgba(255,255,255,.82); max-width: 520px; margin-bottom: 2rem; line-height: 1.7; }
    .give-hero-actions { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
    .give-btn-primary { display: inline-flex; align-items: center; gap: .6rem; background: #dc2626; color: #fff !important; font-weight: 400; font-size: .85rem; letter-spacing: 2.5px; text-transform: uppercase; padding: .85rem 2rem; border: none; text-decoration: none !important; transition: background .2s, transform .15s; }
    .give-btn-primary:hover { background: #b91c1c; transform: translateY(-1px); }
    .give-btn-outline { display: inline-flex; align-items: center; gap: .6rem; background: transparent; color: #fff !important; font-weight: 400; font-size: .85rem; letter-spacing: 2.5px; text-transform: uppercase; padding: .83rem 1.8rem; border: 1px solid rgba(255,255,255,.7); text-decoration: none !important; transition: border-color .2s, background .2s; }
    .give-btn-outline:hover { border-color: #fff; background: rgba(255,255,255,.08); }

    .give-wrap { padding: 48px 0 72px; }
    #donate-form { scroll-margin-top: 24px; }
    .give-grid { display: grid; grid-template-columns: minmax(0, 5fr) minmax(0, 7fr); gap: 24px; align-items: start; }
    .give-card { background: #fff; border-radius: 16px; border: 1px solid #e5e7eb; padding: 24px; }
    .give-card h2 { font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0 0 4px; }
    .give-card .give-sub { font-size: .84rem; color: #64748b; margin: 0 0 18px; }

    .gcash-box { border: 1.5px solid #bfdbfe; background: #eff6ff; border-radius: 12px; padding: 16px; margin-bottom: 18px; }
    .gcash-label { font-size: .68rem; font-weight: 800; color: #1d4ed8; letter-spacing: .08em; text-transform: uppercase; }
    .gcash-number { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; font-size: 1.35rem; font-weight: 800; color: #0f172a; font-variant-numeric: tabular-nums; margin: 4px 0 2px; }
    .gcash-name { font-size: .85rem; color: #475569; }
    .gcash-copy { border: 1.5px solid #93c5fd; background: #fff; color: #1d4ed8; border-radius: 8px; padding: 5px 12px; font-size: .75rem; font-weight: 700; cursor: pointer; }

    .give-steps { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 10px; counter-reset: s; }
    .give-steps li { counter-increment: s; display: grid; grid-template-columns: 26px 1fr; gap: 10px; font-size: .86rem; color: #334155; line-height: 1.5; }
    .give-steps li::before { content: counter(s); width: 24px; height: 24px; border-radius: 50%; background: #fee2e2; color: #b91c1c; font-weight: 800; font-size: .75rem; display: grid; place-items: center; }

    .give-for { border: 1px solid #fde68a; background: #fffbeb; border-radius: 12px; padding: 12px 14px; margin-bottom: 16px; font-size: .85rem; color: #78350f; }
    .give-for strong { color: #0f172a; }

    .give-form .fg { margin-bottom: 14px; }
    .give-form label { display: block; font-size: .7rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: #475569; margin-bottom: 6px; }
    .give-form input[type=text], .give-form input[type=number], .give-form select, .give-form textarea {
        display: block; width: 100%; padding: 10px 13px; border: 1.5px solid #e2e8f0; border-radius: 10px; background: #f8fafc;
        font-size: .9rem; color: #1a2a4a; outline: none; box-sizing: border-box; min-height: 42px; font-family: inherit;
    }
    .give-form input:focus, .give-form select:focus, .give-form textarea:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220,38,38,.12); background: #fff; }
    .give-form .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .give-form .hint { font-size: .74rem; color: #94a3b8; margin-top: 4px; }
    /* File picker and dropdown: same as the reservation form */
    .res-file { position: relative; }
    .res-file-input { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 1; }
    .give-form .res-file-btn { display: flex; align-items: center; gap: 9px; padding: 11px 14px; border: 1.5px dashed #cbd5e1; border-radius: 10px; background: #fff; color: #64748b; font-size: .84rem; font-weight: 500; cursor: pointer; margin: 0; min-height: 42px; transition: border-color .15s, background .15s, color .15s; text-transform: none; letter-spacing: 0; }
    .res-file-btn svg { flex-shrink: 0; color: #dc2626; transition: color .15s; }
    .res-file-btn .res-file-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .res-file-input:hover ~ .res-file-btn, .res-file-btn:hover { border-color: #dc2626; background: #fff1f2; color: #7f1d1d; }
    .res-file-input:focus-visible ~ .res-file-btn { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220,38,38,.1); }
    .res-file.has-file .res-file-btn { border-style: solid; border-color: #bbf7d0; background: #f0fdf4; color: #15803d; }
    .res-file.has-file .res-file-btn svg { color: #15803d; }

    .res-csl { position: relative; }
    .res-csl-btn { width: 100%; display: flex; align-items: center; justify-content: space-between; min-height: 42px; padding: 10px 13px; border: 1.5px solid #e2e8f0; border-radius: 10px; background: #f8fafc; font-size: .9rem; color: #1a2a4a; cursor: pointer; text-align: left; font-family: inherit; transition: border-color .15s, box-shadow .15s, background .15s; }
    .res-csl-btn:focus, .res-csl-btn.is-open { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220,38,38,.12); background: #fff; outline: none; }
    .res-csl-arrow { font-size: .7rem; color: #94a3b8; transition: transform .2s; flex-shrink: 0; }
    .res-csl-btn.is-open .res-csl-arrow { transform: rotate(180deg); }
    .res-csl-list { display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: #fff; border: 1.5px solid #e2e8f0; border-radius: 10px; box-shadow: 0 8px 24px rgba(15,23,42,.12); z-index: 200; max-height: 220px; overflow-y: auto; list-style: none; padding: 4px 0; margin: 0; }
    .res-csl.is-open .res-csl-list { display: block; }
    .res-csl-list li { padding: 10px 14px; font-size: .88rem; color: #0f172a; cursor: pointer; transition: background .1s, color .1s; }
    .res-csl-list li:hover { background: rgba(220,38,38,.07); color: #dc2626; }
    .res-csl-list li.is-selected { background: #dc2626; color: #fff; font-weight: 600; }
    .give-submit { width: 100%; padding: 12px; border: 0; border-radius: 10px; background: #dc2626; color: #fff; font-weight: 700; font-size: .92rem; cursor: pointer; }
    .give-submit:hover { background: #b91c1c; }
    .give-errors { border: 1px solid #fecaca; background: #fef2f2; color: #b91c1c; border-radius: 10px; padding: 10px 14px; font-size: .84rem; margin-bottom: 16px; }
    .give-errors ul { margin: 0; padding-left: 18px; }

    .give-history { margin-top: 24px; }
    .give-table-wrap { overflow-x: auto; }
    .give-table { width: 100%; border-collapse: collapse; font-size: .84rem; font-variant-numeric: tabular-nums; }
    .give-table th { text-align: left; font-size: .68rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; color: #94a3b8; padding: 8px 10px; border-bottom: 2px solid #f1f5f9; white-space: nowrap; }
    .give-table td { padding: 9px 10px; border-bottom: 1px solid #f1f5f9; color: #0f172a; white-space: nowrap; }
    .give-chip { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: .72rem; font-weight: 700; border: 1px solid; }
    .give-chip.pending { color: #b45309; background: #fffbeb; border-color: #fde68a; }
    .give-chip.verified, .give-chip.received, .give-chip.cleared { color: #15803d; background: #f0fdf4; border-color: #bbf7d0; }
    .give-chip.rejected { color: #b91c1c; background: #fef2f2; border-color: #fecaca; }

    @media (max-width: 860px) {
        .give-grid { grid-template-columns: minmax(0, 1fr); }
        .give-hero h1 { font-size: 2.2rem; }
    }
    @media (max-width: 576px) {
        .give-hero h1 { font-size: 1.6rem; }
    }
    @media (max-width: 480px) {
        .give-form .row2 { grid-template-columns: minmax(0, 1fr); }
    }
</style>
@endpush

@section('content')
@php $purposes = \App\Services\DonationService::PURPOSES; @endphp

<div class="give-hero">
    <div class="container give-hero-body">
        <div class="give-kicker ps-reveal" data-reveal-delay="0"><span>St. John the Baptist Parish</span></div>
        <h1 class="ps-reveal" data-reveal-delay="90">Donations</h1>
        <p class="hero-desc ps-reveal" data-reveal-delay="180">
            Support the parish through GCash. Send your gift, then tell us the reference number so the parish office can confirm it.
        </p>
        <div class="give-hero-actions ps-reveal" data-reveal-delay="270">
            <a href="#donate-form" class="give-btn-primary"><i class="fa fa-heart"></i> Donate now</a>
            <a href="{{ route('reservation.my') }}" class="give-btn-outline"><i class="fa fa-calendar-check-o"></i> My Reservations</a>
            <a href="{{ url('/contact') }}" class="give-btn-outline"><i class="fa fa-envelope-o"></i> Contact Parish</a>
        </div>
    </div>
    <div class="give-hero-wave">
        <svg viewBox="0 0 1440 56" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,56 L0,28 Q360,0 720,28 Q1080,56 1440,28 L1440,56 Z" fill="#f4f7fb"/>
        </svg>
    </div>
</div>

<section class="give-wrap">
    <div class="container">
        <div class="give-grid">

            {{-- How to pay --}}
            <div class="give-card ps-reveal">
                <h2>Send through GCash</h2>
                <p class="give-sub">Use the GCash app to send money to the parish account below.</p>

                <div class="gcash-box">
                    <div class="gcash-label">Parish GCash</div>
                    <div class="gcash-number">
                        <span id="gcashNumber">{{ trim(chunk_split($gcashNumber, 4, ' ')) }}</span>
                        <button type="button" class="gcash-copy" id="gcashCopy" data-number="{{ $gcashNumber }}">Copy number</button>
                    </div>
                    <div class="gcash-name">Account name: <strong>{{ $gcashName }}</strong></div>
                </div>

                <ol class="give-steps">
                    <li><span>Open GCash, choose <strong>Send Money</strong>, and enter the number above.</span></li>
                    <li><span>Check that the account name matches, then send the amount.</span></li>
                    <li><span>Take a screenshot of the receipt that shows the reference number.</span></li>
                    <li><span>Fill in the form with the reference number and screenshot. The parish office will verify it and mark it as received.</span></li>
                </ol>
            </div>

            {{-- Submit payment --}}
            <div class="give-card ps-reveal" data-reveal-delay="100" id="donate-form">
                <h2>Tell us about your payment</h2>
                <p class="give-sub">All fields marked * are required.</p>

                @if($errors->any())
                    <div class="give-errors" role="alert">
                        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                @if($reservation)
                    <div class="give-for">
                        Paying for your approved <strong>{{ ucfirst(strtolower($reservation['event_type'])) }}</strong> reservation
                        #{{ $reservation['id'] }} on <strong>{{ \Carbon\Carbon::parse($reservation['reservation_date'])->format('M j, Y') }}</strong>.
                    </div>
                @endif

                <form class="give-form" method="POST" action="{{ route('donate.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if($reservation)
                        <input type="hidden" name="reservation_id" value="{{ $reservation['id'] }}">
                    @endif

                    <div class="row2">
                        <div class="fg">
                            <label for="amount">Amount (₱) *</label>
                            <input type="number" id="amount" name="amount" step="0.01" min="1" required
                                   value="{{ old('amount', $suggestedAmount) }}" placeholder="0.00">
                            @if($suggestedAmount)<div class="hint">Suggested church donation: ₱{{ number_format($suggestedAmount) }}</div>@endif
                        </div>
                        <div class="fg">
                            <label for="purpose">Purpose *</label>
                            @php $defaultPurpose = old('purpose', $reservation ? 'other' : 'building_fund'); @endphp
                            {{-- Native select stays for submitting; the styled list drives it --}}
                            <select id="purpose" name="purpose" required style="display:none" tabindex="-1" aria-hidden="true">
                                @foreach($purposes as $pk => $pl)
                                    <option value="{{ $pk }}" @selected($defaultPurpose === $pk)>{{ $pl }}</option>
                                @endforeach
                            </select>
                            <div class="res-csl" id="purposeCsl">
                                <button type="button" class="res-csl-btn" id="purposeBtn" aria-haspopup="listbox" aria-expanded="false">
                                    <span class="res-csl-label">{{ $purposes[$defaultPurpose] ?? 'Building Fund' }}</span>
                                    <i class="fa fa-chevron-down res-csl-arrow" aria-hidden="true"></i>
                                </button>
                                <ul class="res-csl-list" role="listbox" aria-labelledby="purposeBtn">
                                    @foreach($purposes as $pk => $pl)
                                        <li role="option" data-value="{{ $pk }}" class="{{ $defaultPurpose === $pk ? 'is-selected' : '' }}" aria-selected="{{ $defaultPurpose === $pk ? 'true' : 'false' }}">{{ $pl }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="fg" id="customPurposeGroup" style="{{ $defaultPurpose === 'other' ? '' : 'display:none' }}">
                        <label for="custom_purpose">What is it for? *</label>
                        <input type="text" id="custom_purpose" name="custom_purpose" maxlength="255"
                               value="{{ old('custom_purpose', $reservation ? ucfirst(strtolower($reservation['event_type'])) . ' reservation #' . $reservation['id'] : '') }}"
                               placeholder="e.g. Parish fiesta">
                    </div>

                    <div class="fg">
                        <label for="gcash_reference">GCash reference no. *</label>
                        <input type="text" id="gcash_reference" name="gcash_reference" inputmode="numeric" maxlength="30" required
                               value="{{ old('gcash_reference') }}" placeholder="e.g. 4012 345 678901">
                        <div class="hint">Found at the bottom of your GCash receipt.</div>
                    </div>

                    <div class="fg">
                        <label for="receipt">Receipt screenshot *</label>
                        <div class="res-file">
                            <input type="file" class="res-file-input" id="receipt" name="receipt" accept=".jpg,.jpeg,.png" required>
                            <label for="receipt" class="res-file-btn">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                <span class="res-file-name">Choose file</span>
                            </label>
                        </div>
                        <div class="hint">JPG or PNG, up to 5 MB.</div>
                    </div>

                    <div class="fg">
                        <label for="notes">Notes <span style="text-transform:none;letter-spacing:0;color:#94a3b8">(optional)</span></label>
                        <textarea id="notes" name="notes" rows="2" maxlength="500" placeholder="e.g. In memory of Juan dela Cruz">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="give-submit">Submit payment</button>
                </form>
            </div>
        </div>

        @if(count($history))
        <div class="give-card give-history ps-reveal">
            <h2>Your online donations</h2>
            <p class="give-sub">Pending payments are being checked by the parish office.</p>
            <div class="give-table-wrap">
                <table class="give-table">
                    <thead><tr><th>Receipt</th><th>Date</th><th>Purpose</th><th>Amount</th><th>Reference</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($history as $h)
                        @php $hs = $h['payment_status'] ?? 'pending'; @endphp
                        <tr>
                            <td>{{ $h['receipt_number'] ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($h['donation_date'])->format('M j, Y') }}</td>
                            <td>{{ ($h['purpose'] ?? '') === 'other' && !empty($h['custom_purpose']) ? $h['custom_purpose'] : ($purposes[$h['purpose'] ?? ''] ?? '—') }}</td>
                            <td>₱{{ number_format((float) ($h['amount'] ?? 0), 2) }}</td>
                            <td style="font-family:monospace">{{ $h['gcash_reference'] ?? '—' }}</td>
                            <td><span class="give-chip {{ $hs }}">{{ \App\Services\DonationService::STATUSES[$hs] ?? ucfirst($hs) }}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</section>
@include('partials.reveal')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Reserve the nav bar's height so the page doesn't jump when it turns
    // sticky; then "Donate now" is a single scroll to just below the bar.
    const nav = document.getElementById('sticky-header');
    const setNavSpace = function () {
        if (nav && !nav.classList.contains('sticky')) document.documentElement.style.setProperty('--give-nav-h', nav.offsetHeight + 'px');
    };
    setNavSpace();
    window.addEventListener('resize', setNavSpace);
    document.querySelector('.give-btn-primary')?.addEventListener('click', function (e) {
        const form = document.getElementById('donate-form');
        if (!form) return;
        e.preventDefault();
        const navH = nav ? nav.offsetHeight : 0;
        window.scrollTo({ top: form.getBoundingClientRect().top + window.scrollY - navH - 20, behavior: 'smooth' });
    });

    // Receipt picker: show the chosen file name (same as the reservation form).
    const receipt = document.getElementById('receipt');
    receipt?.addEventListener('change', function () {
        const wrap = receipt.closest('.res-file');
        const file = receipt.files && receipt.files[0];
        wrap.classList.toggle('has-file', !!file);
        wrap.querySelector('.res-file-name').textContent = file ? file.name : 'Choose file';
    });

    // Purpose dropdown: styled list that drives the hidden select.
    const csl = document.getElementById('purposeCsl');
    const cslBtn = document.getElementById('purposeBtn');
    const cslSelect = document.getElementById('purpose');
    const setCslOpen = function (open) {
        csl.classList.toggle('is-open', open);
        cslBtn.classList.toggle('is-open', open);
        cslBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    };
    cslBtn?.addEventListener('click', function (e) { e.stopPropagation(); setCslOpen(!csl.classList.contains('is-open')); });
    csl?.querySelectorAll('li').forEach(function (li) {
        li.addEventListener('click', function () {
            cslSelect.value = li.dataset.value;
            cslSelect.dispatchEvent(new Event('change', { bubbles: true }));
            csl.querySelectorAll('li').forEach(function (x) {
                x.classList.toggle('is-selected', x === li);
                x.setAttribute('aria-selected', x === li ? 'true' : 'false');
            });
            cslBtn.querySelector('.res-csl-label').textContent = li.textContent;
            setCslOpen(false);
            cslBtn.focus();
        });
    });
    document.addEventListener('click', function (e) { if (csl && !csl.contains(e.target)) setCslOpen(false); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && csl?.classList.contains('is-open')) { setCslOpen(false); cslBtn.focus(); } });

    const purpose = document.getElementById('purpose');
    const group = document.getElementById('customPurposeGroup');
    purpose.addEventListener('change', function () {
        group.style.display = purpose.value === 'other' ? '' : 'none';
    });

    const copy = document.getElementById('gcashCopy');
    copy.addEventListener('click', function () {
        const done = () => { copy.textContent = 'Copied'; setTimeout(() => copy.textContent = 'Copy number', 1600); };
        if (navigator.clipboard) {
            navigator.clipboard.writeText(copy.dataset.number).then(done).catch(function () {
                window.getSelection().selectAllChildren(document.getElementById('gcashNumber'));
            });
        } else {
            window.getSelection().selectAllChildren(document.getElementById('gcashNumber'));
        }
    });
});
</script>
@endpush
