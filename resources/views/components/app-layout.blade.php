<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Official Web Profile TEFA SMK Muhammadiyah Pakem">

    <title>{{ $title ?? 'TefaMupa' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/fe-style.css') }}">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.0/dist/cdn.min.js"></script>

    <style>
        /* Base font agar terlihat modern */
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* Utility class tambahan jika perlu */
        .text-tefa-primary {
            color: #0d6efd;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <x-navbar />

    <main class="flex-grow-1">
        {{ $slot }}
    </main>

    <x-footer />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    @stack('morejs')
</body>

</html>
