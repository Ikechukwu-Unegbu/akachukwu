<nav class="flex justify-between items-center p-4 bg-white md:px-[3rem] shadow-md">
        <div class="text-2xl font-bold text-vastel_blue">
            
             <a href="/"><img class="h-[4rem] w-[4rem]" src="{{asset('images/vastel-logo.svg')}}" alt=""></a>
        </div>
        <div class="hidden md:flex space-x-6">
            <a href="/" class="text-vastel_blue hover:text-vastel_blue">Home</a>
            <!-- <a href="#" class="text-vastel_blue hover:text-vastel_blue">Company</a> -->
            <a href="/blog" class="text-vastel_blue hover:text-vastel_blue">Blog</a>
        </div>
        <div class="hidden md:flex space-x-4">
            @guest
            <a href="{{route('login')}}" class="text-vastel_blue hover:text-vastel_blue">Log In</a>
            <a href="{{route('register')}}" class="bg-vastel_blue text-white py-2 px-4 rounded-md hover:bg-vastel_blue">Register</a>
            @endguest
            @auth
            <a href="{{ auth()->user()->dashboard() }}" class="text-vastel_blue hover:text-vastel_blue">Dashboard</a>
            @endauth
        </div>
        <button type="button" class="md:hidden text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600" data-drawer-target="new-nav" data-drawer-show="new-nav" aria-controls="new-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
     

         <!-- off canvas begin -->
        <div id="new-nav" class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80" tabindex="-1" aria-labelledby="drawer-label">
            <img class="h-[4rem] w-[4rem]" src="{{asset('images/vastel-logo.svg')}}" alt="">
            <button type="button" data-drawer-hide="new-nav" aria-controls="new-nav" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close menu</span>
            </button>
                
          
            <div class="off-canvas-nav  text-vastel_blue p-4">
                <ul class="flex flex-col gap-[1rem]">
                    <li><a href="/" class="text-vastel_blue hover:text-vastel_blue">Home</a></li>
                    <!-- <li><a href="{{ route('airtime.index') }}" class="text-vastel_blue hover:text-vastel_blue">Airtime</a></li>
                    <li><a href="{{ route('data.index') }}" class="text-vastel_blue hover:text-vastel_blue">Internet Data</a></li>
                    -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-vastel_blue hover:text-vastel_blue">
                            @guest Bills Payment @else Dashboard @endguest
                        </a>
                    </li>
                    <li><a href="{{ route('profile.edit') }}" class="text-vastel_blue hover:text-vastel_blue">Profile</a></li>
    
                    @guest
                    <li>
                        <ul class="flex flex-col gap-[1rem]">
                            <li><a href="{{ route('register') }}" class="bg-vastel_blue text-white py-2 px-4 rounded hover:bg-blue-600">Register</a></li>
                            <li><a href="{{ route('login') }}" class="bg-vastel_blue text-white py-2 px-4 rounded hover:bg-blue-600">Login</a></li>
                        </ul>
                    </li>
                    @else
                    <li>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button class="bg-vastel_blue text-white py-2 rounded hover:bg-blue-600 w-full text-left">Logout</button>
                        </form>
                    </li>
                    @endguest
                </ul>
            </div>


        </div>
        <!-- off canvas end -->
    </nav>