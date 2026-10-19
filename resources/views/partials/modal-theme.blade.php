{{-- Light parish modal theme. Include last in <body> so it overrides each page's dark modal styles. --}}
<link rel="stylesheet" href="{{ asset('css/parish-modal-light.css') }}?v={{ file_exists(public_path('css/parish-modal-light.css')) ? filemtime(public_path('css/parish-modal-light.css')) : 1 }}">
<script>
// Lock the page behind any open parish modal (alerts, confirmations, form
// modals, View details) so only the modal itself can scroll. Watches the
// DOM, so it covers modals opened by any script on the page.
(function () {
    var SELECTOR = '.ps-overlay, .dcm-overlay, #adm-cfm-overlay, #rdm-modal, #purge-confirm-overlay, #cancel-overlay-panel';
    var root = document.documentElement;
    var queued = false;

    function isShown(el) {
        if (!el.isConnected) return false;
        var cs = getComputedStyle(el);
        return cs.display !== 'none' && cs.visibility !== 'hidden' && el.getClientRects().length > 0;
    }

    function update() {
        queued = false;
        var open = Array.prototype.some.call(document.querySelectorAll(SELECTOR), isShown);
        if (open === root.classList.contains('ps-scroll-lock')) return;
        if (open) {
            // Keep the page from shifting when the scrollbar disappears.
            var gap = window.innerWidth - root.clientWidth;
            root.style.setProperty('--ps-scrollbar-gap', gap + 'px');
        }
        root.classList.toggle('ps-scroll-lock', open);
    }

    function schedule() {
        if (!queued) { queued = true; requestAnimationFrame(update); }
    }

    new MutationObserver(schedule).observe(document.body, {
        childList: true, subtree: true, attributes: true, attributeFilter: ['style', 'class']
    });
    document.addEventListener('DOMContentLoaded', schedule);
    window.addEventListener('load', schedule);
    schedule();
})();
</script>
