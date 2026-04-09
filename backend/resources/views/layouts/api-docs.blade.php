<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="theme-color" content="#1a1a2e">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Mitologi Clothing REST API Documentation - Complete reference for all API endpoints">

    <title>API Documentation - {{ config('app.name', 'Mitologi') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Critical CSS - Must be in head -->
    <style>
        /* Hide Alpine elements before load */
        [x-cloak] { display: none !important; }

        /* Mobile sidebar - default hidden */
        @media (max-width: 1023px) {
            #api-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-out;
            }
            #api-sidebar.sidebar-open {
                transform: translateX(0);
            }
            #sidebar-overlay {
                display: none;
            }
            #sidebar-overlay.overlay-visible {
                display: block;
            }
        }

        /* Desktop - always show sidebar */
        @media (min-width: 1024px) {
            #api-sidebar {
                transform: translateX(0) !important;
            }
        }
    </style>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100">
    @yield('content')

    @stack('scripts')
</body>
</html>
