<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Vastel Landing Page</title> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-800">
    <!-- Navbar -->
    <nav class="flex justify-between items-center p-4 shadow-md">
        <div class="text-2xl font-bold text-blue-900">
            <span class="font-semibold">vastel</span>
        </div>
        <div class="hidden md:flex space-x-6">
            <a href="#" class="text-gray-600 hover:text-blue-900">Home</a>
            <a href="#" class="text-gray-600 hover:text-blue-900">Company</a>
            <a href="#" class="text-gray-600 hover:text-blue-900">Blog</a>
        </div>
        <div class="hidden md:flex space-x-4">
            <a href="#" class="text-gray-600 hover:text-blue-900">Log In</a>
            <a href="#" class="bg-blue-900 text-white py-2 px-4 rounded-md hover:bg-blue-800">Register</a>
        </div>
        <button class="md:hidden text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </nav>

    <!-- Hero Section -->
    <section class="flex flex-col gap-5 md:flex-row items-center justify-between px-4 py-12 max-w-7xl mx-auto">
        <!-- Text Content -->
        <div class="md:w-1/2 space-y-6">
            <h1 class="text-4xl md:text-5xl font-bold text-blue-900">Empower Your Everyday Transactions With Ease</h1>
            <p class="text-lg text-gray-600">Fund Your Account, Send Money, Pay Bills, And More — All In One App.</p>
            <a href="#"
                class="bg-blue-900 text-white py-3 px-6 rounded-md hover:bg-blue-800 inline-block text-lg font-semibold">Get
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
                    <img src="https://via.placeholder.com/40" alt="Icon 1">
                </div>
                <p class="text-gray-600">Simplify your bill payments for airtime, data, electricity, and cable TV with our intuitive, all-in-one app</p>
            </div>
            <!-- Feature Item 2 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="https://via.placeholder.com/40" alt="Icon 2">
                </div>
                <p class="text-gray-600">Enjoy absolute peace of mind with our top-notch security, including 2FA and encrypted transactions.</p>
            </div>
            <!-- Feature Item 3 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="https://via.placeholder.com/40" alt="Icon 3">
                </div>
                <p class="text-gray-600">Upgrade to a Reseller account, earn by reselling airtime and data, and manage everything.</p>
            </div>
            <!-- Feature Item 4 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md md:col-span-2">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="https://via.placeholder.com/40" alt="Icon 4">
                </div>
                <p class="text-gray-600">Access a wide range of services, from funding your wallet to managing transactions and earning referral bonuses.</p>
            </div>
            <!-- Feature Item 5 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="https://via.placeholder.com/40" alt="Icon 5">
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
                <h2 class="text-2xl md:text-4xl font-semibold text-blue-900">How To Get The Best Vastel App</h2>
                <p class="text-gray-600 mt-4">Follow these four easy steps to get the best of the vastel Web and mobile application</p>
            </div>
            <!-- Steps -->
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                <!-- Step 1 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="path/to/image1.svg" alt="Step 1" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-blue-900 mb-2">Step 1</h3>
                    <p class="text-gray-600">Sign up and create your account on the web or mobile app.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="path/to/image2.svg" alt="Step 2" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-blue-900 mb-2">Step 2</h3>
                    <p class="text-gray-600">Add funds to your wallet.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="path/to/image3.svg" alt="Step 3" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-blue-900 mb-2">Step 3</h3>
                    <p class="text-gray-600">Explore and use our services — send money, pay bills, and more.</p>
                </div>
                <!-- Step 4 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="path/to/image4.svg" alt="Step 4" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-blue-900 mb-2">Step 4</h3>
                    <p class="text-gray-600">Refer friends and start earning or become a merchant.</p>
                </div>
            </div>
            <!-- CTA Button -->
            <div class="text-center mt-12">
                <a href="#" class="bg-blue-900 text-white py-3 px-6 rounded-lg shadow hover:bg-blue-800 transition duration-300">
                    Open An Account In Minutes →
                </a>
            </div>
        </section>
    </div>


    <!-- Testimonials Section -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-blue-800 mb-8">What Our Users Are Saying</h2>
        <div class="flex justify-between items-center mb-8">
            <div class="flex space-x-4">
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
<footer class="bg-blue-900 text-white py-10">
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


</body>

</html>
