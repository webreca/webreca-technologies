<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Webreca Technologies</title>
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else

        @endif
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        {{-- <video id="background-video" autoplay loop muted>
            <source src="{{ asset('assets/videos/coming-soon.mp4') }}" type="video/mp4">
        </video> --}}
        <img src="{{ asset('assets/temp/1.png') }}" width="100%" height="100%">
        <img src="{{ asset('assets/temp/2.png') }}" width="100%" height="100%">
        <img src="{{ asset('assets/temp/3.png') }}" width="100%" height="100%">
        <img src="{{ asset('assets/temp/4.png') }}" width="100%" height="100%">
        <img src="{{ asset('assets/temp/5.png') }}" width="100%" height="100%">
        <img src="{{ asset('assets/temp/6.png') }}" width="100%" height="100%">
    </body>
</html>
