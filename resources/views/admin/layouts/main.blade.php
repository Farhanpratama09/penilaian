<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title', 'Sistem Penilaian Kinerja Guru BARS') | {{ auth()->check() ? auth()->user()->nama : 'Admin' }}</title>

    <!-- SEO & Metadata -->
    <meta name="description" content="@yield('meta_description', 'Sistem Informasi Penilaian Kinerja Guru Berbasis Behaviorally Anchored Rating Scale (BARS) SDN 01 Sungai Raya Kepulauan')">
    <meta name="keywords" content="penilaian kinerja guru, metode BARS, evaluasi guru, SDN 01 Sungai Raya Kepulauan, sistem informasi">
    <meta name="author" content="SDN 01 Sungai Raya Kepulauan">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Sistem Penilaian Kinerja Guru BARS') | {{ auth()->check() ? auth()->user()->nama : 'Admin' }}">
    <meta property="og:description" content="@yield('meta_description', 'Sistem Informasi Penilaian Kinerja Guru Berbasis BARS SDN 01 Sungai Raya Kepulauan')">
    <meta property="og:image" content="{{ asset('template/assetsnew/img/favicon/favicon.ico') }}">

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

    <!-- Vite Assets (Tailwind CSS & JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ url('template/assetsnew/vendor/js/helpers.js') }}"></script>
    <script src="{{ url('template/assetsnew/js/config.js') }}"></script>
    @stack('custom-script')
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('admin.includes.sidebar')
            <div class="layout-page">
                <header>
                    @include('admin.includes.navbar')
                </header>
                <main class="content-wrapper">
                    @yield('content')
                </main>
                @include('admin.includes.footer')
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
