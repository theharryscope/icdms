<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#14120e] text-[#f3eee3]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#14120e">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('pwa-icon.svg') }}">

    @php
        $siteSettings = \App\Models\SiteSetting::current();
    @endphp

    <title>{{ $title ?? $siteSettings->site_name }}</title>
    <meta name="description" content="{{ $metaDescription ?? $siteSettings->tagline ?? 'InnoTech Future Foundation coordinates digital skills training, community infrastructure and regional leadership programs across Nigeria, tracked in the open.' }}">
    @if ($siteSettings->favicon_url)
        <link rel="icon" href="{{ $siteSettings->favicon_url }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style> body { font-family: 'IBM Plex Sans', sans-serif; } </style>
</head>
<body class="h-full bg-[#14120e] font-sans antialiased text-[#f3eee3]">
    {{ $slot }}
    @livewireScripts
</body>
</html>