<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>Penilaian | {{ auth()->user()->nama }}</title>
    <link rel="icon" type="image/x-icon" href="{{ url('template/assetsnew/img/favicon/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ url('template/assetsnew/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ url('template/assetsnew/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ url('template/assetsnew/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ url('template/assetsnew/css/demo.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ url('template/assetsnew/vendor/js/helpers.js') }}"></script>
    <script src="{{ url('template/assetsnew/js/config.js') }}"></script>

    {{-- apex chart --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @stack('custom-script')
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('kepsek.includes.sidebar')
            <div class="layout-page">
                @include('kepsek.includes.navbar')
                <div class="content-wrapper">
                    @yield('content')
                </div>
                @include('kepsek.includes.footer')
            </div>
        </div>
    </div>

    <script src="{{ url('template/assetsnew/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ url('template/assetsnew/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ url('template/assetsnew/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ url('template/assetsnew/vendor/js/menu.js') }}"></script>
    <script src="{{ url('template/assetsnew/js/main.js') }}"></script>
    @stack('custom-script')
</body>

</html>
