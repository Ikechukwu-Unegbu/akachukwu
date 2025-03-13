<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header Section -->
        <div class=" text-center py-6">
            <img src="{{asset('images/vastel-logo.svg')}}" alt="Vastel Logo" class="mx-auto">
        </div>
        
        <!-- Main Content -->
        <div class="px-6 py-8 text-gray-800">
            <h1 class="text-xl font-semibold text-center text-[#0018A8] mb-4">Welcome to Vastel</h1>
            <p class="text-sm leading-relaxed">
                Welcome to Vastel! We’re thrilled to have you on board. With our app, you can easily buy airtime, subscribe to internet services, cable TV, and educational services, send money — all at your fingertips!
            </p>
            <p class="text-sm leading-relaxed mt-4">
                To get started, download the app and explore the various services we offer. If you have any questions, our support team is just a tap away.
            </p>
            <p class="text-sm font-medium mt-4">Let's make things easier together!</p>
            
            <p class="text-sm mt-6">Best Regards,<br>The Vastel Team</p>
        </div>

        <!-- Footer Section -->
        <div class="bg-[#0018A8] text-white text-center py-4 text-xs">
            <p>&copy; 2024 Vastel.io</p>
            <div class="flex justify-center space-x-4 mt-2">
                <a href="#" class="hover:opacity-80">
                    <img src="https://via.placeholder.com/16" alt="Twitter">
                </a>
                <a href="#" class="hover:opacity-80">
                    <img src="https://via.placeholder.com/16" alt="Facebook">
                </a>
                <a href="#" class="hover:opacity-80">
                    <img src="https://via.placeholder.com/16" alt="Instagram">
                </a>
                <a href="#" class="hover:opacity-80">
                    <img src="https://via.placeholder.com/16" alt="YouTube">
                </a>
            </div>
        </div>
    </div>
</body>
</html>
