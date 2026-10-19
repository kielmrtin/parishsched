{{-- Light parish modal theme. Loaded in <head> so modals shown on page load (login, logout, flash messages) are white from the first paint. --}}
<link rel="stylesheet" href="{{ asset('css/parish-modal-light.css') }}?v={{ file_exists(public_path('css/parish-modal-light.css')) ? filemtime(public_path('css/parish-modal-light.css')) : 1 }}">
{{-- Entrance animation start state (partials/reveal). In <head> so the text
     never shows first and then jumps into the animation. --}}
<style>
    .ps-reveal { opacity: 0; transform: translateY(20px); transition: opacity .5s ease, transform .5s ease; }
    .ps-reveal.is-visible { opacity: 1; transform: none; }
    @media (prefers-reduced-motion: reduce) { .ps-reveal { opacity: 1; transform: none; transition: none; } }
</style>
<noscript><style>.ps-reveal { opacity: 1 !important; transform: none !important; }</style></noscript>
