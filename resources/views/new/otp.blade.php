@extends('layouts.new-dashboard')

@section('body')

 
<!-- Trigger Buttons -->
<div class="space-x-4 p-6">
  <button id="openPinResetSuccessModal" class="bg-vastel_blue text-white py-2 px-4 rounded hover:bg-blue-600">
    Show Pin Reset Success
  </button>

  <button id="openOTPModal" class="bg-vastel_blue text-white py-2 px-4 rounded hover:bg-blue-600">
    Show OTP Verification
  </button>

  <button id="openChangePinModal" class="bg-vastel_blue text-white py-2 px-4 rounded hover:bg-blue-600">
    Show Change Transaction Pin
  </button>
</div>

<!-- Pin Reset Success Modal -->
<div id="pinResetSuccessModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
  <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm text-center">
    <div class="text-green-500 text-6xl">
      &#10004;
    </div>
    <h2 class="text-lg font-semibold mt-4">You have successfully reset your transaction pin!</h2>
    <button id="closePinResetSuccessModal" class="mt-6 bg-vastel_blue text-white py-2 px-4 rounded hover:bg-blue-600">
      Done
    </button>
  </div>
</div>

<!-- OTP Modal -->
<div id="otpModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
  <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm text-center">
    <h2 class="text-lg font-semibold">OTP Verification</h2>
    <p class="mt-2 text-sm text-gray-600">A 6-digit code has been sent to your email address: <strong>jane@email.com</strong></p>
    
    <!-- OTP Inputs -->
    <div class="flex justify-center mt-4 space-x-2">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
      <!-- <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1"> -->
    </div>

    <p class="mt-4 text-sm text-gray-500">
      Didn't receive the OTP? <a href="#" class="text-vastel_blue hover:underline">Resend code</a>
    </p>

    <button id="closeOTPModal" class="mt-4 bg-vastel_blue text-white py-2 px-4 rounded hover:bg-blue-600">
      Continue
    </button>
  </div>
</div>

<!-- Change Transaction Pin Modal -->
<div id="changePinModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
  <div class="bg-white p-6 rounded-lg shadow-lg w-[80%] md:w-[40%] text-center">
    <h2 class="text-lg font-semibold">Change Transaction Pin</h2>
    <p class="mt-2 text-sm text-gray-600">4-digit new transaction pin</p>

    <!-- New Pin Inputs -->
    <div class="flex justify-center mt-4 space-x-2">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
    </div>

    <p class="mt-4 text-sm text-gray-600">Confirm your transaction pin</p>

    <!-- Confirm Pin Inputs -->
    <div class="flex justify-center mt-4 space-x-2">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
      <input type="text" class="w-12 h-12 text-center border rounded-md" maxlength="1">
    </div>

    <button id="closeChangePinModal" class="mt-6 bg-vastel_blue text-white py-2 px-4 rounded hover:bg-blue-600">
      Continue
    </button>
  </div>
</div>


<script>
  // Get modal elements
  const pinResetSuccessModal = document.getElementById('pinResetSuccessModal');
  const otpModal = document.getElementById('otpModal');
  const changePinModal = document.getElementById('changePinModal');

  // Get open modal buttons
  const openPinResetSuccessModalBtn = document.getElementById('openPinResetSuccessModal');
  const openOTPModalBtn = document.getElementById('openOTPModal');
  const openChangePinModalBtn = document.getElementById('openChangePinModal');

  // Get close modal buttons
  const closePinResetSuccessModalBtn = document.getElementById('closePinResetSuccessModal');
  const closeOTPModalBtn = document.getElementById('closeOTPModal');
  const closeChangePinModalBtn = document.getElementById('closeChangePinModal');

  // Open Modals
  openPinResetSuccessModalBtn.addEventListener('click', () => {
    pinResetSuccessModal.classList.remove('hidden');
  });

  openOTPModalBtn.addEventListener('click', () => {
    otpModal.classList.remove('hidden');
  });

  openChangePinModalBtn.addEventListener('click', () => {
    changePinModal.classList.remove('hidden');
  });

  // Close Modals
  closePinResetSuccessModalBtn.addEventListener('click', () => {
    pinResetSuccessModal.classList.add('hidden');
  });

  closeOTPModalBtn.addEventListener('click', () => {
    otpModal.classList.add('hidden');
  });

  closeChangePinModalBtn.addEventListener('click', () => {
    changePinModal.classList.add('hidden');
  });

  // Optionally, close modal when clicking outside
  window.addEventListener('click', (e) => {
    if (e.target === pinResetSuccessModal) {
      pinResetSuccessModal.classList.add('hidden');
    }
    if (e.target === otpModal) {
      otpModal.classList.add('hidden');
    }
    if (e.target === changePinModal) {
      changePinModal.classList.add('hidden');
    }
  });
</script>


@endsection 