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
            margin-left: auto; background: #dc2626; color: #fff !important;
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
        .flash-messages { display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; }
        .flash { border-radius: 14px; padding: 14px 18px; font-weight: 600; }
        .flash-success { background: rgba(34,197,94,0.16); color: #166534; }
        .flash-error { background: rgba(239,68,68,0.18); color: #991b1b; }
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
        .summary-card.card-pending h2 i  { color:#f59e0b; }
        .summary-card.card-approved h2 i { color:#10b981; }
        .summary-card.card-declined h2 i { color:#ef4444; }
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
        .section-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; }
        .section-header h2 { margin: 0; font-size: 24px; color: #1f2937; }
        .section-header p { margin: 0; color: #6b7280; font-size: 14px; }
        .reservation-toolbar { display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 24px; padding: 16px; border: 1px solid rgba(220,38,38,0.15); border-radius: 18px; background: rgba(248,250,252,0.6); }
        .reservation-filter-form { display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; }
        .reservation-filter-form .form-group { margin: 0; }
        .reservation-filter-form label { font-size: 13px; font-weight: 600; color: #4b5563; margin-bottom: 6px; }
        .reservation-filter-form select { min-width: 160px; padding-right: 36px !important; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 11px center; }
        .reservation-filter-form input[type="date"] { min-width: 160px; }
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
        .schedule-event-counts li { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 999px; background: rgba(220,38,38,0.08); color: #991b1b; font-weight: 600; font-size: 13px; }
        .schedule-list { display: flex; flex-direction: column; gap: 16px; }
        .schedule-item { background: #fff; border-radius: 18px; padding: 20px; box-shadow: 0 14px 32px rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.12); }
        .schedule-item-header { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 12px; margin-bottom: 12px; font-size: 15px; color: #1f2937; font-weight: 600; }
        .schedule-item-body { display: flex; flex-direction: column; gap: 10px; font-size: 13px; color: #4b5563; }
        .schedule-item-body span { display: flex; align-items: center; gap: 8px; }
        .schedule-item-notes { margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(148,163,184,0.4); color: #475569; white-space: pre-line; }
        .reservation-date-field { display: flex; flex-direction: column; }
        .reservation-date-field.is-hidden { display: none; }
        .status-columns { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; }
        .status-column { position: relative; border-radius: 20px; padding: 24px; background: linear-gradient(145deg,#fff 0%,#fff1f2 100%); box-shadow: 0 18px 40px rgba(220,38,38,0.12); overflow: hidden; }
        .status-column::before { content: ''; position: absolute; inset: 0; border-radius: inherit; opacity: 0.08; pointer-events: none; }
        .status-column h3 { font-size: 20px; margin: 0 0 6px; color: #7f1d1d; }
        .status-column p { margin: 0 0 16px; font-size: 14px; color: #6b7280; }
        .status-column .empty-state { font-style: italic; color: #94a3b8; }
        .status-column-pending::before { background: linear-gradient(135deg,#f97316,#fb923c); }
        .status-column-approved::before { background: linear-gradient(135deg,#10b981,#34d399); }
        .status-column-declined::before { background: linear-gradient(135deg,#ef4444,#dc2626); }
        .reservation-card { background: #fff; border-radius: 18px; padding: 20px; margin-bottom: 18px; box-shadow: 0 14px 32px rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.12); }
        .reservation-card:last-child { margin-bottom: 0; }
        .reservation-card h4 { margin: 0 0 8px; font-size: 18px; color: #1f2937; }
        .reservation-meta { display: flex; flex-wrap: wrap; gap: 10px 18px; margin-bottom: 12px; font-size: 13px; color: #4b5563; }
        .reservation-meta span { display: flex; align-items: center; gap: 6px; }
        .muted-text { color: #94a3b8; font-style: italic; }
        .reservation-meta a { color: #991b1b; font-weight: 600; text-decoration: none; }
        .reservation-meta a:hover, .reservation-meta a:focus { text-decoration: underline; }
        .reservation-notes { font-size: 14px; line-height: 1.6; color: #374151; margin-bottom: 14px; white-space: pre-wrap; }
        .reservation-attachments { list-style: none; margin: 0 0 16px; padding: 0; display: flex; flex-wrap: wrap; gap: 10px; }
        .reservation-attachments a { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 999px; background: rgba(220,38,38,0.12); color: #991b1b; font-size: 13px; font-weight: 600; text-decoration: none; transition: background 0.2s ease, color 0.2s ease; }
        .reservation-attachments a:hover, .reservation-attachments a:focus { background: rgba(220,38,38,0.2); color: #7f1d1d; }
        .status-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
        .status-actions form { margin: 0; }
        .status-actions .btn { border-radius: 999px; padding: 6px 16px; font-weight: 600; }
        .view-link { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 999px; border: 1px solid rgba(220,38,38,0.4); color: #991b1b; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s ease; }
        .view-link:hover, .view-link:focus { background: rgba(220,38,38,0.1); color: #7f1d1d; }
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
        .ann-category-tag { display:inline-flex; align-items:center; gap:4px; padding:2px 9px; border-radius:999px; font-size:11px; font-weight:700; background:#fee2e2; color:#dc2626; margin-bottom:6px; }
        .ann-img-preview { margin-top:8px; display:none; }
        .ann-img-preview img { width:100%; max-height:140px; object-fit:cover; border-radius:10px; border:1px solid #e5e7eb; }
        .visibility-badge { display:inline-flex; align-items:center; gap:6px; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; letter-spacing:.3px; }
        .visibility-badge.visible { background:#dcfce7; color:#15803d; border:1.5px solid #bbf7d0; }
        .visibility-badge.visible::before { content:''; display:inline-block; width:6px; height:6px; border-radius:50%; background:#22c55e; animation:livePulse 1.8s ease-in-out infinite; }
        .visibility-badge.hidden { background:#f1f5f9; color:#64748b; border:1.5px solid #e2e8f0; }
        .visibility-badge.hidden::before { content:''; display:inline-block; width:6px; height:6px; border-radius:50%; background:#94a3b8; }
        .empty-block { font-style: italic; color: #94a3b8; }
        /* ── ADMIN LOGIN (Option A — Split Panel) ── */
        html.login-page { scrollbar-gutter: stable; }
        .login-split { display: flex; min-height: 100vh; }

        .login-left {
            flex: 0 0 44%;
            background: #1e293b;
            display: flex; flex-direction: column; justify-content: space-between;
            padding: 56px 52px 44px;
            position: relative; overflow: hidden;
        }
        .login-left::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 20% 25%, rgba(220,38,38,.10) 0%, transparent 65%),
                radial-gradient(ellipse 50% 60% at 85% 80%, rgba(220,38,38,.06) 0%, transparent 65%);
            pointer-events: none;
        }
        .ll-watermark {
            position: absolute; right: -30px; bottom: -50px;
            font-size: 200px; color: rgba(255,255,255,.025);
            font-weight: 900; pointer-events: none; user-select: none; line-height: 1;
        }
        .ll-mark { display: flex; align-items: center; gap: 12px; margin-bottom: 52px; position: relative; z-index: 1; }
        .ll-mark-icon {
            width: 44px; height: 44px; border-radius: 12px; background: #dc2626;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 20px; font-weight: 900; flex-shrink: 0;
        }
        .ll-mark-name { font-size: 13px; font-weight: 800; color: #fff; line-height: 1.3; }
        .ll-mark-sub  { font-size: 10px; color: rgba(255,255,255,.35); margin-top: 2px; }
        .ll-heading {
            font-size: 2rem; font-weight: 900; color: #fff;
            line-height: 1.15; letter-spacing: -.03em;
            margin-bottom: 14px; position: relative; z-index: 1;
        }
        .ll-heading span { color: #fca5a5; }
        .ll-desc {
            font-size: .85rem; color: rgba(255,255,255,.45);
            line-height: 1.75; max-width: 290px;
            margin-bottom: 36px; position: relative; z-index: 1;
        }
        .ll-pills { display: flex; flex-direction: column; gap: 9px; position: relative; z-index: 1; }
        .ll-pill  { display: flex; align-items: center; gap: 9px; font-size: .8rem; font-weight: 600; color: rgba(255,255,255,.65); }
        .ll-pill-dot { width: 7px; height: 7px; border-radius: 50%; background: #dc2626; opacity: .8; flex-shrink: 0; }
        .ll-footer { font-size: .65rem; color: rgba(255,255,255,.2); letter-spacing: .06em; position: relative; z-index: 1; }

        .login-right {
            flex: 1;
            display: flex; align-items: center; justify-content: center;
            padding: 56px 64px;
            background: #fff;
        }
        .login-form-box { width: 100%; max-width: 340px; }
        .lf-eyebrow { font-size: .68rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #dc2626; margin-bottom: 10px; }
        .lf-heading { font-size: 1.55rem; font-weight: 900; color: #0f172a; letter-spacing: -.03em; margin-bottom: 6px; }
        .lf-sub     { font-size: .83rem; color: #94a3b8; margin-bottom: 28px; line-height: 1.6; }

        /* alert */
        .lf-alert {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 14px; border-radius: 10px;
            font-size: .82rem; font-weight: 600; line-height: 1.5;
            margin-bottom: 20px;
        }
        .lf-alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .lf-alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .lf-alert-icon    { flex-shrink: 0; font-size: .9rem; margin-top: 1px; }

        /* fields */
        .lf-field { margin-bottom: 16px; }
        .lf-field label {
            display: block; font-size: .67rem; font-weight: 700;
            letter-spacing: .09em; text-transform: uppercase;
            color: #475569; margin-bottom: 7px;
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
            border-top: 1px solid #f1f5f9;
            font-size: .72rem; color: #94a3b8; text-align: center; line-height: 1.7;
        }
        .lf-chips { display: flex; gap: 7px; justify-content: center; margin-top: 8px; }
        .lf-chip  { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: .68rem; font-weight: 700; }
        .lf-chip-admin { background: #fee2e2; color: #dc2626; }
        .lf-chip-sec   { background: #fef3c7; color: #92400e; }

        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-right { padding: 40px 28px; }
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

/* top-right status dot removed — replaced by colored event-type dots */
.admin-availability-calendar .calendar_day.status_booked::after,
.admin-availability-calendar .calendar_day.status_done::after {
    content: none;
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
    background: linear-gradient(135deg, #ec4899, #f472b6);
}

.event-card.baptism .event-icon {
    background: linear-gradient(135deg, #14b8a6, #2dd4bf);
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

/* modal profile */
.cust-modal-header { padding:24px 24px 0; }
.cust-modal-avatar-row { display:flex; align-items:center; gap:16px; margin-bottom:16px; }
.cust-modal-avatar { width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:800; color:#fff; flex-shrink:0; }
.cust-modal-avatar.active { background:linear-gradient(135deg,#dc2626,#b91c1c); }
.cust-modal-avatar.disabled { background:linear-gradient(135deg,#94a3b8,#64748b); }
.cust-modal-name { font-size:18px; font-weight:800; color:#7f1d1d; margin:0 0 4px; }
.cust-modal-sub { font-size:13px; color:#64748b; margin:0; }
.cust-modal-meta { display:flex; flex-wrap:wrap; gap:10px; padding:16px 24px; background:#f8fafc; border-top:1px solid #f1f5f9; border-bottom:1px solid #f1f5f9; }
.cust-modal-meta-item { display:flex; align-items:center; gap:6px; font-size:13px; color:#374151; }
.cust-modal-meta-item i { color:#9ca3af; }
.cust-modal-stat-row { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; padding:20px 24px; }
.cust-modal-stat { border-radius:12px; padding:14px; text-align:center; }
.cust-modal-stat-num { font-size:22px; font-weight:800; line-height:1; margin-bottom:4px; }
.cust-modal-stat-label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.07em; }
.cust-modal-stat.total { background:rgba(220,38,38,0.08); }
.cust-modal-stat.total .cust-modal-stat-num { color:#dc2626; }
.cust-modal-stat.total .cust-modal-stat-label { color:#ef4444; }
.cust-modal-stat.approved { background:rgba(16,185,129,0.08); }
.cust-modal-stat.approved .cust-modal-stat-num { color:#065f46; }
.cust-modal-stat.approved .cust-modal-stat-label { color:#10b981; }
.cust-modal-stat.pending { background:rgba(245,158,11,0.08); }
.cust-modal-stat.pending .cust-modal-stat-num { color:#92400e; }
.cust-modal-stat.pending .cust-modal-stat-label { color:#f59e0b; }
.cust-modal-stat.declined { background:rgba(239,68,68,0.08); }
.cust-modal-stat.declined .cust-modal-stat-num { color:#991b1b; }
.cust-modal-stat.declined .cust-modal-stat-label { color:#ef4444; }
.cust-modal-history { padding:0 24px 24px; }
.cust-modal-history h6 { font-size:13px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:12px; }
.cust-history-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
.cust-history-row:last-child { border-bottom:none; }
.cust-history-evtype { display:inline-flex; align-items:center; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; }
.cust-history-id { font-size:12px; color:#94a3b8; flex-shrink:0; }
.cust-history-date { font-size:12px; color:#6b7280; margin-left:auto; white-space:nowrap; }

.customer-actions { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.customer-actions form { margin:0; }
.customer-action-btn { height:38px; min-width:145px; border-radius:999px !important; display:inline-flex; align-items:center; justify-content:center; gap:7px; font-weight:700; box-shadow:0 10px 22px rgba(15,23,42,0.08); }
.customer-action-btn:hover { transform:translateY(-1px); }

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
    color: #991b1b;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}

.admin-detail-view-btn:hover,
.admin-detail-view-btn:focus {
    background: rgba(79, 70, 229, 0.14);
    color: #7f1d1d;
    text-decoration: none;
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

/* ── CALENDAR COLORED DOTS ── */
.legend-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 4px;
    vertical-align: middle;
}
.cal-dots {
    position: absolute;
    top: 9px;
    right: 9px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.cal-ev-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ── DETAIL CARD TAGS ── */
.admin-detail-tags {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 8px 0 12px;
    flex-wrap: wrap;
}
.admin-ev-type-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 11px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.04em;
}
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

    </style>
</head>
<body>

@if(!$isLoggedIn)
{{-- ===================== LOGIN ===================== --}}
<script>document.documentElement.classList.add('login-page');</script>
<div class="login-split">

    {{-- LEFT PANEL --}}
    <div class="login-left">
        <div style="position:relative;z-index:1;">
            <div class="ll-mark">
                <img src="{{ asset('img/about/about_1.jpg') }}" alt="Parish Logo" style="width:48px;height:48px;border-radius:12px;object-fit:cover;flex-shrink:0;border:2px solid rgba(255,255,255,.15);">
                <div>
                    <div class="ll-mark-name">St. John the Baptist Parish</div>
                    <div class="ll-mark-sub">Tiaong, Quezon</div>
                </div>
            </div>
            <h1 class="ll-heading">Parish<br><span>Administration</span><br>Portal</h1>
            <p class="ll-desc">Securely manage reservations, announcements, customer accounts, and parish records.</p>
            <div class="ll-pills">
                <div class="ll-pill"><div class="ll-pill-dot"></div> Review &amp; approve reservation requests</div>
                <div class="ll-pill"><div class="ll-pill-dot"></div> Manage the daily sacrament schedule</div>
                <div class="ll-pill"><div class="ll-pill-dot"></div> Publish announcements to parishioners</div>
                <div class="ll-pill"><div class="ll-pill-dot"></div> Monitor donations and generate reports</div>
            </div>
        </div>
        <div class="ll-footer">RESTRICTED ACCESS &middot; AUTHORIZED PERSONNEL ONLY</div>
        <img src="{{ asset('img/about/about_1.jpg') }}" alt="" aria-hidden="true" style="position:absolute;right:-40px;bottom:-40px;width:260px;height:260px;border-radius:50%;object-fit:cover;opacity:.07;pointer-events:none;user-select:none;z-index:0;">
    </div>

    {{-- RIGHT PANEL --}}
    <div class="login-right">
        <div class="login-form-box">
            <div class="lf-eyebrow">Admin Access</div>
            <h2 class="lf-heading">Welcome back</h2>
            <p class="lf-sub">Sign in with your administrator credentials to continue.</p>

            @if(session('flash_success'))
                <div class="lf-alert lf-alert-success">
                    <span class="lf-alert-icon">&#10003;</span>
                    <span>{{ session('flash_success') }}</span>
                </div>
            @endif
            @if(session('flash_error'))
                <div class="lf-alert lf-alert-error">
                    <span class="lf-alert-icon">&#9888;</span>
                    <span>{{ session('flash_error') }}</span>
                </div>
            @endif
            @if(session('login_error'))
                <div class="lf-alert lf-alert-error">
                    <span class="lf-alert-icon">&#9888;</span>
                    <span>{{ session('login_error') }}</span>
                </div>
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
    $map = ['pending'=>'badge-warning','approved'=>'badge-success','declined'=>'badge-danger'];
    $cls = $map[strtolower($status)] ?? 'badge-secondary';
    return '<span class="badge '.$cls.'">'.ucfirst(strtolower($status)).'</span>';
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
                <div class="summary-card card-total">
                    <h2><i class="fa fa-inbox"></i>Total Requests</h2>
                    <div class="summary-value">{{ $summaryTotals['total'] }}</div>
                    <div class="summary-caption">All reservation submissions</div>
                </div>
                <div class="summary-card card-pending">
                    <h2><i class="fa fa-clock-o"></i>Pending</h2>
                    <div class="summary-value">{{ $summaryTotals['pending'] }}</div>
                    <div class="summary-caption">Awaiting your review</div>
                </div>
                <div class="summary-card card-approved">
                    <h2><i class="fa fa-check-circle"></i>Approved</h2>
                    <div class="summary-value">{{ $summaryTotals['approved'] }}</div>
                    <div class="summary-caption">Ready to proceed</div>
                </div>
                <div class="summary-card card-declined">
                    <h2><i class="fa fa-times-circle"></i>Declined</h2>
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
        <div class="event-icon event-symbol">✝</div>
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
        <div class="event-icon event-symbol">⚰</div>
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

        {{-- Stats bar --}}
        <div class="schedule-stats-bar">
            <div class="schedule-stat">
                <span class="schedule-stat-num">{{ $scheduleTodayCount }}</span>
                <span class="schedule-stat-label">Today</span>
            </div>
            <div class="schedule-stat-divider"></div>
            <div class="schedule-stat">
                <span class="schedule-stat-num">{{ $scheduleWeekCount }}</span>
                <span class="schedule-stat-label">This week</span>
            </div>
            <div class="schedule-stat-divider"></div>
            <div class="schedule-stat">
                <span class="schedule-stat-num">{{ $scheduleMonthCount }}</span>
                <span class="schedule-stat-label">This month</span>
            </div>
            <div class="schedule-stat-divider"></div>
            <div class="schedule-stat">
                <span class="schedule-stat-num">{{ $scheduleReservationCount }}</span>
                <span class="schedule-stat-label">Total approved</span>
            </div>
        </div>

        <div class="admin-calendar-layout">
            <div class="admin-calendar-card">
               <div class="calendar_legend mb-3">
    <span><span class="legend-dot" style="background:#f472b6;"></span> Wedding</span>
    <span><span class="legend-dot" style="background:#2dd4bf;"></span> Baptism</span>
    <span><span class="legend-dot" style="background:#64748b;"></span> Funeral</span>
    <span><span class="legend done"></span> Past</span>
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
                    <div class="schedule-upcoming-card">
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
                        <div class="form-group">
                            <label for="reservation_filter_type">Event type</label>
                            <select class="form-control form-control-sm" id="reservation_filter_type" name="event_type">
                                <option value="all"     {{ $reservationFilterType === 'all'     ? 'selected' : '' }}>All types</option>
                                <option value="baptism" {{ $reservationFilterType === 'baptism' ? 'selected' : '' }}>Baptism</option>
                                <option value="wedding" {{ $reservationFilterType === 'wedding' ? 'selected' : '' }}>Wedding</option>
                                <option value="funeral" {{ $reservationFilterType === 'funeral' ? 'selected' : '' }}>Funeral</option>
                            </select>
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

                @if($cancelRequestCount > 0 || $cancelFilterActive)
                <div style="display:flex;align-items:center;justify-content:space-between;background:#fff7ed;border:1.5px solid #fed7aa;border-radius:10px;padding:12px 16px;margin-bottom:16px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:#e11d48;border-radius:8px;color:#fff;font-size:14px;">
                            <i class="fa fa-exclamation"></i>
                        </span>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#92400e;">
                                {{ $cancelRequestCount }} Cancellation Request{{ $cancelRequestCount !== 1 ? 's' : '' }} Pending
                            </div>
                            <div style="font-size:12px;color:#b45309;margin-top:1px;">
                                {{ $cancelFilterActive ? 'Currently filtered — showing only cancellation requests.' : 'Customers are waiting for a response.' }}
                            </div>
                        </div>
                    </div>
                    @if($cancelFilterActive)
                        <a href="{{ route('admin.index', ['section' => 'reservations']) }}"
                           style="font-size:12px;font-weight:700;color:#92400e;text-decoration:none;white-space:nowrap;padding:6px 12px;border:1.5px solid #fed7aa;border-radius:6px;background:#fff;">
                            <i class="fa fa-times"></i> Clear filter
                        </a>
                    @else
                        <a href="{{ route('admin.index', array_merge(request()->query(), ['section' => 'reservations', 'cancel_filter' => '1'])) }}"
                           style="font-size:12px;font-weight:700;color:#fff;text-decoration:none;white-space:nowrap;padding:6px 12px;border-radius:6px;background:#e11d48;">
                            View requests
                        </a>
                    @endif
                </div>
                @endif

                @if($filteredTotals['total'] === 0)
                    <p class="empty-block">
                        {{ $summaryTotals['total'] === 0 ? 'No reservations have been submitted yet.' : 'No reservations match the selected filters.' }}
                    </p>
                @else
                    <div class="status-columns">
                        @foreach($statusMeta as $statusKey => $meta)
                            <div class="status-column {{ $meta['class'] }}">
                                <h3>
                                    {{ $meta['title'] }}
                                    <span style="display:inline-flex;align-items:center;justify-content:center;min-width:22px;height:22px;padding:0 6px;border-radius:999px;font-size:11px;font-weight:700;margin-left:8px;background:rgba(220,38,38,0.12);color:#dc2626">{{ count($filteredGrouped[$statusKey] ?? []) }}</span>
                                </h3>
                                <p>{{ $meta['subtitle'] }}</p>

                                @if(empty($filteredGrouped[$statusKey]))
                                    <p class="empty-state">{{ $meta['empty'] }}</p>
                                @else
                                    @if($statusKey === 'approved')
                                    @php
                                        $pastDoneList = array_values(array_filter(
                                            $filteredGrouped['approved'],
                                            fn($r) => ($r['preferred_date'] ?? $r['reservation_date'] ?? '') < date('Y-m-d')
                                        ));
                                    @endphp
                                    @if(count($pastDoneList) > 0)
                                    <button onclick="togglePastApproved(this)"
                                        style="display:flex;align-items:center;gap:7px;width:100%;padding:8px 12px;margin-bottom:12px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;color:#15803d;font-size:12px;font-weight:700;cursor:pointer;text-align:left">
                                        <i class="fa fa-history"></i>
                                        <span>{{ count($pastDoneList) }} completed event{{ count($pastDoneList) > 1 ? 's' : '' }}</span>
                                        <i class="fa fa-chevron-down" style="margin-left:auto;margin-right:10px;font-size:10px;transition:transform .2s"></i>
                                    </button>
                                    <div id="pastApprovedSection" style="display:none;margin-bottom:14px;border:1.5px solid #dcfce7;border-radius:10px;overflow:hidden">
                                        @foreach($pastDoneList as $r)
                                        @php
                                            $pdEvType  = strtolower($r['event_type'] ?? '');
                                            $pdDate    = $r['preferred_date'] ?? $r['reservation_date'] ?? null;
                                            $pdColors  = ['wedding'=>['bg'=>'rgba(244,114,182,0.14)','color'=>'#db2777'],'baptism'=>['bg'=>'rgba(22,163,74,0.14)','color'=>'#16a34a'],'funeral'=>['bg'=>'rgba(100,116,139,0.14)','color'=>'#475569']];
                                            $pdColor   = $pdColors[$pdEvType] ?? ['bg'=>'rgba(148,163,184,0.12)','color'=>'#64748b'];
                                            $pdPriest  = collect($officiantWorkload)->firstWhere('id', (int)($r['officiant_id'] ?? 0));
                                        @endphp
                                        <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-bottom:1px solid #f0fdf4;background:#fff">
                                            <i class="fa fa-check-circle" style="color:#22c55e;font-size:15px;flex-shrink:0"></i>
                                            <div style="flex:1;min-width:0">
                                                <div style="font-size:13px;font-weight:700;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                                    {{ $r['name'] ?? 'No name' }} <span style="font-weight:400;color:#9ca3af;font-size:12px">#{{ $r['id'] }}</span>
                                                </div>
                                                <div style="display:flex;align-items:center;gap:6px;margin-top:3px;flex-wrap:wrap">
                                                    @if($pdEvType)
                                                    <span style="padding:1px 7px;border-radius:999px;font-size:10px;font-weight:700;text-transform:uppercase;background:{{ $pdColor['bg'] }};color:{{ $pdColor['color'] }}">{{ ucfirst($pdEvType) }}</span>
                                                    @endif
                                                    <span style="font-size:11px;color:#94a3b8"><i class="fa fa-calendar"></i> {{ $pdDate ? date('M j, Y', strtotime($pdDate)) : '—' }}</span>
                                                    @if($pdPriest)
                                                    <span style="font-size:11px;color:#6b7280"><i class="fa fa-user-circle-o"></i> {{ $pdPriest['name'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                    @endif

                                    @foreach($filteredGrouped[$statusKey] as $r)
    @php
        $evDate        = $r['preferred_date'] ?? $r['reservation_date'] ?? null;
        $isPastEvent   = $evDate && $evDate < date('Y-m-d');
        $evType        = strtolower($r['event_type'] ?? '');
        $daysUntil     = $evDate ? (int) ceil((strtotime($evDate) - strtotime('today')) / 86400) : null;
        $isUrgent      = $statusKey === 'pending' && $daysUntil !== null && $daysUntil >= 0 && $daysUntil <= 7;
        $evTypeColors  = ['wedding'=>['bg'=>'rgba(244,114,182,0.14)','color'=>'#db2777'],'baptism'=>['bg'=>'rgba(22,163,74,0.14)','color'=>'#16a34a'],'funeral'=>['bg'=>'rgba(100,116,139,0.14)','color'=>'#475569']];
        $evColor       = $evTypeColors[$evType] ?? ['bg'=>'rgba(148,163,184,0.12)','color'=>'#64748b'];
    @endphp
    @if($statusKey === 'approved' && $isPastEvent) @continue @endif
                                        <div class="reservation-card">
                                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:6px">
                                                <div class="status-badge">{!! adminStatusBadge($r['status']) !!}</div>
                                                @if($evType)
                                                <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 9px;border-radius:999px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;background:{{ $evColor['bg'] }};color:{{ $evColor['color'] }}">
                                                    @if($evType === 'funeral')
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width=".82em" height=".82em" fill="currentColor" style="vertical-align:-.05em;"><path d="M8 2h8l4 4v12l-4 4H8l-4-4V6z"/></svg>
                                                    @else
                                                        <i class="fa {{ $evType === 'wedding' ? 'fa-heart' : 'fa-plus' }}"></i>
                                                    @endif
                                                    {{ ucfirst($evType) }}
                                                </span>
                                                @endif
                                                @if($isUrgent)
                                                <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 9px;border-radius:999px;font-size:10px;font-weight:700;text-transform:uppercase;background:rgba(239,68,68,0.12);color:#dc2626"><i class="fa fa-exclamation-circle"></i> Urgent</span>
                                                @endif
                                                @if($daysUntil !== null)
                                                <span style="font-size:11px;color:#94a3b8;margin-left:auto">
                                                    @if($daysUntil < 0) {{ abs($daysUntil) }}d ago
                                                    @elseif($daysUntil === 0) Today
                                                    @elseif($daysUntil === 1) Tomorrow
                                                    @elseif($daysUntil < 14) In {{ $daysUntil }} days
                                                    @else In {{ ceil($daysUntil / 7) }} weeks
                                                    @endif
                                                </span>
                                                @endif
                                            </div>
                                            <h4 style="margin:0 0 8px">{{ $r['name'] ?? $r['details']['name'] ?? $r['customer_name'] ?? 'No name provided' }} <span style="font-weight:400;color:#94a3b8;font-size:13px">#{{ $r['id'] ?? '—' }}</span></h4>
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

@if($statusKey !== 'declined' && count($priests) > 0)
@php $currentOfficiant = (int)($r['officiant_id'] ?? 0); @endphp
<form method="POST" action="{{ route('admin.reservations.officiant', $r['id']) }}" style="display:flex;align-items:center;gap:8px;margin-bottom:10px;flex-wrap:wrap">
    @csrf
    <label style="font-size:12px;font-weight:600;color:#64748b;white-space:nowrap"><i class="fa fa-user-circle-o"></i> Officiant</label>
    <select name="officiant_id" onchange="this.form.submit()"
        style="flex:1;min-width:140px;padding:5px 10px;border:1px solid #e5e7eb;border-radius:8px;font-size:12px;color:#374151;background:#fff">
        <option value="">— Unassigned —</option>
        @foreach($priests as $p)
        <option value="{{ $p['id'] }}" {{ $currentOfficiant === (int)($p['id'] ?? 0) ? 'selected' : '' }}>
            {{ ($p['title'] ?? 'Fr.') . ' ' . ($p['name'] ?? '') }}
        </option>
        @endforeach
    </select>
</form>
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

    @if(!empty($r['cancellation_requested']))
        <div style="width:100%;margin:8px 0 4px;padding:10px 14px;background:#fff7ed;border:1.5px solid #fed7aa;border-radius:10px;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;">
            <div style="font-size:.8rem;color:#92400e;min-width:0;">
                <div style="font-weight:700;"><i class="fa fa-exclamation-triangle"></i> Cancellation Requested</div>
                @if(!empty($r['cancel_reason']))
                    <div style="color:#b45309;margin-top:2px;font-size:.75rem;">{{ $r['cancel_reason'] }}</div>
                @endif
            </div>
            <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                <form method="POST" action="{{ route('admin.handle') }}" style="margin:0;">
                    @csrf
                    <input type="hidden" name="action" value="approve_cancellation">
                    <input type="hidden" name="reservation_id" value="{{ $r['id'] }}">
                    <input type="hidden" name="admin_note" value="Your cancellation request has been approved.">
                    <input type="hidden" name="redirect_section" value="reservations">
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                </form>
                <button type="button" class="btn btn-danger btn-sm"
                    data-toggle="modal" data-target="#denyCancelModal{{ $r['id'] }}">
                    Deny
                </button>
            </div>
        </div>
        <div class="modal fade" id="denyCancelModal{{ $r['id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content customer-disable-modal">
                    <form method="POST" action="{{ route('admin.handle') }}">
                        @csrf
                        <input type="hidden" name="action" value="deny_cancellation">
                        <input type="hidden" name="reservation_id" value="{{ $r['id'] }}">
                        <input type="hidden" name="redirect_section" value="reservations">
                        <div class="modal-header customer-disable-header">
                            <div>
                                <h5 class="modal-title">Deny Cancellation</h5>
                                <small>{{ $r['name'] ?? 'Customer' }}</small>
                            </div>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <label class="customer-disable-label">Reason for denying</label>
                            <textarea name="admin_note" class="form-control customer-disable-textarea" rows="3"
                                placeholder="e.g. The reservation is already confirmed and scheduled…" required></textarea>
                        </div>
                        <div class="modal-footer customer-disable-footer">
                            <button type="button" class="btn btn-light customer-action-btn" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger customer-action-btn">
                                <i class="fa fa-times-circle"></i> Deny Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if($r['status'] !== 'approved')
        @if(!empty($r['officiant_id']))
            <form method="POST" action="{{ route('admin.handle') }}">
                @csrf
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="reservation_id" value="{{ $r['id'] }}">
                <input type="hidden" name="status" value="approved">
                <input type="hidden" name="admin_note" class="admin-note-{{ $r['id'] }}">
                <input type="hidden" name="redirect_section" value="reservations">
                <button type="submit" class="btn btn-success btn-sm">Approve</button>
            </form>
        @else
            <button type="button" class="btn btn-success btn-sm"
                disabled
                title="Assign a priest before approving"
                style="opacity:0.45;cursor:not-allowed">
                Approve
            </button>
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

{{-- ── Filter toolbar ── --}}
<section class="section-card" style="margin-bottom:24px">
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
                <select name="report_from_month" onchange="document.getElementById('rptFilterForm').submit()"
                        style="min-width:130px;padding:9px 40px 9px 14px;border-radius:12px;border:1.5px solid #d1d5db;background:#fff url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E&quot;) no-repeat right 14px center;color:#1f2937;font-size:14px;font-weight:600;box-shadow:0 2px 8px rgba(220,38,38,0.07);cursor:pointer;appearance:none">
                    @foreach($rptMonthOpts as $rmi => $rmn)
                        <option value="{{ $rmi + 1 }}" {{ $reportFromMonth === $rmi + 1 ? 'selected' : '' }}>{{ $rmn }}</option>
                    @endforeach
                </select>
                <select name="report_from_year" onchange="document.getElementById('rptFilterForm').submit()"
                        style="min-width:90px;padding:9px 40px 9px 14px;border-radius:12px;border:1.5px solid #d1d5db;background:#fff url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E&quot;) no-repeat right 14px center;color:#1f2937;font-size:14px;font-weight:600;box-shadow:0 2px 8px rgba(220,38,38,0.07);cursor:pointer;appearance:none">
                    @for($y = now()->year + 1; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" {{ $reportFromYear === $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div style="display:flex;align-items:flex-end;padding-bottom:10px;color:#9ca3af;font-size:14px;font-weight:600">→</div>

        <div style="display:flex;flex-direction:column;gap:6px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.07em">To</label>
            <div style="display:flex;gap:6px;align-items:center">
                <select name="report_to_month" onchange="document.getElementById('rptFilterForm').submit()"
                        style="min-width:130px;padding:9px 40px 9px 14px;border-radius:12px;border:1.5px solid #d1d5db;background:#fff url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E&quot;) no-repeat right 14px center;color:#1f2937;font-size:14px;font-weight:600;box-shadow:0 2px 8px rgba(220,38,38,0.07);cursor:pointer;appearance:none">
                    @foreach($rptMonthOpts as $rmi => $rmn)
                        <option value="{{ $rmi + 1 }}" {{ $reportToMonth === $rmi + 1 ? 'selected' : '' }}>{{ $rmn }}</option>
                    @endforeach
                </select>
                <select name="report_to_year" onchange="document.getElementById('rptFilterForm').submit()"
                        style="min-width:90px;padding:9px 40px 9px 14px;border-radius:12px;border:1.5px solid #d1d5db;background:#fff url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E&quot;) no-repeat right 14px center;color:#1f2937;font-size:14px;font-weight:600;box-shadow:0 2px 8px rgba(220,38,38,0.07);cursor:pointer;appearance:none">
                    @for($y = now()->year + 1; $y >= now()->year - 4; $y--)
                        <option value="{{ $y }}" {{ $reportToYear === $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:6px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.07em">Type</label>
            <select name="report_type" onchange="document.getElementById('rptFilterForm').submit()"
                    style="min-width:170px;padding:9px 38px 9px 14px;border-radius:12px;border:1.5px solid rgba(220,38,38,0.25);
                           background:#fff url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E&quot;) no-repeat right 12px center;
                           color:#1f2937;font-size:14px;font-weight:600;
                           box-shadow:0 2px 8px rgba(220,38,38,0.07);cursor:pointer;appearance:none">
                <option value="all"     {{ $reportType === 'all'     ? 'selected' : '' }}>All sacraments</option>
                <option value="baptism" {{ $reportType === 'baptism' ? 'selected' : '' }}>Baptism</option>
                <option value="wedding" {{ $reportType === 'wedding' ? 'selected' : '' }}>Wedding</option>
                <option value="funeral" {{ $reportType === 'funeral' ? 'selected' : '' }}>Funeral</option>
            </select>
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
                    style="padding:9px 20px;border-radius:12px;border:1.5px solid rgba(220,38,38,0.35);
                           background:#fff;color:#dc2626;font-size:14px;font-weight:700;cursor:pointer;
                           display:inline-flex;align-items:center;gap:8px;
                           box-shadow:0 2px 8px rgba(220,38,38,0.10);transition:all .2s ease"
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
                <button onclick="showLoggedCards()" id="btnShowLogged"
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
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                <span style="font-size:14px;font-weight:700;color:#111827">{{ $totalLogged }} logged event{{ $totalLogged !== 1 ? 's' : '' }}</span>
                <button onclick="hideLoggedCards()"
                        style="display:inline-flex;align-items:center;gap:6px;padding:6px 13px;border-radius:9px;border:1.5px solid #e5e7eb;color:#6b7280;font-size:12px;font-weight:700;background:#fff;cursor:pointer">
                    <i class="fa fa-arrow-left" style="font-size:11px"></i> Back
                </button>
            </div>
            <div style="max-height:320px;overflow-y:auto;display:grid;grid-template-columns:1fr;gap:10px;padding-right:4px">
                @foreach($pastApproved as $pr)
                @php
                    $prId      = (int)($pr['id'] ?? 0);
                    $prEt      = strtolower(trim($pr['event_type'] ?? ''));
                    $prColor   = match($prEt){ 'wedding'=>'#f472b6','baptism'=>'#2dd4bf','funeral'=>'#64748b',default=>'#94a3b8' };
                    $prDate    = $pr['preferred_date'] ?? $pr['reservation_date'] ?? null;
                    $prDateFmt = $prDate ? (new DateTimeImmutable((string)$prDate))->format('M j, Y') : '—';
                    $prAtt     = $attendanceLookup[$prId] ?? null;
                @endphp
                @if($prAtt)
                <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:12px">
                    <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;background:{{ $prColor }}18;color:{{ $prColor }};border:1px solid {{ $prColor }}40;flex-shrink:0">
                        {{ ucfirst($prEt) }}
                    </span>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $pr['name'] ?? '—' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $prDateFmt }}</div>
                    </div>
                    <span style="font-size:22px;font-weight:800;color:#dc2626;line-height:1;flex-shrink:0">{{ $prAtt['attended_count'] }}</span>
                    <button type="button"
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
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:16px">
        <button onclick="filterAtt('all')"     id="attf-all"     class="att-filter-btn"
                data-default-border="#e5e7eb" data-default-color="#6b7280">All</button>
        <button onclick="filterAtt('wedding')" id="attf-wedding" class="att-filter-btn"
                style="color:#f472b6;border-color:#f472b6;"
                data-default-border="#f472b6" data-default-color="#f472b6">Wedding</button>
        <button onclick="filterAtt('baptism')" id="attf-baptism" class="att-filter-btn"
                style="color:#2dd4bf;border-color:#2dd4bf;"
                data-default-border="#2dd4bf" data-default-color="#2dd4bf">Baptism</button>
        <button onclick="filterAtt('funeral')" id="attf-funeral" class="att-filter-btn"
                style="color:#64748b;border-color:#64748b;"
                data-default-border="#64748b" data-default-color="#64748b">Funeral</button>
    </div>

    <div id="attEmptyMsg" style="display:none;padding:24px;text-align:center;color:#94a3b8;font-size:13px">No unlogged events match the selected filter.</div>
    <div id="attPagination" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;padding:12px 4px 4px;margin-top:8px;border-top:1px solid #f1f5f9"></div>

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
                <tr style="border-bottom:1px solid #f8fafc" class="att-row att-unlogged" data-type="{{ $prEt }}">
                    <td style="padding:10px 12px">
                        <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;background:{{ $prColor }}18;color:{{ $prColor }};border:1px solid {{ $prColor }}40">
                            {{ ucfirst($prEt) }}
                        </span>
                    </td>
                    <td style="padding:10px 12px;font-weight:600;color:#111827">{{ $pr['name'] ?? '—' }}</td>
                    <td style="padding:10px 12px;color:#6b7280">{{ $prDateFmt }}</td>
                    <td style="padding:10px 12px">
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
<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:20px;border:0;overflow:hidden">
            <form method="POST" action="{{ route('admin.attendance.store') }}">
                @csrf
                <input type="hidden" name="reservation_id" id="attResId">
                <input type="hidden" name="event_type"     id="attEventType">
                <div class="modal-header" style="background:#f8fafc;border-bottom:1px solid #f1f5f9;padding:20px 24px">
                    <div>
                        <h5 style="font-weight:800;color:#7f1d1d;margin:0" id="attModalTitle">Log Attendance</h5>
                        <small style="color:#64748b" id="attModalSub"></small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" style="padding:24px;display:flex;flex-direction:column;gap:16px">
                    <div>
                        <label style="font-size:13px;font-weight:700;color:#374151;display:block;margin-bottom:6px">
                            Number of attendees <span style="color:#ef4444">*</span>
                        </label>
                        <input type="number" name="attended_count" id="attCount" min="0" required
                               style="width:100%;padding:10px 14px;border-radius:10px;border:1.5px solid #e5e7eb;font-size:16px;font-weight:700;box-sizing:border-box;outline:none"
                               placeholder="e.g. 120">
                    </div>
                    <div>
                        <label style="font-size:13px;font-weight:700;color:#374151;display:block;margin-bottom:6px">Notes <span style="color:#9ca3af;font-weight:400">(optional)</span></label>
                        <textarea name="notes" id="attNotes" rows="2" maxlength="300"
                                  style="width:100%;padding:10px 14px;border-radius:10px;border:1.5px solid #e5e7eb;font-size:13px;resize:none;box-sizing:border-box;outline:none"
                                  placeholder="e.g. Outdoor overflow area was used"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:16px 24px;gap:10px">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" style="padding:9px 22px;border-radius:10px;background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;font-weight:700;font-size:14px;border:none;cursor:pointer">
                        <i class="fa fa-save"></i> Save attendance
                    </button>
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
        <button class="btn btn-sm" style="background:#f1f5f9;color:#dc2626;border:1px solid #fee2e2;font-weight:600;white-space:nowrap;flex-shrink:0" onclick="togglePriestManager()">
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
    <div id="priestManager" style="display:none;margin-top:20px;border-top:1px solid #f1f5f9;padding-top:20px">
        <h4 style="font-size:14px;font-weight:700;color:#374151;margin:0 0 12px">Priest Roster</h4>
        <form method="POST" action="{{ route('admin.priests.store') }}" style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap">
            @csrf
            <select name="title" style="padding:7px 10px;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;color:#374151;background:#fff">
                <option value="Fr.">Fr.</option>
                <option value="Rev.">Rev.</option>
                <option value="Deacon">Deacon</option>
                <option value="Msgr.">Msgr.</option>
            </select>
            <input type="text" name="name" placeholder="Priest name" required
                style="flex:1;min-width:150px;padding:7px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:13px">
            <button type="submit" class="btn btn-primary btn-sm" style="white-space:nowrap">
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
                    <form method="POST" action="{{ route('admin.priests.delete', $p['id']) }}" onsubmit="return confirm('Remove this priest from the roster?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:14px;padding:2px 6px">
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
                    <tr style="border-bottom:1px solid #f1f5f9;{{ $mt === 0 ? 'color:#cbd5e1' : '' }}">
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
    <div id="dataPagination" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;padding:12px 4px 4px;margin-top:8px;border-top:1px solid #f1f5f9"></div>
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
    const rows = Array.from(document.querySelectorAll('.att-row'));
    // First hide all
    rows.forEach(row => row.style.display = 'none');
    // Filter to matching
    const filtered = rows.filter(row => {
        const matchType   = attCurrentType === 'all' || row.dataset.type === attCurrentType;
        const matchLogged = !unloggedOnly || row.classList.contains('att-unlogged');
        return matchType && matchLogged;
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
    var rows  = Array.from(tbody.querySelectorAll('tr'));
    var total = rows.length;
    var pages = Math.ceil(total / dataPerPage) || 1;
    if (dataPage > pages) dataPage = pages;
    var start = (dataPage - 1) * dataPerPage;
    var end   = Math.min(start + dataPerPage, total);

    rows.forEach(function(row, i) {
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
    }, 200);
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
    $('#attendanceModal').modal('show');
}
</script>
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
    const catSel = document.getElementById('edit_ann_category');
    catSel.value = category || '';
    // current image
    const imgWrap = document.getElementById('edit_ann_current_img');
    const imgEl   = document.getElementById('edit_ann_current_img_el');
    if (imgUrl) { imgEl.src = imgUrl; imgWrap.style.display = 'block'; }
    else { imgWrap.style.display = 'none'; }
    // reset new image preview
    const prev = document.getElementById('ann-img-preview-edit');
    if (prev) prev.style.display = 'none';
    // show modal
    const modal = document.getElementById('annEditModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function annCloseEdit() {
    document.getElementById('annEditModal').style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') annCloseEdit(); });
</script>

{{-- ==================== CUSTOMERS ==================== --}}
@if($section === 'customers')
@php
    $allCustomers   = $customers ?? [];
    $activeCount    = count(array_filter($allCustomers, fn($c) => ($c['status'] ?? 'active') === 'active'));
    $disabledCount  = count($allCustomers) - $activeCount;
@endphp
    <section class="section-card">
        <div class="section-header">
            <div><h2>Manage Customers</h2><p>Review accounts, reservation activity, and account status.</p></div>
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
                <span class="cust-result-count">{{ count($allCustomers) }} result{{ count($allCustomers) !== 1 ? 's' : '' }}</span>
            </div>
        </form>

        @if(empty($allCustomers))
            <p class="empty-block">No customers found.</p>
        @else
            <div class="cust-grid">
                @foreach($allCustomers as $c)
                @php
                    $cStatus  = ($c['status'] ?? 'active') === 'active' ? 'active' : 'disabled';
                    $cInitial = strtoupper(substr($c['name'] ?? 'C', 0, 1));
                    $cRes     = $c['total_reservations'] ?? 0;
                @endphp
                <div class="cust-card customer-search-item"
                     data-customer-search="{{ strtolower(($c['name'] ?? '') . ' ' . ($c['email'] ?? '') . ' ' . ($c['phone'] ?? '')) }}">

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
                    </div>

                    <div class="cust-footer">
                        <button type="button" class="cust-btn view" data-toggle="modal" data-target="#customerModal{{ $c['id'] }}">
                            <i class="fa fa-user"></i> Profile
                        </button>

                        @if($cStatus === 'active')
                            <button type="button" class="cust-btn disable" data-toggle="modal" data-target="#disableCustomerModal{{ $c['id'] }}">
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

                {{-- Disable modal --}}
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
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <label class="customer-disable-label">Reason for disabling</label>
                                    <textarea name="disabled_reason" class="form-control customer-disable-textarea" rows="3"
                                              placeholder="e.g. Incomplete documents, suspicious activity…" required></textarea>
                                </div>
                                <div class="modal-footer customer-disable-footer">
                                    <button type="button" class="btn btn-light customer-action-btn" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger customer-action-btn"><i class="fa fa-ban"></i> Disable Account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Profile modal --}}
                <div class="modal fade" id="customerModal{{ $c['id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;border:0;overflow:hidden;">
                            <button type="button" class="close" data-dismiss="modal"
                                    style="position:absolute;top:16px;right:20px;z-index:10;font-size:22px;opacity:0.5;">
                                <span>&times;</span>
                            </button>

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
                            <div style="margin:0 24px 0;padding:12px 16px;background:rgba(239,68,68,0.07);border-radius:10px;border:1px solid rgba(239,68,68,0.18);font-size:13px;color:#991b1b;">
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
            </div>
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
    @if($errors->has('donation'))
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:10px;padding:12px 16px;color:#dc2626;font-size:13px;margin-bottom:16px">
            {{ $errors->first('donation') }}
        </div>
    @endif
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
            <select name="purpose" id="purposeSelect" onchange="toggleCustomPurpose(this.value)"
                    style="padding:9px 38px 9px 13px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.22);font-size:14px;background:#fff url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E&quot;) no-repeat right 12px center;appearance:none;width:100%;box-sizing:border-box">
                <option value="building_fund"  {{ old('purpose')==='building_fund'  ? 'selected':'' }}>Building Fund</option>
                <option value="mass_intention" {{ old('purpose')==='mass_intention' ? 'selected':'' }}>Mass Intention</option>
                <option value="tithes"         {{ old('purpose')==='tithes'         ? 'selected':'' }}>Tithes</option>
                <option value="other"          {{ old('purpose')==='other'          ? 'selected':'' }}>Other</option>
            </select>
            <input type="text" name="custom_purpose" id="customPurposeInput"
                   value="{{ old('custom_purpose') }}"
                   placeholder="Specify purpose..."
                   style="display:{{ old('purpose')==='other' ? 'block' : 'none' }};margin-top:6px;padding:9px 13px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.35);font-size:14px;width:100%;box-sizing:border-box;outline:none">
        </div>
        <div style="display:flex;flex-direction:column;gap:5px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">Date Received</label>
            <input type="date" name="donation_date" required value="{{ old('donation_date', date('Y-m-d')) }}"
                   style="padding:9px 13px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.22);font-size:14px;width:100%;box-sizing:border-box">
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
            <select name="donation_purpose" style="padding:8px 38px 8px 13px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.22);font-size:14px;background:#fff url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E&quot;) no-repeat right 12px center;appearance:none">
                <option value="all" {{ $donationPurpose==='all' ? 'selected':'' }}>All purposes</option>
                <option value="building_fund"  {{ $donationPurpose==='building_fund'  ? 'selected':'' }}>Building Fund</option>
                <option value="mass_intention" {{ $donationPurpose==='mass_intention' ? 'selected':'' }}>Mass Intention</option>
                <option value="tithes"         {{ $donationPurpose==='tithes'         ? 'selected':'' }}>Tithes</option>
                <option value="other"          {{ $donationPurpose==='other'          ? 'selected':'' }}>Other</option>
            </select>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">From</label>
            <input type="date" name="donation_from" value="{{ $donationFrom }}"
                   style="padding:8px 13px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.22);font-size:14px">
        </div>
        <div style="display:flex;flex-direction:column;gap:4px">
            <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em">To</label>
            <input type="date" name="donation_to" value="{{ $donationTo }}"
                   style="padding:8px 13px;border-radius:10px;border:1.5px solid rgba(220,38,38,0.22);font-size:14px">
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
                                    <label for="announcement_category">Category <span>optional</span></label>
                                    <select class="form-control" id="announcement_category" name="announcement_category">
                                        <option value="">— No category —</option>
                                        <option value="Mass Schedule">Mass Schedule</option>
                                        <option value="Events">Events</option>
                                        <option value="Notice">Notice</option>
                                        <option value="Reminder">Reminder</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="announcement_image">Image <span>optional</span></label>
                                    <input type="file" class="form-control" id="announcement_image" name="announcement_image"
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
                                                <form method="POST" action="{{ route('admin.handle') }}"
                                                      onsubmit="return confirm('Delete this announcement? This cannot be undone.');">
                                                    @csrf
                                                    <input type="hidden" name="action" value="delete_announcement">
                                                    <input type="hidden" name="announcement_id" value="{{ $a->id }}">
                                                    <input type="hidden" name="redirect_section" value="announcements">
                                                    <button type="submit" class="ann-delete-btn">
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
                <div id="annEditModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,.55);backdrop-filter:blur(3px);align-items:center;justify-content:center;">
                    <div style="background:#fff;border-radius:20px;width:100%;max-width:560px;margin:20px;box-shadow:0 32px 80px rgba(15,23,42,.22);overflow:hidden;">
                        <div style="background:linear-gradient(135deg,#dc2626,#b91c1c);padding:18px 24px;display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="color:#fff;font-weight:700;font-size:15px;"><i class="fa fa-pencil"></i> Edit Announcement</div>
                                <div style="color:rgba(255,255,255,.7);font-size:12px;margin-top:2px;">Changes will update immediately on the home page if set to Live.</div>
                            </div>
                            <button onclick="annCloseEdit()" style="background:rgba(255,255,255,.15);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;">&times;</button>
                        </div>
                        <form method="POST" action="{{ route('admin.handle') }}" enctype="multipart/form-data" style="padding:24px;display:flex;flex-direction:column;gap:14px;max-height:75vh;overflow-y:auto;">
                            @csrf
                            <input type="hidden" name="action" value="edit_announcement">
                            <input type="hidden" name="redirect_section" value="announcements">
                            <input type="hidden" name="announcement_id" id="edit_ann_id">

                            <div>
                                <label style="font-size:13px;font-weight:600;color:#374151;display:flex;justify-content:space-between;margin-bottom:5px;">
                                    Title <span id="edit-title-count" style="font-weight:400;color:#9ca3af;font-size:12px;">0 / 150</span>
                                </label>
                                <input type="text" name="announcement_title" id="edit_ann_title" maxlength="150" required
                                    style="width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;"
                                    oninput="document.getElementById('edit-title-count').textContent=this.value.length+' / 150'">
                            </div>

                            <div>
                                <label style="font-size:13px;font-weight:600;color:#374151;display:flex;justify-content:space-between;margin-bottom:5px;">
                                    Message <span id="edit-body-count" style="font-weight:400;color:#9ca3af;font-size:12px;">0 / 500</span>
                                </label>
                                <textarea name="announcement_body" id="edit_ann_body" maxlength="500" rows="5" required
                                    style="width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;resize:vertical;"
                                    oninput="document.getElementById('edit-body-count').textContent=this.value.length+' / 500'"></textarea>
                            </div>

                            <div>
                                <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:5px;display:block;">Category <span style="font-weight:400;color:#9ca3af;font-size:12px;">optional</span></label>
                                <select name="announcement_category" id="edit_ann_category"
                                    style="width:100%;padding:9px 36px 9px 12px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;appearance:none;-webkit-appearance:none;background-image:url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'8\' viewBox=\'0 0 12 8\'%3E%3Cpath d=\'M1 1l5 5 5-5\' stroke=\'%236b7280\' stroke-width=\'1.5\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/%3E%3C/svg%3E');background-repeat:no-repeat;background-position:right 14px center;">
                                    <option value="">— No category —</option>
                                    <option value="Mass Schedule">Mass Schedule</option>
                                    <option value="Events">Events</option>
                                    <option value="Notice">Notice</option>
                                    <option value="Reminder">Reminder</option>
                                </select>
                            </div>

                            <div>
                                <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:5px;display:block;">Replace image <span style="font-weight:400;color:#9ca3af;font-size:12px;">optional — leave blank to keep current</span></label>
                                <div id="edit_ann_current_img" style="margin-bottom:8px;display:none;">
                                    <img id="edit_ann_current_img_el" src="" alt="Current" style="width:100%;max-height:120px;object-fit:cover;border-radius:10px;border:1px solid #e5e7eb;">
                                </div>
                                <input type="file" name="announcement_image" accept="image/jpeg,image/png,image/gif,image/webp"
                                    style="width:100%;padding:8px 12px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:13px;"
                                    onchange="annPreviewImage(this,'ann-img-preview-edit')">
                                <div class="ann-img-preview" id="ann-img-preview-edit"><img src="" alt="Preview"></div>
                            </div>

                            <label style="display:flex;align-items:center;gap:10px;background:#f8fafc;border:1.5px solid #e5e7eb;border-radius:10px;padding:10px 14px;cursor:pointer;">
                                <input type="checkbox" name="announcement_show" id="edit_ann_show" value="1" style="width:16px;height:16px;accent-color:#dc2626;">
                                <div>
                                    <span style="font-size:13px;font-weight:600;color:#374151;display:block;">Show on home page</span>
                                    <span style="font-size:11px;color:#6b7280;">Visitors will see this announcement immediately</span>
                                </div>
                            </label>

                            <div style="display:flex;gap:10px;padding-top:4px;">
                                <button type="button" onclick="annCloseEdit()"
                                    style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:10px;font-weight:600;font-size:14px;background:#fff;cursor:pointer;color:#374151;">
                                    Cancel
                                </button>
                                <button type="submit"
                                    style="flex:2;padding:10px;border:none;border-radius:10px;font-weight:700;font-size:14px;background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;cursor:pointer;">
                                    <i class="fa fa-save"></i> Save changes
                                </button>
                            </div>
                        </form>
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

    const eventTypeColors = {
        wedding: '#f472b6',
        baptism: '#2dd4bf',
        funeral: '#64748b',
    };

    function getEventColor(eventType) {
        return eventTypeColors[(eventType || '').toLowerCase()] || '#94a3b8';
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

    function renderDetails(dateKey) {
        const reservations = reservationsByDate[dateKey] || [];
        const done = isDoneDate(dateKey);
        const daysLabel = daysUntilLabel(dateKey);

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
            const color = getEventColor(r.eventType);
            const etLabel = r.eventType ? (r.eventType.charAt(0).toUpperCase() + r.eventType.slice(1).toLowerCase()) : 'Unknown';
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

                    <div class="admin-detail-tags">
                        <span class="admin-ev-type-badge" style="background:${color}18;color:${color};border:1px solid ${color}40;">
                            ${escapeHtml(etLabel)}
                        </span>
                        <span class="admin-days-badge ${done ? 'is-done' : ''}">${escapeHtml(daysLabel)}</span>
                    </div>

                    <div class="admin-detail-meta">
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

            // Build day content: number + colored dots per event type
            let dotsHtml = '';
            if (reservations.length) {
                const seen = {};
                reservations.forEach(function (r) {
                    const et = (r.eventType || '').toLowerCase();
                    if (!seen[et]) {
                        seen[et] = true;
                        const c = getEventColor(r.eventType);
                        dotsHtml += `<span class="cal-ev-dot" style="background:${c};"></span>`;
                    }
                });
                dotsHtml = `<span class="cal-dots">${dotsHtml}</span>`;
            }

            div.innerHTML = `<span class="day_number">${day}</span>${dotsHtml}`;
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