@extends('layouts.new-dashboard')

@section('body')

<div class="flex space-x-4">
        <!-- Trigger buttons -->
        <button data-modal-target="pendingModal" data-modal-toggle="pendingModal" class="bg-vastel_blue text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
            Show Pending Modal
        </button>
        <button data-modal-target="failedModal" data-modal-toggle="failedModal" class="bg-vastel_blue text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
            Show Failed Modal
        </button>
    </div>

    <!-- Pending Modal -->
    <div id="pendingModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="pendingModal">
                    <i class="fas fa-times w-5 h-5"></i>
                </button>
                <div class="px-6 py-6 lg:px-8">
                    <h3 class="mb-4 text-xl font-medium text-gray-900">Transaction</h3>
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-clock text-yellow-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Transaction Pending</h3>
                        <p class="text-gray-500 text-sm mb-4">TV Purchase Pending</p>
                        <button class="bg-vastel_blue text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                            Transactions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Failed Modal -->
    <div id="failedModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="failedModal">
                    <i class="fas fa-times w-5 h-5"></i>
                </button>
                <div class="px-6 py-6 lg:px-8">
                    <h3 class="mb-4 text-xl font-medium text-gray-900">Transaction</h3>
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-times text-red-500 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Transaction Failed</h3>
                        <p class="text-gray-500 text-sm mb-4">TV Purchase failed</p>
                        <button class="bg-vastel_blue text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                            Transactions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection 