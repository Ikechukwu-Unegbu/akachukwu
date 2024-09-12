@extends('layouts.new-guest')

@section('body')
<!-- <div class="bg-gray-100 p-4 md:p-8"> -->
    <div class=" bg-white rounded-lg  p-6">
        <h1 class="text-2xl font-bold mb-6">Bill Payment</h1>
        
        <!-- Main Services -->
        <div class="grid grid-cols-3 sm:grid-cols-5 gap-4 mb-8">
            <a href="{{ route('airtime.index') }}" class="flex flex-col w-[100%] md:w-[60%] rounded-[10px] shadow-lg  p-5 md:p-2 shadow-lg p-5 items-center">
                <div class="w-12 h-12  flex items-center justify-center">
                    <i class="fas  fa-mobile-alt text-vastel_blue text-3xl"></i>
                </div>
                <span class="font-semibold text-vastel_blue text-xs text-center">Airtime</span>
            </a>
            <a href="{{ route('data.index') }}" class="flex flex-col w-[100%] md:w-[60%] rounded-[10px] shadow-lg  p-5 md:p-2 items-center">
                <div class="w-12 h-12  flex items-center justify-center">
                    <i class="fas  fa-wifi text-vastel_blue text-3xl"></i>
                </div>
                <span class="font-semibold text-vastel_blue text-xs text-center">Data</span>
            </a>
            <a href="{{ route('cable.index') }}" class="flex flex-col w-[100%] md:w-[60%] rounded-[10px] shadow-lg  p-5 md:p-2 items-center">
                <div class="w-12 h-12  flex items-center justify-center">
                    <i class="fas  fa-tv text-vastel_blue text-3xl"></i>
                </div>
                <span class="font-semibold text-vastel_blue text-xs text-center">TV</span>
            </a>
            <a href="{{ route('electricity.index') }}" class="flex flex-col w-[100%] md:w-[60%] rounded-[10px] shadow-lg  p-5 md:p-2 items-center">
                <div class="w-12 h-12  flex items-center justify-center">
                    <i class="fas  fa-bolt text-vastel_blue text-3xl"></i>
                </div>
                <span class="font-semibold text-vastel_blue text-xs text-center">Electricity</span>
            </a>
            <!-- <div class="flex flex-col w-[100%] md:w-[60%] rounded-[10px] shadow-lg  p-5 md:p-2 items-center">
                <div class="w-12 h-12  flex items-center justify-center">
                    <i class="fas  fa-exchange-alt text-vastel_blue text-3xl"></i>
                </div>
                <span class="font-semibold text-vastel_blue text-xs text-center">Internet</span>
            </div> -->
            <a href="{{ route('education.result.index') }}" class="flex flex-col w-[100%] md:w-[60%] rounded-[10px] shadow-lg  p-5 md:p-2 items-center">
                <div class="w-12 h-12  flex items-center justify-center">
                    <i class="fas  fa-graduation-cap text-vastel_blue text-3xl"></i>
                </div>
                <span class="font-semibold text-vastel_blue text-xs text-center">Education</span>
            </a>
        </div>

        <!-- Others Section -->
        <h2 class="text-lg font-semibold mb-4">Others</h2>
        <div class="grid grid-cols-3 sm:grid-cols-5 gap-4">
            <a href="#" class="flex flex-col w-[100%] md:w-[60%] rounded-[10px] shadow-lg  p-5 md:p-2 items-center">
                <div class="w-12 h-12  flex items-center justify-center">
                    <i class="fas  fa-university text-vastel_blue text-3xl"></i>
                </div>
                <span class="font-semibold text-vastel_blue text-xs text-center">Transfer</span>
            </a>
            <a href="#" class="flex flex-col w-[100%] md:w-[60%] rounded-[10px] shadow-lg  p-5 md:p-2 items-center">
                <div class="w-12 h-12  flex items-center justify-center">
                    <i class="fas  fa-hand-holding-usd text-vastel_blue text-3xl"></i>
                </div>
                <span class="font-semibold text-vastel_blue text-xs text-center">Refer & Earn</span>
            </a>
        </div>
    </div>
<!-- </div> -->
@endsection 