@extends('layouts.new-dashboard')

@section('body')


<div class="max-w-lg w-full p-6">
        <!-- Back Button -->
        <a href="#" class="flex items-center text-vastel_blue mb-6">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>

        <!-- Form Header -->
        <h1 class="text-xl font-semibold mb-4">Data Purchase</h1>

        <!-- Form Start -->
        <form>
            <!-- Data Type -->
            <div class="relative z-0 mb-6 w-full group">
                <select id="dataType" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-vastel_blue peer">
                    <option value="" disabled selected>Data Type</option>
                    <option value="SME">SME</option>
                    <option value="Gifting">Gifting</option>
                    <option value="Corporate Gifting">Corporate Gifting</option>
                </select>
                <!-- <label for="dataType" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-vastel_blue peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Data Type*</label> -->
                <p class="mt-1 text-xs text-gray-400">Select Plan Type SME, Gifting or Corporate Gifting</p>
            </div>

            <!-- Mobile Number -->
            <div class="relative z-0 mb-6 w-full group">
                <input type="text" id="mobileNumber" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-vastel_blue peer" placeholder=" " />
                <label for="mobileNumber" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-vastel_blue peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Mobile Number*</label>
            </div>

            <!-- Plan -->
            <div class="relative z-0 mb-6 w-full group">
                <select id="plan" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-vastel_blue peer">
                    <option value="1GB">1.0GB SME = 215 30days</option>
                </select>
                <label for="plan" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-vastel_blue peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Plan*</label>
            </div>

            <!-- Amount -->
            <div class="relative z-0 mb-6 w-full group">
                <input type="text" id="amount" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-vastel_blue peer" placeholder=" " />
                <label for="amount" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-vastel_blue peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Amount</label>
            </div>

            <!-- Proceed Button -->
            <button type="submit" class="text-white bg-vastel_blue hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">Proceed</button>
        </form>
    </div>

@endsection 