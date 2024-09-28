@extends('layouts.new-guest')

@section('body')
<div class="w-full lg:w-[60%] bg-white p-6">
        <!-- Back button -->
        <a href="{{route('dashboard')}}" class="inline-flex items-center mb-4 text-blue-600 hover:text-blue-800">
            <i class="fas fa-chevron-left mr-2"></i>
            Back
        </a>

        <h1 class="text-2xl font-bold mb-6">Change Password</h1>

        <form method="POST" action="{{route('update.password')}}">
            @csrf 

            <div class="max-w-2xl mx-auto mt-6 mb-5">
                <!-- Success Message -->
                @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.354 5.647a.5.5 0 00-.707.707l3.646 3.646-3.646 3.646a.5.5 0 00.707.707L10 10.707l3.646 3.646a.5.5 0 00.707-.707L10.707 10l3.646-3.646a.5.5 0 000-.707z"/>
                    </svg>
                    </span>
                </div>
                @endif

                <!-- Error Message -->
                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">Something went wrong.</span>
                    <ul class="mt-2 ml-4 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.354 5.647a.5.5 0 00-.707.707l3.646 3.646-3.646 3.646a.5.5 0 00.707.707L10 10.707l3.646 3.646a.5.5 0 00.707-.707L10.707 10l3.646-3.646a.5.5 0 000-.707z"/>
                    </svg>
                    </span>
                </div>
                @endif

                <!-- General Info Message -->
                @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-vastel_blue px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Heads up!</strong>
                    <span class="block sm:inline">{{ session('info') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-blue-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.354 5.647a.5.5 0 00-.707.707l3.646 3.646-3.646 3.646a.5.5 0 00.707.707L10 10.707l3.646 3.646a.5.5 0 00.707-.707L10.707 10l3.646-3.646a.5.5 0 000-.707z"/>
                    </svg>
                    </span>
                </div>
                @endif
            </div>

            <!-- Old Password -->
            <div class="mb-4 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="old-password" name="current_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Old Password" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>

            <!-- New Password -->
            <div class="mb-4 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="new-password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="New Password" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>

            <!-- Confirm New Password -->
            <div class="mb-6 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="confirm-new-password" name="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Confirm New Password" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>

            <!-- Save Changes Button -->
            <button type="submit" class="text-white bg-vastel_blue hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-[8rem] mb-6">
                Save Changes
            </button>
        </form>

        <!-- Change Security Pin Section -->
        <div class="border-t pt-6">
            <h2 class="text-xl font-semibold mb-2">Change Security Pin</h2>
            <p class="text-gray-600 text-sm mb-4">Send a request to change your security pin</p>
            <button type="button" data-modal-target="step1" data-modal-toggle="step1" class="text-white bg-vastel_blue hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-[16rem]">
                Request Security Pin Change
            </button>
        </div>
    </div>



    <!-- account deactivation -->
    <div class="w-full lg:w-[60%] bg-white p-6">
        <!-- Title -->
        <h2 class="text-xl font-bold text-blue-600 mb-4">Delete/Deactivate Account</h2>
        
        <!-- Deactivate Account -->
        <a type="button" data-modal-target="deactivateAccountModal" data-modal-toggle="deactivateAccountModal" class="flex items-center p-4 mb-4 bg-gray-50 rounded-lg shadow-sm">
            <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-trash-alt text-blue-600 fa-lg"></i> <!-- Trash icon -->
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Deactivate account</h3>
                    <p class="text-sm text-gray-500">Temporarily deactivate my account</p>
                </div>
                <div class="ml-auto">
                    <i class="fas fa-chevron-right text-gray-400"></i> <!-- Right chevron icon -->
            </div>
        </a>

        <div id="deactivateAccountModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold dark:text-white">
                    Deactivate Account
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deactivateAccountModal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    </button>
                </div>
                <!-- Modal body -->
                    <div class="p-6 text-center">
                        <p class="mb-5 text-lg font-semibold dark:text-gray-400">Deactivate your Vastel account?<br>Your Vastel account will be temporarily closed until reactivation.</p>

                        <div class="flex flex-col justify-between gap-7">
                            <form action="{{route('logout')}}" method="post">
                                @csrf 
                                <button  type="submit" class="text-white bg-vastel_blue hover:bg-vastel_blue focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2">
                                Deactivate Account
                                </button>
                            </form>
                            <button data-modal-hide="deactivateAccountModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border-none border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Delete Account -->
        <a type="button" data-modal-target="deleteAccountModal" data-modal-toggle="deleteAccountModal" class="flex items-center p-4 bg-gray-50 rounded-lg shadow-sm">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-trash-alt text-blue-600 fa-lg"></i> <!-- Trash icon -->
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Delete account</h3>
                <p class="text-sm text-gray-500">Permanently delete my account</p>
            </div>
            <div class="ml-auto">
                <i class="fas fa-chevron-right text-gray-400"></i> <!-- Right chevron icon -->
            </div>
        </a>
    </div>

    <div id="deleteAccountModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold dark:text-white">
                Delete Account
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteAccountModal">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                </button>
            </div>
            <!-- Modal body -->
                <div class="p-6 text-center">
                    <p class="mb-5 text-lg font-semibold dark:text-gray-400">Deactivate your Vastel account?<br>You will lose your data and account history in the Vastel app</p>

                    <div class="flex flex-col justify-between gap-7">
                        <form action="{{route('delete')}}" method="post">
                            @csrf 
                            <div class="mb-4 relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Enter Password to Continue" required>
                                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-eye text-gray-400"></i>
                                </button>
                            </div>
                            <button  type="submit" class="text-white bg-[#FF0000] focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2">
                            Delete Account
                            </button>
                        </form>
                        <button data-modal-hide="deactivateAccountModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border-none border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- end of account deactivation -->


<!-- Step 1: Reset Success Modal -->
<div id="step1" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
  <div class="bg-white rounded-lg shadow p-6 w-1/3 text-center">
    <div class="text-green-500 text-6xl mb-4">✓</div>
    <p class="text-lg font-semibold">You have successfully reset your transaction pin!</p>
    <button onclick="nextStep(2)" class="bg-blue-500 text-white px-4 py-2 rounded mt-6">Done</button>
  </div>
</div>

<!-- Step 2: OTP Verification Modal -->
<div id="step2" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
  <div class="bg-white rounded-lg shadow p-6 w-1/3 text-center">
    <h2 class="text-lg font-semibold mb-2">OTP Verification</h2>
    <p>A 6-digit code has been sent to your email address.</p>
    <div class="flex justify-center gap-2 mt-4">
      <input type="text" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
      <input type="text" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
      <input type="text" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
      <input type="text" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
      <input type="text" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
      <input type="text" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
    </div>
    <p class="text-blue-500 mt-4 cursor-pointer">Didn't receive the OTP? <span class="underline">Resend code</span></p>
    <button onclick="nextStep(3)" class="bg-blue-500 text-white px-4 py-2 rounded mt-6">Continue</button>
  </div>
</div>

<!-- Step 3: Enter New Transaction Pin Modal -->
<div id="step3" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
  <div class="bg-white rounded-lg shadow p-6 w-1/3 text-center">
    <h2 class="text-lg font-semibold mb-2">Change Transaction Pin</h2>
    <p>Enter your new 4-digit transaction pin.</p>
    <div class="flex justify-center gap-2 mt-4">
      <input type="password" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
      <input type="password" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
      <input type="password" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
      <input type="password" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
    </div>
    <div class="flex justify-center gap-2 mt-4">
      <input type="password" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
      <input type="password" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
      <input type="password" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
      <input type="password" maxlength="1" class="w-12 h-12 text-center border border-gray-300 rounded" />
    </div>
    <button class="bg-blue-500 text-white px-4 py-2 rounded mt-6">Continue</button>
  </div>
</div>

@endsection 