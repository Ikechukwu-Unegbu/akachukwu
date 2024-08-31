@extends('layouts.new-dashboard')

@section('body')
<!-- <div class="bg-gray-100 p-4 md:p-8"> -->
    <div class=" bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Bill Payment</h1>
        
        <!-- Main Services -->
        <div class="grid grid-cols-3 sm:grid-cols-5 gap-4 mb-8">
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-mobile-alt text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs text-center">Airtime</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-wifi text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs text-center">Data</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-tv text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs text-center">TV</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-bolt text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs text-center">Electricity</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs text-center">Internet</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs text-center">Education</span>
            </div>
        </div>

        <!-- Others Section -->
        <h2 class="text-lg font-semibold mb-4">Others</h2>
        <div class="grid grid-cols-3 sm:grid-cols-5 gap-4">
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-university text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs text-center">Transfer</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-hand-holding-usd text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs text-center">Refer & Earn</span>
            </div>
        </div>
    </div>
<!-- </div> -->
@endsection 