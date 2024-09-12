<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    @yield('seo')

    <!-- Begin of Chaport Live Chat code -->
    <script>
        window.$zoho = window.$zoho || {};
        $zoho.salesiq = $zoho.salesiq || {
            ready: function() {}
        }
    </script>
    <script id="zsiqscript" src="https://salesiq.zohopublic.com/widget?wc=siq7e27ed946742b2ef7be4a02f6a2e0772" defer>
    </script>
    <!-- End of Chaport Live Chat code -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">

    <link rel="icon" href="{{ asset('images/scape_logo.png') }}" style="height: 2rem;width:6.94rem;" type="image/png">

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="{{ asset('pub-pages\assets\css\theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('pub-pages/assets/css/font-awesome.css') }}" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="{{ asset('css/ut/pin.css') }}" />

    <meta name="google-site-verification" content="RSrvupiRl2PlbPCslC1Ygbkon_UyUsWYWaCflhfrow8" />

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.0/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/heroicons-css@0.1.1/heroicons.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/heroicons-css@0.1.1/heroicons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .vastel_text {
            color: #0018A8;
        }

        .vastel_bg {
            background-color: #0018A8;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/ut/offcanvas.css?t=' . time()) }}" />

    @yield('head')
    @stack('styles')
    <livewire:scripts />
    <livewire:styles />
</head>

<body class="bg-white font-sans">

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    {{-- <main class="main" id="top">

        <nav class="navbar-light bg-light">
            <div class="custom-nav container-fluid">
                <div class="navbar-brand">
                    <a @auth href="{{ route('dashboard') }}" @else href="/" @endauth><img
                            src="{{ asset('images/scape_logo.png') }}" style="height: 2rem;width:6.94rem;"
                            alt="logo"></a>
                </div>
                <div class="navbar-nav main-nav me-auto mb-2 mb-lg-0">
                    <li><a class="fs-1 nav-link" href="/">Home</a></li>
                    <li><a class="fs-1 nav-link" href="{{ route('airtime.index') }}">Airtime</a></li>
                    <li><a class="fs-1 nav-link" href="{{ route('data.index') }}">Internet Data</a></li>
                    <li><a class="fs-1 nav-link" href="{{ route('dashboard') }}">
                            @guest Utility Bills
                            @else
                            Dashboard @endguest
                        </a></li>
                </div>
                <div class="auth-nav">
                    @guest
                        <a class=" btn btn-warning btn-sm" href="{{ route('login') }}">Login</a>
                        <a class="btn btn-warning btn-sm" href="{{ route('register') }}">Register</a>
                    @else
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button class="btn btn-warning btn-sm">Logout</button>
                        </form>
                    @endguest
                    @auth
                        @if (Auth::user()->isImpersonating())
                            <form action="{{ route('impersonate.stop') }}" method="post">
                                @csrf
                                <button class="btn btn-warning btn-sm"><small>Stop Impersonating</small></button>
                            </form>
                        @endif
                    @endauth
                </div>
                <button class="canvas-toggle btn " type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    <i class="fa-solid fa-2x fa-bars"></i>
                </button>




            </div>
            <!-- off canvas begins -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
                aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header">
                    <a @auth href="{{ route('dashboard') }}" @else href="/" @endauth><img
                            src="{{ asset('images/scape_logo.png') }}" style="height: 2rem;width:6.94rem;"
                            alt="logo"></a>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close">&times;</button>
                </div>
                <div class="offcanvas-body">

                    <ul class="off-canvass-nav">
                        <li><a href="/">Home</a></li>
                        <li><a href="{{ route('airtime.index') }}">Airtime</a></li>
                        <li><a href="{{ route('data.index') }}">Internet Data</a></li>
                        <li><a href="{{ route('education.result.index') }}">Result Checker</a></li>
                        <li><a href="{{ route('dashboard') }}">
                                @guest Bills Payment
                                @else
                                Dashboard @endguest
                            </a></li>
                        <li><a href="{{ route('profile.edit') }}">Profile</a></li>
                        <li><a href="{{ route('payment.index') }}">Fund Account</a></li>

                        @guest
                            <li>
                                <ul class="off-canvass-nav" style="margin-left: -1rem;">
                                    <li><a href="{{ route('register') }}" class="btn btn-warning">Register</a></li>
                                    <li><a href="{{ route('login') }}" class="btn btn-warning">Login</a></li>
                                </ul>
                            </li>
                        @else
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button class="btn btn-warning">Logout</button>
                            </form>
                        @endguest

                    </ul>
                </div>
            </div>


            <!-- off canvas ends -->
        </nav>

        @yield('body')

        {{ $slot ?? '' }}

    </main> --}}

    <div class="flex flex-col md:flex-row h-screen">
        <!-- Sidebar -->
        <nav
            class="bg-vastel_blue text-white w-full hidden md:w-16 lg:w-64 md:flex flex-row md:flex-col justify-between md:justify-start ">
            <div class="flex items-center mb-8 py-[5px] border-b border-b-4 h-[5rem]">
                <i class="fas fa-wifi text-2xl md:text-3xl"></i>
                <span class="ml-2 hidden lg:inline">vastal</span>
            </div>
            <div class="flex md:flex-col space-x-4 md:space-x-0 md:space-y-6">
                <!-- <a href="#" class="flex items-center bg-white rounded-lg p-2">
                    <i class="fas fa-home text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Dashboard</span>
                </a> -->
                <a href="{{ auth()->user()->dashboard() }}"
                    class="flex items-center text-vastel_blue w-[80%] py-[1rem] text-white hover:text-vastel_blue hover:bg-white hover:rounded-tr-lg hover:rounded-br-lg p-2">
                    <i class="fas fa-home text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Dashboard</span>
                </a>

                <a href="#" class="flex items-center p-2">
                    <i class="fas fa-chart-bar text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Statistics</span>
                </a>
                <a href="#" class="flex items-center p-2">
                    <i class="fas fa-exchange-alt text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Transactions</span>
                </a>
                <a href="#" class="flex items-center p-2">
                    <i class="fas fa-cog text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Settings</span>
                </a>
                <a href="javascript:void(0)" class="flex items-center p-2"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                >
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                    <i class="fas fa-sign-out-alt text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Logout</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header
                class="flex justify-between text-vastel_blue bg-white items-center mb-8 py-[5px] border-b border-b-4 h-[5rem] px-[2rem]">
                <h1 class="text-2xl font-bold">Hi, {{ auth()->user()->name }}</h1>
                <div class="flex items-center space-x-4">
                    <i class="far fa-bell text-xl"></i>
                    <i class="far fa-question-circle text-xl"></i>
                    <div class="w-8 h-8 bg-red-500 rounded-full"></div>
                </div>
            </header>

            @yield('body')
            {{ $slot ?? '' }}
        </main>
    </div>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->



    {{-- <div class="modal fade" id="popupVideo" tabindex="-1" aria-labelledby="popupVideo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <iframe class="rounded" style="width:100%;height:500px;" src="https://www.youtube.com/embed/_lhdhL4UDIo"
                    title="YouTube video player"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        </div>
    </div> --}}



    {{-- <script src="{{ asset('pub-pages/vendors/@popperjs/popper.min.js') }}"></script>
    <script src="{{ asset('pub-pages/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('pub-pages/vendors/is/is.min.js') }}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="{{ asset('pub-pages/vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('pub-pages/assets/js/theme.js') }}"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;family=Volkhov:wght@700&amp;display=swap"
        rel="stylesheet"> --}}
    <script src="{{ asset('pub-pages/vendors/jquery/jquery.min.js') }}"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <x-toastr />

    @stack('scripts')
</body>

</html>
