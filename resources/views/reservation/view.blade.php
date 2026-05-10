@php
    function rv_escape($value): string {
        return e((string) $value);
    }

    function rv_format_date(?string $date): string {
        if (!$date || trim($date) === '') return '—';

        try {
            return (new DateTime($date))->format('l, F j, Y');
        } catch (Exception $e) {
            return e(trim($date));
        }
    }

    function rv_format_time(?string $time): string {
        if (!$time || trim($time) === '') return '—';

        $trimmed = trim($time);

        if (strpos($trimmed, '-') !== false) {
            $parts = preg_split('/\s*-\s*/', $trimmed);

            if (is_array($parts) && count($parts) >= 2) {
                $formatPart = function ($value) {
                    $segment = trim($value);
                    if ($segment === '') return '';

                    $timestamp = strtotime($segment);
                    if ($timestamp !== false) {
                        return date('g:i A', $timestamp);
                    }

                    return e($segment);
                };

                $start = $formatPart($parts[0]);
                $end = $formatPart($parts[1]);

                if ($start !== '' && $end !== '') {
                    return $start . ' – ' . $end;
                }
            }
        }

        $timestamp = strtotime($trimmed);
        return $timestamp !== false ? date('g:i A', $timestamp) : e($trimmed);
    }

    function rv_format_created(?string $createdAt): string {
        if (!$createdAt || trim($createdAt) === '') return '—';

        $timestamp = strtotime($createdAt);
        return $timestamp !== false ? date('F j, Y • g:i A', $timestamp) : e(trim($createdAt));
    }

    function rv_text($value, string $fallback = '—'): string {
        if ($value === null || trim((string) $value) === '') return $fallback;
        return e(trim((string) $value));
    }

    $statusStyles = [
        'approved' => [
            'label' => 'Approved',
            'accent' => '#0f766e',
            'accent_soft' => 'rgba(15, 118, 110, 0.12)',
            'badge_bg' => 'rgba(15, 118, 110, 0.15)',
            'badge_text' => '#115e59',
            'badge_border' => 'rgba(15, 118, 110, 0.35)',
        ],
        'declined' => [
            'label' => 'Declined',
            'accent' => '#dc2626',
            'accent_soft' => 'rgba(220, 38, 38, 0.1)',
            'badge_bg' => 'rgba(220, 38, 38, 0.14)',
            'badge_text' => '#991b1b',
            'badge_border' => 'rgba(220, 38, 38, 0.32)',
        ],
        'pending' => [
            'label' => 'Pending',
            'accent' => '#4f46e5',
            'accent_soft' => 'rgba(79, 70, 229, 0.12)',
            'badge_bg' => 'rgba(79, 70, 229, 0.14)',
            'badge_text' => '#3730a3',
            'badge_border' => 'rgba(79, 70, 229, 0.32)',
        ],
    ];

    $statusNormalized = 'pending';

    if ($reservation !== null) {
        $candidate = strtolower(trim((string) ($reservation['status'] ?? 'pending')));
        if (isset($statusStyles[$candidate])) {
            $statusNormalized = $candidate;
        }
    }

    $statusStyle = $statusStyles[$statusNormalized];
    $statusLabel = $statusStyle['label'];

    $documentTitle = 'Reservation Details';

    if ($reservation !== null) {
        $reservationIdDisplay = isset($reservation['id']) && (int) $reservation['id'] > 0
            ? '#' . (int) $reservation['id']
            : 'Unassigned reservation';

        $documentTitle = 'Reservation ' . $reservationIdDisplay;
    }

    $attachments = [];

    if ($reservation !== null && !empty($reservation['attachments'])) {
        foreach ($reservation['attachments'] as $attachment) {
            $path = trim((string) ($attachment['stored_path'] ?? $attachment['file_url'] ?? ''));
            $fileName = trim((string) ($attachment['file_name'] ?? basename($path)));
            $label = trim((string) ($attachment['label'] ?? ''));

            if ($path !== '') {
                $attachments[] = [
                    'path' => $path,
                    'file_name' => $fileName ?: 'attachment',
                    'label' => ($label !== '' && strcasecmp($label, $fileName) !== 0) ? $label : '',
                ];
            }
        }
    }

    $attachmentCount = count($attachments);
    $notes = $reservation !== null ? trim((string) ($reservation['notes'] ?? '')) : '';
    $hasNotes = $notes !== '';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $documentTitle }} · Admin Dashboard</title>

    <style>
        :root {
            color-scheme: light;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background: radial-gradient(120% 120% at 50% 0%, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.98) 30%, #020617 100%);
            color: #0f172a;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 48px 16px 64px;
        }

        .page-shell {
            width: min(940px, 100%);
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            box-shadow: 0 50px 120px rgba(15, 23, 42, 0.35);
            overflow: hidden;
            position: relative;
        }

        .page-shell::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(180% 130% at 80% -40%, rgba(255, 255, 255, 0.65) 0%, transparent 55%),
                radial-gradient(120% 120% at 10% 0%, rgba(226, 232, 240, 0.8) 0%, transparent 60%);
        }

        .page-content {
            position: relative;
            padding: 40px;
            backdrop-filter: blur(6px);
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 32px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 32px;
            color: #0f172a;
            letter-spacing: -0.03em;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.04em;
            background: var(--badge-bg);
            color: var(--badge-text);
            border: 1px solid var(--badge-border);
            text-transform: uppercase;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--badge-text);
            box-shadow: 0 0 0 4px rgba(148, 163, 184, 0.18);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            letter-spacing: 0.02em;
            margin-bottom: 24px;
        }

        .back-link span {
            border-bottom: 1px solid transparent;
            padding-bottom: 2px;
            transition: border-color 0.2s ease;
        }

        .back-link:hover span,
        .back-link:focus span {
            border-color: currentColor;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .info-card {
            background: #ffffff;
            border-radius: 22px;
            padding: 22px;
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 22px 45px rgba(15, 23, 42, 0.12);
        }

        .info-card h2 {
            margin: 0 0 16px 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #475569;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.1em;
            color: #94a3b8;
            text-transform: uppercase;
        }

        .info-value {
            font-size: 16px;
            color: #0f172a;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .accent {
            color: var(--accent-color);
        }

        .notes-card,
        .attachments-card {
            background: #ffffff;
            border-radius: 26px;
            padding: 30px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: 0 25px 60px rgba(15, 23, 42, 0.1);
            margin-bottom: 24px;
        }

        .section-heading {
            margin: 0 0 16px 0;
            font-size: 20px;
            color: #0f172a;
            letter-spacing: -0.01em;
        }

        .notes-content {
            background: var(--accent-soft);
            border-radius: 20px;
            padding: 24px;
            line-height: 1.7;
            font-size: 15px;
            color: #1e293b;
            white-space: pre-line;
        }

        .empty-state {
            margin: 0;
            padding: 20px;
            border-radius: 18px;
            background: rgba(226, 232, 240, 0.35);
            color: #64748b;
            font-size: 15px;
        }

        .attachments-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 14px;
        }

        .attachments-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-radius: 18px;
            background: rgba(248, 250, 252, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.25);
        }

        .attachment-label {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .attachment-label span:first-child {
            font-weight: 600;
            color: #0f172a;
        }

        .attachment-label span:last-child {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
        }

        .attachment-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: var(--accent-color);
            border-radius: 999px;
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
            letter-spacing: 0.02em;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .attachment-link:hover,
        .attachment-link:focus {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.18);
        }

        .error-card {
            text-align: center;
            padding: 48px 32px;
            background: rgba(248, 113, 113, 0.08);
            border-radius: 28px;
            border: 1px solid rgba(248, 113, 113, 0.45);
            color: #991b1b;
        }

        .error-card h2 {
            margin-top: 0;
            font-size: 26px;
        }

        .error-card p {
            font-size: 16px;
            line-height: 1.6;
            color: rgba(69, 10, 10, 0.85);
        }

        @media (max-width: 720px) {
            body {
                padding: 24px 12px 48px;
            }

            .page-content {
                padding: 32px 24px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .page-header h1 {
                font-size: 26px;
            }

            .notes-card,
            .attachments-card {
                padding: 24px;
            }
        }
    </style>
</head>

<body style="--accent-color:{{ $statusStyle['accent'] }}; --accent-soft:{{ $statusStyle['accent_soft'] }}; --badge-bg:{{ $statusStyle['badge_bg'] }}; --badge-text:{{ $statusStyle['badge_text'] }}; --badge-border:{{ $statusStyle['badge_border'] }};">
    <div class="page-shell">
        <div class="page-content">
            <a class="back-link" href="{{ route('admin.index', ['section' => 'reservations']) }}">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M9.5 3.5L5 8L9.5 12.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>Back to reservations</span>
            </a>

            @if($reservation !== null)
                <div class="page-header">
                    <h1>{{ $documentTitle }}</h1>
                    <span class="status-badge">
                        <span class="status-indicator" aria-hidden="true"></span>
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="info-grid">
                    <div class="info-card">
                        <h2>Guest details</h2>
                        <div class="info-item">
                            <span class="info-label">Guest name</span>
                            <span class="info-value">{!! rv_text($reservation['name'] ?? '', '—') !!}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email address</span>
                            <span class="info-value accent">{!! rv_text($reservation['email'] ?? '', 'Not provided') !!}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone number</span>
                            <span class="info-value">{!! rv_text($reservation['phone'] ?? '', 'Not provided') !!}</span>
                        </div>
                    </div>

                    <div class="info-card">
                        <h2>Event summary</h2>
                        <div class="info-item">
                            <span class="info-label">Event type</span>
                            <span class="info-value">{!! rv_text($reservation['event_type'] ?? '', 'Not specified') !!}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Preferred date</span>
                            <span class="info-value">{!! rv_format_date($reservation['preferred_date'] ?? $reservation['reservation_date'] ?? null) !!}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Preferred time</span>
                            <span class="info-value">{!! rv_format_time($reservation['preferred_time'] ?? $reservation['reservation_time'] ?? null) !!}</span>
                        </div>
                    </div>

                    <div class="info-card">
                        <h2>Submission</h2>
                        <div class="info-item">
                            <span class="info-label">Reservation ID</span>
                            <span class="info-value">{{ isset($reservation['id']) ? '#' . $reservation['id'] : '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Submitted on</span>
                            <span class="info-value">{!! rv_format_created($reservation['created_at'] ?? null) !!}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value accent">{{ $statusLabel }}</span>
                        </div>
                    </div>
                </div>

                <div class="notes-card">
                    <h2 class="section-heading">Notes &amp; special requests</h2>

                    @if($hasNotes)
                        <div class="notes-content">{!! nl2br(e($notes)) !!}</div>
                    @else
                        <p class="empty-state">No special instructions were included with this reservation.</p>
                    @endif
                </div>

                <div class="attachments-card">
                    <h2 class="section-heading">Attachments</h2>

                    @if($attachmentCount > 0)
                        <ul class="attachments-list">
                            @foreach($attachments as $attachment)
                                @php
                                    $displayLabel = $attachment['label'] !== ''
                                        ? $attachment['label']
                                        : 'Download attachment';

                                    if ($attachment['label'] === '' && $attachmentCount > 1) {
                                        $displayLabel .= ' #' . $loop->iteration;
                                    }
                                @endphp

                                <li>
                                    <div class="attachment-label">
                                        <span>{{ $displayLabel }}</span>
                                        <span>{{ $attachment['file_name'] }}</span>
                                    </div>

                                    <a class="attachment-link" href="{{ asset($attachment['path']) }}" download="{{ $attachment['file_name'] }}">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M9 2.25V11.25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M5.25 7.5L9 11.25L12.75 7.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M3 12.75V13.5C3 14.7426 4.00736 15.75 5.25 15.75H12.75C13.9926 15.75 15 14.7426 15 13.5V12.75" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                                        </svg>
                                        <span>Download</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="empty-state">No files were attached to this reservation.</p>
                    @endif
                </div>
            @else
                <div class="error-card">
                    <h2>{{ $errorTitle ?? 'Unable to display reservation' }}</h2>
                    <p>{{ $errorMessage ?? 'Please return to the reservations dashboard and try again.' }}</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>