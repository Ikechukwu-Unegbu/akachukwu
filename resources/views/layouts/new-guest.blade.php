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
        .active {
            background-color: #ffffff;
            color: #0018A8 !important;
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }
        button:disabled {
            cursor: not-allowed;
            pointer-events: all !important;
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
   

    <div class="flex flex-col md:flex-row h-full">
        <!-- Sidebar -->
        <nav
            class="bg-vastel_blue text-white  w-full hidden md:w-16 lg:w-64 md:flex flex-row md:flex-col justify-between md:justify-start ">
            <div class="flex items-center mb-8 py-[5px] border-b border-b-2 h-[5rem]">
               
              <a @guest href="/" @else href="{{route('dashboard')}}" @endguest>
                <svg width="86" height="66" viewBox="0 0 86 66" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect x="0.5" width="85" height="65.4317" fill="url(#pattern0_954_10208)"/>
                    <defs>
                    <pattern id="pattern0_954_10208" patternContentUnits="objectBoundingBox" width="1" height="1">
                    <use xlink:href="#image0_954_10208" transform="scale(0.00719424 0.00934579)"/>
                    </pattern>
                    <image id="image0_954_10208" width="139" height="107" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIsAAABrCAYAAABHXXF9AAAACXBIWXMAAAsTAAALEwEAmpwYAAAFHGlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDUgNzkuMTYzNDk5LCAyMDE4LzA4LzEzLTE2OjQwOjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDI0LTA3LTI0VDA2OjQ3OjEzKzAxOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyNC0wNy0yNFQwNzo1MDoxMCswMTowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyNC0wNy0yNFQwNzo1MDoxMCswMTowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo4NmNhMWY1Yy02ODI0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"/>
                    </defs>
                </svg>
              </a>

            </div>
            <div class="flex md:flex-col space-x-4 md:space-x-0 md:space-y-6">
    
                <a href="{{ route('dashboard') }}"
                    class="flex flex-col items-center  w-[80%] py-[1rem] text-white hover:text-vastel_blue hover:bg-white hover:rounded-tr-lg hover:rounded-br-lg p-2 {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                    <i class="fas fa-home text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Dashboard</span>
                </a>

                <a href="{{route('services')}}" class="flex flex-col items-center w-[80%] py-[1rem] text-white hover:text-vastel_blue hover:bg-white hover:rounded-tr-lg hover:rounded-br-lg p-2 {{ request()->routeIs(['services', 'airtime.*', 'data.*', 'electricity.*', 'cable.*']) ? 'active' : '' }}">
                    <!-- <i class="fas fa-chart-bar text-xl"></i> -->
                    <i class="fa-solid fa-cubes-stacked text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Services</span>
                </a>
                <a href="{{ route('transactions') }}" class="flex flex-col items-center w-[80%] py-[1rem] text-white hover:text-vastel_blue hover:bg-white hover:rounded-tr-lg hover:rounded-br-lg p-2 {{ request()->routeIs('transactions') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Transactions</span>
                </a>

                <a href="{{ route('settings.index') }}" class="flex flex-col items-center w-[80%] py-[1rem] text-white hover:text-vastel_blue hover:bg-white hover:rounded-tr-lg hover:rounded-br-lg p-2 {{ Route::currentRouteName() == 'settings.credentials' ? 'active' : '' }}">

                    <i class="fas fa-cog text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Settings</span>
                </a>
                <a href="javascript:void(0)" class="flex mb-[3rem] flex-col items-center w-[80%] py-[1rem] text-white hover:text-vastel_blue hover:bg-white hover:rounded-tr-lg hover:rounded-br-lg p-2"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                >
                   <form id="logout-form" action="{{ route('logout') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                    <i class="fas fa-sign-out-alt text-xl"></i>
                    <span class=" hidden lg:inline">Logout</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header
                class="flex justify-between text-vastel_blue bg-white items-center mb-8 py-[5px] border-b border-b-2 h-[5rem] px-[2rem]">
                <div class="">
                    <h1 class="text-2xl hidden md:inline  font-bold">Hi @if(Auth::check()) , {{ auth()->user()->name }} @endif</h1>
                    <div class="flex flex-row-reverse md:hidden">
                        <img src="{{asset('images/clear-log.svg')}}" class="w-[3rem] h-[3rem]" alt="">
                        <button data-drawer-target="drawer-example" data-drawer-show="drawer-example" aria-controls="drawer-example" type="button" class="inline-flex items-center justify-center p-2 w-10 h-10 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" >
                            <span class="sr-only">Open main menu</span>
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                            </svg>
                        </button>

                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <i class="far fa-bell text-xl"></i>
                    <a href="{{route('settings.support')}}"><i class="far fa-question-circle text-xl"></i></a>
                    <button class=" bg-red-500 rounded-full" data-dropdown-toggle="dropdown" type="button">
                    <img 
                            src="{{ auth()->check() ? auth()->user()->profilePicture : asset('path/to/placeholder-image.jpg') }}" 
                            class="rounded-full w-12 h-12" 
                            alt="User Profile Picture"
                        />

                    </button>
                </div>
                <!-- dropdown -->
                <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                    <li>
                        <a href="{{route('dashboard')}}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{route('profile.edit')}}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profile</a>
                    </li>
                    <!-- <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Earnings</a>
                    </li>
                    <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</a>
                    </li> -->
                    </ul>
                </div>
                <!-- end of dropdown -->
            </header>

            @yield('body')
            {{ $slot ?? '' }}
        </main>
    </div>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->







 

    <!-- off can vas -->

    <div id="drawer-example" class="fixed top-0 left-0 z-50 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80" tabindex="-1" aria-labelledby="drawer-label">
        <img class="h-[4rem] w-[4rem]" src="{{ asset('images/vastel-logo.svg') }}" alt="Vastel Logo">
        
        <button type="button" data-drawer-hide="drawer-example" aria-controls="drawer-example" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 flex items-center justify-center">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>

        <nav class="flex flex-col text-blue-800 space-y-2 p-4">
            <a href="/" class="flex items-center space-x-2 hover:text-blue-500">
                <i class="fas fa-home"></i>
                <span>Home</span>   
            </a>
        
            <a href="{{route('services')}}" class="flex items-center space-x-2 hover:text-blue-500">
                <i class="fa-solid fa-cubes-stacked"></i>
                <span>Services</span>
            </a>
        
            <a href="{{route('dashboard')}}" class="flex items-center space-x-2 hover:text-blue-500">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{route('pages.about')}}" class="flex items-center space-x-2 hover:text-blue-500">
                <i class="fa-solid fa-address-card"></i>
                <span>About</span>
            </a>

            @guest
        
                <ul class="flex flex-col gap-[1rem]">
                    <li><a href="{{ route('register') }}" class="bg-vastel_blue text-white py-2 px-4 rounded hover:bg-blue-600">Register</a></li>
                    <li><a href="{{ route('login') }}" class="bg-vastel_blue text-white py-2 px-4 rounded hover:bg-blue-600">Login</a></li>
                </ul>

            @else

            <li>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="bg-vastel_blue text-white py-2 rounded hover:bg-blue-600 w-full text-left">Logout</button>
                </form>
            </li>
            @endguest
        </nav>
    </div>

    <!-- end off can vas -->
   
    <x-toastr />

    @stack('scripts')
</body>

</html>
