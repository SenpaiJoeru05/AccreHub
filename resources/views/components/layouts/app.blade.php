<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'AccreHub' }}</title>
    @livewireStyles
    @filamentStyles
    @vite('resources/css/app.css')
</head>
<body class="antialiased">
    {{ $slot }}
    @livewireScripts
    @filamentScripts
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>