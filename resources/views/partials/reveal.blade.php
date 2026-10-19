{{--
    Page entrance animation, matching the Schedule page: elements with
    .ps-reveal fade in and slide up. Hero items animate on load (staggered
    by data-reveal-delay, in ms); the rest animate when scrolled into view.
    Include once near the end of <body>. The start-state styles live in
    <head> (partials/modal-theme-css) so nothing flashes before animating.
--}}
<script>
(function () {
    var items = Array.prototype.slice.call(document.querySelectorAll('.ps-reveal'));
    if (!items.length) return;
    function show(el) {
        setTimeout(function () { el.classList.add('is-visible'); }, Number(el.dataset.revealDelay || 0));
    }
    if (!('IntersectionObserver' in window)) { items.forEach(show); return; }
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) { show(entry.target); observer.unobserve(entry.target); }
        });
    }, { threshold: 0.12 });
    items.forEach(function (el) { observer.observe(el); });
})();
</script>
