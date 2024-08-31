@extends('layouts.new-dashboard')

@section('body')


    <!-- Transactions Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Transactions</h2>
        <div class="flex space-x-4">
            <!-- Categories Dropdown -->
            <div class="relative">
                <select class="appearance-none border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option>All Categories</option>
                    <option>Airtime</option>
                    <option>Data</option>
                    <option>Electricity</option>
                    <option>Transfers</option>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 12a1 1 0 01-.707-.293l-4-4a1 1 0 111.414-1.414L10 9.586l3.293-3.293a1 1 0 011.414 1.414l-4 4A1 1 0 0110 12z" clip-rule="evenodd" />
                </svg>
            </div>

            <!-- Date Selector -->
            <div class="relative">
                <select class="appearance-none border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option>Mar 2024</option>
                    <option>Feb 2024</option>
                    <option>Jan 2024</option>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 12a1 1 0 01-.707-.293l-4-4a1 1 0 111.414-1.414L10 9.586l3.293-3.293a1 1 0 011.414 1.414l-4 4A1 1 0 0110 12z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="bg-white rounded-lg shadow p-4 space-y-4">
        <!-- Transaction Item -->
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <!-- Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18m-9 4h9" />
                </svg>
                <div>
                    <p class="font-semibold">Airtime Purchased (MTN)</p>
                    <p class="text-sm text-gray-500">Phone No: 08123456789</p>
                    <p class="text-sm text-gray-500">2024-03-23 09:15:33</p>
                    <a href="#" class="text-blue-600 text-sm">View Receipt</a>
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold text-green-600">₦200</p>
                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Successful</span>
            </div>
        </div>

        <hr>

        <!-- Another Transaction Item -->
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <!-- Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m-6 0l3-3V8m-6 8h18" />
                </svg>
                <div>
                    <p class="font-semibold">Transfer to JOSEPHINE AYO</p>
                    <p class="text-sm text-gray-500">Panpay | 05075368</p>
                    <p class="text-sm text-gray-500">2024-03-23 09:15:33</p>
                    <a href="#" class="text-blue-600 text-sm">View Receipt</a>
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold text-green-600">₦200</p>
                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Successful</span>
            </div>
        </div>

        <hr>

        <!-- Another Transaction Item -->
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <!-- Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <div>
                    <p class="font-semibold">Transfer to AYOBAMI JOYCE</p>
                    <p class="text-sm text-gray-500">Panpay | 05075363</p>
                    <p class="text-sm text-gray-500">2024-03-23 09:15:33</p>
                    <a href="#" class="text-blue-600 text-sm">View Receipt</a>
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold text-red-600">₦200</p>
                <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full">Failed</span>
            </div>
        </div>

        <hr>

        <!-- Another Transaction Item -->
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <!-- Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18m-9 4h9" />
                </svg>
                <div>
                    <p class="font-semibold">Data Purchased (GLO)</p>
                    <p class="text-sm text-gray-500">Phone No: 08123456789</p>
                    <p class="text-sm text-gray-500">2024-03-23 09:15:33</p>
                    <a href="#" class="text-blue-600 text-sm">View Receipt</a>
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold text-green-600">₦400</p>
                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Successful</span>
            </div>
        </div>

        <hr>

        <!-- Another Transaction Item -->
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <!-- Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5" />
                </svg>
                <div>
                    <p class="font-semibold">Electricity Purchase (IKEJA Electric)</p>
                    <p class="text-sm text-gray-500">Account No: 1234567890</p>
                    <p class="text-sm text-gray-500">2024-03-23 09:15:33</p>
                    <a href="#" class="text-blue-600 text-sm">View Receipt</a>
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold text-green-600">₦500</p>
                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Successful</span>
            </div>
        </div>
    </div>

@endsection 