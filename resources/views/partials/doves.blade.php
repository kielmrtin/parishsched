{{-- Parish doves: login doves on the "Login Successful" alert, plus three little
     doves visiting customer pages. Customer pages only (never admin).
     Include once near the end of <body>. --}}
<link rel="stylesheet" href="{{ asset('css/parish-doves.css') }}?v={{ file_exists(public_path('css/parish-doves.css')) ? filemtime(public_path('css/parish-doves.css')) : 1 }}">
<script src="{{ asset('js/parish-doves.js') }}?v={{ file_exists(public_path('js/parish-doves.js')) ? filemtime(public_path('js/parish-doves.js')) : 1 }}" data-ambient="1" defer></script>
