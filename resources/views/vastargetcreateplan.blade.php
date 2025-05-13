@extends('layouts.new-guest')
@section('head')
    <title>VASTel | vasfixed dashboard</title>
@endsection

@section('body')
    <section class="lg:px-[2rem] px-4">
        <div class="text-[#0018A8] lg:text-lg  lg:flex gap-4 hidden ">
            <span class="flex justify-center items-center"> <svg width="8" height="13" viewBox="0 0 8 13" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.875 12.0416L1.33333 6.49992L6.875 0.958252" stroke="#0018A8" stroke-width="1.58333"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <span class="flex justify-center items-center">Back</span>
        </div>
        <section class="lg:border border-[#00000038] lg:my-8 lg:py-5  mt-8 py-4 lg:w-6/12">
            <div class="m-3 bg-[#0018A8] rounded-3xl p-4">
                <span class="text-[#E6E6E6] text-xs">VasFixed 30% p.a</span>
                <div class="flex justify-between  ">
                    <p class="text-white font-semibold text-3xl flex justify-center items-center">₦1,000</p>

                    <div class="flex   justify-center items-center">
                        <img src="/images/piggy.png" alt="" class="w-20">

                    </div>
                </div>

            </div>



            <div>


                <div>
                    <!-- Tab Buttons -->
                    <div class="flex px-6">
                        <button id="liveTab" onclick="switchTab('live')"
                            class="w-1/2 pb-2 flex justify-center items-center text-center border-l-0 border-r-0 border-t-0 border-[#0018A8] text-[#333333] border border-b-4 font-bold">
                            Live
                        </button>
                        <button id="completedTab" onclick="switchTab('completed')"
                            class="w-1/2 pb-2 flex justify-center items-center text-center border-l-0 border-r-0 border-t-0 border-[#E6E6E6] text-[#8A8A8A] border border-b-4">
                            Completed
                        </button>
                    </div>

                    <!-- Live Boxes -->
                    <div id="liveContent" class="overflow-x-hidden mt-2 scrollbar-none">
                        <div class="flex space-x-2 px-2 py-4 w-max">
                            <!-- Box 1 -->
                            <div
                                class="flex flex-col justify-center items-center text-center bg-[#E6E8F6] rounded-xl w-[143px] h-[115px] min-w-[8rem] flex-shrink-0 p-1">
                                <p class="text-[#3346B9] text-lg font-semibold">₦50,000.00</p>
                                <span class="text-[#3346B9] text-sm">Total Savings Balance</span>
                                <span
                                    class="bg-white text-[#333333] text-[12px] px-4 py-1 mt-2 rounded-2xl font-semibold">Earn
                                    ₦1109.58</span>
                            </div>
                            <!-- Box 2 -->
                            <div
                                class="flex flex-col justify-center items-center text-center bg-[#E6E8F6] rounded-xl w-[143px] h-[115px] min-w-[8rem] flex-shrink-0 p-1">
                                <p class="text-[#3346B9] text-lg font-semibold">₦100,000.00</p>
                                <span class="text-[#3346B9] text-sm">Total Savings Balance</span>
                                <span
                                    class="bg-white text-[#333333] text-[12px] px-4 py-1 mt-2 rounded-2xl font-semibold">Earn
                                    ₦3550.68</span>
                            </div>
                            <!-- Box 3 -->
                            <div
                                class="flex flex-col justify-center items-center text-center bg-[#E6E8F6] rounded-xl w-[143px] h-[115px] min-w-[8rem] flex-shrink-0 p-1">
                                <p class="text-[#3346B9] text-lg font-semibold">₦500,000.00</p>
                                <span class="text-[#3346B9] text-sm">Total Savings Balance</span>
                                <span
                                    class="bg-white text-[#333333] text-[12px] px-4 py-1 mt-2 rounded-2xl font-semibold">Earn
                                    ₦1109.58</span>
                            </div>
                            <!-- Box 4 -->
                            <div
                                class="flex flex-col justify-center items-center text-center bg-[#E6E8F6] rounded-xl w-[143px] h-[115px] min-w-[8rem] flex-shrink-0 p-1">
                                <p class="text-[#3346B9] text-lg font-semibold">₦500,000.00</p>
                                <span class="text-[#3346B9] text-sm">Total Savings Balance</span>
                                <span
                                    class="bg-white text-[#333333] text-[12px] px-4 py-1 mt-2 rounded-2xl font-semibold">Earn
                                    ₦1109.58</span>
                            </div>
                        </div>
                    </div>

                    <!-- Completed (Empty) -->
                    <div id="completedContent" class="hidden mt-6 px-6">
                        <p class="text-center text-[#8A8A8A]">No completed items yet.</p>
                    </div>
                </div>

                <!-- JavaScript -->
                <script>
                    function switchTab(tab) {
                        const liveTab = document.getElementById('liveTab');
                        const completedTab = document.getElementById('completedTab');
                        const liveContent = document.getElementById('liveContent');
                        const completedContent = document.getElementById('completedContent');

                        if (tab === 'live') {
                            liveContent.classList.remove('hidden');
                            completedContent.classList.add('hidden');

                            liveTab.classList.add('border-[#0018A8]', 'text-[#333333]', 'font-bold');
                            liveTab.classList.remove('border-[#E6E6E6]', 'text-[#8A8A8A]');

                            completedTab.classList.add('border-[#E6E6E6]', 'text-[#8A8A8A]');
                            completedTab.classList.remove('border-[#0018A8]', 'text-[#333333]', 'font-bold');
                        } else {
                            liveContent.classList.add('hidden');
                            completedContent.classList.remove('hidden');

                            completedTab.classList.add('border-[#0018A8]', 'text-[#333333]', 'font-bold');
                            completedTab.classList.remove('border-[#E6E6E6]', 'text-[#8A8A8A]');

                            liveTab.classList.add('border-[#E6E6E6]', 'text-[#8A8A8A]');
                            liveTab.classList.remove('border-[#0018A8]', 'text-[#333333]', 'font-bold');
                        }
                    }
                </script>


            </div>
            <div class="my-8 bg-[#E6E6E640] px-4 mx-2 rounded-3xl border   flex gap-5 py-3">
                <div>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C13.5524 1.99994 15.0444 2.60157 16.1625 3.67847C17.2806 4.75537 17.9378 6.22371 17.996 7.775L18 8H19C19.5046 7.99984 19.9906 8.19041 20.3605 8.5335C20.7305 8.87659 20.9572 9.34685 20.995 9.85L21 10V20C21.0002 20.5046 20.8096 20.9906 20.4665 21.3606C20.1234 21.7305 19.6532 21.9572 19.15 21.995L19 22H5C4.49542 22.0002 4.00943 21.8096 3.63945 21.4665C3.26947 21.1234 3.04284 20.6532 3.005 20.15L3 20V10C2.99984 9.49543 3.19041 9.00944 3.5335 8.63945C3.87659 8.26947 4.34684 8.04284 4.85 8.005L5 8H6C6 6.4087 6.63214 4.88258 7.75736 3.75736C8.88258 2.63214 10.4087 2 12 2ZM19 10H5V20H19V10ZM12 12C12.4266 12.0001 12.842 12.1367 13.1854 12.3897C13.5289 12.6426 13.7825 12.9988 13.9092 13.4062C14.0358 13.8135 14.0289 14.2507 13.8895 14.6538C13.75 15.057 13.4853 15.405 13.134 15.647L13 15.732V17C12.9997 17.2549 12.9021 17.5 12.7272 17.6854C12.5522 17.8707 12.313 17.9822 12.0586 17.9972C11.8042 18.0121 11.5536 17.9293 11.3582 17.7657C11.1627 17.6021 11.0371 17.3701 11.007 17.117L11 17V15.732C10.6187 15.5119 10.3208 15.1721 10.1523 14.7653C9.98384 14.3586 9.95429 13.9076 10.0682 13.4824C10.1822 13.0571 10.4333 12.6813 10.7825 12.4133C11.1318 12.1453 11.5597 12 12 12ZM12 4C10.9391 4 9.92172 4.42143 9.17157 5.17157C8.42143 5.92172 8 6.93914 8 8H16C16 6.93914 15.5786 5.92172 14.8284 5.17157C14.0783 4.42143 13.0609 4 12 4Z"
                            fill="#0018A8" />
                    </svg>

                </div>
                <div class="flex flex-col">
                    <h3 class="text-[#333333] text-lg">House rent</h3>
                    <div class="flex gap-6">
                        <div class="flex flex-col">
                            <p class="font-semibold text-[#333333]">₦0</p>
                            <span class="text-xs">Saved</span>

                        </div>
                        <div class="flex flex-col">
                            <p class="font-semibold text-[#333333]">₦30K</p>
                            <span class="text-xs">Total Target</span>

                        </div>
                        <div class="flex flex-col">
                            <p class="font-semibold text-[#333333]">20</p>
                            <span class="text-xs">Days Left</span>

                        </div>
                    </div>

                    <div class="flex justify-between items-center gap-2 mt-3">
                        <hr class="lg:w-[400px] w-[200px] border-2 bg-[#0000003B]">
                        <span class="text-sm text-[#333333] font-medium ml-8 lg:ml-16 w-8">0%</span>
                    </div>






                </div>

            </div>


            <div class="flex justify-center items-center py-8">
                <button class="text-white px-20 rounded-2xl py-3 bg-[#0018A8] font-bold mt-2">Create a Plan</button>

            </div>
        </section>
    </section>
@endsection
