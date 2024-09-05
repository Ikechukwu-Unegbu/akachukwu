<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.0/flowbite.min.js"></script>
    <script src="
        https://cdn.jsdelivr.net/npm/heroicons-css@0.1.1/heroicons.min.js
        "></script>
        <link href="
        https://cdn.jsdelivr.net/npm/heroicons-css@0.1.1/heroicons.min.css
        " rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .vastel_text{
            color: #0018A8;
        }
        .vastel_bg{
            background-color: #0018A8;
        }
    </style>
</head>
<body class="bg-white font-sans">
    <div class="flex flex-col md:flex-row h-screen">
        <!-- Sidebar -->
        <nav class="bg-vastel_blue text-white w-full hidden md:w-16 lg:w-64 md:flex flex-row md:flex-col justify-between md:justify-start ">
            <div class="flex items-center mb-8 py-[5px] border-b border-b-4 h-[5rem]">
                <i class="fas fa-wifi text-2xl md:text-3xl"></i>
                <span class="ml-2 hidden lg:inline">vastal</span>
            </div>
            <div class="flex md:flex-col space-x-4 md:space-x-0 md:space-y-6">
                <!-- <a href="#" class="flex items-center bg-white rounded-lg p-2">
                    <i class="fas fa-home text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Dashboard</span>
                </a> -->
                <a href="#" class="flex items-center text-vastel_blue w-[80%] py-[1rem] text-white hover:text-vastel_blue hover:bg-white hover:rounded-tr-lg hover:rounded-br-lg p-2">
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
                <a href="#" class="flex items-center p-2">
                    <i class="fas fa-sign-out-alt text-xl"></i>
                    <span class="ml-2 hidden lg:inline">Logout</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="flex justify-between text-vastel_blue bg-white items-center mb-8 py-[5px] border-b border-b-4 h-[5rem] px-[2rem]">
                <h1 class="text-2xl font-bold">Hi, Joyce</h1>
                <div class="flex items-center space-x-4">
                    <i class="far fa-bell text-xl"></i>
                    <i class="far fa-question-circle text-xl"></i>
                    <div class="w-8 h-8 bg-red-500 rounded-full"></div>
                </div>
            </header>

        @yield('body')
        </main>
    </div>
</body>
</html>