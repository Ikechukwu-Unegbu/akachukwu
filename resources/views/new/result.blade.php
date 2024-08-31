@extends('layouts.new-dashboard')

@section('body')
<div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
        <a href="#" class="text-sm text-blue-500 mb-4 inline-block"><i class="fas fa-arrow-left"></i> Back</a>
        <h2 class="text-xl font-semibold mb-4">Education</h2>
        
        <form>
            <div class="mb-4">
                <label for="exam-type" class="block text-sm font-medium mb-1">Exam Type</label>
                <select id="exam-type" class="w-full p-2 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Select Exam Type</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="mobile-number" class="block text-sm font-medium mb-1">Mobile Number</label>
                <input type="text" id="mobile-number" class="w-full p-2 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="080********">
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium mb-1">Amount</label>
                <input type="text" id="amount" class="w-full p-2 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="₦">
            </div>

            <div class="mb-6">
                <label for="mobile-network" class="block text-sm font-medium mb-1">Mobile Network</label>
                <select id="mobile-network" class="w-full p-2 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Select Mobile Network</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-400 text-white py-2 rounded text-sm font-semibold hover:bg-blue-500 transition">Proceed</button>
        </form>
    </div>
@endsection 