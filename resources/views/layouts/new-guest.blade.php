<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
   @yield('seo')
    
    <!-- Begin of Chaport Live Chat code -->
    <script>window.$zoho=window.$zoho || {};$zoho.salesiq=$zoho.salesiq||{ready:function(){}}</script><script id="zsiqscript" src="https://salesiq.zohopublic.com/widget?wc=siq7e27ed946742b2ef7be4a02f6a2e0772" defer></script>
<!-- End of Chaport Live Chat code -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">

     <link rel="icon" href="{{ asset('images/scape_logo.png') }}" style="height: 2rem;width:6.94rem;" type="image/png">

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="{{ asset('pub-pages\assets\css\theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('pub-pages/assets/css/font-awesome.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('css/ut/pin.css')}}"/>

    <meta name="google-site-verification" content="RSrvupiRl2PlbPCslC1Ygbkon_UyUsWYWaCflhfrow8" />

    <link rel="stylesheet" href="{{asset('css/ut/offcanvas.css?t=' . time() )}}"/>

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
        .vastel-bg{
            background-color: #0018A8;
            color:white;
        }
        .vastel-bg:hover {
            background-color: #1d057d; /* Change this color to your desired hover effect color */
            color: white;
        }
        .vastel-text{
            background-color: #0018A8;
        }
        .vastel-text{
            color: gray;
        }
        .vastel-text:hover, .vastel-text:focus{
            color: #0018A8;
        }

        .custom-nav{
            display: grid;
            grid-template-columns: 1fr 3fr 1fr; /* Defines the column widths */
            justify-content: center; /* Horizontally centers the grid items */
            align-items: center; /* Vertically centers the grid items */
            gap: 10px;
            height: 4rem;
        }
        .main-nav{
            display: flex;
            flex-direction: row;
            gap: 1rem;
            width: 100%;
            justify-content: center;
            align-items: center;
        }
        .auth-nav{
            display: flex;
            flex-direction: row;
            gap: 1.4rem;
            justify-content:end;
            align-items: center;
        }
        .canvas-toggle{
            display: none;
        }

        @media(max-width:800px) {
            .custom-nav{
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .auth-nav{
                display: none;
              
            }
            .canvas-toggle{
                display:block;
                width: 3rem;
                /* align-content: center; */
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .main-nav{
                display: none;
            }

        }
        .off-canvass-nav{
            display: flex;
            flex-direction: column;
            list-style: none;
            gap: 0.4rem;
        }
        .off-canvass-nav a{
            text-decoration: none;
        }
    </style>
</head>


<body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
       
        <nav class="navbar-light bg-light">
           <div class="custom-nav container-fluid">
                <div class="navbar-brand">
                    <a @auth href="{{route('dashboard')}}" @else href="/" @endauth><img  src="{{ asset('images/scape_logo.png') }}" style="height: 2rem;width:6.94rem;" alt="logo"></a>
                </div>
                <div class="navbar-nav main-nav me-auto mb-2 mb-lg-0">
                    <li><a class="fs-1 nav-link" href="/">Home</a></li>
                    <li><a class="fs-1 nav-link" href="{{route('airtime.index')}}">Airtime</a></li>
                    <li><a class="fs-1 nav-link" href="{{route('data.index')}}">Internet Data</a></li>
                    <li><a class="fs-1 nav-link" href="{{route('dashboard')}}"> 
                        @guest Utility Bills @else Dashboard @endguest
                    </a></li>
                </div>
                <div class="auth-nav">
                    @guest 
                    <a class=" btn btn-warning" href="{{route('login')}}">Login</a>
                    <div class="btn btn-warning" href="{{route('register')}}">Register</div>
                    @else 
                    <form action="{{route('logout')}}" method="post">
                        @csrf 
                        <button class="btn btn-warning">Logout</button>
                    </form>
                    @endguest   
                </div>
                <button class="canvas-toggle btn " type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                <i class="fa-solid fa-2x fa-bars"></i>
                </button> 
      

               

           </div>
            <!-- off canvas begins -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header">
                    <a @auth href="{{route('dashboard')}}" @else href="/" @endauth><img  src="{{ asset('images/scape_logo.png') }}" style="height: 2rem;width:6.94rem;" alt="logo"></a>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                  
                    <ul class="off-canvass-nav">
                        <li><a href="/">Home</a></li>
                        <li><a href="{{route('airtime.index')}}">Airtime</a></li>
                        <li><a href="{{route('data.index')}}">Internet Data</a></li>
                        <li><a href="{{route('education.result.index')}}">Result Checker</a></li>
                        <li><a href="{{route('dashboard')}}">
                            @guest Bills Payment @else Dashboard @endguest
                        </a></li>
                        <li><a href="{{route('profile.edit')}}">Profile</a></li>
                        <li><a href="{{route('payment.index')}}">Fund Account</a></li>
                        
                        @guest 
                        <li>
                            <ul class="off-canvass-nav" style="margin-left: -1rem;">
                                <li><a href="{{route('register')}}" class="btn btn-warning">Register</a></li>
                                <li><a href="{{route('login')}}" class="btn btn-warning">Login</a></li>
                            </ul>
                        </li>
                        @else 
                        <form action="{{route('logout')}}" method="post">
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