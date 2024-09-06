@extends('layouts.new-dashboard')

@section('body')
<div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
        <a href="#" class="text-sm text-blue-500 mb-4 inline-block"><i class="fas fa-arrow-left"></i> Back</a>
        <h2 class="text-xl font-semibold mb-4">Electricity Purchase</h2>
        
        <form>
            <div class="mb-4">
                <label for="provider" class="block text-sm font-medium mb-1">Select Provider</label>
                <select id="provider" class="w-full p-2 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Select Provider</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="meter-number" class="block text-sm font-medium mb-1">Enter Meter Number</label>
                <input type="text" id="meter-number" class="w-full p-2 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="1234********">
            </div>

            <div class="mb-6">
                <label for="amount" class="block text-sm font-medium mb-1">Amount</label>
                <input type="text" id="amount" class="w-full p-2 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="₦">
            </div>

            <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded text-sm font-semibold hover:bg-blue-800 transition">Proceed</button>
        </form>
    </div>
@endsection 