@extends('layouts.new-guest')
@section('head')
    <title>VASTel | vasTarget</title>
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
            <section class="p-6">
                <div class="bg-[#0018A8] rounded-3xl text-white  p-4 ">

                    <div class="flex flex-col">

                        <span class="text-[11px] text-[#E6E6E6]">VasSave 17% p.a</span>
                        <p class="font-semibold mt-1 text-3xl">₦500,000</p>

                    </div>

                    <div class="pt-3 flex justify-between lg:hidden">
                        <span class="text-white text-[15px]">Next Free Withdraw</span>
                        <div class="flex gap-1">
                            <span class="text-white flex justify-center items-center text-[15px]">30 Jun 2025</span>
                            <svg class="flex justify-center items-center" width="23" height="24" viewBox="0 0 23 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.60223 6.25L8.25098 7.60125L12.6401 12L8.25098 16.3988L9.60223 17.75L15.3522 12L9.60223 6.25Z"
                                    fill="white" />
                            </svg>


                        </div>

                    </div>
                    <div>
                        <button
                            class="flex justify-center text-center items-center gap-2 text-[#3346B9] bg-white mt-4 rounded-lg w-full py-2">

                            <svg class="flex justify-center items-center" width="18" height="18" viewBox="0 0 18 18"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 3V15M15 9L3 9" stroke="#3346B9" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span class="flex justify-center items-center text-sm font-semibold"> Add Money
                            </span>

                        </button>
                    </div>

                    <div class="py-3 flex justify-between lg:hidden">
                        <span class="text-white text-[15px]">Next Free Withdraw</span>
                        <div class="flex gap-1">
                            <span class="text-white flex justify-center items-center text-[15px]">30 Jun 2025</span>
                            <svg class="flex justify-center items-center" width="23" height="24" viewBox="0 0 23 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.60223 6.25L8.25098 7.60125L12.6401 12L8.25098 16.3988L9.60223 17.75L15.3522 12L9.60223 6.25Z"
                                    fill="white" />
                            </svg>


                        </div>

                    </div>
                </div>


            </section>
            <section class="lg:px-8">
                <div class="mt-8 flex justify-between items-center">
                    <div>
                        <p class="text-[#646464] font-semibold text-lg">AutoSave Deposit</p>
                        <span id="autosave-status" class="text-[#D8D8D8] text-xs">Turn off Autosave</span>
                    </div>

                    <button id="toggle-autosave"
                        class="relative w-12 h-6 rounded-full bg-white border border-[#B0B0B080] focus:outline-none transition-colors duration-300">
                        <span id="toggle-knob"
                            class="absolute left-0.5 top-0.5 w-5 h-5 bg-blue-700 rounded-full transition-all duration-400">
                        </span>
                    </button>
                </div>

                <script>
                    const button = document.getElementById('toggle-autosave');
                    const knob = document.getElementById('toggle-knob');
                    const status = document.getElementById('autosave-status');

                    let isOn = false;

                    button.addEventListener('click', () => {
                        isOn = !isOn;

                        if (isOn) {
                            // ON state
                            button.classList.remove('bg-white');
                            button.classList.add('bg-blue-700');

                            knob.classList.remove('bg-blue-700', 'left-0.5');
                            knob.classList.add('bg-white', 'left-6');

                            status.textContent = 'Autosave is On';
                        } else {
                            // OFF state
                            button.classList.remove('bg-blue-700');
                            button.classList.add('bg-white');

                            knob.classList.remove('bg-white', 'left-6');
                            knob.classList.add('bg-blue-700', 'left-0.5');

                            status.textContent = 'Turn off Autosave';
                        }
                    });
                </script>





            </section>


            <div class="lg:px-8">
                <div class="flex gap-8 justify-center items-center py-6">
                    <div class="flex flex-col gap-1">
                        <div class="flex justify-center items-center rounded-2xl bg-[#D9D9D963] w-12 h-12">
                            <svg width="21" height="29" viewBox="0 0 21 29" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3 28.25C2.3125 28.25 1.72396 28.0052 1.23438 27.5156C0.744792 27.026 0.5 26.4375 0.5 25.75V12C0.5 11.3125 0.744792 10.724 1.23438 10.2344C1.72396 9.74479 2.3125 9.5 3 9.5H6.75V12H3V25.75H18V12H14.25V9.5H18C18.6875 9.5 19.276 9.74479 19.7656 10.2344C20.2552 10.724 20.5 11.3125 20.5 12V25.75C20.5 26.4375 20.2552 27.026 19.7656 27.5156C19.276 28.0052 18.6875 28.25 18 28.25H3ZM9.25 19.5V5.53125L7.25 7.53125L5.5 5.75L10.5 0.75L15.5 5.75L13.75 7.53125L11.75 5.53125V19.5H9.25Z"
                                    fill="#3346B9" />
                            </svg>



                        </div>
                        <span class="text-[#3346B9] text-[12px]">Withdraw</span>
                    </div>

                    <div class="flex flex-col gap-1">
                        <div class="flex justify-center items-center rounded-2xl bg-[#D9D9D963] w-12 h-12">
                            <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.875 9.25C3.66667 9.25 2.63542 8.82292 1.78125 7.96875C0.927083 7.11458 0.5 6.08333 0.5 4.875C0.5 3.66667 0.927083 2.63542 1.78125 1.78125C2.63542 0.927083 3.66667 0.5 4.875 0.5C6.08333 0.5 7.11458 0.927083 7.96875 1.78125C8.82292 2.63542 9.25 3.66667 9.25 4.875C9.25 6.08333 8.82292 7.11458 7.96875 7.96875C7.11458 8.82292 6.08333 9.25 4.875 9.25ZM4.875 6.75C5.39583 6.75 5.83854 6.56771 6.20312 6.20312C6.56771 5.83854 6.75 5.39583 6.75 4.875C6.75 4.35417 6.56771 3.91146 6.20312 3.54688C5.83854 3.18229 5.39583 3 4.875 3C4.35417 3 3.91146 3.18229 3.54688 3.54688C3.18229 3.91146 3 4.35417 3 4.875C3 5.39583 3.18229 5.83854 3.54688 6.20312C3.91146 6.56771 4.35417 6.75 4.875 6.75ZM16.125 20.5C14.9167 20.5 13.8854 20.0729 13.0313 19.2188C12.1771 18.3646 11.75 17.3333 11.75 16.125C11.75 14.9167 12.1771 13.8854 13.0313 13.0313C13.8854 12.1771 14.9167 11.75 16.125 11.75C17.3333 11.75 18.3646 12.1771 19.2188 13.0313C20.0729 13.8854 20.5 14.9167 20.5 16.125C20.5 17.3333 20.0729 18.3646 19.2188 19.2188C18.3646 20.0729 17.3333 20.5 16.125 20.5ZM16.125 18C16.6458 18 17.0885 17.8177 17.4531 17.4531C17.8177 17.0885 18 16.6458 18 16.125C18 15.6042 17.8177 15.1615 17.4531 14.7969C17.0885 14.4323 16.6458 14.25 16.125 14.25C15.6042 14.25 15.1615 14.4323 14.7969 14.7969C14.4323 15.1615 14.25 15.6042 14.25 16.125C14.25 16.6458 14.4323 17.0885 14.7969 17.4531C15.1615 17.8177 15.6042 18 16.125 18ZM2.25 20.5L0.5 18.75L18.75 0.5L20.5 2.25L2.25 20.5Z"
                                    fill="#3346B9" />
                            </svg>




                        </div>
                        <span class="text-[#3346B9] text-[12px]">Interest</span>
                    </div>


                    <div class="flex flex-col gap-1">
                        <div class="flex justify-center items-center rounded-2xl bg-[#D9D9D963] w-12 h-12">
                            <svg width="30" height="29" viewBox="0 0 30 29" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15 28.625L10.8125 24.5H5V18.6875L0.875 14.5L5 10.3125V4.5H10.8125L15 0.375L19.1875 4.5H25V10.3125L29.125 14.5L25 18.6875V24.5H19.1875L15 28.625ZM15 20.75C16.7292 20.75 18.2031 20.1406 19.4219 18.9219C20.6406 17.7031 21.25 16.2292 21.25 14.5C21.25 12.7708 20.6406 11.2969 19.4219 10.0781C18.2031 8.85938 16.7292 8.25 15 8.25C13.2708 8.25 11.7969 8.85938 10.5781 10.0781C9.35938 11.2969 8.75 12.7708 8.75 14.5C8.75 16.2292 9.35938 17.7031 10.5781 18.9219C11.7969 20.1406 13.2708 20.75 15 20.75ZM15 18.25C13.9583 18.25 13.0729 17.8854 12.3438 17.1562C11.6146 16.4271 11.25 15.5417 11.25 14.5C11.25 13.4583 11.6146 12.5729 12.3438 11.8438C13.0729 11.1146 13.9583 10.75 15 10.75C16.0417 10.75 16.9271 11.1146 17.6562 11.8438C18.3854 12.5729 18.75 13.4583 18.75 14.5C18.75 15.5417 18.3854 16.4271 17.6562 17.1562C16.9271 17.8854 16.0417 18.25 15 18.25ZM15 25.125L18.125 22H22.5V17.625L25.625 14.5L22.5 11.375V7H18.125L15 3.875L11.875 7H7.5V11.375L4.375 14.5L7.5 17.625V22H11.875L15 25.125Z"
                                    fill="#3346B9" />
                            </svg>





                        </div>
                        <span class="text-[#3346B9] text-[12px]">Settings</span>
                    </div>
                </div>


            </div>


            <div class="lg:px-8">
                <section class=mt-7>
                    <h4 class=" font-semibold text-[16px]">Recent Activities</h4>
                    <div
                        class="pt-6 pb-3 flex justify-between border-b-[#00000021] border border-l-0 border-r-0 border-t-0">
                        <div class="ml-4">
                            <p class="font-semibold text-sm">AutoSave Deposit</p>
                            <span class="text-[#B0B7E4]  text-xs">2024-03-23 <span class="ml-2"> 05:13:53 </span> </span>
                        </div>
                        <span class="text-[#12B76A]  text-sm">+₦500</span>
                    </div>


                    <div
                        class="pt-6 pb-3 flex justify-between border-b-[#00000021] border border-l-0 border-r-0 border-t-0">
                        <div class="ml-4">
                            <p class="font-semibold text-sm">Interest</p>
                            <span class="text-[#B0B7E4]  text-xs">2024-03-23 <span class="ml-2"> 05:13:53 </span>
                            </span>
                        </div>
                        <span class="text-[#12B76A]  text-sm">+₦2.52</span>
                    </div>


                    <div class="pt-6 pb-3 flex justify-between ">
                        <div class="ml-4">
                            <p class="font-semibold text-sm">AutoSave Deposit</p>
                            <span class="text-[#B0B7E4]  text-xs">2024-03-23 <span class="ml-2"> 05:13:53 </span>
                            </span>
                        </div>
                        <span class="text-[#12B76A]  text-sm">+₦500</span>
                    </div>

                </section>


            </div>


        </section>
    </section>
@endsection
