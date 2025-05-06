


@extends('layouts.new-guest')
@section('head')
<title>VASTel | vasSave</title>
@endsection
@section('body')
<section class="lg:px-[2rem] px-4">
    <div class="text-[#0018A8] lg:text-lg  lg:flex gap-4 hidden ">
        <span class="flex justify-center items-center"> <svg width="8" height="13" viewBox="0 0 8 13" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.875 12.0416L1.33333 6.49992L6.875 0.958252" stroke="#0018A8" stroke-width="1.58333" stroke-linecap="round" stroke-linejoin="round"/>
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
                <input 
                    type="text" 
                    placeholder="100~99,000" 
                    class="pl-7 w-full border border-l-0 border-r-0 border-t-0 border-[#B0B0B080] focus:outline-none"
                >
            </div>
        </label>
        {{-- <div class="mt-8 flex justify-between">
            <div>
             <p class="text-[#646464] font-semibold text-lg">AutoSave Deposit</p>
             <span class="text-[#D8D8D8] text-xs">Turn off Autosave</span>
            </div>
            <button>
                <svg width="38" height="24" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 11.9999C0 10.4743 0.31543 9.01618 0.946289 7.62553C1.57715 6.23488 2.42139 5.03786 3.479 4.03448C4.53662 3.0311 5.79834 2.23016 7.26416 1.63165C8.72998 1.03314 10.2669 0.733887 11.875 0.733887H26.125C27.7331 0.733887 29.27 1.03314 30.7358 1.63165C32.2017 2.23016 33.4634 3.0311 34.521 4.03448C35.5786 5.03786 36.4229 6.23488 37.0537 7.62553C37.6846 9.01618 38 10.4743 38 11.9999C38 13.5255 37.6846 14.9836 37.0537 16.3743C36.4229 17.7649 35.5786 18.962 34.521 19.9653C33.4634 20.9687 32.2017 21.7697 30.7358 22.3682C29.27 22.9667 27.7331 23.2659 26.125 23.2659H11.875C10.2669 23.2659 8.72998 22.9667 7.26416 22.3682C5.79834 21.7697 4.53662 20.9687 3.479 19.9653C2.42139 18.962 1.57715 17.7649 0.946289 16.3743C0.31543 14.9836 0 13.5255 0 11.9999ZM26.125 21.0127C27.4115 21.0127 28.6392 20.7751 29.8081 20.2998C30.9771 19.8245 31.9883 19.182 32.8418 18.3723C33.6953 17.5625 34.3726 16.6031 34.8735 15.4941C35.3745 14.3851 35.625 13.2204 35.625 11.9999C35.625 10.7794 35.3745 9.61468 34.8735 8.50568C34.3726 7.39668 33.6953 6.43731 32.8418 5.62757C31.9883 4.81782 30.9771 4.17531 29.8081 3.70002C28.6392 3.22473 27.4115 2.98709 26.125 2.98709C24.8385 2.98709 23.6108 3.22473 22.4419 3.70002C21.2729 4.17531 20.2617 4.81782 19.4082 5.62757C18.5547 6.43731 17.8774 7.39668 17.3765 8.50568C16.8755 9.61468 16.625 10.7794 16.625 11.9999C16.625 13.2204 16.8755 14.3851 17.3765 15.4941C17.8774 16.6031 18.5547 17.5625 19.4082 18.3723C20.2617 19.182 21.2729 19.8245 22.4419 20.2998C23.6108 20.7751 24.8385 21.0127 26.125 21.0127Z" fill="#0018A8"/>
                    </svg>
                    

            </button>
        </div> --}}

        <div class="mt-8 flex justify-between items-center">
            <div>
              <p class="text-[#646464] font-semibold text-lg">AutoSave Deposit</p>
              <span id="autosave-status" class="text-[#D8D8D8] text-xs">Turn off Autosave</span>
            </div>
          
            <button 
              id="toggle-autosave" 
              class="relative w-12 h-6 rounded-full bg-white border border-[#B0B0B080] focus:outline-none transition-colors duration-300"
            >
              <span 
                id="toggle-knob" 
                class="absolute left-0.5 top-0.5 w-5 h-5 bg-blue-700 rounded-full transition-all duration-400"
              ></span>
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
    <select 
      id="saving-frequency" 
      name="saving-frequency" 
      class="w-full border border-[#B0B0B080] px-3 py-2 rounded-md text-gray-700 bg-white"
    >
      <option value="" disabled selected class="text-[#8A8A8A] text-sm">Select savings frequency</option>
      <option value="daily">Daily</option>
      <option value="weekly">Weekly</option>
      <option value="bi-weekly">Bi-weekly</option>
      <option value="monthly">Monthly</option>
    </select>
  </label>

  <label for="start-date" class="mt-5 block">
    <h4 class="mb-2 text-[#333333]">Start date</h4>
    <select 
      id="saving-frequency" 
      name="saving-frequency" 
      class="w-full border border-[#B0B0B080] px-3 py-2 rounded-md text-gray-700 bg-white"
    >
      <option value="" disabled selected class="text-[#8A8A8A] text-sm">Set start date</option>
      <option value="#">#</option>
      <option value="#">#</option>
      <option value="#">#</option>
      <option value="#">#</option>
    </select>
  </label>

  <label for="Time" class="mt-5 block">
    <h4 class="mb-2 text-[#333333]">Time</h4>
    <select 
      id="Time" 
      name="Time" 
      class="w-full border border-[#B0B0B080] px-3 py-2 rounded-md text-gray-700 bg-white"
    >
      <option value="" disabled selected class="text-[#8A8A8A] text-sm">Select preferred time</option>
      <option value="#">#</option>
      <option value="#">#</option>
      <option value="#">#</option>
      <option value="#">#</option>
    </select>
  </label>
  <section class="bg-[#B0B7E436] flex justify-center flex-col text-center items-center mt-5 border border-[#0018A8B5] rounded-2xl py-4 w-full px-1 lg:px-1">
         <p class="text-[#333333] font-semibold text-[13px] lg:text-[16px] ">₦10,000.04 will be saved monthly for 365 days</p>

         <p class="mt-3 text-[#333333] font-semibold text-[13px]  lg:text-[16px]">You will receive 156,000 at maturity </p>
  </section>
  <div class="justify-center flex items-center mt-6">
    <button class="bg-[#0018A8] text-white px-28 rounded-lg py-2 text-sm ">Deposit</button>
  </div>
          
    </section>

    



</section>
    
</section>

   
    
@endsection
