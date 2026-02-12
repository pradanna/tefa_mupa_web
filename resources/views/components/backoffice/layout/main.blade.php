<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta19
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name') }}</title>
    <!-- CSS files -->
    <link href="{{ asset('assets/backoffice/css/tabler.min.css?1684106062') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('assets/backoffice/css/demo.min.css?1684106062') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    {{-- Custom Backoffice CSS --}}
    <link rel="stylesheet" href="{{ asset('css/backoffice.css') }}">
</head>

<body>
    <script src="{{ asset('assets/backoffice/js/demo-theme.min.js?1684106062') }}"></script>
    <div class="page d-flex flex-row">
        {{-- Sidebar untuk mode Website (Desktop), disembunyikan di mobile --}}
        <div class="d-none d-lg-block">
            <x-backoffice.partials.sidebar />
        </div>

        <div class="page-wrapper flex-grow-1">
            <x-backoffice.partials.header />
            {{-- Navbar untuk mode Mobile, disembunyikan di desktop --}}
            <div class="d-lg-none">
                <x-backoffice.partials.navbar />
            </div>
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <x-backoffice.partials.breadcrumb />
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <x-backoffice.partials.footer />
        </div>
    </div>

    <!-- Toast untuk Error/Success/Informasi -->
    <div aria-live="polite" aria-atomic="true"
        style="position: fixed; top: 1.5rem; right: 1.5rem; min-width: 300px; z-index: 1055;">
        @if (session('error'))
            <x-backoffice.toast.error :message="session('error')" />
        @endif

        @if (session('success'))
            <x-backoffice.badge.success :message="session('success')" />
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <x-backoffice.toast.error :message="$error" />
            @endforeach
        @endif
    </div>
    <!-- Libs JS -->
    <x-backoffice.partials.scripts />

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>
