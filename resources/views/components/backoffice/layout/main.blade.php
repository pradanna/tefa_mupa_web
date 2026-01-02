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
    <x-backoffice.partials.head/>
  <body >
    <script src="{{asset ('assets/backoffice/js/demo-theme.min.js?1684106062')}}"></script>
    <div class="page">
      <!-- Navbar -->
      <x-backoffice.partials.header/>
      <x-backoffice.partials.navbar/>

      <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <x-backoffice.partials.breadcrumb/>
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
        <x-backoffice.partials.footer/>
      </div>
    </div>
    <!-- Libs JS -->
    <x-backoffice.partials.scripts/>
</body>
</html>
