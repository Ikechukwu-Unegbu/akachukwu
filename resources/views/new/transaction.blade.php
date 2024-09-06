@extends('layouts.new-dashboard')

@section('body')
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
                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>

            <!-- Date Selector -->
            <div class="relative">
                <select class="appearance-none border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option>Mar 2024</option>
                    <option>Feb 2024</option>
                    <option>Jan 2024</option>
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="bg-white rounded-lg shadow p-4 space-y-4">
        <!-- Transaction Item -->
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <!-- Icon -->
                <i class="fas fa-mobile-alt text-blue-500 text-xl"></i>
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
                <i class="fas fa-exchange-alt text-blue-500 text-xl"></i>
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
                <i class="fas fa-times-circle text-red-500 text-xl"></i>
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
                <i class="fas fa-wifi text-blue-500 text-xl"></i>
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
                <i class="fas fa-bolt text-yellow-500 text-xl"></i>
                <div>
                    <p class="font-semibold">Electricity Purchased (IKEJA Electric)</p>
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