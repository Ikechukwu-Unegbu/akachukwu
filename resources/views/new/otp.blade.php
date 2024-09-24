@extends('layouts.new-dashboard')

@section('body')

 <!-- Trigger button -->
 <button data-modal-target="pinModal" data-modal-toggle="pinModal" class="px-4 py-2 text-white bg-vastel_blue rounded-lg">
        Open Transaction Pin Modal
    </button>

    <div class="w-full max-w-sm mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <ul class="divide-y divide-gray-200">
            <li class="flex justify-between items-center p-4">
                <span class="text-gray-700">100MB for 1 Day</span>
                <div class="flex flex-row gap-3">
                    <span class="text-green-600 font-bold">₦98.00</span>
                    <span class="line-through text-gray-400">₦100.00</span>
                </div>
            </li>
            <li class="flex justify-between items-center p-4">
                <span class="text-gray-700">100MB for 1 Day</span>
                <div class="flex flex-row gap-3">
                    <span class="text-green-600 font-bold">₦98.00</span>
                    <span class="line-through text-gray-400">₦100.00</span>
                </div>
            </li>
            <li class="flex justify-between items-center p-4">
                <span class="text-gray-700">100MB for 1 Day</span>
                <div class="flex flex-row gap-3">
                    <span class="text-green-600 font-bold">₦98.00</span>
                    <span class="line-through text-gray-400">₦100.00</span>
                </div>
            </li>
            <li class="flex justify-between items-center p-4">
                <span class="text-gray-700">100MB for 1 Day</span>
                <div class="flex flex-row gap-3">
                    <span class="text-green-600 font-bold">₦98.00</span>
                    <span class="line-through text-gray-400">₦100.00</span>
                </div>
            </li>
            <li class="flex justify-between items-center p-4">
                <span class="text-gray-700">100MB for 1 Day</span>
                <div class="flex flex-row gap-3">
                    <span class="text-green-600 font-bold">₦98.00</span>
                    <span class="line-through text-gray-400">₦100.00</span>
                </div>
            </li>
            <li class="flex justify-between items-center p-4">
                <span class="text-gray-700">100MB for 1 Day</span>
                <div class="flex flex-row gap-3">
                    <span class="text-green-600 font-bold">₦98.00</span>
                    <span class="line-through text-gray-400">₦100.00</span>
                </div>
            </li>
            <li class="flex justify-between items-center p-4">
                <span class="text-gray-700">100MB for 1 Day</span>
                <div class="flex flex-row gap-3">
                    <span class="text-green-600 font-bold">₦98.00</span>
                    <span class="line-through text-gray-400">₦100.00</span>
                </div>
            </li>
        </ul>
    </div>




    <!-- Modal -->
    <div id="pinModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
        <div class="relative w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Enter Transaction Pin
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="pinModal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal body (PIN input) -->
                <div class="p-6 space-y-6">
                    <div class="flex justify-center space-x-4">
                        <input type="text" maxlength="1" class="w-12 h-12 text-2xl text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vastel_blue" />
                        <input type="text" maxlength="1" class="w-12 h-12 text-2xl text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vastel_blue" />
                        <input type="text" maxlength="1" class="w-12 h-12 text-2xl text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vastel_blue" />
                        <input type="text" maxlength="1" class="w-12 h-12 text-2xl text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vastel_blue" />
                    </div>
                    <div class="flex justify-center">
                        <a href="#" class="text-sm text-vastel_blue hover:underline">Forgot Pin</a>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="flex items-center justify-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="pinModal" type="button" class="w-full px-5 py-2.5 text-white bg-vastel_blue hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm">
                        Pay
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection 