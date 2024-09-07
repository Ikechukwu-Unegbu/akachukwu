@extends('layouts.new-dashboard')

@section('body')
<div class="w-full max-w-md p-6 bg-white ">
    <a href="#" class="text-sm text-vastel_blue mb-4 inline-block"><i class="fas fa-arrow-left"></i> Back</a>
    <h2 class="text-xl font-semibold mb-4">Electricity Purchase</h2>

    <form>
        <div class="relative z-0 w-full mb-4 group">
            <select id="provider" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                <option selected>Select Provider</option>
            </select>
            <label for="provider" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-vastel_blue peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Select Provider</label>
        </div>

        <div class="relative z-0 w-full mb-4 group">
            <input type="text" id="meter-number" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
            <label for="meter-number" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-vastel_blue peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Enter Meter Number</label>
        </div>

        <div class="relative z-0 w-full mb-6 group">
            <input type="text" id="amount" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
            <label for="amount" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-vastel_blue peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Amount</label>
        </div>

        <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded text-sm font-semibold hover:bg-blue-800 transition">Proceed</button>
    </form>
</div>

@endsection 