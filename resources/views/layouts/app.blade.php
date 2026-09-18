<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'St. John the Baptist Parish')</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
    <meta name="description" content="@yield('description', 'St. John the Baptist Parish in Tiaong, Quezon — schedule Masses and reserve baptisms, weddings, and funerals online.')">
    <meta property="og:title" content="@yield('title', 'St. John the Baptist Parish')">
    <meta property="og:description" content="@yield('description', 'St. John the Baptist Parish in Tiaong, Quezon — schedule Masses and reserve baptisms, weddings, and funerals online.')">
    <meta property="og:image" content="{{ asset('img/cover.png') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(request()->routeIs('home'))
    <script>if ('scrollRestoration' in history) history.scrollRestoration = 'manual';</script>
    @endif
    @stack('preloads')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
<link rel="stylesheet" href="{{ asset('css/nice-select.css') }}">
<link rel="stylesheet" href="{{ asset('css/flaticon.css') }}">
<link rel="stylesheet" href="{{ asset('css/gijgo.css') }}">
<link rel="stylesheet" href="{{ asset('css/animate.css') }}">
<link rel="stylesheet" href="{{ asset('css/slicknav.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
.ps-overlay{position:fixed;inset:0;background:rgba(6,13,26,.75);backdrop-filter:blur(6px);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px}
.ps-modal{width:100%;max-width:440px;background:#0f172a;border-radius:22px;overflow:hidden;box-shadow:0 40px 90px rgba(0,0,0,.7),0 0 0 1px rgba(255,255,255,.07);animation:ps-pop .5s cubic-bezier(.34,1.56,.64,1) both;position:relative;font-family:'Raleway',system-ui,sans-serif}
@keyframes ps-pop{from{opacity:0;transform:scale(.88) translateY(28px)}to{opacity:1;transform:scale(1) translateY(0)}}
.ps-hdr{background:rgba(255,255,255,.04);border-bottom:1px solid rgba(255,255,255,.07);padding:16px 24px;display:flex;align-items:center;gap:9px;position:relative;overflow:hidden}
.ps-cross{width:18px;height:18px;flex-shrink:0}
.ps-parish{font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#fff;white-space:nowrap}
.ps-dot{width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.2);flex-shrink:0}
.ps-loc{font-size:.68rem;color:rgba(255,255,255,.32);letter-spacing:.04em;white-space:nowrap}
.ps-body{padding:40px 32px 36px;display:flex;flex-direction:column;align-items:center;text-align:center;position:relative;overflow:hidden}
.ps-glow{position:absolute;width:280px;height:280px;background:radial-gradient(circle,rgba(220,38,38,.13) 0%,transparent 70%);top:-70px;left:50%;transform:translateX(-50%);pointer-events:none;animation:ps-pulse 3s ease-in-out infinite}
@keyframes ps-pulse{0%,100%{opacity:.7;transform:translateX(-50%) scale(1)}50%{opacity:1;transform:translateX(-50%) scale(1.15)}}
.ps-particle{position:absolute;border-radius:50%;pointer-events:none;animation:ps-float 4s ease-in infinite;opacity:0}
@keyframes ps-float{0%{opacity:0;transform:translateY(0) scale(0)}20%{opacity:.45}100%{opacity:0;transform:translateY(-200px) scale(1.1)}}
.ps-icon{position:relative;width:82px;height:82px;margin-bottom:24px;z-index:2}
.ps-ring{position:absolute;inset:-5px;border-radius:50%;background:conic-gradient(rgba(220,38,38,.5) 0%,rgba(252,165,165,.3) 40%,transparent 58%);animation:ps-spin 3s linear infinite}
@keyframes ps-spin{to{transform:rotate(360deg)}}
.ps-inner{position:absolute;inset:0;border-radius:50%;background:rgba(220,38,38,.1);border:1.5px solid rgba(220,38,38,.22);display:flex;align-items:center;justify-content:center;animation:ps-ipop .5s .15s cubic-bezier(.34,1.56,.64,1) both;backdrop-filter:blur(8px)}
@keyframes ps-ipop{from{transform:scale(0)}to{transform:scale(1)}}
.ps-inner svg{width:38px;height:38px}
.ps-chk{stroke:#fca5a5;stroke-width:2.6;stroke-linecap:round;stroke-linejoin:round;fill:none;stroke-dasharray:50;stroke-dashoffset:50;animation:ps-draw .7s .45s cubic-bezier(.4,0,.2,1) forwards}
@keyframes ps-draw{to{stroke-dashoffset:0}}
.ps-title{font-size:1.5rem;font-weight:900;color:#fff;letter-spacing:-.03em;margin-bottom:9px;z-index:2;animation:ps-up .4s .5s both}
.ps-sub{font-size:.86rem;color:rgba(255,255,255,.45);line-height:1.68;max-width:270px;margin-bottom:22px;z-index:2;animation:ps-up .4s .6s both}
@keyframes ps-up{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
.ps-chip{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:30px;padding:8px 20px;font-size:.8rem;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:26px;z-index:2;display:flex;align-items:center;gap:8px;animation:ps-up .4s .7s both;backdrop-filter:blur(6px)}
.ps-chip-dot{width:8px;height:8px;border-radius:50%;background:#22c55e;flex-shrink:0;animation:ps-cpulse 2s ease-out .9s infinite}
@keyframes ps-cpulse{0%{box-shadow:0 0 0 0 rgba(34,197,94,.5)}70%{box-shadow:0 0 0 6px rgba(34,197,94,0)}100%{box-shadow:0 0 0 0 rgba(34,197,94,0)}}
.ps-btns{display:flex;gap:10px;width:100%;z-index:2;animation:ps-up .4s .8s both}
.ps-btn-out{flex:1;padding:13px;border-radius:11px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);font-family:'Raleway',system-ui,sans-serif;font-size:.86rem;font-weight:700;cursor:pointer;transition:all .15s}
.ps-btn-out:hover{background:rgba(255,255,255,.1);color:#fff}
.ps-btn-prim{flex:1.5;padding:13px;border-radius:11px;border:none;background:#dc2626;color:#fff;font-family:'Raleway',system-ui,sans-serif;font-size:.86rem;font-weight:800;cursor:pointer;box-shadow:0 6px 20px rgba(220,38,38,.4);transition:all .15s;letter-spacing:.02em}
.ps-btn-prim:hover{background:#b91c1c;transform:translateY(-1px)}
.ps-btn-solo{width:100%;padding:13px;border-radius:11px;border:none;background:#dc2626;color:#fff;font-family:'Raleway',system-ui,sans-serif;font-size:.86rem;font-weight:800;cursor:pointer;box-shadow:0 6px 20px rgba(220,38,38,.4);transition:all .15s}
.ps-btn-solo:hover{background:#b91c1c;transform:translateY(-1px)}
</style>
</head>

<body>
@if(session('auth_notification'))
@php
  $notif  = session('auth_notification');
  $isLogin = str_contains($notif['title'] ?? '', 'Login');
  $nType  = $notif['icon'] ?? 'error';
  $nCfg   = [
    'success' => ['glow'=>'rgba(34,197,94,.13)',  'ring'=>'rgba(34,197,94,.5),rgba(134,239,172,.3)',  'inner'=>'rgba(34,197,94,.1)',  'border'=>'rgba(34,197,94,.3)',  'stroke'=>'#4ade80', 'h1'=>'rgba(255,255,255,.25)','h2'=>'#4ade8066','h3'=>'#4ade804d','b1'=>'#4ade8066','b2'=>'#4ade8059','b3'=>'rgba(255,255,255,.18)'],
    'error'   => ['glow'=>'rgba(220,38,38,.13)',  'ring'=>'rgba(220,38,38,.5),rgba(252,165,165,.3)',  'inner'=>'rgba(220,38,38,.1)',  'border'=>'rgba(220,38,38,.3)',  'stroke'=>'#f87171', 'h1'=>'rgba(255,255,255,.25)','h2'=>'#f8717166','h3'=>'#f871714d','b1'=>'#f8717166','b2'=>'#f8717159','b3'=>'rgba(255,255,255,.18)'],
  ];
  $nc = $nCfg[$nType] ?? $nCfg['error'];
@endphp
<div class="ps-overlay" id="ps-overlay">
  <div class="ps-modal">
    <div class="ps-hdr" id="ps-hdr">
      <svg class="ps-cross" viewBox="0 0 20 20" fill="none"><path d="M10 1v18M4 7h12" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/></svg>
      <span class="ps-parish">St. John the Baptist Parish</span>
      <span class="ps-dot"></span>
      <span class="ps-loc">Tiaong, Quezon</span>
    </div>
    <div class="ps-body" id="ps-body">
      <div class="ps-glow" style="background:radial-gradient(circle,{{ $nc['glow'] }} 0%,transparent 70%)"></div>
      <div class="ps-icon">
        <div class="ps-ring" style="background:conic-gradient({{ $nc['ring'] }},transparent 58%)"></div>
        <div class="ps-inner" style="background:{{ $nc['inner'] }};border:1.5px solid {{ $nc['border'] }}">
          <svg viewBox="0 0 42 42">
            @if($nType === 'success')
              <polyline class="ps-chk" style="stroke:{{ $nc['stroke'] }}" points="9,22 17,30 33,12"/>
            @else
              <line class="ps-chk" style="stroke:{{ $nc['stroke'] }}" x1="13" y1="13" x2="29" y2="29"/>
              <line class="ps-chk" style="stroke:{{ $nc['stroke'] }}" x1="29" y1="13" x2="13" y2="29"/>
            @endif
          </svg>
        </div>
      </div>
      <h2 class="ps-title">{{ $notif['title'] }}</h2>
      <p class="ps-sub">{{ $notif['text'] }}</p>
      @if($isLogin && session('customer_name'))
      <div class="ps-chip"><span class="ps-chip-dot"></span>{{ session('customer_name') }}</div>
      <div class="ps-btns">
        <button class="ps-btn-out" onclick="document.getElementById('ps-overlay').remove()">Home</button>
        <button class="ps-btn-prim" onclick="window.location='{{ route('reservation.index') }}'">Reserve Now</button>
      </div>
      @else
      <div class="ps-btns" style="justify-content:center">
        <button class="ps-btn-solo" onclick="document.getElementById('ps-overlay').remove()">OK</button>
      </div>
      @endif
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const colors = ['{{ $nc['b1'] }}','{{ $nc['b2'] }}','{{ $nc['b3'] }}'];
  const hColors = ['{{ $nc['h1'] }}','{{ $nc['h2'] }}','{{ $nc['h3'] }}'];
  function spawnDots(el, cols, count) {
    for (let i = 0; i < count; i++) {
      const p = document.createElement('div');
      const s = Math.random()*6+4;
      p.className = 'ps-particle';
      p.style.cssText = `width:${s}px;height:${s}px;background:${cols[Math.floor(Math.random()*cols.length)]};left:${Math.random()*100}%;bottom:${Math.random()*25}%;animation-delay:${Math.random()*4}s;animation-duration:${2.5+Math.random()*2}s;`;
      el.appendChild(p);
    }
  }
  spawnDots(document.getElementById('ps-hdr'), hColors, 10);
  spawnDots(document.getElementById('ps-body'), colors, 20);
  document.getElementById('ps-overlay').addEventListener('click', function(e) {
    if (false) this.remove();
  });
});
</script>
@endif

    @include('partials.header')

    @yield('content')

    @include('partials.footer')

    <!-- JS -->
<script src="{{ asset('js/vendor/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/vendor/modernizr-3.5.0.min.js') }}"></script>
<script src="{{ asset('js/vendor/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('js/ajax-form.js') }}"></script>
<script src="{{ asset('js/waypoints.min.js') }}"></script>
<script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('js/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('js/scrollIt.js') }}"></script>
<script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>
<script src="{{ asset('js/wow.min.js') }}"></script>
<script src="{{ asset('js/nice-select.min.js') }}"></script>
<script src="{{ asset('js/jquery.slicknav.min.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/plugins.js') }}"></script>
<script src="{{ asset('js/gijgo.min.js') }}"></script>

<!-- contact js -->
<script src="{{ asset('js/contact.js') }}"></script>
<script src="{{ asset('js/jquery.ajaxchimp.min.js') }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/mail-script.js') }}"></script>

@if (!request()->is('reservation'))
    <script src="{{ asset('js/main.js') }}"></script>
@endif


@stack('scripts')
</body>
</html>