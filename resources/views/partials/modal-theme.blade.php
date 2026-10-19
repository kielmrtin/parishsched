{{-- Light parish modal theme. Include last in <body> so it overrides each page's dark modal styles. --}}
<link rel="stylesheet" href="{{ asset('css/parish-modal-light.css') }}?v={{ file_exists(public_path('css/parish-modal-light.css')) ? filemtime(public_path('css/parish-modal-light.css')) : 1 }}">
