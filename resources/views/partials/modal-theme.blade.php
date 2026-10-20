{{-- Modal behaviour (scroll lock, closing animation). The styles load in <head> via partials/modal-theme-css. --}}
<script>
// Shared behaviour for every parish modal (alerts, confirmations, form
// modals, View details), whichever script opens or closes them:
//  1. the page behind is locked while one is open (only the modal scrolls);
//  2. closing plays a short fade-and-shrink. Modals close in different ways
//     (removed, class removed, display:none), so a copy of the closing modal
//     is shown in its place and animated out.
(function () {
    var SELECTOR = '.ps-overlay, .dcm-overlay, #adm-cfm-overlay, #rdm-modal, #rdm-backdrop, #purge-confirm-overlay, #cancel-overlay-panel, #cancel-overlay-backdrop, .ps-help-overlay';
    var LEAVE_MS = 300;
    var root = document.documentElement;
    var shown = new Map(); // element -> its display value while open
    var queued = false;
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function isGhost(el) { return el.hasAttribute && el.hasAttribute('data-ps-ghost'); }

    function isShown(el) {
        if (!el.isConnected || isGhost(el)) return false;
        var cs = getComputedStyle(el);
        return cs.display !== 'none' && cs.visibility !== 'hidden' && el.getClientRects().length > 0;
    }

    function playClose(el, display) {
        if (reduceMotion) return;
        var ghost = el.cloneNode(true);
        ghost.setAttribute('data-ps-ghost', '');
        // The outer id stays (some modals are styled by it); the copy sits
        // after the original, so getElementById still finds the real one.
        ghost.querySelectorAll('[id]').forEach(function (n) { n.removeAttribute('id'); });
        ghost.querySelectorAll('script').forEach(function (n) { n.remove(); });
        ghost.removeAttribute('hidden');
        ghost.style.display = display;
        ghost.style.visibility = 'visible';
        ghost.style.pointerEvents = 'none';
        // The opening animations would replay on the copy (and hold the
        // backdrop fully visible), so switch them off for the copy and
        // everything in it; inline !important outranks every stylesheet rule.
        [ghost].concat(Array.prototype.slice.call(ghost.querySelectorAll('*'))).forEach(function (n) {
            n.style.setProperty('animation', 'none', 'important');
            n.style.setProperty('transition', 'none', 'important');
        });
        document.body.appendChild(ghost);
        if (!ghost.animate) { ghost.remove(); return; }
        // Backdrop and card fade together; the card also sinks and shrinks a little.
        var ease = 'cubic-bezier(.4, 0, .2, 1)';
        var fade = ghost.animate([{ opacity: 1 }, { opacity: 0 }], { duration: LEAVE_MS, easing: ease, fill: 'forwards' });
        var card = ghost.querySelector('.ps-modal, .adm-cfm-modal, .dcm-modal, .rdm-panel, .ps-help-dialog');
        if (card) card.animate([{ transform: 'none' }, { transform: 'translateY(10px) scale(.96)' }], { duration: LEAVE_MS, easing: ease, fill: 'forwards' });
        fade.onfinish = function () { ghost.remove(); };
        setTimeout(function () { ghost.remove(); }, LEAVE_MS + 200);   // in case the animation never finishes
    }

    function update() {
        queued = false;
        // Modals that closed since the last check get their exit animation.
        shown.forEach(function (display, el) {
            if (!isShown(el)) {
                shown.delete(el);
                playClose(el, display);
            }
        });
        document.querySelectorAll(SELECTOR).forEach(function (el) {
            if (!shown.has(el) && isShown(el)) shown.set(el, getComputedStyle(el).display);
        });

        var open = shown.size > 0;
        if (open !== root.classList.contains('ps-scroll-lock')) {
            if (open) root.style.setProperty('--ps-scrollbar-gap', (window.innerWidth - root.clientWidth) + 'px');
            root.classList.toggle('ps-scroll-lock', open);
        }
    }

    function schedule() {
        if (!queued) { queued = true; requestAnimationFrame(update); }
    }

    new MutationObserver(function (records) {
        // A modal removed from the page can't be measured any more, so copy
        // it right away while we still have it.
        records.forEach(function (r) {
            r.removedNodes.forEach(function (n) {
                if (n.nodeType === 1 && shown.has(n)) {
                    var display = shown.get(n);
                    shown.delete(n);
                    playClose(n, display);
                }
            });
        });
        if (records.some(function (r) { return !isGhost(r.target) && !(r.addedNodes.length === 1 && isGhost(r.addedNodes[0])); })) {
            schedule();
        }
    }).observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['style', 'class'] });

    document.addEventListener('DOMContentLoaded', schedule);
    window.addEventListener('load', schedule);
    schedule();
})();
</script>
