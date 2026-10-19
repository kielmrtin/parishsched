{{--
    Page banner in the My Reservations style: kicker line, large title,
    description, square uppercase buttons and a wave edge. The text fades up
    on load (partials/reveal, included by the page).

    @include('partials.page-hero', [
        'image'   => asset('img/banner/inquire.webp'),
        'title'   => 'Inquire',
        'desc'    => 'Short description…',
        'buttons' => [['label' => 'Send a message', 'href' => '#contact-form', 'icon' => 'fa-paper-plane', 'primary' => true], …],
        'meta'    => ['3 sacrament types', …],   // optional small facts under the buttons
        'wave'    => '#f4f7fb',                  // colour of the section below
        'position'=> 'center',                   // background-position of the photo
    ])
--}}
@once
<style>
    .page-hero { background: linear-gradient(rgba(0,0,0,.52), rgba(0,0,0,.48)), var(--page-hero-img) var(--page-hero-pos, center)/cover no-repeat; padding: 72px 0 0; color: #fff; position: relative; }
    .page-hero-body { padding-bottom: 56px; }
    .page-hero-wave { display: block; width: 100%; line-height: 0; }
    .page-hero-wave svg { display: block; width: 100%; }
    .page-hero-kicker { display: inline-flex; align-items: center; gap: .5rem; font-size: .78rem; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: rgba(255,255,255,.75); margin-bottom: 1rem; }
    .page-hero-kicker::before, .page-hero-kicker::after { content: ''; display: inline-block; width: 32px; height: 1px; background: rgba(255,255,255,.5); }
    .page-hero h1 { font-size: 3rem; font-weight: 800; line-height: 1.15; margin: 0 0 1rem; color: #fff; text-align: left; max-width: 780px; text-wrap: balance; }
    .page-hero .page-hero-desc { font-size: 1.05rem; color: rgba(255,255,255,.82); max-width: 560px; margin: 0 0 2rem; line-height: 1.7; text-align: left; }
    .page-hero-actions { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
    .page-hero-btn { display: inline-flex; align-items: center; gap: .6rem; font-weight: 400; font-size: .85rem; letter-spacing: 2.5px; text-transform: uppercase; text-decoration: none !important; transition: background .2s, border-color .2s, transform .15s; }
    .page-hero-btn.is-primary { background: #dc2626; color: #fff !important; padding: .85rem 2rem; border: none; }
    .page-hero-btn.is-primary:hover { background: #b91c1c; transform: translateY(-1px); }
    .page-hero-btn.is-outline { background: transparent; color: #fff !important; padding: .83rem 1.8rem; border: 1px solid rgba(255,255,255,.7); }
    .page-hero-btn.is-outline:hover { border-color: #fff; background: rgba(255,255,255,.08); }
    .page-hero-meta { display: flex; flex-wrap: wrap; gap: 6px 18px; margin-top: 1.6rem; font-size: .8rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: rgba(255,255,255,.7); }
    .page-hero-meta span { display: inline-flex; align-items: center; gap: 7px; }
    .page-hero-meta span::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #dc2626; }
    @media (max-width: 860px) { .page-hero h1 { font-size: 2.2rem; } }
    @media (max-width: 576px) { .page-hero h1 { font-size: 1.6rem; } }
</style>
@endonce
<div class="page-hero" style="--page-hero-img: url('{{ $image }}'); --page-hero-pos: {{ $position ?? 'center' }};">
    <div class="container page-hero-body">
        <div class="page-hero-kicker ps-reveal" data-reveal-delay="0"><span>St. John the Baptist Parish</span></div>
        <h1 class="ps-reveal" data-reveal-delay="90">{{ $title }}</h1>
        <p class="page-hero-desc ps-reveal" data-reveal-delay="180">{{ $desc }}</p>
        @if(!empty($buttons))
        <div class="page-hero-actions ps-reveal" data-reveal-delay="270">
            @foreach($buttons as $btn)
                <a href="{{ $btn['href'] }}" class="page-hero-btn {{ !empty($btn['primary']) ? 'is-primary' : 'is-outline' }}" @if(!empty($btn['scroll'])) data-hero-scroll @endif>
                    @if(!empty($btn['icon']))<i class="fa {{ $btn['icon'] }}" aria-hidden="true"></i>@endif {{ $btn['label'] }}
                </a>
            @endforeach
        </div>
        @endif
        @if(!empty($meta))
        <div class="page-hero-meta ps-reveal" data-reveal-delay="340">
            @foreach($meta as $m)<span>{{ $m }}</span>@endforeach
        </div>
        @endif
    </div>
    <div class="page-hero-wave">
        <svg viewBox="0 0 1440 56" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,56 L0,28 Q360,0 720,28 Q1080,56 1440,28 L1440,56 Z" fill="{{ $wave ?? '#f4f7fb' }}"/>
        </svg>
    </div>
</div>
@once
<script>
// Buttons that jump to a section on the page stop just below the nav bar.
document.addEventListener('click', function (e) {
    var a = e.target.closest && e.target.closest('a[data-hero-scroll]');
    if (!a) return;
    var target = document.querySelector(a.getAttribute('href'));
    if (!target) return;
    e.preventDefault();
    var nav = document.getElementById('sticky-header');
    window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - (nav ? nav.offsetHeight : 0) - 20, behavior: 'smooth' });
});
</script>
@endonce
