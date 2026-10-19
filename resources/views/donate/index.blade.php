@extends('layouts.app')

@section('title', 'Donations | St. John the Baptist Parish')

@push('preloads')
<style>
    body { background: #f4f7fb; }

    .give-hero { background: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.5)), url("{{ asset('img/banner/myreservations.webp') }}") center/cover no-repeat; color: #fff; padding: 150px 0 56px; }
    .give-kicker { font-size: .74rem; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: rgba(255,255,255,.75); margin-bottom: .6rem; }
    .give-hero h1 { color: #fff; font-size: 2.4rem; font-weight: 800; margin: 0 0 .6rem; }
    .give-hero p { color: rgba(255,255,255,.82); max-width: 560px; margin: 0; line-height: 1.7; }

    .give-wrap { padding: 36px 0 64px; }
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
    .give-file { display: flex; align-items: center; gap: 10px; border: 1.5px dashed #cbd5e1; border-radius: 10px; padding: 10px 13px; background: #f8fafc; }
    .give-file input { font-size: .82rem; min-width: 0; }
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
        .give-hero { padding-top: 120px; }
        .give-hero h1 { font-size: 1.7rem; }
    }
    @media (max-width: 480px) {
        .give-form .row2 { grid-template-columns: minmax(0, 1fr); }
    }
</style>
@endpush

@section('content')
@php $purposes = \App\Services\DonationService::PURPOSES; @endphp

<div class="give-hero">
    <div class="container">
        <div class="give-kicker">St. John the Baptist Parish</div>
        <h1>Donations</h1>
        <p>Support the parish through GCash. Send your gift, then tell us the reference number so the parish office can confirm it.</p>
    </div>
</div>

<section class="give-wrap">
    <div class="container">
        <div class="give-grid">

            {{-- How to pay --}}
            <div class="give-card">
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
            <div class="give-card">
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
                            <select id="purpose" name="purpose" required>
                                @foreach($purposes as $pk => $pl)
                                    <option value="{{ $pk }}" @selected($defaultPurpose === $pk)>{{ $pl }}</option>
                                @endforeach
                            </select>
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
                        <div class="give-file">
                            <i class="fa fa-picture-o" style="color:#94a3b8"></i>
                            <input type="file" id="receipt" name="receipt" accept=".jpg,.jpeg,.png" required>
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
        <div class="give-card give-history">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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
