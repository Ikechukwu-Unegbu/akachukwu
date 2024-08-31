@extends('layouts.new-dashboard')

@section('body')


<!-- <div class="w-full max-w-md p-6 bg-white rounded shadow-lg"> -->
        <a href="#" class="text-sm text-blue-500 mb-4 inline-block"><i class="fas fa-arrow-left"></i> Back</a>
        <h2 class="text-xl font-semibold mb-4">Data Purchase</h2>
        
        <form>
            <div class="mb-4">
                <label for="data-type" class="block text-sm font-medium mb-1">Data Type*</label>
                <select id="data-type" class="w-full p-2 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Select Plan Type SME, GIFTING or CORPORATE GIFTING</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="mobile-number" class="block text-sm font-medium mb-1">Mobile Number*</label>
                <div class="relative">
                    <input type="text" id="mobile-number" class="w-full p-2 pl-10 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="080********">
                    <i class="fas fa-phone absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div class="mb-4">
                <label for="plan" class="block text-sm font-medium mb-1">Plan*</label>
                <select id="plan" class="w-full p-2 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>1.0GB SME = 215 30days</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="amount" class="block text-sm font-medium mb-1">Amount</label>
                <input type="text" id="amount" class="w-full p-2 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="₦">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded text-sm font-semibold hover:bg-blue-600 transition">Proceed</button>
        </form>
    <!-- </div> -->

@endsection 