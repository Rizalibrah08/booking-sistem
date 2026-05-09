<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — SPK Peminjaman Fasilitas MBS</title>
    <meta name="description" content="Sistem Pendukung Keputusan Peminjaman Fasilitas MBS A.R. Fachruddin">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-950 via-slate-900 to-brand-900 flex items-center justify-center p-4">
    @yield('content')
</body>
</html>
