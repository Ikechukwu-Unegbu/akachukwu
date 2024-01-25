<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pages / Not Found 404</title>
  <meta name="robots" content="noindex, nofollow">
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('admin-pages//img/favicon.png') }}" rel="icon">
  <link href="{{ asset('admin-pages//img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <main>
    <div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1>404</h1>
        <h2>The page you are looking for doesn't exist.</h2>
        <a class="btn" 
            href=""
            onclick="window.history.back();"
            >
            Back to home
        </a>       
        <img src="{{ asset('admin-pages/img/not-found.svg') }}" class="img-fluid py-5" alt="Page Not Found">
      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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
  <script src="{{ asset('admin-pages//js/main.js') }}"></script>

</body>

</html>