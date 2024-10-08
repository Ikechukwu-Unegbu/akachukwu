<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@stack('title') :: {{ config('app.name') }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('admin-pages/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('admin-pages/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('admin-pages/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-pages/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-pages/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-pages/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-pages/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-pages/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-pages/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('admin-pages/css/style.css') }}" rel="stylesheet">
    <style>
        .btn.disabled,
        .btn:disabled,
        fieldset:disabled .btn {
            cursor: not-allowed !important;
        }
    </style>
    <livewire:scripts />
    <livewire:styles />
</head>

<body>

    @auth
        @include('layouts.admin.navbar')
        @include('layouts.admin.sidebar')
    @endauth

    <main @auth id="main" class="main" @endauth>
      {{ $slot ?? '' }}
      @yield('content')
    </main><!-- End #main -->

    @auth
      <!-- ======= Footer ======= -->
      <footer id="footer" class="footer">
          <div class="copyright">
              &copy; {{ date('Y') }} Copyright <strong><span></span></strong>. All Rights Reserved
          </div>
      </footer>
      <!-- End Footer -->
    @endauth

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
      <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('admin-pages/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin-pages/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-pages/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('admin-pages/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('admin-pages/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('admin-pages/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('admin-pages/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('admin-pages/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('admin-pages/js/main.js') }}"></script>
    <script src="{{ asset('pub-pages/vendors/jquery/jquery.min.js') }}"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <x-toastr />
    @stack('scripts')
</body>

</html>
