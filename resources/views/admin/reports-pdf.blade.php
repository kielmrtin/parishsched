<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 32px; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1a2a4a;
            font-size: 11px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header-logo {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #dc2626;
        }
        .header h1 {
            font-size: 17px;
            margin: 0 0 2px;
            color: #1a2a4a;
        }
        .header .sub {
            font-size: 11px;
            color: #64748b;
            margin: 0;
        }
        .header .meta {
            text-align: right;
            font-size: 10px;
            color: #94a3b8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead th {
            background: #1a2a4a;
            color: #ffffff;
            text-align: left;
            padding: 7px 8px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .03em;
        }
        tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10.5px;
            vertical-align: top;
        }
        tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
            text-transform: capitalize;
        }
        .badge-approved { background: #d1fae5; color: #065f46; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-declined { background: #fee2e2; color: #991b1b; }
        .badge-booked   { background: #dbeafe; color: #1e40af; }
        .badge-cancelled{ background: #e5e7eb; color: #374151; }
        .empty {
            text-align: center;
            padding: 30px 0;
            color: #94a3b8;
        }
        .footer-note {
            margin-top: 14px;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            @if(file_exists($logoPath))
                <img class="header-logo" src="{{ $logoPath }}" alt="Parish Logo">
            @endif
            <div>
                <h1>St. John the Baptist Parish — Reservation Report</h1>
                <p class="sub">
                    Period: {{ $reportRangeLabel }}
                    @if($reportType !== 'all')
                        &middot; Type: {{ ['communion' => 'First Communion'][$reportType] ?? ucfirst($reportType) }}
                    @endif
                    &middot; {{ count($reservations) }} record{{ count($reservations) === 1 ? '' : 's' }}
                </p>
            </div>
        </div>
        <div class="meta">
            Generated {{ $generatedAt }}
        </div>
    </div>

    @if(count($reservations) === 0)
        <div class="empty">No reservations found for this range.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:9%">Date</th>
                    <th style="width:9%">Time</th>
                    <th style="width:10%">Type</th>
                    <th style="width:18%">Name</th>
                    <th style="width:20%">Email</th>
                    <th style="width:12%">Phone</th>
                    <th style="width:10%">Status</th>
                    <th style="width:12%">Submitted</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $r)
                    @php
                        $status = strtolower($r['status'] ?? 'pending');
                        $date = $r['reservation_date'] ?? null;
                        $created = $r['created_at'] ?? null;
                    @endphp
                    <tr>
                        <td>{{ $date ? \Carbon\Carbon::parse($date)->format('M j, Y') : '—' }}</td>
                        <td>{{ $r['reservation_time'] ?? '—' }}</td>
                        <td>{{ ucfirst($r['event_type'] ?? '—') }}</td>
                        <td>{{ $r['name'] ?? '—' }}</td>
                        <td>{{ $r['email'] ?? '—' }}</td>
                        <td>{{ $r['phone'] ?? '—' }}</td>
                        <td><span class="badge badge-{{ $status }}">{{ ucfirst($status) }}</span></td>
                        <td>{{ $created ? \Carbon\Carbon::parse($created)->format('M j, Y') : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer-note">
        ParishSched &middot; St. John the Baptist Parish, Tiaong, Quezon &middot; Confidential — contains personal information, handle accordingly.
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->getFont("Helvetica");
            $size = 8;
            $width = $fontMetrics->getTextWidth($text, $font, $size);
            $x = $pdf->get_width() - $width - 32;
            $y = $pdf->get_height() - 20;
            $pdf->page_text($x, $y, $text, $font, $size, array(0.58, 0.64, 0.72));
        }
    </script>
</body>
</html>
