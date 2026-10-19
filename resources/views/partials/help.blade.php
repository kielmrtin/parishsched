{{--
    Help "?" button (panel revision: Help Menu, Ma'am Imelda).
    Include once near the end of <body>. Pass ['section' => $section] on the
    admin page so the help follows the open section and role.
--}}
@php $help = \App\Support\HelpTopics::forRequest($section ?? null); @endphp
@if($help)
<style>
    .ps-help-fab { position: fixed; right: 20px; bottom: calc(20px + env(safe-area-inset-bottom, 0px)); z-index: 9990; width: 48px; height: 48px; border-radius: 50%; border: 0; background: #dc2626; color: #fff; font: 800 21px/1 'Raleway', system-ui, sans-serif; box-shadow: 0 8px 22px rgba(220,38,38,.38); cursor: pointer; transition: transform .15s, background .15s; }
    .ps-help-fab:hover { background: #b91c1c; transform: translateY(-2px); }
    .ps-help-fab:focus-visible { outline: 3px solid #fca5a5; outline-offset: 3px; }
    .ps-help-panel { position: fixed; right: 20px; bottom: calc(80px + env(safe-area-inset-bottom, 0px)); z-index: 9991; width: min(340px, calc(100vw - 40px)); max-height: min(70vh, 560px); overflow-y: auto; background: #fff; color: #0f172a; border-radius: 16px; border-top: 4px solid #dc2626; box-shadow: 0 24px 60px rgba(15,23,42,.22), 0 0 0 1px rgba(15,23,42,.06); font-family: 'Raleway', system-ui, sans-serif; text-align: left; }
    .ps-help-panel[hidden] { display: none !important; }
    .ps-help-hdr { display: flex; align-items: center; gap: 8px; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; font-size: .66rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #94a3b8; }
    .ps-help-hdr b { color: #dc2626; }
    .ps-help-close { margin-left: auto; background: none; border: 0; color: #94a3b8; font-size: 20px; line-height: 1; cursor: pointer; padding: 0 2px; }
    .ps-help-body { padding: 14px 18px 16px; }
    .ps-help-body h4 { color: #0f172a; font-size: .98rem; font-weight: 800; margin: 0 0 10px; }
    .ps-help-body ol { margin: 0; padding-left: 18px; display: flex; flex-direction: column; gap: 7px; }
    .ps-help-body li { font-size: .82rem; line-height: 1.5; color: #475569; list-style: decimal; }
    .ps-help-more { margin-top: 14px; border-top: 1px solid #f1f5f9; padding-top: 10px; }
    .ps-help-more summary { cursor: pointer; font-size: .78rem; font-weight: 700; color: #dc2626; list-style: none; }
    .ps-help-more summary::-webkit-details-marker { display: none; }
    .ps-help-more details details { margin-top: 8px; }
    .ps-help-more details details summary { color: #0f172a; font-weight: 700; font-size: .8rem; }
    .ps-help-more details details ol { margin-top: 6px; }
    @media print { .ps-help-fab, .ps-help-panel { display: none !important; } }
</style>

<button type="button" class="ps-help-fab" id="psHelpFab" aria-label="Help for this page" aria-expanded="false" aria-controls="psHelpPanel">?</button>

<div class="ps-help-panel" id="psHelpPanel" role="dialog" aria-label="Help" hidden>
    <div class="ps-help-hdr">
        <b>Help</b> · {{ $help['audience'] }}
        <button type="button" class="ps-help-close" id="psHelpClose" aria-label="Close help">&times;</button>
    </div>
    <div class="ps-help-body">
        <h4>{{ $help['current']['title'] }}</h4>
        <ol>
            @foreach($help['current']['steps'] as $step)<li>{{ $step }}</li>@endforeach
        </ol>

        @if(count($help['others']))
        <div class="ps-help-more">
            <details>
                <summary>All help topics ({{ count($help['others']) }}) →</summary>
                @foreach($help['others'] as $topic)
                <details>
                    <summary>{{ $topic['title'] }}</summary>
                    <ol>@foreach($topic['steps'] as $step)<li>{{ $step }}</li>@endforeach</ol>
                </details>
                @endforeach
            </details>
        </div>
        @endif
    </div>
</div>

<script>
(function () {
    var fab = document.getElementById('psHelpFab');
    var panel = document.getElementById('psHelpPanel');
    if (!fab || !panel) return;
    function setOpen(open) {
        panel.hidden = !open;
        fab.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) document.getElementById('psHelpClose').focus();
    }
    fab.addEventListener('click', function () { setOpen(panel.hidden); });
    document.getElementById('psHelpClose').addEventListener('click', function () { setOpen(false); fab.focus(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !panel.hidden) { setOpen(false); fab.focus(); } });
    document.addEventListener('click', function (e) {
        if (!panel.hidden && !panel.contains(e.target) && e.target !== fab) setOpen(false);
    });
})();
</script>
@endif
