{{--
    Help "?" button (panel revision: Help Menu, Ma'am Imelda).
    Opens a centred help panel in the same style as View details.
    Include once near the end of <body>. Pass ['section' => $section] on the
    admin page so the help follows the open section and role.
--}}
@php $help = \App\Support\HelpTopics::forRequest($section ?? null); @endphp
@if($help)
<style>
    .ps-help-fab { position: fixed; right: 20px; bottom: calc(20px + env(safe-area-inset-bottom, 0px)); z-index: 9990; width: 48px; height: 48px; border-radius: 50%; border: 0; background: #dc2626; color: #fff; font: 800 21px/1 'Raleway', system-ui, sans-serif; box-shadow: 0 8px 22px rgba(220,38,38,.38); cursor: pointer; transition: transform .15s, background .15s; }
    .ps-help-fab:hover { background: #b91c1c; transform: translateY(-2px); }
    .ps-help-fab:focus-visible { outline: 3px solid #fca5a5; outline-offset: 3px; }

    .ps-help-overlay { position: fixed; inset: 0; z-index: 99990; display: flex; align-items: center; justify-content: center; padding: 20px; background: rgba(15,23,42,.45); font-family: 'Raleway', system-ui, sans-serif; animation: ps-help-fade .25s ease both; }
    .ps-help-overlay[hidden] { display: none !important; }
    @keyframes ps-help-fade { from { opacity: 0; } to { opacity: 1; } }
    @keyframes ps-help-pop { from { opacity: 0; transform: scale(.94) translateY(16px); } to { opacity: 1; transform: none; } }
    .ps-help-dialog { width: 100%; max-width: 560px; max-height: 88vh; display: flex; flex-direction: column; overflow: hidden; background: #fff; color: #0f172a; text-align: left; border-radius: 20px; border-top: 4px solid #dc2626; box-shadow: 0 24px 60px rgba(15,23,42,.22), 0 0 0 1px rgba(15,23,42,.06); animation: ps-help-pop .4s cubic-bezier(.34,1.56,.64,1) both; }

    .ps-help-hdr { display: flex; align-items: center; gap: 9px; padding: 14px 22px; border-bottom: 1px solid #f1f5f9; flex-shrink: 0; }
    .ps-help-hdr svg { width: 16px; height: 16px; flex-shrink: 0; }
    .ps-help-parish { font-size: .66rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #0f172a; white-space: nowrap; }
    .ps-help-dot { width: 4px; height: 4px; border-radius: 50%; background: #cbd5e1; flex-shrink: 0; }
    .ps-help-loc { font-size: .66rem; color: #94a3b8; white-space: nowrap; }
    .ps-help-x { margin-left: auto; width: 30px; height: 30px; border-radius: 50%; border: 0; background: #f1f5f9; color: #64748b; font-size: 20px; line-height: 1; cursor: pointer; flex-shrink: 0; }
    .ps-help-x:hover { background: #e2e8f0; color: #0f172a; }

    .ps-help-head { padding: 18px 22px 16px; flex-shrink: 0; display: flex; align-items: center; gap: 16px; }
    .ps-help-head-text { flex: 1; min-width: 0; }
    .ps-help-hero { width: 68px; height: 68px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 3px solid #fff; box-shadow: 0 0 0 2px #fecaca, 0 8px 20px rgba(220,38,38,.18); }
    .ps-help-eyebrow { font-size: 9px; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 700; color: #dc2626; margin-bottom: 8px; }
    .ps-help-title { font-size: 20px; font-weight: 800; color: #0f172a; margin: 0 0 3px; }
    .ps-help-sub { font-size: 12px; color: #94a3b8; margin: 0; }

    .ps-help-body { flex: 1; overflow-y: auto; min-height: 0; }
    .ps-help-section { padding: 16px 22px; border-top: 1px solid #f1f5f9; }
    .ps-help-sec-title { font-size: 9px; letter-spacing: .18em; text-transform: uppercase; font-weight: 800; color: #94a3b8; margin: 0 0 12px; }
    .ps-help-steps { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 8px; counter-reset: ps-step; }
    .ps-help-steps li { counter-increment: ps-step; display: grid; grid-template-columns: 26px minmax(0,1fr); gap: 12px; align-items: start; padding: 11px 14px; border-radius: 10px; background: #fef2f2; border: 1px solid #fecaca; font-size: 13px; line-height: 1.55; color: #334155; }
    .ps-help-topic { transition: border-color .25s ease, background .25s ease; }
    .ps-help-topic[open] { border-color: #fecaca; background: #fff; }
    .ps-help-steps li::before { content: counter(ps-step); width: 26px; height: 26px; border-radius: 8px; background: #fff; color: #dc2626; font-weight: 800; font-size: 12px; display: grid; place-items: center; border: 1px solid #fecaca; }

    .ps-help-topics { display: flex; flex-direction: column; gap: 8px; }
    .ps-help-topic { border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc; }
    .ps-help-topic summary { list-style: none; cursor: pointer; display: flex; align-items: center; gap: 10px; padding: 11px 14px; font-size: 13px; font-weight: 700; color: #0f172a; }
    .ps-help-topic summary::-webkit-details-marker { display: none; }
    .ps-help-topic summary::after { content: '›'; margin-left: auto; color: #94a3b8; font-size: 18px; line-height: 1; transition: transform .25s ease, color .25s ease; }
    .ps-help-topic.is-opening summary::after { transform: rotate(90deg); color: #dc2626; }
    .ps-help-topic.is-closing[open] summary::after { transform: none; color: #94a3b8; }
    .ps-help-topic-body { overflow: hidden; }
    .ps-help-topic[open] summary::after { transform: rotate(90deg); color: #dc2626; }
    .ps-help-topic summary:hover { color: #dc2626; }
    .ps-help-topic ol { margin: 0; padding: 0 14px 12px 34px; display: flex; flex-direction: column; gap: 6px; }
    .ps-help-topic li { font-size: 12.5px; line-height: 1.55; color: #475569; }

    .ps-help-footer { padding: 13px 22px; border-top: 1px solid #f1f5f9; flex-shrink: 0; display: flex; justify-content: flex-end; }
    .ps-help-close { padding: 10px 26px; border-radius: 999px; border: 1px solid #e2e8f0; background: #f8fafc; color: #475569; font: 700 .8rem 'Raleway', system-ui, sans-serif; cursor: pointer; }
    .ps-help-close:hover { background: #f1f5f9; color: #0f172a; }

    @media (max-width: 480px) {
        .ps-help-overlay { padding: 12px; }
        .ps-help-hero { width: 52px; height: 52px; }
        .ps-help-head, .ps-help-section, .ps-help-footer, .ps-help-hdr { padding-left: 16px; padding-right: 16px; }
        .ps-help-dot, .ps-help-loc { display: none; } /* keeps the close button on screen */
    }
    @media print { .ps-help-fab, .ps-help-overlay { display: none !important; } }
</style>

<button type="button" class="ps-help-fab" id="psHelpFab" aria-label="Help for this page" aria-haspopup="dialog" aria-controls="psHelpOverlay">?</button>

<div class="ps-help-overlay" id="psHelpOverlay" hidden>
    <div class="ps-help-dialog" role="dialog" aria-modal="true" aria-labelledby="psHelpTitle">
        <div class="ps-help-hdr">
            <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 1v18M4 7h12" stroke="#dc2626" stroke-width="2.2" stroke-linecap="round"/></svg>
            <span class="ps-help-parish">St. John the Baptist Parish</span>
            <span class="ps-help-dot"></span>
            <span class="ps-help-loc">Tiaong, Quezon</span>
            <button type="button" class="ps-help-x" data-help-close aria-label="Close help">&times;</button>
        </div>

        <div class="ps-help-head">
            <div class="ps-help-head-text">
                <div class="ps-help-eyebrow">Help · {{ $help['audience'] }}</div>
                <h2 class="ps-help-title" id="psHelpTitle">{{ $help['current']['title'] }}</h2>
                <p class="ps-help-sub">Steps for the page you are on.</p>
            </div>
            <img class="ps-help-hero" src="{{ asset('img/logo-160.webp') }}" alt="" width="68" height="68">
        </div>

        <div class="ps-help-body">
            <div class="ps-help-section">
                <div class="ps-help-sec-title">On this page</div>
                <ol class="ps-help-steps">
                    @foreach($help['current']['steps'] as $step)<li><span>{{ $step }}</span></li>@endforeach
                </ol>
            </div>

            @if(count($help['others']))
            <div class="ps-help-section">
                <div class="ps-help-sec-title">Other help topics</div>
                <div class="ps-help-topics">
                    @foreach($help['others'] as $topic)
                    <details class="ps-help-topic">
                        <summary>{{ $topic['title'] }}</summary>
                        <div class="ps-help-topic-body"><ol>@foreach($topic['steps'] as $step)<li>{{ $step }}</li>@endforeach</ol></div>
                    </details>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="ps-help-footer">
            <button type="button" class="ps-help-close" data-help-close>Close</button>
        </div>
    </div>
</div>

<script>
(function () {
    var fab = document.getElementById('psHelpFab');
    var overlay = document.getElementById('psHelpOverlay');
    if (!fab || !overlay) return;

    function open() {
        overlay.hidden = false;
        overlay.querySelector('.ps-help-x').focus();
    }
    function close() {
        if (overlay.hidden) return;
        overlay.hidden = true;
        fab.focus();
    }

    // Slide topics open and closed instead of snapping.
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    overlay.querySelectorAll('.ps-help-topic').forEach(function (topic) {
        var summary = topic.querySelector('summary');
        var body = topic.querySelector('.ps-help-topic-body');
        var anim = null;
        summary.addEventListener('click', function (e) {
            if (reduceMotion || !body.animate) return;
            e.preventDefault();
            var closing = topic.open && !topic.classList.contains('is-closing');
            if (anim) anim.cancel();
            topic.classList.toggle('is-closing', closing);
            topic.classList.toggle('is-opening', !closing);
            if (!closing) topic.open = true;
            var full = body.scrollHeight;
            var from = closing ? full : 0, to = closing ? 0 : full;
            anim = body.animate([{ height: from + 'px', opacity: closing ? 1 : 0 }, { height: to + 'px', opacity: closing ? 0 : 1 }],
                                { duration: 260, easing: 'cubic-bezier(.4,0,.2,1)', fill: 'forwards' });
            anim.onfinish = function () {
                // Close first, then drop the held last frame, so the full
                // height never flashes for a frame.
                if (closing) topic.open = false;
                topic.classList.remove('is-opening', 'is-closing');
                anim.cancel();
                anim = null;
            };
        });
    });

    fab.addEventListener('click', open);
    overlay.querySelectorAll('[data-help-close]').forEach(function (b) { b.addEventListener('click', close); });
    overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
})();
</script>
@endif
