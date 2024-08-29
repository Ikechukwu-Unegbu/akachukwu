<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration UI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex flex-col lg:flex-row min-h-screen">

        <!-- Left Side (Form Section) -->
        <div class="flex flex-col justify-center w-full lg:w-1/2 p-6 lg:p-12 bg-white">
            <div class="max-w-md mx-auto">
                <a href="#" class="text-sm text-gray-600 hover:text-gray-800 mb-6 inline-block">&lt; Back</a>
                <h2 class="text-2xl font-semibold mb-6">Welcome back!</h2>
                <p class="text-gray-600 mb-6">Login to your account so you can pay and purchase top-ups faster.</p>
                <form>
                    <div class="mb-4">
                        <input type="text" placeholder="Username" class="w-full p-3 border border-gray-300 rounded">
                    </div>
                    <div class="mb-4">
                        <input type="password" placeholder="Password" class="w-full p-3 border border-gray-300 rounded">
                    </div>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 mb-4 block">Forgot Password</a>
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700">Continue</button>
                    <p class="mt-4 text-gray-600">Don't have an account? <a href="#"
                            class="text-blue-600 hover:text-blue-800">Click here to register</a></p>
                </form>
                <div class="mt-6">
                    <p class="text-gray-600 mb-2">Download our mobile app now and take control of your transactions
                        anytime, anywhere.</p>
                    <div class="flex space-x-2">
                        <a href="#">
                            <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                                alt="App Store" class="h-10">
                        </a>
                        <a href="#">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                                alt="Google Play Store" class="h-10">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side (Image Section for Desktop) -->
        <div class="hidden lg:flex lg:w-1/2 lg:justify-center lg:items-center bg-gray-50">
            <div class="max-w-md">
                <img src="https://via.placeholder.com/400x300" alt="Never miss a payment"
                    class="rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold">Never miss a payment again with Vastel</h3>
                <p class="text-gray-600 mt-4">Pay your electricity, internet, and other utility bills quickly and early
                    from the palm of your hand.</p>
            </div>
        </div>

    </div>

</body>

</html>
