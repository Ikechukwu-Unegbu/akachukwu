<nav class="flex justify-between items-center p-4 bg-white md:px-[3rem] shadow-md">
        <div class="text-2xl font-bold text-vastel_blue">
            <!-- <span class="font-semibold">vastel</span> -->
             <img class="h-[4rem] w-[4rem]" src="{{asset('images/vastel-logo.svg')}}" alt="">
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
                
            <!-- <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Supercharge your hiring by taking advantage of our <a href="#" class="text-blue-600 underline dark:text-vastel_blue hover:no-underline">limited-time sale</a> for Flowbite Docs + Job Board. Unlimited access to over 190K top-ranked candidates and the #1 design job board.</p> -->
            <!-- <div class="grid grid-cols-2 gap-4">
                <a href="#" class="px-4 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Learn more</a>
                <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Get access <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg></a>
            </div> -->
            <div class="off-canvas-nav  text-vastel_blue p-4">
                <ul class="flex flex-col gap-[1rem]">
                    <li><a href="/" class="text-vastel_blue hover:text-vastel_blue">Home</a></li>
                    <li><a href="{{ route('airtime.index') }}" class="text-vastel_blue hover:text-vastel_blue">Airtime</a></li>
                    <li><a href="{{ route('data.index') }}" class="text-vastel_blue hover:text-vastel_blue">Internet Data</a></li>
                    <li><a href="{{ route('education.result.index') }}" class="text-vastel_blue hover:text-vastel_blue">Result Checker</a></li>
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-vastel_blue hover:text-vastel_blue">
                            @guest Bills Payment @else Dashboard @endguest
                        </a>
                    </li>
                    <li><a href="{{ route('profile.edit') }}" class="text-vastel_blue hover:text-vastel_blue">Profile</a></li>
                    <li><a href="{{ route('payment.index') }}" class="text-vastel_blue hover:text-vastel_blue">Fund Account</a></li>

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