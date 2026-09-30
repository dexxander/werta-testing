<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Submissions') — Werta</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/Werta_Logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/Werta_Logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    @include('partials.tailwind-dashboard-config')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lato', sans-serif; background-color: #F5EFE0; }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>