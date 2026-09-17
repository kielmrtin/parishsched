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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <style>
        body { background-color: #f4f6fb; color: #1f2937; }
        a { color: inherit; }
        .admin-layout { display: flex; min-height: 100vh; align-items: flex-start; }
        .admin-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #7f1d1d 0%, #991b1b 45%, #b91c1c 100%);
            color: #e2e8f0;
            padding: 32px 20px 28px;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 32px rgba(127,29,29,0.35);
            border-top-right-radius: 32px; border-bottom-right-radius: 32px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            flex-shrink: 0;
        }
        .sidebar-brand { margin-bottom: 0; }
        .sidebar-brand-icon {
            width: 54px; height: 54px; border-radius: 50%;
            overflow: hidden; margin-bottom: 14px; flex-shrink: 0;
            box-shadow: 0 4px 16px rgba(0,0,0,.25);
            background: #dc2626;
        }
        .sidebar-brand-icon img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .sidebar-brand-name { font-size: 16px; font-weight: 800; line-height: 1.3; color: #fff; }
        .sidebar-brand-sub { font-size: 12px; font-weight: 500; color: rgba(255,255,255,.45); margin-top: 2px; }
        .sidebar-divider { height: 1px; background: rgba(255,255,255,0.1); margin: 20px 0; }
        .sidebar-nav { display: flex; flex-direction: column; gap: 4px; margin-bottom: auto; }
        .sidebar-group-label {
            font-size: 10px; font-weight: 700; letter-spacing: 0.12em;
            text-transform: uppercase; color: rgba(255,255,255,0.38);
            padding: 0 10px; margin: 10px 0 4px;
        }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 12px;
            text-decoration: none; font-size: 14px; font-weight: 600;
            color: rgba(255,255,255,0.75);
            transition: background 0.18s, color 0.18s, border-color 0.18s;
            border-left: 3px solid transparent;
            position: relative;
        }
        .sidebar-nav a:hover, .sidebar-nav a:focus {
            background: rgba(255,255,255,0.1); color: #fff; text-decoration: none;
        }
        .sidebar-nav a.active {
            background: rgba(255,255,255,0.14); color: #fff;
            border-left-color: #fff;
            font-weight: 700;
        }
        .sidebar-nav a .icon { width: 20px; display: inline-flex; justify-content: center; flex-shrink: 0; }
        .sidebar-nav a .nav-badge {
            margin-left: auto; background: #fff; color: #dc2626 !important;
            font-size: 10px; font-weight: 800; padding: 2px 7px;
            border-radius: 999px; line-height: 1.4;
        }
        .sidebar-footer { margin-top: 24px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 18px; }
        .sidebar-admin-info { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
        .sidebar-admin-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: rgba(255,255,255,0.18);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 800; color: #fff; flex-shrink: 0;
        }
        .sidebar-admin-name { font-size: 13px; font-weight: 700; color: #fff; }
        .sidebar-admin-role { font-size: 11px; color: rgba(255,255,255,0.45); }
        .logout-button {
            display: inline-flex; align-items: center; gap: 10px;
            background: rgba(248,250,252,0.15); color: #fefefe;
            padding: 10px 18px; border-radius: 999px; font-weight: 600;
            text-decoration: none; transition: background 0.2s ease;
        }
        .logout-button:hover, .logout-button:focus { background: rgba(255,255,255,0.28); color: #fff; }
        .admin-main { flex: 1; padding: 40px 48px; }
        .main-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 32px; }
        .main-header h1 { margin: 0; font-size: 32px; font-weight: 700; color: #7f1d1d; }
        .header-summary { margin: 0; font-size: 14px; color: #6b7280; }
        .header-meta { display: flex; align-items: center; gap: 12px; font-size: 13px; color: #dc2626; font-weight: 600; }
        .flash-messages { display: flex; flex-direction: column; gap: 10px; margin-bottom: 24px; }
        .flash { display:flex; align-items:flex-start; gap:12px; border-radius:16px; padding:14px 16px; font-family:'Raleway',sans-serif; font-size:.85rem; font-weight:600; line-height:1.5; animation:flash-in .3s cubic-bezier(.34,1.56,.64,1); position:relative; }
        @keyframes flash-in { from{transform:translateY(-8px);opacity:0} to{transform:translateY(0);opacity:1} }
        .flash-icon { width:32px; height:32px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .flash-body { flex:1; }
        .flash-title { font-weight:700; font-size:.82rem; letter-spacing:.02em; margin-bottom:2px; }
        .flash-msg { font-weight:500; font-size:.8rem; }
        .flash-close { background:none; border:none; cursor:pointer; padding:2px 4px; border-radius:6px; font-size:.9rem; line-height:1; opacity:.55; color:inherit; flex-shrink:0; margin-top:1px; }
        .flash-close:hover { opacity:1; }
        .flash-success { background:#f0fdf4; border:1.5px solid #bbf7d0; color:#15803d; }
        .flash-success .flash-icon { background:#dcfce7; color:#16a34a; }
        .flash-error { background:#fff1f2; border:1.5px solid #fecdd3; color:#be123c; }
        .flash-error .flash-icon { background:#ffe4e6; color:#e11d48; }
        .summary-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .summary-card { background: #fff; border-radius: 20px; padding: 20px 22px; box-shadow: 0 4px 20px rgba(15,23,42,0.07); border: none; position: relative; overflow: hidden; }
        .summary-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; border-radius:20px 20px 0 0; }
        .summary-card.card-total::before  { background: linear-gradient(90deg,#ef4444,#f87171); }
        .summary-card.card-pending::before { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
        .summary-card.card-approved::before { background: linear-gradient(90deg,#10b981,#34d399); }
        .summary-card.card-declined::before { background: linear-gradient(90deg,#ef4444,#f87171); }
        .summary-card h2 { font-size: 13px; letter-spacing: 0.12em; text-transform: uppercase; color: #6b7280; margin: 0 0 10px; display:flex; align-items:center; gap:7px; }
        .summary-card h2 i { font-size:14px; }
        .summary-card.card-total h2 i   { color:#ef4444; }
        .summary-dot { width:9px; height:9px; border-radius:50%; flex-shrink:0; animation: summary-dot-pulse 1.6s ease-out infinite; }
        @keyframes summary-dot-pulse { 0%{box-shadow:0 0 0 0 var(--dot-glow,rgba(0,0,0,.4))} 70%{box-shadow:0 0 0 6px transparent} 100%{box-shadow:0 0 0 0 transparent} }
        .summary-card.card-pending .summary-dot  { background:#f59e0b; --dot-glow:rgba(245,158,11,.5); }
        .summary-card.card-approved .summary-dot { background:#10b981; --dot-glow:rgba(16,185,129,.5); }
        .summary-card.card-declined .summary-dot { background:#ef4444; --dot-glow:rgba(239,68,68,.5); }
        .summary-value { font-size: 34px; font-weight: 800; color: #1f2937; line-height:1; }
        .summary-caption { font-size: 13px; color: #9ca3af; margin-top: 8px; }
        .quick-actions { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:28px; }
        .quick-action-btn { display:inline-flex; align-items:center; gap:7px; padding:9px 18px; border-radius:12px; border:1.5px solid rgba(220,38,38,0.2); background:#fff; color:#dc2626; font-size:13px; font-weight:600; text-decoration:none; box-shadow:0 2px 8px rgba(220,38,38,0.07); transition:all .18s; cursor:pointer; }
        .quick-action-btn:hover { background:#dc2626; color:#fff; border-color:#dc2626; transform:translateY(-1px); }
        .quick-action-btn i { font-size:13px; }
        .event-progress { margin-top:8px; height:4px; border-radius:999px; background:#f1f5f9; overflow:hidden; }
        .event-progress-bar { height:100%; border-radius:999px; transition:width .6s ease; }
        .event-card.wedding .event-progress-bar { background:linear-gradient(90deg,#ec4899,#f472b6); }
        .event-card.baptism .event-progress-bar { background:linear-gradient(90deg,#14b8a6,#2dd4bf); }
        .event-card.funeral .event-progress-bar { background:linear-gradient(90deg,#475569,#64748b); }
        .event-type-tag { display:inline-block; padding:2px 8px; border-radius:999px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; }
        .event-type-tag.wedding  { background:rgba(244,114,182,0.14); color:#db2777; }
        .event-type-tag.baptism  { background:rgba(22,163,74,0.14);   color:#16a34a; }
        .event-type-tag.funeral  { background:rgba(100,116,139,0.14); color:#475569; }
        .overview-panels { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
        .overview-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 14px; }
        .overview-list li { display: flex; flex-direction: column; gap: 6px; padding: 14px 16px; border-radius: 16px; border: 1px solid rgba(148,163,184,0.18); background: #f8fafc; }
        .overview-list-primary { display: flex; justify-content: space-between; gap: 12px; align-items: center; }
        .overview-list-title { font-weight: 700; color: #1f2937; }
        .overview-list-name { font-size: 13px; color: #64748b; }
        .overview-list-meta { display: flex; flex-wrap: wrap; gap: 10px; font-size: 12px; color: #475569; align-items: center; }
        .overview-list-meta .badge { padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; letter-spacing: 0.02em; }
        .overview-link { display: inline-flex; align-items: center; gap: 8px; margin-top: 20px; font-weight: 600; color: #dc2626; text-decoration: none; }
        .overview-link i { transition: transform 0.2s ease; }
        .overview-link:hover i, .overview-link:focus i { transform: translateX(4px); }
        .section-card { background: #fff; border-radius: 24px; padding: 32px; box-shadow: 0 26px 60px rgba(15,23,42,0.08); margin-bottom: 32px; }
        .section-card.schedule-section-card { box-shadow: none; }
        .section-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; }
        .section-header h2 { margin: 0; font-size: 24px; color: #1f2937; }
        .section-header p { margin: 0; color: #6b7280; font-size: 14px; }
        .reservation-toolbar { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 24px; padding: 14px 20px; border: none; border-radius: 18px; background: #fff; box-shadow: 0 2px 12px rgba(15,23,42,0.07); }
        .reservation-filter-form { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
        .reservation-filter-form .form-group { margin: 0; display:flex; flex-direction:column; gap:5px; }
        .reservation-filter-form label { font-size: .7rem; font-weight: 700; color: #94a3b8; letter-spacing:.06em; text-transform:uppercase; }
        .reservation-filter-form input[type="date"] {
            min-width: 160px; padding: 9px 36px 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 10px;
            background: #f8fafc; font-family: inherit; font-size: .82rem; color: #374151; font-weight: 500;
            transition: border-color .15s, background .15s;
        }
        .reservation-filter-form input[type="date"]:focus { outline:none; border-color:#94a3b8; background:#fff; }
        /* Custom admin filter dropdown */
        .adm-csl { position:relative; min-width:170px; }
        .adm-csl-btn {
            width:100%; display:flex; align-items:center; justify-content:space-between; gap:10px;
            padding:9px 12px; border:1.5px solid #e2e8f0; border-radius:10px;
            background:#f8fafc; font-family:inherit; font-size:.82rem; color:#374151; font-weight:500;
            cursor:pointer; text-align:left; transition:border-color .15s, background .15s;
        }
        .adm-csl-btn:focus { outline:none; }
        .adm-csl-btn.is-open { border-color:#94a3b8; background:#fff; }
        .adm-csl-arrow { transition:transform .18s; color:#94a3b8; flex-shrink:0; }
        .adm-csl-btn.is-open .adm-csl-arrow { transform:rotate(180deg); }
        .adm-csl-label.adm-csl-placeholder { opacity:.55; }
        .priest-name-input { outline:none; transition:border-color .15s, box-shadow .15s; }
        .priest-name-input:focus { border-color:#dc2626; box-shadow:0 0 0 3px rgba(220,38,38,0.12); }
        .priest-add-btn { padding:7px 16px; border-radius:8px; border:none; background:#dc2626; color:#fff; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:background .15s; }
        .priest-add-btn:hover { background:#b91c1c; }
        .adm-csl-list {
            display:none; position:absolute; top:calc(100% + 6px); left:0; min-width:100%;
            background:#fff; border:1.5px solid #e2e8f0; border-radius:12px;
            box-shadow:0 8px 24px rgba(15,23,42,.1); z-index:999; overflow:hidden; padding:4px 0;
        }
        .adm-csl.is-open .adm-csl-list { display:block; }
        .adm-csl-list li {
            padding:9px 14px; font-size:.82rem; color:#374151; font-weight:500;
            cursor:pointer; list-style:none; transition:background .12s;
        }
        .adm-csl-list li:hover { background:#f8fafc; color:#7f1d1d; }
        .adm-csl-list li.is-selected { background:#fff1f2; color:#dc2626; font-weight:700; }
        .reservation-filter-actions { display: flex; gap: 8px; align-items: center; }
        .reservation-filter-actions .btn-primary { background:#dc2626; border-color:#dc2626; border-radius:10px; padding:9px 20px; font-size:.82rem; font-weight:700; }
        .reservation-filter-actions .btn-primary:hover { background:#b91c1c; border-color:#b91c1c; }
        .reservation-filter-actions .btn-primary:active,
        .reservation-filter-actions .btn-primary:focus,
        .reservation-filter-actions .btn-primary:not(:disabled):not(.disabled):active {
            background:#b91c1c; border-color:#b91c1c; box-shadow:0 0 0 .2rem rgba(220,38,38,.35);
        }
        .reservation-filter-actions .btn-link { font-size:.8rem; color:#94a3b8; font-weight:600; }
        .reservation-filter-summary { display: flex; flex-direction: column; gap: 2px; }
        .reservation-filter-summary strong { font-size: .92rem; font-weight: 800; color: #0f172a; }
        .reservation-filter-summary span { font-size: .75rem; color: #94a3b8; }
        .schedule-toolbar { align-items: center; gap: 20px; }
        .schedule-filter-form { display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; }
        .schedule-filter-form .form-group { margin: 0; }
        .schedule-filter-form label { font-size: 13px; font-weight: 600; color: #4b5563; margin-bottom: 6px; }
        .schedule-meta { display: flex; flex-direction: column; gap: 6px; font-size: 14px; color: #475569; }
        .schedule-meta strong { color: #111827; font-size: 16px; }
        .schedule-event-counts { display: flex; flex-wrap: wrap; gap: 10px; margin: 0 0 16px; padding: 0; list-style: none; }
        .schedule-event-counts li { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 999px; background: rgba(220,38,38,0.08); color: #991b1b; font-weight: 600; font-size: 13px; }
        .schedule-list { display: flex; flex-direction: column; gap: 16px; }
        .schedule-item { background: #fff; border-radius: 18px; padding: 20px; box-shadow: 0 14px 32px rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.12); }
        .schedule-item-header { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 12px; margin-bottom: 12px; font-size: 15px; color: #1f2937; font-weight: 600; }
        .schedule-item-body { display: flex; flex-direction: column; gap: 10px; font-size: 13px; color: #4b5563; }
        .schedule-item-body span { display: flex; align-items: center; gap: 8px; }
        .schedule-item-notes { margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(148,163,184,0.4); color: #475569; white-space: pre-line; }
        .reservation-date-field { display: flex; flex-direction: column; }
        .reservation-date-field.is-hidden { display: none; }
        /* Mini calendar */
        .adm-cal-wrap { position:relative; }
        .adm-cal-popup { position:absolute; top:calc(100% + 6px); left:0; z-index:1200; background:#fff; border:1.5px solid #e2e8f0; border-radius:16px; box-shadow:0 12px 40px rgba(15,23,42,.13); padding:14px; width:252px; }
        .adm-cal-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
        .adm-cal-nav { background:none; border:none; cursor:pointer; width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#64748b; transition:background .12s; }
        .adm-cal-nav:hover { background:#f1f5f9; color:#0f172a; }
        .adm-cal-month-label { font-size:.82rem; font-weight:800; color:#0f172a; display:flex; gap:4px; align-items:center; }
        .adm-cal-hdr-btn { background:none; border:none; cursor:pointer; font-size:.82rem; font-weight:800; color:#0f172a; padding:2px 6px; border-radius:6px; transition:background .12s; font-family:inherit; }
        .adm-cal-hdr-btn:hover { background:#f1f5f9; color:#dc2626; }
        .adm-cal-month-grid,.adm-cal-year-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:4px; margin-bottom:4px; }
        .adm-cal-mcell,.adm-cal-ycell { padding:8px 4px; border:none; background:none; border-radius:8px; font-family:inherit; font-size:.76rem; font-weight:500; color:#374151; cursor:pointer; text-align:center; transition:background .12s,color .12s; }
        .adm-cal-mcell:hover,.adm-cal-ycell:hover { background:#fff1f2; color:#dc2626; }
        .adm-cal-mcell.is-today,.adm-cal-ycell.is-today { font-weight:800; color:#dc2626; }
        .adm-cal-mcell.is-selected,.adm-cal-ycell.is-selected { background:#dc2626; color:#fff; font-weight:700; }
        .adm-cal-days-row { display:grid; grid-template-columns:repeat(7,1fr); margin-bottom:4px; }
        .adm-cal-days-row span { text-align:center; font-size:.6rem; font-weight:700; color:#94a3b8; padding:4px 0; letter-spacing:.04em; }
        .adm-cal-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:2px; }
        .adm-cal-day { width:32px; height:32px; border:none; background:none; border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:500; color:#374151; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background .12s, color .12s; margin:0 auto; }
        .adm-cal-day:hover { background:#fff1f2; color:#dc2626; }
        .adm-cal-day.is-today { font-weight:800; color:#dc2626; }
        .adm-cal-day.is-today::after { content:''; display:block; width:4px; height:4px; border-radius:50%; background:#dc2626; position:absolute; bottom:3px; left:50%; transform:translateX(-50%); }
        .adm-cal-day { position:relative; }
        .adm-cal-day.is-selected { background:#dc2626 !important; color:#fff !important; font-weight:700; }
        .adm-cal-day.is-other-month { color:#cbd5e1; }
        .adm-cal-day.is-other-month:hover { background:#f8fafc; color:#94a3b8; }
        .adm-cal-footer { display:flex; justify-content:space-between; margin-top:10px; padding-top:10px; border-top:1px solid #f1f5f9; gap:6px; }
        .adm-cal-today-btn, .adm-cal-clear-btn { flex:1; padding:6px; border-radius:8px; border:1.5px solid #e2e8f0; background:#f8fafc; font-family:inherit; font-size:.72rem; font-weight:700; color:#64748b; cursor:pointer; transition:all .12s; }
        .adm-cal-today-btn:hover { background:#fff1f2; border-color:#fecaca; color:#dc2626; }
        .adm-cal-clear-btn:hover { background:#f1f5f9; color:#374151; }
        .status-columns { display: flex; flex-direction: column; gap: 32px; }
        .status-column { position: relative; }
        .status-column-header { display:flex; align-items:center; gap:10px; margin-bottom:14px; padding-bottom:12px; border-bottom:2px solid #f1f5f9; }
        .status-column-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
        .status-column-pending   .status-column-dot { background: linear-gradient(135deg,#FFD700,#FFE566); }
        .status-column-approved  .status-column-dot { background: linear-gradient(135deg,#10b981,#34d399); }
        .status-column-declined  .status-column-dot { background: linear-gradient(135deg,#ef4444,#dc2626); }
        .status-column h3 { font-size: 17px; margin: 0; color: #0f172a; font-weight: 800; }
        .status-column > p { margin: 0 0 14px; font-size: 13px; color: #94a3b8; }
        .status-column .empty-state { font-style: italic; color: #94a3b8; font-size:14px; }
        /* ── Status tab filter ── */
        .res-tab-bar { display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
        .res-tab-btn { display:inline-flex; align-items:center; gap:7px; padding:8px 18px; border-radius:999px; border:1.5px solid #e2e8f0; background:transparent; color:#64748b; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; transition:all .18s; }
        .res-tab-btn:hover { border-color:#94a3b8; color:#374151; }
        .res-tab-btn.active-all      { background:#dc2626; border-color:#dc2626; color:#fff; }
        .res-tab-btn.active-pending  { background:#FFD700; border-color:#FFD700; color:#fff; }
        .res-tab-btn.active-approved { background:#16a34a; border-color:#16a34a; color:#fff; }
        .res-tab-btn.active-declined { background:#dc2626; border-color:#dc2626; color:#fff; }
        .res-tab-count { display:inline-flex; align-items:center; justify-content:center; min-width:18px; height:18px; padding:0 5px; border-radius:999px; font-size:.65rem; font-weight:800; background:#fff; color:#dc2626; }
        .res-tab-btn:not([class*="active"]) .res-tab-count { background:#fff; color:#dc2626; }
        /* ── Reservation card – horizontal light layout ── */
        .reservation-card { background: transparent; border-radius: 16px; margin-bottom: 12px; box-shadow: 0 2px 12px rgba(15,23,42,.07), 0 0 0 1px rgba(15,23,42,.05); display:flex; overflow:visible; transition: box-shadow .2s, transform .15s; }
        .rcard-stripe { border-radius: 16px 0 0 16px; }
        .reservation-card:last-child { margin-bottom: 0; }
        .reservation-card:hover { box-shadow: 0 8px 28px rgba(127,29,29,.1), 0 0 0 1px rgba(127,29,29,.08); }
        .res-card-hidden { display:none !important; }
        .rcard-stripe { width: 6px; flex-shrink: 0; }
        .rcard-stripe-pending  { background: linear-gradient(180deg,#FFD700,#FFE566); }
        .rcard-stripe-approved { background: linear-gradient(180deg,#16a34a,#4ade80); }
        .rcard-stripe-declined { background: linear-gradient(180deg,#dc2626,#f87171); }
        /* Three explicit columns */
        .rcard-identity  { flex: 1.2; min-width:0; padding: 20px 22px; border-right: 1px solid #f1f5f9; display:flex; flex-direction:column; background:#fff; }
        .rcard-schedule  { flex: 1.4; min-width:0; padding: 20px 22px; border-right: 1px solid #f1f5f9; display:flex; flex-direction:column; background:#fafafa; }
        .rcard-actions-col { flex: 1.4; min-width:0; padding: 20px 22px; display:flex; flex-direction:column; border-radius: 0 16px 16px 0; background:#fff; }
        .rcard-col-label { font-size:.6rem; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:#94a3b8; margin-bottom:14px; }
        /* Identity column */
        .rcard-badges { display:flex; flex-wrap:wrap; gap:5px; align-items:center; margin-bottom:10px; }
        .rcard-badges .badge { padding:3px 9px; border-radius:999px; font-size:.62rem; font-weight:700; letter-spacing:.03em; vertical-align:middle; line-height:1.5; }
        /* Glassy status pills */
        .status-pill { display:inline-flex; align-items:center; gap:6px; padding:3px 10px; border-radius:999px; font-size:.62rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase; vertical-align:middle; line-height:1; }
        .spill-pending  { background:rgba(234,179,8,.12);  border:1.5px solid rgba(234,179,8,.35);  color:#854d0e; }
        .spill-approved { background:rgba(22,163,74,.1);   border:1.5px solid rgba(22,163,74,.3);   color:#15803d; }
        .spill-declined { background:rgba(220,38,38,.1);   border:1.5px solid rgba(220,38,38,.28);  color:#b91c1c; }
        .spill-dot { width:7px; height:7px; min-width:7px; min-height:7px; aspect-ratio:1; border-radius:50%; flex-shrink:0; flex-grow:0; position:relative; }
        .spill-pending  .spill-dot { background:#eab308; box-shadow:0 0 0 0 rgba(234,179,8,.6); animation:pulse-dot-y 1.8s cubic-bezier(.4,0,.6,1) infinite; }
        .spill-approved .spill-dot { background:#16a34a; box-shadow:0 0 0 0 rgba(22,163,74,.6);  animation:pulse-dot-g 1.8s cubic-bezier(.4,0,.6,1) infinite; }
        .spill-declined .spill-dot { background:#dc2626; box-shadow:0 0 0 0 rgba(220,38,38,.6);  animation:pulse-dot-r 1.8s cubic-bezier(.4,0,.6,1) infinite; }
        .spill-done { background:rgba(100,116,139,.1); border:1.5px solid rgba(100,116,139,.28); color:#475569; }
        .spill-done .spill-dot { background:#64748b; }
        @keyframes pulse-dot-y { 0%,100%{box-shadow:0 0 0 0 rgba(234,179,8,.6)} 50%{box-shadow:0 0 0 5px rgba(234,179,8,0)} }
        @keyframes pulse-dot-g { 0%,100%{box-shadow:0 0 0 0 rgba(22,163,74,.6)}  50%{box-shadow:0 0 0 5px rgba(22,163,74,0)}  }
        @keyframes pulse-dot-r { 0%,100%{box-shadow:0 0 0 0 rgba(220,38,38,.6)}  50%{box-shadow:0 0 0 5px rgba(220,38,38,0)}  }
        .rcard-name { font-size:1.05rem; font-weight:800; color:#0f172a; margin:0 0 2px; }
        .rcard-id-span { font-weight:400; color:#94a3b8; font-size:.8rem; margin-left:4px; }
        .rcard-info-rows { display:flex; flex-direction:column; gap:6px; margin-top:12px; }
        .rcard-info-row { display:flex; align-items:center; gap:8px; font-size:.78rem; color:#64748b; }
        .rcard-info-row i { color:#94a3b8; width:13px; text-align:center; flex-shrink:0; }
        .rcard-info-row a { color:#7f1d1d; font-weight:600; text-decoration:none; }
        .rcard-info-row a:hover { text-decoration:underline; }
        .rcard-rel { display:inline-flex; align-items:center; padding:3px 10px; border-radius:999px; font-size:.65rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; margin-top:12px; align-self:flex-start; }
        .rcard-rel-soon  { background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; }
        .rcard-rel-past  { background:#f8fafc; color:#94a3b8; border:1px solid #e2e8f0; }
        /* Schedule column */
        .sched-item { display:flex; align-items:center; gap:12px; margin-bottom:14px; }
        .sched-icon-box { width:32px; height:32px; border-radius:9px; background:#fff1f2; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#dc2626; }
        .sched-val { font-size:.85rem; font-weight:700; color:#0f172a; line-height:1.2; }
        .sched-sub { font-size:.7rem; color:#94a3b8; margin-top:1px; }
        .officiant-form { display:flex; flex-direction:column; gap:4px; }
        .officiant-form label { font-size:.7rem; font-weight:600; color:#94a3b8; }
        .officiant-form .adm-csl { min-width:0; width:100%; }
        .officiant-static { font-size:.82rem; font-weight:600; color:#374151; }
        /* Actions column */
        .admin-note-prev { background:#fff1f2; border-left:3px solid #dc2626; border-radius:0 8px 8px 0; padding:8px 12px; font-size:.75rem; color:#64748b; line-height:1.5; margin-bottom:2px; }
        .admin-note-prev-label { font-size:.6rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#dc2626; margin-bottom:3px; }
        .admin-note-textarea { width:100%; flex:1; padding:10px 12px; border:1.5px solid #e2e8f0; border-radius:10px; background:#f8fafc; color:#374151; font-family:inherit; font-size:.78rem; resize:none; min-height:68px; line-height:1.5; }
        .admin-note-textarea:focus { outline:none; border-color:#94a3b8; background:#fff; }
        .admin-note-textarea::placeholder { color:#cbd5e1; }
        .muted-text { color:#94a3b8; font-style:italic; }
        .reservation-notes { font-size:.75rem; line-height:1.5; color:#64748b; white-space:pre-wrap; margin-top:6px; }
        /* Cancel section (inline on card, hidden) */
        .cancel-section { display:none; }
        /* Cancel overlay panel */
        #cancel-overlay-backdrop { display:none; position:fixed; inset:0; background:rgba(6,13,26,.75); backdrop-filter:blur(6px); z-index:1040; }
        #cancel-overlay-panel { display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%) scale(.96); opacity:0; z-index:1050; width:min(560px,92vw); height:auto; max-height:88vh; border-radius:22px; box-shadow:0 40px 90px rgba(0,0,0,.7),0 0 0 1px rgba(255,255,255,.07); flex-direction:column; background:#0f172a; overflow:hidden; font-family:'Raleway',system-ui,sans-serif; }
        .cpanel-header { padding:16px 22px; display:flex; align-items:center; gap:9px; flex-shrink:0; border-bottom:1px solid rgba(255,255,255,.07); background:rgba(255,255,255,.04); }
        .cpanel-htitle { flex:1; min-width:0; }
        .cpanel-close { width:32px; height:32px; border-radius:9px; background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.1); cursor:pointer; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,.55); flex-shrink:0; transition:background .15s,color .15s; }
        .cpanel-close:hover { background:rgba(255,255,255,.12); color:#fff; }
        .cpanel-section { padding:18px 22px 4px; flex-shrink:0; }
        .cpanel-eyebrow { font-size:.62rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:#f87171; margin-bottom:5px; }
        .cpanel-title { font-size:1.15rem; font-weight:800; color:#fff; }
        .cpanel-body { background:transparent; overflow-y:auto; overflow-x:hidden; flex:1; padding:14px 22px 18px; display:flex; flex-direction:column; gap:10px; min-height:0; }
        .creq-card { background:rgba(255,255,255,.03); border-radius:16px; border:1px solid rgba(255,255,255,.08); flex-shrink:0; }
        .creq-inner { padding:16px 18px 14px; display:flex; flex-direction:column; gap:12px; }
        .creq-top { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
        .creq-name { font-size:.95rem; font-weight:800; color:#fff; }
        .creq-detail { font-size:.72rem; color:rgba(255,255,255,.4); margin-top:3px; }
        .creq-reason { background:rgba(220,38,38,.08); border:1px solid rgba(220,38,38,.25); border-radius:12px; padding:11px 14px; }
        .creq-reason-label { font-size:.58rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#f87171; margin-bottom:5px; display:flex; align-items:center; gap:5px; }
        .creq-reason-text { font-size:.8rem; color:rgba(255,255,255,.75); line-height:1.55; font-weight:500; }
        .creq-reason-empty { font-size:.8rem; color:rgba(248,113,113,.6); font-style:italic; }
        .creq-actions { display:flex; border-top:1px solid rgba(255,255,255,.07); overflow:hidden; border-radius:0 0 14px 14px; }
        .creq-actions form { flex:1; display:flex; margin:0; }
        .creq-btn { flex:1; padding:11px 8px !important; border:none !important; border-radius:0 !important; cursor:pointer; font-family:inherit !important; font-size:.78rem !important; font-weight:700 !important; display:flex !important; align-items:center; justify-content:center; gap:6px; transition:background .15s; box-shadow:none !important; text-decoration:none; line-height:1; }
        .creq-btn-approve { background:rgba(34,197,94,.08) !important; color:#4ade80 !important; border-right:1px solid rgba(255,255,255,.07) !important; }
        .creq-btn-approve:hover { background:rgba(34,197,94,.16) !important; color:#86efac !important; }
        .creq-btn-deny { background:rgba(220,38,38,.08) !important; color:#f87171 !important; }
        .creq-btn-deny:hover { background:rgba(220,38,38,.16) !important; color:#fca5a5 !important; }
        .cpanel-footer { background:rgba(255,255,255,.03); border-top:1px solid rgba(255,255,255,.07); padding:12px 18px; flex-shrink:0; display:flex; align-items:center; gap:10px; }
        .cpanel-footer-icon { width:28px; height:28px; border-radius:8px; background:rgba(245,158,11,.12); color:#fbbf24; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }
        .cpanel-footer-note { font-size:.7rem; color:rgba(255,255,255,.4); line-height:1.4; }
        /* ── Reservation Detail Modal ── */
        #rdm-backdrop { display:none; position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(4px); z-index:1040; }
        #rdm-backdrop.is-open { display:block; }
        #rdm-modal { display:none; position:fixed; inset:0; z-index:1041; align-items:center; justify-content:center; padding:20px; }
        #rdm-modal.is-open { display:flex; }
        .rdm-panel { width:100%; max-width:520px; max-height:88vh; background:#0f172a; border-radius:20px; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 40px 90px rgba(0,0,0,.7),0 0 0 1px rgba(255,255,255,.07); animation:rdm-in .22s cubic-bezier(.34,1.2,.64,1); font-family:'Raleway',system-ui,sans-serif; }
        @keyframes rdm-in { from{opacity:0;transform:scale(.95) translateY(10px)}to{opacity:1;transform:scale(1) translateY(0)} }
        .rdm-head { padding:18px 22px 20px; flex-shrink:0; position:relative; overflow:hidden; }
        .rdm-eyebrow { font-size:9px; letter-spacing:2.5px; text-transform:uppercase; font-weight:700; color:#f87171; margin-bottom:8px; position:relative; z-index:1; }
        .rdm-name { font-size:20px; font-weight:800; color:#fff; margin-bottom:3px; position:relative; z-index:1; font-family:'Raleway',sans-serif; }
        .rdm-sub { font-size:12px; color:rgba(255,255,255,.4); margin-bottom:12px; position:relative; z-index:1; }
        .rdm-badges { display:flex; align-items:center; gap:6px; flex-wrap:wrap; position:relative; z-index:1; }
        .rdm-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 12px; border-radius:999px; font-size:9.5px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; line-height:1; background:rgba(255,255,255,.08); color:#fff; border:1.5px solid rgba(255,255,255,.16); }
        .rdm-badge > span:last-child { line-height:1; display:inline-block; }
        .rdm-badge-pending  { background:rgba(245,158,11,.14); color:#fbbf24; border-color:rgba(245,158,11,.3); }
        .rdm-badge-approved { background:rgba(34,197,94,.14); color:#4ade80; border-color:rgba(34,197,94,.3); }
        .rdm-badge-declined { background:rgba(220,38,38,.14); color:#f87171; border-color:rgba(220,38,38,.3); }
        .rdm-badge-wedding  { background:rgba(244,114,182,.14); color:#f9a8d4; border-color:rgba(244,114,182,.3); }
        .rdm-badge-baptism  { background:rgba(74,222,128,.14); color:#86efac; border-color:rgba(74,222,128,.3); }
        .rdm-badge-funeral  { background:rgba(148,163,184,.14); color:#cbd5e1; border-color:rgba(148,163,184,.3); }
        .rdm-badge-urgent   { background:rgba(225,29,72,.16); color:#fb7185; border-color:rgba(225,29,72,.32); }
        .rdm-badge-icon { display:inline-flex; align-items:center; justify-content:center; width:9px; line-height:1; flex-shrink:0; }
        .rdm-badge svg { display:block; flex-shrink:0; }
        @keyframes rdm-dot-pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.6)} }
        .rdm-pulse-dot { width:7px; height:7px; border-radius:50%; background:currentColor; flex-shrink:0; align-self:center; animation:rdm-dot-pulse 1.5s ease-in-out infinite; }
        .rdm-badges .status-pill { padding:5px 12px; font-size:9.5px; letter-spacing:1.2px; }
        .spill-label { display:inline-block; position:relative; top:1px; line-height:1; }
        .rdm-badge-icon.icon-baptism { width:12px; font-size:13px; }
        .rdm-det-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
        .rdm-det-cell { background:rgba(220,38,38,.08); border:1px solid rgba(220,38,38,.25); border-radius:8px; padding:10px 12px; }
        .rdm-det-full { grid-column:1/-1; }
        .rdm-det-label { font-size:8.5px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; color:#f87171; margin-bottom:4px; }
        .rdm-det-value { font-size:12.5px; font-weight:600; color:#fff; }
        .rdm-body { flex:1; overflow-y:auto; scroll-behavior:smooth; -webkit-overflow-scrolling:touch; }
        .rdm-section { padding:16px 22px; border-bottom:1px solid rgba(255,255,255,.07); }
        .rdm-section:last-child { border-bottom:none; }
        .rdm-two-col { display:grid; grid-template-columns:1fr 1fr; gap:0; }
        .rdm-two-col > div:first-child { padding-right:14px; border-right:1px solid rgba(255,255,255,.07); }
        .rdm-two-col > div:last-child { padding-left:14px; }
        .rdm-sec-title { font-size:9px; letter-spacing:.18em; text-transform:uppercase; font-weight:800; color:rgba(255,255,255,.4); margin-bottom:12px; }
        .rdm-att-link { color:#f87171; text-decoration:none; font-size:12px; font-weight:600; }
        .rdm-att-link:hover { text-decoration:underline; color:#fca5a5; }
        .rdm-row { display:flex; align-items:flex-start; gap:10px; margin-bottom:10px; }
        .rdm-row:last-child { margin-bottom:0; }
        .rdm-icon { width:28px; height:28px; border-radius:8px; background:rgba(220,38,38,.14); display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; color:#f87171; }
        .rdm-label { font-size:9.5px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:rgba(255,255,255,.4); margin-bottom:2px; }
        .rdm-value { font-size:13px; font-weight:600; color:#fff; line-height:1.4; }
        .rdm-value-sub { font-size:11px; color:rgba(255,255,255,.35); margin-top:1px; }
        .rdm-notes { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); border-radius:10px; padding:11px 14px; font-size:12px; line-height:1.65; color:rgba(255,255,255,.75); white-space:pre-line; }
        .rdm-att-row { display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:8px; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); margin-bottom:7px; }
        .rdm-att-row:last-child { margin-bottom:0; }
        .rdm-att-name { font-size:12px; font-weight:600; color:#fff; }
        /* ── Parish confirm modal ── */
        #adm-cfm-overlay{position:fixed;inset:0;background:rgba(6,13,26,.75);backdrop-filter:blur(6px);z-index:99999;display:none;align-items:center;justify-content:center;padding:20px}
        #adm-cfm-overlay.is-open{display:flex}
        .adm-cfm-modal{width:100%;max-width:440px;background:#0f172a;border-radius:22px;overflow:hidden;box-shadow:0 40px 90px rgba(0,0,0,.7),0 0 0 1px rgba(255,255,255,.07);animation:adm-cfm-pop .5s cubic-bezier(.34,1.56,.64,1) both;font-family:'Raleway',system-ui,sans-serif}
        @keyframes adm-cfm-pop{from{opacity:0;transform:scale(.88) translateY(28px)}to{opacity:1;transform:scale(1) translateY(0)}}
        .adm-cfm-hdr{background:rgba(255,255,255,.04);border-bottom:1px solid rgba(255,255,255,.07);padding:15px 22px;display:flex;align-items:center;gap:10px}
        .adm-cfm-cross{width:18px;height:18px;flex-shrink:0}
        .adm-cfm-parish{font-size:.67rem;font-weight:800;letter-spacing:.11em;text-transform:uppercase;color:#fff;white-space:nowrap}
        .adm-cfm-dot{width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.22);flex-shrink:0}
        .adm-cfm-loc{font-size:.67rem;color:rgba(255,255,255,.32);letter-spacing:.04em;white-space:nowrap}
        .adm-cfm-body{padding:40px 32px 36px;display:flex;flex-direction:column;align-items:center;text-align:center;position:relative;overflow:hidden}
        .adm-cfm-glow{position:absolute;width:290px;height:290px;background:radial-gradient(circle,rgba(220,38,38,.14) 0%,transparent 70%);top:-72px;left:50%;transform:translateX(-50%);pointer-events:none;animation:adm-cfm-pulse 3s ease-in-out infinite}
        @keyframes adm-cfm-pulse{0%,100%{opacity:.7;transform:translateX(-50%) scale(1)}50%{opacity:1;transform:translateX(-50%) scale(1.15)}}
        .adm-cfm-icon{position:relative;width:82px;height:82px;margin-bottom:24px;z-index:2}
        .adm-cfm-ring{position:absolute;inset:-5px;border-radius:50%;background:conic-gradient(rgba(220,38,38,.55) 0%,rgba(252,165,165,.3) 40%,transparent 58%);animation:adm-cfm-spin 3s linear infinite}
        @keyframes adm-cfm-spin{to{transform:rotate(360deg)}}
        .adm-cfm-inner{position:absolute;inset:0;border-radius:50%;background:rgba(220,38,38,.1);border:1.5px solid rgba(220,38,38,.25);display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px);animation:adm-cfm-ipop .5s .15s cubic-bezier(.34,1.56,.64,1) both}
        @keyframes adm-cfm-ipop{from{transform:scale(0)}to{transform:scale(1)}}
        .adm-cfm-chk{stroke:#fca5a5;stroke-width:2.6;stroke-linecap:round;stroke-linejoin:round;fill:none;stroke-dasharray:50;stroke-dashoffset:50;animation:adm-cfm-draw .7s .45s cubic-bezier(.4,0,.2,1) forwards}
        .adm-cfm-modal.is-success .adm-cfm-glow{background:radial-gradient(circle,rgba(34,197,94,.14) 0%,transparent 70%)}
        .adm-cfm-modal.is-success .adm-cfm-ring{background:conic-gradient(rgba(34,197,94,.55) 0%,rgba(134,239,172,.3) 40%,transparent 58%)}
        .adm-cfm-modal.is-success .adm-cfm-inner{background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.25)}
        .adm-cfm-modal.is-success .adm-cfm-chk{stroke:#86efac}
        @keyframes adm-cfm-draw{to{stroke-dashoffset:0}}
        .adm-cfm-title{font-size:1.5rem;font-weight:900;color:#fff;letter-spacing:-.03em;margin-bottom:10px;z-index:2;animation:adm-cfm-up .4s .5s both}
        .adm-cfm-sub{font-size:.86rem;color:rgba(255,255,255,.45);line-height:1.68;max-width:270px;margin-bottom:28px;z-index:2;animation:adm-cfm-up .4s .6s both}
        @keyframes adm-cfm-up{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .adm-cfm-btns{display:flex;gap:10px;width:100%;margin-top:16px;z-index:2;animation:adm-cfm-up .4s .7s both}
        .adm-cfm-cancel{flex:1;padding:13px;border-radius:11px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);font-family:'Raleway',system-ui,sans-serif;font-size:.86rem;font-weight:700;cursor:pointer;transition:all .15s}
        .adm-cfm-cancel:hover{background:rgba(255,255,255,.12);color:#fff}
        .adm-cfm-ok{flex:1.5;padding:13px;border-radius:11px;border:none;background:#dc2626;color:#fff;font-family:'Raleway',system-ui,sans-serif;font-size:.86rem;font-weight:800;cursor:pointer;box-shadow:0 6px 20px rgba(220,38,38,.4);transition:all .15s;letter-spacing:.02em}
        .adm-cfm-ok:hover{background:#b91c1c;transform:translateY(-1px)}
        .adm-cfm-particle{position:absolute;border-radius:50%;pointer-events:none;animation:adm-cfm-float 4s ease-in infinite;opacity:0}
        @keyframes adm-cfm-float{0%{opacity:0;transform:translateY(0) scale(0)}20%{opacity:.45}100%{opacity:0;transform:translateY(-200px) scale(1.1)}}
        /* Deny Cancellation modal (dark parish-style) */
        .dcm-overlay{position:fixed;inset:0;background:rgba(6,13,26,.75);backdrop-filter:blur(6px);z-index:99999;display:none;align-items:center;justify-content:center;padding:20px}
        .dcm-overlay.is-open{display:flex;animation:ps-fade-in .2s ease both}
        @keyframes ps-fade-in{from{opacity:0}to{opacity:1}}
        .dcm-modal{width:100%;max-width:440px;max-height:88vh;background:#0f172a;border-radius:22px;overflow:hidden;box-shadow:0 40px 90px rgba(0,0,0,.7),0 0 0 1px rgba(255,255,255,.07);font-family:'Raleway',system-ui,sans-serif;animation:adm-cfm-pop .4s cubic-bezier(.34,1.56,.64,1) both;display:flex;flex-direction:column;position:relative}
        .dcm-scroll{overflow-y:auto;flex:1 1 auto;min-height:0}
        .dcm-label{font-size:.68rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:10px;display:block;text-align:left}
        .dcm-textarea{width:100%;background:rgba(255,255,255,.04);border:1.5px solid rgba(255,255,255,.12);border-radius:12px;color:#fff;font-family:inherit;font-size:.84rem;padding:12px 14px;resize:none;transition:border-color .15s,background .15s}
        .dcm-textarea::placeholder{color:rgba(255,255,255,.3)}
        .dcm-textarea:focus{outline:none;border-color:#f87171;background:rgba(255,255,255,.06)}
        .rdm-att-label { font-size:10.5px; color:rgba(255,255,255,.35); }
        .rdm-footer { padding:13px 22px; border-top:1px solid rgba(255,255,255,.07); background:#0f172a; flex-shrink:0; display:flex; gap:8px; }
        .rdm-foot-btn { flex:1; padding:10px 14px; border-radius:999px; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; border:none; display:inline-flex; align-items:center; justify-content:center; gap:6px; transition:background .15s; }
        .rdm-approve { background:#16a34a; color:#fff; }
        .rdm-approve:hover { background:#15803d; }
        .rdm-decline { background:#dc2626; color:#fff; }
        .rdm-decline:hover { background:#b91c1c; }
        .rdm-close-btn { background:rgba(255,255,255,.06); color:rgba(255,255,255,.6); flex:0 0 auto; padding:10px 20px; }
        .rdm-close-btn:hover { background:rgba(255,255,255,.12); color:#fff; }
        /* Bottom action row */
        .rcard-bottom { display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-top:auto; padding-top:12px; border-top:1px solid #f1f5f9; }
        .rcard-status-btns { display:flex; flex-wrap:wrap; gap:6px; align-items:center; flex-shrink:0; }
        .rcard-status-btns form { margin:0; }
        .rcard-status-btns .btn { border-radius:999px; padding:6px 16px; font-size:.75rem; font-weight:700; display:inline-flex !important; align-items:center; gap:5px; }
        .rcard-status-btns .btn-success { background:#16a34a !important; border-color:#16a34a !important; color:#fff !important; }
        .rcard-status-btns .btn-success:hover { background:#15803d !important; border-color:#15803d !important; }
        .rcard-status-btns .btn-danger { background:#dc2626 !important; border-color:#dc2626 !important; color:#fff !important; }
        .rcard-status-btns .btn-danger:hover { background:#b91c1c !important; border-color:#b91c1c !important; }
        .rcard-status-btns .btn-secondary { background:#f1f5f9; border-color:#e2e8f0; color:#64748b; }
        .view-link { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:999px; border:1.5px solid rgba(127,29,29,.25); color:#7f1d1d; font-size:.75rem; font-weight:600; text-decoration:none; transition:all .18s; flex-shrink:0; background:none; cursor:pointer; font-family:inherit; }
        .view-link:hover { background:rgba(127,29,29,.06); }
        /* ── ANNOUNCEMENTS ── */
        .ann-stats-bar { display:flex; gap:0; background:#f8fafc; border:1px solid #e5e7eb; border-radius:14px; padding:14px 24px; margin-bottom:22px; }
        .ann-stat { display:flex; flex-direction:column; align-items:center; flex:1; gap:2px; }
        .ann-stat-num { font-size:24px; font-weight:800; color:#991b1b; line-height:1; }
        .ann-stat-num.live { color:#059669; }
        .ann-stat-num.hidden { color:#94a3b8; }
        .ann-stat-label { font-size:11px; color:#6b7280; font-weight:600; text-transform:uppercase; letter-spacing:0.08em; }
        .ann-stat-divider { width:1px; height:36px; background:#e5e7eb; margin:0 8px; align-self:center; }

        .announcement-grid { display:grid; grid-template-columns:minmax(260px,380px) 1fr; gap:28px; align-items:start; }
        .announcement-form { background:#fff; border-radius:18px; overflow:hidden; box-shadow:0 4px 24px rgba(15,23,42,0.07); border:1px solid rgba(148,163,184,0.18); position:sticky; top:24px; }
        .ann-form-header { background:linear-gradient(135deg,#dc2626,#b91c1c); padding:18px 22px; }
        .ann-form-header h3 { margin:0; color:#fff; font-size:15px; font-weight:700; }
        .ann-form-header p { margin:4px 0 0; color:rgba(255,255,255,0.75); font-size:12px; }
        .ann-form-body { padding:22px; }
        .ann-form-body .form-group { margin-bottom:16px; }
        .ann-form-body .form-group label { font-weight:600; color:#374151; font-size:13px; margin-bottom:5px; display:flex; justify-content:space-between; }
        .ann-form-body .form-group label span { font-weight:400; color:#9ca3af; font-size:12px; }
        .ann-form-body .form-control { border-radius:10px; border:1.5px solid #e5e7eb; font-size:14px; padding:9px 12px; }
        .ann-form-body .form-control:focus { border-color:#ef4444; box-shadow:0 0 0 3px rgba(220,38,38,0.1); }
        .ann-form-body .adm-csl { min-width:0; width:100%; }
        .ann-form-body textarea.form-control { resize:vertical; min-height:110px; }
        .ann-form-body select.form-control { appearance:none; -webkit-appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 14px center; padding-right:36px; }
        .ann-visibility-toggle { display:flex; align-items:center; gap:10px; background:#f8fafc; border:1.5px solid #e5e7eb; border-radius:10px; padding:10px 14px; margin-bottom:16px; cursor:pointer; }
        .ann-visibility-toggle input[type=checkbox] { width:16px; height:16px; accent-color:#dc2626; cursor:pointer; }
        .ann-visibility-toggle-label { font-size:13px; font-weight:600; color:#374151; margin:0; cursor:pointer; }
        .ann-visibility-toggle-sub { font-size:11px; color:#6b7280; display:block; font-weight:400; }
        .ann-publish-btn { width:100%; padding:11px; border-radius:10px; font-weight:700; font-size:14px; background:linear-gradient(135deg,#dc2626,#b91c1c); border:none; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; }
        .ann-publish-btn:hover { opacity:0.9; }

        .announcement-list { display:flex; flex-direction:column; gap:16px; }
        .announcement-item { background:#fff; border-radius:16px; border:1px solid rgba(148,163,184,0.2); overflow:hidden; box-shadow:0 2px 12px rgba(15,23,42,0.05); display:flex; }
        .ann-accent { width:5px; flex-shrink:0; }
        .ann-accent.live { background:linear-gradient(180deg,#10b981,#34d399); }
        .ann-accent.hidden { background:#e2e8f0; }
        .ann-content { flex:1; padding:18px 20px; min-width:0; }
        .ann-header-row { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:8px; }
        .ann-title-block { display:flex; flex-direction:column; gap:4px; min-width:0; }
        .ann-title { font-size:15px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .ann-date { font-size:12px; color:#94a3b8; display:inline-flex; align-items:center; gap:5px; }
        .ann-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 12px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; flex-shrink:0; letter-spacing:.3px; }
        .ann-badge.live { background:#dcfce7; color:#15803d; border:1.5px solid #bbf7d0; }
        .ann-badge.live::before { content:''; display:inline-block; width:7px; height:7px; border-radius:50%; background:#22c55e; box-shadow:0 0 0 2px rgba(34,197,94,.3); animation:livePulse 1.8s ease-in-out infinite; }
        .ann-badge.hidden { background:#f1f5f9; color:#64748b; border:1.5px solid #e2e8f0; }
        .ann-badge.hidden::before { content:''; display:inline-block; width:7px; height:7px; border-radius:50%; background:#94a3b8; }
        @keyframes livePulse { 0%,100%{box-shadow:0 0 0 2px rgba(34,197,94,.3)} 50%{box-shadow:0 0 0 5px rgba(34,197,94,.08)} }
        .ann-image-thumb { width:64px; height:64px; border-radius:10px; object-fit:cover; flex-shrink:0; border:1px solid #e5e7eb; }
        .ann-body { font-size:13px; color:#4b5563; line-height:1.6; margin-bottom:6px; white-space:pre-line; }
        .ann-body.clamped { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
        .ann-read-more-btn { background:none; border:none; padding:0; font-size:12px; font-weight:700; color:#dc2626; cursor:pointer; margin-bottom:12px; display:inline-flex; align-items:center; gap:4px; }
        .ann-read-more-btn:hover { opacity:.75; }
        .ann-footer { display:flex; justify-content:space-between; align-items:center; gap:8px; padding-top:12px; border-top:1px solid #f1f5f9; }
        .ann-actions { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
        .ann-actions form { margin:0; }
        .ann-toggle-btn { display:inline-flex; align-items:center; gap:6px; padding:6px 13px; border-radius:8px; font-size:12px; font-weight:700; border:1.5px solid; cursor:pointer; background:transparent; }
        .ann-toggle-btn.make-live { color:#059669; border-color:rgba(5,150,105,0.3); }
        .ann-toggle-btn.make-live:hover { background:rgba(5,150,105,0.07); }
        .ann-toggle-btn.make-hidden { color:#64748b; border-color:rgba(100,116,139,0.3); }
        .ann-toggle-btn.make-hidden:hover { background:rgba(100,116,139,0.07); }
        .ann-edit-btn { display:inline-flex; align-items:center; gap:6px; padding:6px 13px; border-radius:8px; font-size:12px; font-weight:700; border:1.5px solid rgba(220,38,38,0.3); color:#dc2626; background:transparent; cursor:pointer; }
        .ann-edit-btn:hover { background:rgba(220,38,38,0.06); }
        .ann-delete-btn { display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:8px; font-size:12px; font-weight:700; border:1.5px solid rgba(239,68,68,0.25); color:#dc2626; background:transparent; cursor:pointer; }
        .ann-delete-btn:hover { background:rgba(239,68,68,0.06); }
        .ann-category-tag { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:999px; font-size:12px; font-weight:700; background:#fee2e2; color:#dc2626; margin-bottom:10px; }
        .ann-img-preview { margin-top:8px; display:none; }
        .ann-img-preview img { width:100%; max-height:140px; object-fit:cover; border-radius:10px; border:1px solid #e5e7eb; }
        .visibility-badge { display:inline-flex; align-items:center; gap:6px; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; letter-spacing:.3px; }
        .visibility-badge.visible { background:#dcfce7; color:#15803d; border:1.5px solid #bbf7d0; }
        .visibility-badge.visible::before { content:''; display:inline-block; width:6px; height:6px; border-radius:50%; background:#22c55e; animation:livePulse 1.8s ease-in-out infinite; }
        .visibility-badge.hidden { background:#f1f5f9; color:#64748b; border:1.5px solid #e2e8f0; }
        .visibility-badge.hidden::before { content:''; display:inline-block; width:6px; height:6px; border-radius:50%; background:#94a3b8; }
        .empty-block { font-style: italic; color: #94a3b8; }
        /* ── ADMIN LOGIN (Option A — Split Panel) ── */
        html.login-page, html.login-page body { background: #1e293b; }

        .login-cover {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 48px 20px;
            background:
                linear-gradient(180deg, rgba(15,23,42,.45) 0%, rgba(15,23,42,.72) 100%),
                url('{{ asset('img/cover.png') }}') center/cover no-repeat fixed;
            position: relative;
        }
        .login-cover-content { width: 100%; max-width: 420px; display: flex; flex-direction: column; align-items: center; position: relative; z-index: 1; }
        .lc-brand { display: flex; flex-direction: row; align-items: center; gap: 16px; margin-bottom: 30px; }
        .lc-logo {
            width: 84px; height: 84px; border-radius: 50%; object-fit: cover;
            box-shadow: 0 12px 32px rgba(0,0,0,.4);
            margin-top: -10px;
            flex-shrink: 0;
        }
        .lc-brand-text { text-align: left; }
        .lc-wordmark { font-family: 'Playfair Display', 'Raleway', serif; font-size: 2.1rem; font-weight: 800; color: #fff; letter-spacing: .02em; text-shadow: 0 2px 16px rgba(0,0,0,.45); }
        .lc-tagline { font-family: 'Playfair Display', 'Raleway', serif; font-size: .8rem; color: rgba(255,255,255,.8); margin-top: -1px; letter-spacing: .03em; text-shadow: 0 1px 8px rgba(0,0,0,.4); }

        .login-form-box {
            width: 100%;
            background: rgba(255,255,255,.28);
            border: 1px solid rgba(255,255,255,.45);
            border-radius: 24px;
            box-shadow: 0 25px 70px rgba(0,0,0,.35);
            padding: 40px 36px;
        }
        .lf-eyebrow {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 11px; font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
            color: #7f1d1d; background: rgba(127,29,29,.35); border: 1px solid rgba(153,27,27,.6);
            backdrop-filter: blur(6px) saturate(160%); -webkit-backdrop-filter: blur(6px) saturate(160%);
            padding: 7px 18px; border-radius: 999px; margin-bottom: 14px;
        }
        .lf-heading { font-family: 'Playfair Display', 'Raleway', serif; font-size: 1.65rem; font-weight: 800; color: #fff; letter-spacing: -.01em; margin-bottom: 6px; }
        .lf-sub     { font-size: .83rem; color: rgba(255,255,255,.85); margin-bottom: 28px; line-height: 1.6; }
        .lc-footer { margin-top: 22px; font-size: .68rem; color: rgba(255,255,255,.65); letter-spacing: .06em; text-align: center; text-shadow: 0 1px 6px rgba(0,0,0,.4); }

        /* Login alert (ps-overlay style, matches the customer-side auth alerts) */
        .ps-overlay{position:fixed;inset:0;background:rgba(6,13,26,.75);backdrop-filter:blur(6px);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px;animation:ps-fade-in .25s ease both}
        @keyframes ps-fade-in{from{opacity:0}to{opacity:1}}
        .ps-modal{width:100%;max-width:440px;background:#0f172a;border-radius:22px;overflow:hidden;box-shadow:0 40px 90px rgba(0,0,0,.7),0 0 0 1px rgba(255,255,255,.07);animation:ps-pop .5s cubic-bezier(.34,1.56,.64,1) both;position:relative;font-family:'Raleway',system-ui,sans-serif}
        @keyframes ps-pop{from{opacity:0;transform:scale(.88) translateY(28px)}to{opacity:1;transform:scale(1) translateY(0)}}
        .ps-hdr{background:rgba(255,255,255,.04);border-bottom:1px solid rgba(255,255,255,.07);padding:16px 24px;display:flex;align-items:center;gap:9px;position:relative;overflow:hidden}
        .ps-cross{width:18px;height:18px;flex-shrink:0}
        .ps-parish{font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#fff;white-space:nowrap}
        .ps-dot{width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.2);flex-shrink:0}
        .ps-loc{font-size:.68rem;color:rgba(255,255,255,.32);letter-spacing:.04em;white-space:nowrap}
        .ps-body{padding:40px 32px 36px;display:flex;flex-direction:column;align-items:center;text-align:center;position:relative;overflow:hidden}
        .ps-glow{position:absolute;width:280px;height:280px;top:-70px;left:50%;transform:translateX(-50%);pointer-events:none;animation:ps-pulse 3s ease-in-out infinite}
        @keyframes ps-pulse{0%,100%{opacity:.7;transform:translateX(-50%) scale(1)}50%{opacity:1;transform:translateX(-50%) scale(1.15)}}
        .ps-particle{position:absolute;border-radius:50%;pointer-events:none;animation:ps-float 4s ease-in infinite;opacity:0}
        @keyframes ps-float{0%{opacity:0;transform:translateY(0) scale(0)}20%{opacity:.45}100%{opacity:0;transform:translateY(-200px) scale(1.1)}}
        .ps-hdr-particle{position:absolute;border-radius:50%;pointer-events:none;animation:ps-hfloat 4s ease-in infinite;opacity:0}
        @keyframes ps-hfloat{0%{opacity:0;transform:translateY(0) scale(0)}15%{opacity:.6}100%{opacity:0;transform:translateY(-50px) scale(1.2)}}
        .ps-icon{position:relative;width:82px;height:82px;margin-bottom:24px;z-index:2}
        .ps-ring{position:absolute;inset:-5px;border-radius:50%;animation:ps-spin 3s linear infinite}
        @keyframes ps-spin{to{transform:rotate(360deg)}}
        .ps-inner{position:absolute;inset:0;border-radius:50%;display:flex;align-items:center;justify-content:center;animation:ps-ipop .5s .15s cubic-bezier(.34,1.56,.64,1) both;backdrop-filter:blur(8px)}
        @keyframes ps-ipop{from{transform:scale(0)}to{transform:scale(1)}}
        .ps-inner svg{width:38px;height:38px}
        .ps-warn{stroke-width:2.6;stroke-linecap:round;fill:none;stroke-dasharray:80;stroke-dashoffset:80;animation:ps-draw .6s .45s ease forwards}
        @keyframes ps-draw{to{stroke-dashoffset:0}}
        .ps-title{font-size:1.5rem;font-weight:900;color:#fff;letter-spacing:-.03em;margin-bottom:9px;z-index:2;animation:ps-up .4s .5s both}
        .ps-sub{font-size:.86rem;color:rgba(255,255,255,.45);line-height:1.68;max-width:270px;margin-bottom:26px;z-index:2;animation:ps-up .4s .6s both}
        @keyframes ps-up{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .ps-btns{display:flex;gap:10px;width:100%;z-index:2;animation:ps-up .4s .7s both;justify-content:center}
        .ps-btn-solo{width:100%;padding:13px;border-radius:11px;border:none;background:#dc2626;color:#fff;font-family:'Raleway',system-ui,sans-serif;font-size:.86rem;font-weight:800;cursor:pointer;box-shadow:0 6px 20px rgba(220,38,38,.4);transition:all .15s}
        .ps-btn-solo:hover{background:#b91c1c;transform:translateY(-1px)}
        .ps-chip{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:30px;padding:8px 20px;font-size:.8rem;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:26px;z-index:2;display:flex;align-items:center;gap:8px;animation:ps-up .4s .7s both;backdrop-filter:blur(6px)}
        .ps-chip-dot{width:8px;height:8px;border-radius:50%;background:#22c55e;flex-shrink:0;animation:ps-cpulse 2s ease-out .9s infinite}
        @keyframes ps-cpulse{0%{box-shadow:0 0 0 0 rgba(34,197,94,.5)}70%{box-shadow:0 0 0 6px rgba(34,197,94,0)}100%{box-shadow:0 0 0 0 rgba(34,197,94,0)}}

        /* fields */
        .lf-field { margin-bottom: 16px; }
        .lf-field label {
            display: block; font-size: .67rem; font-weight: 700;
            letter-spacing: .09em; text-transform: uppercase;
            color: #334155; margin-bottom: 7px;
        }
        .lf-iw { position: relative; }
        .lf-iw-icon {
            position: absolute; top: 50%; left: 13px;
            transform: translateY(-50%);
            color: #cbd5e1; font-size: .82rem; pointer-events: none;
        }
        .lf-iw input {
            display: block; width: 100%;
            padding: 11px 14px 11px 38px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            background: #f8fafc; font-family: inherit;
            font-size: .88rem; color: #0f172a; outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        .lf-iw input:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220,38,38,.1);
            background: #fff;
        }
        .lf-pw-toggle {
            position: absolute; top: 50%; right: 11px;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            font-size: .72rem; font-weight: 700; font-family: inherit;
            color: #94a3b8; padding: 2px 4px; transition: color .13s;
        }
        .lf-pw-toggle:hover { color: #dc2626; }

        .lf-submit {
            display: block; width: 100%; padding: 13px; margin-top: 6px;
            background: #dc2626; color: #fff; border: none; border-radius: 10px;
            font-family: inherit; font-size: .9rem; font-weight: 800; cursor: pointer;
            box-shadow: 0 4px 14px rgba(220,38,38,.28);
            transition: background .15s, transform .12s; letter-spacing: .01em;
        }
        .lf-submit:hover { background: #b91c1c; transform: translateY(-1px); }
        .lf-submit:active { transform: none; }

        .lf-roles {
            margin-top: 22px; padding-top: 18px;
            border-top: 1px solid rgba(30,41,59,.15);
            font-size: .72rem; color: #334155; text-align: center; line-height: 1.7;
        }
        .lf-chips { display: flex; gap: 7px; justify-content: center; margin-top: 8px; }
        .lf-chip  { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: .68rem; font-weight: 700; }
        .lf-chip-admin { background: rgba(127,29,29,.35); border: 1px solid rgba(153,27,27,.6); color: #7f1d1d; backdrop-filter: blur(6px) saturate(160%); -webkit-backdrop-filter: blur(6px) saturate(160%); }
        .lf-chip-sec   { background: rgba(180,83,9,.2); border: 1px solid rgba(180,83,9,.4); color: #fcd34d; }

        @media (max-width: 768px) {
            .login-cover { padding: 32px 16px; }
            .login-form-box { padding: 32px 24px; }
            .lc-logo { width: 68px; height: 68px; }
            .lc-wordmark { font-size: 1.7rem; }
        }
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
    background: #f8fafc !important;
}

.admin-availability-calendar .calendar_day .day_number {
    color: #1e293b !important;
    font-size: 15px !important;
    font-weight: 700 !important;
}

.admin-availability-calendar .calendar_day.status_pending {
    background: rgba(245,158,11,.1) !important;
    border: 1px solid rgba(245,158,11,.4) !important;
}

.admin-availability-calendar .calendar_day.status_booked {
    background: rgba(220,38,38,.07) !important;
    border: 1px solid rgba(220,38,38,.35) !important;
}

.admin-availability-calendar .calendar_day.status_done {
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
}

.admin-availability-calendar .calendar_day.status_done .day_number {
    color: #94a3b8 !important;
}

.admin-availability-calendar .calendar_day.is_today {
    background: rgba(34,197,94,.08) !important;
}

.admin-availability-calendar .calendar_day.is_today .day_number {
    color: #15803d !important;
    font-weight: 800 !important;
}

/* Today dot (top-right) */
.admin-availability-calendar .today-indicator-dot {
    position: absolute !important;
    top: 10px; right: 10px; width: 10px; height: 10px; border-radius: 50%;
    background: #22c55e; border: 2px solid #fff; z-index: 2; pointer-events: none;
    box-shadow: 0 0 0 0 rgba(34,197,94,.5);
    animation: admin-today-pulse 2s ease-out infinite;
}
@keyframes admin-today-pulse { 0%{box-shadow:0 0 0 0 rgba(34,197,94,.5)} 70%{box-shadow:0 0 0 7px rgba(34,197,94,0)} 100%{box-shadow:0 0 0 0 rgba(34,197,94,0)} }

/* Booked / pending status dot — stacked top-right underneath any dot already there (position set inline per-dot by JS) */
.admin-availability-calendar .reservation-status-dot {
    position: absolute !important;
    top: 10px; right: 10px; width: 10px !important; height: 10px !important;
    border-radius: 50% !important; border: 2px solid #fff !important; z-index: 2; pointer-events: none;
}
.admin-availability-calendar .booked-dot  { background:#dc2626; animation: admin-booked-pulse 1.8s infinite; }
.admin-availability-calendar .pending-dot { background:#f59e0b; animation: admin-pending-pulse 1.8s infinite; }
@keyframes admin-booked-pulse  { 0%{box-shadow:0 0 0 0 rgba(220,38,38,.6)}  70%{box-shadow:0 0 0 7px rgba(220,38,38,0)}  100%{box-shadow:0 0 0 0 rgba(220,38,38,0)} }
@keyframes admin-pending-pulse { 0%{box-shadow:0 0 0 0 rgba(245,158,11,.6)} 70%{box-shadow:0 0 0 7px rgba(245,158,11,0)} 100%{box-shadow:0 0 0 0 rgba(245,158,11,0)} }

/* Event-type dot(s) — stacked top-right underneath the today/status dots (position set inline per-dot by JS) */
.admin-availability-calendar .type-dot {
    position: absolute !important;
    right: 10px; width: 10px !important; height: 10px !important;
    border-radius: 50% !important; border: 2px solid #fff !important; z-index: 2; pointer-events: none;
}
.admin-availability-calendar .type-dot-wedding { background:#f472b6; animation: admin-type-pulse-wedding 1.8s infinite; }
.admin-availability-calendar .type-dot-baptism { background:#2dd4bf; animation: admin-type-pulse-baptism 1.8s infinite; }
.admin-availability-calendar .type-dot-funeral { background:#64748b; animation: admin-type-pulse-funeral 1.8s infinite; }
@keyframes admin-type-pulse-wedding { 0%{box-shadow:0 0 0 0 rgba(244,114,182,.6)} 70%{box-shadow:0 0 0 7px rgba(244,114,182,0)} 100%{box-shadow:0 0 0 0 rgba(244,114,182,0)} }
@keyframes admin-type-pulse-baptism { 0%{box-shadow:0 0 0 0 rgba(45,212,191,.6)}  70%{box-shadow:0 0 0 7px rgba(45,212,191,0)}  100%{box-shadow:0 0 0 0 rgba(45,212,191,0)} }
@keyframes admin-type-pulse-funeral { 0%{box-shadow:0 0 0 0 rgba(100,116,139,.6)} 70%{box-shadow:0 0 0 7px rgba(100,116,139,0)} 100%{box-shadow:0 0 0 0 rgba(100,116,139,0)} }

/* better legend */
.calendar_legend {
    background: #ffffff !important;
    border: 1px solid rgba(220,38,38,0.14) !important;
    border-radius: 16px !important;
    padding: 12px 16px !important;
    gap: 18px !important;
}

.cal-legend-pulse { width:10px; height:10px; border-radius:50%; display:inline-block; flex-shrink:0; }
@keyframes legend-pulse-green  { 0%{box-shadow:0 0 0 0 rgba(34,197,94,.5)}  70%{box-shadow:0 0 0 6px rgba(34,197,94,0)}  100%{box-shadow:0 0 0 0 rgba(34,197,94,0)} }
@keyframes legend-pulse-yellow { 0%{box-shadow:0 0 0 0 rgba(245,158,11,.5)} 70%{box-shadow:0 0 0 6px rgba(245,158,11,0)} 100%{box-shadow:0 0 0 0 rgba(245,158,11,0)} }
@keyframes legend-pulse-red    { 0%{box-shadow:0 0 0 0 rgba(220,38,38,.5)}  70%{box-shadow:0 0 0 6px rgba(220,38,38,0)}  100%{box-shadow:0 0 0 0 rgba(220,38,38,0)} }

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
    color: #991b1b;
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 6px;
}

.admin-note-icon {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    background: #fff1f2;
    color: #dc2626;
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
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 3px rgba(220,38,38,0.10) !important;
}

.admin-detail-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.status-badge {
    display: inline-flex;
    align-items: center;
}

.reservation-card h4 {
    line-height: 1.35;
    margin-top: 0;
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
    background: #fce7f3; color: #be185d; border: 1.5px solid #fbcfe8;
}
.event-card.baptism .event-icon {
    background: #dcfce7; color: #15803d; border: 1.5px solid #bbf7d0;
}
.event-card.funeral .event-icon {
    background: #f1f5f9; color: #475569; border: 1.5px solid #cbd5e1;
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

/* ── CUSTOMER CARDS ── */
.att-filter-btn {
    padding: 6px 16px; border-radius: 999px; border: 1.5px solid #e5e7eb;
    background: #fff; font-size: 13px; font-weight: 600; color: #6b7280;
    cursor: pointer; transition: background .15s, color .15s;
}
.att-filter-btn:hover { background: #f8fafc; }
.att-filter-btn.active-att { background: #dc2626; border-color: #dc2626; color: #fff !important; }

.cust-stats-bar { display:flex; gap:0; background:#f8fafc; border:1px solid #e5e7eb; border-radius:14px; padding:14px 24px; margin-bottom:20px; }
.cust-stat { display:flex; flex-direction:column; align-items:center; flex:1; gap:2px; }
.cust-stat-num { font-size:24px; font-weight:800; color:#991b1b; line-height:1; }
.cust-stat-num.active { color:#059669; }
.cust-stat-num.disabled { color:#ef4444; }
.cust-stat-label { font-size:11px; color:#6b7280; font-weight:600; text-transform:uppercase; letter-spacing:0.08em; }
.cust-stat-divider { width:1px; height:36px; background:#e5e7eb; margin:0 8px; align-self:center; }

.cust-search-bar { display:flex; gap:12px; align-items:flex-end; margin-bottom:20px; flex-wrap:wrap; }
.cust-search-input-wrap { flex:1; min-width:200px; position:relative; }
.cust-search-input-wrap i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:14px; pointer-events:none; }
.cust-search-input { width:100%; padding:10px 13px 10px 36px; border-radius:12px; border:1.5px solid #e5e7eb; font-size:14px; outline:none; box-sizing:border-box; }
.cust-search-input:focus { border-color:#ef4444; box-shadow:0 0 0 3px rgba(220,38,38,0.1); }
.cust-filter-pills { display:flex; gap:6px; }
.cust-filter-pill { padding:8px 16px; border-radius:999px; border:1.5px solid #e5e7eb; background:#fff; font-size:13px; font-weight:600; color:#6b7280; cursor:pointer; text-decoration:none; white-space:nowrap; }
.cust-filter-pill.active-pill { background:#dc2626; border-color:#dc2626; color:#fff; }
.cust-filter-pill:hover:not(.active-pill) { border-color:#ef4444; color:#dc2626; text-decoration:none; }
.cust-result-count { font-size:13px; color:#94a3b8; align-self:center; white-space:nowrap; }

.cust-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:16px; }
.cust-card { background:#fff; border-radius:16px; border:1px solid #e5e7eb; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 2px 10px rgba(15,23,42,0.05); transition:box-shadow 0.2s,transform 0.2s; }
.cust-card:hover { box-shadow:0 8px 24px rgba(15,23,42,0.1); transform:translateY(-2px); }
.cust-card-top { display:flex; align-items:center; gap:14px; padding:18px 18px 14px; }
.cust-avatar { width:46px; height:46px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:18px; font-weight:800; color:#fff; flex-shrink:0; }
.cust-avatar.active { background:linear-gradient(135deg,#dc2626,#b91c1c); }
.cust-avatar.disabled { background:linear-gradient(135deg,#94a3b8,#64748b); }
.cust-info { flex:1; min-width:0; }
.cust-name { font-size:15px; font-weight:700; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:3px; }
.cust-email { font-size:12px; color:#6b7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.cust-status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:700; flex-shrink:0; }
.cust-status-badge.active { background:rgba(16,185,129,0.1); color:#065f46; border:1px solid rgba(16,185,129,0.25); }
.cust-status-badge.disabled { background:rgba(239,68,68,0.1); color:#991b1b; border:1px solid rgba(239,68,68,0.2); }
.cust-meta { display:flex; gap:0; padding:0 18px 14px; flex-wrap:wrap; }
.cust-meta-item { display:flex; align-items:center; gap:5px; font-size:12px; color:#6b7280; margin-right:14px; margin-bottom:4px; }
.cust-meta-item i { color:#94a3b8; font-size:12px; }
.cust-res-count { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; background:rgba(220,38,38,0.08); border-radius:999px; font-size:12px; font-weight:700; color:#dc2626; }
.cust-footer { display:flex; gap:8px; padding:12px 18px; border-top:1px solid #f1f5f9; margin-top:auto; }
.cust-footer form { margin:0; }
.cust-btn { display:inline-flex; align-items:center; gap:6px; padding:7px 13px; border-radius:9px; font-size:12px; font-weight:700; border:1.5px solid; cursor:pointer; white-space:nowrap; text-decoration:none; background:transparent; }
.cust-btn.view { color:#dc2626; border-color:rgba(220,38,38,0.3); }
.cust-btn.view:hover { background:rgba(220,38,38,0.07); }
.cust-btn.enable { color:#059669; border-color:rgba(5,150,105,0.3); }
.cust-btn.enable:hover { background:rgba(5,150,105,0.07); }
.cust-btn.disable { color:#dc2626; border-color:rgba(239,68,68,0.25); }
.cust-btn.disable:hover { background:rgba(239,68,68,0.06); }
.cust-btn.reset { color:#64748b; border-color:rgba(100,116,139,0.25); }
.cust-btn.reset:hover { background:rgba(100,116,139,0.06); }

.cust-card-disabled { opacity:.72; }
.cust-stat-num.pending { color:#b45309; }
.cust-pending-chip { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; background:rgba(245,158,11,0.12); border-radius:999px; font-size:11px; font-weight:700; color:#92400e; margin-left:6px; }
.cust-pending-dot { width:6px; height:6px; border-radius:50%; background:#f59e0b; display:inline-block; }

/* Customer table view */
.cust-table-wrap { overflow-x:auto; }
.cust-table { width:100%; border-collapse:collapse; min-width:760px; }
.cust-table thead tr { border-bottom:2px solid #f1f5f9; }
.cust-table th { text-align:left; padding:0 12px 12px 6px; font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.06em; }
.cust-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .15s; }
.cust-table tbody tr:hover { background:#f8fafc; }
.cust-table td { padding:14px 12px 14px 6px; vertical-align:middle; }
.cust-table-customer { display:flex; align-items:center; gap:12px; }
.cust-table-name { font-size:14px; font-weight:700; color:#111827; white-space:nowrap; }
.cust-table-email { font-size:12px; color:#6b7280; white-space:nowrap; }
.cust-table-contact { font-size:13px; color:#374151; white-space:nowrap; }
.cust-table-empty { font-size:12px; color:#94a3b8; }
.cust-table-breakdown { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
.cust-table-total { font-size:13px; font-weight:700; color:#1f2937; }
.cust-bd { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:700; }
.cust-bd-dot { width:6px; height:6px; border-radius:50%; display:inline-block; }
.cust-bd.approved { color:#059669; } .cust-bd.approved .cust-bd-dot { background:#10b981; }
.cust-bd.pending { color:#b45309; } .cust-bd.pending .cust-bd-dot { background:#f59e0b; }
.cust-bd.declined { color:#dc2626; } .cust-bd.declined .cust-bd-dot { background:#ef4444; }
.cust-table-actions { display:flex; gap:6px; justify-content:flex-end; }
.cust-icon-btn { width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; border:1.5px solid; background:transparent; cursor:pointer; font-size:12px; }
.cust-icon-btn.view { border-color:rgba(220,38,38,0.3); color:#dc2626; }
.cust-icon-btn.view:hover { background:rgba(220,38,38,0.07); }
.cust-icon-btn.disable { border-color:rgba(239,68,68,0.25); color:#dc2626; }
.cust-icon-btn.disable:hover { background:rgba(239,68,68,0.06); }
.cust-icon-btn.enable { border-color:rgba(5,150,105,0.3); color:#059669; }
.cust-icon-btn.enable:hover { background:rgba(5,150,105,0.07); }
.cust-icon-btn.reset { border-color:rgba(100,116,139,0.25); color:#64748b; }
.cust-icon-btn.reset:hover { background:rgba(100,116,139,0.06); }

/* modal profile — dark parish modal theme */
.dcm-modal-lg { max-width: 640px; }
.dcm-close-btn { position:absolute; top:16px; right:20px; z-index:10; width:30px; height:30px; border-radius:50%; border:none; background:rgba(255,255,255,.08); color:rgba(255,255,255,.7); font-size:20px; line-height:1; cursor:pointer; transition:background .15s,color .15s; }
.dcm-close-btn:hover { background:rgba(255,255,255,.16); color:#fff; }

/* Dark-modal form fields (used by the Edit Announcement modal) */
.dcm-modal .dcm-form-input, .dcm-modal .dcm-textarea { background:rgba(255,255,255,.04); border:1.5px solid rgba(255,255,255,.12); border-radius:12px; color:#fff; font-family:inherit; font-size:.84rem; padding:12px 14px; width:100%; box-sizing:border-box; }
.dcm-modal .dcm-form-input::placeholder, .dcm-modal .dcm-textarea::placeholder { color:rgba(255,255,255,.3); }
.dcm-modal .dcm-form-input:focus, .dcm-modal .dcm-textarea:focus { outline:none; border-color:#f87171; background:rgba(255,255,255,.06); }
input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button { -webkit-appearance:none; margin:0; }
input[type="number"] { -moz-appearance:textfield; }
.dcm-modal .dcm-label-counter { font-weight:400; color:rgba(255,255,255,.3); text-transform:none; letter-spacing:0; float:right; }
.dcm-modal .dcm-toggle-row { display:flex; align-items:center; gap:10px; background:rgba(255,255,255,.04); border:1.5px solid rgba(255,255,255,.12); border-radius:10px; padding:10px 14px; cursor:pointer; }
.dcm-modal .dcm-toggle-row span.t { font-size:13px; font-weight:600; color:#fff; display:block; }
.dcm-modal .dcm-toggle-row span.s { font-size:11px; color:rgba(255,255,255,.4); }
.dcm-modal .adm-csl-btn { background:rgba(255,255,255,.04); border:1.5px solid rgba(255,255,255,.12); color:#fff; }
.dcm-modal .adm-csl-btn.is-open { background:rgba(255,255,255,.06); border-color:rgba(255,255,255,.25); }
.dcm-modal .adm-csl-arrow { color:rgba(255,255,255,.4); }
.dcm-modal .adm-csl-list { background:#1e293b; border:1.5px solid rgba(255,255,255,.12); }
.dcm-modal .adm-csl-list li { color:#e2e8f0; }
.dcm-modal .adm-csl-list li:hover { background:rgba(255,255,255,.06); color:#fca5a5; }
.dcm-modal .adm-csl-list li.is-selected { background:rgba(220,38,38,.18); color:#fca5a5; }

/* Modern file input (light form + dark modal via .dcm-modal override) */
.ann-file-input { width:100%; padding:8px 12px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:13px; color:#6b7280; background:#f8fafc; cursor:pointer; box-sizing:border-box; }
.ann-file-input::file-selector-button { margin-right:12px; padding:7px 14px; border-radius:8px; border:none; background:#dc2626; color:#fff; font-weight:700; font-size:12px; cursor:pointer; transition:background .15s; font-family:inherit; }
.ann-file-input::file-selector-button:hover { background:#b91c1c; }
.dcm-modal .ann-file-input { background:rgba(255,255,255,.04); border-color:rgba(255,255,255,.12); color:rgba(255,255,255,.4); }
.cust-modal-header { padding:24px 24px 0; position:relative; }
.cust-modal-avatar-row { display:flex; align-items:center; gap:16px; margin-bottom:16px; }
.cust-modal-avatar { width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:800; color:#fff; flex-shrink:0; }
.cust-modal-avatar.active { background:linear-gradient(135deg,#dc2626,#b91c1c); }
.cust-modal-avatar.disabled { background:linear-gradient(135deg,#94a3b8,#64748b); }
.cust-modal-name { font-size:18px; font-weight:800; color:#fff; margin:0 0 4px; }
.cust-modal-sub { font-size:13px; color:rgba(255,255,255,.5); margin:0; }
.cust-modal-meta { display:flex; flex-wrap:wrap; gap:10px; padding:16px 24px; background:rgba(255,255,255,.04); border-top:1px solid rgba(255,255,255,.08); border-bottom:1px solid rgba(255,255,255,.08); }
.cust-modal-meta-item { display:flex; align-items:center; gap:6px; font-size:13px; color:rgba(255,255,255,.75); }
.cust-modal-meta-item i { color:rgba(255,255,255,.4); }
.cust-modal-disabled-note { margin:16px 24px 0; padding:12px 16px; background:rgba(239,68,68,0.12); border-radius:10px; border:1px solid rgba(239,68,68,0.28); font-size:13px; color:#fca5a5; }
.cust-modal-stat-row { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; padding:20px 24px; }
.cust-modal-stat { border-radius:12px; padding:14px; text-align:center; }
.cust-modal-stat-num { font-size:22px; font-weight:800; line-height:1; margin-bottom:4px; }
.cust-modal-stat-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.07em; }
.cust-modal-stat.total { background:rgba(220,38,38,0.14); }
.cust-modal-stat.total .cust-modal-stat-num { color:#fca5a5; }
.cust-modal-stat.total .cust-modal-stat-label { color:#f87171; }
.cust-modal-stat.approved { background:rgba(16,185,129,0.14); }
.cust-modal-stat.approved .cust-modal-stat-num { color:#6ee7b7; }
.cust-modal-stat.approved .cust-modal-stat-label { color:#34d399; }
.cust-modal-stat.pending { background:rgba(245,158,11,0.14); }
.cust-modal-stat.pending .cust-modal-stat-num { color:#fcd34d; }
.cust-modal-stat.pending .cust-modal-stat-label { color:#fbbf24; }
.cust-modal-stat.declined { background:rgba(239,68,68,0.14); }
.cust-modal-stat.declined .cust-modal-stat-num { color:#fca5a5; }
.cust-modal-stat.declined .cust-modal-stat-label { color:#f87171; }
.cust-modal-history { padding:0 24px 24px; }
.cust-modal-history h6 { font-size:13px; font-weight:700; color:rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:12px; }
.cust-history-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid rgba(255,255,255,.08); }
.cust-history-row:last-child { border-bottom:none; }
.cust-history-evtype { display:inline-flex; align-items:center; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; }
.cust-history-id { font-size:12px; color:rgba(255,255,255,.4); flex-shrink:0; }
.cust-history-date { font-size:12px; color:rgba(255,255,255,.5); margin-left:auto; white-space:nowrap; }
.dcm-modal .cust-status-badge.active { background:rgba(16,185,129,0.16); color:#6ee7b7; border-color:rgba(16,185,129,0.35); }
.dcm-modal .cust-status-badge.disabled { background:rgba(239,68,68,0.16); color:#fca5a5; border-color:rgba(239,68,68,0.32); }
.dcm-modal .empty-block { color:rgba(255,255,255,.4); }

.admin-detail-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
}

.admin-detail-view-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 999px;
    background: rgba(220,38,38,0.08);
    border: 1px solid rgba(220,38,38,0.18);
    color: #991b1b;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    transition: background .15s, color .15s;
}

.admin-detail-view-btn:hover,
.admin-detail-view-btn:focus {
    background: #dc2626;
    color: #fff;
    text-decoration: none;
}

.admin-meta-icon {
    width: 22px;
    height: 22px;
    border-radius: 7px;
    background: rgba(220,38,38,0.08);
    color: #dc2626;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    flex-shrink: 0;
}

/* ── SCHEDULE STATS BAR ── */
.schedule-stats-bar {
    display: flex;
    align-items: center;
    gap: 0;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 16px 24px;
    margin-bottom: 24px;
}
.schedule-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    gap: 3px;
}
.schedule-stat-num {
    font-size: 26px;
    font-weight: 800;
    color: #991b1b;
    line-height: 1;
}
.schedule-stat-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}
.schedule-stat-divider {
    width: 1px;
    height: 40px;
    background: #e5e7eb;
    margin: 0 8px;
}
.schedule-stat-num.is-zero { color: #cbd5e1; }

/* ── VIEW TOGGLE (Calendar / List) ── */
.schedule-view-toggle { display:inline-flex; background:#f1f5f9; border-radius:999px; padding:3px; gap:2px; flex-shrink:0; }
.schedule-view-toggle-btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; border:none; background:transparent; color:#64748b; font-family:inherit; font-size:13px; font-weight:700; cursor:pointer; transition:background .15s,color .15s,box-shadow .15s; }
.schedule-view-toggle-btn.is-active { background:#fff; color:#dc2626; box-shadow:0 2px 8px rgba(15,23,42,.08); }

/* ── TYPE FILTER PILLS ── */
.schedule-type-pills { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px; }
.schedule-type-pill { display:inline-flex; align-items:center; padding:8px 16px; border-radius:999px; border:1.5px solid #e2e8f0; background:transparent; color:#64748b; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; transition:all .18s; }
.schedule-type-pill .pill-icon { display:inline-flex; align-items:center; justify-content:center; margin-right:7px; font-size:11px; }
.schedule-type-pill.is-active { color:#fff; }
.schedule-type-pill.is-active .pill-icon { color:#fff !important; }
.schedule-type-pill[data-type="all"].is-active { background:#dc2626; border-color:#dc2626; }
.schedule-type-pill[data-type="wedding"].is-active { background:#f472b6; border-color:#f472b6; }
.schedule-type-pill[data-type="baptism"].is-active { background:#2dd4bf; border-color:#2dd4bf; }
.schedule-type-pill[data-type="funeral"].is-active { background:#64748b; border-color:#64748b; }
.calendar_day.type-dim { opacity:.35; }

/* ── TODAY QUICK-JUMP ── */
.schedule-today-btn { padding:8px 18px; border-radius:999px; border:1.5px solid rgba(220,38,38,0.25); background:#fff; color:#dc2626; font-family:inherit; font-size:13px; font-weight:700; cursor:pointer; transition:background .18s,color .18s,border-color .18s; }
.schedule-today-btn:hover { background:#dc2626; color:#fff; border-color:#dc2626; }

/* ── LIST (AGENDA) VIEW ── */
.schedule-list-group { margin-bottom:24px; }
.schedule-list-date-heading { font-size:13px; font-weight:800; color:#7f1d1d; text-transform:uppercase; letter-spacing:.06em; margin-bottom:10px; padding-bottom:8px; border-bottom:2px solid #f1f5f9; }
.schedule-list-cards { display:flex; flex-direction:column; gap:14px; }
.schedule-list-empty { text-align:center; padding:48px 20px; color:#94a3b8; font-size:14px; }

/* ── CALENDAR COLORED DOTS ── */
.legend-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 4px;
    vertical-align: middle;
}
/* ── DETAIL CARD TAGS ── */
.admin-detail-tags {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 8px 0 12px;
    flex-wrap: wrap;
}
.admin-ev-type-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}
.admin-ev-icon {
    width: 22px;
    height: 22px;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    flex-shrink: 0;
}
.admin-ev-icon svg { flex-shrink: 0; }
.admin-days-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 11px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    background: rgba(220,38,38,0.09);
    color: #991b1b;
    border: 1px solid rgba(220,38,38,0.18);
}
.admin-days-badge.is-done {
    background: rgba(100,116,139,0.09);
    color: #475569;
    border-color: rgba(100,116,139,0.2);
}

/* ── UPCOMING STRIP ── */
.schedule-upcoming-strip {
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}
.schedule-upcoming-title {
    font-size: 13px;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.schedule-upcoming-list {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.schedule-upcoming-card {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px 14px;
    min-width: 180px;
    flex: 1;
}
.schedule-upcoming-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}
.schedule-upcoming-info {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
}
.schedule-upcoming-name {
    font-size: 13px;
    font-weight: 700;
    color: #111827;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.schedule-upcoming-type {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.schedule-upcoming-date {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 2px;
    white-space: nowrap;
}
.schedule-upcoming-datestr {
    font-size: 13px;
    font-weight: 700;
    color: #374151;
}
.schedule-upcoming-days {
    font-size: 11px;
    color: #6b7280;
}

/* ── REPORTS SECTION ── */
.rpt-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
.rpt-label { display: flex; align-items: center; gap: 14px; margin: 4px 0 16px; }
.rpt-label span { font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.10em; white-space: nowrap; }
.rpt-label-line { flex: 1; height: 1px; background: #e5e7eb; }
.rpt-summary-box { border-left: 4px solid #ef4444; background: #f8fafc; border-radius: 0 16px 16px 0; padding: 18px 20px; margin-bottom: 24px; }
.rpt-summary-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; font-weight: 700; color: #7f1d1d; font-size: 15px; }
.rpt-summary-badge { font-size: 12px; padding: 3px 12px; border-radius: 999px; background: rgba(220,38,38,0.12); color: #dc2626; font-weight: 600; }
.rpt-summary-text { font-size: 13px; color: #475569; line-height: 1.75; margin-bottom: 16px; }
.rpt-highlights { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
.rpt-hl { background: #fff; border-radius: 12px; padding: 12px 14px; border: 1px solid rgba(220,38,38,0.14); }
.rpt-hl-label { font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 4px; }
.rpt-hl-val { font-size: 22px; font-weight: 700; color: #1f2937; }
.rpt-hl-sub { font-size: 11px; color: #10b981; margin-top: 3px; }
.rpt-pie-legend { display: flex; flex-direction: column; gap: 10px; }
.rpt-pie-row { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #4b5563; }
.rpt-pie-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.rpt-pie-pct { margin-left: auto; font-weight: 700; color: #1f2937; }
.rpt-status-totals { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-top: 12px; }
.rpt-stot { background: #f8fafc; border-radius: 10px; padding: 8px 10px; text-align: center; border: 1px solid #e5e7eb; }
.rpt-stot-label { font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 3px; }
.rpt-stot-val { font-size: 20px; font-weight: 700; }
.rpt-heatmap-wrap { overflow-x: auto; padding-bottom: 4px; }
.rpt-hm-month-row { display: flex; margin-bottom: 5px; }
.rpt-hm-spacer { width: 76px; flex-shrink: 0; }
.rpt-hm-ml { flex: 1; font-size: 10px; color: #9ca3af; text-align: center; }
.rpt-hm-row { display: flex; align-items: center; margin-bottom: 5px; }
.rpt-hm-rl { width: 76px; flex-shrink: 0; font-size: 12px; color: #64748b; text-align: right; padding-right: 10px; }
.rpt-hm-cells { display: grid; grid-template-columns: repeat(12, 1fr); gap: 4px; flex: 1; min-width: 480px; }
.rpt-hm-cell { height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 600; cursor: default; transition: transform 0.15s ease; }
.rpt-hm-cell:hover { transform: scale(1.12); z-index: 1; }
.rpt-scale-row { display: flex; align-items: center; gap: 8px; margin-top: 10px; }
.rpt-scale-lbl { font-size: 10px; color: #9ca3af; }
.rpt-scale-bar { display: flex; gap: 3px; }
.rpt-sc { width: 18px; height: 10px; border-radius: 3px; }
.rpt-placeholder-box { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 24px; border: 1.5px dashed #fecaca; border-radius: 16px; background: #f8fafc; text-align: center; }
        .don-hidden { display: none; }
.rpt-placeholder-title { font-size: 14px; font-weight: 600; color: #64748b; margin: 10px 0 6px; }
.rpt-placeholder-sub { font-size: 12px; color: #94a3b8; max-width: 280px; line-height: 1.6; }
.rpt-placeholder-badge { font-size: 10px; padding: 3px 10px; border-radius: 999px; background: rgba(148,163,184,0.18); color: #64748b; font-weight: 600; white-space: nowrap; border: 1px solid #e2e8f0; }
@media (max-width: 992px) {
    .rpt-two-col { grid-template-columns: 1fr; }
    .rpt-highlights { grid-template-columns: 1fr 1fr; }
    .rpt-status-totals { grid-template-columns: repeat(3, 1fr); }
}

@media print {
    @page { size: A4 landscape; margin: 12mm; }
    html, body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: #fff; }
    .admin-sidebar, .no-print { display: none !important; }
    .admin-layout { display: block; }
    .admin-main { padding: 0; }
    .section-card { box-shadow: none; border: 1px solid #e5e7eb; break-inside: avoid; page-break-inside: avoid; }
    .summary-card, .rpt-hl, .rpt-stot { break-inside: avoid; page-break-inside: avoid; }
    .main-header { margin-bottom: 16px; }
    #donScreenView { display: none !important; }
    #rptPrintDoc, #donPrintDoc { display: block !important; }
    .print-hide-header { display: none !important; }
    #logProgressStats, #loggedCardsView { display: block !important; opacity: 1 !important; }
}

/* ── Print-only report document (Reports & Donations) ── */
#rptPrintDoc, #donPrintDoc { display: none; font-family: inherit; color: #1f2937; }
.rpd-logo { width: 46px; height: 46px; border-radius: 50%; object-fit: cover; border: 2px solid #dc2626; flex-shrink: 0; }
.rpd-letterhead { display: flex; align-items: center; justify-content: space-between; padding-bottom: 16px; border-bottom: 2px solid #dc2626; margin-bottom: 22px; }
.rpd-letterhead-left { display: flex; align-items: center; gap: 12px; }
.rpd-parish-name { font-size: 15px; font-weight: 800; letter-spacing: .03em; color: #111827; }
.rpd-parish-loc { font-size: 11.5px; color: #6b7280; font-weight: 500; margin-top: 1px; }
.rpd-letterhead-right { text-align: right; }
.rpd-generated-label { font-size: 10.5px; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
.rpd-generated-date { font-size: 12px; color: #374151; font-weight: 600; }
.rpd-title-row { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 10px; }
.rpd-title { font-size: 24px; font-weight: 800; color: #111827; }
.rpd-subtitle { font-size: 12.5px; color: #6b7280; margin-top: 4px; }
.rpd-range-pill { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; font-size: 12px; font-weight: 700; padding: 7px 16px; border-radius: 999px; white-space: nowrap; }
.rpd-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px; margin-bottom: 26px; }
.rpd-stat { border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px 14px; }
.rpd-stat-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
.rpd-stat-val { font-size: 22px; font-weight: 800; margin-top: 4px; }
.rpd-section-label { font-size: 13px; font-weight: 800; color: #111827; margin-bottom: 10px; }
.rpd-table { border-collapse: collapse; width: 100%; font-size: 12.5px; }
.rpd-table th { text-align: left; padding: 8px 10px; color: #6b7280; font-size: 10.5px; text-transform: uppercase; letter-spacing: .05em; border-bottom: 2px solid #e5e7eb; }
.rpd-table td { padding: 8px 10px; border-bottom: 1px solid #f1f5f9; }
.rpd-total-row td { padding-top: 9px; padding-bottom: 9px; font-weight: 800; border-bottom: none; border-top: 2px solid #e5e7eb; }
.rpd-footer { margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; font-size: 10.5px; color: #9ca3af; }
.rpd-donor-table th:nth-child(3), .rpd-donor-table td:nth-child(3) { text-align: right; }

    </style>
    <style id="printPageSizeOverride"></style>
</head>
<body>

@if(!$isLoggedIn)
{{-- ===================== LOGIN ===================== --}}
<script>document.documentElement.classList.add('login-page');</script>
<div class="login-cover">
<div class="login-cover-content">

    <div class="lc-brand">
        <img src="{{ asset('img/about/about_1.jpg') }}" alt="Parish Logo" class="lc-logo">
        <div class="lc-brand-text">
            <div class="lc-wordmark">ParishSched</div>
            <div class="lc-tagline">St. John the Baptist Parish &middot; Tiaong, Quezon</div>
        </div>
    </div>

    <div class="login-form-box">
        <div class="lf-eyebrow">Admin Access</div>
        <h2 class="lf-heading">Welcome back</h2>
            <p class="lf-sub">Sign in with your administrator credentials to continue.</p>

            @if(session('login_error') || session('flash_error') || session('flash_success') || session('logout_success'))
            @php
                if (session('login_error')) {
                    $loginNotif = ['icon' => 'error', 'title' => 'Login Failed', 'text' => session('login_error')];
                } elseif (session('flash_error')) {
                    $loginNotif = ['icon' => 'error', 'title' => 'Error', 'text' => session('flash_error')];
                } elseif (session('logout_success')) {
                    $loginNotif = ['icon' => 'success', 'title' => 'Logged Out', 'text' => session('logout_success')];
                } else {
                    $loginNotif = ['icon' => 'success', 'title' => 'Success', 'text' => session('flash_success')];
                }
                $lnType = $loginNotif['icon'];
                $lnCfg = [
                    'success' => ['glow'=>'rgba(34,197,94,.13)',  'ring'=>'rgba(34,197,94,.5),rgba(134,239,172,.3)',  'inner'=>'rgba(34,197,94,.1)',  'border'=>'rgba(34,197,94,.3)',  'stroke'=>'#4ade80', 'h1'=>'rgba(255,255,255,.25)','h2'=>'#4ade8066','h3'=>'#4ade804d','b1'=>'#4ade8066','b2'=>'#4ade8059','b3'=>'rgba(255,255,255,.18)'],
                    'error'   => ['glow'=>'rgba(220,38,38,.13)', 'ring'=>'rgba(220,38,38,.5),rgba(252,165,165,.3)', 'inner'=>'rgba(220,38,38,.1)', 'border'=>'rgba(220,38,38,.3)', 'stroke'=>'#f87171', 'h1'=>'rgba(255,255,255,.25)','h2'=>'#f8717166','h3'=>'#f871714d','b1'=>'#f8717166','b2'=>'#f8717159','b3'=>'rgba(255,255,255,.18)'],
                ];
                $lnc = $lnCfg[$lnType] ?? $lnCfg['error'];
            @endphp
            <div class="ps-overlay" id="ps-overlay-adm-login">
                <div class="ps-modal">
                    <div class="ps-hdr" id="ps-hdr-adm-login">
                        <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                        <span class="ps-parish">St. John the Baptist Parish</span>
                        <span class="ps-dot"></span>
                        <span class="ps-loc">Tiaong, Quezon</span>
                    </div>
                    <div class="ps-body" id="ps-body-adm-login">
                        <div class="ps-glow" style="background:radial-gradient(circle,{{ $lnc['glow'] }} 0%,transparent 70%)"></div>
                        <div class="ps-icon">
                            <div class="ps-ring" style="background:conic-gradient({{ $lnc['ring'] }},transparent 58%)"></div>
                            <div class="ps-inner" style="background:{{ $lnc['inner'] }};border:1.5px solid {{ $lnc['border'] }}">
                                <svg viewBox="0 0 42 42">
                                    @if($lnType === 'success')
                                        <polyline class="ps-warn" style="stroke:{{ $lnc['stroke'] }};fill:none" points="10,22 18,30 32,14"/>
                                    @else
                                        <line class="ps-warn" style="stroke:{{ $lnc['stroke'] }}" x1="13" y1="13" x2="29" y2="29"/>
                                        <line class="ps-warn" style="stroke:{{ $lnc['stroke'] }}" x1="29" y1="13" x2="13" y2="29"/>
                                    @endif
                                </svg>
                            </div>
                        </div>
                        <h2 class="ps-title">{{ $loginNotif['title'] }}</h2>
                        <p class="ps-sub">{{ $loginNotif['text'] }}</p>
                        <div class="ps-btns">
                            <button type="button" class="ps-btn-solo" onclick="document.getElementById('ps-overlay-adm-login').remove()">OK</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const colors  = ['{{ $lnc['b1'] }}','{{ $lnc['b2'] }}','{{ $lnc['b3'] }}'];
                const hColors = ['{{ $lnc['h1'] }}','{{ $lnc['h2'] }}','{{ $lnc['h3'] }}'];
                function spawnDots(el, cols, count, cls) {
                    for (let i = 0; i < count; i++) {
                        const p = document.createElement('div');
                        const s = Math.random()*6+4;
                        p.className = cls;
                        p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
                        el.appendChild(p);
                    }
                }
                const hdr = document.getElementById('ps-hdr-adm-login');
                const bdy = document.getElementById('ps-body-adm-login');
                if (hdr) spawnDots(hdr, hColors, 10, 'ps-hdr-particle');
                if (bdy) spawnDots(bdy, colors, 20, 'ps-particle');
                const overlay = document.getElementById('ps-overlay-adm-login');
                if (overlay) overlay.addEventListener('click', function (e) { if (e.target === this) this.remove(); });
            });
            </script>
            @endif

            <form method="POST" action="{{ route('admin.handle') }}">
                @csrf
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="redirect_section" value="{{ $section }}">

                <div class="lf-field">
                    <label for="username">Username</label>
                    <div class="lf-iw">
                        <i class="lf-iw-icon fa fa-user-o"></i>
                        <input type="text" id="username" name="username" placeholder="Enter username" required autocomplete="username">
                    </div>
                </div>

                <div class="lf-field">
                    <label for="password">Password</label>
                    <div class="lf-iw">
                        <i class="lf-iw-icon fa fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required autocomplete="current-password">
                        <button type="button" class="lf-pw-toggle" onclick="(function(b){var i=document.getElementById('password');i.type=i.type==='password'?'text':'password';b.textContent=i.type==='password'?'Show':'Hide';})(this)">Show</button>
                    </div>
                </div>

                <button type="submit" class="lf-submit">Sign In to Dashboard</button>
            </form>

            <div class="lf-roles">
                Two access levels on this system
                <div class="lf-chips">
                    <span class="lf-chip lf-chip-admin">Administrator</span>
                    <span class="lf-chip lf-chip-sec">Secretary</span>
                </div>
            </div>
    </div>

    <div class="lc-footer">RESTRICTED ACCESS &middot; AUTHORIZED PERSONNEL ONLY</div>

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
    'customers'     => ['title'=>'Manage Customers',          'subtitle'=>'Review customer accounts, reservation activity, and password reset status.'],
    'reports'       => ['title'=>'Reports & Visualization', 'subtitle'=>'Monthly statistics on sacraments, reservation trends, and schedule utilization.'],
    'donations'     => ['title'=>'Donation Monitoring',    'subtitle'=>'Record and track all parish donations — who gave, how much, when, and for what purpose.'],
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
    $s = strtolower($status);
    $map = [
        'pending'  => 'spill-pending',
        'approved' => 'spill-approved',
        'declined' => 'spill-declined',
    ];
    $cls = $map[$s] ?? 'spill-pending';
    $label = ucfirst($s);
    return '<span class="status-pill '.$cls.'"><span class="spill-dot"></span>'.$label.'</span>';
}
@endphp

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <img src="{{ asset('img/about/about_1.jpg') }}" alt="Parish Logo">
            </div>
            <div class="sidebar-brand-name">St. John the Baptist Parish</div>
            <div class="sidebar-brand-sub">Administration Panel</div>
        </div>

        <div class="sidebar-divider"></div>

        <nav class="sidebar-nav">
            @if($adminRole === 'admin')
            <div class="sidebar-group-label">Dashboard</div>
            <a href="{{ route('admin.index') }}" class="{{ $section === 'overview' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-bar-chart"></i></span> Overview
            </a>
            <a href="{{ route('admin.index', ['section'=>'reports']) }}" class="{{ $section === 'reports' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-pie-chart"></i></span> Reports
            </a>
            @endif

            <div class="sidebar-group-label">Manage</div>
            <a href="{{ route('admin.index', ['section'=>'reservations']) }}" class="{{ $section === 'reservations' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-calendar"></i></span> Reservations
                @if(($summaryTotals['pending'] ?? 0) > 0)
                    <span class="nav-badge">{{ $summaryTotals['pending'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.index', ['section'=>'schedule']) }}" class="{{ $section === 'schedule' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-calendar-check-o"></i></span> Schedule
            </a>
            <a href="{{ route('admin.index', ['section'=>'customers']) }}" class="{{ $section === 'customers' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-users"></i></span> Customers
            </a>

            <div class="sidebar-group-label">Content</div>
            @if($adminRole === 'admin')
            <a href="{{ route('admin.index', ['section'=>'announcements']) }}" class="{{ $section === 'announcements' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-bullhorn"></i></span> Announcements
            </a>
            @endif
            <a href="{{ route('admin.index', ['section'=>'donations']) }}" class="{{ $section === 'donations' ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-money"></i></span> Donations
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-admin-info">
                <div class="sidebar-admin-avatar">{{ strtoupper(substr(Session::get('admin_username', 'A'), 0, 1)) }}</div>
                <div>
                    <div class="sidebar-admin-name">{{ Session::get('admin_username', 'Admin') }}</div>
                    <div class="sidebar-admin-role">{{ $adminRole === 'secretary' ? 'Secretary' : 'Administrator' }}</div>
                </div>
            </div>
            <a class="logout-button" href="{{ route('admin.logout') }}"><i class="fa fa-sign-out"></i><span>Logout</span></a>
        </div>
    </aside>

    @if(session('login_success'))
    <div class="ps-overlay" id="ps-overlay-adm-welcome">
        <div class="ps-modal">
            <div class="ps-hdr" id="ps-hdr-adm-welcome">
                <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                <span class="ps-parish">St. John the Baptist Parish</span>
                <span class="ps-dot"></span>
                <span class="ps-loc">Tiaong, Quezon</span>
            </div>
            <div class="ps-body" id="ps-body-adm-welcome">
                <div class="ps-glow" style="background:radial-gradient(circle,rgba(34,197,94,.13) 0%,transparent 70%)"></div>
                <div class="ps-icon">
                    <div class="ps-ring" style="background:conic-gradient(rgba(34,197,94,.5),rgba(134,239,172,.3) 40%,transparent 58%)"></div>
                    <div class="ps-inner" style="background:rgba(34,197,94,.1);border:1.5px solid rgba(34,197,94,.3)">
                        <svg viewBox="0 0 42 42">
                            <polyline class="ps-warn" style="stroke:#4ade80;fill:none" points="10,22 18,30 32,14"/>
                        </svg>
                    </div>
                </div>
                <h2 class="ps-title">Login Successful</h2>
                <p class="ps-sub">{{ session('login_success') }}</p>
                <div class="ps-chip"><span class="ps-chip-dot"></span>{{ Session::get('admin_username', 'Admin') }}</div>
                <div class="ps-btns">
                    <button type="button" class="ps-btn-solo" onclick="document.getElementById('ps-overlay-adm-welcome').remove()">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const colors  = ['#4ade8066','#4ade8059','rgba(255,255,255,.18)'];
        const hColors = ['rgba(255,255,255,.25)','#4ade8066','#4ade804d'];
        function spawnDots(el, cols, count, cls) {
            for (let i = 0; i < count; i++) {
                const p = document.createElement('div');
                const s = Math.random()*6+4;
                p.className = cls;
                p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
                el.appendChild(p);
            }
        }
        const hdr = document.getElementById('ps-hdr-adm-welcome');
        const bdy = document.getElementById('ps-body-adm-welcome');
        if (hdr) spawnDots(hdr, hColors, 10, 'ps-hdr-particle');
        if (bdy) spawnDots(bdy, colors, 20, 'ps-particle');
        const overlay = document.getElementById('ps-overlay-adm-welcome');
        if (overlay) overlay.addEventListener('click', function (e) { if (e.target === this) this.remove(); });
    });
    </script>
    @endif

    @if(session('deny_cancellation_success'))
    <div class="ps-overlay" id="ps-overlay-deny-success">
        <div class="ps-modal">
            <div class="ps-hdr" id="ps-hdr-deny-success">
                <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                <span class="ps-parish">St. John the Baptist Parish</span>
                <span class="ps-dot"></span>
                <span class="ps-loc">Tiaong, Quezon</span>
            </div>
            <div class="ps-body" id="ps-body-deny-success">
                <div class="ps-glow" style="background:radial-gradient(circle,rgba(34,197,94,.13) 0%,transparent 70%)"></div>
                <div class="ps-icon">
                    <div class="ps-ring" style="background:conic-gradient(rgba(34,197,94,.5),rgba(134,239,172,.3) 40%,transparent 58%)"></div>
                    <div class="ps-inner" style="background:rgba(34,197,94,.1);border:1.5px solid rgba(34,197,94,.3)">
                        <svg viewBox="0 0 42 42">
                            <polyline class="ps-warn" style="stroke:#4ade80;fill:none" points="10,22 18,30 32,14"/>
                        </svg>
                    </div>
                </div>
                <h2 class="ps-title">Request Denied</h2>
                <p class="ps-sub">{{ session('deny_cancellation_success') }}</p>
                <div class="ps-btns">
                    <button type="button" class="ps-btn-solo" onclick="document.getElementById('ps-overlay-deny-success').remove()">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const colors  = ['#4ade8066','#4ade8059','rgba(255,255,255,.18)'];
        const hColors = ['rgba(255,255,255,.25)','#4ade8066','#4ade804d'];
        function spawnDots(el, cols, count, cls) {
            for (let i = 0; i < count; i++) {
                const p = document.createElement('div');
                const s = Math.random()*6+4;
                p.className = cls;
                p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
                el.appendChild(p);
            }
        }
        const hdr = document.getElementById('ps-hdr-deny-success');
        const bdy = document.getElementById('ps-body-deny-success');
        if (hdr) spawnDots(hdr, hColors, 10, 'ps-hdr-particle');
        if (bdy) spawnDots(bdy, colors, 20, 'ps-particle');
        const overlay = document.getElementById('ps-overlay-deny-success');
        if (overlay) overlay.addEventListener('click', function (e) { if (e.target === this) this.remove(); });
    });
    </script>
    @endif

    @if(session('purge_expired_success') || session('purge_expired_error'))
    @php
        $purgeIsError = (bool) session('purge_expired_error');
        $purgeTitle   = $purgeIsError ? 'Removal Failed' : 'Expired Reservations Removed';
        $purgeText    = session($purgeIsError ? 'purge_expired_error' : 'purge_expired_success');
        $purgeGlow    = $purgeIsError ? 'rgba(220,38,38,.13)' : 'rgba(34,197,94,.13)';
        $purgeRing    = $purgeIsError ? 'rgba(220,38,38,.5),rgba(252,165,165,.3)' : 'rgba(34,197,94,.5),rgba(134,239,172,.3)';
        $purgeInner   = $purgeIsError ? 'rgba(220,38,38,.1)' : 'rgba(34,197,94,.1)';
        $purgeBorder  = $purgeIsError ? 'rgba(220,38,38,.3)' : 'rgba(34,197,94,.3)';
        $purgeStroke  = $purgeIsError ? '#f87171' : '#4ade80';
    @endphp
    <div class="ps-overlay" id="ps-overlay-purge">
        <div class="ps-modal">
            <div class="ps-hdr" id="ps-hdr-purge">
                <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                <span class="ps-parish">St. John the Baptist Parish</span>
                <span class="ps-dot"></span>
                <span class="ps-loc">Tiaong, Quezon</span>
            </div>
            <div class="ps-body" id="ps-body-purge">
                <div class="ps-glow" style="background:radial-gradient(circle,{{ $purgeGlow }} 0%,transparent 70%)"></div>
                <div class="ps-icon">
                    <div class="ps-ring" style="background:conic-gradient({{ $purgeRing }},transparent 58%)"></div>
                    <div class="ps-inner" style="background:{{ $purgeInner }};border:1.5px solid {{ $purgeBorder }}">
                        <svg viewBox="0 0 42 42">
                            @if($purgeIsError)
                                <line class="ps-warn" style="stroke:{{ $purgeStroke }}" x1="13" y1="13" x2="29" y2="29"/>
                                <line class="ps-warn" style="stroke:{{ $purgeStroke }}" x1="29" y1="13" x2="13" y2="29"/>
                            @else
                                <polyline class="ps-warn" style="stroke:{{ $purgeStroke }};fill:none" points="10,22 18,30 32,14"/>
                            @endif
                        </svg>
                    </div>
                </div>
                <h2 class="ps-title">{{ $purgeTitle }}</h2>
                <p class="ps-sub">{{ $purgeText }}</p>
                <div class="ps-btns">
                    <button type="button" class="ps-btn-solo" onclick="document.getElementById('ps-overlay-purge').remove()">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const colors  = ['{{ $purgeIsError ? "#f8717166" : "#4ade8066" }}','{{ $purgeIsError ? "#f8717159" : "#4ade8059" }}','rgba(255,255,255,.18)'];
        const hColors = ['rgba(255,255,255,.25)','{{ $purgeIsError ? "#f8717166" : "#4ade8066" }}','{{ $purgeIsError ? "#f871714d" : "#4ade804d" }}'];
        function spawnDots(el, cols, count, cls) {
            for (let i = 0; i < count; i++) {
                const p = document.createElement('div');
                const s = Math.random()*6+4;
                p.className = cls;
                p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
                el.appendChild(p);
            }
        }
        const hdr = document.getElementById('ps-hdr-purge');
        const bdy = document.getElementById('ps-body-purge');
        if (hdr) spawnDots(hdr, hColors, 10, 'ps-hdr-particle');
        if (bdy) spawnDots(bdy, colors, 20, 'ps-particle');
        const overlay = document.getElementById('ps-overlay-purge');
        if (overlay) overlay.addEventListener('click', function (e) { if (e.target === this) this.remove(); });
    });
    </script>
    @endif

    @if(session('status_update_success') || session('status_update_error'))
    @php
        $statusIsError = (bool) session('status_update_error');
        $statusTitle   = $statusIsError ? 'Update Failed' : 'Reservation Updated';
        $statusText    = session($statusIsError ? 'status_update_error' : 'status_update_success');
        $statusGlow    = $statusIsError ? 'rgba(220,38,38,.13)' : 'rgba(34,197,94,.13)';
        $statusRing    = $statusIsError ? 'rgba(220,38,38,.5),rgba(252,165,165,.3)' : 'rgba(34,197,94,.5),rgba(134,239,172,.3)';
        $statusInner   = $statusIsError ? 'rgba(220,38,38,.1)' : 'rgba(34,197,94,.1)';
        $statusBorder  = $statusIsError ? 'rgba(220,38,38,.3)' : 'rgba(34,197,94,.3)';
        $statusStroke  = $statusIsError ? '#f87171' : '#4ade80';
    @endphp
    <div class="ps-overlay" id="ps-overlay-status">
        <div class="ps-modal">
            <div class="ps-hdr" id="ps-hdr-status">
                <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                <span class="ps-parish">St. John the Baptist Parish</span>
                <span class="ps-dot"></span>
                <span class="ps-loc">Tiaong, Quezon</span>
            </div>
            <div class="ps-body" id="ps-body-status">
                <div class="ps-glow" style="background:radial-gradient(circle,{{ $statusGlow }} 0%,transparent 70%)"></div>
                <div class="ps-icon">
                    <div class="ps-ring" style="background:conic-gradient({{ $statusRing }},transparent 58%)"></div>
                    <div class="ps-inner" style="background:{{ $statusInner }};border:1.5px solid {{ $statusBorder }}">
                        <svg viewBox="0 0 42 42">
                            @if($statusIsError)
                                <line class="ps-warn" style="stroke:{{ $statusStroke }}" x1="13" y1="13" x2="29" y2="29"/>
                                <line class="ps-warn" style="stroke:{{ $statusStroke }}" x1="29" y1="13" x2="13" y2="29"/>
                            @else
                                <polyline class="ps-warn" style="stroke:{{ $statusStroke }};fill:none" points="10,22 18,30 32,14"/>
                            @endif
                        </svg>
                    </div>
                </div>
                <h2 class="ps-title">{{ $statusTitle }}</h2>
                <p class="ps-sub">{{ $statusText }}</p>
                <div class="ps-btns">
                    <button type="button" class="ps-btn-solo" onclick="document.getElementById('ps-overlay-status').remove()">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const colors  = ['{{ $statusIsError ? "#f8717166" : "#4ade8066" }}','{{ $statusIsError ? "#f8717159" : "#4ade8059" }}','rgba(255,255,255,.18)'];
        const hColors = ['rgba(255,255,255,.25)','{{ $statusIsError ? "#f8717166" : "#4ade8066" }}','{{ $statusIsError ? "#f871714d" : "#4ade804d" }}'];
        function spawnDots(el, cols, count, cls) {
            for (let i = 0; i < count; i++) {
                const p = document.createElement('div');
                const s = Math.random()*6+4;
                p.className = cls;
                p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
                el.appendChild(p);
            }
        }
        const hdr = document.getElementById('ps-hdr-status');
        const bdy = document.getElementById('ps-body-status');
        if (hdr) spawnDots(hdr, hColors, 10, 'ps-hdr-particle');
        if (bdy) spawnDots(bdy, colors, 20, 'ps-particle');
        const overlay = document.getElementById('ps-overlay-status');
        if (overlay) overlay.addEventListener('click', function (e) { if (e.target === this) this.remove(); });
    });
    </script>
    @endif

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
                @if($section === 'reports')
                    <span><i class="fa fa-calendar-check-o"></i> {{ $reportTotals['approved'] }} approved</span>
                    <span><i class="fa fa-clock-o"></i> {{ $reportTotals['pending'] }} pending</span>
                    <span><i class="fa fa-times-circle-o"></i> {{ $reportTotals['declined'] }} declined</span>
                    <span><i class="fa fa-bar-chart"></i> {{ $reportTotals['baptism'] + $reportTotals['wedding'] + $reportTotals['funeral'] }} total</span>
                @endif
                @if($section === 'donations')
                    <span><i class="fa fa-money"></i> ₱{{ number_format($donationSectionTotal, 2) }} collected</span>
                    <span><i class="fa fa-list"></i> {{ count($donationsList) }} records</span>
                @endif
            </div>
        </header>

        @if(session('flash_success') || session('flash_error'))
            <div class="flash-messages" id="flash-messages">
                @if(session('flash_success'))
                    <div class="flash flash-success" id="flash-success">
                        <div class="flash-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="flash-body">
                            <div class="flash-title">Success</div>
                            <div class="flash-msg">{{ session('flash_success') }}</div>
                        </div>
                        <button class="flash-close" onclick="this.closest('.flash').remove()" title="Dismiss">&times;</button>
                    </div>
                @endif
                @if(session('flash_error'))
                    <div class="flash flash-error" id="flash-error">
                        <div class="flash-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <div class="flash-body">
                            <div class="flash-title">Error</div>
                            <div class="flash-msg">{{ session('flash_error') }}</div>
                        </div>
                        <button class="flash-close" onclick="this.closest('.flash').remove()" title="Dismiss">&times;</button>
                    </div>
                @endif
            </div>
            <script>
                setTimeout(function() {
                    var el = document.getElementById('flash-messages');
                    if (el) { el.style.transition = 'opacity .5s'; el.style.opacity = '0'; setTimeout(function(){ el.remove(); }, 500); }
                }, 5000);
            </script>
        @endif

        {{-- ==================== OVERVIEW ==================== --}}
        @if($section === 'overview')
            <div class="summary-cards">
                <div class="summary-card card-total">
                    <h2><i class="fa fa-inbox"></i>Total Requests</h2>
                    <div class="summary-value">{{ $summaryTotals['total'] }}</div>
                    <div class="summary-caption">All reservation submissions</div>
                </div>
                <div class="summary-card card-pending">
                    <h2><span class="summary-dot"></span>Pending</h2>
                    <div class="summary-value">{{ $summaryTotals['pending'] }}</div>
                    <div class="summary-caption">Awaiting your review</div>
                </div>
                <div class="summary-card card-approved">
                    <h2><span class="summary-dot"></span>Approved</h2>
                    <div class="summary-value">{{ $summaryTotals['approved'] }}</div>
                    <div class="summary-caption">Ready to proceed</div>
                </div>
                <div class="summary-card card-declined">
                    <h2><span class="summary-dot"></span>Declined</h2>
                    <div class="summary-value">{{ $summaryTotals['declined'] }}</div>
                    <div class="summary-caption">Not moving forward</div>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="quick-actions">
                <a class="quick-action-btn" href="{{ route('admin.index', ['section'=>'reservations']) }}">
                    <i class="fa fa-clock-o"></i> View Pending
                </a>
                <a class="quick-action-btn" href="{{ route('admin.index', ['section'=>'schedule']) }}">
                    <i class="fa fa-calendar"></i> Daily Schedule
                </a>
                <a class="quick-action-btn" href="{{ route('admin.index', ['section'=>'announcements']) }}">
                    <i class="fa fa-bullhorn"></i> Add Announcement
                </a>
                <a class="quick-action-btn" href="{{ route('admin.index', ['section'=>'donations']) }}">
                    <i class="fa fa-money"></i> Record Donation
                </a>
                <a class="quick-action-btn" href="{{ route('admin.index', ['section'=>'reports']) }}">
                    <i class="fa fa-pie-chart"></i> View Reports
                </a>
            </div>

            @php
    $eventTotal = max(1, array_sum($eventCounts ?? []));
    $weddingPct = round(($eventCounts['Wedding'] ?? 0) / $eventTotal * 100);
    $baptismPct = round(($eventCounts['Baptism'] ?? 0) / $eventTotal * 100);
    $funeralPct = round(($eventCounts['Funeral'] ?? 0) / $eventTotal * 100);
@endphp

<div class="event-summary-row">

    <div class="event-card wedding">
        <div class="event-icon"><i class="fa fa-heart"></i></div>
        <div class="event-info">
            <span>Wedding reservations</span>
            <strong class="count-up" data-count="{{ $eventCounts['Wedding'] ?? 0 }}">0</strong>
            <div class="event-progress">
                <div class="event-progress-bar" style="width:{{ $weddingPct }}%"></div>
            </div>
            <span style="font-size:11px;color:#94a3b8;margin-top:3px;display:block">{{ $weddingPct }}% of total</span>
        </div>
    </div>

    <div class="event-card baptism">
        <div class="event-icon"><i class="fa fa-plus"></i></div>
        <div class="event-info">
            <span>Baptism reservations</span>
            <strong class="count-up" data-count="{{ $eventCounts['Baptism'] ?? 0 }}">0</strong>
            <div class="event-progress">
                <div class="event-progress-bar" style="width:{{ $baptismPct }}%"></div>
            </div>
            <span style="font-size:11px;color:#94a3b8;margin-top:3px;display:block">{{ $baptismPct }}% of total</span>
        </div>
    </div>

    <div class="event-card funeral">
        <div class="event-icon event-symbol"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="currentColor"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg></div>
        <div class="event-info">
            <span>Funeral reservations</span>
            <strong class="count-up" data-count="{{ $eventCounts['Funeral'] ?? 0 }}">0</strong>
            <div class="event-progress">
                <div class="event-progress-bar" style="width:{{ $funeralPct }}%"></div>
            </div>
            <span style="font-size:11px;color:#94a3b8;margin-top:3px;display:block">{{ $funeralPct }}% of total</span>
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
                                @php $evType = strtolower($r['event_type'] ?? ''); @endphp
                                <li>
                                    <div class="overview-list-primary">
                                        <div style="display:flex;align-items:center;gap:8px">
                                            <span class="overview-list-title">{{ $r['name'] ?? $r['customer_name'] ?? 'No name' }}</span>
                                            @if($evType)
                                            <span class="event-type-tag {{ $evType }}">{{ ucfirst($evType) }}</span>
                                            @endif
                                        </div>
                                        {!! adminStatusBadge($r['status'] ?? 'pending') !!}
                                    </div>
                                    <div class="overview-list-meta">
                                        <span><i class="fa fa-calendar" style="margin-right:4px;opacity:.5"></i>{{ adminFormatDate($r['preferred_date'] ?? $r['reservation_date'] ?? null) }}</span>
                                        <span><i class="fa fa-clock-o" style="margin-right:4px;opacity:.5"></i>{{ adminFormatTime($r['preferred_time'] ?? $r['reservation_time'] ?? null) }}</span>
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
                                @php
                                    $isVisible = (int)($a->show_on_home ?? 0) === 1;
                                    $preview = mb_strimwidth(strip_tags($a->content ?? $a->body ?? ''), 0, 80, '...');
                                @endphp
                                <li>
                                    <div class="overview-list-primary">
                                        <span class="overview-list-title">{{ $a->title }}</span>
                                        <span class="visibility-badge {{ $isVisible ? 'visible' : 'hidden' }}">
                                            {{ $isVisible ? 'Live' : 'Hidden' }}
                                        </span>
                                    </div>
                                    @if($preview)
                                    <div style="font-size:12px;color:#94a3b8;line-height:1.5;margin-top:2px">{{ $preview }}</div>
                                    @endif
                                    <div class="overview-list-meta">
                                        <span><i class="fa fa-clock-o" style="margin-right:4px;opacity:.5"></i>Posted {{ adminFormatCreatedAt($a->created_at ?? '') }}</span>
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
    <section class="section-card schedule-section-card">
        <div class="section-header">
            <div>
                <h2>Reservation Calendar</h2>
                <p>Click a date to view approved reservations. Past reservations are marked as done.</p>
            </div>
            <div class="schedule-view-toggle" id="scheduleViewToggle">
                <button type="button" class="schedule-view-toggle-btn is-active" id="scheduleViewCalendarBtn" data-view="calendar">
                    <i class="fa fa-th-large"></i> Calendar
                </button>
                <button type="button" class="schedule-view-toggle-btn" id="scheduleViewListBtn" data-view="list">
                    <i class="fa fa-list"></i> List
                </button>
            </div>
        </div>

        {{-- Stats bar --}}
        <div class="schedule-stats-bar">
            <div class="schedule-stat">
                <span class="schedule-stat-num {{ $scheduleTodayCount == 0 ? 'is-zero' : '' }}">{{ $scheduleTodayCount }}</span>
                <span class="schedule-stat-label">Today</span>
            </div>
            <div class="schedule-stat-divider"></div>
            <div class="schedule-stat">
                <span class="schedule-stat-num {{ $scheduleWeekCount == 0 ? 'is-zero' : '' }}">{{ $scheduleWeekCount }}</span>
                <span class="schedule-stat-label">This week</span>
            </div>
            <div class="schedule-stat-divider"></div>
            <div class="schedule-stat">
                <span class="schedule-stat-num {{ $scheduleMonthCount == 0 ? 'is-zero' : '' }}">{{ $scheduleMonthCount }}</span>
                <span class="schedule-stat-label">This month</span>
            </div>
            <div class="schedule-stat-divider"></div>
            <div class="schedule-stat">
                <span class="schedule-stat-num {{ $scheduleReservationCount == 0 ? 'is-zero' : '' }}">{{ $scheduleReservationCount }}</span>
                <span class="schedule-stat-label">Total approved</span>
            </div>
        </div>

        {{-- Event-type filter (applies to both Calendar and List views) --}}
        <div class="schedule-type-pills" id="scheduleTypePills">
            <button type="button" class="schedule-type-pill is-active" data-type="all">All types</button>
            <button type="button" class="schedule-type-pill" data-type="wedding"><span class="pill-icon" style="color:#f472b6;"><i class="fa fa-heart"></i></span>Wedding</button>
            <button type="button" class="schedule-type-pill" data-type="baptism"><span class="pill-icon" style="color:#2dd4bf;"><i class="fa fa-plus"></i></span>Baptism</button>
            <button type="button" class="schedule-type-pill" data-type="funeral"><span class="pill-icon" style="color:#64748b;"><svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg></span>Funeral</button>
        </div>

        <div id="scheduleCalendarView">
            <div class="admin-calendar-layout">
                <div class="admin-calendar-card">
                   <div class="calendar_legend mb-3">
        <span><span class="cal-legend-pulse" style="background:#22c55e;box-shadow:0 0 0 0 rgba(34,197,94,0.5);animation:legend-pulse-green 2s ease-out infinite;"></span> Today</span>
        <span><span class="cal-legend-pulse" style="background:#f59e0b;box-shadow:0 0 0 0 rgba(245,158,11,0.5);animation:legend-pulse-yellow 2s ease-out infinite;"></span> Pending</span>
        <span><span class="cal-legend-pulse" style="background:#dc2626;box-shadow:0 0 0 0 rgba(220,38,38,0.5);animation:legend-pulse-red 2s ease-out infinite;"></span> Has reservation</span>
        <span><span class="legend-dot" style="background:#cbd5e1;"></span> Past / finished</span>
    </div>

                    <div class="availability_calendar admin-availability-calendar" id="admin-schedule-calendar"></div>
                </div>

                <div class="admin-calendar-details" id="admin-calendar-details">
                    <div class="admin-calendar-empty">
                        <div class="admin-calendar-empty-icon">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                        <h4>Select a date</h4>
                        <p>Click a highlighted date to view approved reservation details.</p>
                    </div>
                </div>
            </div>

            {{-- Upcoming reservations strip --}}
            @if(count($scheduleUpcoming))
            <div class="schedule-upcoming-strip">
                <div class="schedule-upcoming-title"><i class="fa fa-clock-o"></i> Upcoming reservations</div>
                <div class="schedule-upcoming-list">
                    @foreach($scheduleUpcoming as $ur)
                        @php
                            $urDate    = $ur['preferred_date'] ?? $ur['reservation_date'] ?? null;
                            $urDateStr = $urDate ? (new DateTimeImmutable((string)$urDate))->format('Y-m-d') : null;
                            $urEt      = strtolower(trim($ur['event_type'] ?? ''));
                            $urColor   = match($urEt) { 'wedding'=>'#f472b6', 'baptism'=>'#2dd4bf', 'funeral'=>'#64748b', default=>'#94a3b8' };
                            $urLabel   = ucfirst($urEt ?: 'Unknown');
                            $urDays    = $urDateStr ? (new DateTimeImmutable($urDateStr))->diff(new DateTimeImmutable('today'))->days : null;
                            $urDaysLabel = $urDays === 0 ? 'Today' : ($urDays === 1 ? 'Tomorrow' : 'In ' . $urDays . ' days');
                        @endphp
                        <div class="schedule-upcoming-card" data-date="{{ $urDateStr }}" style="cursor:pointer;">
                            <span class="schedule-upcoming-dot" style="background:{{ $urColor }};"></span>
                            <div class="schedule-upcoming-info">
                                <span class="schedule-upcoming-name">{{ $ur['name'] ?? 'N/A' }}</span>
                                <span class="schedule-upcoming-type" style="color:{{ $urColor }};">{{ $urLabel }}</span>
                            </div>
                            <div class="schedule-upcoming-date">
                                <span class="schedule-upcoming-datestr">{{ $urDateStr ? (new DateTimeImmutable($urDateStr))->format('M j') : '—' }}</span>
                                <span class="schedule-upcoming-days">{{ $urDaysLabel }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div id="scheduleListView" style="display:none;"></div>
    </section>

    @include('admin.partials.reservation-detail-modal')
@endif

        {{-- ==================== RESERVATIONS ==================== --}}
        @if($section === 'reservations')
            <section class="section-card">
                <div class="section-header">
                    <div><h2>Reservations</h2><p>Review reservation requests grouped by their current status.</p></div>
                    <div class="header-meta" style="display:flex;align-items:center;gap:12px;">
                        <span><i class="fa fa-clock-o"></i> {{ $filteredTotals['pending'] }} pending</span>
                        <button type="button" onclick="document.getElementById('purge-confirm-overlay').style.display='flex'"
                            style="display:inline-flex;align-items:center;gap:7px;padding:7px 16px;border-radius:999px;border:1.5px solid #fca5a5;background:transparent;color:#dc2626;font-family:'Raleway',sans-serif;font-size:.75rem;font-weight:700;letter-spacing:.03em;cursor:pointer;transition:background .18s,color .18s;"
                            onmouseover="this.style.background='#dc2626';this.style.color='#fff';this.style.borderColor='#dc2626';"
                            onmouseout="this.style.background='transparent';this.style.color='#dc2626';this.style.borderColor='#fca5a5';">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            Remove Expired Pending
                        </button>
                    </div>
                </div>

                {{-- Purge confirm modal --}}
                <div id="purge-confirm-overlay" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(6,13,26,.75);backdrop-filter:blur(6px);align-items:center;justify-content:center;"
                     onclick="if(event.target===this)this.style.display='none'">
                    <div style="background:#0f172a;border-radius:22px;max-width:420px;width:92%;overflow:hidden;box-shadow:0 40px 90px rgba(0,0,0,.7),0 0 0 1px rgba(255,255,255,.07);animation:purge-pop .2s cubic-bezier(.34,1.56,.64,1);font-family:'Raleway',system-ui,sans-serif;">
                        <style>@keyframes purge-pop{from{transform:scale(.92);opacity:0}to{transform:scale(1);opacity:1}}</style>

                        {{-- Header --}}
                        <div class="ps-hdr">
                            <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                            <span class="ps-parish">St. John the Baptist Parish</span>
                            <span class="ps-dot"></span>
                            <span class="ps-loc">Tiaong, Quezon</span>
                        </div>

                        {{-- Title --}}
                        <div style="padding:18px 22px 0;display:flex;align-items:center;gap:12px;">
                            <div style="width:38px;height:38px;border-radius:11px;background:rgba(220,38,38,.14);border:1.5px solid rgba(220,38,38,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#f87171;">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </div>
                            <div>
                                <div style="font-size:.62rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:#f87171;margin-bottom:3px;">Confirmation Required</div>
                                <div style="font-size:1.05rem;font-weight:800;color:#fff;">Remove Expired Pending</div>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div style="padding:18px 22px 22px;">
                            <p style="margin:0 0 8px;color:#f1f5f9;font-size:.9rem;font-weight:600;line-height:1.5;">All pending reservations with a past date will be permanently deleted.</p>
                            <p style="margin:0 0 22px;color:rgba(255,255,255,.4);font-size:.8rem;line-height:1.65;">This action cannot be undone. Only reservations with <strong style="color:rgba(255,255,255,.6);">pending</strong> status and a date before today will be affected.</p>

                            <div style="display:flex;gap:10px;justify-content:flex-end;">
                                <button type="button" class="adm-cfm-cancel" style="flex:none;padding:9px 22px;"
                                    onclick="document.getElementById('purge-confirm-overlay').style.display='none'">
                                    Cancel
                                </button>
                                <form method="POST" action="{{ route('admin.index') }}" style="margin:0;">
                                    @csrf
                                    <input type="hidden" name="action" value="purge_expired_pending">
                                    <button type="submit" class="adm-cfm-ok" style="flex:none;padding:9px 22px;display:inline-flex;align-items:center;gap:7px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                        Yes, Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="reservation-toolbar">
                    <form method="GET" action="{{ route('admin.index') }}" class="reservation-filter-form">
                        <input type="hidden" name="section" value="reservations">
                        {{-- Show dropdown --}}
                        <div class="form-group">
                            <label>Show</label>
                            <input type="hidden" name="range" id="reservation_filter_range" value="{{ $reservationFilterRange }}" data-reservation-filter-range>
                            <div class="adm-csl" id="adm-csl-range">
                                <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-range')">
                                    <span class="adm-csl-label">{{ $reservationFilterRange === 'today' ? 'Today' : ($reservationFilterRange === 'week' ? 'This week' : ($reservationFilterRange === 'date' ? 'Specific date' : 'All reservations')) }}</span>
                                    <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <ul class="adm-csl-list">
                                    <li class="{{ $reservationFilterRange === 'all'   ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-range','reservation_filter_range','all','All reservations')">All reservations</li>
                                    <li class="{{ $reservationFilterRange === 'today' ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-range','reservation_filter_range','today','Today')">Today</li>
                                    <li class="{{ $reservationFilterRange === 'week'  ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-range','reservation_filter_range','week','This week')">This week</li>
                                    <li class="{{ $reservationFilterRange === 'date'  ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-range','reservation_filter_range','date','Specific date')">Specific date</li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group reservation-date-field" data-reservation-filter-date-wrapper>
                            <label>Date</label>
                            <input type="hidden" id="reservation_filter_date" name="date" value="{{ $reservationFilterDate ?? '' }}">
                            <div class="adm-cal-wrap" id="adm-cal-wrap">
                                <button type="button" class="adm-csl-btn" id="adm-cal-trigger" onclick="admCalToggle()">
                                    <span class="adm-csl-label" id="adm-cal-label">{{ $reservationFilterDate ? \Carbon\Carbon::parse($reservationFilterDate)->format('M d, Y') : 'Pick a date' }}</span>
                                    <svg class="adm-csl-arrow" id="adm-cal-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div class="adm-cal-popup" id="adm-cal-popup" style="display:none;">
                                    <div class="adm-cal-header">
                                        <button type="button" class="adm-cal-nav" onclick="admCalShift(-1)">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                                        </button>
                                        <span class="adm-cal-month-label" id="adm-cal-month-label"></span>
                                        <button type="button" class="adm-cal-nav" onclick="admCalShift(1)">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                        </button>
                                    </div>
                                    <div class="adm-cal-days-row">
                                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                                    </div>
                                    <div class="adm-cal-grid" id="adm-cal-grid"></div>
                                    <div class="adm-cal-footer">
                                        <button type="button" class="adm-cal-today-btn" onclick="admCalSelectToday()">Today</button>
                                        <button type="button" class="adm-cal-clear-btn" onclick="admCalClear()">Clear</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Event type dropdown --}}
                        <div class="form-group">
                            <label>Event type</label>
                            <input type="hidden" name="event_type" id="reservation_filter_type" value="{{ $reservationFilterType }}">
                            <div class="adm-csl" id="adm-csl-type">
                                <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-type')">
                                    <span class="adm-csl-label">{{ $reservationFilterType === 'baptism' ? 'Baptism' : ($reservationFilterType === 'wedding' ? 'Wedding' : ($reservationFilterType === 'funeral' ? 'Funeral' : 'All types')) }}</span>
                                    <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <ul class="adm-csl-list">
                                    <li class="{{ $reservationFilterType === 'all'     ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-type','reservation_filter_type','all','All types')">All types</li>
                                    <li class="{{ $reservationFilterType === 'baptism' ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-type','reservation_filter_type','baptism','Baptism')">Baptism</li>
                                    <li class="{{ $reservationFilterType === 'wedding' ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-type','reservation_filter_type','wedding','Wedding')">Wedding</li>
                                    <li class="{{ $reservationFilterType === 'funeral' ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-type','reservation_filter_type','funeral','Funeral')">Funeral</li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Sort by</label>
                            <div class="adm-csl" id="adm-csl-sort">
                                <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-sort')">
                                    <span class="adm-csl-label">{{ $reservationSort === 'oldest' ? 'Oldest first' : 'Latest first' }}</span>
                                    <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <ul class="adm-csl-list">
                                    <li class="{{ $reservationSort === 'latest' ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-sort','reservation_filter_sort','latest','Latest first')">Latest first</li>
                                    <li class="{{ $reservationSort === 'oldest' ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-sort','reservation_filter_sort','oldest','Oldest first')">Oldest first</li>
                                </ul>
                                <input type="hidden" name="sort" id="reservation_filter_sort" value="{{ $reservationSort }}">
                            </div>
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

                @php
                    $pendingCount  = count($filteredGrouped['pending']  ?? []);
                    $approvedCount = count($filteredGrouped['approved'] ?? []);
                    $declinedCount = count($filteredGrouped['declined'] ?? []);
                    $totalCount    = $pendingCount + $approvedCount + $declinedCount;
                @endphp
                @if($totalCount === 0)
                    <p class="empty-block">
                        {{ $summaryTotals['total'] === 0 ? 'No reservations have been submitted yet.' : 'No reservations match the selected filters.' }}
                    </p>
                @else
                    {{-- Tab filter + View requests aligned in one row --}}
                    <div class="res-tab-bar" style="justify-content:space-between;">
                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            <button class="res-tab-btn active-all" onclick="resTabFilter('all',this)">
                                All <span class="res-tab-count">{{ $totalCount }}</span>
                            </button>
                            <button class="res-tab-btn" onclick="resTabFilter('pending',this)">
                                Pending <span class="res-tab-count">{{ $pendingCount }}</span>
                            </button>
                            <button class="res-tab-btn" onclick="resTabFilter('approved',this)">
                                Approved <span class="res-tab-count">{{ $approvedCount }}</span>
                            </button>
                            <button class="res-tab-btn" onclick="resTabFilter('declined',this)">
                                Declined <span class="res-tab-count">{{ $declinedCount }}</span>
                            </button>
                        </div>
                        @if($cancelRequestCount > 0)
                        <button type="button" id="view-cancel-btn" onclick="toggleCancelView(this)"
                            style="flex-shrink:0;font-size:.75rem;font-weight:700;color:#fff;white-space:nowrap;padding:8px 18px;border-radius:999px;border:none;background:#dc2626;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:background .15s;"
                            onmouseover="this.style.background='#b91c1c'" onmouseout="if(!this.dataset.active)this.style.background='#dc2626';else this.style.background='#9f1239'">
                            <i class="fa fa-eye"></i> View requests
                            <span style="background:#fff;color:#dc2626;border-radius:999px;font-size:.65rem;font-weight:800;padding:0 6px;min-width:18px;height:18px;display:inline-flex;align-items:center;justify-content:center;">{{ $cancelRequestCount }}</span>
                        </button>
                        @endif
                    </div>

                    {{-- Flat card list (all statuses) --}}
                    <div id="res-cards-container">
                    @foreach(['pending','approved','declined'] as $statusKey)
                        @foreach($filteredGrouped[$statusKey] ?? [] as $r)
                    @php
                        $evDate        = $r['preferred_date'] ?? $r['reservation_date'] ?? null;
                        $isPastEvent   = $evDate && $evDate < date('Y-m-d');
                        $evType        = strtolower($r['event_type'] ?? '');
                        $daysUntil     = $evDate ? (int) ceil((strtotime($evDate) - strtotime('today')) / 86400) : null;
                        $isUrgent      = $statusKey === 'pending' && $daysUntil !== null && $daysUntil >= 0 && $daysUntil <= 7;
                        $evTypeColors  = [
                            'wedding' => ['bg'=>'#fce7f3','color'=>'#be185d','border'=>'#fbcfe8'],
                            'baptism' => ['bg'=>'#dcfce7','color'=>'#15803d','border'=>'#bbf7d0'],
                            'funeral' => ['bg'=>'#f1f5f9','color'=>'#475569','border'=>'#cbd5e1'],
                        ];
                        $evColor = $evTypeColors[$evType] ?? ['bg'=>'#f1f5f9','color'=>'#64748b','border'=>'#e2e8f0'];
                        $currentOfficiant = (int)($r['officiant_id'] ?? 0);
                        $officiantName = '';
                        if ($currentOfficiant) {
                            foreach ($priests as $p) {
                                if ((int)($p['id'] ?? 0) === $currentOfficiant) {
                                    $officiantName = ($p['title'] ?? 'Fr.') . ' ' . ($p['name'] ?? '');
                                    break;
                                }
                            }
                        }
                    @endphp
                        <div class="reservation-card" data-status="{{ $statusKey }}" data-cancel="{{ !empty($r['cancellation_requested']) ? '1' : '0' }}">
                            <div class="rcard-stripe rcard-stripe-{{ $statusKey }}"></div>

                            {{-- IDENTITY COLUMN --}}
                            <div class="rcard-identity">
                                <div class="rcard-badges">
                                    <div class="status-badge">{!! adminStatusBadge($r['status']) !!}</div>
                                    @if($evType)
                                    <span style="display:inline-flex;align-items:center;gap:6px;">
                                        <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:7px;background:{{ $evColor['bg'] }};color:{{ $evColor['color'] }};border:1.5px solid {{ $evColor['border'] }};font-size:10px;flex-shrink:0;">
                                            @if($evType==='funeral')<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="currentColor"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg>@elseif($evType==='wedding')<i class="fa fa-heart"></i>@else<i class="fa fa-plus"></i>@endif
                                        </span>
                                        <span style="font-size:.65rem;font-weight:700;color:{{ $evColor['color'] }};text-transform:uppercase;letter-spacing:.05em;">{{ ucfirst($evType) }}</span>
                                    </span>
                                    @endif
                                    @if($isUrgent)
                                    <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:999px;font-size:.62rem;font-weight:700;text-transform:uppercase;background:rgba(239,68,68,.18);color:#f87171;border:1px solid rgba(239,68,68,.3)"><i class="fa fa-exclamation-circle"></i> Urgent</span>
                                    @endif
                                </div>

                                <div class="rcard-name">{{ $r['name'] ?? $r['customer_name'] ?? 'No name' }}<span class="rcard-id-span">#{{ $r['id'] ?? '—' }}</span></div>

                                <div class="rcard-info-rows">
                                    <div class="rcard-info-row">
                                        <i class="fa fa-envelope"></i>
                                        @if(!empty($r['email']))<a href="mailto:{{ $r['email'] }}">{{ $r['email'] }}</a>@else<span class="muted-text">Not provided</span>@endif
                                    </div>
                                    <div class="rcard-info-row">
                                        <i class="fa fa-phone"></i>
                                        @if(!empty($r['phone']))<a href="tel:{{ $r['phone'] }}">{{ $r['phone'] }}</a>@else<span class="muted-text">Not provided</span>@endif
                                    </div>
                                    @if(!empty($r['notes']))<div class="reservation-notes">{{ $r['notes'] }}</div>@endif
                                </div>

                                @if($daysUntil !== null)
                                <span class="rcard-rel {{ $daysUntil < 0 ? 'rcard-rel-past' : 'rcard-rel-soon' }}">
                                    @if($daysUntil < 0) {{ abs($daysUntil) }}d ago
                                    @elseif($daysUntil === 0) Today
                                    @elseif($daysUntil === 1) Tomorrow
                                    @elseif($daysUntil < 14) In {{ $daysUntil }} days
                                    @else In {{ ceil($daysUntil / 7) }} weeks
                                    @endif
                                </span>
                                @endif
                            </div>

                            {{-- SCHEDULE COLUMN --}}
                            <div class="rcard-schedule">
                                <div class="rcard-col-label">Schedule</div>

                                <div class="sched-item">
                                    <div class="sched-icon-box">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    </div>
                                    <div>
                                        <div class="sched-val">{{ adminFormatDate($r['preferred_date'] ?? $r['reservation_date'] ?? null) }}</div>
                                        <div class="sched-sub">{{ $evDate ? date('l', strtotime($evDate)) : '—' }}</div>
                                    </div>
                                </div>

                                <div class="sched-item">
                                    <div class="sched-icon-box">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    </div>
                                    <div>
                                        <div class="sched-val">{{ adminFormatTime($r['preferred_time'] ?? $r['reservation_time'] ?? null) }}</div>
                                        <div class="sched-sub">Time</div>
                                    </div>
                                </div>

                                <div class="sched-item" style="margin-bottom:0">
                                    <div class="sched-icon-box">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        @if($statusKey !== 'declined' && count($priests) > 0)
                                        <div class="officiant-form">
                                            <label>Officiant</label>
                                            <form method="POST" action="{{ route('admin.reservations.officiant', $r['id']) }}" id="off-form-{{ $r['id'] }}" style="margin:0;">
                                                @csrf
                                                <input type="hidden" name="officiant_id" id="off-val-{{ $r['id'] }}" value="{{ $currentOfficiant ?: '' }}">
                                                <div class="adm-csl" id="adm-csl-off-{{ $r['id'] }}">
                                                    <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-off-{{ $r['id'] }}')">
                                                        <span class="adm-csl-label">{{ $officiantName ?: 'Assign an Officiant' }}</span>
                                                        <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                                    </button>
                                                    <ul class="adm-csl-list">
                                                        <li class="{{ !$currentOfficiant ? 'is-selected' : '' }}"
                                                            @if($currentOfficiant) onclick="admCslSelectAndSubmit('adm-csl-off-{{ $r['id'] }}','off-val-{{ $r['id'] }}','','Assign an Officiant','off-form-{{ $r['id'] }}')" @else style="opacity:.4;cursor:default;pointer-events:none;" @endif>Assign an Officiant</li>
                                                        @foreach($priests as $p)
                                                        @php $pLabel = ($p['title'] ?? 'Fr.') . ' ' . ($p['name'] ?? ''); $pLabelJs = str_replace("'", "\\'", $pLabel); @endphp
                                                        <li class="{{ $currentOfficiant === (int)($p['id'] ?? 0) ? 'is-selected' : '' }}"
                                                            onclick="admCslSelectAndSubmit('adm-csl-off-{{ $r['id'] }}','off-val-{{ $r['id'] }}','{{ $p['id'] }}','{{ $pLabelJs }}','off-form-{{ $r['id'] }}')">{{ $pLabel }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </form>
                                        </div>
                                        @else
                                        <div class="sched-sub">Officiant</div>
                                        <div class="officiant-static">{{ $officiantName ?: 'Unassigned' }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ACTIONS COLUMN --}}
                            <div class="rcard-actions-col">
                                <div class="rcard-col-label">Admin note</div>

                                @if(!empty($r['admin_note']))
                                <div class="admin-note-prev">
                                    <div class="admin-note-prev-label">{{ $statusKey === 'declined' ? 'Reason for decline' : 'Previous note' }}</div>
                                    {{ $r['admin_note'] }}
                                </div>
                                @endif

                                {{-- Cancellation section (hidden until View Requests clicked) --}}
                                @if(!empty($r['cancellation_requested']))
                                <div class="cancel-section" id="cancel-section-{{ $r['id'] }}">
                                    <div class="cancel-section-title"><i class="fa fa-exclamation-triangle"></i> Cancellation Requested</div>
                                    @if(!empty($r['cancel_reason']))<div class="cancel-section-reason">{{ $r['cancel_reason'] }}</div>@endif
                                    <div class="cancel-section-btns">
                                        <form method="POST" action="{{ route('admin.handle') }}" style="margin:0;">
                                            @csrf
                                            <input type="hidden" name="action" value="approve_cancellation">
                                            <input type="hidden" name="reservation_id" value="{{ $r['id'] }}">
                                            <input type="hidden" name="admin_note" value="Your cancellation request has been approved.">
                                            <input type="hidden" name="redirect_section" value="reservations">
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="openDenyModal({{ $r['id'] }})">Deny</button>
                                    </div>
                                </div>
                                <div class="dcm-overlay" id="deny-overlay-{{ $r['id'] }}" onclick="if(event.target===this) closeDenyModal({{ $r['id'] }})">
                                    <div class="dcm-modal">
                                        <div class="adm-cfm-hdr">
                                            <svg class="adm-cfm-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                                            <span class="adm-cfm-parish">St. John the Baptist Parish</span>
                                            <span class="adm-cfm-dot"></span>
                                            <span class="adm-cfm-loc">Tiaong, Quezon</span>
                                        </div>
                                        <div class="adm-cfm-body dcm-scroll">
                                            <div class="adm-cfm-glow"></div>
                                            <div class="adm-cfm-icon">
                                                <div class="adm-cfm-ring"></div>
                                                <div class="adm-cfm-inner">
                                                    <svg width="38" height="38" viewBox="0 0 38 38" fill="none">
                                                        <line class="adm-cfm-chk" x1="12" y1="12" x2="26" y2="26"/>
                                                        <line class="adm-cfm-chk" x1="26" y1="12" x2="12" y2="26"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="adm-cfm-title">Deny Cancellation</div>
                                            <p class="adm-cfm-sub">This will reject the cancellation request and keep the reservation active.</p>
                                            <div class="ps-chip"><span class="ps-chip-dot"></span>{{ $r['name'] ?? 'Customer' }}</div>
                                            <form method="POST" action="{{ route('admin.handle') }}" style="width:100%;text-align:left">
                                                @csrf
                                                <input type="hidden" name="action" value="deny_cancellation">
                                                <input type="hidden" name="reservation_id" value="{{ $r['id'] }}">
                                                <input type="hidden" name="redirect_section" value="reservations">
                                                <label class="dcm-label">Reason for denying</label>
                                                <textarea name="admin_note" class="dcm-textarea" rows="3" placeholder="e.g. The reservation is already confirmed and scheduled…" required></textarea>
                                                <div class="adm-cfm-btns">
                                                    <button type="button" class="adm-cfm-cancel" onclick="closeDenyModal({{ $r['id'] }})">Cancel</button>
                                                    <button type="submit" class="adm-cfm-ok"><i class="fa fa-times-circle"></i> Deny Request</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <textarea class="admin-note-textarea" rows="2"
                                    placeholder="Optional note before approving or declining…"
                                    oninput="document.querySelectorAll('.admin-note-{{ $r['id'] }}').forEach(el => el.value = this.value)"></textarea>

                                <div class="rcard-bottom">
                                    @php
                                        $rDate = $r['reservation_date'] ?? $r['preferred_date'] ?? '';
                                        $isOneDayBefore = $rDate && \Carbon\Carbon::parse($rDate)->startOfDay()->lte(\Carbon\Carbon::tomorrow()->startOfDay());
                                        $rdmAtts = collect($r['attachments'] ?? [])->map(function ($a) {
                                            $path = $a['file_url'] ?? '';
                                            $file = $path ? preg_replace('/^\d+_[a-zA-Z0-9]+_/', '', basename(parse_url($path, PHP_URL_PATH))) : '';
                                            return ['label' => $a['label'] ?? 'Attachment', 'file' => $file, 'path' => $path];
                                        })->values()->all();
                                        $rdmDetails = $r['details'] ?? [];
                                        if (is_string($rdmDetails)) $rdmDetails = json_decode($rdmDetails, true) ?? [];
                                        $rdmBaptism = $rdmDetails['baptism'] ?? null;
                                        $rdmWedding = $rdmDetails['wedding'] ?? null;
                                        $rdmFuneral = $rdmDetails['funeral'] ?? null;
                                        $rdmData = json_encode([
                                            'id'               => $r['id'],
                                            'name'             => $r['name'] ?? $r['customer_name'] ?? '',
                                            'email'            => $r['email'] ?? '',
                                            'phone'            => $r['phone'] ?? '',
                                            'status'           => $statusKey,
                                            'event_type'       => $r['event_type'] ?? '',
                                            'date'             => adminFormatDate($r['preferred_date'] ?? $r['reservation_date'] ?? null),
                                            'date_day'         => $evDate ? date('l', strtotime($evDate)) : '',
                                            'time'             => adminFormatTime($r['preferred_time'] ?? $r['reservation_time'] ?? null),
                                            'officiant'        => $officiantName,
                                            'notes'            => $r['notes'] ?? '',
                                            'admin_note'       => $r['admin_note'] ?? '',
                                            'is_urgent'        => $isUrgent,
                                            'is_one_day_before'=> $isOneDayBefore,
                                            'created_at'       => $r['created_at'] ?? '',
                                            'attachments'      => $rdmAtts,
                                            'baptism'          => $rdmBaptism,
                                            'wedding'          => $rdmWedding,
                                            'funeral'          => $rdmFuneral,
                                        ], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP);
                                    @endphp
                                    <button type="button" class="view-link" data-res="{{ $rdmData }}" onclick="openResDetail(JSON.parse(this.dataset.res))">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        View details
                                    </button>
                                    <div class="rcard-status-btns">
                                        @if($isOneDayBefore)
                                            <span style="font-size:.72rem;color:#dc2626;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                                Cannot approve — event is tomorrow or past
                                            </span>
                                        @else
                                        @if($r['status'] !== 'approved')
                                            @if(!empty($r['officiant_id']))
                                            <form method="POST" action="{{ route('admin.handle') }}">
                                                @csrf
                                                <input type="hidden" name="action" value="update_status">
                                                <input type="hidden" name="reservation_id" value="{{ $r['id'] }}">
                                                <input type="hidden" name="status" value="approved">
                                                <input type="hidden" name="admin_note" class="admin-note-{{ $r['id'] }}">
                                                <input type="hidden" name="redirect_section" value="reservations">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Approve
                                                </button>
                                            </form>
                                            @else
                                            <button class="btn btn-success btn-sm" disabled title="Assign a priest first" style="opacity:.35;cursor:not-allowed">Approve</button>
                                            @endif
                                        @endif
                                        @if($r['status'] !== 'declined')
                                        <form method="POST" action="{{ route('admin.handle') }}">
                                            @csrf
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="reservation_id" value="{{ $r['id'] }}">
                                            <input type="hidden" name="status" value="declined">
                                            <input type="hidden" name="admin_note" class="admin-note-{{ $r['id'] }}">
                                            <input type="hidden" name="redirect_section" value="reservations">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>Decline
                                            </button>
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
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                    </div>
                @endif
            </section>

            {{-- Cancellation Requests Overlay Panel --}}
            @if($cancelRequestCount > 0)
            <div id="cancel-overlay-backdrop" onclick="closeCancelPanel()"></div>
            <div id="cancel-overlay-panel">
                {{-- Header --}}
                <div class="cpanel-header">
                    <div class="cpanel-htitle" style="display:flex;align-items:center;gap:9px;">
                        <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                        <span class="ps-parish">St. John the Baptist Parish</span>
                        <span class="ps-dot"></span>
                        <span class="ps-loc">Tiaong, Quezon</span>
                    </div>
                    <button class="cpanel-close" onclick="closeCancelPanel()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="cpanel-section">
                    <div class="cpanel-eyebrow">Cancellation Requests</div>
                    <div class="cpanel-title">{{ $cancelRequestCount }} pending {{ Str::plural('request', $cancelRequestCount) }}</div>
                </div>
                {{-- Body --}}
                <div class="cpanel-body">
                    @foreach(['pending','approved','declined'] as $sk)
                        @foreach($filteredGrouped[$sk] ?? [] as $r)
                            @if(!empty($r['cancellation_requested']))
                            @php
                                $cEvDate   = $r['preferred_date'] ?? $r['reservation_date'] ?? null;
                                $cName     = $r['name'] ?? 'Customer';
                                $cRawType  = strtolower($r['event_type'] ?? '');
                                $cType     = ucfirst($cRawType);
                                $cTimeStr  = '';
                                if (!empty($r['time_start']) && !empty($r['time_end'])) {
                                    $cTimeStr = \Carbon\Carbon::parse($r['time_start'])->format('g:i A') . ' – ' . \Carbon\Carbon::parse($r['time_end'])->format('g:i A');
                                } elseif (!empty($r['time_start'])) {
                                    $cTimeStr = \Carbon\Carbon::parse($r['time_start'])->format('g:i A');
                                }
                                $cBadgeMap = [
                                    'wedding' => ['bg'=>'rgba(244,114,182,.14)','color'=>'#f9a8d4','border'=>'rgba(244,114,182,.3)','icon'=>'fa-heart',   'emoji'=>null],
                                    'baptism' => ['bg'=>'rgba(74,222,128,.14)', 'color'=>'#86efac','border'=>'rgba(74,222,128,.3)', 'icon'=>'fa-plus',    'emoji'=>null],
                                    'funeral' => ['bg'=>'rgba(148,163,184,.14)','color'=>'#cbd5e1','border'=>'rgba(148,163,184,.3)','icon'=>null,         'emoji'=>null,'svg'=>true],
                                ];
                                $cBadge = $cBadgeMap[$cRawType] ?? ['bg'=>'rgba(255,255,255,.08)','color'=>'rgba(255,255,255,.6)','border'=>'rgba(255,255,255,.16)','icon'=>'fa-calendar','emoji'=>null];
                            @endphp
                            <div class="creq-card">
                                <div class="creq-inner">
                                    <div class="creq-top">
                                        <div style="flex:1;min-width:0;">
                                            <div class="creq-name">{{ $cName }}</div>
                                            {{-- Contact info --}}
                                            <div style="display:flex;flex-direction:column;gap:4px;margin-top:7px;">
                                                @if(!empty($r['email']))
                                                <div class="creq-detail" style="display:flex;align-items:center;gap:6px;">
                                                    <span style="width:18px;height:18px;border-radius:6px;background:rgba(220,38,38,.14);color:#f87171;display:inline-flex;align-items:center;justify-content:center;font-size:9px;flex-shrink:0;"><i class="fa fa-envelope"></i></span>
                                                    {{ $r['email'] }}
                                                </div>
                                                @endif
                                                @if(!empty($r['phone']))
                                                <div class="creq-detail" style="display:flex;align-items:center;gap:6px;">
                                                    <span style="width:18px;height:18px;border-radius:6px;background:rgba(220,38,38,.14);color:#f87171;display:inline-flex;align-items:center;justify-content:center;font-size:9px;flex-shrink:0;"><i class="fa fa-phone"></i></span>
                                                    {{ $r['phone'] }}
                                                </div>
                                                @endif
                                                <div class="creq-detail" style="display:flex;align-items:center;gap:6px;">
                                                    <span style="width:18px;height:18px;border-radius:6px;background:rgba(220,38,38,.14);color:#f87171;display:inline-flex;align-items:center;justify-content:center;font-size:9px;flex-shrink:0;"><i class="fa fa-calendar"></i></span>
                                                    {{ $cEvDate ? \Carbon\Carbon::parse($cEvDate)->format('M d, Y') : 'No date' }}
                                                </div>
                                                @if($cTimeStr)
                                                <div class="creq-detail" style="display:flex;align-items:center;gap:6px;">
                                                    <span style="width:18px;height:18px;border-radius:6px;background:rgba(220,38,38,.14);color:#f87171;display:inline-flex;align-items:center;justify-content:center;font-size:9px;flex-shrink:0;"><i class="fa fa-clock-o"></i></span>
                                                    {{ $cTimeStr }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- Colored sacrament badge --}}
                                        <span style="padding:5px 11px;border-radius:999px;font-size:.65rem;font-weight:700;border:1.5px solid {{ $cBadge['border'] }};color:{{ $cBadge['color'] }};background:{{ $cBadge['bg'] }};white-space:nowrap;flex-shrink:0;display:inline-flex;align-items:center;gap:5px;">
                                            @if(!empty($cBadge['svg']))<span style="font-size:11px;line-height:1;display:inline-flex;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="currentColor"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg></span>@elseif($cBadge['emoji'])<span style="font-size:11px;line-height:1;">{{ $cBadge['emoji'] }}</span>@else<i class="fa {{ $cBadge['icon'] }}" style="font-size:9px;"></i>@endif {{ $cType }}
                                        </span>
                                    </div>
                                    <div class="creq-reason">
                                        <div class="creq-reason-label"><i class="fa fa-info-circle"></i> Reason</div>
                                        @if(!empty($r['cancel_reason']))
                                            <div class="creq-reason-text">{{ $r['cancel_reason'] }}</div>
                                        @else
                                            <div class="creq-reason-empty">No reason provided.</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="creq-actions">
                                    <form method="POST" action="{{ route('admin.handle') }}" style="flex:1;display:flex;margin:0;">
                                        @csrf
                                        <input type="hidden" name="action" value="approve_cancellation">
                                        <input type="hidden" name="reservation_id" value="{{ $r['id'] }}">
                                        <input type="hidden" name="admin_note" value="Your cancellation request has been approved.">
                                        <input type="hidden" name="redirect_section" value="reservations">
                                        <button type="submit" class="creq-btn creq-btn-approve">
                                            <i class="fa fa-check"></i> Approve cancellation
                                        </button>
                                    </form>
                                    <button type="button" class="creq-btn creq-btn-deny"
                                        onclick="closeCancelPanel();openDenyModal({{ $r['id'] }})">
                                        <i class="fa fa-times"></i> Deny
                                    </button>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
                {{-- Footer --}}
                <div class="cpanel-footer">
                    <div class="cpanel-footer-icon">⚠</div>
                    <div class="cpanel-footer-note">Approving a cancellation will permanently remove the reservation and cannot be undone.</div>
                </div>
            </div>
            @endif

            @include('admin.partials.reservation-detail-modal')
        @endif
{{-- ==================== REPORTS ==================== --}}
@if($section === 'reports')
@php
    $rptLabels   = array_keys($reportMonthlyData);
    $rptBaptism  = array_values(array_column($reportMonthlyData, 'baptism'));
    $rptWedding  = array_values(array_column($reportMonthlyData, 'wedding'));
    $rptFuneral  = array_values(array_column($reportMonthlyData, 'funeral'));
    $rptApproved = array_values(array_column($reportMonthlyData, 'approved'));
    $rptPending  = array_values(array_column($reportMonthlyData, 'pending'));
    $rptDeclined = array_values(array_column($reportMonthlyData, 'declined'));

    $rptDayLabels = array_keys($reportDayData);
    $rptDayBap    = array_values(array_column($reportDayData, 'baptism'));
    $rptDayWed    = array_values(array_column($reportDayData, 'wedding'));
    $rptDayFun    = array_values(array_column($reportDayData, 'funeral'));

    $rptTotal  = $reportTotals['total'];
    $rptPieBap = $rptTotal > 0 ? round($reportTotals['baptism'] / $rptTotal * 100) : 0;
    $rptPieWed = $rptTotal > 0 ? round($reportTotals['wedding'] / $rptTotal * 100) : 0;
    $rptPieFun = $rptTotal > 0 ? round($reportTotals['funeral'] / $rptTotal * 100) : 0;

    $rptPeakMonth = '—'; $rptPeakVal = 0;
    $rptPeakBapM  = '—'; $rptPeakBapV = 0;
    $rptPeakWedM  = '—'; $rptPeakWedV = 0;
    $rptPeakFunM  = '—'; $rptPeakFunV = 0;
    foreach ($reportMonthlyData as $mn => $md) {
        $mt = $md['baptism'] + $md['wedding'] + $md['funeral'];
        if ($mt > $rptPeakVal)       { $rptPeakVal = $mt;          $rptPeakMonth = $mn; }
        if ($md['baptism'] > $rptPeakBapV) { $rptPeakBapV = $md['baptism']; $rptPeakBapM = $mn; }
        if ($md['wedding'] > $rptPeakWedV) { $rptPeakWedV = $md['wedding']; $rptPeakWedM = $mn; }
        if ($md['funeral'] > $rptPeakFunV) { $rptPeakFunV = $md['funeral']; $rptPeakFunM = $mn; }
    }

    $rptMaxBap = max(1, max($rptBaptism ?: [0]));
    $rptMaxWed = max(1, max($rptWedding ?: [0]));
    $rptMaxFun = max(1, max($rptFuneral ?: [0]));

    $rptBusiestDay = '—'; $rptBusiestMax = 0;
    foreach ($reportDayData as $d => $dd) {
        if ($dd['total'] > $rptBusiestMax) { $rptBusiestMax = $dd['total']; $rptBusiestDay = $d; }
    }

    $rptTypeIcon = function(string $et): string {
        return match($et) {
            'wedding' => '<i class="fa fa-heart"></i>',
            'baptism' => '<i class="fa fa-plus"></i>',
            'funeral' => '<svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:-1px;"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg>',
            default   => '',
        };
    };

    $rptCell = function(int $val, int $max, string $rgb): string {
        $pct = $max > 0 ? $val / $max : 0;
        if ($pct === 0.0) return 'background:#f1f5f9;color:#cbd5e1';
        if ($pct < 0.25)  return "background:rgba({$rgb},0.14);color:rgba(127,29,29,0.55)";
        if ($pct < 0.50)  return "background:rgba({$rgb},0.30);color:rgba(127,29,29,0.75)";
        if ($pct < 0.75)  return "background:rgba({$rgb},0.55);color:#7f1d1d";
        return "background:rgba({$rgb},0.82);color:#fff";
    };

    $rptGreen = '45,212,191';
    $rptRed   = '244,114,182';
    $rptGray  = '100,116,139';
@endphp

{{-- ── Print-only letterhead (the rest of the real page prints below it) ── --}}
<div id="rptPrintDoc" class="rpd-page">
    <div class="rpd-letterhead">
        <div class="rpd-letterhead-left">
            <img src="{{ asset('img/about/about_1.jpg') }}" alt="Parish Logo" class="rpd-logo">
            <div>
                <div class="rpd-parish-name">ST. JOHN THE BAPTIST PARISH</div>
                <div class="rpd-parish-loc">Tiaong, Quezon</div>
            </div>
        </div>
        <div class="rpd-letterhead-right">
            <div class="rpd-generated-label">Generated</div>
            <div class="rpd-generated-date">{{ now()->format('F j, Y') }}</div>
        </div>
    </div>

    <div class="rpd-title-row">
        <div>
            <div class="rpd-title">Reports &amp; Visualization</div>
            <div class="rpd-subtitle">Monthly statistics on sacraments, reservation trends, and schedule utilization.</div>
        </div>
        <div class="rpd-range-pill">{{ $reportRangeLabel }}</div>
    </div>
</div>

<div id="rptScreenView">
{{-- ── Filter toolbar ── --}}
<section class="section-card no-print" style="margin-bottom:24px">
    <form method="GET" action="{{ route('admin.index') }}" id="rptFilterForm"
          style="display:flex;align-items:flex-end;gap:20px;flex-wrap:wrap;padding:20px 24px;
                 background:#f8fafc;border-radius:18px;border:1px solid rgba(220,38,38,0.13);
                 margin-bottom:28px">
        <input type="hidden" name="section" value="reports">

        @php
            $rptMonthOpts = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        @endphp

        <div style="display:flex;flex-direction:column;gap:6px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.07em">From</label>
            <div style="display:flex;gap:6px;align-items:center">
                <input type="hidden" name="report_from_month" id="report_from_month" value="{{ $reportFromMonth }}">
                <div class="adm-csl" id="adm-csl-rpt-from-month" style="min-width:130px;">
                    <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-rpt-from-month')">
                        <span class="adm-csl-label">{{ $rptMonthOpts[$reportFromMonth - 1] }}</span>
                        <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <ul class="adm-csl-list">
                        @foreach($rptMonthOpts as $rmi => $rmn)
                        <li class="{{ $reportFromMonth === $rmi + 1 ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-rpt-from-month','report_from_month','{{ $rmi + 1 }}','{{ $rmn }}');document.getElementById('rptFilterForm').submit()">{{ $rmn }}</li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="report_from_year" id="report_from_year" value="{{ $reportFromYear }}">
                <div class="adm-csl" id="adm-csl-rpt-from-year" style="min-width:90px;">
                    <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-rpt-from-year')">
                        <span class="adm-csl-label">{{ $reportFromYear }}</span>
                        <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <ul class="adm-csl-list">
                        @for($y = now()->year + 1; $y >= now()->year - 4; $y--)
                        <li class="{{ $reportFromYear === $y ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-rpt-from-year','report_from_year','{{ $y }}','{{ $y }}');document.getElementById('rptFilterForm').submit()">{{ $y }}</li>
                        @endfor
                    </ul>
                </div>
            </div>
        </div>

        <div style="display:flex;align-items:flex-end;padding-bottom:10px;color:#9ca3af;font-size:14px;font-weight:600">→</div>

        <div style="display:flex;flex-direction:column;gap:6px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.07em">To</label>
            <div style="display:flex;gap:6px;align-items:center">
                <input type="hidden" name="report_to_month" id="report_to_month" value="{{ $reportToMonth }}">
                <div class="adm-csl" id="adm-csl-rpt-to-month" style="min-width:130px;">
                    <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-rpt-to-month')">
                        <span class="adm-csl-label">{{ $rptMonthOpts[$reportToMonth - 1] }}</span>
                        <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <ul class="adm-csl-list">
                        @foreach($rptMonthOpts as $rmi => $rmn)
                        <li class="{{ $reportToMonth === $rmi + 1 ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-rpt-to-month','report_to_month','{{ $rmi + 1 }}','{{ $rmn }}');document.getElementById('rptFilterForm').submit()">{{ $rmn }}</li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="report_to_year" id="report_to_year" value="{{ $reportToYear }}">
                <div class="adm-csl" id="adm-csl-rpt-to-year" style="min-width:90px;">
                    <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-rpt-to-year')">
                        <span class="adm-csl-label">{{ $reportToYear }}</span>
                        <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <ul class="adm-csl-list">
                        @for($y = now()->year + 1; $y >= now()->year - 4; $y--)
                        <li class="{{ $reportToYear === $y ? 'is-selected' : '' }}" onclick="admCslSelect('adm-csl-rpt-to-year','report_to_year','{{ $y }}','{{ $y }}');document.getElementById('rptFilterForm').submit()">{{ $y }}</li>
                        @endfor
                    </ul>
                </div>
            </div>
        </div>

        @php $rptTypeLabelMap = ['all'=>'All sacraments','baptism'=>'Baptism','wedding'=>'Wedding','funeral'=>'Funeral']; @endphp
        <div style="display:flex;flex-direction:column;gap:6px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.07em">Type</label>
            <input type="hidden" name="report_type" id="report_type" value="{{ $reportType }}">
            <div class="adm-csl" id="adm-csl-rpt-type" style="min-width:170px;">
                <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-rpt-type')">
                    <span class="adm-csl-label">{{ $rptTypeLabelMap[$reportType] ?? 'All sacraments' }}</span>
                    <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="adm-csl-list">
                    <li class="{{ $reportType==='all'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-rpt-type','report_type','all','All sacraments');document.getElementById('rptFilterForm').submit()">All sacraments</li>
                    <li class="{{ $reportType==='baptism'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-rpt-type','report_type','baptism','Baptism');document.getElementById('rptFilterForm').submit()">Baptism</li>
                    <li class="{{ $reportType==='wedding'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-rpt-type','report_type','wedding','Wedding');document.getElementById('rptFilterForm').submit()">Wedding</li>
                    <li class="{{ $reportType==='funeral'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-rpt-type','report_type','funeral','Funeral');document.getElementById('rptFilterForm').submit()">Funeral</li>
                </ul>
            </div>
        </div>

        @if($reportFromMonth !== 1 || $reportFromYear !== now()->year || $reportToMonth !== now()->month || $reportToYear !== now()->year || $reportType !== 'all')
        <div style="display:flex;flex-direction:column;gap:6px">
            <label style="font-size:11px;font-weight:700;color:transparent;letter-spacing:.07em">Reset</label>
            <a href="{{ route('admin.index', ['section'=>'reports']) }}"
               style="padding:9px 16px;border-radius:12px;border:1.5px solid #e5e7eb;background:#fff;
                      color:#6b7280;font-size:13px;font-weight:600;text-decoration:none;
                      display:inline-flex;align-items:center;gap:6px">
                <i class="fa fa-times"></i> Clear filters
            </a>
        </div>
        @endif

        <div style="display:flex;flex-direction:column;gap:6px;margin-left:auto">
            <label style="font-size:11px;font-weight:700;color:transparent;letter-spacing:.07em">Export</label>
            <button type="button" onclick="window.print()"
                    style="padding:9px 20px;border-radius:12px;border:1.5px solid #d1d5db;
                           background:#fff;color:#dc2626;font-size:14px;font-weight:700;cursor:pointer;
                           display:inline-flex;align-items:center;gap:8px;transition:all .2s ease"
                    onmouseover="this.style.background='rgba(220,38,38,0.08)'"
                    onmouseout="this.style.background='#fff'">
                <i class="fa fa-download"></i> Export PDF
            </button>
        </div>
    </form>

    {{-- Stat cards --}}
    <div class="summary-cards" style="margin-bottom:24px">
        <div class="summary-card">
            <h2>Total Reservations</h2>
            <div class="summary-value">{{ $rptTotal }}</div>
            <div class="summary-caption">{{ $reportRangeLabel }}</div>
        </div>
        <div class="summary-card">
            <h2>Baptisms</h2>
            <div class="summary-value" style="color:#2dd4bf">{{ $reportTotals['baptism'] }}</div>
            <div class="summary-caption">{{ $rptPeakBapM !== '—' ? 'Peak: ' . $rptPeakBapM : 'No data yet' }}</div>
        </div>
        <div class="summary-card">
            <h2>Weddings</h2>
            <div class="summary-value" style="color:#f472b6">{{ $reportTotals['wedding'] }}</div>
            <div class="summary-caption">{{ $rptPeakWedM !== '—' ? 'Peak: ' . $rptPeakWedM : 'No data yet' }}</div>
        </div>
        <div class="summary-card">
            <h2>Funerals</h2>
            <div class="summary-value" style="color:#64748b">{{ $reportTotals['funeral'] }}</div>
            <div class="summary-caption">{{ $rptPeakFunM !== '—' ? 'Peak: ' . $rptPeakFunM : 'No data yet' }}</div>
        </div>
    </div>

    {{-- Report summary --}}
    @if($rptTotal > 0)
    <div class="rpt-summary-box">
        <div class="rpt-summary-header">
            <span style="display:inline-flex;align-items:center;gap:8px">
                Report Summary
                @if($reportSummary)
                    <span style="font-size:10px;padding:2px 9px;border-radius:999px;background:rgba(16,185,129,0.12);color:#059669;font-weight:700;letter-spacing:.05em;border:1px solid rgba(16,185,129,0.25)">AI</span>
                @endif
            </span>
            <span class="rpt-summary-badge">{{ $reportRangeLabel }}</span>
        </div>
        <p class="rpt-summary-text">
            @if($reportSummary)
                {{ $reportSummary }}
            @else
                For <strong>{{ $reportRangeLabel }}</strong>, the parish recorded <strong>{{ $rptTotal }}</strong> total sacrament reservations.
                Baptisms are the most common at <strong>{{ $reportTotals['baptism'] }}</strong> ({{ $rptPieBap }}%),
                followed by weddings at <strong>{{ $reportTotals['wedding'] }}</strong> ({{ $rptPieWed }}%)
                and funerals at <strong>{{ $reportTotals['funeral'] }}</strong> ({{ $rptPieFun }}%).
                @if($rptPeakMonth !== '—') Peak activity was in <strong>{{ $rptPeakMonth }}</strong> with {{ $rptPeakVal }} total sacraments. @endif
                @if($rptBusiestDay !== '—') The busiest day of the week is <strong>{{ $rptBusiestDay }}</strong>. @endif
            @endif
        </p>
        <div class="rpt-highlights">
            <div class="rpt-hl">
                <div class="rpt-hl-label">Peak Month</div>
                <div class="rpt-hl-val">{{ $rptPeakMonth }}</div>
                <div class="rpt-hl-sub">{{ $rptPeakVal }} sacraments</div>
            </div>
            <div class="rpt-hl">
                <div class="rpt-hl-label">Approved Total</div>
                <div class="rpt-hl-val" style="color:#10b981">{{ $reportTotals['approved'] }}</div>
                <div class="rpt-hl-sub">{{ $rptTotal > 0 ? round($reportTotals['approved'] / $rptTotal * 100) : 0 }}% approval rate</div>
            </div>
            <div class="rpt-hl">
                <div class="rpt-hl-label">Busiest Day</div>
                <div class="rpt-hl-val">{{ $rptBusiestDay }}</div>
                <div class="rpt-hl-sub">{{ $rptBusiestMax }} sacraments</div>
            </div>
        </div>
    </div>
    @endif
</section>

{{-- ── Sacrament Overview ── --}}
<div class="rpt-label"><span>Sacrament Overview</span><div class="rpt-label-line"></div></div>

<div class="rpt-two-col" style="grid-template-columns:2fr 1fr">
    <section class="section-card" style="margin-bottom:0">
        <div class="section-header">
            <div>
                <h2>Monthly sacrament count</h2>
                <p>Baptisms, weddings, and funerals per month — {{ $reportRangeLabel }}</p>
            </div>
        </div>
        <div style="height:220px;position:relative"><canvas id="rptMonthlyChart"></canvas></div>
    </section>
    <section class="section-card" style="margin-bottom:0">
        <div class="section-header">
            <div>
                <h2>Sacrament breakdown</h2>
                <p>Share of total — {{ $reportRangeLabel }}</p>
            </div>
        </div>
        @if($rptTotal > 0)
            <div style="height:160px;position:relative;margin-bottom:16px"><canvas id="rptPieChart"></canvas></div>
            <div class="rpt-pie-legend">
                <div class="rpt-pie-row"><div class="rpt-pie-dot" style="background:#2dd4bf"></div>Baptisms<span class="rpt-pie-pct">{{ $rptPieBap }}%</span></div>
                <div class="rpt-pie-row"><div class="rpt-pie-dot" style="background:#f472b6"></div>Weddings<span class="rpt-pie-pct">{{ $rptPieWed }}%</span></div>
                <div class="rpt-pie-row"><div class="rpt-pie-dot" style="background:#64748b"></div>Funerals<span class="rpt-pie-pct">{{ $rptPieFun }}%</span></div>
            </div>
        @else
            <div class="empty-block" style="text-align:center;padding:48px 0">No sacrament data for {{ $reportRangeLabel }}.</div>
        @endif
    </section>
</div>

{{-- ── Reservation Activity ── --}}
<div class="rpt-label" style="margin-top:24px"><span>Reservation Activity</span><div class="rpt-label-line"></div></div>

<div class="rpt-two-col">
    <section class="section-card" style="margin-bottom:0">
        <div class="section-header">
            <div>
                <h2>Sacraments by day of week</h2>
                <p>Busiest days — stacked by type</p>
            </div>
        </div>
        <div style="height:220px;position:relative"><canvas id="rptDayChart"></canvas></div>
    </section>
    <section class="section-card" style="margin-bottom:0">
        <div class="section-header">
            <div>
                <h2>Reservation status over time</h2>
                <p>Approved, pending, declined per month</p>
            </div>
        </div>
        <div style="height:180px;position:relative"><canvas id="rptStatusChart"></canvas></div>
        <div class="rpt-status-totals">
            <div class="rpt-stot"><div class="rpt-stot-label">Approved</div><div class="rpt-stot-val" style="color:#10b981">{{ $reportTotals['approved'] }}</div></div>
            <div class="rpt-stot"><div class="rpt-stot-label">Pending</div><div class="rpt-stot-val" style="color:#f59e0b">{{ $reportTotals['pending'] }}</div></div>
            <div class="rpt-stot"><div class="rpt-stot-label">Declined</div><div class="rpt-stot-val" style="color:#ef4444">{{ $reportTotals['declined'] }}</div></div>
        </div>
    </section>
</div>

{{-- ── Peak Months Heatmap ── --}}
<section class="section-card" style="margin-top:24px;margin-bottom:0">
    <div class="section-header">
        <div>
            <h2>Peak months heatmap</h2>
            <p>Activity intensity by sacrament type — darker cells indicate higher volume</p>
        </div>
    </div>
    <div class="rpt-heatmap-wrap">
        <div class="rpt-hm-month-row">
            <div class="rpt-hm-spacer"></div>
            @foreach($rptLabels as $ml)<div class="rpt-hm-ml">{{ $ml }}</div>@endforeach
        </div>
        <div class="rpt-hm-row">
            <div class="rpt-hm-rl">Baptisms</div>
            <div class="rpt-hm-cells">
                @foreach($reportMonthlyData as $mn => $md)
                    <div class="rpt-hm-cell" style="{{ $rptCell($md['baptism'], $rptMaxBap, $rptGreen) }}" title="{{ $mn }}: {{ $md['baptism'] }}">{{ $md['baptism'] > 0 ? $md['baptism'] : '' }}</div>
                @endforeach
            </div>
        </div>
        <div class="rpt-hm-row">
            <div class="rpt-hm-rl">Weddings</div>
            <div class="rpt-hm-cells">
                @foreach($reportMonthlyData as $mn => $md)
                    <div class="rpt-hm-cell" style="{{ $rptCell($md['wedding'], $rptMaxWed, $rptRed) }}" title="{{ $mn }}: {{ $md['wedding'] }}">{{ $md['wedding'] > 0 ? $md['wedding'] : '' }}</div>
                @endforeach
            </div>
        </div>
        <div class="rpt-hm-row">
            <div class="rpt-hm-rl">Funerals</div>
            <div class="rpt-hm-cells">
                @foreach($reportMonthlyData as $mn => $md)
                    <div class="rpt-hm-cell" style="{{ $rptCell($md['funeral'], $rptMaxFun, $rptGray) }}" title="{{ $mn }}: {{ $md['funeral'] }}">{{ $md['funeral'] > 0 ? $md['funeral'] : '' }}</div>
                @endforeach
            </div>
        </div>
        <div class="rpt-scale-row">
            <span class="rpt-scale-lbl">Low</span>
            <div class="rpt-scale-bar">
                <div class="rpt-sc" style="background:#f1f5f9"></div>
                <div class="rpt-sc" style="background:rgba(220,38,38,0.14)"></div>
                <div class="rpt-sc" style="background:rgba(220,38,38,0.30)"></div>
                <div class="rpt-sc" style="background:rgba(220,38,38,0.55)"></div>
                <div class="rpt-sc" style="background:rgba(220,38,38,0.82)"></div>
            </div>
            <span class="rpt-scale-lbl">High</span>
        </div>
    </div>
</section>

{{-- ── Event Attendance ── --}}
<div class="rpt-label" style="margin-top:24px"><span>Event Attendance</span><div class="rpt-label-line"></div></div>

<div class="rpt-two-col" style="margin-bottom:24px">
    {{-- Avg attendance chart --}}
    <section class="section-card" style="margin-bottom:0">
        <div class="section-header">
            <div><h2>Average attendance</h2><p>Average headcount per event type across all logged events</p></div>
        </div>
        @if(array_sum($attendanceAvg) === 0)
            <div class="rpt-placeholder-box" style="padding:32px 16px">
                <i class="fa fa-users" style="font-size:28px;color:#fecaca"></i>
                <div class="rpt-placeholder-title">No attendance logged yet</div>
                <div class="rpt-placeholder-sub">Log attendance on past events below to see averages here.</div>
            </div>
        @else
            <div style="position:relative;height:200px"><canvas id="rptAttendanceChart"></canvas></div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:14px">
                @foreach(['Wedding'=>'#f472b6','Baptism'=>'#2dd4bf','Funeral'=>'#64748b'] as $et=>$ec)
                <div style="text-align:center;padding:10px;border-radius:10px;background:{{ $ec }}14;border:1px solid {{ $ec }}30">
                    <div style="font-size:20px;font-weight:800;color:{{ $ec }}">{{ $attendanceAvg[$et] ?? 0 }}</div>
                    <div style="font-size:11px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:.06em">{{ $et }}</div>
                </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Summary stats --}}
    <section class="section-card" style="margin-bottom:0;overflow:hidden">
        @php
            $totalPast   = count($pastApproved);
            $totalLogged = count(array_filter($pastApproved, fn($r) => isset($attendanceLookup[(int)($r['id'] ?? 0)])));
            $pct = $totalPast > 0 ? round($totalLogged / $totalPast * 100) : 0;
        @endphp

        {{-- Stats view --}}
        <div id="logProgressStats">
            <div class="section-header" style="margin-bottom:0">
                <div><h2>Logging progress</h2><p>How many past events have been logged</p></div>
                @if($totalLogged > 0)
                <button onclick="showLoggedCards()" id="btnShowLogged" class="no-print"
                        style="display:inline-flex;align-items:center;gap:7px;padding:7px 14px;border-radius:10px;border:1.5px solid rgba(16,185,129,0.3);color:#059669;font-size:12px;font-weight:700;background:rgba(16,185,129,0.06);cursor:pointer;white-space:nowrap;flex-shrink:0">
                    <i class="fa fa-check-circle"></i>
                    View {{ $totalLogged }} logged
                    <i class="fa fa-chevron-right" style="font-size:11px"></i>
                </button>
                @endif
            </div>
            <div style="display:flex;flex-direction:column;gap:16px;padding:16px 0 0">
                <div style="text-align:center">
                    <div style="font-size:42px;font-weight:800;color:#dc2626;line-height:1">{{ $totalLogged }}</div>
                    <div style="font-size:13px;color:#6b7280">of {{ $totalPast }} past events logged</div>
                </div>
                <div style="background:#f1f5f9;border-radius:999px;height:10px;overflow:hidden">
                    <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#dc2626,#b91c1c);border-radius:999px;transition:.3s"></div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                    <div style="padding:10px;border-radius:10px;background:rgba(16,185,129,.07);text-align:center">
                        <div style="font-size:18px;font-weight:800;color:#065f46">{{ $totalLogged }}</div>
                        <div style="font-size:11px;color:#6b7280;font-weight:600">Logged</div>
                    </div>
                    <div style="padding:10px;border-radius:10px;background:rgba(245,158,11,.07);text-align:center">
                        <div style="font-size:18px;font-weight:800;color:#92400e">{{ $totalPast - $totalLogged }}</div>
                        <div style="font-size:11px;color:#6b7280;font-weight:600">Pending log</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Logged events view (hidden by default) --}}
        <div id="loggedCardsView" style="display:none;opacity:0;transition:opacity .25s ease">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;gap:10px;flex-wrap:wrap">
                <span style="font-size:14px;font-weight:700;color:#111827">{{ $totalLogged }} logged event{{ $totalLogged !== 1 ? 's' : '' }}</span>
                <button onclick="hideLoggedCards()" class="no-print"
                        style="display:inline-flex;align-items:center;gap:6px;padding:6px 13px;border-radius:9px;border:1.5px solid #e5e7eb;color:#6b7280;font-size:12px;font-weight:700;background:#fff;cursor:pointer">
                    <i class="fa fa-arrow-left" style="font-size:11px"></i> Back
                </button>
            </div>
            <div class="no-print" style="display:flex;align-items:center;gap:8px;margin-bottom:12px;flex-wrap:wrap">
                <div style="position:relative;flex:1;min-width:160px">
                    <i class="fa fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:12px"></i>
                    <input type="text" id="loggedSearchInput" placeholder="Search by name…" oninput="loggedFilterAndSort()"
                           style="width:100%;padding:7px 12px 7px 30px;border-radius:8px;border:1.5px solid #e5e7eb;font-size:12px;box-sizing:border-box;outline:none">
                </div>
                <div class="adm-csl" id="adm-csl-logged-sort" style="min-width:150px;">
                    <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-logged-sort')" style="padding:7px 12px;font-size:12px;">
                        <span class="adm-csl-label">Latest first</span>
                        <svg class="adm-csl-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <ul class="adm-csl-list">
                        <li class="is-selected" onclick="admCslSelect('adm-csl-logged-sort','loggedSortInput','latest','Latest first');loggedFilterAndSort()">Latest first</li>
                        <li onclick="admCslSelect('adm-csl-logged-sort','loggedSortInput','oldest','Oldest first');loggedFilterAndSort()">Oldest first</li>
                    </ul>
                </div>
                <input type="hidden" id="loggedSortInput" value="latest">
            </div>
            <div id="loggedCardsEmpty" style="display:none;padding:24px;text-align:center;color:#94a3b8;font-size:13px">No logged events match your search.</div>
            <div id="loggedCardsList" style="max-height:320px;overflow-y:auto;display:grid;grid-template-columns:1fr;gap:10px;padding-right:4px">
                @foreach($pastApproved as $pr)
                @php
                    $prId      = (int)($pr['id'] ?? 0);
                    $prEt      = strtolower(trim($pr['event_type'] ?? ''));
                    $prColor   = match($prEt){ 'wedding'=>'#f472b6','baptism'=>'#2dd4bf','funeral'=>'#64748b',default=>'#94a3b8' };
                    $prDate    = $pr['preferred_date'] ?? $pr['reservation_date'] ?? null;
                    $prDateFmt = $prDate ? (new DateTimeImmutable((string)$prDate))->format('M j, Y') : '—';
                    $prDateRaw = $prDate ? (string)$prDate : '';
                    $prAtt     = $attendanceLookup[$prId] ?? null;
                @endphp
                @if($prAtt)
                <div class="logged-card" data-name="{{ strtolower($pr['name'] ?? '') }}" data-date="{{ $prDateRaw }}"
                     style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:12px">
                    <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;background:{{ $prColor }}18;color:{{ $prColor }};border:1px solid {{ $prColor }}40;flex-shrink:0">
                        {!! $rptTypeIcon($prEt) !!} {{ ucfirst($prEt) }}
                    </span>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $pr['name'] ?? '—' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $prDateFmt }}</div>
                    </div>
                    <span style="font-size:22px;font-weight:800;color:#dc2626;line-height:1;flex-shrink:0">{{ $prAtt['attended_count'] }}</span>
                    <button type="button" class="no-print"
                            style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:7px;border:1.5px solid rgba(100,116,139,0.25);color:#64748b;font-size:11px;font-weight:700;background:transparent;cursor:pointer;flex-shrink:0"
                            onclick="openAttModal({{ $prId }}, '{{ ucfirst($prEt) }}', '{{ addslashes($pr['name'] ?? '') }}', {{ $prAtt['attended_count'] }}, '{{ addslashes($prAtt['notes'] ?? '') }}')">
                        <i class="fa fa-pencil"></i>
                    </button>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>
</div>

{{-- Past events log table (unlogged only) --}}
<section class="section-card" style="margin-bottom:0">
    <div class="section-header">
        <div>
            <h2>Event attendance log</h2>
            <p>Events below still need their attendance recorded.</p>
        </div>
        @php $unloggedCount = count(array_filter($pastApproved, fn($r) => !isset($attendanceLookup[(int)($r['id'] ?? 0)]))); @endphp
        <span style="font-size:13px;font-weight:700;color:#f59e0b;background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.25);padding:5px 14px;border-radius:999px;white-space:nowrap">
            {{ $unloggedCount }} pending
        </span>
    </div>

    @if(empty($pastApproved))
        <p class="empty-block">No past approved events to log yet.</p>
    @else

    {{-- Filter pills --}}
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <button onclick="filterAtt('all')"     id="attf-all"     class="att-filter-btn"
                    data-default-border="#e5e7eb" data-default-color="#6b7280">All</button>
            <button onclick="filterAtt('wedding')" id="attf-wedding" class="att-filter-btn"
                    style="color:#f472b6;border-color:#f472b6;"
                    data-default-border="#f472b6" data-default-color="#f472b6">{!! $rptTypeIcon('wedding') !!} Wedding</button>
            <button onclick="filterAtt('baptism')" id="attf-baptism" class="att-filter-btn"
                    style="color:#2dd4bf;border-color:#2dd4bf;"
                    data-default-border="#2dd4bf" data-default-color="#2dd4bf">{!! $rptTypeIcon('baptism') !!} Baptism</button>
            <button onclick="filterAtt('funeral')" id="attf-funeral" class="att-filter-btn"
                    style="color:#64748b;border-color:#64748b;"
                    data-default-border="#64748b" data-default-color="#64748b">{!! $rptTypeIcon('funeral') !!} Funeral</button>
        </div>
        <div class="no-print" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <div style="position:relative;min-width:170px">
                <i class="fa fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:12px"></i>
                <input type="text" id="attSearchInput" placeholder="Search by name…" oninput="attPage=1;filterAtt()"
                       style="width:100%;padding:7px 12px 7px 30px;border-radius:8px;border:1.5px solid #e5e7eb;font-size:12px;box-sizing:border-box;outline:none">
            </div>
            <div class="adm-csl" id="adm-csl-att-sort" style="min-width:150px;">
                <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-att-sort')" style="padding:7px 12px;font-size:12px;">
                    <span class="adm-csl-label">Latest first</span>
                    <svg class="adm-csl-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="adm-csl-list">
                    <li class="is-selected" onclick="admCslSelect('adm-csl-att-sort','attSortInput','latest','Latest first');attPage=1;filterAtt()">Latest first</li>
                    <li onclick="admCslSelect('adm-csl-att-sort','attSortInput','oldest','Oldest first');attPage=1;filterAtt()">Oldest first</li>
                </ul>
            </div>
            <input type="hidden" id="attSortInput" value="latest">
        </div>
    </div>

    <div id="attEmptyMsg" style="display:none;padding:24px;text-align:center;color:#94a3b8;font-size:13px">No unlogged events match the selected filter.</div>
    <div id="attPagination" class="no-print" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;padding:12px 4px 4px;margin-top:8px;border-top:1px solid #f1f5f9"></div>

    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
                <tr style="border-bottom:2px solid #f1f5f9">
                    <th style="text-align:left;padding:10px 12px;color:#6b7280;font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.06em">Event</th>
                    <th style="text-align:left;padding:10px 12px;color:#6b7280;font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.06em">Name</th>
                    <th style="text-align:left;padding:10px 12px;color:#6b7280;font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.06em">Date</th>
                    <th style="padding:10px 12px"></th>
                </tr>
            </thead>
            <tbody id="attTableBody">
                @foreach($pastApproved as $pr)
                @php
                    $prId      = (int)($pr['id'] ?? 0);
                    $prEt      = strtolower(trim($pr['event_type'] ?? ''));
                    $prColor   = match($prEt){ 'wedding'=>'#f472b6','baptism'=>'#2dd4bf','funeral'=>'#64748b',default=>'#94a3b8' };
                    $prDate    = $pr['preferred_date'] ?? $pr['reservation_date'] ?? null;
                    $prDateFmt = $prDate ? (new DateTimeImmutable((string)$prDate))->format('M j, Y') : '—';
                    $prAtt     = $attendanceLookup[$prId] ?? null;
                @endphp
                @if(!$prAtt)
                <tr style="border-bottom:1px solid #f8fafc" class="att-row att-unlogged" data-type="{{ $prEt }}"
                    data-date="{{ $prDate ?? '' }}" data-name="{{ strtolower($pr['name'] ?? '') }}">
                    <td style="padding:10px 12px">
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;background:{{ $prColor }}18;color:{{ $prColor }};border:1px solid {{ $prColor }}40">
                            {!! $rptTypeIcon($prEt) !!} {{ ucfirst($prEt) }}
                        </span>
                    </td>
                    <td style="padding:10px 12px;font-weight:600;color:#111827">{{ $pr['name'] ?? '—' }}</td>
                    <td style="padding:10px 12px;color:#6b7280">{{ $prDateFmt }}</td>
                    <td style="padding:10px 12px" class="no-print">
                        <button type="button"
                                style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:8px;border:1.5px solid rgba(220,38,38,.3);color:#dc2626;font-size:12px;font-weight:700;background:transparent;cursor:pointer"
                                onclick="openAttModal({{ $prId }}, '{{ ucfirst($prEt) }}', '{{ addslashes($pr['name'] ?? '') }}', 0, '')">
                            <i class="fa fa-plus"></i> Log
                        </button>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>


    @endif
</section>

{{-- Attendance modal --}}
<div class="dcm-overlay" id="attendanceModal" onclick="if(event.target===this) closeAttModal()">
    <div class="dcm-modal">
        <div class="ps-hdr">
            <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
            <span class="ps-parish">St. John the Baptist Parish</span>
            <span class="ps-dot"></span>
            <span class="ps-loc">Tiaong, Quezon</span>
        </div>
        <button type="button" class="dcm-close-btn" onclick="closeAttModal()">&times;</button>

        <div class="dcm-scroll">
            <form method="POST" action="{{ route('admin.attendance.store') }}" style="padding:22px 24px 24px;display:flex;flex-direction:column;gap:16px;">
                @csrf
                <input type="hidden" name="reservation_id" id="attResId">
                <input type="hidden" name="event_type"     id="attEventType">

                <div>
                    <div style="display:flex;align-items:center;gap:8px;color:#fff;font-weight:800;font-size:16px;">
                        <i class="fa fa-users"></i> <span id="attModalTitle">Log Attendance</span>
                    </div>
                    <p style="margin:6px 0 0;color:rgba(255,255,255,.45);font-size:12px;" id="attModalSub"></p>
                </div>

                <div>
                    <label class="dcm-label">Number of attendees <span style="color:#f87171">*</span></label>
                    <input type="number" name="attended_count" id="attCount" min="0" required
                           class="dcm-form-input" placeholder="e.g. 120">
                </div>
                <div>
                    <label class="dcm-label">Notes <span class="dcm-label-counter">optional</span></label>
                    <textarea name="notes" id="attNotes" rows="2" maxlength="300"
                              class="dcm-textarea" style="resize:none;"
                              placeholder="e.g. Outdoor overflow area was used"></textarea>
                </div>

                <div class="adm-cfm-btns" style="padding-top:4px;">
                    <button type="button" class="adm-cfm-cancel" onclick="closeAttModal()">Cancel</button>
                    <button type="submit" class="adm-cfm-ok"><i class="fa fa-save"></i> Save attendance</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="rpt-two-col" style="margin-top:24px">

    {{-- ── Donation Trends ── --}}
    <section class="section-card" style="margin-bottom:0">
        <div class="section-header">
            <div>
                <h2>Donation trends</h2>
                <p>Monthly totals for {{ $reportRangeLabel }} &mdash; ₱{{ number_format($donationTotal, 2) }} total</p>
            </div>
        </div>

        {{-- Chart --}}
        <div style="position:relative;height:200px">
            <canvas id="rptDonationChart"></canvas>
        </div>

        {{-- Recent donations mini-list --}}
        @if(count($recentDonations) > 0)
        <div style="margin-top:14px;border-top:1px solid #f1f5f9;padding-top:12px">
            @foreach($recentDonations as $don)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:13px;border-bottom:1px solid #f8fafc">
                <span style="color:#374151;font-weight:600">₱{{ number_format($don['amount'], 2) }}
                    <span style="font-weight:400;color:#9ca3af;font-size:11px;margin-left:4px">{{ ucfirst($don['type'] ?? '') }}</span>
                </span>
                <span style="color:#94a3b8;font-size:11px">{{ \Carbon\Carbon::parse($don['donation_date'])->format('M j, Y') }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </section>

    {{-- ── Schedule Utilization ── --}}
    <section class="section-card" style="margin-bottom:0">
        <div class="section-header">
            <div>
                <h2>Schedule utilization</h2>
                <p>Approved reservations vs. available slots &mdash; {{ $reportRangeLabel }}</p>
            </div>
            @php
                $avgUtil = count($utilizationMonthly) > 0
                    ? round(array_sum(array_column($utilizationMonthly, 'rate')) / count($utilizationMonthly), 1)
                    : 0;
            @endphp
            <span style="flex-shrink:0;font-size:18px;font-weight:800;color:#dc2626">{{ $avgUtil }}%<span style="font-size:11px;font-weight:500;color:#9ca3af;margin-left:4px">avg</span></span>
        </div>

        <div style="position:relative;height:200px">
            <canvas id="rptUtilChart"></canvas>
        </div>

        {{-- Capacity note --}}
        <p style="font-size:11px;color:#94a3b8;margin:10px 0 0;text-align:center">
            Capacity = days × 7 slots/day (5 baptism + 1 wedding + 1 funeral per day)
        </p>

        {{-- Monthly breakdown --}}
        <div style="margin-top:12px;display:grid;grid-template-columns:repeat(4,1fr);gap:6px">
            @foreach($utilizationMonthly as $mn => $ud)
            <div style="text-align:center;padding:6px 4px;border-radius:8px;background:#f8fafc">
                <div style="font-size:10px;color:#9ca3af;font-weight:600;text-transform:uppercase">{{ $mn }}</div>
                <div style="font-size:14px;font-weight:800;color:{{ $ud['rate'] >= 50 ? '#dc2626' : ($ud['rate'] >= 20 ? '#f59e0b' : '#94a3b8') }}">{{ $ud['rate'] }}%</div>
                <div style="font-size:9px;color:#cbd5e1">{{ $ud['approved'] }}/{{ $ud['capacity'] }}</div>
            </div>
            @endforeach
        </div>
    </section>
</div>

<div class="rpt-label" style="margin-top:24px"><span>People</span><div class="rpt-label-line"></div></div>

<section class="section-card" style="margin-bottom:0">
    <div class="section-header" style="align-items:flex-start">
        <div>
            <h2>Officiant workload</h2>
            <p>Total ceremonies per priest — {{ now()->format('Y') }}</p>
        </div>
        @if($adminRole === 'admin')
        <button class="btn btn-sm no-print" style="background:#f1f5f9;color:#dc2626;border:1px solid #fee2e2;font-weight:600;white-space:nowrap;flex-shrink:0" onclick="togglePriestManager()">
            <i class="fa fa-cog"></i> Manage Priests
        </button>
        @endif
    </div>

    @if(count($officiantWorkload) === 0)
        <div style="text-align:center;padding:32px 16px;color:#94a3b8">
            <i class="fa fa-users" style="font-size:28px;margin-bottom:10px;display:block;color:#fecaca"></i>
            <div style="font-weight:600;margin-bottom:4px;color:#64748b">No priests in the roster yet</div>
            @if($adminRole === 'admin')
            <div style="font-size:13px">Click "Manage Priests" above to add priests.</div>
            @endif
        </div>
    @else
        @php
            $wlMax    = max(array_column($officiantWorkload, 'count')) ?: 1;
            $wlPalette = ['#dc2626','#10b981','#f97316','#8b5cf6','#ec4899','#14b8a6','#f59e0b','#ef4444'];
        @endphp
        <div style="display:flex;flex-direction:column">
            @foreach($officiantWorkload as $wi => $wo)
            @php
                $wlColor    = $wlPalette[$wi % count($wlPalette)];
                $wlWords    = array_values(array_filter(explode(' ', $wo['name'])));
                $wlInitials = strtoupper(substr($wlWords[0] ?? '?', 0, 1) . substr($wlWords[1] ?? '', 0, 1));
                $wlPct      = round($wo['count'] / $wlMax * 100);
            @endphp
            <div style="display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px solid #f8fafc">
                <div style="width:42px;height:42px;border-radius:50%;background:{{ $wlColor }}22;color:{{ $wlColor }};display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;flex-shrink:0;letter-spacing:.5px">
                    {{ $wlInitials }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:700;color:#111827;font-size:14px;margin-bottom:5px">{{ $wo['name'] }}</div>
                    <div style="display:flex;gap:5px;flex-wrap:wrap">
                        @if($wo['baptisms'] > 0)
                        <span style="padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;background:rgba(45,212,191,0.12);color:#0d9488">Baptisms: {{ $wo['baptisms'] }}</span>
                        @endif
                        @if($wo['weddings'] > 0)
                        <span style="padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;background:rgba(244,114,182,0.12);color:#db2777">Weddings: {{ $wo['weddings'] }}</span>
                        @endif
                        @if($wo['funerals'] > 0)
                        <span style="padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;background:rgba(100,116,139,0.12);color:#475569">Funerals: {{ $wo['funerals'] }}</span>
                        @endif
                        @if($wo['count'] === 0)
                        <span style="padding:2px 8px;border-radius:999px;font-size:11px;font-weight:500;background:#f1f5f9;color:#94a3b8">No ceremonies yet</span>
                        @endif
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex:0 0 180px">
                    <div style="flex:1;background:#f1f5f9;border-radius:999px;height:8px;overflow:hidden">
                        <div style="height:100%;width:{{ $wlPct }}%;background:{{ $wlColor }};border-radius:999px"></div>
                    </div>
                    <span style="font-size:15px;font-weight:800;color:#374151;min-width:26px;text-align:right">{{ $wo['count'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    @if($adminRole === 'admin')
    <div id="priestManager" class="no-print" style="display:none;margin-top:20px;border-top:1px solid #f1f5f9;padding-top:20px">
        <h4 style="font-size:14px;font-weight:700;color:#374151;margin:0 0 12px">Priest Roster</h4>
        <form method="POST" action="{{ route('admin.priests.store') }}" style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap">
            @csrf
            <input type="hidden" name="title" id="priest_title" value="Fr.">
            <div class="adm-csl" id="adm-csl-priest-title" style="min-width:110px;">
                <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-priest-title')">
                    <span class="adm-csl-label">Fr.</span>
                    <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="adm-csl-list">
                    <li class="is-selected" onclick="admCslSelect('adm-csl-priest-title','priest_title','Fr.','Fr.')">Fr.</li>
                    <li onclick="admCslSelect('adm-csl-priest-title','priest_title','Rev.','Rev.')">Rev.</li>
                    <li onclick="admCslSelect('adm-csl-priest-title','priest_title','Deacon','Deacon')">Deacon</li>
                    <li onclick="admCslSelect('adm-csl-priest-title','priest_title','Msgr.','Msgr.')">Msgr.</li>
                </ul>
            </div>
            <input type="text" name="name" placeholder="Priest name" required class="priest-name-input"
                style="flex:1;min-width:150px;padding:7px 12px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:13px">
            <button type="submit" class="priest-add-btn" style="white-space:nowrap">
                <i class="fa fa-plus"></i> Add
            </button>
        </form>
        @if(count($priests) === 0)
            <p style="color:#94a3b8;font-size:13px;text-align:center;padding:12px 0">No priests in the roster yet.</p>
        @else
            <div style="display:flex;flex-direction:column;gap:6px">
                @foreach($priests as $p)
                @php $wl = collect($officiantWorkload)->firstWhere('id', $p['id']); @endphp
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#f8fafc;border-radius:8px;border:1px solid #f1f5f9">
                    <div>
                        <span style="font-weight:600;color:#374151;font-size:14px">{{ ($p['title'] ?? 'Fr.') . ' ' . ($p['name'] ?? '') }}</span>
                        <span style="font-size:12px;color:#94a3b8;margin-left:8px">{{ $wl ? $wl['count'] : 0 }} ceremonies</span>
                    </div>
                    <form method="POST" action="{{ route('admin.priests.delete', $p['id']) }}" id="priest-delete-form-{{ $p['id'] }}">
                        @csrf @method('DELETE')
                        <button type="button" onclick="priestConfirmDelete('priest-delete-form-{{ $p['id'] }}', '{{ addslashes(($p['title'] ?? 'Fr.') . ' ' . ($p['name'] ?? '')) }}')"
                                style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:14px;padding:2px 6px">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif
</section>

{{-- ── Raw Data Table ── --}}
<div class="rpt-label" style="margin-top:24px"><span>Raw Data</span><div class="rpt-label-line"></div></div>

<section class="section-card" style="margin-bottom:24px">
    <div class="section-header">
        <div>
            <h2>Data table</h2>
            <p>Monthly sacrament and reservation records — {{ $reportRangeLabel }}</p>
        </div>
    </div>
    @php $rptDataMonthKeys = array_keys($reportMonthlyData); @endphp
    @if(count($rptDataMonthKeys) > 1)
    <div class="no-print" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:16px">
        <span style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">From</span>
        <div class="adm-csl" id="adm-csl-data-from" style="min-width:150px;">
            <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-data-from')" style="padding:7px 12px;font-size:12px;">
                <span class="adm-csl-label">{{ $rptDataMonthKeys[0] }}</span>
                <svg class="adm-csl-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <ul class="adm-csl-list">
                @foreach($rptDataMonthKeys as $rmk)
                <li class="{{ $loop->first ? 'is-selected' : '' }}" data-value="{{ \Carbon\Carbon::parse($rmk)->format('Y-m') }}" onclick="admCslSelect('adm-csl-data-from','dataFromInput','{{ \Carbon\Carbon::parse($rmk)->format('Y-m') }}','{{ $rmk }}');dataPage=1;renderDataPagination()">{{ $rmk }}</li>
                @endforeach
            </ul>
        </div>
        <span style="color:#9ca3af;font-size:13px">→</span>
        <span style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">To</span>
        <div class="adm-csl" id="adm-csl-data-to" style="min-width:150px;">
            <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-data-to')" style="padding:7px 12px;font-size:12px;">
                <span class="adm-csl-label">{{ end($rptDataMonthKeys) }}</span>
                <svg class="adm-csl-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <ul class="adm-csl-list">
                @foreach($rptDataMonthKeys as $rmk)
                <li class="{{ $loop->last ? 'is-selected' : '' }}" data-value="{{ \Carbon\Carbon::parse($rmk)->format('Y-m') }}" onclick="admCslSelect('adm-csl-data-to','dataToInput','{{ \Carbon\Carbon::parse($rmk)->format('Y-m') }}','{{ $rmk }}');dataPage=1;renderDataPagination()">{{ $rmk }}</li>
                @endforeach
            </ul>
        </div>
        <input type="hidden" id="dataFromInput" value="{{ \Carbon\Carbon::parse($rptDataMonthKeys[0])->format('Y-m') }}">
        <input type="hidden" id="dataToInput" value="{{ \Carbon\Carbon::parse(end($rptDataMonthKeys))->format('Y-m') }}">
        <button type="button" onclick="dataResetFilter()" style="padding:7px 14px;border-radius:8px;border:1.5px solid #e5e7eb;background:#fff;color:#6b7280;font-size:12px;font-weight:600;cursor:pointer">Reset</button>
    </div>
    @endif
    <div class="table-responsive">
        <table class="table" style="font-size:14px">
            <thead>
                <tr style="border-bottom:2px solid #e5e7eb">
                    <th style="color:#6b7280;font-size:12px;text-transform:uppercase;letter-spacing:.05em;padding:8px 10px">Month</th>
                    <th style="color:#2dd4bf;font-size:12px;text-transform:uppercase;letter-spacing:.05em;padding:8px 10px;text-align:center">Baptisms</th>
                    <th style="color:#f472b6;font-size:12px;text-transform:uppercase;letter-spacing:.05em;padding:8px 10px;text-align:center">Weddings</th>
                    <th style="color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:.05em;padding:8px 10px;text-align:center">Funerals</th>
                    <th style="color:#6b7280;font-size:12px;text-transform:uppercase;letter-spacing:.05em;padding:8px 10px;text-align:center">Total</th>
                    <th style="color:#10b981;font-size:12px;text-transform:uppercase;letter-spacing:.05em;padding:8px 10px;text-align:center">Approved</th>
                    <th style="color:#f59e0b;font-size:12px;text-transform:uppercase;letter-spacing:.05em;padding:8px 10px;text-align:center">Pending</th>
                    <th style="color:#ef4444;font-size:12px;text-transform:uppercase;letter-spacing:.05em;padding:8px 10px;text-align:center">Declined</th>
                </tr>
            </thead>
            <tbody id="dataTableBody">
                @php $rptFoot = ['b'=>0,'w'=>0,'f'=>0,'t'=>0,'a'=>0,'p'=>0,'d'=>0]; @endphp
                @foreach($reportMonthlyData as $mn => $md)
                    @php
                        $mt = $md['baptism'] + $md['wedding'] + $md['funeral'];
                        $rptFoot['b'] += $md['baptism']; $rptFoot['w'] += $md['wedding']; $rptFoot['f'] += $md['funeral'];
                        $rptFoot['t'] += $mt; $rptFoot['a'] += $md['approved']; $rptFoot['p'] += $md['pending']; $rptFoot['d'] += $md['declined'];
                    @endphp
                    <tr style="border-bottom:1px solid #f1f5f9;{{ $mt === 0 ? 'color:#cbd5e1' : '' }}" data-ym="{{ \Carbon\Carbon::parse($mn)->format('Y-m') }}">
                        <td style="padding:9px 10px;font-weight:500">{{ $mn }}</td>
                        <td style="padding:9px 10px;text-align:center">{{ $md['baptism'] ?: '—' }}</td>
                        <td style="padding:9px 10px;text-align:center">{{ $md['wedding'] ?: '—' }}</td>
                        <td style="padding:9px 10px;text-align:center">{{ $md['funeral'] ?: '—' }}</td>
                        <td style="padding:9px 10px;text-align:center;font-weight:700">{{ $mt ?: '—' }}</td>
                        <td style="padding:9px 10px;text-align:center;color:{{ $md['approved'] > 0 ? '#10b981' : '#cbd5e1' }}">{{ $md['approved'] ?: '—' }}</td>
                        <td style="padding:9px 10px;text-align:center;color:{{ $md['pending']  > 0 ? '#f59e0b' : '#cbd5e1' }}">{{ $md['pending']  ?: '—' }}</td>
                        <td style="padding:9px 10px;text-align:center;color:{{ $md['declined'] > 0 ? '#ef4444' : '#cbd5e1' }}">{{ $md['declined'] ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight:700;border-top:2px solid #e5e7eb;background:#f8fafc">
                    <td style="padding:9px 10px">Total</td>
                    <td style="padding:9px 10px;text-align:center">{{ $rptFoot['b'] }}</td>
                    <td style="padding:9px 10px;text-align:center">{{ $rptFoot['w'] }}</td>
                    <td style="padding:9px 10px;text-align:center">{{ $rptFoot['f'] }}</td>
                    <td style="padding:9px 10px;text-align:center">{{ $rptFoot['t'] }}</td>
                    <td style="padding:9px 10px;text-align:center;color:#10b981">{{ $rptFoot['a'] }}</td>
                    <td style="padding:9px 10px;text-align:center;color:#f59e0b">{{ $rptFoot['p'] }}</td>
                    <td style="padding:9px 10px;text-align:center;color:#ef4444">{{ $rptFoot['d'] }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div id="dataPagination" class="no-print" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;padding:12px 4px 4px;margin-top:8px;border-top:1px solid #f1f5f9"></div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.font.family = "'Inter','system-ui',sans-serif";
    Chart.defaults.color = '#6b7280';

    const months     = @json($rptLabels);
    const baptismD   = @json($rptBaptism);
    const weddingD   = @json($rptWedding);
    const funeralD   = @json($rptFuneral);
    const approvedD  = @json($rptApproved);
    const pendingD   = @json($rptPending);
    const declinedD  = @json($rptDeclined);
    const dayLabels  = @json($rptDayLabels);
    const dayBap     = @json($rptDayBap);
    const dayWed     = @json($rptDayWed);
    const dayFun     = @json($rptDayFun);
    const totBap     = {{ $reportTotals['baptism'] }};
    const totWed     = {{ $reportTotals['wedding'] }};
    const totFun     = {{ $reportTotals['funeral'] }};

    const yAxis = { beginAtZero:true, ticks:{ precision:0, font:{ size:11 } }, grid:{ color:'#f1f5f9' } };
    const xAxis = { ticks:{ font:{ size:11 } }, grid:{ display:false } };

    const monthlyEl = document.getElementById('rptMonthlyChart');
    if (monthlyEl) {
        new Chart(monthlyEl, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    { label:'Baptisms', data:baptismD,  backgroundColor:'#2dd4bf', borderRadius:4 },
                    { label:'Weddings', data:weddingD,  backgroundColor:'#f472b6', borderRadius:4 },
                    { label:'Funerals', data:funeralD,  backgroundColor:'#64748b', borderRadius:4 },
                ]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'top', labels:{ boxWidth:10, font:{ size:11 } } } }, scales:{ x:xAxis, y:yAxis } }
        });
    }

    const pieEl = document.getElementById('rptPieChart');
    if (pieEl && (totBap + totWed + totFun) > 0) {
        new Chart(pieEl, {
            type: 'doughnut',
            data: {
                labels: ['Baptisms','Weddings','Funerals'],
                datasets: [{ data:[totBap,totWed,totFun], backgroundColor:['#2dd4bf','#f472b6','#64748b'], borderWidth:0, hoverOffset:4 }]
            },
            options: { responsive:true, maintainAspectRatio:false, cutout:'65%', plugins:{ legend:{ display:false } } }
        });
    }

    const dayEl = document.getElementById('rptDayChart');
    if (dayEl) {
        new Chart(dayEl, {
            type: 'bar',
            data: {
                labels: dayLabels,
                datasets: [
                    { label:'Baptisms', data:dayBap, backgroundColor:'#2dd4bf', stack:'d' },
                    { label:'Weddings', data:dayWed, backgroundColor:'#f472b6', stack:'d' },
                    { label:'Funerals', data:dayFun, backgroundColor:'#64748b', stack:'d' },
                ]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'top', labels:{ boxWidth:10, font:{ size:11 } } } }, scales:{ x:{ ...xAxis, stacked:true }, y:{ ...yAxis, stacked:true } } }
        });
    }

    const statusEl = document.getElementById('rptStatusChart');
    if (statusEl) {
        new Chart(statusEl, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    { label:'Approved', data:approvedD, backgroundColor:'#10b981', stack:'s' },
                    { label:'Pending',  data:pendingD,  backgroundColor:'#f59e0b', stack:'s' },
                    { label:'Declined', data:declinedD, backgroundColor:'#ef4444', stack:'s' },
                ]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'top', labels:{ boxWidth:10, font:{ size:11 } } } }, scales:{ x:{ ...xAxis, stacked:true }, y:{ ...yAxis, stacked:true } } }
        });
    }

    // ── Donation trends chart ─────────────────────────────────────────
    const donEl = document.getElementById('rptDonationChart');
    if (donEl) {
        const donData = @json($donationMonthly);
        const donLabels = Object.keys(donData);
        new Chart(donEl, {
            type: 'bar',
            data: {
                labels: donLabels,
                datasets: [
                    { label:'Building Fund',   data: donLabels.map(m => donData[m].building_fund),  backgroundColor:'#92400e', borderRadius:4, stack:'d' },
                    { label:'Mass Intention',  data: donLabels.map(m => donData[m].mass_intention), backgroundColor:'#b91c1c', borderRadius:4, stack:'d' },
                    { label:'Tithes',          data: donLabels.map(m => donData[m].tithes),         backgroundColor:'#ca8a04', borderRadius:4, stack:'d' },
                    { label:'Other',           data: donLabels.map(m => donData[m].other),          backgroundColor:'#94a3b8', borderRadius:4, stack:'d' },
                ]
            },
            options: {
                responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{ position:'top', labels:{ boxWidth:10, font:{ size:11 } } },
                    tooltip:{ callbacks:{ label: ctx => ' ₱' + ctx.parsed.y.toLocaleString('en-PH', {minimumFractionDigits:2}) } } },
                scales:{
                    x:{ ...xAxis, stacked:true },
                    y:{ ...yAxis, stacked:true, ticks:{ callback: v => '₱' + v.toLocaleString() } }
                }
            }
        });
    }

    // ── Schedule utilization chart ────────────────────────────────────
    const utilEl = document.getElementById('rptUtilChart');
    if (utilEl) {
        const utilData = @json($utilizationMonthly);
        const utilLabels = Object.keys(utilData);
        new Chart(utilEl, {
            type: 'bar',
            data: {
                labels: utilLabels,
                datasets: [{
                    label: 'Utilization %',
                    data: utilLabels.map(m => utilData[m].rate),
                    backgroundColor: utilLabels.map(m => {
                        const r = utilData[m].rate;
                        return r >= 50 ? 'rgba(220,38,38,0.8)' : r >= 20 ? 'rgba(245,158,11,0.8)' : 'rgba(148,163,184,0.6)';
                    }),
                    borderRadius: 6,
                }]
            },
            options: {
                responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{ display:false } },
                scales:{
                    x: { ...xAxis },
                    y: { ...yAxis, min:0, max:100, ticks:{ callback: v => v + '%' } }
                }
            }
        });
    }

    // Attendance chart
    const attEl = document.getElementById('rptAttendanceChart');
    if (attEl) {
        const attAvg = @json($attendanceAvg);
        new Chart(attEl, {
            type: 'bar',
            data: {
                labels: ['Wedding', 'Baptism', 'Funeral'],
                datasets: [{
                    label: 'Avg attendees',
                    data: [attAvg['Wedding'] ?? 0, attAvg['Baptism'] ?? 0, attAvg['Funeral'] ?? 0],
                    backgroundColor: ['rgba(244,114,182,0.8)', 'rgba(45,212,191,0.8)', 'rgba(100,116,139,0.8)'],
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ...xAxis },
                    y: { ...yAxis, min: 0, ticks: { stepSize: 10 } }
                }
            }
        });
    }
});
</script>


<script>
let attCurrentType = 'all';

const attColors = {
    all:     { bg: '#dc2626',                border: '#dc2626', color: '#fff' },
    wedding: { bg: 'rgba(244,114,182,0.12)', border: '#f472b6', color: '#f472b6' },
    baptism: { bg: 'rgba(45,212,191,0.12)',  border: '#2dd4bf', color: '#2dd4bf' },
    funeral: { bg: 'rgba(100,116,139,0.12)', border: '#64748b', color: '#64748b' },
};

// ── Attendance log pagination ──────────────────────────────────────────────
var attPage = 1;
var attPerPage = 10;

function renderAttPagination(filteredRows) {
    var total   = filteredRows.length;
    var pages   = Math.ceil(total / attPerPage) || 1;
    if (attPage > pages) attPage = pages;
    var start   = (attPage - 1) * attPerPage;
    var end     = Math.min(start + attPerPage, total);

    filteredRows.forEach(function(row, i) {
        row.style.display = (i >= start && i < end) ? '' : 'none';
    });

    var pg = document.getElementById('attPagination');
    if (!pg) return;
    if (total <= attPerPage) { pg.innerHTML = ''; return; }

    var info = '<span style="font-size:12px;color:#6b7280">Showing ' + (start+1) + '–' + end + ' of ' + total + '</span>';
    var btns = '<div style="display:flex;gap:4px;align-items:center">';
    btns += '<button onclick="attGoPage(' + (attPage-1) + ')" ' + (attPage===1?'disabled':'') + ' style="padding:4px 10px;border-radius:8px;border:1.5px solid #e5e7eb;background:#fff;font-size:12px;font-weight:600;color:#6b7280;cursor:pointer;' + (attPage===1?'opacity:.4;cursor:default':'') + '">‹ Prev</button>';
    var from = Math.max(1, attPage-2), to = Math.min(pages, from+4);
    for (var p = from; p <= to; p++) {
        var active = p === attPage;
        btns += '<button onclick="attGoPage(' + p + ')" style="padding:4px 10px;border-radius:8px;border:1.5px solid ' + (active?'#dc2626':'#e5e7eb') + ';background:' + (active?'#dc2626':'#fff') + ';font-size:12px;font-weight:700;color:' + (active?'#fff':'#374151') + ';cursor:pointer">' + p + '</button>';
    }
    btns += '<button onclick="attGoPage(' + (attPage+1) + ')" ' + (attPage===pages?'disabled':'') + ' style="padding:4px 10px;border-radius:8px;border:1.5px solid #e5e7eb;background:#fff;font-size:12px;font-weight:600;color:#6b7280;cursor:pointer;' + (attPage===pages?'opacity:.4;cursor:default':'') + '">Next ›</button>';
    btns += '</div>';
    pg.innerHTML = info + btns;
}

function attGoPage(p) {
    attPage = p;
    filterAtt();
}

function filterAtt(type) {
    if (type !== undefined) {
        attCurrentType = type;
        attPage = 1;
        document.querySelectorAll('.att-filter-btn').forEach(btn => {
            btn.style.background  = '#fff';
            btn.style.borderColor = btn.dataset.defaultBorder;
            btn.style.color       = btn.dataset.defaultColor;
            btn.style.fontWeight  = '600';
        });
        if (type !== 'all') {
            const allBtn = document.getElementById('attf-all');
            if (allBtn) {
                allBtn.style.background  = '#fff';
                allBtn.style.borderColor = '#e5e7eb';
                allBtn.style.color       = '#6b7280';
                allBtn.style.fontWeight  = '600';
            }
        }
        const active = document.getElementById('attf-' + type);
        if (active) {
            const c = attColors[type] || attColors.all;
            active.style.background  = c.bg;
            active.style.borderColor = c.border;
            active.style.color       = c.color;
            active.style.fontWeight  = '700';
        }
    }
    const unloggedOnly = document.getElementById('attUnloggedOnly')?.checked;
    const search = (document.getElementById('attSearchInput')?.value || '').trim().toLowerCase();
    const sort   = document.getElementById('attSortInput')?.value || 'latest';

    // Reorder rows in the DOM by date so pagination reflects the chosen sort
    const tbody = document.getElementById('attTableBody');
    if (tbody) {
        const sortedRows = Array.from(tbody.querySelectorAll('.att-row')).sort((a, b) => {
            const da = a.dataset.date || '', db = b.dataset.date || '';
            return sort === 'oldest' ? da.localeCompare(db) : db.localeCompare(da);
        });
        sortedRows.forEach(row => tbody.appendChild(row));
    }

    const rows = Array.from(document.querySelectorAll('.att-row'));
    // First hide all
    rows.forEach(row => row.style.display = 'none');
    // Filter to matching
    const filtered = rows.filter(row => {
        const matchType   = attCurrentType === 'all' || row.dataset.type === attCurrentType;
        const matchLogged = !unloggedOnly || row.classList.contains('att-unlogged');
        const matchSearch = !search || (row.dataset.name || '').includes(search);
        return matchType && matchLogged && matchSearch;
    });
    const emptyMsg = document.getElementById('attEmptyMsg');
    if (emptyMsg) emptyMsg.style.display = filtered.length === 0 ? 'block' : 'none';
    renderAttPagination(filtered);
}

// ── Data table pagination ──────────────────────────────────────────────────
var dataPage = 1;
var dataPerPage = 12;

function renderDataPagination() {
    var tbody = document.getElementById('dataTableBody');
    if (!tbody) return;
    var fromYm = document.getElementById('dataFromInput')?.value || '';
    var toYm   = document.getElementById('dataToInput')?.value || '';
    var rows   = Array.from(tbody.querySelectorAll('tr'));

    rows.forEach(function(row) { row.style.display = 'none'; });
    var filtered = rows.filter(function(row) {
        var ym = row.dataset.ym || '';
        return (!fromYm || ym >= fromYm) && (!toYm || ym <= toYm);
    });

    var total = filtered.length;
    var pages = Math.ceil(total / dataPerPage) || 1;
    if (dataPage > pages) dataPage = pages;
    var start = (dataPage - 1) * dataPerPage;
    var end   = Math.min(start + dataPerPage, total);

    filtered.forEach(function(row, i) {
        row.style.display = (i >= start && i < end) ? '' : 'none';
    });

    var pg = document.getElementById('dataPagination');
    if (!pg) return;
    if (total <= dataPerPage) { pg.innerHTML = ''; return; }

    var info = '<span style="font-size:12px;color:#6b7280">Showing ' + (start+1) + '–' + end + ' of ' + total + ' months</span>';
    var btns = '<div style="display:flex;gap:4px;align-items:center">';
    btns += '<button onclick="dataGoPage(' + (dataPage-1) + ')" ' + (dataPage===1?'disabled':'') + ' style="padding:4px 10px;border-radius:8px;border:1.5px solid #e5e7eb;background:#fff;font-size:12px;font-weight:600;color:#6b7280;cursor:pointer;' + (dataPage===1?'opacity:.4;cursor:default':'') + '">‹ Prev</button>';
    var from = Math.max(1, dataPage-2), to = Math.min(pages, from+4);
    for (var p = from; p <= to; p++) {
        var active = p === dataPage;
        btns += '<button onclick="dataGoPage(' + p + ')" style="padding:4px 10px;border-radius:8px;border:1.5px solid ' + (active?'#dc2626':'#e5e7eb') + ';background:' + (active?'#dc2626':'#fff') + ';font-size:12px;font-weight:700;color:' + (active?'#fff':'#374151') + ';cursor:pointer">' + p + '</button>';
    }
    btns += '<button onclick="dataGoPage(' + (dataPage+1) + ')" ' + (dataPage===pages?'disabled':'') + ' style="padding:4px 10px;border-radius:8px;border:1.5px solid #e5e7eb;background:#fff;font-size:12px;font-weight:600;color:#6b7280;cursor:pointer;' + (dataPage===pages?'opacity:.4;cursor:default':'') + '">Next ›</button>';
    btns += '</div>';
    pg.innerHTML = info + btns;
}

function dataGoPage(p) {
    dataPage = p;
    renderDataPagination();
}

function dataResetFilter() {
    const tbody = document.getElementById('dataTableBody');
    if (!tbody) return;
    const yms = Array.from(tbody.querySelectorAll('tr')).map(r => r.dataset.ym).filter(Boolean).sort();
    if (yms.length === 0) return;
    admCslSetValue('adm-csl-data-from', 'dataFromInput', yms[0]);
    admCslSetValue('adm-csl-data-to', 'dataToInput', yms[yms.length - 1]);
    dataPage = 1;
    renderDataPagination();
}

document.addEventListener('DOMContentLoaded', function () {
    filterAtt('all');
    renderDataPagination();
});

function showLoggedCards() {
    const stats = document.getElementById('logProgressStats');
    const cards = document.getElementById('loggedCardsView');
    stats.style.transition = 'opacity .2s ease';
    stats.style.opacity = '0';
    setTimeout(function () {
        stats.style.display = 'none';
        cards.style.display = 'block';
        requestAnimationFrame(function () {
            cards.style.opacity = '1';
        });
        loggedFilterAndSort();
    }, 200);
}

function loggedFilterAndSort() {
    const list   = document.getElementById('loggedCardsList');
    const empty  = document.getElementById('loggedCardsEmpty');
    if (!list) return;
    const search = (document.getElementById('loggedSearchInput')?.value || '').trim().toLowerCase();
    const sort   = document.getElementById('loggedSortInput')?.value || 'latest';
    const cards  = Array.from(list.querySelectorAll('.logged-card'));

    cards.sort(function (a, b) {
        const da = a.dataset.date || '', db = b.dataset.date || '';
        return sort === 'oldest' ? da.localeCompare(db) : db.localeCompare(da);
    });
    cards.forEach(function (card) { list.appendChild(card); });

    let visibleCount = 0;
    cards.forEach(function (card) {
        const matches = !search || card.dataset.name.includes(search);
        card.style.display = matches ? '' : 'none';
        if (matches) visibleCount++;
    });
    if (empty) empty.style.display = visibleCount === 0 ? 'block' : 'none';
}

function hideLoggedCards() {
    const stats = document.getElementById('logProgressStats');
    const cards = document.getElementById('loggedCardsView');
    cards.style.transition = 'opacity .2s ease';
    cards.style.opacity = '0';
    setTimeout(function () {
        cards.style.display = 'none';
        stats.style.display = 'block';
        requestAnimationFrame(function () {
            stats.style.opacity = '1';
        });
    }, 200);
}

function openAttModal(resId, eventType, name, count, notes) {
    document.getElementById('attResId').value     = resId;
    document.getElementById('attEventType').value = eventType;
    document.getElementById('attCount').value     = count || '';
    document.getElementById('attNotes').value     = notes || '';
    document.getElementById('attModalTitle').textContent = (count > 0 ? 'Edit' : 'Log') + ' Attendance';
    document.getElementById('attModalSub').textContent   = eventType + ' — ' + name;
    document.getElementById('attendanceModal').classList.add('is-open');
}

function closeAttModal() {
    document.getElementById('attendanceModal').classList.remove('is-open');
}
</script>
</div>

@endif

<script>
function toggleDenyForm(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.style.display = el.style.display === 'block' ? 'none' : 'block';
    if (el.style.display === 'block') {
        el.querySelector('textarea').focus();
    }
}

function togglePastApproved(btn) {
    const section = document.getElementById('pastApprovedSection');
    if (!section) return;
    const isOpen = section.style.display !== 'none';
    section.style.display = isOpen ? 'none' : 'block';
    const icon = btn.querySelector('.fa-chevron-down, .fa-chevron-up');
    if (icon) icon.className = isOpen ? 'fa fa-chevron-down' : 'fa fa-chevron-up';
    const label = btn.querySelector('span');
    if (label) {
        const count = label.textContent.match(/\d+/)?.[0] ?? '';
        const word = parseInt(count) > 1 ? 'events' : 'event';
        label.textContent = isOpen ? count + ' completed ' + word : 'Hide completed ' + word;
    }
}
function togglePriestManager() {
    const el = document.getElementById('priestManager');
    if (!el) return;
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
(function() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('show_priest_manager') === '1') {
        const el = document.getElementById('priestManager');
        if (el) el.style.display = 'block';
    }
})();

// Announcement: image preview
function annPreviewImage(input, previewId) {
    const wrap = document.getElementById(previewId);
    if (!wrap) return;
    const img = wrap.querySelector('img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; wrap.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    } else {
        wrap.style.display = 'none';
    }
}

// Announcement admin list: read more toggle
function annAdminToggle(btn, bodyId) {
    const body = document.getElementById(bodyId);
    if (!body) return;
    const clamped = body.classList.toggle('clamped');
    btn.innerHTML = clamped
        ? '<i class="fa fa-chevron-down"></i> Read more'
        : '<i class="fa fa-chevron-up"></i> Show less';
}

// Edit modal open/close
function annOpenEdit(id, title, body, category, visible, imgUrl) {
    document.getElementById('edit_ann_id').value       = id;
    document.getElementById('edit_ann_title').value    = title;
    document.getElementById('edit_ann_body').value     = body;
    document.getElementById('edit_ann_show').checked   = visible === 1;
    document.getElementById('edit-title-count').textContent = title.length + ' / 150';
    document.getElementById('edit-body-count').textContent  = body.length + ' / 500';
    // category
    admCslSetValue('adm-csl-edit-ann-category', 'edit_ann_category', category || '');
    // current image
    const imgWrap = document.getElementById('edit_ann_current_img');
    const imgEl   = document.getElementById('edit_ann_current_img_el');
    if (imgUrl) { imgEl.src = imgUrl; imgWrap.style.display = 'block'; }
    else { imgWrap.style.display = 'none'; }
    // reset new image preview
    const prev = document.getElementById('ann-img-preview-edit');
    if (prev) prev.style.display = 'none';
    // show modal
    document.getElementById('annEditModal').classList.add('is-open');
    document.body.style.overflow = 'hidden';
}
function annCloseEdit() {
    document.getElementById('annEditModal').classList.remove('is-open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') { annCloseEdit(); if (window.closeAttModal) closeAttModal(); } });

function annConfirmDelete(formId, title) {
    showAdminConfirm('Delete Announcement', 'Delete "' + title + '"? This cannot be undone.', function () {
        document.getElementById(formId).submit();
    });
}

function priestConfirmDelete(formId, name) {
    showAdminConfirm('Remove Priest', 'Remove "' + name + '" from the roster?', function () {
        document.getElementById(formId).submit();
    });
}

function resTabFilter(status, btn) {
    // Update active tab
    document.querySelectorAll('.res-tab-btn').forEach(b => {
        b.classList.remove('active-all','active-pending','active-approved','active-declined');
    });
    btn.classList.add('active-' + status);

    // Show/hide cards
    document.querySelectorAll('#res-cards-container .reservation-card').forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.classList.remove('res-card-hidden');
        } else {
            card.classList.add('res-card-hidden');
        }
    });
}

function toggleCancelView(btn) {
    const panel    = document.getElementById('cancel-overlay-panel');
    const backdrop = document.getElementById('cancel-overlay-backdrop');
    if (!panel) return;
    panel.style.display    = 'flex';
    backdrop.style.display = 'block';
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        panel.style.transition = 'transform .22s cubic-bezier(.34,1.56,.64,1), opacity .18s';
        panel.style.opacity    = '1';
        panel.style.transform  = 'translate(-50%,-50%) scale(1)';
    });
}

function closeCancelPanel() {
    const panel    = document.getElementById('cancel-overlay-panel');
    const backdrop = document.getElementById('cancel-overlay-backdrop');
    if (!panel) return;
    panel.style.transform = 'translate(-50%,-50%) scale(.96)';
    panel.style.opacity   = '0';
    document.body.style.overflow = '';
    setTimeout(() => {
        panel.style.display    = 'none';
        backdrop.style.display = 'none';
    }, 180);
}

function openDenyModal(id) {
    const el = document.getElementById('deny-overlay-' + id);
    if (!el) return;
    el.classList.add('is-open');
    document.body.style.overflow = 'hidden';
}

function closeDenyModal(id) {
    const el = document.getElementById('deny-overlay-' + id);
    if (!el) return;
    el.classList.remove('is-open');
    document.body.style.overflow = '';
}

function openResDetail(r) {
    const eh = s => String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    const funeralIconSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="9" height="9" fill="currentColor"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg>';
    document.getElementById('rdm-name').textContent = r.name || '—';
    document.getElementById('rdm-sub').textContent  = '#' + r.id + (r.created_at ? ' · Submitted ' + r.created_at.substring(0,10) : '');
    // Badges
    const bl = document.getElementById('rdm-badges'); bl.innerHTML = '';
    const mkBadge = (cls, txt, dotted, iconHTML) => {
        const s = document.createElement('span'); s.className = 'rdm-badge ' + cls;
        if (dotted) { const d = document.createElement('span'); d.className = 'rdm-pulse-dot'; s.appendChild(d); }
        if (iconHTML) { s.insertAdjacentHTML('beforeend', iconHTML); }
        const t = document.createElement('span'); t.textContent = txt; s.appendChild(t); bl.appendChild(s);
    };
    const mkStatusPill = (status) => {
        const s = document.createElement('span'); s.className = 'status-pill spill-' + status;
        const d = document.createElement('span'); d.className = 'spill-dot'; s.appendChild(d);
        const t = document.createElement('span'); t.className = 'spill-label'; t.textContent = status.charAt(0).toUpperCase() + status.slice(1); s.appendChild(t);
        bl.appendChild(s);
    };
    const statusLabel = (r.status||'pending');
    mkStatusPill(statusLabel);
    if (r.event_type) {
        const et = r.event_type.toLowerCase();
        if (et === 'funeral') {
            mkBadge('rdm-badge-'+et, r.event_type, false, funeralIconSvg);
        } else {
            const etIconChar = {wedding:'♥', baptism:'+'};
            const iconCls = 'rdm-badge-icon' + (et === 'baptism' ? ' icon-baptism' : '');
            mkBadge('rdm-badge-'+et, r.event_type, false, `<span class="${iconCls}">${etIconChar[et]||''}</span>`);
        }
    }
    if (r.is_urgent) mkBadge('rdm-badge-urgent', 'Urgent', false, '<span class="rdm-badge-icon">!</span>');
    // Contact
    document.getElementById('rdm-email').textContent    = r.email || 'Not provided';
    document.getElementById('rdm-phone').textContent    = r.phone || 'Not provided';
    // Schedule
    document.getElementById('rdm-date').textContent     = r.date || '—';
    document.getElementById('rdm-day').textContent      = r.date_day || '';
    document.getElementById('rdm-time').textContent     = r.time || '—';
    const offRow = document.getElementById('rdm-off-row');
    if (r.officiant) { document.getElementById('rdm-officiant').textContent = r.officiant; offRow.style.display='flex'; }
    else offRow.style.display = 'none';
    // Baptism details
    const bapSec = document.getElementById('rdm-bap-sec');
    if (bapSec) {
        const isBap = (r.event_type||'').toLowerCase() === 'baptism';
        bapSec.style.display = isBap ? '' : 'none';
        if (isBap) {
            const b = r.baptism || {};
            const childBase = [b.child_first, b.child_middle, b.child_last].filter(Boolean).join(' ');
            const childName = (childBase + (b.child_suffix ? ', ' + b.child_suffix : '')) || b.child_name || '—';
            const dobRaw = b.child_dob || '';
            let dobFmt = '—';
            if (dobRaw) { try { const d = new Date(dobRaw+'T00:00:00'); dobFmt = d.toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'}); } catch(e){dobFmt=dobRaw;} }
            const fatherName = b.father_name || '—';
            const motherName = b.mother_name || '—';
            document.getElementById('rdm-bap-child').textContent   = childName;
            document.getElementById('rdm-bap-dob').textContent     = dobFmt;
            document.getElementById('rdm-bap-father').textContent  = fatherName;
            document.getElementById('rdm-bap-mother').textContent  = motherName;
        }
    }
    // Wedding details
    const wedSec = document.getElementById('rdm-wed-sec');
    if (wedSec) {
        const isWed = (r.event_type||'').toLowerCase() === 'wedding';
        wedSec.style.display = isWed ? '' : 'none';
        if (isWed) {
            const w = r.wedding || {};
            const groomBase = [w.groom_first, w.groom_middle, w.groom_last].filter(Boolean).join(' ');
            const groom = (groomBase + (w.groom_suffix ? ', ' + w.groom_suffix : '')) || '—';
            const brideBase = [w.bride_first, w.bride_middle, w.bride_last].filter(Boolean).join(' ');
            const bride = (brideBase + (w.bride_suffix ? ', ' + w.bride_suffix : '')) || '—';
            let seminarFmt = '—';
            if (w.seminar_date) { try { const d = new Date(w.seminar_date+'T00:00:00'); seminarFmt = d.toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'}); } catch(e){seminarFmt=w.seminar_date;} }
            document.getElementById('rdm-wed-groom').textContent   = groom;
            document.getElementById('rdm-wed-bride').textContent   = bride;
            document.getElementById('rdm-wed-seminar').textContent = seminarFmt;
            document.getElementById('rdm-wed-sacrament').textContent = w.sacrament_details || '—';
            document.getElementById('rdm-wed-sac-cell').style.display = '';
        }
    }
    // Funeral details
    const funSec = document.getElementById('rdm-fun-sec');
    if (funSec) {
        const isFun = (r.event_type||'').toLowerCase() === 'funeral';
        funSec.style.display = isFun ? '' : 'none';
        if (isFun) {
            const f = r.funeral || {};
            const decBase = [f.deceased_first, f.deceased_middle, f.deceased_last].filter(Boolean).join(' ');
            const deceased = (decBase + (f.deceased_suffix ? ', ' + f.deceased_suffix : '')) || '—';
            const maritalLabels = {
                'single':               'Single / Unmarried',
                'married_baptized':     'Married (baptized in the church)',
                'married_not_baptized': 'Married (not baptized in the church)',
                'widowed':              'Widowed',
                'annulled':             'Annulled',
            };
            document.getElementById('rdm-fun-deceased').textContent = deceased;
            document.getElementById('rdm-fun-marital').textContent  = maritalLabels[f.marital_status] || f.marital_status || '—';
        }
    }
    // Notes
    const notesSec = document.getElementById('rdm-notes-sec');
    if (r.notes) { document.getElementById('rdm-notes').textContent=r.notes; notesSec.style.display='block'; }
    else notesSec.style.display='none';
    // Attachments
    const attSec = document.getElementById('rdm-att-sec');
    const attList = document.getElementById('rdm-att-list');
    if (r.attachments && r.attachments.length) {
        attList.innerHTML = r.attachments.map(a => {
            const icon = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>`;
            const nameEl = a.path
                ? `<a class="rdm-att-link" href="${eh(a.path)}" target="_blank" rel="noopener">${eh(a.file)}</a>`
                : `<div class="rdm-att-name">${eh(a.file)}</div>`;
            return `<div class="rdm-att-row">${icon}<div>${nameEl}<div class="rdm-att-label">${eh(a.label)}</div></div></div>`;
        }).join('');
        attSec.style.display='block';
    } else attSec.style.display='none';
    // Form IDs
    document.getElementById('rdm-app-id').value = r.id;
    document.getElementById('rdm-dec-id').value = r.id;
    // Approve/Decline visibility
    const appBtn = document.getElementById('rdm-app-btn');
    const decBtn = document.getElementById('rdm-dec-btn');
    appBtn.style.display = r.status==='approved' ? 'none' : 'inline-flex';
    decBtn.style.display = r.status==='declined' ? 'none' : 'inline-flex';
    if (r.is_one_day_before) { appBtn.disabled=true; appBtn.style.opacity='.35'; appBtn.style.cursor='not-allowed'; appBtn.title='Cannot approve — event is tomorrow or past'; }
    else { appBtn.disabled=false; appBtn.style.opacity=''; appBtn.style.cursor=''; appBtn.title=''; }
    // Open
    document.getElementById('rdm-backdrop').classList.add('is-open');
    document.getElementById('rdm-modal').classList.add('is-open');
    document.body.style.overflow = 'hidden';
}

function closeResDetail() {
    document.getElementById('rdm-backdrop').classList.remove('is-open');
    document.getElementById('rdm-modal').classList.remove('is-open');
    document.body.style.overflow = '';
}

// Custom dropdown helpers
function admCslToggle(id) {
    const csl = document.getElementById(id);
    const isOpen = csl.classList.contains('is-open');
    // Close all other open dropdowns
    document.querySelectorAll('.adm-csl.is-open').forEach(el => {
        el.classList.remove('is-open');
        el.querySelector('.adm-csl-btn')?.classList.remove('is-open');
    });
    if (!isOpen) {
        csl.classList.add('is-open');
        csl.querySelector('.adm-csl-btn')?.classList.add('is-open');
    }
}

function admCslSelect(cslId, inputId, value, label) {
    const csl = document.getElementById(cslId);
    document.getElementById(inputId).value = value;
    const labelEl = csl.querySelector('.adm-csl-label');
    labelEl.textContent = label;
    labelEl.classList.toggle('adm-csl-placeholder', event.currentTarget.dataset.placeholder === '1');
    csl.querySelectorAll('.adm-csl-list li').forEach(li => li.classList.remove('is-selected'));
    event.currentTarget.classList.add('is-selected');
    csl.classList.remove('is-open');
    csl.querySelector('.adm-csl-btn')?.classList.remove('is-open');
    // For range filter: show/hide date picker
    if (inputId === 'reservation_filter_range') {
        const wrap = document.querySelector('[data-reservation-filter-date-wrapper]');
        if (wrap) wrap.classList.toggle('is-hidden', value !== 'date');
    }
}

function admCslSetValue(cslId, inputId, value) {
    const csl = document.getElementById(cslId);
    if (!csl) return;
    const input = document.getElementById(inputId);
    if (input) input.value = value || '';
    let matched = null;
    csl.querySelectorAll('.adm-csl-list li').forEach(function (li) {
        li.classList.remove('is-selected');
        if (li.dataset.value === (value || '')) matched = li;
    });
    if (matched) {
        matched.classList.add('is-selected');
        const labelEl = csl.querySelector('.adm-csl-label');
        if (labelEl) {
            labelEl.textContent = matched.textContent.trim();
            labelEl.classList.toggle('adm-csl-placeholder', matched.dataset.placeholder === '1');
        }
    }
}

function admCslSelectAndSubmit(cslId, inputId, value, label, formId) {
    const csl = document.getElementById(cslId);
    const clickedLi = event.currentTarget;
    csl.classList.remove('is-open');
    csl.querySelector('.adm-csl-btn')?.classList.remove('is-open');

    const title = value ? 'Assign Officiant' : 'Remove Officiant';
    const text  = value
        ? 'Assign ' + label + ' as the officiant for this reservation?'
        : 'Remove the assigned officiant from this reservation?';

    showAdminConfirm(title, text, function () {
        document.getElementById(inputId).value = value;
        csl.querySelector('.adm-csl-label').textContent = label;
        csl.querySelectorAll('.adm-csl-list li').forEach(li => li.classList.remove('is-selected'));
        clickedLi?.classList.add('is-selected');
        document.getElementById(formId).submit();
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!document.contains(e.target)) return; // element removed by innerHTML re-render
    if (!e.target.closest('.adm-csl') && !e.target.closest('.adm-cal-wrap')) {
        document.querySelectorAll('.adm-csl.is-open').forEach(el => {
            el.classList.remove('is-open');
            el.querySelector('.adm-csl-btn')?.classList.remove('is-open');
        });
        admCalClose();
        if (window.donCalClose) donCalClose();
        if (window.donFromCalClose) donFromCalClose();
        if (window.donToCalClose) donToCalClose();
    }
});

// Hide the page's own title/header bar while printing the Reports or Donations
// print-only document, so it doesn't duplicate the letterhead's title. The
// Donations record list is short and reads better as a portrait page, so we
// swap the page size only for that document.
let __printPrevAttPerPage, __printPrevDataPerPage;
window.addEventListener('beforeprint', function () {
    if (document.getElementById('rptPrintDoc') || document.getElementById('donPrintDoc')) {
        document.querySelector('.main-header')?.classList.add('print-hide-header');
    }
    const sizeStyle = document.getElementById('printPageSizeOverride');
    if (sizeStyle) {
        sizeStyle.textContent = document.getElementById('donPrintDoc')
            ? '@page { size: A4 portrait; margin: 14mm; }'
            : '';
    }
    // Printing the Reports page should include every row, not just the
    // current page of the on-screen pagination.
    if (typeof attPerPage !== 'undefined') { __printPrevAttPerPage = attPerPage; attPerPage = 100000; filterAtt(); }
    if (typeof dataPerPage !== 'undefined') { __printPrevDataPerPage = dataPerPage; dataPerPage = 100000; renderDataPagination(); }
});
window.addEventListener('afterprint', function () {
    document.querySelector('.main-header')?.classList.remove('print-hide-header');
    const sizeStyle = document.getElementById('printPageSizeOverride');
    if (sizeStyle) sizeStyle.textContent = '';
    if (typeof attPerPage !== 'undefined' && __printPrevAttPerPage !== undefined) { attPerPage = __printPrevAttPerPage; filterAtt(); }
    if (typeof dataPerPage !== 'undefined' && __printPrevDataPerPage !== undefined) { dataPerPage = __printPrevDataPerPage; renderDataPagination(); }
});

// Mini calendar
(function() {
    const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const MON_ABB = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    let calYear, calMonth, calSelected = null, calView = 'day';

    const init = {{ $reservationFilterDate ? "'" . $reservationFilterDate . "'" : 'null' }};
    if (init) {
        const d = new Date(init + 'T00:00:00');
        calYear = d.getFullYear(); calMonth = d.getMonth();
        calSelected = { y: calYear, m: calMonth, d: d.getDate() };
    } else {
        const now = new Date(); calYear = now.getFullYear(); calMonth = now.getMonth();
    }

    function render() {
        const popup = document.getElementById('adm-cal-popup');
        if (!popup) return;
        // Keep header/footer wrappers, only replace inner content area
        if (calView === 'month') { renderMonthView(popup); return; }
        if (calView === 'year')  { renderYearView(popup);  return; }
        renderDayView(popup);
    }

    function renderDayView(popup) {
        const label = popup.querySelector('#adm-cal-month-label');
        const grid  = popup.querySelector('#adm-cal-grid');
        const subGrid = popup.querySelector('#adm-cal-sub-grid');
        if (label) {
            label.innerHTML = `<button type="button" class="adm-cal-hdr-btn" onclick="admCalSetView('month')">${MONTHS[calMonth]}</button><button type="button" class="adm-cal-hdr-btn" onclick="admCalSetView('year')">${calYear}</button>`;
        }
        if (subGrid) subGrid.style.display = 'none';
        if (!grid) return;
        grid.style.display = '';

        const firstDay = new Date(calYear, calMonth, 1).getDay();
        const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
        const today = new Date();
        let html = '';
        for (let i = 0; i < firstDay; i++) html += `<span class="adm-cal-day" style="visibility:hidden;pointer-events:none;"></span>`;
        for (let d = 1; d <= daysInMonth; d++) {
            let cls = 'adm-cal-day';
            if (today.getFullYear()===calYear && today.getMonth()===calMonth && today.getDate()===d) cls += ' is-today';
            if (calSelected && calSelected.y===calYear && calSelected.m===calMonth && calSelected.d===d) cls += ' is-selected';
            html += `<button type="button" class="${cls}" data-y="${calYear}" data-m="${calMonth}" data-d="${d}" onclick="admCalPick(this)">${d}</button>`;
        }
        const total = firstDay + daysInMonth;
        const nextCells = total % 7 === 0 ? 0 : 7 - (total % 7);
        for (let i = 0; i < nextCells; i++) html += `<span class="adm-cal-day" style="visibility:hidden;pointer-events:none;"></span>`;
        grid.innerHTML = html;
    }

    function renderMonthView(popup) {
        const label = popup.querySelector('#adm-cal-month-label');
        const grid  = popup.querySelector('#adm-cal-grid');
        const subGrid = popup.querySelector('#adm-cal-sub-grid') || createSubGrid(popup);
        if (label) label.innerHTML = `<button type="button" class="adm-cal-hdr-btn" onclick="admCalSetView('year')">${calYear}</button>`;
        if (grid) grid.style.display = 'none';
        const now = new Date(); let html = '';
        for (let m = 0; m < 12; m++) {
            const isT = now.getFullYear()===calYear && now.getMonth()===m;
            const isS = calSelected && calSelected.y===calYear && calSelected.m===m;
            html += `<button type="button" class="adm-cal-mcell${isT?' is-today':''}${isS?' is-selected':''}" onclick="admCalPickMonth(${m})">${MON_ABB[m]}</button>`;
        }
        subGrid.className = 'adm-cal-month-grid'; subGrid.innerHTML = html; subGrid.style.display = '';
    }

    function renderYearView(popup) {
        const label = popup.querySelector('#adm-cal-month-label');
        const grid  = popup.querySelector('#adm-cal-grid');
        const subGrid = popup.querySelector('#adm-cal-sub-grid') || createSubGrid(popup);
        const startYear = Math.floor(calYear / 12) * 12;
        if (label) label.innerHTML = `<span style="font-size:.82rem;font-weight:800;color:#0f172a">${startYear}–${startYear+11}</span>`;
        if (grid) grid.style.display = 'none';
        const now = new Date(); let html = '';
        for (let y = startYear; y < startYear + 12; y++) {
            const isT = now.getFullYear()===y; const isS = calSelected && calSelected.y===y;
            html += `<button type="button" class="adm-cal-ycell${isT?' is-today':''}${isS?' is-selected':''}" onclick="admCalPickYear(${y})">${y}</button>`;
        }
        subGrid.className = 'adm-cal-year-grid'; subGrid.innerHTML = html; subGrid.style.display = '';
    }

    function createSubGrid(popup) {
        const footer = popup.querySelector('.adm-cal-footer');
        const el = document.createElement('div'); el.id = 'adm-cal-sub-grid';
        popup.insertBefore(el, footer); return el;
    }

    window.admCalSetView  = function(v) { calView = v; render(); };
    window.admCalPickMonth = function(m) { calMonth = m; calView = 'day'; render(); };
    window.admCalPickYear  = function(y) { calYear = y; calView = 'month'; render(); };

    window.admCalToggle = function() {
        const popup   = document.getElementById('adm-cal-popup');
        const trigger = document.getElementById('adm-cal-trigger');
        const arrow   = document.getElementById('adm-cal-arrow');
        const isOpen  = popup.style.display !== 'none';
        document.querySelectorAll('.adm-csl.is-open').forEach(el => {
            el.classList.remove('is-open');
            el.querySelector('.adm-csl-btn')?.classList.remove('is-open');
        });
        closeAllCalendarPopups('adm-cal-popup');
        if (isOpen) {
            popup.style.display = 'none'; trigger.classList.remove('is-open');
            if (arrow) arrow.style.transform = '';
        } else {
            calView = 'day'; render();
            popup.style.display = 'block'; trigger.classList.add('is-open');
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        }
    };

    window.admCalClose = function() {
        const popup=document.getElementById('adm-cal-popup'), trigger=document.getElementById('adm-cal-trigger'), arrow=document.getElementById('adm-cal-arrow');
        if (popup) popup.style.display = 'none';
        if (trigger) trigger.classList.remove('is-open');
        if (arrow) arrow.style.transform = '';
    };

    window.admCalShift = function(dir) {
        if (calView==='day')   { calMonth+=dir; if(calMonth>11){calMonth=0;calYear++;}if(calMonth<0){calMonth=11;calYear--;} }
        else if (calView==='month') { calYear+=dir; }
        else { calYear = Math.floor(calYear/12)*12 + dir*12; }
        render();
    };

    window.admCalPick = function(el) {
        let y=parseInt(el.dataset.y), m=parseInt(el.dataset.m), d=parseInt(el.dataset.d);
        const nd=new Date(y,m,d); y=nd.getFullYear(); m=nd.getMonth(); d=nd.getDate();
        calYear=y; calMonth=m; calSelected={y,m,d};
        const pad=n=>String(n).padStart(2,'0');
        document.getElementById('reservation_filter_date').value=`${y}-${pad(m+1)}-${pad(d)}`;
        document.getElementById('adm-cal-label').textContent=MON_ABB[m]+' '+pad(d)+', '+y;
        admCalClose();
    };

    window.admCalSelectToday = function() {
        const now=new Date();
        admCalPick({dataset:{y:now.getFullYear(),m:now.getMonth(),d:now.getDate()}});
    };

    window.admCalClear = function() {
        calSelected=null;
        document.getElementById('reservation_filter_date').value='';
        document.getElementById('adm-cal-label').textContent='Pick a date';
        admCalClose();
    };
})();

// Shared: closes every open custom calendar popup except the one being toggled,
// so opening one always closes the others instead of stacking on screen.
const ADM_CAL_ICON = '<i class="fa fa-calendar" style="color:#94a3b8;margin-right:7px;"></i>';
function closeAllCalendarPopups(exceptId) {
    document.querySelectorAll('.adm-cal-popup').forEach(function (popup) {
        if (popup.id === exceptId) return;
        popup.style.display = 'none';
        const prefix = popup.id.replace(/-popup$/, '');
        const trigger = document.getElementById(prefix + '-trigger');
        const arrow = document.getElementById(prefix + '-arrow');
        if (trigger) trigger.classList.remove('is-open');
        if (arrow) arrow.style.transform = '';
    });
}

// Donation "Date Received" calendar
(function() {
    const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const MON_ABB = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    let calYear, calMonth, calSelected = null, calView = 'day';

    const initVal = '{{ old("donation_date", date("Y-m-d")) }}';
    const d = new Date(initVal + 'T00:00:00');
    calYear = d.getFullYear(); calMonth = d.getMonth();
    calSelected = { y: calYear, m: calMonth, d: d.getDate() };

    function render() {
        const popup = document.getElementById('don-cal-popup');
        if (!popup) return;
        if (calView === 'month') { renderMonthView(popup); return; }
        if (calView === 'year')  { renderYearView(popup);  return; }
        renderDayView(popup);
    }

    function renderDayView(popup) {
        const label = popup.querySelector('#don-cal-month-label');
        const grid  = popup.querySelector('#don-cal-grid');
        const subGrid = popup.querySelector('#don-cal-sub-grid');
        if (label) {
            label.innerHTML = `<button type="button" class="adm-cal-hdr-btn" onclick="donCalSetView('month')">${MONTHS[calMonth]}</button><button type="button" class="adm-cal-hdr-btn" onclick="donCalSetView('year')">${calYear}</button>`;
        }
        if (subGrid) subGrid.style.display = 'none';
        if (!grid) return;
        grid.style.display = '';

        const firstDay = new Date(calYear, calMonth, 1).getDay();
        const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
        const today = new Date();
        let html = '';
        for (let i = 0; i < firstDay; i++) html += `<span class="adm-cal-day" style="visibility:hidden;pointer-events:none;"></span>`;
        for (let dd = 1; dd <= daysInMonth; dd++) {
            let cls = 'adm-cal-day';
            if (today.getFullYear()===calYear && today.getMonth()===calMonth && today.getDate()===dd) cls += ' is-today';
            if (calSelected && calSelected.y===calYear && calSelected.m===calMonth && calSelected.d===dd) cls += ' is-selected';
            html += `<button type="button" class="${cls}" data-y="${calYear}" data-m="${calMonth}" data-d="${dd}" onclick="donCalPick(this)">${dd}</button>`;
        }
        const total = firstDay + daysInMonth;
        const nextCells = total % 7 === 0 ? 0 : 7 - (total % 7);
        for (let i = 0; i < nextCells; i++) html += `<span class="adm-cal-day" style="visibility:hidden;pointer-events:none;"></span>`;
        grid.innerHTML = html;
    }

    function renderMonthView(popup) {
        const label = popup.querySelector('#don-cal-month-label');
        const grid  = popup.querySelector('#don-cal-grid');
        const subGrid = popup.querySelector('#don-cal-sub-grid') || createSubGrid(popup);
        if (label) label.innerHTML = `<button type="button" class="adm-cal-hdr-btn" onclick="donCalSetView('year')">${calYear}</button>`;
        if (grid) grid.style.display = 'none';
        const now = new Date(); let html = '';
        for (let m = 0; m < 12; m++) {
            const isT = now.getFullYear()===calYear && now.getMonth()===m;
            const isS = calSelected && calSelected.y===calYear && calSelected.m===m;
            html += `<button type="button" class="adm-cal-mcell${isT?' is-today':''}${isS?' is-selected':''}" onclick="donCalPickMonth(${m})">${MON_ABB[m]}</button>`;
        }
        subGrid.className = 'adm-cal-month-grid'; subGrid.innerHTML = html; subGrid.style.display = '';
    }

    function renderYearView(popup) {
        const label = popup.querySelector('#don-cal-month-label');
        const grid  = popup.querySelector('#don-cal-grid');
        const subGrid = popup.querySelector('#don-cal-sub-grid') || createSubGrid(popup);
        const startYear = Math.floor(calYear / 12) * 12;
        if (label) label.innerHTML = `<span style="font-size:.82rem;font-weight:800;color:#0f172a">${startYear}–${startYear+11}</span>`;
        if (grid) grid.style.display = 'none';
        const now = new Date(); let html = '';
        for (let y = startYear; y < startYear + 12; y++) {
            const isT = now.getFullYear()===y; const isS = calSelected && calSelected.y===y;
            html += `<button type="button" class="adm-cal-ycell${isT?' is-today':''}${isS?' is-selected':''}" onclick="donCalPickYear(${y})">${y}</button>`;
        }
        subGrid.className = 'adm-cal-year-grid'; subGrid.innerHTML = html; subGrid.style.display = '';
    }

    function createSubGrid(popup) {
        const footer = popup.querySelector('.adm-cal-footer');
        const el = document.createElement('div'); el.id = 'don-cal-sub-grid';
        popup.insertBefore(el, footer); return el;
    }

    window.donCalSetView   = function(v) { calView = v; render(); };
    window.donCalPickMonth = function(m) { calMonth = m; calView = 'day'; render(); };
    window.donCalPickYear  = function(y) { calYear = y; calView = 'month'; render(); };

    window.donCalToggle = function() {
        const popup   = document.getElementById('don-cal-popup');
        const trigger = document.getElementById('don-cal-trigger');
        const arrow   = document.getElementById('don-cal-arrow');
        const isOpen  = popup.style.display !== 'none';
        document.querySelectorAll('.adm-csl.is-open').forEach(el => {
            el.classList.remove('is-open');
            el.querySelector('.adm-csl-btn')?.classList.remove('is-open');
        });
        closeAllCalendarPopups('don-cal-popup');
        if (isOpen) {
            popup.style.display = 'none'; trigger.classList.remove('is-open');
            if (arrow) arrow.style.transform = '';
        } else {
            calView = 'day'; render();
            popup.style.display = 'block'; trigger.classList.add('is-open');
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        }
    };

    window.donCalClose = function() {
        const popup=document.getElementById('don-cal-popup'), trigger=document.getElementById('don-cal-trigger'), arrow=document.getElementById('don-cal-arrow');
        if (popup) popup.style.display = 'none';
        if (trigger) trigger.classList.remove('is-open');
        if (arrow) arrow.style.transform = '';
    };

    window.donCalShift = function(dir) {
        if (calView==='day')   { calMonth+=dir; if(calMonth>11){calMonth=0;calYear++;}if(calMonth<0){calMonth=11;calYear--;} }
        else if (calView==='month') { calYear+=dir; }
        else { calYear = Math.floor(calYear/12)*12 + dir*12; }
        render();
    };

    window.donCalPick = function(el) {
        let y=parseInt(el.dataset.y), m=parseInt(el.dataset.m), dd=parseInt(el.dataset.d);
        const nd=new Date(y,m,dd); y=nd.getFullYear(); m=nd.getMonth(); dd=nd.getDate();
        calYear=y; calMonth=m; calSelected={y,m,d:dd};
        const pad=n=>String(n).padStart(2,'0');
        document.getElementById('don-cal-input').value=`${y}-${pad(m+1)}-${pad(dd)}`;
        document.getElementById('don-cal-label').innerHTML=ADM_CAL_ICON+MON_ABB[m]+' '+pad(dd)+', '+y;
        donCalClose();
    };

    window.donCalSelectToday = function() {
        const now=new Date();
        donCalPick({dataset:{y:now.getFullYear(),m:now.getMonth(),d:now.getDate()}});
    };
})();

// Donation filter bar "From"/"To" calendars (optional, clearable)
function makeAdmMiniCalendar(prefix, initVal) {
    const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const MON_ABB = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    let calYear, calMonth, calSelected, calView = 'day';
    if (initVal) {
        const d = new Date(initVal + 'T00:00:00');
        calYear = d.getFullYear(); calMonth = d.getMonth();
        calSelected = { y: calYear, m: calMonth, d: d.getDate() };
    } else {
        const now = new Date(); calYear = now.getFullYear(); calMonth = now.getMonth();
        calSelected = null;
    }

    function render() {
        const popup = document.getElementById(prefix + '-popup');
        if (!popup) return;
        if (calView === 'month') { renderMonthView(popup); return; }
        if (calView === 'year')  { renderYearView(popup);  return; }
        renderDayView(popup);
    }

    function renderDayView(popup) {
        const label = popup.querySelector('#' + prefix + '-month-label');
        const grid  = popup.querySelector('#' + prefix + '-grid');
        const subGrid = popup.querySelector('#' + prefix + '-sub-grid');
        if (label) {
            label.innerHTML = `<button type="button" class="adm-cal-hdr-btn" onclick="${prefix}SetView('month')">${MONTHS[calMonth]}</button><button type="button" class="adm-cal-hdr-btn" onclick="${prefix}SetView('year')">${calYear}</button>`;
        }
        if (subGrid) subGrid.style.display = 'none';
        if (!grid) return;
        grid.style.display = '';

        const firstDay = new Date(calYear, calMonth, 1).getDay();
        const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
        const today = new Date();
        let html = '';
        for (let i = 0; i < firstDay; i++) html += `<span class="adm-cal-day" style="visibility:hidden;pointer-events:none;"></span>`;
        for (let dd = 1; dd <= daysInMonth; dd++) {
            let cls = 'adm-cal-day';
            if (today.getFullYear()===calYear && today.getMonth()===calMonth && today.getDate()===dd) cls += ' is-today';
            if (calSelected && calSelected.y===calYear && calSelected.m===calMonth && calSelected.d===dd) cls += ' is-selected';
            html += `<button type="button" class="${cls}" data-y="${calYear}" data-m="${calMonth}" data-d="${dd}" onclick="${prefix}Pick(this)">${dd}</button>`;
        }
        const total = firstDay + daysInMonth;
        const nextCells = total % 7 === 0 ? 0 : 7 - (total % 7);
        for (let i = 0; i < nextCells; i++) html += `<span class="adm-cal-day" style="visibility:hidden;pointer-events:none;"></span>`;
        grid.innerHTML = html;
    }

    function renderMonthView(popup) {
        const label = popup.querySelector('#' + prefix + '-month-label');
        const grid  = popup.querySelector('#' + prefix + '-grid');
        const subGrid = popup.querySelector('#' + prefix + '-sub-grid') || createSubGrid(popup);
        if (label) label.innerHTML = `<button type="button" class="adm-cal-hdr-btn" onclick="${prefix}SetView('year')">${calYear}</button>`;
        if (grid) grid.style.display = 'none';
        const now = new Date(); let html = '';
        for (let m = 0; m < 12; m++) {
            const isT = now.getFullYear()===calYear && now.getMonth()===m;
            const isS = calSelected && calSelected.y===calYear && calSelected.m===m;
            html += `<button type="button" class="adm-cal-mcell${isT?' is-today':''}${isS?' is-selected':''}" onclick="${prefix}PickMonth(${m})">${MON_ABB[m]}</button>`;
        }
        subGrid.className = 'adm-cal-month-grid'; subGrid.innerHTML = html; subGrid.style.display = '';
    }

    function renderYearView(popup) {
        const label = popup.querySelector('#' + prefix + '-month-label');
        const grid  = popup.querySelector('#' + prefix + '-grid');
        const subGrid = popup.querySelector('#' + prefix + '-sub-grid') || createSubGrid(popup);
        const startYear = Math.floor(calYear / 12) * 12;
        if (label) label.innerHTML = `<span style="font-size:.82rem;font-weight:800;color:#0f172a">${startYear}–${startYear+11}</span>`;
        if (grid) grid.style.display = 'none';
        const now = new Date(); let html = '';
        for (let y = startYear; y < startYear + 12; y++) {
            const isT = now.getFullYear()===y; const isS = calSelected && calSelected.y===y;
            html += `<button type="button" class="adm-cal-ycell${isT?' is-today':''}${isS?' is-selected':''}" onclick="${prefix}PickYear(${y})">${y}</button>`;
        }
        subGrid.className = 'adm-cal-year-grid'; subGrid.innerHTML = html; subGrid.style.display = '';
    }

    function createSubGrid(popup) {
        const footer = popup.querySelector('.adm-cal-footer');
        const el = document.createElement('div'); el.id = prefix + '-sub-grid';
        popup.insertBefore(el, footer); return el;
    }

    window[prefix + 'SetView']   = function(v) { calView = v; render(); };
    window[prefix + 'PickMonth'] = function(m) { calMonth = m; calView = 'day'; render(); };
    window[prefix + 'PickYear']  = function(y) { calYear = y; calView = 'month'; render(); };

    window[prefix + 'Toggle'] = function() {
        const popup   = document.getElementById(prefix + '-popup');
        const trigger = document.getElementById(prefix + '-trigger');
        const arrow   = document.getElementById(prefix + '-arrow');
        const isOpen  = popup.style.display !== 'none';
        document.querySelectorAll('.adm-csl.is-open').forEach(el => {
            el.classList.remove('is-open');
            el.querySelector('.adm-csl-btn')?.classList.remove('is-open');
        });
        closeAllCalendarPopups(prefix + '-popup');
        if (isOpen) {
            popup.style.display = 'none'; trigger.classList.remove('is-open');
            if (arrow) arrow.style.transform = '';
        } else {
            calView = 'day'; render();
            popup.style.display = 'block'; trigger.classList.add('is-open');
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        }
    };

    window[prefix + 'Close'] = function() {
        const popup=document.getElementById(prefix + '-popup'), trigger=document.getElementById(prefix + '-trigger'), arrow=document.getElementById(prefix + '-arrow');
        if (popup) popup.style.display = 'none';
        if (trigger) trigger.classList.remove('is-open');
        if (arrow) arrow.style.transform = '';
    };

    window[prefix + 'Shift'] = function(dir) {
        if (calView==='day')   { calMonth+=dir; if(calMonth>11){calMonth=0;calYear++;}if(calMonth<0){calMonth=11;calYear--;} }
        else if (calView==='month') { calYear+=dir; }
        else { calYear = Math.floor(calYear/12)*12 + dir*12; }
        render();
    };

    window[prefix + 'Pick'] = function(el) {
        let y=parseInt(el.dataset.y), m=parseInt(el.dataset.m), dd=parseInt(el.dataset.d);
        const nd=new Date(y,m,dd); y=nd.getFullYear(); m=nd.getMonth(); dd=nd.getDate();
        calYear=y; calMonth=m; calSelected={y,m,d:dd};
        const pad=n=>String(n).padStart(2,'0');
        document.getElementById(prefix + '-input').value=`${y}-${pad(m+1)}-${pad(dd)}`;
        document.getElementById(prefix + '-label').innerHTML=ADM_CAL_ICON+MON_ABB[m]+' '+pad(dd)+', '+y;
        window[prefix + 'Close']();
    };

    window[prefix + 'SelectToday'] = function() {
        const now=new Date();
        window[prefix + 'Pick']({dataset:{y:now.getFullYear(),m:now.getMonth(),d:now.getDate()}});
    };

    window[prefix + 'Clear'] = function() {
        calSelected=null;
        document.getElementById(prefix + '-input').value='';
        document.getElementById(prefix + '-label').innerHTML=ADM_CAL_ICON+'Pick a date';
        window[prefix + 'Close']();
    };
}
makeAdmMiniCalendar('donFromCal', {!! $donationFrom ? "'" . $donationFrom . "'" : 'null' !!});
makeAdmMiniCalendar('donToCal', {!! $donationTo ? "'" . $donationTo . "'" : 'null' !!});

// On load: hide date picker if not needed
(function() {
    const rangeInput = document.getElementById('reservation_filter_range');
    const wrap = document.querySelector('[data-reservation-filter-date-wrapper]');
    if (wrap && rangeInput && rangeInput.value !== 'date') wrap.classList.add('is-hidden');
})();
</script>

{{-- ==================== CUSTOMERS ==================== --}}
@if($section === 'customers')
@php
    $allCustomers   = $customers ?? [];
    $activeCount    = count(array_filter($allCustomers, fn($c) => ($c['status'] ?? 'active') === 'active'));
    $disabledCount  = count($allCustomers) - $activeCount;
    $pendingResCount = array_sum(array_map(fn($c) => (int)($c['pending_count'] ?? 0), $allCustomers));
@endphp
    <section class="section-card">
        <div class="section-header">
            <div><h2>Manage Customers</h2><p>Review accounts, reservation activity, and account status.</p></div>
            <div class="schedule-view-toggle" id="customerViewToggle">
                <button type="button" class="schedule-view-toggle-btn is-active" id="customerViewCardBtn" data-view="card">
                    <i class="fa fa-th-large"></i> Card
                </button>
                <button type="button" class="schedule-view-toggle-btn" id="customerViewTableBtn" data-view="table">
                    <i class="fa fa-list"></i> Table
                </button>
            </div>
        </div>

        {{-- Stats bar --}}
        <div class="cust-stats-bar">
            <div class="cust-stat">
                <span class="cust-stat-num">{{ count($allCustomers) }}</span>
                <span class="cust-stat-label">Total</span>
            </div>
            <div class="cust-stat-divider"></div>
            <div class="cust-stat">
                <span class="cust-stat-num active">{{ $activeCount }}</span>
                <span class="cust-stat-label">Active</span>
            </div>
            <div class="cust-stat-divider"></div>
            <div class="cust-stat">
                <span class="cust-stat-num disabled">{{ $disabledCount }}</span>
                <span class="cust-stat-label">Disabled</span>
            </div>
            <div class="cust-stat-divider"></div>
            <div class="cust-stat">
                <span class="cust-stat-num {{ $pendingResCount == 0 ? '' : 'pending' }}">{{ $pendingResCount }}</span>
                <span class="cust-stat-label">Pending Reservations</span>
            </div>
        </div>

        {{-- Search bar --}}
        <form method="GET" action="{{ route('admin.index') }}">
            <input type="hidden" name="section" value="customers">
            <div class="cust-search-bar">
                <div class="cust-search-input-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" id="customerLiveSearch" class="cust-search-input"
                           name="customer_search" value="{{ $customerSearch ?? '' }}"
                           placeholder="Search by name, email or phone…">
                </div>
                <div class="cust-filter-pills">
                    <a href="{{ route('admin.index', ['section'=>'customers','customer_status'=>'all','customer_search'=>$customerSearch ?? '']) }}"
                       class="cust-filter-pill {{ ($customerStatus ?? 'all') === 'all' ? 'active-pill' : '' }}">All</a>
                    <a href="{{ route('admin.index', ['section'=>'customers','customer_status'=>'active','customer_search'=>$customerSearch ?? '']) }}"
                       class="cust-filter-pill {{ ($customerStatus ?? 'all') === 'active' ? 'active-pill' : '' }}">Active</a>
                    <a href="{{ route('admin.index', ['section'=>'customers','customer_status'=>'disabled','customer_search'=>$customerSearch ?? '']) }}"
                       class="cust-filter-pill {{ ($customerStatus ?? 'all') === 'disabled' ? 'active-pill' : '' }}">Disabled</a>
                </div>
                <button type="submit" class="cust-filter-pill" style="border-color:#dc2626;color:#dc2626;">
                    <i class="fa fa-search"></i> Search
                </button>
                <div class="adm-csl" id="adm-csl-customer-sort" style="min-width:200px;">
                    <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-customer-sort')">
                        <span class="adm-csl-label">Sort: Name A–Z</span>
                        <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <ul class="adm-csl-list">
                        <li class="is-selected" onclick="customerSortPick(this,'name-asc','Name A–Z')">Sort: Name A–Z</li>
                        <li onclick="customerSortPick(this,'name-desc','Name Z–A')">Sort: Name Z–A</li>
                        <li onclick="customerSortPick(this,'count-desc','Most reservations')">Sort: Most reservations</li>
                        <li onclick="customerSortPick(this,'count-asc','Fewest reservations')">Sort: Fewest reservations</li>
                    </ul>
                </div>
                <span class="cust-result-count">{{ count($allCustomers) }} result{{ count($allCustomers) !== 1 ? 's' : '' }}</span>
            </div>
        </form>

        @if(empty($allCustomers))
            <p class="empty-block">No customers found.</p>
        @else
            <div class="cust-grid" id="customerCardView">
                @foreach($allCustomers as $c)
                @php
                    $cStatus  = ($c['status'] ?? 'active') === 'active' ? 'active' : 'disabled';
                    $cInitial = strtoupper(substr($c['name'] ?? 'C', 0, 1));
                    $cRes     = $c['total_reservations'] ?? 0;
                @endphp
                <div class="cust-card customer-search-item {{ $cStatus === 'disabled' ? 'cust-card-disabled' : '' }}"
                     data-customer-search="{{ strtolower(($c['name'] ?? '') . ' ' . ($c['email'] ?? '') . ' ' . ($c['phone'] ?? '')) }}"
                     data-sort-name="{{ strtolower($c['name'] ?? '') }}" data-sort-count="{{ $cRes }}">

                    <div class="cust-card-top">
                        <div class="cust-avatar {{ $cStatus }}">{{ $cInitial }}</div>
                        <div class="cust-info">
                            <div class="cust-name">{{ $c['name'] ?? 'No name' }}</div>
                            <div class="cust-email">{{ $c['email'] ?? 'No email' }}</div>
                        </div>
                        <span class="cust-status-badge {{ $cStatus }}">
                            <i class="fa {{ $cStatus === 'active' ? 'fa-check-circle' : 'fa-ban' }}"></i>
                            {{ ucfirst($cStatus) }}
                        </span>
                    </div>

                    <div class="cust-meta">
                        @if(!empty($c['phone']))
                        <span class="cust-meta-item"><i class="fa fa-phone"></i> {{ $c['phone'] }}</span>
                        @endif
                        <span class="cust-res-count"><i class="fa fa-calendar-check-o"></i> {{ $cRes }} reservation{{ $cRes !== 1 ? 's' : '' }}</span>
                        @if(($c['pending_count'] ?? 0) > 0)
                        <span class="cust-pending-chip"><span class="cust-pending-dot"></span>{{ $c['pending_count'] }} pending</span>
                        @endif
                    </div>

                    <div class="cust-footer">
                        <button type="button" class="cust-btn view" onclick="openProfileModal({{ $c['id'] }})">
                            <i class="fa fa-user"></i> Profile
                        </button>

                        @if($cStatus === 'active')
                            <button type="button" class="cust-btn disable" onclick="openDisableModal({{ $c['id'] }})">
                                <i class="fa fa-ban"></i> Disable
                            </button>
                        @else
                            <form method="POST" action="{{ route('admin.handle') }}">
                                @csrf
                                <input type="hidden" name="action" value="toggle_customer_status">
                                <input type="hidden" name="customer_id" value="{{ $c['id'] }}">
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="cust-btn enable"><i class="fa fa-check"></i> Enable</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.handle') }}">
                            @csrf
                            <input type="hidden" name="action" value="reset_password">
                            <input type="hidden" name="customer_id" value="{{ $c['id'] }}">
                            <button type="submit" class="cust-btn reset"><i class="fa fa-key"></i> Reset</button>
                        </form>
                    </div>
                </div>

                @endforeach
            </div>

            <div class="cust-table-wrap" id="customerTableView" style="display:none;">
                <table class="cust-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Reservations</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customerTableBody">
                        @foreach($allCustomers as $c)
                        @php
                            $cStatus  = ($c['status'] ?? 'active') === 'active' ? 'active' : 'disabled';
                            $cInitial = strtoupper(substr($c['name'] ?? 'C', 0, 1));
                            $cRes     = $c['total_reservations'] ?? 0;
                        @endphp
                        <tr class="customer-search-item {{ $cStatus === 'disabled' ? 'cust-card-disabled' : '' }}"
                            data-customer-search="{{ strtolower(($c['name'] ?? '') . ' ' . ($c['email'] ?? '') . ' ' . ($c['phone'] ?? '')) }}"
                            data-sort-name="{{ strtolower($c['name'] ?? '') }}" data-sort-count="{{ $cRes }}">
                            <td>
                                <div class="cust-table-customer">
                                    <div class="cust-avatar {{ $cStatus }}" style="width:38px;height:38px;font-size:15px;">{{ $cInitial }}</div>
                                    <div style="min-width:0;">
                                        <div class="cust-table-name">{{ $c['name'] ?? 'No name' }}</div>
                                        <div class="cust-table-email">{{ $c['email'] ?? 'No email' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="cust-table-contact">{{ $c['phone'] ?? '—' }}</td>
                            <td>
                                <span class="cust-status-badge {{ $cStatus }}">
                                    <i class="fa {{ $cStatus === 'active' ? 'fa-check-circle' : 'fa-ban' }}"></i>
                                    {{ ucfirst($cStatus) }}
                                </span>
                            </td>
                            <td>
                                @if($cRes > 0)
                                <div class="cust-table-breakdown">
                                    <span class="cust-table-total">{{ $cRes }}</span>
                                    @if(($c['approved_count'] ?? 0) > 0)<span class="cust-bd approved"><span class="cust-bd-dot"></span>{{ $c['approved_count'] }}</span>@endif
                                    @if(($c['pending_count'] ?? 0) > 0)<span class="cust-bd pending"><span class="cust-bd-dot"></span>{{ $c['pending_count'] }}</span>@endif
                                    @if(($c['declined_count'] ?? 0) > 0)<span class="cust-bd declined"><span class="cust-bd-dot"></span>{{ $c['declined_count'] }}</span>@endif
                                </div>
                                @else
                                <span class="cust-table-empty">No reservations yet</span>
                                @endif
                            </td>
                            <td>
                                <div class="cust-table-actions">
                                    <button type="button" class="cust-icon-btn view" title="Profile" onclick="openProfileModal({{ $c['id'] }})">
                                        <i class="fa fa-user"></i>
                                    </button>
                                    @if($cStatus === 'active')
                                        <button type="button" class="cust-icon-btn disable" title="Disable" onclick="openDisableModal({{ $c['id'] }})">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('admin.handle') }}" style="display:contents;">
                                            @csrf
                                            <input type="hidden" name="action" value="toggle_customer_status">
                                            <input type="hidden" name="customer_id" value="{{ $c['id'] }}">
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="cust-icon-btn enable" title="Enable"><i class="fa fa-check"></i></button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.handle') }}" style="display:contents;">
                                        @csrf
                                        <input type="hidden" name="action" value="reset_password">
                                        <input type="hidden" name="customer_id" value="{{ $c['id'] }}">
                                        <button type="submit" class="cust-icon-btn reset" title="Reset password"><i class="fa fa-key"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Customer modals — kept outside both view containers so toggling Card/Table never hides an open modal --}}
            @foreach($allCustomers as $c)
            @php
                $cStatus  = ($c['status'] ?? 'active') === 'active' ? 'active' : 'disabled';
                $cInitial = strtoupper(substr($c['name'] ?? 'C', 0, 1));
                $cRes     = $c['total_reservations'] ?? 0;
            @endphp

            {{-- Disable modal --}}
            <div class="dcm-overlay" id="disable-overlay-{{ $c['id'] }}" onclick="if(event.target===this) closeDisableModal({{ $c['id'] }})">
                <div class="dcm-modal">
                    <div class="adm-cfm-hdr">
                        <svg class="adm-cfm-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                        <span class="adm-cfm-parish">St. John the Baptist Parish</span>
                        <span class="adm-cfm-dot"></span>
                        <span class="adm-cfm-loc">Tiaong, Quezon</span>
                    </div>
                    <div class="adm-cfm-body dcm-scroll">
                        <div class="adm-cfm-glow"></div>
                        <div class="adm-cfm-icon">
                            <div class="adm-cfm-ring"></div>
                            <div class="adm-cfm-inner">
                                <svg width="38" height="38" viewBox="0 0 38 38" fill="none">
                                    <line class="adm-cfm-chk" x1="12" y1="12" x2="26" y2="26"/>
                                    <line class="adm-cfm-chk" x1="26" y1="12" x2="12" y2="26"/>
                                </svg>
                            </div>
                        </div>
                        <div class="adm-cfm-title">Disable Customer</div>
                        <p class="adm-cfm-sub">This will disable the account and notify the customer by email.</p>
                        <div class="ps-chip"><span class="ps-chip-dot"></span>{{ $c['name'] ?? 'Customer' }}</div>
                        <form method="POST" action="{{ route('admin.handle') }}" style="width:100%;text-align:left">
                            @csrf
                            <input type="hidden" name="action" value="toggle_customer_status">
                            <input type="hidden" name="customer_id" value="{{ $c['id'] }}">
                            <input type="hidden" name="status" value="disabled">
                            <label class="dcm-label">Reason for disabling</label>
                            <textarea name="disabled_reason" class="dcm-textarea" rows="3"
                                      placeholder="e.g. Incomplete documents, suspicious activity…" required></textarea>
                            <div class="adm-cfm-btns">
                                <button type="button" class="adm-cfm-cancel" onclick="closeDisableModal({{ $c['id'] }})">Cancel</button>
                                <button type="submit" class="adm-cfm-ok"><i class="fa fa-ban"></i> Disable Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Profile modal --}}
            <div class="dcm-overlay" id="profile-overlay-{{ $c['id'] }}" onclick="if(event.target===this) closeProfileModal({{ $c['id'] }})">
                <div class="dcm-modal dcm-modal-lg">
                    <div class="ps-hdr">
                        <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                        <span class="ps-parish">St. John the Baptist Parish</span>
                        <span class="ps-dot"></span>
                        <span class="ps-loc">Tiaong, Quezon</span>
                    </div>
                    <button type="button" class="dcm-close-btn" onclick="closeProfileModal({{ $c['id'] }})">&times;</button>

                    <div class="dcm-scroll">
                        <div class="cust-modal-header">
                            <div class="cust-modal-avatar-row">
                                <div class="cust-modal-avatar {{ $cStatus }}">{{ $cInitial }}</div>
                                <div>
                                    <p class="cust-modal-name">{{ $c['name'] ?? 'No name' }}</p>
                                    <p class="cust-modal-sub">
                                        <span class="cust-status-badge {{ $cStatus }}" style="font-size:11px;">
                                            <i class="fa {{ $cStatus === 'active' ? 'fa-check-circle' : 'fa-ban' }}"></i>
                                            {{ ucfirst($cStatus) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="cust-modal-meta">
                            <span class="cust-modal-meta-item"><i class="fa fa-envelope"></i> {{ $c['email'] ?? 'No email' }}</span>
                            <span class="cust-modal-meta-item"><i class="fa fa-phone"></i> {{ $c['phone'] ?? 'No phone' }}</span>
                            @if(!empty($c['last_password_reset_at']))
                            <span class="cust-modal-meta-item"><i class="fa fa-key"></i> Reset: {{ adminFormatCreatedAt($c['last_password_reset_at']) }}</span>
                            @endif
                        </div>

                        @if($cStatus === 'disabled' && !empty($c['disabled_reason']))
                        <div class="cust-modal-disabled-note">
                            <i class="fa fa-ban"></i> <strong>Disabled:</strong> {{ $c['disabled_reason'] }}
                        </div>
                        @endif

                        <div class="cust-modal-stat-row">
                            <div class="cust-modal-stat total">
                                <div class="cust-modal-stat-num">{{ $cRes }}</div>
                                <div class="cust-modal-stat-label">Total</div>
                            </div>
                            <div class="cust-modal-stat approved">
                                <div class="cust-modal-stat-num">{{ $c['approved_count'] ?? 0 }}</div>
                                <div class="cust-modal-stat-label">Approved</div>
                            </div>
                            <div class="cust-modal-stat pending">
                                <div class="cust-modal-stat-num">{{ $c['pending_count'] ?? 0 }}</div>
                                <div class="cust-modal-stat-label">Pending</div>
                            </div>
                            <div class="cust-modal-stat declined">
                                <div class="cust-modal-stat-num">{{ $c['declined_count'] ?? 0 }}</div>
                                <div class="cust-modal-stat-label">Declined</div>
                            </div>
                        </div>

                        <div class="cust-modal-history">
                            <h6>Reservation History</h6>
                            @if(empty($c['reservations']))
                                <p class="empty-block" style="margin:0;">No reservations yet.</p>
                            @else
                                @foreach($c['reservations'] as $r)
                                @php $ret = strtolower($r['event_type'] ?? ''); $reColor = match($ret){ 'wedding'=>'#f472b6','baptism'=>'#2dd4bf','funeral'=>'#64748b',default=>'#94a3b8' }; @endphp
                                <div class="cust-history-row">
                                    <span class="cust-history-id">#{{ $r['id'] }}</span>
                                    <span class="cust-history-evtype" style="background:{{ $reColor }}18;color:{{ $reColor }};border:1px solid {{ $reColor }}40;">
                                        {{ ucfirst($ret ?: 'Unknown') }}
                                    </span>
                                    {!! adminStatusBadge($r['status']) !!}
                                    <span class="cust-history-date">
                                        <i class="fa fa-calendar-o"></i> {{ adminFormatDate($r['reservation_date']) }}
                                    </span>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </section>
@endif

{{-- ==================== DONATIONS ==================== --}}
@if($section === 'donations')
@php
$purposeLabels = [
    'building_fund'  => 'Building Fund',
    'mass_intention' => 'Mass Intention',
    'tithes'         => 'Tithes',
    'other'          => 'Other',
];
$purposeColors = [
    'building_fund'  => '#92400e',
    'mass_intention' => '#b91c1c',
    'tithes'         => '#ca8a04',
    'other'          => '#94a3b8',
];
@endphp

<div id="donScreenView">
{{-- Summary cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:24px">
    <div class="section-card" style="margin:0;padding:20px 22px">
        <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px">Total Collected</div>
        <div style="font-size:26px;font-weight:800;color:#dc2626">₱{{ number_format($donationSectionTotal, 2) }}</div>
        <div style="font-size:12px;color:#94a3b8;margin-top:4px">{{ count($donationsList) }} donation{{ count($donationsList) !== 1 ? 's' : '' }}</div>
    </div>
    @foreach($donationsByPurpose as $pk => $pv)
    <div class="section-card" style="margin:0;padding:20px 22px">
        <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px">{{ $purposeLabels[$pk] ?? $pk }}</div>
        <div style="font-size:22px;font-weight:800;color:{{ $purposeColors[$pk] ?? '#64748b' }}">₱{{ number_format($pv, 2) }}</div>
    </div>
    @endforeach
</div>

{{-- Record Donation button + form --}}
<section class="section-card" style="margin-bottom:24px">
    <div class="section-header">
        <div><h2>Record a Donation</h2><p>Fill in the details for each in-person donation received.</p></div>
    </div>
    <form method="POST" action="{{ route('admin.donations.store') }}"
          style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;align-items:end">
        @csrf
        <div style="display:flex;flex-direction:column;gap:5px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Donor Name</label>
            <input type="text" name="donor_name" required maxlength="255" value="{{ old('donor_name') }}"
                   style="padding:9px 13px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.22);font-size:14px;outline:none;width:100%;box-sizing:border-box"
                   placeholder="Full name">
        </div>
        <div style="display:flex;flex-direction:column;gap:5px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Amount (₱)</label>
            <input type="number" name="amount" step="0.01" min="0.01" required value="{{ old('amount') }}"
                   style="padding:9px 13px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.22);font-size:14px;outline:none;width:100%;box-sizing:border-box"
                   placeholder="0.00">
        </div>
        <div style="display:flex;flex-direction:column;gap:5px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Purpose</label>
            @php $oldPurpose = old('purpose', 'building_fund'); $purposeLabelMap = ['building_fund'=>'Building Fund','mass_intention'=>'Mass Intention','tithes'=>'Tithes','other'=>'Other']; @endphp
            <input type="hidden" name="purpose" id="purposeSelect" value="{{ $oldPurpose }}">
            <div class="adm-csl" id="adm-csl-donation-purpose" style="width:100%;">
                <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-donation-purpose')">
                    <span class="adm-csl-label">{{ $purposeLabelMap[$oldPurpose] ?? 'Building Fund' }}</span>
                    <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="adm-csl-list">
                    <li class="{{ $oldPurpose==='building_fund'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-donation-purpose','purposeSelect','building_fund','Building Fund');toggleCustomPurpose('building_fund')">Building Fund</li>
                    <li class="{{ $oldPurpose==='mass_intention'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-donation-purpose','purposeSelect','mass_intention','Mass Intention');toggleCustomPurpose('mass_intention')">Mass Intention</li>
                    <li class="{{ $oldPurpose==='tithes'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-donation-purpose','purposeSelect','tithes','Tithes');toggleCustomPurpose('tithes')">Tithes</li>
                    <li class="{{ $oldPurpose==='other'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-donation-purpose','purposeSelect','other','Other');toggleCustomPurpose('other')">Other</li>
                </ul>
            </div>
            <input type="text" name="custom_purpose" id="customPurposeInput"
                   value="{{ old('custom_purpose') }}"
                   placeholder="Specify purpose..."
                   style="display:{{ old('purpose')==='other' ? 'block' : 'none' }};margin-top:6px;padding:9px 13px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.35);font-size:14px;width:100%;box-sizing:border-box;outline:none">
        </div>
        <div style="display:flex;flex-direction:column;gap:5px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Date Received</label>
            <input type="hidden" name="donation_date" id="don-cal-input" value="{{ old('donation_date', date('Y-m-d')) }}">
            <div class="adm-cal-wrap" id="don-cal-wrap">
                <button type="button" class="adm-csl-btn" id="don-cal-trigger" onclick="donCalToggle()" style="width:100%;">
                    <span class="adm-csl-label" id="don-cal-label"><i class="fa fa-calendar" style="color:#94a3b8;margin-right:7px;"></i>{{ \Carbon\Carbon::parse(old('donation_date', date('Y-m-d')))->format('M d, Y') }}</span>
                    <svg class="adm-csl-arrow" id="don-cal-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="adm-cal-popup" id="don-cal-popup" style="display:none;">
                    <div class="adm-cal-header">
                        <button type="button" class="adm-cal-nav" onclick="donCalShift(-1)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <span class="adm-cal-month-label" id="don-cal-month-label"></span>
                        <button type="button" class="adm-cal-nav" onclick="donCalShift(1)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                    <div class="adm-cal-days-row">
                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                    </div>
                    <div class="adm-cal-grid" id="don-cal-grid"></div>
                    <div class="adm-cal-footer">
                        <button type="button" class="adm-cal-today-btn" onclick="donCalSelectToday()">Today</button>
                    </div>
                </div>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:5px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Notes (optional)</label>
            <input type="text" name="notes" maxlength="500" value="{{ old('notes') }}"
                   style="padding:9px 13px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.22);font-size:14px;width:100%;box-sizing:border-box"
                   placeholder="e.g. In memory of Juan dela Cruz">
        </div>
        <div>
            <button type="submit"
                    style="width:100%;padding:10px 0;border-radius:10px;border:none;background:#dc2626;color:#fff;font-size:14px;font-weight:700;cursor:pointer">
                Save &amp; Generate Receipt
            </button>
        </div>
    </form>
</section>

{{-- Filter bar --}}
<section class="section-card" style="margin-bottom:24px">
    <form method="GET" action="{{ route('admin.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
        <input type="hidden" name="section" value="donations">
        <div style="display:flex;flex-direction:column;gap:4px;flex:1;min-width:160px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Search Donor</label>
            <input type="text" name="donation_search" value="{{ $donationSearch }}"
                   style="padding:8px 13px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.22);font-size:14px"
                   placeholder="Name...">
        </div>
        <div style="display:flex;flex-direction:column;gap:4px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Purpose</label>
            @php $purposeFilterLabelMap = ['all'=>'All purposes','building_fund'=>'Building Fund','mass_intention'=>'Mass Intention','tithes'=>'Tithes','other'=>'Other']; @endphp
            <input type="hidden" name="donation_purpose" id="donation_purpose_filter" value="{{ $donationPurpose }}">
            <div class="adm-csl" id="adm-csl-donation-purpose-filter" style="min-width:170px;">
                <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-donation-purpose-filter')">
                    <span class="adm-csl-label">{{ $purposeFilterLabelMap[$donationPurpose] ?? 'All purposes' }}</span>
                    <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="adm-csl-list">
                    <li class="{{ $donationPurpose==='all'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-donation-purpose-filter','donation_purpose_filter','all','All purposes')">All purposes</li>
                    <li class="{{ $donationPurpose==='building_fund'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-donation-purpose-filter','donation_purpose_filter','building_fund','Building Fund')">Building Fund</li>
                    <li class="{{ $donationPurpose==='mass_intention'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-donation-purpose-filter','donation_purpose_filter','mass_intention','Mass Intention')">Mass Intention</li>
                    <li class="{{ $donationPurpose==='tithes'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-donation-purpose-filter','donation_purpose_filter','tithes','Tithes')">Tithes</li>
                    <li class="{{ $donationPurpose==='other'?'is-selected':'' }}" onclick="admCslSelect('adm-csl-donation-purpose-filter','donation_purpose_filter','other','Other')">Other</li>
                </ul>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">From</label>
            <input type="hidden" name="donation_from" id="donFromCal-input" value="{{ $donationFrom }}">
            <div class="adm-cal-wrap" id="donFromCal-wrap">
                <button type="button" class="adm-csl-btn" id="donFromCal-trigger" onclick="donFromCalToggle()">
                    <span class="adm-csl-label" id="donFromCal-label"><i class="fa fa-calendar" style="color:#94a3b8;margin-right:7px;"></i>{{ $donationFrom ? \Carbon\Carbon::parse($donationFrom)->format('M d, Y') : 'Pick a date' }}</span>
                    <svg class="adm-csl-arrow" id="donFromCal-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="adm-cal-popup" id="donFromCal-popup" style="display:none;">
                    <div class="adm-cal-header">
                        <button type="button" class="adm-cal-nav" onclick="donFromCalShift(-1)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <span class="adm-cal-month-label" id="donFromCal-month-label"></span>
                        <button type="button" class="adm-cal-nav" onclick="donFromCalShift(1)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                    <div class="adm-cal-days-row">
                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                    </div>
                    <div class="adm-cal-grid" id="donFromCal-grid"></div>
                    <div class="adm-cal-footer">
                        <button type="button" class="adm-cal-today-btn" onclick="donFromCalSelectToday()">Today</button>
                        <button type="button" class="adm-cal-clear-btn" onclick="donFromCalClear()">Clear</button>
                    </div>
                </div>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">To</label>
            <input type="hidden" name="donation_to" id="donToCal-input" value="{{ $donationTo }}">
            <div class="adm-cal-wrap" id="donToCal-wrap">
                <button type="button" class="adm-csl-btn" id="donToCal-trigger" onclick="donToCalToggle()">
                    <span class="adm-csl-label" id="donToCal-label"><i class="fa fa-calendar" style="color:#94a3b8;margin-right:7px;"></i>{{ $donationTo ? \Carbon\Carbon::parse($donationTo)->format('M d, Y') : 'Pick a date' }}</span>
                    <svg class="adm-csl-arrow" id="donToCal-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="adm-cal-popup" id="donToCal-popup" style="display:none;">
                    <div class="adm-cal-header">
                        <button type="button" class="adm-cal-nav" onclick="donToCalShift(-1)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <span class="adm-cal-month-label" id="donToCal-month-label"></span>
                        <button type="button" class="adm-cal-nav" onclick="donToCalShift(1)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                    <div class="adm-cal-days-row">
                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                    </div>
                    <div class="adm-cal-grid" id="donToCal-grid"></div>
                    <div class="adm-cal-footer">
                        <button type="button" class="adm-cal-today-btn" onclick="donToCalSelectToday()">Today</button>
                        <button type="button" class="adm-cal-clear-btn" onclick="donToCalClear()">Clear</button>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" style="padding:9px 20px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.3);background:#fff;color:#dc2626;font-size:13px;font-weight:700;cursor:pointer">Filter</button>
        @if($donationSearch || $donationPurpose !== 'all' || $donationFrom || $donationTo)
        <a href="{{ route('admin.index', ['section'=>'donations']) }}"
           style="padding:9px 16px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;color:#94a3b8;font-size:13px;font-weight:600;text-decoration:none">Clear</a>
        @endif
    </form>
</section>

{{-- Donations Table --}}
<section class="section-card" style="margin-bottom:24px">
    <div class="section-header">
        <div><h2>Donation Records</h2><p>{{ count($donationsList) }} record{{ count($donationsList) !== 1 ? 's' : '' }} found</p></div>
        <button onclick="window.print()" style="padding:8px 18px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.3);background:#fff;color:#dc2626;font-size:13px;font-weight:700;cursor:pointer">
            <i class="fa fa-print"></i> Print List
        </button>
    </div>

    @if(count($donationsList) === 0)
        <div style="text-align:center;padding:40px;color:#94a3b8;font-size:14px">
            <i class="fa fa-money" style="font-size:32px;display:block;margin-bottom:12px;opacity:.4"></i>
            No donation records found.
        </div>
    @else
    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;font-size:14px">
            <thead>
                <tr style="background:#f8fafc">
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap">Receipt #</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Donor Name</th>
                    <th style="padding:10px 14px;text-align:right;font-size:11px;font-weight:700;color:#dc2626;text-transform:uppercase;letter-spacing:.06em">Amount</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Purpose</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Date</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Notes</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donationsList as $don)
                @php $donPurpose = $don['purpose'] ?? 'other'; @endphp
                <tr style="border-top:1px solid #f1f5f9">
                    <td style="padding:10px 14px;font-family:monospace;font-size:12px;color:#64748b;white-space:nowrap">{{ $don['receipt_number'] ?? '—' }}</td>
                    <td style="padding:10px 14px;font-weight:600;color:#1f2937">{{ $don['donor_name'] ?? '—' }}</td>
                    <td style="padding:10px 14px;text-align:right;font-weight:700;color:#dc2626;white-space:nowrap">₱{{ number_format((float)($don['amount'] ?? 0), 2) }}</td>
                    <td style="padding:10px 14px">
                        <span style="display:inline-block;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;
                              background:{{ ($purposeColors[$donPurpose] ?? '#94a3b8') }}20;
                              color:{{ $purposeColors[$donPurpose] ?? '#64748b' }}">
                            {{ $donPurpose === 'other' && !empty($don['custom_purpose']) ? $don['custom_purpose'] : ($purposeLabels[$donPurpose] ?? ucfirst($donPurpose)) }}
                        </span>
                    </td>
                    <td style="padding:10px 14px;color:#64748b;white-space:nowrap">{{ \Carbon\Carbon::parse($don['donation_date'])->format('M j, Y') }}</td>
                    <td style="padding:10px 14px;color:#94a3b8;font-size:13px;max-width:200px">{{ $don['notes'] ?? '—' }}</td>
                    <td style="padding:10px 14px;text-align:center;white-space:nowrap">
                        <button onclick="printReceipt({{ json_encode($don) }})"
                                style="padding:5px 12px;border-radius:8px;border:1.5px solid rgba(220,38,38,0.3);background:#fff;color:#dc2626;font-size:12px;font-weight:600;cursor:pointer;margin-right:6px">
                            <i class="fa fa-print"></i> Receipt
                        </button>
                        <form method="POST" action="{{ route('admin.donations.delete', $don['id']) }}" style="display:inline"
                              onsubmit="return confirm('Delete this donation record?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="padding:5px 10px;border-radius:8px;border:1.5px solid rgba(239,68,68,0.3);background:#fff;color:#ef4444;font-size:12px;cursor:pointer">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="border-top:2px solid #e2e8f0;background:#f8fafc">
                    <td colspan="2" style="padding:10px 14px;font-weight:700;color:#374151">Total</td>
                    <td style="padding:10px 14px;text-align:right;font-weight:800;color:#dc2626;font-size:16px">₱{{ number_format($donationSectionTotal, 2) }}</td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</section>
</div>

{{-- ── Print-only donation records document ── --}}
<div id="donPrintDoc">
    <div class="rpd-letterhead">
        <div class="rpd-letterhead-left">
            <img src="{{ asset('img/about/about_1.jpg') }}" alt="Parish Logo" class="rpd-logo">
            <div>
                <div class="rpd-parish-name">ST. JOHN THE BAPTIST PARISH</div>
                <div class="rpd-parish-loc">Tiaong, Quezon</div>
            </div>
        </div>
        <div class="rpd-letterhead-right">
            <div class="rpd-generated-label">Generated</div>
            <div class="rpd-generated-date">{{ now()->format('F j, Y') }}</div>
        </div>
    </div>

    <div class="rpd-title-row">
        <div>
            <div class="rpd-title">Donation Records</div>
            <div class="rpd-subtitle">Record of all in-person donations received.</div>
        </div>
        <div class="rpd-range-pill">{{ count($donationsList) }} record{{ count($donationsList) !== 1 ? 's' : '' }}</div>
    </div>

    <div class="rpd-stats">
        <div class="rpd-stat"><div class="rpd-stat-label" style="color:#dc2626">Total Collected</div><div class="rpd-stat-val" style="color:#dc2626">₱{{ number_format($donationSectionTotal, 2) }}</div></div>
        @foreach($donationsByPurpose as $pk => $pv)
        <div class="rpd-stat"><div class="rpd-stat-label" style="color:{{ $purposeColors[$pk] ?? '#64748b' }}">{{ $purposeLabels[$pk] ?? $pk }}</div><div class="rpd-stat-val" style="color:{{ $purposeColors[$pk] ?? '#64748b' }}">₱{{ number_format($pv, 2) }}</div></div>
        @endforeach
    </div>

    <div class="rpd-section-label">Donation List</div>

    <table class="rpd-table rpd-donor-table">
        <thead>
            <tr>
                <th>Receipt #</th>
                <th>Donor Name</th>
                <th>Amount</th>
                <th>Purpose</th>
                <th>Date</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @php $rpdDonI = 0; @endphp
            @foreach($donationsList as $don)
            @php $rpdDonPurpose = $don['purpose'] ?? 'other'; $rpdDonI++; @endphp
            <tr style="{{ $rpdDonI % 2 === 0 ? 'background:#fafafa;' : '' }}">
                <td style="font-family:monospace;color:#64748b">{{ $don['receipt_number'] ?? '—' }}</td>
                <td style="font-weight:600">{{ $don['donor_name'] ?? '—' }}</td>
                <td style="font-weight:700;color:#dc2626">₱{{ number_format((float)($don['amount'] ?? 0), 2) }}</td>
                <td>{{ $rpdDonPurpose === 'other' && !empty($don['custom_purpose']) ? $don['custom_purpose'] : ($purposeLabels[$rpdDonPurpose] ?? ucfirst($rpdDonPurpose)) }}</td>
                <td style="color:#64748b">{{ \Carbon\Carbon::parse($don['donation_date'])->format('M j, Y') }}</td>
                <td style="color:#94a3b8">{{ $don['notes'] ?? '—' }}</td>
            </tr>
            @endforeach
            <tr class="rpd-total-row">
                <td colspan="2">Total</td>
                <td style="text-align:right">₱{{ number_format($donationSectionTotal, 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>

    <div class="rpd-footer">
        <span>St. John the Baptist Parish &middot; Administration Panel</span>
        <span>Page 1 of 1</span>
    </div>
</div>

{{-- Receipt Print Modal --}}
<div id="receiptModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.55);z-index:9999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:20px;padding:0;max-width:420px;width:94%;box-shadow:0 24px 60px rgba(0,0,0,0.25);overflow:hidden">
        <div id="receiptContent" style="padding:36px 32px;font-family:'Georgia',serif">
            <div style="text-align:center;margin-bottom:20px;border-bottom:2px solid #e2e8f0;padding-bottom:16px">
                <div style="font-size:11px;font-weight:700;letter-spacing:.12em;color:#6b7280;text-transform:uppercase;margin-bottom:4px">Official Receipt</div>
                <div style="font-size:18px;font-weight:800;color:#1f2937">St. John the Baptist Parish</div>
                <div style="font-size:12px;color:#94a3b8;margin-top:2px">Donation Acknowledgment</div>
            </div>
            <table style="width:100%;font-size:13px;border-collapse:collapse">
                <tr><td style="padding:5px 0;color:#6b7280;width:40%">Receipt No.</td><td style="padding:5px 0;font-weight:700;color:#1f2937" id="rct-num"></td></tr>
                <tr><td style="padding:5px 0;color:#6b7280">Date</td><td style="padding:5px 0;color:#374151" id="rct-date"></td></tr>
                <tr><td style="padding:5px 0;color:#6b7280">Donor</td><td style="padding:5px 0;font-weight:700;color:#1f2937" id="rct-donor"></td></tr>
                <tr><td style="padding:5px 0;color:#6b7280">Purpose</td><td style="padding:5px 0;color:#374151" id="rct-purpose"></td></tr>
                <tr><td style="padding:5px 0;color:#6b7280">Notes</td><td style="padding:5px 0;color:#94a3b8;font-style:italic;font-size:12px" id="rct-notes"></td></tr>
                <tr style="border-top:2px solid #dc2626;margin-top:8px">
                    <td style="padding:12px 0 5px;font-weight:700;color:#dc2626;font-size:15px">Amount</td>
                    <td style="padding:12px 0 5px;font-weight:800;color:#dc2626;font-size:20px" id="rct-amount"></td>
                </tr>
            </table>
            <div style="margin-top:24px;padding-top:16px;border-top:1px dashed #e2e8f0;display:flex;justify-content:space-between">
                <div style="font-size:11px;color:#94a3b8;text-align:center">
                    <div style="border-top:1px solid #374151;width:120px;margin-bottom:4px;margin-top:28px"></div>
                    Received by
                </div>
                <div style="font-size:11px;color:#94a3b8;text-align:center">
                    <div style="border-top:1px solid #374151;width:120px;margin-bottom:4px;margin-top:28px"></div>
                    Donor's Signature
                </div>
            </div>
            <div style="text-align:center;margin-top:16px;font-size:10px;color:#cbd5e1">This receipt serves as official acknowledgment of your donation. Thank you for your generosity.</div>
        </div>
        <div style="padding:16px 32px 24px;display:flex;justify-content:flex-end;gap:10px;border-top:1px solid #f1f5f9">
            <button onclick="document.getElementById('receiptModal').style.display='none'"
                    style="padding:9px 20px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;color:#64748b;font-size:13px;font-weight:600;cursor:pointer">
                Close
            </button>
            <button onclick="printReceiptNow()"
                    style="padding:9px 22px;border-radius:10px;border:none;background:#dc2626;color:#fff;font-size:13px;font-weight:700;cursor:pointer">
                <i class="fa fa-print"></i> Print
            </button>
        </div>
    </div>
</div>

<script>
const purposeLabels = {
    building_fund:  'Building Fund',
    mass_intention: 'Mass Intention',
    tithes:         'Tithes',
    other:          'Other',
};
function toggleCustomPurpose(val) {
    const el = document.getElementById('customPurposeInput');
    el.style.display = val === 'other' ? 'block' : 'none';
    el.required = val === 'other';
}
function printReceipt(don) {
    document.getElementById('rct-num').textContent    = don.receipt_number || '—';
    document.getElementById('rct-date').textContent   = don.donation_date  || '—';
    document.getElementById('rct-donor').textContent  = don.donor_name     || '—';
    document.getElementById('rct-purpose').textContent= (don.purpose === 'other' && don.custom_purpose) ? don.custom_purpose : (purposeLabels[don.purpose] || don.purpose || '—');
    document.getElementById('rct-notes').textContent  = don.notes           || '—';
    document.getElementById('rct-amount').textContent = '₱' + parseFloat(don.amount || 0).toLocaleString('en-PH', {minimumFractionDigits:2});
    const modal = document.getElementById('receiptModal');
    modal.style.display = 'flex';
}
function printReceiptNow() {
    const content = document.getElementById('receiptContent').innerHTML;
    const win = window.open('', '_blank', 'width=500,height=700');
    win.document.write(`<!DOCTYPE html><html><head><title>Donation Receipt</title>
        <style>body{font-family:Georgia,serif;padding:40px;max-width:420px;margin:0 auto}table{width:100%;border-collapse:collapse}td{padding:5px 0;font-size:13px}@media print{button{display:none}}</style>
        </head><body>${content}<script>window.onload=()=>window.print()<\/script></body></html>`);
    win.document.close();
}
</script>
@endif

        {{-- ==================== ANNOUNCEMENTS ==================== --}}
        @if($section === 'announcements')
            <section class="section-card">
                <div class="section-header">
                    <div><h2>Announcements Board</h2><p>Publish updates for parishioners and control what appears on the home page.</p></div>
                </div>

                {{-- Stats bar --}}
                <div class="ann-stats-bar">
                    <div class="ann-stat">
                        <span class="ann-stat-num">{{ $announcementCount }}</span>
                        <span class="ann-stat-label">Total</span>
                    </div>
                    <div class="ann-stat-divider"></div>
                    <div class="ann-stat">
                        <span class="ann-stat-num live">{{ $visibleAnnouncementCount }}</span>
                        <span class="ann-stat-label">Live on home</span>
                    </div>
                    <div class="ann-stat-divider"></div>
                    <div class="ann-stat">
                        <span class="ann-stat-num hidden">{{ $announcementCount - $visibleAnnouncementCount }}</span>
                        <span class="ann-stat-label">Hidden</span>
                    </div>
                </div>

                <div class="announcement-grid">
                    {{-- Compose form --}}
                    <div class="announcement-form">
                        <div class="ann-form-header">
                            <h3><i class="fa fa-pencil"></i> Compose announcement</h3>
                            <p>Fill in the details below and publish to the home page.</p>
                        </div>
                        <div class="ann-form-body">
                            <form method="POST" action="{{ route('admin.handle') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="action" value="create_announcement">
                                <input type="hidden" name="redirect_section" value="announcements">
                                <div class="form-group">
                                    <label for="announcement_title">
                                        Title
                                        <span id="ann-title-count">0 / 150</span>
                                    </label>
                                    <input type="text" class="form-control" id="announcement_title" name="announcement_title"
                                           maxlength="150" required placeholder="e.g. Parish Fiesta 2025"
                                           oninput="document.getElementById('ann-title-count').textContent=this.value.length+' / 150'">
                                </div>
                                <div class="form-group">
                                    <label for="announcement_body">
                                        Message
                                        <span id="ann-body-count">0 / 500</span>
                                    </label>
                                    <textarea class="form-control" id="announcement_body" name="announcement_body"
                                              rows="5" maxlength="500" required placeholder="Write your message here…"
                                              oninput="document.getElementById('ann-body-count').textContent=this.value.length+' / 500'"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Category <span>optional</span></label>
                                    <input type="hidden" id="announcement_category" name="announcement_category" value="">
                                    <div class="adm-csl" id="adm-csl-ann-category">
                                        <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-ann-category')">
                                            <span class="adm-csl-label adm-csl-placeholder">Choose a category</span>
                                            <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </button>
                                        <ul class="adm-csl-list">
                                            <li class="is-selected" data-value="" data-placeholder="1" onclick="admCslSelect('adm-csl-ann-category','announcement_category','','Choose a category')">Choose a category</li>
                                            <li data-value="Mass Schedule" onclick="admCslSelect('adm-csl-ann-category','announcement_category','Mass Schedule','Mass Schedule')">Mass Schedule</li>
                                            <li data-value="Events" onclick="admCslSelect('adm-csl-ann-category','announcement_category','Events','Events')">Events</li>
                                            <li data-value="Notice" onclick="admCslSelect('adm-csl-ann-category','announcement_category','Notice','Notice')">Notice</li>
                                            <li data-value="Reminder" onclick="admCslSelect('adm-csl-ann-category','announcement_category','Reminder','Reminder')">Reminder</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="announcement_image">Image <span>optional</span></label>
                                    <input type="file" class="ann-file-input" id="announcement_image" name="announcement_image"
                                           accept="image/jpeg,image/png,image/gif,image/webp"
                                           onchange="annPreviewImage(this,'ann-img-preview-compose')">
                                    <div class="ann-img-preview" id="ann-img-preview-compose">
                                        <img src="" alt="Preview">
                                    </div>
                                </div>
                                <label class="ann-visibility-toggle" for="announcement_show">
                                    <input type="checkbox" id="announcement_show" name="announcement_show" value="1">
                                    <div>
                                        <span class="ann-visibility-toggle-label">Show on home page</span>
                                        <span class="ann-visibility-toggle-sub">Visitors will see this announcement immediately</span>
                                    </div>
                                </label>
                                <button type="submit" class="ann-publish-btn">
                                    <i class="fa fa-paper-plane"></i> Publish announcement
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Announcement list --}}
                    <div class="announcement-list">
                        @if($announcementCount === 0)
                            <p class="empty-block">No announcements have been posted yet.</p>
                        @else
                            @foreach($announcements as $a)
                                @php
                                    $isVisible  = (int)($a->show_on_home ?? 0) === 1;
                                    $annBody    = $a->body ?? '';
                                    $annNeedsRM = strlen($annBody) > 200;
                                    $annCat     = $a->category ?? null;
                                    $annImgUrl  = !empty($a->image_path)
                                        ? rtrim(config('services.supabase.url'), '/') . '/storage/v1/object/public/' . $a->image_path
                                        : null;
                                @endphp
                                <div class="announcement-item">
                                    <div class="ann-accent {{ $isVisible ? 'live' : 'hidden' }}"></div>
                                    <div class="ann-content">
                                        <div class="ann-header-row">
                                            <div class="ann-title-block" style="flex:1;min-width:0;">
                                                @if($annCat)
                                                    <span class="ann-category-tag"><i class="fa fa-tag"></i> {{ $annCat }}</span>
                                                @endif
                                                <p class="ann-title">{{ $a->title }}</p>
                                                <span class="ann-date">
                                                    <i class="fa fa-calendar-o"></i>
                                                    {{ adminFormatCreatedAt($a->created_at ?? '') }}
                                                </span>
                                            </div>
                                            <div style="display:flex;align-items:flex-start;gap:10px;flex-shrink:0;">
                                                <span class="ann-badge {{ $isVisible ? 'live' : 'hidden' }}">
                                                    {{ $isVisible ? 'Live' : 'Hidden' }}
                                                </span>
                                                @if($annImgUrl)
                                                    <img class="ann-image-thumb" src="{{ $annImgUrl }}" alt="Thumbnail">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="ann-body {{ $annNeedsRM ? 'clamped' : '' }}" id="ann-body-{{ $a->id }}">{{ $annBody }}</div>
                                        @if($annNeedsRM)
                                            <button class="ann-read-more-btn" onclick="annAdminToggle(this,'ann-body-{{ $a->id }}')">
                                                <i class="fa fa-chevron-down"></i> Read more
                                            </button>
                                        @endif

                                        <div class="ann-footer">
                                            <span style="font-size:12px;color:#94a3b8;">
                                                {{ $isVisible ? 'Visible to parishioners' : 'Not shown on home page' }}
                                            </span>
                                            <div class="ann-actions">
                                                {{-- Edit --}}
                                                <button type="button" class="ann-edit-btn"
                                                    onclick="annOpenEdit({{ $a->id }},'{{ addslashes($a->title) }}',{{ json_encode($annBody) }},'{{ addslashes($annCat ?? '') }}',{{ $isVisible ? 1 : 0 }},'{{ $annImgUrl ?? '' }}')">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </button>
                                                {{-- Toggle visibility --}}
                                                <form method="POST" action="{{ route('admin.handle') }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="toggle_announcement">
                                                    <input type="hidden" name="announcement_id" value="{{ $a->id }}">
                                                    <input type="hidden" name="show_on_home" value="{{ $isVisible ? '0' : '1' }}">
                                                    <input type="hidden" name="redirect_section" value="announcements">
                                                    <button type="submit" class="ann-toggle-btn {{ $isVisible ? 'make-hidden' : 'make-live' }}">
                                                        <i class="fa {{ $isVisible ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                        {{ $isVisible ? 'Hide' : 'Make live' }}
                                                    </button>
                                                </form>
                                                {{-- Delete --}}
                                                <form method="POST" action="{{ route('admin.handle') }}" id="ann-delete-form-{{ $a->id }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="delete_announcement">
                                                    <input type="hidden" name="announcement_id" value="{{ $a->id }}">
                                                    <input type="hidden" name="redirect_section" value="announcements">
                                                    <button type="button" class="ann-delete-btn" onclick="annConfirmDelete('ann-delete-form-{{ $a->id }}', '{{ addslashes($a->title) }}')">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Edit Announcement Modal --}}
                <div class="dcm-overlay" id="annEditModal" onclick="if(event.target===this) annCloseEdit()">
                    <div class="dcm-modal dcm-modal-lg">
                        <div class="ps-hdr">
                            <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
                            <span class="ps-parish">St. John the Baptist Parish</span>
                            <span class="ps-dot"></span>
                            <span class="ps-loc">Tiaong, Quezon</span>
                        </div>
                        <button type="button" class="dcm-close-btn" onclick="annCloseEdit()">&times;</button>

                        <div class="dcm-scroll">
                            <div style="padding:22px 24px 0;">
                                <div style="display:flex;align-items:center;gap:8px;color:#fff;font-weight:800;font-size:16px;">
                                    <i class="fa fa-pencil"></i> Edit Announcement
                                </div>
                                <p style="margin:6px 0 0;color:rgba(255,255,255,.45);font-size:12px;">Changes will update immediately on the home page if set to Live.</p>
                            </div>

                            <form method="POST" action="{{ route('admin.handle') }}" enctype="multipart/form-data" style="padding:20px 24px 24px;display:flex;flex-direction:column;gap:16px;">
                                @csrf
                                <input type="hidden" name="action" value="edit_announcement">
                                <input type="hidden" name="redirect_section" value="announcements">
                                <input type="hidden" name="announcement_id" id="edit_ann_id">

                                <div>
                                    <label class="dcm-label">Title <span id="edit-title-count" class="dcm-label-counter">0 / 150</span></label>
                                    <input type="text" name="announcement_title" id="edit_ann_title" maxlength="150" required
                                        class="dcm-form-input"
                                        oninput="document.getElementById('edit-title-count').textContent=this.value.length+' / 150'">
                                </div>

                                <div>
                                    <label class="dcm-label">Message <span id="edit-body-count" class="dcm-label-counter">0 / 500</span></label>
                                    <textarea name="announcement_body" id="edit_ann_body" maxlength="500" rows="5" required
                                        class="dcm-textarea" style="resize:vertical;"
                                        oninput="document.getElementById('edit-body-count').textContent=this.value.length+' / 500'"></textarea>
                                </div>

                                <div>
                                    <label class="dcm-label">Category <span class="dcm-label-counter">optional</span></label>
                                    <input type="hidden" name="announcement_category" id="edit_ann_category" value="">
                                    <div class="adm-csl" id="adm-csl-edit-ann-category" style="min-width:0;width:100%;">
                                        <button type="button" class="adm-csl-btn" onclick="admCslToggle('adm-csl-edit-ann-category')">
                                            <span class="adm-csl-label adm-csl-placeholder">Choose a category</span>
                                            <svg class="adm-csl-arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </button>
                                        <ul class="adm-csl-list">
                                            <li class="is-selected" data-value="" data-placeholder="1" onclick="admCslSelect('adm-csl-edit-ann-category','edit_ann_category','','Choose a category')">Choose a category</li>
                                            <li data-value="Mass Schedule" onclick="admCslSelect('adm-csl-edit-ann-category','edit_ann_category','Mass Schedule','Mass Schedule')">Mass Schedule</li>
                                            <li data-value="Events" onclick="admCslSelect('adm-csl-edit-ann-category','edit_ann_category','Events','Events')">Events</li>
                                            <li data-value="Notice" onclick="admCslSelect('adm-csl-edit-ann-category','edit_ann_category','Notice','Notice')">Notice</li>
                                            <li data-value="Reminder" onclick="admCslSelect('adm-csl-edit-ann-category','edit_ann_category','Reminder','Reminder')">Reminder</li>
                                        </ul>
                                    </div>
                                </div>

                                <div>
                                    <label class="dcm-label">Replace image <span class="dcm-label-counter">optional — keeps current if blank</span></label>
                                    <div id="edit_ann_current_img" style="margin-bottom:8px;display:none;">
                                        <img id="edit_ann_current_img_el" src="" alt="Current" style="width:100%;max-height:120px;object-fit:cover;border-radius:10px;border:1px solid rgba(255,255,255,.15);">
                                    </div>
                                    <input type="file" name="announcement_image" accept="image/jpeg,image/png,image/gif,image/webp"
                                        class="ann-file-input"
                                        onchange="annPreviewImage(this,'ann-img-preview-edit')">
                                    <div class="ann-img-preview" id="ann-img-preview-edit"><img src="" alt="Preview"></div>
                                </div>

                                <label class="dcm-toggle-row">
                                    <input type="checkbox" name="announcement_show" id="edit_ann_show" value="1" style="width:16px;height:16px;accent-color:#dc2626;">
                                    <div>
                                        <span class="t">Show on home page</span>
                                        <span class="s">Visitors will see this announcement immediately</span>
                                    </div>
                                </label>

                                <div class="adm-cfm-btns" style="padding-top:4px;">
                                    <button type="button" class="adm-cfm-cancel" onclick="annCloseEdit()">Cancel</button>
                                    <button type="submit" class="adm-cfm-ok"><i class="fa fa-save"></i> Save changes</button>
                                </div>
                            </form>
                        </div>
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
    // Legacy range toggle removed — handled by admCslSelect
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
        selectedDate: null,
        view: 'calendar',
        typeFilter: 'all'
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

    const eventTypeColors = {
        wedding: '#f472b6',
        baptism: '#2dd4bf',
        funeral: '#64748b',
    };

    function getEventColor(eventType) {
        return eventTypeColors[(eventType || '').toLowerCase()] || '#94a3b8';
    }

    const eventTypeIcons = {
        wedding: '<i class="fa fa-heart"></i>',
        baptism: '<i class="fa fa-plus"></i>',
        funeral: '<svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg>',
    };

    function getEventIcon(eventType) {
        return eventTypeIcons[(eventType || '').toLowerCase()] || '';
    }

    const eventTypeStyles = {
        wedding: { bg: '#fce7f3', color: '#be185d', border: '#fbcfe8' },
        baptism: { bg: '#dcfce7', color: '#15803d', border: '#bbf7d0' },
        funeral: { bg: '#f1f5f9', color: '#475569', border: '#cbd5e1' },
    };

    function getEventStyle(eventType) {
        return eventTypeStyles[(eventType || '').toLowerCase()] || { bg: '#f1f5f9', color: '#64748b', border: '#e2e8f0' };
    }

    function daysUntilLabel(dateKey) {
        const parts = dateKey.split('-');
        const d = new Date(parts[0], parts[1] - 1, parts[2]);
        d.setHours(0, 0, 0, 0);
        const diff = Math.round((d - today) / 86400000);
        if (diff === 0) return 'Today';
        if (diff === 1) return 'Tomorrow';
        if (diff === -1) return 'Yesterday';
        if (diff > 1) return 'In ' + diff + ' days';
        return Math.abs(diff) + ' days ago';
    }

    function buildDetailCardHtml(r, dateKey, done) {
        const evStyle = getEventStyle(r.eventType);
        const etLabel = r.eventType ? (r.eventType.charAt(0).toUpperCase() + r.eventType.slice(1).toLowerCase()) : 'Unknown';
        const isPending = r.status === 'pending';
        const statusClass = isPending ? 'spill-pending' : (done ? 'spill-done' : 'spill-approved');
        const statusLabel = isPending ? 'Pending' : (done ? 'Done' : 'Approved');
        const detailPayload = escapeHtml(JSON.stringify(r.detail || {}));
        const daysLabel = daysUntilLabel(dateKey);
        return `
                <div class="admin-detail-card">
                    <div class="admin-detail-top">
                        <h4>Reservation #${escapeHtml(r.id)} · ${escapeHtml(r.name)}</h4>
                        <span class="status-pill ${statusClass}"><span class="spill-dot"></span>${statusLabel}</span>
                    </div>

                    <div class="admin-detail-tags">
                        <span class="admin-ev-type-tag">
                            <span class="admin-ev-icon" style="background:${evStyle.bg};color:${evStyle.color};border:1.5px solid ${evStyle.border}">
                                ${getEventIcon(r.eventType)}
                            </span>
                            ${escapeHtml(etLabel)}
                        </span>
                        <span class="admin-days-badge ${done ? 'is-done' : ''}">${escapeHtml(daysLabel)}</span>
                    </div>

                    <div class="admin-detail-meta">
                        <span><span class="admin-meta-icon"><i class="fa fa-clock-o"></i></span> ${escapeHtml(r.time || 'No time provided')}</span>
                        <span><span class="admin-meta-icon"><i class="fa fa-envelope"></i></span> ${escapeHtml(r.email || 'No email provided')}</span>
                        <span><span class="admin-meta-icon"><i class="fa fa-phone"></i></span> ${escapeHtml(r.phone || 'No phone provided')}</span>
                    </div>

                    ${r.note ? `
                        <div class="admin-detail-note">
                            <strong><i class="fa fa-sticky-note-o"></i> Admin note:</strong>
                            <div>${escapeHtml(r.note)}</div>
                        </div>
                    ` : ''}

                    <div class="admin-detail-footer">
                        <button type="button" class="admin-detail-view-btn" data-res="${detailPayload}" onclick="openResDetail(JSON.parse(this.dataset.res))">
                            <i class="fa fa-eye"></i> View details
                        </button>
                    </div>
                </div>
            `;
    }

    function renderDetails(dateKey) {
        const reservations = reservationsByDate[dateKey] || [];
        const done = isDoneDate(dateKey);
        const todayKeyForPanel = toLocalKey(today.getFullYear(), today.getMonth(), today.getDate());
        const isToday = dateKey === todayKeyForPanel;

        if (!reservations.length) {
            detailsPanel.innerHTML = `
                <div class="admin-calendar-empty">
                    <div class="admin-calendar-empty-icon">
                        <i class="fa fa-calendar-o"></i>
                    </div>
                    <h3>${escapeHtml(formatDisplayDate(dateKey))}</h3>
                    <p>No reservations for this date.</p>
                </div>
            `;
            return;
        }

        const badgeLabel = isToday ? "Today's schedule" : (done ? 'Done / Past date' : 'Upcoming schedule');

        let html = `
            <div class="admin-date-heading">
                <span>${badgeLabel}</span>
                <h3>${escapeHtml(formatDisplayDate(dateKey))}</h3>
                <p>${reservations.length} reservation${reservations.length > 1 ? 's' : ''}</p>
            </div>
        `;

        reservations.forEach(function (r) {
            html += buildDetailCardHtml(r, dateKey, done);
        });

        detailsPanel.innerHTML = html;
    }

    function renderList() {
        const listContainer = document.getElementById('scheduleListView');
        if (!listContainer) return;

        const keys = Object.keys(reservationsByDate).filter(function (key) {
            if (state.typeFilter === 'all') return true;
            return reservationsByDate[key].some(function (r) { return (r.eventType || '').toLowerCase() === state.typeFilter; });
        }).sort();

        if (!keys.length) {
            listContainer.innerHTML = '<div class="schedule-list-empty">No reservations match this filter.</div>';
            return;
        }

        let html = '';
        keys.forEach(function (key) {
            const done = isDoneDate(key);
            const entries = reservationsByDate[key].filter(function (r) {
                return state.typeFilter === 'all' || (r.eventType || '').toLowerCase() === state.typeFilter;
            });
            if (!entries.length) return;
            html += '<div class="schedule-list-group"><div class="schedule-list-date-heading">' + escapeHtml(formatDisplayDate(key)) + '</div><div class="schedule-list-cards">';
            entries.forEach(function (r) {
                html += buildDetailCardHtml(r, key, done);
            });
            html += '</div></div>';
        });

        listContainer.innerHTML = html;
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

        const navGroup = document.createElement('div');
        navGroup.style.display = 'flex';
        navGroup.style.alignItems = 'center';
        navGroup.style.gap = '14px';

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

        navGroup.appendChild(previousButton);
        navGroup.appendChild(label);
        navGroup.appendChild(nextButton);

        const todayButton = document.createElement('button');
        todayButton.type = 'button';
        todayButton.className = 'schedule-today-btn';
        todayButton.textContent = 'Today';
        todayButton.addEventListener('click', function () {
            state.year = today.getFullYear();
            state.month = today.getMonth();
            state.selectedDate = toLocalKey(today.getFullYear(), today.getMonth(), today.getDate());
            renderCalendar();
            renderDetails(state.selectedDate);
        });

        navigation.appendChild(navGroup);
        navigation.appendChild(todayButton);
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

        const todayKey = toLocalKey(today.getFullYear(), today.getMonth(), today.getDate());

        for (let day = 1; day <= lastDay.getDate(); day++) {
            const dateKey = toLocalKey(state.year, state.month, day);
            const reservations = reservationsByDate[dateKey] || [];
            const td = document.createElement('td');
            const div = document.createElement('div');

            div.className = 'calendar_day';

            const isToday = dateKey === todayKey;
            const isDone  = isDoneDate(dateKey);
            const approvedCount = reservations.filter(function (r) { return r.status === 'approved'; }).length;
            const pendingCount  = reservations.filter(function (r) { return r.status === 'pending'; }).length;

            const typesPresent = [];
            reservations.forEach(function (r) {
                const t = (r.eventType || '').toLowerCase();
                if (['wedding', 'baptism', 'funeral'].indexOf(t) !== -1 && typesPresent.indexOf(t) === -1) {
                    typesPresent.push(t);
                }
            });

            if (reservations.length) {
                if (isDone) {
                    div.classList.add('status_done');
                } else if (approvedCount > 0) {
                    div.classList.add('status_booked');
                } else if (pendingCount > 0) {
                    div.classList.add('status_pending');
                }
            }

            if (isToday) {
                div.classList.add('is_today');
            }

            if (state.selectedDate === dateKey) {
                div.classList.add('is_selected');
            }

            const matchesTypeFilter = state.typeFilter === 'all' || typesPresent.indexOf(state.typeFilter) !== -1;
            if (reservations.length && !matchesTypeFilter) {
                div.classList.add('type-dim');
            }

            // Build stacked dots, all top-right: today dot, then status dot, then one per event type present — each one underneath the last
            const dotClasses = [];
            if (isToday) dotClasses.push('today-indicator-dot');

            let tooltip = '';
            if (reservations.length) {
                if (!isDone && approvedCount > 0) {
                    dotClasses.push('reservation-status-dot booked-dot');
                    tooltip = approvedCount + ' approved booking(s)' + (pendingCount ? ' • ' + pendingCount + ' pending' : '');
                } else if (!isDone && pendingCount > 0) {
                    dotClasses.push('reservation-status-dot pending-dot');
                    tooltip = pendingCount + ' pending booking(s)';
                } else if (isDone) {
                    tooltip = reservations.length + ' reservation(s) — done';
                }
            }

            typesPresent.forEach(function (t) {
                dotClasses.push('type-dot type-dot-' + t);
            });

            let dotHtml = '';
            dotClasses.forEach(function (cls, idx) {
                dotHtml += '<span class="' + cls + '" style="top:' + (10 + idx * 16) + 'px"></span>';
            });

            if (tooltip) {
                div.setAttribute('data-booking-tooltip', tooltip);
            }

            div.innerHTML = `<span class="day_number">${day}</span>${dotHtml}`;
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

    const viewCalendarBtn = document.getElementById('scheduleViewCalendarBtn');
    const viewListBtn = document.getElementById('scheduleViewListBtn');
    const calendarViewEl = document.getElementById('scheduleCalendarView');
    const listViewEl = document.getElementById('scheduleListView');

    function setView(view) {
        state.view = view;
        if (viewCalendarBtn) viewCalendarBtn.classList.toggle('is-active', view === 'calendar');
        if (viewListBtn) viewListBtn.classList.toggle('is-active', view === 'list');
        if (calendarViewEl) calendarViewEl.style.display = view === 'calendar' ? '' : 'none';
        if (listViewEl) listViewEl.style.display = view === 'list' ? '' : 'none';
        if (view === 'list') {
            renderList();
        } else {
            renderCalendar();
        }
    }

    if (viewCalendarBtn) viewCalendarBtn.addEventListener('click', function () { setView('calendar'); });
    if (viewListBtn) viewListBtn.addEventListener('click', function () { setView('list'); });

    document.querySelectorAll('#scheduleTypePills .schedule-type-pill').forEach(function (btn) {
        btn.addEventListener('click', function () {
            state.typeFilter = btn.dataset.type;
            document.querySelectorAll('#scheduleTypePills .schedule-type-pill').forEach(function (b) {
                b.classList.toggle('is-active', b === btn);
            });
            if (state.view === 'list') {
                renderList();
            } else {
                renderCalendar();
            }
        });
    });

    document.querySelectorAll('.schedule-upcoming-card[data-date]').forEach(function (card) {
        card.addEventListener('click', function () {
            const dateKey = card.dataset.date;
            if (!dateKey) return;
            const parts = dateKey.split('-');
            state.year = parseInt(parts[0], 10);
            state.month = parseInt(parts[1], 10) - 1;
            state.selectedDate = dateKey;
            setView('calendar');
            renderDetails(dateKey);
        });
    });

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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cardViewBtn  = document.getElementById('customerViewCardBtn');
    const tableViewBtn = document.getElementById('customerViewTableBtn');
    const cardView     = document.getElementById('customerCardView');
    const tableView    = document.getElementById('customerTableView');

    if (cardViewBtn && tableViewBtn && cardView && tableView) {
        function setCustomerView(view) {
            cardViewBtn.classList.toggle('is-active', view === 'card');
            tableViewBtn.classList.toggle('is-active', view === 'table');
            cardView.style.display  = view === 'card'  ? '' : 'none';
            tableView.style.display = view === 'table' ? '' : 'none';
        }
        cardViewBtn.addEventListener('click', function () { setCustomerView('card'); });
        tableViewBtn.addEventListener('click', function () { setCustomerView('table'); });
    }

});

function customerSortContainer(container, itemSelector, mode) {
    if (!container) return;
    const items = Array.from(container.querySelectorAll(itemSelector));
    items.sort(function (a, b) {
        if (mode === 'name-asc')  return (a.dataset.sortName || '').localeCompare(b.dataset.sortName || '');
        if (mode === 'name-desc') return (b.dataset.sortName || '').localeCompare(a.dataset.sortName || '');
        if (mode === 'count-desc') return (parseInt(b.dataset.sortCount, 10) || 0) - (parseInt(a.dataset.sortCount, 10) || 0);
        if (mode === 'count-asc')  return (parseInt(a.dataset.sortCount, 10) || 0) - (parseInt(b.dataset.sortCount, 10) || 0);
        return 0;
    });
    items.forEach(function (item) { container.appendChild(item); });
}

function customerSortPick(li, value, label) {
    const csl = document.getElementById('adm-csl-customer-sort');
    if (!csl) return;
    csl.querySelector('.adm-csl-label').textContent = 'Sort: ' + label;
    csl.querySelectorAll('.adm-csl-list li').forEach(function (item) { item.classList.remove('is-selected'); });
    li.classList.add('is-selected');
    csl.classList.remove('is-open');
    csl.querySelector('.adm-csl-btn')?.classList.remove('is-open');

    customerSortContainer(document.getElementById('customerCardView'), '.cust-card', value);
    customerSortContainer(document.getElementById('customerTableBody'), 'tr', value);
}
</script>
<!-- Parish Confirm Modal -->
<div id="adm-cfm-overlay">
    <div class="adm-cfm-modal">
        <div class="adm-cfm-hdr" id="adm-cfm-hdr">
            <svg class="adm-cfm-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
            <span class="adm-cfm-parish">St. John the Baptist Parish</span>
            <span class="adm-cfm-dot"></span>
            <span class="adm-cfm-loc">Tiaong, Quezon</span>
        </div>
        <div class="adm-cfm-body" id="adm-cfm-body">
            <div class="adm-cfm-glow"></div>
            <div class="adm-cfm-icon">
                <div class="adm-cfm-ring"></div>
                <div class="adm-cfm-inner">
                    <svg width="38" height="38" viewBox="0 0 38 38" fill="none">
                        <path class="adm-cfm-chk" d="M10 19l6 6L28 13"/>
                    </svg>
                </div>
            </div>
            <div class="adm-cfm-title" id="adm-cfm-title"></div>
            <div class="adm-cfm-sub" id="adm-cfm-sub"></div>
            <div class="adm-cfm-btns">
                <button class="adm-cfm-cancel" id="adm-cfm-cancel">Cancel</button>
                <button class="adm-cfm-ok" id="adm-cfm-ok">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
function spawnParticles(el, cols, count) {
    el.querySelectorAll('.adm-cfm-particle').forEach(p => p.remove());
    for (let i = 0; i < count; i++) {
        const p = document.createElement('div');
        const s = Math.random() * 6 + 4;
        p.className = 'adm-cfm-particle';
        p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
        el.appendChild(p);
    }
}

function admCfmOpen(titleText, subText, okLabel, showCancel) {
    const overlay = document.getElementById('adm-cfm-overlay');
    const modal = overlay.querySelector('.adm-cfm-modal');
    const clone = modal.cloneNode(true);
    modal.replaceWith(clone);
    clone.classList.remove('is-success');
    const cfmInner = clone.querySelector('.adm-cfm-inner');
    if (cfmInner) {
        cfmInner.innerHTML = '<svg width="38" height="38" viewBox="0 0 38 38" fill="none"><path class="adm-cfm-chk" d="M10 19l6 6L28 13"/></svg>';
    }
    clone.querySelector('.adm-cfm-title').id  = 'adm-cfm-title';
    clone.querySelector('.adm-cfm-sub').id    = 'adm-cfm-sub';
    clone.querySelector('.adm-cfm-ok').id     = 'adm-cfm-ok';
    clone.querySelector('.adm-cfm-cancel').id = 'adm-cfm-cancel';

    document.getElementById('adm-cfm-title').textContent  = titleText;
    document.getElementById('adm-cfm-sub').textContent    = subText;
    document.getElementById('adm-cfm-ok').textContent     = okLabel;
    document.getElementById('adm-cfm-cancel').style.display = showCancel ? '' : 'none';
    if (!showCancel) {
        document.getElementById('adm-cfm-ok').style.cssText += ';flex:unset;width:100%';
    }

    spawnParticles(clone.querySelector('.adm-cfm-hdr'),  ['rgba(255,255,255,.25)','#fca5a5','#dc2626'], 10);
    spawnParticles(clone.querySelector('.adm-cfm-body'), ['#dc2626','#fca5a5','rgba(255,255,255,.2)'], 20);

    overlay.classList.add('is-open');
    return overlay;
}

function showAdminConfirm(title, text, onConfirm) {
    const overlay = admCfmOpen(title, text, 'Confirm', true);

    function cleanup() {
        overlay.classList.remove('is-open');
        document.getElementById('adm-cfm-ok').removeEventListener('click', onOk);
        document.getElementById('adm-cfm-cancel').removeEventListener('click', onCancel);
        overlay.removeEventListener('click', onBg);
    }
    function onOk()    { cleanup(); onConfirm(); }
    function onCancel(){ cleanup(); }
    function onBg(e)   { if (e.target === overlay) cleanup(); }

    document.getElementById('adm-cfm-ok').addEventListener('click', onOk);
    document.getElementById('adm-cfm-cancel').addEventListener('click', onCancel);
    overlay.addEventListener('click', onBg);
}

function showAdminSuccess(title, text) {
    const overlay = admCfmOpen(title, text, 'OK', false);
    const modal = overlay.querySelector('.adm-cfm-modal');
    if (modal) modal.classList.add('is-success');

    function dismiss() { overlay.classList.remove('is-open'); }
    document.getElementById('adm-cfm-ok').addEventListener('click', dismiss, { once: true });
    overlay.addEventListener('click', function onBg(e) {
        if (e.target === overlay) { dismiss(); overlay.removeEventListener('click', onBg); }
    });
}

function showAdminError(title, text) {
    const overlay = admCfmOpen(title, text, 'OK', false);

    const inner = overlay.querySelector('.adm-cfm-inner');
    if (inner) {
        inner.innerHTML = '<svg width="38" height="38" viewBox="0 0 38 38" fill="none"><line class="adm-cfm-chk" x1="12" y1="12" x2="26" y2="26"/><line class="adm-cfm-chk" x1="26" y1="12" x2="12" y2="26"/></svg>';
    }

    function dismiss() { overlay.classList.remove('is-open'); }
    document.getElementById('adm-cfm-ok').addEventListener('click', dismiss, { once: true });
    overlay.addEventListener('click', function onBg(e) {
        if (e.target === overlay) { dismiss(); overlay.removeEventListener('click', onBg); }
    });
}

function openDisableModal(id) {
    const el = document.getElementById('disable-overlay-' + id);
    if (!el) return;
    el.classList.add('is-open');
    document.body.style.overflow = 'hidden';
}

function closeDisableModal(id) {
    const el = document.getElementById('disable-overlay-' + id);
    if (!el) return;
    el.classList.remove('is-open');
    document.body.style.overflow = '';
}

function openProfileModal(id) {
    const el = document.getElementById('profile-overlay-' + id);
    if (!el) return;
    el.classList.add('is-open');
    document.body.style.overflow = 'hidden';
}

function closeProfileModal(id) {
    const el = document.getElementById('profile-overlay-' + id);
    if (!el) return;
    el.classList.remove('is-open');
    document.body.style.overflow = '';
}
</script>

@if(session('offi_assigned'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminSuccess('Officiant Assigned!', 'The officiant has been assigned to this reservation.');
});
</script>
@elseif(session('offi_removed'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminSuccess('Officiant Removed', 'The officiant has been removed from this reservation.');
});
</script>
@endif

@if(session('customer_action_success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminSuccess('Success', @json(session('customer_action_success')));
});
</script>
@elseif(session('customer_action_error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminError('Error', @json(session('customer_action_error')));
});
</script>
@endif

@if(session('announcement_action_success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminSuccess('Success', @json(session('announcement_action_success')));
});
</script>
@elseif(session('announcement_action_error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminError('Error', @json(session('announcement_action_error')));
});
</script>
@endif

@if(session('donation_action_success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminSuccess('Success', @json(session('donation_action_success')));
});
</script>
@elseif(session('donation_action_error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminError('Error', @json(session('donation_action_error')));
});
</script>
@endif

@if(session('priest_action_success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminSuccess('Success', @json(session('priest_action_success')));
});
</script>
@elseif(session('priest_action_error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminError('Error', @json(session('priest_action_error')));
});
</script>
@endif

@if(session('attendance_action_success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminSuccess('Success', @json(session('attendance_action_success')));
});
</script>
@elseif(session('attendance_action_error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showAdminError('Error', @json(session('attendance_action_error')));
});
</script>
@endif

</body>
</html>