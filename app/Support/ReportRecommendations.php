<?php

namespace App\Support;

/**
 * "Recommended actions" on Admin → Reports (panel revision: state at least
 * one decision that requires data reports). Each rule is a plain threshold
 * on numbers the reports already show, so every recommendation can be
 * traced back to the chart or table it came from.
 */
class ReportRecommendations
{
    private const BAPTISM_CAPACITY = 5;

    /**
     * @param array $monthlyData   Reports "Data table": month label => counts per type/status
     * @param array $dayData       Reports "By day of week": day => counts
     * @param array $totals        Reports totals per type/status
     * @param array $reservations  Reservations in the selected range
     * @param array $allReservations Every reservation (for the pending-review rule)
     * @param array $attendanceAvg Average attendance per event type
     * @param array $workload      Officiant workload rows
     * @return array<int, array{tone: string, title: string, detail: string, source: string}>
     */
    public static function build(array $monthlyData, array $dayData, array $totals, array $reservations, array $allReservations, array $attendanceAvg, array $workload): array
    {
        $recs = [];
        $active = array_filter($reservations, fn ($r) => in_array(strtolower($r['status'] ?? ''), ['approved', 'pending', 'booked'], true));

        // 1. Pending requests close to their date: decide what to review first.
        $today = now()->startOfDay();
        $pendingAll = array_filter($allReservations, fn ($r) => strtolower($r['status'] ?? '') === 'pending');
        $soon = array_filter($pendingAll, function ($r) use ($today) {
            if (empty($r['reservation_date'])) return false;
            $days = $today->diffInDays(\Carbon\Carbon::parse($r['reservation_date'])->startOfDay(), false);
            return $days >= 0 && $days <= 7;
        });
        if (count($soon) > 0) {
            $recs[] = [
                'tone'   => 'urgent',
                'title'  => 'Review ' . count($soon) . ' pending ' . self::plural(count($soon), 'request') . ' first',
                'detail' => count($soon) . ' of ' . count($pendingAll) . ' pending ' . self::plural(count($pendingAll), 'request') . ' fall within the next 7 days. Approving or declining these first gives families time to prepare.',
                'source' => 'Rule: pending and event date ≤ 7 days away · Reservation status',
            ];
        }

        // 2. Baptism capacity: weekend dates that are (nearly) full.
        $baptismsByDate = [];
        foreach ($active as $r) {
            if (strtolower($r['event_type'] ?? '') === 'baptism' && !empty($r['reservation_date'])) {
                $baptismsByDate[$r['reservation_date']] = ($baptismsByDate[$r['reservation_date']] ?? 0) + 1;
            }
        }
        $fullDates = array_filter($baptismsByDate, fn ($n) => $n >= self::BAPTISM_CAPACITY - 1);
        if (count($fullDates) > 0) {
            $recs[] = [
                'tone'   => 'capacity',
                'title'  => 'Consider a second baptism batch on busy weekends',
                'detail' => count($fullDates) . ' baptism ' . self::plural(count($fullDates), 'date') . ' reached ' . (self::BAPTISM_CAPACITY - 1) . ' or more of the ' . self::BAPTISM_CAPACITY . ' places'
                    . ' (busiest: ' . \Carbon\Carbon::parse(array_search(max($fullDates), $fullDates))->format('M j') . ' with ' . max($fullDates) . '). Families after that are turned away.',
                'source' => 'Rule: baptisms on one date ≥ 80% of 5 places · Reservations by date',
            ];
        }

        // 3. Peak month per sacrament: plan priests and slots ahead of it.
        $months = max(count($monthlyData), 1);
        $best = null;
        foreach (['baptism' => 'Baptisms', 'wedding' => 'Weddings', 'funeral' => 'Funerals', 'kumpil' => 'Kumpil', 'communion' => 'First Communions'] as $type => $label) {
            $values = array_column($monthlyData, $type);
            if (!$values) continue;
            $peak = max($values);
            $avg  = array_sum($values) / $months;
            if ($peak >= 3 && $avg > 0 && $peak >= 1.5 * $avg) {
                $ratio = $peak / $avg;
                if (!$best || $ratio > $best['ratio']) {
                    $best = ['ratio' => $ratio, 'label' => $label, 'peak' => $peak, 'avg' => $avg, 'month' => array_keys($monthlyData)[array_search($peak, $values)]];
                }
            }
        }
        if ($best) {
            $recs[] = [
                'tone'   => 'plan',
                'title'  => 'Plan extra officiants for ' . strtolower($best['label']) . ' around ' . $best['month'],
                'detail' => "{$best['label']} reached {$best['peak']} in {$best['month']}, " . round($best['ratio'], 1) . '× the average of ' . round($best['avg'], 1) . ' a month. Expect the same season next year.',
                'source' => 'Rule: peak month ≥ 1.5× monthly average · Monthly trend',
            ];
        }

        // 4. Busiest day of the week: staff the office accordingly.
        $dayTotal = array_sum(array_column($dayData, 'total'));
        if ($dayTotal >= 5) {
            $busiest = array_search(max(array_column($dayData, 'total')), array_column($dayData, 'total'));
            $busiestDay = array_keys($dayData)[$busiest];
            $count = $dayData[$busiestDay]['total'];
            $share = $count / $dayTotal;
            if ($share >= 0.3) {
                $recs[] = [
                    'tone'   => 'staff',
                    'title'  => 'Schedule more parish staff on ' . self::dayName($busiestDay) . 's',
                    'detail' => round($share * 100) . "% of sacraments ({$count} of {$dayTotal}) fall on " . self::dayName($busiestDay) . '. More ushers and office staff on that day shortens waiting time.',
                    'source' => 'Rule: one weekday holds ≥ 30% of sacraments · Day-of-week chart',
                ];
            }
        }

        // 5. Officiant workload imbalance.
        $activePriests = array_values(array_filter($workload, fn ($w) => $w['active'] ?? true));
        if (count($activePriests) >= 2) {
            $counts = array_column($activePriests, 'count');
            $max = max($counts);
            $min = min($counts);
            if ($max >= 4 && $max >= 2 * max($min, 1)) {
                $top = $activePriests[array_search($max, $counts)]['name'];
                $low = $activePriests[array_search($min, $counts)]['name'];
                $recs[] = [
                    'tone'   => 'plan',
                    'title'  => 'Spread officiant assignments more evenly',
                    'detail' => "{$top} has {$max} assigned sacraments while {$low} has {$min}. Assigning new requests to {$low} balances the load.",
                    'source' => 'Rule: busiest priest ≥ 2× least busy · Officiant workload',
                ];
            }
        }

        // 6. Attendance: prepare the church for the largest gatherings.
        $attendanceAvg = array_filter($attendanceAvg);
        if ($attendanceAvg) {
            $topType = array_search(max($attendanceAvg), $attendanceAvg);
            $recs[] = [
                'tone'   => 'staff',
                'title'  => 'Prepare extra seating for ' . strtolower($topType) . 's',
                'detail' => "{$topType}s draw the most people, about " . max($attendanceAvg) . ' on average. Arrange extra chairs, ushers and parking for these events.',
                'source' => 'Rule: highest average attendance · Event attendance log',
            ];
        }

        // 7. High decline rate: clarify the requirements.
        $all = ($totals['approved'] ?? 0) + ($totals['pending'] ?? 0) + ($totals['declined'] ?? 0);
        if ($all >= 8 && ($totals['declined'] ?? 0) / $all >= 0.25) {
            $recs[] = [
                'tone'   => 'urgent',
                'title'  => 'Look into why requests are declined',
                'detail' => round(($totals['declined'] / $all) * 100) . "% of requests ({$totals['declined']} of {$all}) were declined. Clearer requirements on the reservation form can lower this.",
                'source' => 'Rule: declined ≥ 25% of requests · Status breakdown',
            ];
        }

        return $recs;
    }

    private static function plural(int $n, string $word): string
    {
        return $n === 1 ? $word : $word . 's';
    }

    private static function dayName(string $short): string
    {
        return ['Sun' => 'Sunday', 'Mon' => 'Monday', 'Tue' => 'Tuesday', 'Wed' => 'Wednesday', 'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday'][$short] ?? $short;
    }
}
