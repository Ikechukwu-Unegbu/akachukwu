@extends('layouts.new-dashboard')

@section('body')
    <!-- Wallet Balance -->
    <div class="px-[2rem] bg-white  shadow p-6 mb-8">
                <p class="text-sm text-gray-600 mb-2">Wallet Balance</p>
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold">₦500,000</h2>
                        <p class="text-sm text-blue-600 mt-1">Referral Bonus: ₦1000 <i class="fas fa-chevron-right"></i></p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-plus mr-2"></i> Add Money
                        </button>
                        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-exchange-alt mr-2"></i> Transfer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="grid grid-cols-3 md:grid-cols-6 gap-4 mb-8">
                <div class="flex flex-col items-center">
                    <i class="fas fa-mobile-alt text-2xl text-blue-500 mb-2"></i>
                    <span class="text-sm">Airtime</span>
                </div>
                <div class="flex flex-col items-center">
                    <i class="fas fa-wifi text-2xl text-blue-500 mb-2"></i>
                    <span class="text-sm">Data</span>
                </div>
                <div class="flex flex-col items-center">
                    <i class="fas fa-tv text-2xl text-blue-500 mb-2"></i>
                    <span class="text-sm">TV</span>
                </div>
                <div class="flex flex-col items-center">
                    <i class="fas fa-bolt text-2xl text-blue-500 mb-2"></i>
                    <span class="text-sm">Electricity</span>
                </div>
                <div class="flex flex-col items-center">
                    <i class="fas fa-globe text-2xl text-blue-500 mb-2"></i>
                    <span class="text-sm">Internet</span>
                </div>
                <div class="flex flex-col items-center">
                    <!-- <i class="fas fa-ellipsis-h text-2xl text-blue-500 mb-2"></i> -->
                    <i class="fa-solid text-2xl text-blue-500 fa-cubes-stacked"></i>
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
@endsection 