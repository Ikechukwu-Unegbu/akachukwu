<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>VasTel Nig</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets\img\favicons\favicon.png') }}">
    <link rel="manifest" href="{{ asset('passets\img\favicons\manifest.json') }}">
    <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="{{ asset('pub-pages\assets\css\theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('pub-pages/assets/css/font-awesome.css') }}" rel="stylesheet" />
    @yield('head')
    @stack('styles')
    <livewire:scripts />
    <livewire:styles />
    <style>
        .dashboard-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            height: 100%;
        }

        .card-body {
            padding: 0;
        }

        .card-header {
            background-color: #fff !important;
        }

        .card-title {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .card-content {
            font-size: 16px;
            text-align: center;
        }
    </style>
</head>


<body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <nav class="navbar navbar-expand-lg navbar-light" {{-- data-navbar-on-scroll="data-navbar-on-scroll" sticky-top
            --}}>
            <div class="container"><a class="navbar-brand" href="/"><img
                        src="{{ asset('pub-pages/assets/img/logo.svg') }}" height="31" alt="logo" /></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">
                    </span></button>
                @guest
                <div class="mt-4 collapse navbar-collapse border-top border-lg-0 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" aria-current="page"
                                href="{{route('airtime.index')}}">Airtime</a></li>
                        <li class="nav-item"><a class="nav-link" aria-current="page"
                                href="{{route('data.index')}}">Internet Data</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" aria-current="page"
                                href="{{route('cable.index')}}">Cable</a></li>
                        <li class="nav-item"><a class="nav-link" aria-current="page"
                                href="{{route('electricity.index')}}">Electricity</a>
                        </li>
                    </ul>
                    <div class="d-flex ms-lg-4">
                        <a class="btn btn-secondary-outline" href="{{ route('login') }}">Login</a>
                        <a class="btn btn-warning ms-3" href="{{ route('register') }}">Register</a>
                    </div>
                </div>
                @endguest
                @auth
                <div class="mt-4 collapse navbar-collapse border-top border-lg-0 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" aria-current="page"
                                href="{{route('airtime.index')}}">Airtime</a></li>
                        <li class="nav-item"><a class="nav-link" aria-current="page"
                                href="{{route('data.index')}}">Internet Data</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" aria-current="page"
                                href="{{route('cable.index')}}">Cable</a></li>
                        <li class="nav-item"><a class="nav-link" aria-current="page"
                                href="{{route('electricity.index')}}">Electricity</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" aria-current="page"
                                href="{{route('payment.index')}}">Fund Account</a>
                        </li>

                    </ul>
                    <div class="d-flex ms-lg-4">
                        <a href="{{ auth()->user()->dashboard() }}" class="btn btn-outline-warning ms-3">
                            Dashboard
                        </a>
                        <a class="btn btn-outline-warning ms-3"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            Logout
                        </a>                       
                    </div>
                </div>
                @endauth
            </div>
        </nav>

        @yield('body')

        {{ $slot ?? '' }}

    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->


    <div class="modal fade" id="popupVideo" tabindex="-1" aria-labelledby="popupVideo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <iframe class="rounded" style="width:100%;height:500px;" src="https://www.youtube.com/embed/_lhdhL4UDIo"
                    title="YouTube video player"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        </div>
    </div>


    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{ asset('pub-pages/vendors/@popperjs/popper.min.js') }}"></script>
    <script src="{{ asset('pub-pages/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('pub-pages/vendors/is/is.min.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="{{ asset('pub-pages/vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('pub-pages/assets/js/theme.js') }}"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Volkhov:wght@700&amp;display=swap"
        rel="stylesheet">
    <script src="{{ asset('pub-pages/vendors/jquery/jquery.min.js') }}"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <x-toastr />

    @stack('scripts')
</body>

</html>