<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#10B981">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Ccircle cx='32' cy='32' r='14' fill='%23047857'/%3E%3Cpath d='M 26 38 C 23 33, 25 26, 32 23 C 39 27, 41 33, 38 38 C 35 41, 29 41, 26 38 Z' fill='%23A7F3D0'/%3E%3C/svg%3E">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-app text-slate-900 font-sans antialiased">
    <div class="relative min-h-screen bg-gradient-mesh">
        {{ $slot }}
    </div>
    <x-toast-host />
</body>
</html>
