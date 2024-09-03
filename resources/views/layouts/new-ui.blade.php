<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Vastel Landing Page</title> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-white text-gray-800">
    <!-- Navbar -->
    <nav class="flex justify-between items-center p-4 md:px-[3rem] shadow-md">
        <div class="text-2xl font-bold text-vastel_blue">
            <!-- <span class="font-semibold">vastel</span> -->
             <img class="" src="{{asset('images/vastel-logo.svg')}}" alt="">
        </div>
        <div class="hidden md:flex space-x-6">
            <a href="#" class="text-vastel_blue hover:text-vastel_blue">Home</a>
            <a href="#" class="text-vastel_blue hover:text-vastel_blue">Company</a>
            <a href="#" class="text-vastel_blue hover:text-vastel_blue">Blog</a>
        </div>
        <div class="hidden md:flex space-x-4">
            <a href="#" class="text-vastel_blue hover:text-vastel_blue">Log In</a>
            <a href="#" class="bg-vastel_blue text-white py-2 px-4 rounded-md hover:bg-vastel_blue">Register</a>
        </div>
        <button type="button" class="md:hidden text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600" data-drawer-target="new-nav" data-drawer-show="new-nav" aria-controls="new-nav">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
     

         <!-- off canvas begin -->
    <div id="new-nav" class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80 dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-label">
        <h5 id="drawer-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400"><svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>Info</h5>
        <button type="button" data-drawer-hide="new-nav" aria-controls="new-nav" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>
            
        <!-- <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Supercharge your hiring by taking advantage of our <a href="#" class="text-blue-600 underline dark:text-blue-500 hover:no-underline">limited-time sale</a> for Flowbite Docs + Job Board. Unlimited access to over 190K top-ranked candidates and the #1 design job board.</p> -->
        <!-- <div class="grid grid-cols-2 gap-4">
            <a href="#" class="px-4 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Learn more</a>
            <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Get access <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
        </svg></a>
        </div> -->
        <div class="off-canvas-nav  text-vastel_blue p-4">
    <ul class="flex flex-col gap-[1rem]">
        <li><a href="/" class="text-vastel_blue hover:text-blue-400">Home</a></li>
        <li><a href="{{ route('airtime.index') }}" class="text-vastel_blue hover:text-blue-400">Airtime</a></li>
        <li><a href="{{ route('data.index') }}" class="text-vastel_blue hover:text-blue-400">Internet Data</a></li>
        <li><a href="{{ route('education.result.index') }}" class="text-vastel_blue hover:text-blue-400">Result Checker</a></li>
        <li>
            <a href="{{ route('dashboard') }}" class="text-vastel_blue hover:text-blue-400">
                @guest Bills Payment @else Dashboard @endguest
            </a>
        </li>
        <li><a href="{{ route('profile.edit') }}" class="text-vastel_blue hover:text-blue-400">Profile</a></li>
        <li><a href="{{ route('payment.index') }}" class="text-vastel_blue hover:text-blue-400">Fund Account</a></li>

        @guest
        <li>
            <ul class="flex flex-col gap-[1rem]">
                <li><a href="{{ route('register') }}" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Register</a></li>
                <li><a href="{{ route('login') }}" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Login</a></li>
            </ul>
        </li>
        @else
        <li>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="bg-blue-500 text-white py-2 rounded hover:bg-blue-600 w-full text-left">Logout</button>
            </form>
        </li>
        @endguest
    </ul>
</div>


    </div>
    <!-- off canvas end -->
    </nav>

   

    <!-- Hero Section -->
    <section class="flex flex-col gap-5 md:flex-row items-center justify-between px-4 py-12  bg-gray-50 md:px-[3rem]">
        <!-- Text Content -->
        <div class="md:w-1/2 space-y-6">
            <h1 class="text-4xl md:text-5xl font-bold text-vastel_blue">Empower Your Everyday Transactions With Ease</h1>
            <p class="text-lg text-gray-600">Fund Your Account, Send Money, Pay Bills, And More — All In One App.</p>
            <a href="#"
                class="bg-vastel_blue text-white py-3 px-6 rounded-md hover:bg-blue-800 inline-block text-lg font-semibold">Get
                Started</a>
        </div>

        <!-- Placeholder for Image -->
        <div class="md:w-1/2 flex justify-center md:justify-end mb-8 md:mb-0">
            <img src="{{asset('images/hero-img.svg')}}" alt="Placeholder Image"
                class="w-60 md:w-80 h-auto object-cover">
        </div>
    </section>

    <!-- feature -->

    <section class="py-12 px-4 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Feature Item 1 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="{{asset('images/card.svg')}}" alt="Icon 1">
                </div>
                <p class="text-gray-600">Simplify your bill payments for airtime, data, electricity, and cable TV with our intuitive, all-in-one app</p>
            </div>
            <!-- Feature Item 2 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="{{asset('images/sync.svg')}}" alt="Icon 2">
                </div>
                <p class="text-gray-600">Enjoy absolute peace of mind with our top-notch security, including 2FA and encrypted transactions.</p>
            </div>
            <!-- Feature Item 3 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="{{asset('images/upgrade.svg')}}" alt="Icon 3">
                </div>
                <p class="text-gray-600">Upgrade to a Reseller account, earn by reselling airtime and data, and manage everything.</p>
            </div>
            <!-- Feature Item 4 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md md:col-span-2">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="{{asset('images/widget.svg')}}" alt="Icon 3">
                </div>
                <p class="text-gray-600">Access a wide range of services, from funding your wallet to managing transactions and earning referral bonuses.</p>
            </div>
            <!-- Feature Item 5 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="{{asset('images/card.svg')}}" alt="Icon 1">
                </div>
                <p class="text-gray-600">Simplify your bill payments for airtime, data, electricity, and cable TV with our intuitive, all-in-one app</p>
            </div>
        </div>
    </section>

    <!--  -->


    
    <div class="bg-gray-50">
        <section class="max-w-7xl mx-auto px-4 py-12">
            <!-- Header -->
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-4xl font-semibold text-vastel_blue">How To Get The Best Vastel App</h2>
                <p class="text-gray-600 mt-4">Follow these four easy steps to get the best of the vastel Web and mobile application</p>
            </div>
            <!-- Steps -->
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                <!-- Step 1 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="{{asset('images/step1.svg')}}" alt="Step 1" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-vastel_blue mb-2">Step 1</h3>
                    <p class="text-gray-600">Sign up and create your account on the web or mobile app.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="{{asset('images/step2.svg')}}" alt="Step 2" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-vastel_blue mb-2">Step 2</h3>
                    <p class="text-gray-600">Add funds to your wallet.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="{{asset('images/step3.svg')}}" alt="Step 3" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-vastel_blue mb-2">Step 3</h3>
                    <p class="text-gray-600">Explore and use our services — send money, pay bills, and more.</p>
                </div>
                <!-- Step 4 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="{{asset('images/step4.svg')}}" alt="Step 4" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-vastel_blue mb-2">Step 4</h3>
                    <p class="text-gray-600">Refer friends and start earning or become a merchant.</p>
                </div>
            </div>
            <!-- CTA Button -->
            <div class="text-center mt-12">
                <a href="#" class="bg-vastel_blue text-white py-3 px-6 rounded-lg shadow hover:bg-blue-800 transition duration-300">
                    Open An Account In Minutes →
                </a>
            </div>
        </section>
    </div>


    <!-- download -->
    <div class="flex items-center justify-center min-h-screen bg-white">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between">
        <!-- Phone Image Placeholder -->
        <div class="flex justify-center w-full md:w-1/2">
            <!-- <div class="bg-gray-200 rounded-lg w-72 h-96 md:w-80 md:h-[500px] flex items-center justify-center"> -->
                <!-- Placeholder for the mobile image -->
                 <img src="{{asset('images/phone.svg')}}" alt="">
                <!-- <span class="text-gray-500">Phone Image Placeholder</span> -->
            <!-- </div> -->
        </div>

        <!-- QR Code and Download Section -->
        <div class="mt-8 md:mt-0 md:w-1/2 flex flex-col items-center md:items-start">
            <div class="flex flex-col items-center md:items-start">
                <img src="https://via.placeholder.com/100" alt="QR Code" class="mb-4">
                <p class="text-center md:text-left text-gray-700 text-lg">
                    Use Your Phone's Camera To Scan And Download The Vastel App<br>
                    Available On IOS And Android
                </p>
            </div>
            <div class="flex mt-4 space-x-4">
                <img src="{{}}" alt="Download on the App Store">
                <img src="https://via.placeholder.com/120x40?text=Google+Play" alt="Get it on Google Play">
            </div>
        </div>
    </div>
</div>
    <!-- download ends -->


    <!-- Testimonials Section -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-blue-800 mb-8">What Our Users Are Saying</h2>
            <div class="flex space-x-4 flex-col md:flex-row md:gap-[2rem] gap-[2rem]">
                <!-- Testimonial Item 1 -->
                <div class="max-w-sm bg-white rounded-lg shadow-md p-6">
                    <img src="https://via.placeholder.com/150" alt="User 1" class="w-full h-48 object-cover rounded-lg mb-4">
                    <blockquote class="text-purple-600 text-2xl mb-4">“</blockquote>
                    <p class="text-gray-700 text-lg">This app makes life so much easier—sending money has never been this quick!</p>
                    <p class="mt-4 font-bold">Brightlyn Star</p>
                </div>
                <!-- Testimonial Item 2 -->
                <div class="max-w-sm bg-white rounded-lg shadow-md p-6">
                    <img src="https://via.placeholder.com/150" alt="User 2" class="w-full h-48 object-cover rounded-lg mb-4">
                    <blockquote class="text-purple-600 text-2xl mb-4">“</blockquote>
                    <p class="text-gray-700 text-lg">This app makes life so much easier—sending money has never been this quick!</p>
                    <p class="mt-4 font-bold">Brightlyn Star</p>
                </div>
            </div>
            <!-- Pagination Controls -->
            <div class="text-gray-500 flex items-center space-x-2">
                <span>01</span>
                <span>/</span>
                <span>07</span>
                <button class="text-gray-700 hover:text-blue-800 focus:outline-none">&larr;</button>
                <button class="text-gray-700 hover:text-blue-800 focus:outline-none">&rarr;</button>
            </div>
        </div>
    </div>
</section>

<!-- Footer Section -->
<footer class="bg-vastel_blue text-white py-10">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Column 1 -->
        <div>
            <h4 class="font-semibold mb-4">Product</h4>
            <ul>
                <li><a href="#" class="hover:underline">Features</a></li>
                <li><a href="#" class="hover:underline">Pricing</a></li>
            </ul>
        </div>
        <!-- Column 2 -->
        <div>
            <h4 class="font-semibold mb-4">Resources</h4>
            <ul>
                <li><a href="#" class="hover:underline">Blog</a></li>
                <li><a href="#" class="hover:underline">User guides</a></li>
                <li><a href="#" class="hover:underline">Privacy Policy</a></li>
            </ul>
        </div>
        <!-- Column 3 -->
        <div>
            <h4 class="font-semibold mb-4">Company</h4>
            <ul>
                <li><a href="#" class="hover:underline">About</a></li>
                <li><a href="#" class="hover:underline">Join us</a></li>
            </ul>
        </div>
        <!-- Column 4 -->
        <div>
            <h4 class="font-semibold mb-4">Subscribe to our newsletter</h4>
            <p class="mb-4 text-sm">For product announcements and exclusive insights</p>
            <div class="flex space-x-2">
                <input type="email" placeholder="Enter text" class="w-full p-2 rounded bg-white text-gray-800 focus:outline-none">
                <button class="bg-purple-500 hover:bg-purple-700 text-white py-2 px-4 rounded">Subscribe</button>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="border-t border-gray-700 mt-8 pt-8 text-center">
        <div class="flex justify-center items-center space-x-4 mb-4">
            <select class="bg-transparent border border-white rounded px-2 py-1 text-sm">
                <option>English</option>
                <!-- Add more languages as options if needed -->
            </select>
            <!-- Social Icons -->
            <div class="flex space-x-4">
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-linkedin"></i></a>
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <p>&copy; 2024 Vastel.com</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>

</html>
