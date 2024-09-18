@extends('layouts.new-guest')

@section('body')
    <!-- Wallet Balance -->
    <div class="px-[2rem] bg-white  shadow-xl p-6 mb-8">
        <p class="text-sm mb-2 text-vastel_blue ml-3 md:ml-0">Wallet Balance</p>
        <div class="flex items-center justify-start md:justify-between flex-col md:flex-row">
            <div class="text-vastel_blue w-[100%] pl-3 md:pl-0  md:w-[50%] flex flex-col justify-end">
                <h2 class="text-3xl font-bold">₦500,000</h2>
                <p class="text-sm text-blue-600 mt-1">Referral Bonus: ₦1000 <i class="fas fa-chevron-right"></i></p>
            </div>
            <div class="flex justify-between gap-[2rem]">
                <button  data-modal-target="addMoneyModal" data-modal-toggle="addMoneyModal" class="bg-vastel_blue shadow-lg text-white px-4 py-[0.7rem] rounded-[5px] flex items-center justify-center w-[8.8rem] ">
                    <i class="fas fa-plus mr-2"></i> Add Money
                </button>
                <button class="bg-vastel_blue shadow-lg text-white px-4 py-[0.7rem] rounded-[5px] flex items-center justify-center w-[8.8rem] ">
                    <i class="fas fa-exchange-alt mr-2"></i> Transfer
                </button>
            </div>
        </div>
    </div>

            <!-- Services -->
            <div class="grid grid-cols-3 md:grid-cols-6 gap-4 mb-8">
                <div class="flex flex-col  items-center text-vastel_blue">
                    <i class=" fas fa-mobile-alt text-3xl text-vastel_blue mb-2"></i>
                    <span class="text-sm">Airtime</span>
                </div>
                <div class="flex flex-col  items-center text-vastel_blue">
                    <i class=" fas fa-wifi text-3xl  text-vastel_blue mb-2"></i>
                    <span class="text-sm">Data</span>
                </div>
                <div class="flex flex-col  items-center text-vastel_blue">
                    <i class=" fas fa-tv text-3xl text-vastel_blue mb-2"></i>
                    <span class="text-sm">TV</span>
                </div>
                <div class="flex flex-col  items-center text-vastel_blue">
                    <i class=" fas fa-bolt text-3xl text-vastel_blue mb-2"></i>
                    <span class="text-sm">Electricity</span>
                </div>
                <div class="flex flex-col  items-center text-vastel_blue">
                    <i class=" fas fa-globe text-3xl text-vastel_blue mb-2"></i>
                    <span class="text-sm">Internet</span>
                </div>
                <div class="flex flex-col  items-center text-vastel_blue">
                    <!-- <i class=" fas fa-ellipsis-h text-3xl text-blue-500 mb-2"></i> -->
                    <i class=" fa-solid text-3xl text-vastel_blue fa-cubes-stacked"></i>
                    <span class="text-sm">All Services</span>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="px-[2rem] bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Recent Transactions</h3>
                    <a href="#" class="text-blue-600 text-sm">See all Transactions</a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-mobile-alt bg-blue-100 p-2 rounded-full mr-3"></i>
                            <div>
                                <p class="font-semibold">Airtime Purchase (MTN)</p>
                                <p class="text-sm text-gray-500">2023-10-21 10:30:15</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-green-500 font-semibold">₦5000</p>
                            <p class="text-sm text-gray-500">Success</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-exchange-alt bg-purple-100 p-2 rounded-full mr-3"></i>
                            <div>
                                <p class="font-semibold">Transfer to JOSEPHINE AYO</p>
                                <p class="text-sm text-gray-500">2023-10-20 14:45:30</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-green-500 font-semibold">₦5000</p>
                            <p class="text-sm text-gray-500">Success</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-exchange-alt bg-purple-100 p-2 rounded-full mr-3"></i>
                            <div>
                                <p class="font-semibold">Transfer to AYOBAMI JOYCE</p>
                                <p class="text-sm text-gray-500">2023-10-19 09:15:45</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-red-500 font-semibold">₦4000</p>
                            <p class="text-sm text-gray-500">Failed</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-plus bg-green-100 p-2 rounded-full mr-3"></i>
                            <div>
                                <p class="font-semibold">Add Money</p>
                                <p class="text-sm text-gray-500">2023-10-18 16:20:00</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-green-500 font-semibold">₦5000</p>
                            <p class="text-sm text-gray-500">Success</p>
                        </div>
                    </div>
                </div>
            </div>



<!-- modals -->
<div id="addMoneyModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-gray-800 bg-opacity-50">
    <div class="relative w-full max-w-md">
        <!-- Modal content -->
        <div class="bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">
                    Add Money
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="addMoneyModal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6">
                <h5 class="text-sm font-medium text-gray-600">Virtual accounts</h5>
                <p class="mb-4 text-xs text-gray-500">Make a transfer to any of the accounts</p>
                
                <!-- Account 1 -->
                <div class="flex items-center justify-between p-4 mb-3 bg-gray-100 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Sterling Bank</p>
                        <p class="text-xs text-gray-500">Account Name</p>
                        <p class="text-sm font-medium text-gray-800">9876543210</p>
                    </div>
                    <button class="text-indigo-600 text-sm font-medium">Copy</button>
                </div>
                
                <!-- Account 2 -->
                <div class="flex items-center justify-between p-4 mb-3 bg-gray-100 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Wema Bank</p>
                        <p class="text-xs text-gray-500">Account Name</p>
                        <p class="text-sm font-medium text-gray-800">9876543210</p>
                    </div>
                    <button class="text-indigo-600 text-sm font-medium">Copy</button>
                </div>
                
                <!-- Account 3 -->
                <div class="flex items-center justify-between p-4 mb-4 bg-gray-100 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Other Bank</p>
                        <p class="text-xs text-gray-500">Account Name</p>
                        <p class="text-sm font-medium text-gray-800">9876543210</p>
                    </div>
                    <button class="text-indigo-600 text-sm font-medium">Copy</button>
                </div>
                
                <div class="flex items-center justify-center py-4">
                    <span class="text-sm text-gray-500">OR</span>
                </div>
                
                <div class="flex items-center p-4 bg-gray-100 rounded-lg">
                    <i class="fas fa-credit-card text-indigo-600"></i>
                    <button class="ml-3 text-sm font-medium text-indigo-600">Top-up with Card</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modals end -->

<!-- <script src="https://unpkg.com/flowbite@latest/dist/flowbite.js"></script> -->
@endsection 