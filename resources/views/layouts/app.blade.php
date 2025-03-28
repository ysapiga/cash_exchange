<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[url('/images/cash_background_enhanced.png')] bg-cover bg-center bg-fixed">
    <div class="min-h-screen bg-black/80">
        @include('layouts.header')

        <!-- Page Content -->
        <main class="container mx-auto px-4 py-8">
            @yield('content')
        </main>
    </div>
</body>
</html> 