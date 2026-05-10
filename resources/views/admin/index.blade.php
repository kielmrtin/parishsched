<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | St. John the Baptist Parish</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f6fb; color: #1f2937; }
        a { color: inherit; }
        .admin-layout { display: flex; min-height: 100vh; }
        .admin-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #312e81 0%, #4338ca 50%, #6366f1 100%);
            color: #e2e8f0; padding: 40px 28px;
            display: flex; flex-direction: column;
            box-shadow: 0 30px 60px rgba(67,56,202,0.22);
            border-top-right-radius: 32px; border-bottom-right-radius: 32px;
        }
        .sidebar-brand { font-size: 22px; font-weight: 700; line-height: 1.3; margin-bottom: 36px; }
        .sidebar-brand span { display: block; font-size: 14px; font-weight: 500; opacity: 0.82; margin-top: 4px; }
        .sidebar-nav { display: flex; flex-direction: column; gap: 12px; margin-bottom: auto; }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px; border-radius: 14px;
            text-decoration: none; font-weight: 600; color: #f8fafc;
            background: rgba(255,255,255,0.08);
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .sidebar-nav a:hover, .sidebar-nav a:focus { background: rgba(255,255,255,0.22); transform: translateX(4px); color: #fff; }
        .sidebar-nav a.active { background: rgba(255,255,255,0.24); color: #fff; box-shadow: 0 16px 28px rgba(15,23,42,0.2); transform: translateX(6px); }
        .sidebar-nav a .icon { width: 20px; display: inline-flex; justify-content: center; }
        .sidebar-footer { margin-top: 40px; }
        .logout-button {
            display: inline-flex; align-items: center; gap: 10px;
            background: rgba(248,250,252,0.15); color: #fefefe;
            padding: 10px 18px; border-radius: 999px; font-weight: 600;
            text-decoration: none; transition: background 0.2s ease;
        }
        .logout-button:hover, .logout-button:focus { background: rgba(255,255,255,0.28); color: #fff; }
        .admin-main { flex: 1; padding: 40px 48px; }
        .main-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 32px; }
        .main-header h1 { margin: 0; font-size: 32px; font-weight: 700; color: #1e1b4b; }
        .header-summary { margin: 0; font-size: 14px; color: #6b7280; }
        .header-meta { display: flex; align-items: center; gap: 12px; font-size: 13px; color: #4338ca; font-weight: 600; }
        .flash-messages { display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; }
        .flash { border-radius: 14px; padding: 14px 18px; font-weight: 600; }
        .flash-success { background: rgba(34,197,94,0.16); color: #166534; }
        .flash-error { background: rgba(248,113,113,0.18); color: #991b1b; }
        .summary-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .summary-card { background: #fff; border-radius: 20px; padding: 20px 22px; box-shadow: 0 24px 48px rgba(79,70,229,0.1); border: 1px solid rgba(99,102,241,0.12); }
        .summary-card h2 { font-size: 13px; letter-spacing: 0.12em; text-transform: uppercase; color: #6366f1; margin: 0 0 12px; }
        .summary-value { font-size: 32px; font-weight: 700; color: #1f2937; }
        .summary-caption { font-size: 13px; color: #6b7280; margin-top: 8px; }
        .overview-panels { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
        .overview-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 14px; }
        .overview-list li { display: flex; flex-direction: column; gap: 6px; padding: 14px 16px; border-radius: 16px; border: 1px solid rgba(148,163,184,0.18); background: #f8fafc; }
        .overview-list-primary { display: flex; justify-content: space-between; gap: 12px; align-items: center; }
        .overview-list-title { font-weight: 700; color: #1f2937; }
        .overview-list-name { font-size: 13px; color: #64748b; }
        .overview-list-meta { display: flex; flex-wrap: wrap; gap: 10px; font-size: 12px; color: #475569; }
        .overview-link { display: inline-flex; align-items: center; gap: 8px; margin-top: 20px; font-weight: 600; color: #4338ca; text-decoration: none; }
        .overview-link i { transition: transform 0.2s ease; }
        .overview-link:hover i, .overview-link:focus i { transform: translateX(4px); }
        .section-card { background: #fff; border-radius: 24px; padding: 32px; box-shadow: 0 26px 60px rgba(15,23,42,0.08); margin-bottom: 32px; }
        .section-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; }
        .section-header h2 { margin: 0; font-size: 24px; color: #1f2937; }
        .section-header p { margin: 0; color: #6b7280; font-size: 14px; }
        .reservation-toolbar { display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 24px; padding: 16px; border: 1px solid rgba(99,102,241,0.15); border-radius: 18px; background: rgba(248,250,252,0.6); }
        .reservation-filter-form { display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; }
        .reservation-filter-form .form-group { margin: 0; }
        .reservation-filter-form label { font-size: 13px; font-weight: 600; color: #4b5563; margin-bottom: 6px; }
        .reservation-filter-form select, .reservation-filter-form input[type="date"] { min-width: 180px; }
        .reservation-filter-actions { display: flex; gap: 10px; align-items: center; }
        .reservation-filter-summary { display: flex; flex-direction: column; gap: 4px; font-size: 14px; color: #475569; }
        .reservation-filter-summary strong { font-size: 16px; color: #111827; }
        .schedule-toolbar { align-items: center; gap: 20px; }
        .schedule-filter-form { display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; }
        .schedule-filter-form .form-group { margin: 0; }
        .schedule-filter-form label { font-size: 13px; font-weight: 600; color: #4b5563; margin-bottom: 6px; }
        .schedule-meta { display: flex; flex-direction: column; gap: 6px; font-size: 14px; color: #475569; }
        .schedule-meta strong { color: #111827; font-size: 16px; }
        .schedule-event-counts { display: flex; flex-wrap: wrap; gap: 10px; margin: 0 0 16px; padding: 0; list-style: none; }
        .schedule-event-counts li { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 999px; background: rgba(79,70,229,0.08); color: #312e81; font-weight: 600; font-size: 13px; }
        .schedule-list { display: flex; flex-direction: column; gap: 16px; }
        .schedule-item { background: #fff; border-radius: 18px; padding: 20px; box-shadow: 0 14px 32px rgba(79,70,229,0.1); border: 1px solid rgba(99,102,241,0.12); }
        .schedule-item-header { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 12px; margin-bottom: 12px; font-size: 15px; color: #1f2937; font-weight: 600; }
        .schedule-item-body { display: flex; flex-direction: column; gap: 10px; font-size: 13px; color: #4b5563; }
        .schedule-item-body span { display: flex; align-items: center; gap: 8px; }
        .schedule-item-notes { margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(148,163,184,0.4); color: #475569; white-space: pre-line; }
        .reservation-date-field { display: flex; flex-direction: column; }
        .reservation-date-field.is-hidden { display: none; }
        .status-columns { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; }
        .status-column { position: relative; border-radius: 20px; padding: 24px; background: linear-gradient(145deg,#fff 0%,#eef2ff 100%); box-shadow: 0 18px 40px rgba(99,102,241,0.12); overflow: hidden; }
        .status-column::before { content: ''; position: absolute; inset: 0; border-radius: inherit; opacity: 0.08; pointer-events: none; }
        .status-column h3 { font-size: 20px; margin: 0 0 6px; color: #1e1b4b; }
        .status-column p { margin: 0 0 16px; font-size: 14px; color: #6b7280; }
        .status-column .empty-state { font-style: italic; color: #94a3b8; }
        .status-column-pending::before { background: linear-gradient(135deg,#f97316,#fb923c); }
        .status-column-approved::before { background: linear-gradient(135deg,#10b981,#34d399); }
        .status-column-declined::before { background: linear-gradient(135deg,#ef4444,#f87171); }
        .reservation-card { background: #fff; border-radius: 18px; padding: 20px; margin-bottom: 18px; box-shadow: 0 14px 32px rgba(79,70,229,0.1); border: 1px solid rgba(99,102,241,0.12); }
        .reservation-card:last-child { margin-bottom: 0; }
        .reservation-card h4 { margin: 0 0 8px; font-size: 18px; color: #1f2937; }
        .reservation-meta { display: flex; flex-wrap: wrap; gap: 10px 18px; margin-bottom: 12px; font-size: 13px; color: #4b5563; }
        .reservation-meta span { display: flex; align-items: center; gap: 6px; }
        .muted-text { color: #94a3b8; font-style: italic; }
        .reservation-meta a { color: #312e81; font-weight: 600; text-decoration: none; }
        .reservation-meta a:hover, .reservation-meta a:focus { text-decoration: underline; }
        .reservation-notes { font-size: 14px; line-height: 1.6; color: #374151; margin-bottom: 14px; white-space: pre-wrap; }
        .reservation-attachments { list-style: none; margin: 0 0 16px; padding: 0; display: flex; flex-wrap: wrap; gap: 10px; }
        .reservation-attachments a { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 999px; background: rgba(99,102,241,0.12); color: #312e81; font-size: 13px; font-weight: 600; text-decoration: none; transition: background 0.2s ease, color 0.2s ease; }
        .reservation-attachments a:hover, .reservation-attachments a:focus { background: rgba(99,102,241,0.2); color: #1e1b4b; }
        .status-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
        .status-actions form { margin: 0; }
        .status-actions .btn { border-radius: 999px; padding: 6px 16px; font-weight: 600; }
        .view-link { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 999px; border: 1px solid rgba(99,102,241,0.4); color: #312e81; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s ease; }
        .view-link:hover, .view-link:focus { background: rgba(99,102,241,0.1); color: #1e1b4b; }
        .announcement-grid { display: grid; grid-template-columns: minmax(260px,1fr) minmax(280px,1fr); gap: 32px; }
        .announcement-form { background: #fff; border-radius: 18px; padding: 24px; box-shadow: 0 22px 48px rgba(15,23,42,0.08); }
        .announcement-form .form-group label { font-weight: 600; color: #1f2937; }
        .announcement-list { display: flex; flex-direction: column; gap: 20px; }
        .announcement-item { background: #fff; border-radius: 18px; padding: 24px; box-shadow: 0 20px 40px rgba(15,23,42,0.08); border: 1px solid rgba(148,163,184,0.2); }
        .announcement-item-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
        .announcement-item-header h3 { margin: 0; font-size: 18px; color: #1e293b; }
        .announcement-date { font-size: 13px; color: #64748b; display: inline-flex; align-items: center; gap: 6px; }
        .announcement-image { margin-top: 16px; border-radius: 14px; overflow: hidden; background: #0f172a; }
        .announcement-image img { display: block; width: 100%; height: auto; }
        .announcement-body { margin-top: 16px; font-size: 14px; color: #374151; line-height: 1.6; white-space: pre-line; }
        .announcement-controls { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 18px; }
        .announcement-actions { display: flex; gap: 10px; align-items: center; }
        .announcement-actions form { margin: 0; }
        .visibility-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .visibility-badge.visible { background: rgba(34,197,94,0.16); color: #166534; }
        .visibility-badge.hidden { background: rgba(148,163,184,0.18); color: #475569; }
        .empty-block { font-style: italic; color: #94a3b8; }
        .login-wrapper { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 40px 16px; }
        .login-card { width: 100%; max-width: 420px; background: #fff; border-radius: 24px; padding: 36px; box-shadow: 0 28px 60px rgba(79,70,229,0.18); border: 1px solid rgba(99,102,241,0.14); }
        .login-card h1 { font-size: 26px; font-weight: 700; margin-bottom: 12px; color: #1e1b4b; }
        .login-card p { margin-bottom: 24px; color: #64748b; }
        @media (max-width: 992px) {
            .admin-layout { flex-direction: column; }
            .admin-sidebar { width: 100%; border-radius: 0; border-bottom-left-radius: 32px; border-bottom-right-radius: 32px; padding: 28px 24px; }
            .sidebar-nav { flex-direction: row; overflow-x: auto; padding-bottom: 12px; }
            .sidebar-nav a { flex: 1 0 180px; justify-content: center; }
            .admin-main { padding: 32px 20px 48px; }
            .overview-panels { grid-template-columns: 1fr; }
            .announcement-grid { grid-template-columns: 1fr; }
        }
        /* FORCE ADMIN CALENDAR SPACING + CLEAN BADGES */
.admin-calendar-card {
    overflow: visible !important;
}

.admin-availability-calendar .calendar_table {
    border-spacing: 12px !important;
}

.admin-availability-calendar .calendar_day {
    min-height: 92px !important;
    border-radius: 20px !important;
    padding: 12px !important;
    position: relative !important;
    overflow: hidden !important;
    background: #f8fbff !important;
}

.admin-availability-calendar .calendar_day .day_number {
    color: #1e293b !important;
    font-size: 15px !important;
    font-weight: 700 !important;
}

.admin-availability-calendar .calendar_day.status_booked {
    background: #eaf4ff !important;
    border: 1px solid #93c5fd !important;
}

.admin-availability-calendar .calendar_day.status_done {
    background: #fff7d6 !important;
    border: 1px solid #facc15 !important;
}

.admin-availability-calendar .calendar_day.status_done .day_number {
    color: #854d0e !important;
}

.admin-availability-calendar .calendar_day.is_selected {
    background: linear-gradient(135deg, #dbeafe, #eff6ff) !important;
    border: 2px solid #60a5fa !important;
    box-shadow: 0 10px 22px rgba(96,165,250,0.22) !important;
}

.admin-availability-calendar .calendar_day.is_selected .day_number {
    color: #0f172a !important;
}

/* small top-right status dot */
.admin-availability-calendar .calendar_day.status_booked::after,
.admin-availability-calendar .calendar_day.status_done::after {
    content: "";
    position: absolute;
    top: 10px;
    right: 10px;
    width: 11px;
    height: 11px;
    border-radius: 999px;
    border: 3px solid #fff;
    box-shadow: 0 4px 10px rgba(15,23,42,0.12);
}

.admin-availability-calendar .calendar_day.status_booked::after {
    background: #60a5fa;
}

.admin-availability-calendar .calendar_day.status_done::after {
    background: #facc15;
}

/* booking badge */
.admin-availability-calendar .calendar_day .day_label {
    position: absolute !important;
    left: 10px !important;
    right: 10px !important;
    bottom: 10px !important;
    width: auto !important;
    max-width: none !important;
    padding: 4px 6px !important;
    border-radius: 999px !important;
    font-size: 10px !important;
    line-height: 1.1 !important;
    text-align: center !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    background: rgba(255,255,255,0.75) !important;
    color: #1e3a8a !important;
    border: 1px solid rgba(96,165,250,0.35) !important;
}

.admin-availability-calendar .calendar_day.status_done .day_label {
    color: #854d0e !important;
    border-color: rgba(250,204,21,0.55) !important;
}

.admin-availability-calendar .calendar_day.is_selected .day_label {
    background: rgba(255,255,255,0.85) !important;
    color: #0f172a !important;
}

/* better legend */
.calendar_legend {
    background: #f1f5ff !important;
    border-radius: 16px !important;
    padding: 12px 16px !important;
    gap: 18px !important;
}

.legend.booked {
    background: #60a5fa !important;
}

.legend.done {
    background: #facc15 !important;
}

.admin-note-box {
    background: #ffffff !important;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px 12px;
    margin: 12px 0;
    box-shadow: none;
}

.admin-note-header {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #312e81;
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 6px;
}

.admin-note-icon {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    background: #eef2ff;
    color: #4338ca;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
}

.admin-note-box p {
    margin: 0;
    color: #4b5563;
    font-size: 11px !important;
    line-height: 1.4;
    white-space: pre-line;
}

.admin-note-field {
    margin: 12px 0 14px;
}

.admin-note-field label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #4b5563;
    margin-bottom: 5px;
}

.admin-note-input {
    width: 100%;
    min-height: 62px;
    background: #ffffff !important;
    background-color: #ffffff !important;
    border: 1px solid #d1d5db !important;
    border-radius: 10px;
    padding: 9px 11px;
    font-size: 11px !important;
    line-height: 1.35;
    color: #1f2937 !important;
    resize: none;
    outline: none !important;
    box-shadow: none !important;
}

.admin-note-input::placeholder {
    color: #9ca3af !important;
    font-size: 11px !important;
}

.admin-note-input:focus {
    background: #ffffff !important;
    background-color: #ffffff !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.10) !important;
}

.admin-detail-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.admin-detail-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 6px 14px;
    border-radius: 999px;

    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;

    white-space: nowrap;
}

.status-badge {
    margin-bottom: 10px;
}

.reservation-card h4 {
    line-height: 1.35;
    margin-top: 0;
}

/* UPCOMING (clean green) */
.admin-detail-badge.upcoming {
    background: #d1fae5;
    color: #065f46;
}

/* DONE (yellow) */
.admin-detail-badge.done {
    background: #fef9c3;
    color: #854d0e;
}

.delete-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px; /* space between icon and text */

    height: 32px; /* consistent height */
    padding: 0 12px;

    border-radius: 999px; /* pill shape like your UI */
    font-weight: 600;
}

/* fix icon alignment */
.delete-btn i {
    font-size: 13px;
    line-height: 1;
}

/* fix text alignment */
.delete-btn span {
    display: inline-block;
    line-height: 1;
}

.event-summary-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}

.event-card {
    display: flex;
    align-items: center;
    gap: 14px;

    background: #ffffff;
    border-radius: 18px;
    padding: 16px 18px;

    box-shadow: 0 18px 35px rgba(15,23,42,0.08);
    border: 1px solid rgba(148,163,184,0.15);

    transition: all 0.2s ease;
}

.event-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 22px 40px rgba(15,23,42,0.12);
}

.event-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 16px;
    color: white;
}

/* COLORS */
.event-card.wedding .event-icon {
    background: linear-gradient(135deg, #ef4444, #f87171);
}

.event-card.baptism .event-icon {
    background: linear-gradient(135deg, #10b981, #34d399);
}

.event-card.funeral .event-icon {
    background: linear-gradient(135deg, #475569, #64748b);
}

.event-info span {
    display: block;
    font-size: 13px;
    color: #64748b;
}

.event-info strong {
    font-size: 22px;
    font-weight: 700;
    color: #1f2937;
}

.event-symbol {
    font-size: 22px;
    font-weight: 800;
}

.event-card {
    justify-content: space-between;
}

.event-info {
    flex: 1;
}

.event-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.event-mini-chart {
    width: 90px;
    height: 50px;
}

.event-mini-chart canvas {
    width: 100% !important;
    height: 100% !important;
}

.event-summary-row {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 28px;
}

.event-card {
    min-height: 120px !important;
    height: 120px !important;
    padding: 18px 20px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 16px !important;
    overflow: hidden !important;
}

.event-info {
    flex: 1;
    min-width: 0;
}

.event-info span {
    font-size: 13px;
    color: #64748b;
}

.event-info strong {
    display: block;
    font-size: 26px;
    line-height: 1.1;
}

.event-mini-chart {
    width: 115px !important;
    height: 58px !important;
    flex: 0 0 115px !important;
}

.event-mini-chart canvas {
    width: 115px !important;
    height: 58px !important;
    max-width: 115px !important;
    max-height: 58px !important;
}

@media (max-width: 992px) {
    .event-summary-row {
        grid-template-columns: 1fr;
    }
}

.customer-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.customer-disable-form {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}

.customer-disable-reason {
    width: 230px;
    height: 34px;
    border-radius: 10px;
    font-size: 13px;
}

.customer-actions .btn {
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.customer-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.customer-actions form {
    margin: 0;
}

.customer-action-btn {
    height: 38px;
    min-width: 145px;
    border-radius: 999px !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    font-weight: 700;
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
}

.customer-action-btn:hover {
    transform: translateY(-1px);
}

.customer-disable-modal {
    border: 0;
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 30px 70px rgba(15, 23, 42, 0.18);
}

.customer-disable-header {
    background: linear-gradient(135deg, #fff1f2, #ffffff);
    border-bottom: 1px solid rgba(248, 113, 113, 0.18);
}

.customer-disable-header h5 {
    font-weight: 800;
    color: #991b1b;
}

.customer-disable-header small {
    color: #64748b;
}

.customer-disable-label {
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 8px;
}

.customer-disable-textarea {
    border-radius: 14px;
    border: 1px solid #d1d5db;
    resize: none;
    font-size: 14px;
}

.customer-disable-textarea:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
}

.customer-disable-footer {
    border-top: 1px solid rgba(148, 163, 184, 0.18);
}

.admin-detail-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 14px; /* ⬅️ increase this */
}

.admin-detail-view-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 13px;
    border-radius: 999px;
    background: rgba(79, 70, 229, 0.08);
    border: 1px solid rgba(79, 70, 229, 0.18);
    color: #312e81;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}

.admin-detail-view-btn:hover,
.admin-detail-view-btn:focus {
    background: rgba(79, 70, 229, 0.14);
    color: #1e1b4b;
    text-decoration: none;
}

    </style>
</head>
<body>

@if(!$isLoggedIn)
{{-- ===================== LOGIN ===================== --}}
<div class="login-wrapper">
    <div class="login-card">
        <h1>Welcome back</h1>
        <p>Sign in to manage reservations and announcements.</p>

        @if(session('flash_success'))
            <div class="alert alert-success">{{ session('flash_success') }}</div>
        @endif
        @if(session('flash_error'))
            <div class="alert alert-danger">{{ session('flash_error') }}</div>
        @endif
        @if(session('login_error'))
            <div class="alert alert-danger">{{ session('login_error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.handle') }}">
            @csrf
            <input type="hidden" name="action" value="login">
            <input type="hidden" name="redirect_section" value="{{ $section }}">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </form>
    </div>
</div>

@else
{{-- ===================== DASHBOARD ===================== --}}

@php
$sectionCopy = [
    'overview'      => ['title'=>'Administration Overview',  'subtitle'=>'Review key activity across reservations and announcements at a glance.'],
    'reservations'  => ['title'=>'Manage Reservations',      'subtitle'=>'Approve, decline, or follow up on reservation requests.'],
    'schedule'      => ['title'=>'Daily Schedule',           'subtitle'=>'Review approved reservations scheduled for a specific day.'],
    'announcements' => ['title'=>'Announcements Board',      'subtitle'=>'Publish timely updates and control what appears on the website.'],
    'analytics'     => ['title'=>'Predictive Analytics',     'subtitle'=>'Forecast busy months for baptism, wedding, and funeral reservations.'],
    'customers'     => ['title'=>'Manage Customers',          'subtitle'=>'Review customer accounts, reservation activity, and password reset status.'],
];
$pageCopy = $sectionCopy[$section] ?? $sectionCopy['overview'];

$statusMeta = [
    'pending'  => ['title'=>'Pending Review',  'subtitle'=>'Reservations awaiting your decision.',       'class'=>'status-column-pending',  'empty'=>'No pending reservations at the moment.'],
    'approved' => ['title'=>'Approved',         'subtitle'=>'Confirmed reservations ready to proceed.',   'class'=>'status-column-approved', 'empty'=>'No reservations have been approved yet.'],
    'declined' => ['title'=>'Declined',         'subtitle'=>'Reservations that were not accepted.',       'class'=>'status-column-declined', 'empty'=>'No declined reservations.'],
];

function adminFormatDate(?string $date): string {
    if (!$date || trim($date) === '') return '—';
    try { return (new DateTime($date))->format('M j, Y'); } catch (\Exception $e) { return htmlspecialchars($date); }
}
function adminFormatTime(?string $time): string {
    if (!$time || trim($time) === '') return '—';
    $t = trim($time);
    if (strpos($t, '-') !== false) {
        $parts = preg_split('/\s*-\s*/', $t);
        if (count($parts) >= 2) {
            $fmt = fn($v) => ($ts = strtotime(trim($v))) !== false ? date('g:i A', $ts) : trim($v);
            return $fmt($parts[0]) . ' – ' . $fmt($parts[1]);
        }
    }
    $ts = strtotime($t);
    return $ts !== false ? date('g:i A', $ts) : $t;
}
function adminFormatCreatedAt(?string $ca): string {
    if (!$ca || trim($ca) === '') return '—';
    $ts = strtotime($ca);
    return $ts !== false ? date('M j, Y g:i A', $ts) : $ca;
}
function adminStatusBadge(string $status): string {
    $map = ['pending'=>'badge-warning','approved'=>'badge-success','declined'=>'badge-danger'];
    $cls = $map[strtolower($status)] ?? 'badge-secondary';
    return '<span class="badge '.$cls.'">'.ucfirst(strtolower($status)).'</span>';
}
@endphp

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            St. John the Baptist Parish
            <span>Administration</span>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.index') }}" class="{{ $section === 'overview' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-bar-chart"></i></span>Overview
            </a>
            <a href="{{ route('admin.index', ['section'=>'analytics']) }}" class="{{ $section === 'analytics' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-line-chart"></i></span>Analytics
            </a>
            <a href="{{ route('admin.index', ['section'=>'reservations']) }}" class="{{ $section === 'reservations' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-calendar"></i></span>Reservations
            </a>
            <a href="{{ route('admin.index', ['section'=>'schedule']) }}" class="{{ $section === 'schedule' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-list-alt"></i></span>Schedule
            </a>
            <a href="{{ route('admin.index', ['section'=>'announcements']) }}" class="{{ $section === 'announcements' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-bullhorn"></i></span>Announcements
            </a>
            <a href="{{ route('admin.index', ['section'=>'customers']) }}" class="{{ $section === 'customers' ? 'active' : '' }}">
    <span class="icon"><i class="fa fa-users"></i></span>Customers
</a>
        </nav>
        <div class="sidebar-footer">
            <a class="logout-button" href="{{ route('admin.logout') }}"><i class="fa fa-sign-out"></i><span>Logout</span></a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="main-header">
            <div>
                <h1>{{ $pageCopy['title'] }}</h1>
                <p class="header-summary">{{ $pageCopy['subtitle'] }}</p>
            </div>
            <div class="header-meta">
                @if(in_array($section, ['overview','reservations']))
                    <span><i class="fa fa-calendar-check-o"></i> {{ $reservationHeaderTotals['approved'] }} approved</span>
                    <span><i class="fa fa-clock-o"></i> {{ $reservationHeaderTotals['pending'] }} pending</span>
                @endif
                @if($section === 'schedule')
                    <span><i class="fa fa-calendar-check-o"></i> {{ $scheduleReservationCount }} scheduled</span>
                @endif
                @if(in_array($section, ['overview','announcements']))
                    <span><i class="fa fa-bullhorn"></i> {{ $announcementCount }} announcements</span>
                    <span><i class="fa fa-eye"></i> {{ $visibleAnnouncementCount }} live</span>
                @endif
            </div>
        </header>

        @if(session('flash_success') || session('flash_error'))
            <div class="flash-messages">
                @if(session('flash_success'))
                    <div class="flash flash-success">{{ session('flash_success') }}</div>
                @endif
                @if(session('flash_error'))
                    <div class="flash flash-error">{{ session('flash_error') }}</div>
                @endif
            </div>
        @endif

        {{-- ==================== OVERVIEW ==================== --}}
        @if($section === 'overview')
            <div class="summary-cards">
                <div class="summary-card">
                    <h2>Total Requests</h2>
                    <div class="summary-value">{{ $summaryTotals['total'] }}</div>
                    <div class="summary-caption">All reservation submissions</div>
                </div>
                <div class="summary-card">
                    <h2>Pending</h2>
                    <div class="summary-value">{{ $summaryTotals['pending'] }}</div>
                    <div class="summary-caption">Awaiting review</div>
                </div>
                <div class="summary-card">
                    <h2>Approved</h2>
                    <div class="summary-value">{{ $summaryTotals['approved'] }}</div>
                    <div class="summary-caption">Ready to proceed</div>
                </div>
                <div class="summary-card">
                    <h2>Declined</h2>
                    <div class="summary-value">{{ $summaryTotals['declined'] }}</div>
                    <div class="summary-caption">Not moving forward</div>
                </div>
            </div>

            @php
    $eventTotal = max(1, array_sum($eventCounts ?? []));
@endphp

<div class="event-summary-row">

    <div class="event-card wedding">
    <div class="event-icon">
        <i class="fa fa-heart"></i>
    </div>

    <div class="event-info">
        <span>Wedding reservations</span>
        <strong class="count-up" data-count="{{ $eventCounts['Wedding'] ?? 0 }}">0</strong>
    </div>

    <div class="event-mini-chart">
        <canvas id="miniWeddingChart"></canvas>
    </div>
</div>


<div class="event-card baptism">
    <div class="event-icon event-symbol">✝</div>

    <div class="event-info">
        <span>Baptism reservations</span>
        <strong class="count-up" data-count="{{ $eventCounts['Baptism'] ?? 0 }}">0</strong>
    </div>

    <div class="event-mini-chart">
        <canvas id="miniBaptismChart"></canvas>
    </div>
</div>


<div class="event-card funeral">
    <div class="event-icon event-symbol">⚰</div>

    <div class="event-info">
        <span>Funeral reservations</span>
        <strong class="count-up" data-count="{{ $eventCounts['Funeral'] ?? 0 }}">0</strong>
    </div>

    <div class="event-mini-chart">
        <canvas id="miniFuneralChart"></canvas>
    </div>
</div>

</div>

            <div class="overview-panels">
                <section class="section-card">
                    <div class="section-header">
                        <div><h2>Recent reservations</h2><p>Latest submissions with their current status.</p></div>
                    </div>
                    @if(empty($recentReservations))
                        <p class="empty-block">No reservations have been submitted yet.</p>
                    @else
                        <ul class="overview-list">
                            @foreach($recentReservations as $r)
                                <li>
                                    <div class="overview-list-primary">
                                        <span class="overview-list-title">Reservation #{{ $r['id'] }}</span>
                                        <span class="overview-list-name">{{ $r['name'] ?? $r['customer_name'] ?? 'No name provided' }}</span>
                                    </div>
                                    <div class="overview-list-meta">
                                        <span>{{ adminFormatDate($r['preferred_date'] ?? $r['reservation_date'] ?? null) }}</span>
                                        <span>{{ adminFormatTime($r['preferred_time'] ?? $r['reservation_time'] ?? null) }}</span>
                                        {!! adminStatusBadge($r['status'] ?? 'pending') !!}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <a class="overview-link" href="{{ route('admin.index', ['section'=>'reservations']) }}">
                        Go to reservations<i class="fa fa-arrow-right"></i>
                    </a>
                </section>

                <section class="section-card">
                    <div class="section-header">
                        <div><h2>Announcements</h2><p>Stay up to date with the latest messages for parishioners.</p></div>
                    </div>
                    @if(empty($recentAnnouncements))
                        <p class="empty-block">No announcements have been posted yet.</p>
                    @else
                        <ul class="overview-list">
                            @foreach($recentAnnouncements as $a)
                                @php $isVisible = (int)($a->show_on_home ?? 0) === 1; @endphp
                                <li>
                                    <div class="overview-list-primary">
                                        <span class="overview-list-title">{{ $a->title }}</span>
                                        <span class="overview-list-name">Posted {{ adminFormatCreatedAt($a->created_at ?? '') }}</span>
                                    </div>
                                    <div class="overview-list-meta">
                                        <span class="visibility-badge {{ $isVisible ? 'visible' : 'hidden' }}">
                                            <i class="fa {{ $isVisible ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                            {{ $isVisible ? 'Visible' : 'Hidden' }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <a class="overview-link" href="{{ route('admin.index', ['section'=>'announcements']) }}">
                        Manage announcements<i class="fa fa-arrow-right"></i>
                    </a>
                </section>
            </div>
        @endif

        {{-- ==================== SCHEDULE ==================== --}}
@if($section === 'schedule')
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Reservation Calendar</h2>
                <p>Click a date to view approved reservations. Past reservations are marked as done.</p>
            </div>
            <div class="schedule-meta">
                <strong>{{ $scheduleReservationCount }} approved reservations</strong>
                <span>Calendar overview</span>
            </div>
        </div>

        <div class="admin-calendar-layout">
            <div class="admin-calendar-card">
               <div class="calendar_legend mb-3">
    <span>
        <span class="legend booked"></span> Reservation on file
    </span>
    <span>
        <span class="legend done"></span> Done / past reservation
    </span>
</div>

                <div class="availability_calendar admin-availability-calendar" id="admin-schedule-calendar"></div>
            </div>

            <div class="admin-calendar-details" id="admin-calendar-details">
                <div class="admin-calendar-empty">
                    <i class="fa fa-calendar-check-o"></i>
                    <h4>Select a date</h4>
                    <p>Click a highlighted date to view approved reservation details.</p>
                </div>
            </div>
        </div>
    </section>
@endif
         
        {{-- ==================== RESERVATIONS ==================== --}}
        @if($section === 'reservations')
            <section class="section-card">
                <div class="section-header">
                    <div><h2>Reservations</h2><p>Review reservation requests grouped by their current status.</p></div>
                    <div class="header-meta">
                        <span><i class="fa fa-clock-o"></i> {{ $filteredTotals['pending'] }} pending</span>
                    </div>
                </div>

                <div class="reservation-toolbar">
                    <form method="GET" action="{{ route('admin.index') }}" class="reservation-filter-form">
                        <input type="hidden" name="section" value="reservations">
                        <div class="form-group">
                            <label for="reservation_filter_range">Show</label>
                            <select class="form-control form-control-sm" id="reservation_filter_range" name="range" data-reservation-filter-range>
                                <option value="all"   {{ $reservationFilterRange === 'all'   ? 'selected' : '' }}>All reservations</option>
                                <option value="today" {{ $reservationFilterRange === 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week"  {{ $reservationFilterRange === 'week'  ? 'selected' : '' }}>This week</option>
                                <option value="date"  {{ $reservationFilterRange === 'date'  ? 'selected' : '' }}>Specific date</option>
                            </select>
                        </div>
                        <div class="form-group reservation-date-field" data-reservation-filter-date-wrapper>
                            <label for="reservation_filter_date">Date</label>
                            <input type="date" class="form-control form-control-sm" id="reservation_filter_date" name="date"
                                value="{{ $reservationFilterDate ?? '' }}">
                        </div>
                        <div class="reservation-filter-actions">
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                            @if($hasActiveFilter)
                                <a class="btn btn-link btn-sm" href="{{ route('admin.index', ['section'=>'reservations']) }}">Reset</a>
                            @endif
                        </div>
                    </form>
                    <div class="reservation-filter-summary">
                        <strong>{{ $reservationCountSummary }}</strong>
                        <span>{{ $reservationFilterDesc }}</span>
                    </div>
                </div>

                @if($filteredTotals['total'] === 0)
                    <p class="empty-block">
                        {{ $summaryTotals['total'] === 0 ? 'No reservations have been submitted yet.' : 'No reservations match the selected filters.' }}
                    </p>
                @else
                    <div class="status-columns">
                        @foreach($statusMeta as $statusKey => $meta)
                            <div class="status-column {{ $meta['class'] }}">
                                <h3>{{ $meta['title'] }}</h3>
                                <p>{{ $meta['subtitle'] }}</p>

                                @if(empty($filteredGrouped[$statusKey]))
                                    <p class="empty-state">{{ $meta['empty'] }}</p>
                                @else
                                    @foreach($filteredGrouped[$statusKey] as $r)
    @php
        $reservationDate = $r['preferred_date'] ?? $r['reservation_date'] ?? null;
        $isPastApproved = $statusKey === 'approved'
            && $reservationDate
            && strtotime($reservationDate) < strtotime(date('Y-m-d'));
    @endphp

    @if($isPastApproved)
        @continue
    @endif
                                        <div class="reservation-card">
                                            <div class="status-badge">{!! adminStatusBadge($r['status']) !!}</div>
                                            <h4>
    Reservation #{{ $r['id'] ?? '—' }} · 
    {{ $r['name'] ?? $r['details']['name'] ?? $r['customer_name'] ?? 'No name provided' }}
</h4>
                                            <div class="reservation-meta">
                                                <span>
                                                    <i class="fa fa-envelope"></i>
                                                    @if(!empty($r['email']))
                                                        <a href="mailto:{{ $r['email'] }}">{{ $r['email'] }}</a>
                                                    @else
                                                        <span class="muted-text">Not provided</span>
                                                    @endif
                                                </span>
                                                <span>
                                                    <i class="fa fa-phone"></i>
                                                    @if(!empty($r['phone']))
                                                        <a href="tel:{{ $r['phone'] }}">{{ $r['phone'] }}</a>
                                                    @else
                                                        <span class="muted-text">Not provided</span>
                                                    @endif
                                                </span>
                                                <span><i class="fa fa-calendar"></i>{{ adminFormatDate($r['preferred_date'] ?? $r['reservation_date'] ?? null) }}</span>
                                                <span><i class="fa fa-clock-o"></i>{{ adminFormatTime($r['preferred_time'] ?? $r['reservation_time'] ?? null) }}</span>
                                            </div>
                                            @if(!empty($r['notes']))
                                                <div class="reservation-notes">{{ $r['notes'] }}</div>
                                            @endif
                                            @if(!empty($r['attachments']))
                                                <ul class="reservation-attachments">
                                                    @foreach($r['attachments'] as $att)
                                                        @php
                                                            $attPath  = $att['stored_path'] ?? '';
                                                            $attName  = $att['file_name'] ?? '';
                                                            $attLabel = !empty($att['label']) && $att['label'] !== $attName ? $att['label'] : 'Download attachment';
                                                        @endphp
                                                        @if($attPath && $attName)
                                                            <li>
                                                                <a href="{{ $attPath }}" download="{{ $attName }}" target="_blank" rel="noopener">
                                                                    <i class="fa fa-paperclip"></i>
                                                                    <span>{{ $attLabel }}</span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                            @if(!empty($r['admin_note']))
    <div class="admin-note-box">
        <div class="admin-note-header">
            <span class="admin-note-icon">
                <i class="fa fa-sticky-note-o"></i>
            </span>
            <span>Admin note</span>
        </div>
        <p>{{ $r['admin_note'] }}</p>
    </div>
@endif

<div class="admin-note-field">
    <label>Admin note</label>
    <textarea
        class="admin-note-input"
        rows="2"
        placeholder="Optional note before approving or declining..."
        oninput="document.querySelectorAll('.admin-note-{{ $r['id'] }}').forEach(el => el.value = this.value)"></textarea>
</div>

<div class="status-actions">
    <a class="view-link" href="{{ url('reservation_view/'.$r['id']) }}">
        <i class="fa fa-eye"></i><span>View details</span>
    </a>

    @if($r['status'] !== 'approved')
        <form method="POST" action="{{ route('admin.handle') }}">
            @csrf
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="reservation_id" value="{{ $r['id'] }}">
            <input type="hidden" name="status" value="approved">
            <input type="hidden" name="admin_note" class="admin-note-{{ $r['id'] }}">
            <input type="hidden" name="redirect_section" value="reservations">
            <button type="submit" class="btn btn-success btn-sm">Approve</button>
        </form>
    @endif

    @if($r['status'] !== 'declined')
        <form method="POST" action="{{ route('admin.handle') }}">
            @csrf
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="reservation_id" value="{{ $r['id'] }}">
            <input type="hidden" name="status" value="declined">
            <input type="hidden" name="admin_note" class="admin-note-{{ $r['id'] }}">
            <input type="hidden" name="redirect_section" value="reservations">
            <button type="submit" class="btn btn-danger btn-sm">Decline</button>
        </form>
    @endif

    @if($r['status'] !== 'pending')
        <form method="POST" action="{{ route('admin.handle') }}">
            @csrf
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="reservation_id" value="{{ $r['id'] }}">
            <input type="hidden" name="status" value="pending">
            <input type="hidden" name="admin_note" class="admin-note-{{ $r['id'] }}">
            <input type="hidden" name="redirect_section" value="reservations">
            <button type="submit" class="btn btn-secondary btn-sm">Mark Pending</button>
        </form>
    @endif
</div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif

        <style>
    .analytics-reposition-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 325px;
        gap: 28px;
        align-items: start;
    }

    .analytics-left-charts,
    .analytics-right-cards {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .analytics-right-cards .summary-card {
        margin: 0;
        min-height: 132px;
    }

    .analytics-left-charts .section-card {
        margin-bottom: 0 !important;
    }

    @media (max-width: 992px) {
        .analytics-reposition-layout {
            grid-template-columns: 1fr;
        }
    }
</style>

        {{-- ==================== ANALYTICS ==================== --}}
@if($section === 'analytics')
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Predictive Analytics Dashboard</h2>
                <p>Forecasted reservation demand for Baptism, Wedding, and Funeral based on historical monthly bookings.</p>
            </div>
        </div>

        <div class="analytics-reposition-layout">

    {{-- LEFT SIDE: CHARTS --}}
    <div class="analytics-left-charts">
        <div class="section-card" style="margin-bottom:0;">
            <div class="section-header">
                <div><h2>Baptism Monthly Trend</h2><p>Historical monthly count of baptism reservations</p></div>
            </div>
            <canvas id="baptismChart" height="120"></canvas>
        </div>

        <div class="section-card" style="margin-bottom:0;">
            <div class="section-header">
                <div><h2>Wedding Monthly Trend</h2><p>Historical monthly count of wedding reservations</p></div>
            </div>
            <canvas id="weddingChart" height="120"></canvas>
        </div>

        <div class="section-card" style="margin-bottom:0;">
            <div class="section-header">
                <div><h2>Funeral Monthly Trend</h2><p>Historical monthly count of funeral reservations</p></div>
            </div>
            <canvas id="funeralChart" height="120"></canvas>
        </div>
    </div>

    {{-- RIGHT SIDE: SAME OLD CARDS --}}
    <div class="analytics-right-cards">
        <div class="summary-card">
            <h2>Predicted Baptism</h2>
            <div class="summary-value">{{ (int)$baptismForecast }}</div>
            <div class="summary-caption">Expected bookings next month</div>
        </div>

        <div class="summary-card">
            <h2>Predicted Wedding</h2>
            <div class="summary-value">{{ (int)$weddingForecast }}</div>
            <div class="summary-caption">Expected bookings next month</div>
        </div>

        <div class="summary-card">
            <h2>Predicted Funeral</h2>
            <div class="summary-value">{{ (int)$funeralForecast }}</div>
            <div class="summary-caption">Expected bookings next month</div>
        </div>

        <div class="summary-card">
            <h2>Peak Baptism Month</h2>
            <div class="summary-value" style="font-size:22px;">{{ $weddingPeak['label'] ?? 'No data' }}
                @if($baptismPeak)
                    {{ $baptismPeak['label'] }}
                @else
                    No data
                @endif
            </div>
            <div class="summary-caption">
                {{ $baptismPeak ? (int)$baptismPeak['total'].' reservations' : 'No historical data available' }}
            </div>
        </div>

        <div class="summary-card">
            <h2>Peak Wedding Month</h2>
            <div class="summary-value" style="font-size:22px;">
                @if($weddingPeak)
                    {{ $weddingPeak['label'] ?? 'No data' }}
                @else
                    No data
                @endif
            </div>
            <div class="summary-caption">
                {{ $weddingPeak ? (int)$weddingPeak['total'].' reservations' : 'No historical data available' }}
            </div>
        </div>

        <div class="summary-card">
            <h2>Peak Funeral Month</h2>
            <div class="summary-value" style="font-size:22px;">
                @if($funeralPeak)
                    {{ $funeralPeak['label'] ?? 'No data' }}
                @else
                    No data
                @endif
            </div>
            <div class="summary-caption">
                {{ $funeralPeak ? (int)$funeralPeak['total'].' reservations' : 'No historical data available' }}
            </div>
        </div>
    </div>

</div>

        <div class="section-card" style="margin-top:24px; margin-bottom:0;">
            <div class="section-header">
                <div><h2>Forecast Insights</h2><p>System-generated planning insights for administrators</p></div>
            </div>

            <ul class="overview-list">
                <li>
                    <div class="overview-list-title">Predicted baptism bookings next month: {{ (int)$baptismForecast }}</div>
                    <div class="overview-list-name">
                        @if($baptismPeak)
                            Highest historical baptism demand was in {{ $baptismPeak['label'] }} with {{ (int)$baptismPeak['total'] }} reservations.
                        @else
                            No baptism data available yet.
                        @endif
                    </div>
                </li>

                <li>
                    <div class="overview-list-title">Predicted wedding bookings next month: {{ (int)$weddingForecast }}</div>
                    <div class="overview-list-name">
                        @if($weddingPeak)
                            Highest historical wedding demand was in {{ $weddingPeak['label'] ?? 'No data' }} with {{ (int)$weddingPeak['total'] }} reservations.
                        @else
                            No wedding data available yet.
                        @endif
                    </div>
                </li>

                <li>
                    <div class="overview-list-title">Predicted funeral bookings next month: {{ (int)$funeralForecast }}</div>
                    <div class="overview-list-name">
                        @if($funeralPeak)
                            Highest historical funeral demand was in {{ $funeralPeak['label'] ?? 'No data' }} with {{ (int)$funeralPeak['total'] }} reservations.
                        @else
                            No funeral data available yet.
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </section>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const baptismLabels = @json($baptismChart['labels']);
    const baptismTotals = @json($baptismChart['totals']);

    const weddingLabels = @json($weddingChart['labels']);
    const weddingTotals = @json($weddingChart['totals']);

    const funeralLabels = @json($funeralChart['labels']);
    const funeralTotals = @json($funeralChart['totals']);

    function createGradient(ctx, color) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, color);
        gradient.addColorStop(1, 'rgba(255,255,255,0)');
        return gradient;
    }

    function makeChart(canvasId, labels, totals, label, color) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const gradient = createGradient(ctx, color);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: totals,
                    borderColor: color,
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,          // small clean dots
                    pointHoverRadius: 7
                }]
            },
        options: {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: {
            display: true
        },
        tooltip: {
            enabled: true
        }
    },
    scales: {
        x: {
            display: true
        },
        y: {
            display: true,
            beginAtZero: true,
            ticks: {
                precision: 0
            }
        }
    }
}
        });
    }

    // COLORS (as you requested)
    makeChart(
        'baptismChart',
        baptismLabels,
        baptismTotals,
        'Baptism Reservations',
        '#86efac' // light green
    );

    makeChart(
        'weddingChart',
        weddingLabels,
        weddingTotals,
        'Wedding Reservations',
        '#fca5a5' // light red
    );

    makeChart(
        'funeralChart',
        funeralLabels,
        funeralTotals,
        'Funeral Reservations',
        '#9ca3af' // gray
    );
});
</script>
@endif

{{-- ==================== CUSTOMERS ==================== --}}
@if($section === 'customers')
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Manage Customers</h2>
                <p>View registered customer accounts and reservation history.</p>
            </div>
            <div class="header-meta">
                <span><i class="fa fa-users"></i> {{ count($customers ?? []) }} customers</span>
            </div>
        </div>

        <div class="reservation-toolbar">
    <form method="GET" action="{{ route('admin.index') }}" class="reservation-filter-form">
        <input type="hidden" name="section" value="customers">

        <div class="form-group">
            <label>Search customer</label>
            <input type="text"
            id="customerLiveSearch"
                   class="form-control form-control-sm"
                   name="customer_search"
                   value="{{ $customerSearch ?? '' }}"
                   placeholder="Name, email, or phone">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select class="form-control form-control-sm" name="customer_status">
                <option value="all" {{ ($customerStatus ?? 'all') === 'all' ? 'selected' : '' }}>All customers</option>
                <option value="active" {{ ($customerStatus ?? 'all') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="disabled" {{ ($customerStatus ?? 'all') === 'disabled' ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>

        <div class="reservation-filter-actions">
            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
            <a class="btn btn-link btn-sm" href="{{ route('admin.index', ['section' => 'customers']) }}">Reset</a>
        </div>
    </form>

    <div class="reservation-filter-summary">
        <strong>{{ count($customers ?? []) }} customer(s)</strong>
        <span>Filtered customer results</span>
    </div>
</div>

        @if(empty($customers))
            <p class="empty-block">No customers found.</p>
        @else
            <div class="overview-list">
                @foreach($customers as $c)
                    <div class="reservation-card customer-search-item"
     data-customer-search="{{ strtolower(($c['name'] ?? '') . ' ' . ($c['email'] ?? '') . ' ' . ($c['phone'] ?? '')) }}">
                        <div class="overview-list-primary">
                            <div>
                                <h4 style="margin-bottom:6px;">{{ $c['name'] ?? 'No name provided' }}</h4>
                                <div class="reservation-meta">
                                    <span><i class="fa fa-envelope"></i>{{ $c['email'] ?? 'No email' }}</span>
                                    <span><i class="fa fa-phone"></i>{{ $c['phone'] ?? 'No phone' }}</span>
                                    <span><i class="fa fa-calendar-check-o"></i>{{ $c['total_reservations'] ?? 0 }} reservation(s)</span>
                                </div>
                            </div>

                            <span class="visibility-badge {{ ($c['status'] ?? 'active') === 'active' ? 'visible' : 'hidden' }}">
                                <i class="fa {{ ($c['status'] ?? 'active') === 'active' ? 'fa-check-circle' : 'fa-ban' }}"></i>
                                {{ ucfirst($c['status'] ?? 'active') }}
                            </span>
                        </div>

                        <div class="status-actions customer-actions" style="margin-top:16px;">
    <button type="button"
            class="btn btn-primary btn-sm customer-action-btn"
            data-toggle="modal"
            data-target="#customerModal{{ $c['id'] }}">
        <i class="fa fa-eye"></i> View Profile
    </button>

    @if(($c['status'] ?? 'active') === 'active')
        <button type="button"
                class="btn btn-danger btn-sm customer-action-btn"
                data-toggle="modal"
                data-target="#disableCustomerModal{{ $c['id'] }}">
            <i class="fa fa-ban"></i> Disable
        </button>
    @else
        <form method="POST" action="{{ route('admin.handle') }}">
            @csrf
            <input type="hidden" name="action" value="toggle_customer_status">
            <input type="hidden" name="customer_id" value="{{ $c['id'] }}">
            <input type="hidden" name="status" value="active">

            <button type="submit" class="btn btn-success btn-sm customer-action-btn">
                <i class="fa fa-check"></i> Enable
            </button>
        </form>
    @endif

    <form method="POST" action="{{ route('admin.handle') }}">
        @csrf
        <input type="hidden" name="action" value="reset_password">
        <input type="hidden" name="customer_id" value="{{ $c['id'] }}">

        <button type="submit" class="btn btn-secondary btn-sm customer-action-btn">
            <i class="fa fa-key"></i> Reset Password
        </button>
    </form>
</div>

<div class="modal fade" id="disableCustomerModal{{ $c['id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content customer-disable-modal">
            <form method="POST" action="{{ route('admin.handle') }}">
                @csrf
                <input type="hidden" name="action" value="toggle_customer_status">
                <input type="hidden" name="customer_id" value="{{ $c['id'] }}">
                <input type="hidden" name="status" value="disabled">

                <div class="modal-header customer-disable-header">
                    <div>
                        <h5 class="modal-title">Disable Customer</h5>
                        <small>{{ $c['name'] ?? 'Customer account' }}</small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <label class="customer-disable-label">Reason for disabling</label>
                    <textarea name="disabled_reason"
                              class="form-control customer-disable-textarea"
                              rows="3"
                              placeholder="Example: Incomplete documents, suspicious activity, duplicate account..."
                              required></textarea>
                </div>

                <div class="modal-footer customer-disable-footer">
                    <button type="button" class="btn btn-light customer-action-btn" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger customer-action-btn">
                        <i class="fa fa-ban"></i> Disable Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div> {{-- CLOSE CUSTOMER CARD --}}

                    <div class="modal fade" id="customerModal{{ $c['id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content" style="border-radius:24px; border:0; overflow:hidden;">
                                <div class="modal-header" style="background:#f8fafc; border-bottom:1px solid rgba(148,163,184,0.18);">
                                    <div>
                                        <h5 class="modal-title" style="font-weight:700; color:#1e1b4b;">
                                            {{ $c['name'] ?? 'No name provided' }}
                                        </h5>
                                        <small style="color:#64748b;">Customer profile and reservation history</small>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body" style="padding:24px;">
                                    <div class="reservation-meta" style="margin-bottom:18px;">
    <span><i class="fa fa-envelope"></i>{{ $c['email'] ?? 'No email' }}</span>
    <span><i class="fa fa-phone"></i>{{ $c['phone'] ?? 'No phone' }}</span>
    <span><i class="fa fa-calendar"></i>{{ $c['total_reservations'] ?? 0 }} total reservation(s)</span>

    @if(!empty($c['last_password_reset_at']))
        <span><i class="fa fa-key"></i>Reset requested: {{ adminFormatCreatedAt($c['last_password_reset_at']) }}</span>
    @endif
</div>

@if(($c['status'] ?? 'active') === 'disabled')
    <div class="admin-note-box">
        <div class="admin-note-header">
            <span class="admin-note-icon">
                <i class="fa fa-ban"></i>
            </span>
            <span>Disabled reason</span>
        </div>
        <p>{{ $c['disabled_reason'] ?: 'No reason provided.' }}</p>
    </div>
@endif

<div class="summary-cards" style="grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); margin-bottom:22px;">
    <div class="summary-card" style="padding:16px;">
        <h2>Favorite Event</h2>
        <div class="summary-value" style="font-size:20px;">
            {{ $c['favorite_event_type'] ?? 'No data' }}
        </div>
        <div class="summary-caption">Most booked type</div>
    </div>

    <div class="summary-card" style="padding:16px;">
        <h2>Approved</h2>
        <div class="summary-value" style="font-size:24px;">
            {{ $c['approved_count'] ?? 0 }}
        </div>
        <div class="summary-caption">Confirmed</div>
    </div>

    <div class="summary-card" style="padding:16px;">
        <h2>Pending</h2>
        <div class="summary-value" style="font-size:24px;">
            {{ $c['pending_count'] ?? 0 }}
        </div>
        <div class="summary-caption">Awaiting review</div>
    </div>

    <div class="summary-card" style="padding:16px;">
        <h2>Declined</h2>
        <div class="summary-value" style="font-size:24px;">
            {{ $c['declined_count'] ?? 0 }}
        </div>
        <div class="summary-caption">Not approved</div>
    </div>
</div>

                                    <h5 style="font-weight:700; margin-bottom:14px;">Reservation History</h5>

                                    @if(empty($c['reservations']))
                                        <p class="empty-block">This customer has no reservations yet.</p>
                                    @else
                                        <ul class="overview-list">
                                            @foreach($c['reservations'] as $r)
                                                <li>
                                                    <div class="overview-list-primary">
                                                        <span class="overview-list-title">
                                                            Reservation #{{ $r['id'] }} · {{ $r['event_type'] }}
                                                        </span>
                                                        {!! adminStatusBadge($r['status']) !!}
                                                    </div>
                                                    <div class="overview-list-meta">
                                                        <span><i class="fa fa-calendar"></i>{{ adminFormatDate($r['reservation_date']) }}</span>
                                                        <span><i class="fa fa-clock-o"></i>{{ adminFormatTime($r['reservation_time']) }}</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endif

        {{-- ==================== ANNOUNCEMENTS ==================== --}}
        @if($section === 'announcements')
            <section class="section-card">
                <div class="section-header">
                    <div><h2>Create announcements</h2><p>Share news with parishioners and control visibility with one click.</p></div>
                </div>
                <div class="announcement-grid">
                    <div class="announcement-form">
                        <h3 class="h5 mb-3">Compose a message</h3>
                        <form method="POST" action="{{ route('admin.handle') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="action" value="create_announcement">
                            <input type="hidden" name="redirect_section" value="announcements">
                            <div class="form-group">
                                <label for="announcement_title">Title</label>
                                <input type="text" class="form-control" id="announcement_title" name="announcement_title" maxlength="150" required>
                            </div>
                            <div class="form-group">
                                <label for="announcement_body">Message</label>
                                <textarea class="form-control" id="announcement_body" name="announcement_body" rows="4" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="announcement_image">Image (optional)</label>
                                <input type="file" class="form-control" id="announcement_image" name="announcement_image" accept="image/jpeg,image/png,image/gif,image/webp">
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="announcement_show" name="announcement_show" value="1">
                                <label class="form-check-label" for="announcement_show">Show this on the home page</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Publish announcement</button>
                        </form>
                    </div>

                    <div class="announcement-list">
                        @if($announcementCount === 0)
                            <p class="empty-block">No announcements have been posted yet.</p>
                        @else
                            @foreach($announcements as $a)
                                @php $isVisible = (int)($a->show_on_home ?? 0) === 1; @endphp
                                <div class="announcement-item">
                                    <div class="announcement-item-header">
                                        <h3>{{ $a->title }}</h3>
                                        <span class="announcement-date"><i class="fa fa-calendar"></i> {{ adminFormatCreatedAt($a->created_at ?? '') }}</span>
                                    </div>
                                    @if(!empty($a->image_path))
                                        <div class="announcement-image">
                                            <img src="{{ rtrim(config('services.supabase.url'), '/') }}/storage/v1/object/public/{{ $a->image_path }}"
     alt="Announcement image"
     style="width:100%; height:200px; object-fit:cover; border-radius:12px;">
                                        </div>
                                    @endif
                                    <div class="announcement-body">{{ $a->body }}</div>
                                    <div class="announcement-controls">
                                        <span class="visibility-badge {{ $isVisible ? 'visible' : 'hidden' }}">
                                            <i class="fa {{ $isVisible ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                            {{ $isVisible ? 'Visible on home page' : 'Hidden from home page' }}
                                        </span>
                                        <div class="announcement-actions">
                                            <form method="POST" action="{{ route('admin.handle') }}">
                                                @csrf
                                                <input type="hidden" name="action" value="toggle_announcement">
                                                <input type="hidden" name="announcement_id" value="{{ $a->id }}">
                                                <input type="hidden" name="show_on_home" value="{{ $isVisible ? '0' : '1' }}">
                                                <input type="hidden" name="redirect_section" value="announcements">
                                                <button type="submit" class="btn btn-{{ $isVisible ? 'secondary' : 'success' }} btn-sm">
                                                    {{ $isVisible ? 'Hide from home' : 'Show on home' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.handle') }}" onsubmit="return confirm('Delete this announcement? This action cannot be undone.');">
                                                @csrf
                                                <input type="hidden" name="action" value="delete_announcement">
                                                <input type="hidden" name="announcement_id" value="{{ $a->id }}">
                                                <input type="hidden" name="redirect_section" value="announcements">
                                                <button type="submit" class="btn btn-outline-danger btn-sm delete-btn">
    <i class="fa fa-trash"></i>
    <span>Delete</span>
</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </section>
        @endif

    </main>
</div>

@endif

<script src="{{ asset('js/vendor/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rangeSelect = document.querySelector('[data-reservation-filter-range]');
        var dateWrapper = document.querySelector('[data-reservation-filter-date-wrapper]');
        if (!rangeSelect || !dateWrapper) return;
        var toggle = function () { dateWrapper.classList.toggle('is-hidden', rangeSelect.value !== 'date'); };
        toggle();
        rangeSelect.addEventListener('change', toggle);
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('admin-schedule-calendar');
    const detailsPanel = document.getElementById('admin-calendar-details');

    if (!container || !detailsPanel) return;

    const reservationsByDate = window.adminScheduleCalendar = @json($scheduleCalendarJson ?? []);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    const weekdayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    const state = {
        year: today.getFullYear(),
        month: today.getMonth(),
        selectedDate: null
    };

    function toLocalKey(year, month, day) {
        return year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
    }

    function formatDisplayDate(isoDate) {
        const parts = isoDate.split('-');
        const d = new Date(parts[0], parts[1] - 1, parts[2]);
        return d.toLocaleDateString(undefined, {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    function isDoneDate(isoDate) {
        const parts = isoDate.split('-');
        const d = new Date(parts[0], parts[1] - 1, parts[2]);
        d.setHours(0, 0, 0, 0);
        return d.getTime() < today.getTime();
    }

    function escapeHtml(value) {
        return String(value || '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function renderDetails(dateKey) {
        const reservations = reservationsByDate[dateKey] || [];
        const done = isDoneDate(dateKey);

        if (!reservations.length) {
            detailsPanel.innerHTML = `
                <div class="admin-calendar-empty">
                    <div class="admin-calendar-empty-icon">
                        <i class="fa fa-calendar-o"></i>
                    </div>
                    <h3>${escapeHtml(formatDisplayDate(dateKey))}</h3>
                    <p>No approved reservations for this date.</p>
                </div>
            `;
            return;
        }

        let html = `
            <div class="admin-date-heading">
                <span>${done ? 'Done / Past date' : 'Upcoming schedule'}</span>
                <h3>${escapeHtml(formatDisplayDate(dateKey))}</h3>
                <p>${reservations.length} approved reservation${reservations.length > 1 ? 's' : ''}</p>
            </div>
        `;

        reservations.forEach(function (r) {
            html += `
                <div class="admin-detail-card">
                    <div class="admin-detail-top">
                        <h4>Reservation #${escapeHtml(r.id)} · ${escapeHtml(r.name)}</h4>
                        <div class="admin-detail-actions">
    <span class="admin-detail-badge ${done ? 'done' : 'upcoming'}">${done ? 'Done' : 'Upcoming'}</span>
    <a class="admin-detail-view-btn" href="/reservation_view/${escapeHtml(r.id)}">
        <i class="fa fa-eye"></i> View details
    </a>
</div>
                    </div>

                    <div class="admin-detail-meta">
                        <span><i class="fa fa-bookmark"></i> ${escapeHtml(r.eventType)}</span>
                        <span><i class="fa fa-clock-o"></i> ${escapeHtml(r.time || 'No time provided')}</span>
                        <span><i class="fa fa-envelope"></i> ${escapeHtml(r.email || 'No email provided')}</span>
                        <span><i class="fa fa-phone"></i> ${escapeHtml(r.phone || 'No phone provided')}</span>
                    </div>

                    ${r.note ? `
                        <div class="admin-detail-note">
                            <strong><i class="fa fa-sticky-note-o"></i> Admin note:</strong>
                            <div>${escapeHtml(r.note)}</div>
                        </div>
                    ` : ''}
                </div>
            `;
        });

        detailsPanel.innerHTML = html;
    }

    function appendEmptyCell(row) {
        const td = document.createElement('td');
        const div = document.createElement('div');
        div.className = 'calendar_day is_empty';
        td.appendChild(div);
        row.appendChild(td);
    }

    function renderCalendar() {
        container.innerHTML = '';

        const navigation = document.createElement('div');
        navigation.className = 'calendar_navigation';

        const previousButton = document.createElement('button');
        previousButton.type = 'button';
        previousButton.className = 'calendar_nav_button';
        previousButton.innerHTML = '<i class="fa fa-angle-left"></i>';

        const label = document.createElement('div');
        label.className = 'calendar_nav_label';
        label.textContent = monthNames[state.month] + ' ' + state.year;

        const nextButton = document.createElement('button');
        nextButton.type = 'button';
        nextButton.className = 'calendar_nav_button';
        nextButton.innerHTML = '<i class="fa fa-angle-right"></i>';

        navigation.appendChild(previousButton);
        navigation.appendChild(label);
        navigation.appendChild(nextButton);
        container.appendChild(navigation);

        const table = document.createElement('table');
        table.className = 'calendar_table';

        const thead = document.createElement('thead');
        const headRow = document.createElement('tr');

        weekdayNames.forEach(function (day) {
            const th = document.createElement('th');
            th.textContent = day;
            headRow.appendChild(th);
        });

        thead.appendChild(headRow);
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        const firstDay = new Date(state.year, state.month, 1);
        const lastDay = new Date(state.year, state.month + 1, 0);

        let row = document.createElement('tr');

        for (let i = 0; i < firstDay.getDay(); i++) {
            appendEmptyCell(row);
        }

        for (let day = 1; day <= lastDay.getDate(); day++) {
            const dateKey = toLocalKey(state.year, state.month, day);
            const reservations = reservationsByDate[dateKey] || [];
            const td = document.createElement('td');
            const div = document.createElement('div');

            div.className = 'calendar_day';

            if (reservations.length) {
                div.classList.add(isDoneDate(dateKey) ? 'status_done' : 'status_booked');
            }

            if (state.selectedDate === dateKey) {
                div.classList.add('is_selected');
            }

            div.innerHTML = `<span class="day_number">${day}</span>`;
            div.classList.add('is_clickable');
            div.addEventListener('click', function () {
                state.selectedDate = dateKey;
                renderCalendar();
                renderDetails(dateKey);
            });

            td.appendChild(div);
            row.appendChild(td);

            if (new Date(state.year, state.month, day).getDay() === 6) {
                tbody.appendChild(row);
                row = document.createElement('tr');
            }
        }

        if (row.children.length) {
            while (row.children.length < 7) {
                appendEmptyCell(row);
            }
            tbody.appendChild(row);
        }

        table.appendChild(tbody);
        container.appendChild(table);

        previousButton.addEventListener('click', function () {
            if (state.month === 0) {
                state.month = 11;
                state.year--;
            } else {
                state.month--;
            }
            renderCalendar();
        });

        nextButton.addEventListener('click', function () {
            if (state.month === 11) {
                state.month = 0;
                state.year++;
            } else {
                state.month++;
            }
            renderCalendar();
        });
    }

    renderCalendar();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.count-up').forEach(function (el) {
        let target = parseInt(el.dataset.count) || 0;
        let duration = 800;
        let start = 0;

        function animate() {
            start += Math.ceil(target / 20);
            if (start > target) start = target;
            el.innerText = start;
            if (start < target) requestAnimationFrame(animate);
        }

        animate();
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function createMiniChart(id, labels, data, color) {
        const canvas = document.getElementById(id);
        if (!canvas || typeof Chart === 'undefined') return;

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    borderColor: color,
backgroundColor: function (context) {
    const chart = context.chart;
    const area = chart.chartArea;
    if (!area) return color + '18';

    const gradient = chart.ctx.createLinearGradient(0, area.top, 0, area.bottom);
    gradient.addColorStop(0, color + '35');
    gradient.addColorStop(0.65, color + '12');
    gradient.addColorStop(1, color + '00');
    return gradient;
},
fill: true,
tension: 0.48,
pointRadius: 0,
pointHoverRadius: 4,
borderWidth: 2.6,
borderCapStyle: 'round',
borderJoinStyle: 'round'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 700
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                },
                scales: {
                    x: { display: false },
                    y: { display: false, beginAtZero: true }
                }
            }
        });
    }

    createMiniChart(
        'miniWeddingChart',
        @json($weddingChart['labels'] ?? []),
        @json($weddingChart['totals'] ?? []),
        '#ef4444'
    );

    createMiniChart(
        'miniBaptismChart',
        @json($baptismChart['labels'] ?? []),
        @json($baptismChart['totals'] ?? []),
        '#22c55e'
    );

    createMiniChart(
        'miniFuneralChart',
        @json($funeralChart['labels'] ?? []),
        @json($funeralChart['totals'] ?? []),
        '#64748b'
    );
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('customerLiveSearch');
    const items = document.querySelectorAll('.customer-search-item');

    if (!input || !items.length) return;

    input.addEventListener('input', function () {
        const term = input.value.toLowerCase().trim();

        items.forEach(function (item) {
            const text = item.dataset.customerSearch || '';
            item.style.display = text.includes(term) ? '' : 'none';
        });
    });
});
</script>

</body>
</html>