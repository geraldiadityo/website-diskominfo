<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description ?? $siteDescription ?? 'Portal resmi Dinas Komunikasi dan Informatika - Mewujudkan Transformasi Digital Daerah' }}">

    <title>{{ $title ?? 'Diskominfo' }} — {{ $siteName ?? 'Diskominfo' }}</title>

    {{-- Favicon --}}
    @if(isset($siteFavicon) && $siteFavicon)
        <link rel="icon" href="{{ asset('storage/' . $siteFavicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="{{ $title ?? 'Diskominfo' }} — {{ $siteName ?? 'Diskominfo' }}">
    <meta property="og:description" content="{{ $description ?? $siteDescription ?? 'Portal resmi Dinas Komunikasi dan Informatika - Mewujudkan Transformasi Digital Daerah' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    @if(isset($ogImage) && $ogImage)
        <meta property="og:image" itemprop="image" content="{{ $ogImage }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ $ogImage }}">
    @elseif(isset($siteLogo) && $siteLogo)
        <meta property="og:image" itemprop="image" content="{{ url('storage/' . $siteLogo) }}">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:image" content="{{ url('storage/' . $siteLogo) }}">
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    {{-- Material Icons --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{ $head ?? '' }}
</head>
<body class="bg-white text-slate-800 font-sans antialiased">

    <x-navbar />

    <main>
        {{ $slot }}
    </main>

    <x-footer />

</body>
</html>
