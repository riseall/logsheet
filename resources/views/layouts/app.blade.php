<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Logsheet Monitoring Mesin Produksi')</title>

    <link rel="shortcut icon" href="">

    <!-- Compiled Styles (TailwindCSS, Self-hosted Fonts, SweetAlert2) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Compiled Scripts (Alpine.js, SweetAlert2 & Notification Helpers) -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @include('layouts.partials.swal')
</head>

<body class="bg-slate-50 text-[#1E3A5F] min-h-screen flex flex-col antialiased">

    <!-- Top Horizontal Navbar (CauseConnect Styled) -->
    @include('layouts.partials.navbar')

    <!-- Flash Messages -->
    @include('layouts.partials.flash')

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        @yield('content')
    </main>

    <!-- Simple Footer (CauseConnect Design) -->
    @include('layouts.partials.footer')

    @stack('scripts')
</body>

</html>
