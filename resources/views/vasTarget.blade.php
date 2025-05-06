{{-- 


@extends('layouts.new-guest')
@section('head')
<title>VASTel | vasTarget</title>
@endsection
@section('body')
<section class="lg:px-[2rem] px-4">
    <div class="text-[#0018A8] lg:text-lg  lg:flex gap-4 hidden ">
        <span class="font-bold"> < </span> 
        <span>Back</span>
     </div>

     <section class="lg:border border-[#00000038] lg:my-8 lg:py-5  mt-8 py-4 lg:w-6/12  ">
        <section class="p-6">
            <h3 class="text-[#000000] text-lgs">Reach your financial goals with ease</h3>
            <label for="" class="block mt-5">
                <h4 class=" mb-2 text-[#333333]">How much are you aiming for?</h4>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-lg">₦</span>
                    <input 
                        type="text" 
                        placeholder="100~99,000" 
                        class="pl-7 w-full border border-[#B0B0B080] focus:outline-none"
                    >
                </div>
            </label>

            <label for="Target name" class="block mt-5">
                <h4 class=" mb-2 text-[#333333]">Target name (Optional)</h4>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-lg">₦</span>
                    <input 
                        type="text" 
                        placeholder="Please enter" 
                        class="pl-7 w-full border border-[#B0B0B080] focus:outline-none"
                    >
                </div>
            </label>

            <label for="Set Start Date" class="block mt-5">
                <h4 class=" mb-2 text-[#333333]">Set Start Date</h4>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-lg">₦</span>
                    <input 
                        type="date" 
                        placeholder="Set Start Date" 
                        class="pl-7 w-full border border-[#B0B0B080] focus:outline-none"
                    >
                </div>
            </label>

            <label for="Set Maturity Date" class="block mt-5">
                <h4 class=" mb-2 text-[#333333]">Set Maturity Date</h4>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-lg">₦</span>
                    <input 
                        type="date" 
                        placeholder="Set Maturity Date" 
                        class="pl-7 w-full border border-[#B0B0B080] focus:outline-none"
                    >
                </div>
            </label>

            <label for="saving-frequency" class="mt-5 block">
                <h4 class="mb-2 text-[#333333]">How will you prefer to save?</h4>
                <select 
                  id="saving-frequency" 
                  name="saving-frequency" 
                  class="w-full border border-[#B0B0B080] px-3 py-2 rounded-md text-gray-700 bg-white"
                >
                  <option value="" disabled selected class="text-[#8A8A8A] text-sm">Daily</option>
                  <option value="daily">Daily</option>
                  <option value="weekly">Weekly</option>
                  <option value="bi-weekly">Bi-weekly</option>
                  <option value="monthly">Monthly</option>
                </select>
              </label>

              <label for="saving-frequency" class="mt-5 block">
                <h4 class="mb-2 text-[#333333]">Time</h4>
                <select 
                  id="saving-frequency" 
                  name="saving-frequency" 
                  class="w-full border border-[#B0B0B080] px-3 py-2 rounded-md text-gray-700 bg-white"
                >
                  <option value="" disabled selected class="text-[#8A8A8A] text-sm">Select a time </option>
                  <option value="daily">morning </option>
                  <option value="weekly">afternoon</option>
                  <option value="bi-weekly">night</option>
                </select>
              </label>


              <div class="justify-center flex items-center mt-6">
                <button class="bg-[#B0B7E4] text-white px-28 rounded-lg py-2 text-sm ">Deposit</button>
              </div>

              


        </section>
     </section>
     

    
</section>

   
    
@endsection --}}




@extends('layouts.new-guest')
@section('head')
<title>VASTel | vasTarget</title>
@endsection

@section('body')
<section class="lg:px-[2rem] px-4">
    <div class="text-[#0018A8] lg:text-lg  lg:flex gap-4 hidden ">
        <span class="font-bold"> < </span> 
        <span>Back</span>
    </div>

    <section class="lg:border border-[#00000038] lg:my-8 lg:py-5  mt-8 py-4 lg:w-6/12">
        <section class="p-6">
            <h3 class="text-[#000000] text-lgs">Reach your financial goals with ease</h3>

            <!-- Amount -->
            <label for="amount" class="block mt-5">
                <h4 class="mb-2 text-[#333333]">How much are you aiming for?</h4>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-lg">₦</span>
                    <input 
                        id="amount"
                        type="text" 
                        placeholder="100~99,000" 
                        class="pl-7 w-full border border-[#B0B0B080] focus:outline-none"
                    >
                </div>
                <small id="amount-error" class="text-red-500 text-sm hidden">Enter a valid amount between ₦100 - ₦99,000</small>
            </label>

            <!-- Target Name (Optional) -->
            <label for="target-name" class="block mt-5">
                <h4 class="mb-2 text-[#333333]">Target name (Optional)</h4>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-lg">₦</span>
                    <input 
                        id="target-name"
                        type="text" 
                        placeholder="Please enter" 
                        class="pl-7 w-full border border-[#B0B0B080] focus:outline-none"
                    >
                </div>
            </label>

            <!-- Start Date -->
            <label for="start-date" class="block mt-5">
                <h4 class="mb-2 text-[#333333]">Set Start Date</h4>
                <div class="relative">
                    <input 
                        id="start-date"
                        type="date" 
                        class="pl-3 w-full border border-[#B0B0B080] focus:outline-none"
                    >
                </div>
                <small id="start-date-error" class="text-red-500 text-sm hidden">Start date is required</small>
            </label>

            <!-- Maturity Date -->
            <label for="maturity-date" class="block mt-5">
                <h4 class="mb-2 text-[#333333]">Set Maturity Date</h4>
                <div class="relative">
                    <input 
                        id="maturity-date"
                        type="date" 
                        class="pl-3 w-full border border-[#B0B0B080] focus:outline-none"
                    >
                </div>
                <small id="maturity-date-error" class="text-red-500 text-sm hidden">Maturity date must be after start date</small>
            </label>

            <!-- Saving Frequency -->
            <label for="saving-frequency" class="mt-5 block">
                <h4 class="mb-2 text-[#333333]">How will you prefer to save?</h4>
                <select 
                  id="saving-frequency" 
                  class="w-full border border-[#B0B0B080] px-3 py-2 rounded-md text-gray-700 bg-white"
                >
                  <option value="" disabled selected>Daily</option>
                  <option value="daily">Daily</option>
                  <option value="weekly">Weekly</option>
                  <option value="bi-weekly">Bi-weekly</option>
                  <option value="monthly">Monthly</option>
                </select>
                <small id="saving-frequency-error" class="text-red-500 text-sm hidden">Please select a saving frequency</small>
            </label>

            <!-- Time -->
            <label for="time-select" class="mt-5 block">
                <h4 class="mb-2 text-[#333333]">Time</h4>
                <select 
                  id="time-select" 
                  class="w-full border border-[#B0B0B080] px-3 py-2 rounded-md text-gray-700 bg-white"
                >
                  <option value="" disabled selected>Select a time</option>
                  <option value="morning">Morning</option>
                  <option value="afternoon">Afternoon</option>
                  <option value="night">Night</option>
                </select>
                <small id="time-error" class="text-red-500 text-sm hidden">Please select a preferred time</small>
            </label>

            <!-- Button -->
            {{-- <div class="justify-center flex items-center mt-6">
                <button 
                    id="deposit-btn" 
                    class="bg-[#B0B7E4] text-white px-28 rounded-lg py-2 text-sm cursor-not-allowed" 
                    disabled
                >
                    Deposit
                </button>
            </div> --}}
            <div class="justify-center flex items-center mt-6">
                <button 
                    id="deposit-btn" 
                    class="bg-[#B0B7E4] text-white px-28 rounded-lg py-2 text-sm cursor-not-allowed" 
                    disabled
                >
                    Deposit
                </button>
            </div>
        </section>
    </section>
</section>

<!-- Validation Script -->
{{-- <script>
    const amount = document.getElementById('amount');
    const startDate = document.getElementById('start-date');
    const maturityDate = document.getElementById('maturity-date');
    const savingFrequency = document.getElementById('saving-frequency');
    const timeSelect = document.getElementById('time-select');
    const depositBtn = document.getElementById('deposit-btn');

    const showError = (id, message = '') => {
        const el = document.getElementById(id);
        el.classList.remove('hidden');
        if (message) el.innerText = message;
    };

    const hideError = (id) => {
        document.getElementById(id).classList.add('hidden');
    };

    function validateForm() {
        let valid = true;

        // Amount validation
        const amt = parseFloat(amount.value.trim());
        if (isNaN(amt) || amt < 100 || amt > 99000) {
            showError('amount-error');
            valid = false;
        } else {
            hideError('amount-error');
        }

        // Start date validation
        if (!startDate.value.trim()) {
            showError('start-date-error');
            valid = false;
        } else {
            hideError('start-date-error');
        }

        // Maturity date validation
        if (!maturityDate.value.trim() || new Date(maturityDate.value) <= new Date(startDate.value)) {
            showError('maturity-date-error');
            valid = false;
        } else {
            hideError('maturity-date-error');
        }

        // Saving frequency
        if (!savingFrequency.value.trim()) {
            showError('saving-frequency-error');
            valid = false;
        } else {
            hideError('saving-frequency-error');
        }

        // Time select
        if (!timeSelect.value.trim()) {
            showError('time-error');
            valid = false;
        } else {
            hideError('time-error');
        }

        // Button state
        if (valid) {
            depositBtn.disabled = false;
            depositBtn.classList.remove('cursor-not-allowed');
            depositBtn.style.backgroundColor = '#0018A8';
        } else {
            depositBtn.disabled = true;
            depositBtn.classList.add('cursor-not-allowed');
            depositBtn.style.backgroundColor = '#B0B7E4';
        }
    }

    // Add event listeners
    [amount, startDate, maturityDate, savingFrequency, timeSelect].forEach(field => {
        field.addEventListener('input', validateForm);
        field.addEventListener('change', validateForm);
    });

    // Initial validation
    validateForm();
</script> --}}


<script>
    const amount = document.getElementById('amount');
    const startDate = document.getElementById('start-date');
    const maturityDate = document.getElementById('maturity-date');
    const savingFrequency = document.getElementById('saving-frequency');
    const timeSelect = document.getElementById('time-select');
    const depositBtn = document.getElementById('deposit-btn');

    const showError = (id, message = '') => {
        const el = document.getElementById(id);
        el.classList.remove('hidden');
        if (message) el.innerText = message;
    };

    const hideError = (id) => {
        document.getElementById(id).classList.add('hidden');
    };

    function validateForm() {
        let valid = true;

        const amt = parseFloat(amount.value.trim());
        if (isNaN(amt) || amt < 100 || amt > 99000) {
            showError('amount-error');
            valid = false;
        } else {
            hideError('amount-error');
        }

        if (!startDate.value.trim()) {
            showError('start-date-error');
            valid = false;
        } else {
            hideError('start-date-error');
        }

        if (!maturityDate.value.trim() || new Date(maturityDate.value) <= new Date(startDate.value)) {
            showError('maturity-date-error');
            valid = false;
        } else {
            hideError('maturity-date-error');
        }

        if (!savingFrequency.value.trim()) {
            showError('saving-frequency-error');
            valid = false;
        } else {
            hideError('saving-frequency-error');
        }

        if (!timeSelect.value.trim()) {
            showError('time-error');
            valid = false;
        } else {
            hideError('time-error');
        }

        if (valid) {
            depositBtn.disabled = false;
            depositBtn.classList.remove('cursor-not-allowed');
            depositBtn.style.backgroundColor = '#0018A8';
        } else {
            depositBtn.disabled = true;
            depositBtn.classList.add('cursor-not-allowed');
            depositBtn.style.backgroundColor = '#B0B7E4';
        }

        return valid;
    }

    [amount, startDate, maturityDate, savingFrequency, timeSelect].forEach(field => {
        field.addEventListener('input', validateForm);
        field.addEventListener('change', validateForm);
    });

    depositBtn.addEventListener('click', function (e) {
        e.preventDefault(); // prevent default form behavior
        if (validateForm()) {
            window.location.href = "{{ route('vasTargetDashboard') }}";
        }
    });

    validateForm(); // Run on load
</script>

@endsection

