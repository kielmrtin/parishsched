{{-- Light parish modal theme. Loaded in <head> so modals shown on page load (login, logout, flash messages) are white from the first paint. --}}
<link rel="stylesheet" href="{{ asset('css/parish-modal-light.css') }}?v={{ file_exists(public_path('css/parish-modal-light.css')) ? filemtime(public_path('css/parish-modal-light.css')) : 1 }}">
