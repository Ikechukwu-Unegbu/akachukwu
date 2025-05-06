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


                {{-- <div class="flex px-6">
                    <a href="#"
                        class="w-1/2 pb-2 flex justify-center items-center text-center border-l-0 border-r-0 border-t-0 border-[#0018A8] text-[#333333] border border-b-4">
                        Live
                    </a>
                    <a href="#"
                        class="w-1/2 pb-2 flex justify-center items-center text-center border-l-0 border-r-0 border-t-0 border-[#E6E6E6] text-[#8A8A8A] border border-b-4">
                        Completed
                    </a>
                </div>



                <div class="overflow-x-hidden mt-2 scrollbar-none">
                    <div class="flex space-x-2 px-2 py-4 w-max">
                        <div
                            class="flex flex-col justify-center items-center text-center bg-[#E6E8F6] rounded-xl w-[143px] h-[115px] min-w-[8rem] flex-shrink-0 p-1">
                            <p class="text-[#3346B9] text-lg font-semibold">₦50,000.00</p>
                            <span class="text-[#3346B9] text-sm ">Total Savings Balance</span>
                            <span
                                class="bg-white text-[#333333] text-[12px] px-4 py-1 mt-2 rounded-2xl font-semibold text-center">Earn
                                ₦1109.58</span>
                        </div>

                        <div
                            class="flex flex-col justify-center items-center text-center bg-[#E6E8F6] rounded-xl w-[143px] h-[115px] min-w-[8rem] flex-shrink-0 p-1">
                            <p class="text-[#3346B9] text-lg font-semibold">₦ 100,000.00</p>
                            <span class="text-[#3346B9] text-sm ">Total Savings Balance</span>
                            <span
                                class="bg-white text-[#333333] text-[12px] px-4 py-1 mt-2 rounded-2xl font-semibold text-center">Earn
                                ₦3550.68</span>
                        </div>

                        <div
                            class="flex flex-col justify-center items-center text-center bg-[#E6E8F6] rounded-xl w-[143px] h-[115px] min-w-[8rem] flex-shrink-0 p-1">
                            <p class="text-[#3346B9] text-lg font-semibold">₦ 500,000.00</p>
                            <span class="text-[#3346B9] text-sm ">Total Savings Balance</span>
                            <span
                                class="bg-white text-[#333333] text-[12px] px-4 py-1 mt-2 rounded-2xl font-semibold text-center">Earn
                                ₦1109.58</span>
                        </div>

                        <div
                            class="flex flex-col justify-center items-center text-center bg-[#E6E8F6] rounded-xl w-[143px] h-[115px] min-w-[8rem] flex-shrink-0 p-1">
                            <p class="text-[#3346B9] text-lg font-semibold">₦ 500,000.00</p>
                            <span class="text-[#3346B9] text-sm ">Total Savings Balance</span>
                            <span
                                class="bg-white text-[#333333] text-[12px] px-4 py-1 mt-2 rounded-2xl font-semibold text-center">Earn
                                ₦1109.58</span>
                        </div>
                    </div>
                </div> --}}
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

            <div class="flex gap-3 mt-8 mx-14">
                <div>
                    <svg width="18" height="20" viewBox="0 0 18 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9 4.04725e-09C10.5524 -5.69771e-05 12.0444 0.601566 13.1625 1.67847C14.2806 2.75537 14.9378 4.22371 14.996 5.775L15 6H16C16.5046 5.99984 16.9906 6.19041 17.3605 6.5335C17.7305 6.87659 17.9572 7.34685 17.995 7.85L18 8V18C18.0002 18.5046 17.8096 18.9906 17.4665 19.3606C17.1234 19.7305 16.6532 19.9572 16.15 19.995L16 20H2C1.49542 20.0002 1.00943 19.8096 0.639452 19.4665C0.269471 19.1234 0.0428434 18.6532 0.00500021 18.15L1.00268e-07 18V8C-0.000159579 7.49543 0.190406 7.00944 0.533497 6.63945C0.876588 6.26947 1.34684 6.04284 1.85 6.005L2 6H3C3 4.4087 3.63214 2.88258 4.75736 1.75736C5.88258 0.632141 7.4087 4.04725e-09 9 4.04725e-09ZM16 8H2V18H16V8ZM9 10C9.42659 10.0001 9.84196 10.1367 10.1854 10.3897C10.5289 10.6426 10.7825 10.9988 10.9092 11.4062C11.0358 11.8135 11.0289 12.2507 10.8895 12.6538C10.75 13.057 10.4853 13.405 10.134 13.647L10 13.732V15C9.99972 15.2549 9.90212 15.5 9.72715 15.6854C9.55218 15.8707 9.31305 15.9822 9.05861 15.9972C8.80416 16.0121 8.55362 15.9293 8.35817 15.7657C8.16271 15.6021 8.0371 15.3701 8.007 15.117L8 15V13.732C7.61874 13.5119 7.32077 13.1721 7.15231 12.7653C6.98384 12.3586 6.95429 11.9076 7.06824 11.4824C7.18219 11.0571 7.43326 10.6813 7.78253 10.4133C8.1318 10.1453 8.55975 10 9 10ZM9 2C7.93913 2 6.92172 2.42143 6.17157 3.17157C5.42143 3.92172 5 4.93914 5 6H13C13 4.93914 12.5786 3.92172 11.8284 3.17157C11.0783 2.42143 10.0609 2 9 2Z"
                            fill="#0018A8" />
                    </svg>

                </div>

                <span class="text-[#333333] font-semibold">House rent</span>


            </div>

            <div class="mx-14 flex justify-between mt-4">
                <div class="flex flex-col text-[#333333]">
                    <span class="text-xs">Amount</span>
                    <span class="font-semibold">₦1,000.00 </span>
                </div>

                <div class="flex flex-col text-[#333333]">
                    <span class="text-xs">Interest</span>
                    <span class="font-semibold">₦492.15 </span>
                </div>

                <div class="flex flex-col text-[#333333]">
                    <span class="text-xs">Maturity</span>
                    <span class="font-semibold">11/12/2025 </span>
                </div>


            </div>
            <div class="flex justify-center items-center py-8">
                <button class="text-white px-20 rounded-2xl py-3 bg-[#0018A8] font-bold mt-2">Create a Plan</button>

            </div>
        </section>
    </section>
@endsection
