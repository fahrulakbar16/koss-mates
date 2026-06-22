<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Koss Mates') }}</title>
    @php
        $favicon = \App\Models\Setting::get('logo_favicon');
        $faviconUrl = $favicon && is_string($favicon)
            ? (str_starts_with($favicon, 'http') ? $favicon : asset('storage/' . $favicon))
            : asset('/KosMates/Logo Kosmate.png');
    @endphp
    <link rel="shortcut icon" href="{{ $faviconUrl }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://api.fontshare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=clash-grotesk@500,600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600&display=swap">

    <!-- Scripts -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
