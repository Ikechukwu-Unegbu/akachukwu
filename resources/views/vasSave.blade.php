@extends('layouts.new-guest')
@section('head')
    <title>VASTel | vasSave</title>
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
        <section class="lg:border border-[#00000038] lg:my-8 lg:py-5  mt-8 py-4 lg:w-6/12  ">
            <section class="p-6">
                <label for="" class="block">
                    <h4 class="text-lg mb-2">Amount</h4>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-lg">₦</span>
                        <input type="text" placeholder="100~99,000"
                            class="pl-7 w-full border border-l-0 border-r-0 border-t-0 border-[#B0B0B080] focus:outline-none">
                    </div>
                </label>


                <div class="mt-8 flex justify-between items-center">
                    <div>
                        <p class="text-[#646464] font-semibold text-lg">AutoSave Deposit</p>
                        <span id="autosave-status" class="text-[#D8D8D8] text-xs">Turn off Autosave</span>
                    </div>

                    <button id="toggle-autosave"
                        class="relative w-12 h-6 rounded-full bg-white border border-[#B0B0B080] focus:outline-none transition-colors duration-300">
                        <span id="toggle-knob"
                            class="absolute left-0.5 top-0.5 w-5 h-5 bg-blue-700 rounded-full transition-all duration-400"></span>
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


                <label for="saving-frequency" class="mt-5 block">
                    <h4 class="mb-2 text-[#333333]">Saving Frequency</h4>
                    <select id="saving-frequency" name="saving-frequency"
                        class="w-full border border-[#B0B0B080] px-3 py-2 rounded-md text-gray-700 bg-white">
                        <option value="" disabled selected class="text-[#8A8A8A] text-sm">Select savings frequency
                        </option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="bi-weekly">Bi-weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </label>

                <label for="start-date" class="mt-5 block">
                    <h4 class="mb-2 text-[#333333]">Start date</h4>
                    <select id="saving-frequency" name="saving-frequency"
                        class="w-full border border-[#B0B0B080] px-3 py-2 rounded-md text-gray-700 bg-white">
                        <option value="" disabled selected class="text-[#8A8A8A] text-sm">Set start date</option>
                        <option value="#">#</option>
                        <option value="#">#</option>
                        <option value="#">#</option>
                        <option value="#">#</option>
                    </select>
                </label>

                <label for="Time" class="mt-5 block">
                    <h4 class="mb-2 text-[#333333]">Time</h4>
                    <select id="Time" name="Time"
                        class="w-full border border-[#B0B0B080] px-3 py-2 rounded-md text-gray-700 bg-white">
                        <option value="" disabled selected class="text-[#8A8A8A] text-sm">Select preferred time
                        </option>
                        <option value="#">#</option>
                        <option value="#">#</option>
                        <option value="#">#</option>
                        <option value="#">#</option>
                    </select>
                </label>
                <section
                    class="bg-[#B0B7E436] flex justify-center flex-col text-center items-center mt-5 border border-[#0018A8B5] rounded-2xl py-4 w-full px-1 lg:px-1">
                    <p class="text-[#333333] font-semibold text-[13px] lg:text-[16px] ">₦10,000.04 will be saved monthly for
                        365 days</p>

                    <p class="mt-3 text-[#333333] font-semibold text-[13px]  lg:text-[16px]">You will receive 156,000 at
                        maturity </p>
                </section>
                <div class="justify-center flex items-center mt-6">
                    <button class="bg-[#0018A8] text-white px-28 rounded-lg py-2 text-sm ">Deposit</button>
                </div>

            </section>





        </section>

    </section>
@endsection
