<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    @php
        $favicon = \App\Models\Setting::get('logo_favicon');
        $faviconUrl = $favicon && is_string($favicon)
            ? (str_starts_with($favicon, 'http') ? $favicon : asset('storage/' . $favicon))
            : asset('/images/logo/logo.png');
    @endphp
    <link rel="shortcut icon" href="{{ $faviconUrl }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:100,200,300,400,500,600,700,800,900&display=swap"
        rel="stylesheet" />

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
