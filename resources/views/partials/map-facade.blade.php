{{--
    Click-to-load Google Map (panel revision: load fast on any network).
    The embedded map pulls about 1.6 MB of Google scripts, so it only loads
    when the visitor asks for it. Pass ['height' => 380].
--}}
@php
    $mapHeight = $height ?? 320;
    $mapQuery  = 'St. John The Baptist Parish Tiaong Quezon';
    $mapEmbed  = 'https://maps.google.com/maps?q=' . rawurlencode($mapQuery) . '&t=&z=15&ie=UTF8&iwloc=&output=embed';
    $mapLink   = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($mapQuery);
@endphp
<div class="ps-map" style="height:{{ $mapHeight }}px" data-embed="{{ $mapEmbed }}">
    <div class="ps-map-face">
        <i class="fa fa-map-marker" aria-hidden="true"></i>
        <div class="ps-map-name">St. John the Baptist Parish</div>
        <div class="ps-map-addr">Tiaong, Quezon, Philippines</div>
        <div class="ps-map-btns">
            <button type="button" class="ps-map-show">Show map</button>
            <a class="ps-map-open" href="{{ $mapLink }}" target="_blank" rel="noopener">Open in Google Maps</a>
        </div>
    </div>
</div>
@once
<style>
    .ps-map { position: relative; width: 100%; border-radius: 12px; overflow: hidden; background: linear-gradient(135deg, #eef2f7 0%, #e2e8f0 100%); }
    .ps-map iframe { display: block; width: 100%; height: 100%; border: 0; }
    .ps-map-face { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; padding: 16px; text-align: center; font-family: 'Raleway', system-ui, sans-serif; }
    .ps-map-face .fa { font-size: 34px; color: #dc2626; margin-bottom: 6px; }
    .ps-map-name { font-weight: 800; color: #0f172a; font-size: 1rem; }
    .ps-map-addr { color: #64748b; font-size: .85rem; }
    .ps-map-btns { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-top: 12px; }
    .ps-map-show, .ps-map-open { padding: 8px 16px; border-radius: 8px; font-size: .8rem; font-weight: 700; cursor: pointer; text-decoration: none !important; }
    .ps-map-show { background: #dc2626; color: #fff; border: 0; }
    .ps-map-show:hover { background: #b91c1c; }
    .ps-map-open { background: #fff; color: #dc2626 !important; border: 1.5px solid rgba(220,38,38,.35); }
</style>
<script>
document.addEventListener('click', function (e) {
    var btn = e.target.closest && e.target.closest('.ps-map-show');
    if (!btn) return;
    var box = btn.closest('.ps-map');
    var frame = document.createElement('iframe');
    frame.src = box.dataset.embed;
    frame.title = 'Map to St. John the Baptist Parish';
    frame.setAttribute('loading', 'eager');
    box.innerHTML = '';
    box.appendChild(frame);
});
</script>
@endonce
