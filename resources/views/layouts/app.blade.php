<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'St. John the Baptist Parish')</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
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
</head>

<body>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('auth_notification'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const notif = @json(session('auth_notification'));

    Swal.fire({
        icon: notif.icon || 'info',
        title: notif.title || '',
        text: notif.text || '',
        confirmButtonColor: '#009DFF'
    });
});
</script>
@endif

</body>
</html>