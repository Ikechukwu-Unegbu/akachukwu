@extends('layouts.new-guest')

@section('body')
    <div class="w-full lg:w-[60%] bg-white p-6">
        <!-- Back button -->
        <a href="{{ route('settings.index') }}" class="inline-flex items-center mb-4 text-blue-600 hover:text-blue-800">
            <i class="fas fa-chevron-left mr-2"></i>
            Back
        </a>

        <h1 class="text-2xl font-bold mb-6">Change Password</h1>

        <form method="POST" action="{{ route('update.password') }}">
            @csrf

            <div class="max-w-2xl mx-auto mt-6 mb-5">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path
                                    d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.354 5.647a.5.5 0 00-.707.707l3.646 3.646-3.646 3.646a.5.5 0 00.707.707L10 10.707l3.646 3.646a.5.5 0 00.707-.707L10.707 10l3.646-3.646a.5.5 0 000-.707z" />
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
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">
                                <title>Close</title>
                                <path
                                    d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.354 5.647a.5.5 0 00-.707.707l3.646 3.646-3.646 3.646a.5.5 0 00.707.707L10 10.707l3.646 3.646a.5.5 0 00.707-.707L10.707 10l3.646-3.646a.5.5 0 000-.707z" />
                            </svg>
                        </span>
                    </div>
                @endif

                <!-- General Info Message -->
                @if (session('info'))
                    <div class="bg-blue-100 border border-blue-400 text-vastel_blue px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Heads up!</strong>
                        <span class="block sm:inline">{{ session('info') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-blue-500" role="button"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path
                                    d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.354 5.647a.5.5 0 00-.707.707l3.646 3.646-3.646 3.646a.5.5 0 00.707.707L10 10.707l3.646 3.646a.5.5 0 00.707-.707L10.707 10l3.646-3.646a.5.5 0 000-.707z" />
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
                <input type="password" id="old-password" name="current_password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                    placeholder="Old Password" required>
                <button type="button" id="toggle-old-password" class="absolute inset-y-0 right-0 flex items-center pr-3"
                    onclick="togglePassword('old-password', 'toggle-old-password')">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>

            <!-- New Password -->
            <div class="mb-4 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="new-password" name="password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                    placeholder="New Password" required>
                <button type="button" id="toggle-new-password" class="absolute inset-y-0 right-0 flex items-center pr-3"
                    onclick="togglePassword('new-password', 'toggle-new-password')">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>

            <!-- Confirm New Password -->
            <div class="mb-6 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="confirm-new-password" name="password_confirmation"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                    placeholder="Confirm New Password" required>
                <button type="button" id="toggle-confirm-password"
                    class="absolute inset-y-0 right-0 flex items-center pr-3"
                    onclick="togglePassword('confirm-new-password', 'toggle-confirm-password')">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>
            <!-- Save Changes Button -->
            <button type="submit"
                class="text-white bg-vastel_blue hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-[8rem] mb-6">
                Save Changes
            </button>
        </form>

        <!-- Change Security Pin Section -->
        <div class="border-t pt-6">
            <h2 class="text-xl font-semibold mb-2">
                {{ !empty(auth()->user()->pin) ? 'Change Security PIN ' : 'Setup Security PIN' }}</h2>
            <p class="text-gray-600 text-sm mb-4">Click the button below to
                {{ !empty(auth()->user()->pin) ? 'change' : 'setup' }} your security PIN</p>
            <button type="button" data-modal-target="handlePinModal" data-modal-toggle="handlePinModal"
                class="text-white bg-vastel_blue hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-[16rem] handleOtp">
                {{ !empty(auth()->user()->pin) ? 'Change Pin ' : 'Setup PIN' }}
            </button>
        </div>
        {{-- @if (request()->query('otp_verified') == 'true') --}}
        <button type="button" data-modal-target="handlePin" class="hidden" data-modal-toggle="handlePin"></button>
        <div id="handlePin" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full hidden justify-center items-center">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold dark:text-white">
                            Change Transaction Pin
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="handlePin">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6">
                        <form action="{{ route('pin.update') }}" method="POST" id="pin-form">
                            @csrf
                           <div class="text-center mb-3">
                                <p class="dark:text-white">A 4-digit code has been sent to your email address.</p>
                                <p class="text-vastel_blue dark:text-white">{{ auth()->user()->email }}</p>
                           </div>
                            <p class="text-vastel_blue dark:text-white">New Transaction pin</p>
                            <div class="text-center">
                                <div class="flex justify-center gap-2 mt-4" id="otp-container">
                                    <input type="text" maxlength="1"
                                        class="new-pin-input w-12 h-12 text-center border border-gray-300 rounded" />
                                    <input type="text" maxlength="1"
                                        class="new-pin-input w-12 h-12 text-center border border-gray-300 rounded" />
                                    <input type="text" maxlength="1"
                                        class="new-pin-input w-12 h-12 text-center border border-gray-300 rounded" />
                                    <input type="text" maxlength="1"
                                        class="new-pin-input w-12 h-12 text-center border border-gray-300 rounded" />
                                </div>
                            </div>
                            <p class="text-vastel_blue dark:text-white mb-3 mt-5">Confirm new Transaction pin</p>
                            <div class="text-center">
                                <div class="flex justify-center gap-2 mt-4" id="otp-container">
                                    <input type="text" maxlength="1"
                                        class="confirm-pin-input w-12 h-12 text-center border border-gray-300 rounded" />
                                    <input type="text" maxlength="1"
                                        class="confirm-pin-input w-12 h-12 text-center border border-gray-300 rounded" />
                                    <input type="text" maxlength="1"
                                        class="confirm-pin-input w-12 h-12 text-center border border-gray-300 rounded" />
                                    <input type="text" maxlength="1"
                                        class="confirm-pin-input w-12 h-12 text-center border border-gray-300 rounded" />
                                </div>
                            </div>
                            <!-- Hidden input to hold the concatenated OTP -->
                            <input type="hidden" name="new_pin" id="pin-input-hidden">
                            <input type="hidden" name="confirm_pin" id="confirm-pin-input-hidden">
                            <div class="text-center">
                                <button type="submit" class="bg-vastel_blue text-white px-4 py-2 rounded mt-6">Continue</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- @endif --}}
        <div id="handlePinModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold dark:text-white">
                            OTP Verification
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="handlePinModal">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 text-center ">
                        <form action="{{ route('pin.verify-otp') }}" method="POST" id="otp-form">
                            @csrf
                            <p class="dark:text-white">A 4-digit code has been sent to your email address.</p>
                            <p class="text-vastel_blue dark:text-white">{{ auth()->user()->email }}</p>
                            <div class="flex justify-center gap-2 mt-4" id="otp-container">
                                <input type="text" maxlength="1"
                                    class="otp-input w-12 h-12 text-center border border-gray-300 rounded" />
                                <input type="text" maxlength="1"
                                    class="otp-input w-12 h-12 text-center border border-gray-300 rounded" />
                                <input type="text" maxlength="1"
                                    class="otp-input w-12 h-12 text-center border border-gray-300 rounded" />
                                <input type="text" maxlength="1"
                                    class="otp-input w-12 h-12 text-center border border-gray-300 rounded" />
                            </div>
                            <!-- Hidden input to hold the concatenated OTP -->
                            <input type="hidden" name="otp" id="otp-hidden">
                            <p class="text-blue-500 mt-4 cursor-pointer">
                                Didn't receive the OTP? <a href="javascript:void(0)" class="underline handleOtp">Resend
                                    code</a>
                            </p>
                            <button type="submit" class="bg-vastel_blue text-white px-4 py-2 rounded mt-6">Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        {{-- <div id="pinResetModal" class="hidden fixed inset-0 items-center justify-center bg-gray-800 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-[80%] md:w-[40%] text-center">
                @livewire('profile.pin-form', [App\Models\User::find(auth()->user()->id)])
            </div>
        </div> --}}
        {{-- <div id="pinResetModal" class="hidden fixed inset-0 items-center justify-center bg-gray-800 bg-opacity-50">
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
        </div> --}}
    </div>



    <!-- account deactivation -->
    <div class="w-full lg:w-[60%] bg-white p-6">
        <!-- Title -->
        <h2 class="text-xl font-bold text-blue-600 mb-4">Delete/Deactivate Account</h2>

        <!-- Deactivate Account -->
        <a type="button" data-modal-target="deactivateAccountModal" data-modal-toggle="deactivateAccountModal"
            class="flex items-center p-4 mb-4 bg-gray-50 rounded-lg shadow-sm">
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

        <div id="deactivateAccountModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold dark:text-white">
                            Deactivate Account
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="deactivateAccountModal">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 text-center">
                        <p class="mb-5 text-lg font-semibold dark:text-gray-400">Deactivate your Vastel account?<br>Your
                            Vastel account will be temporarily closed until reactivation.</p>

                        <div class="flex flex-col justify-between gap-7">
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit"
                                    class="text-white bg-vastel_blue hover:bg-vastel_blue focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2">
                                    Deactivate Account
                                </button>
                            </form>
                            <button data-modal-hide="deactivateAccountModal" type="button"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border-none border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Delete Account -->
        <a type="button" data-modal-target="deleteAccountModal" data-modal-toggle="deleteAccountModal"
            class="flex items-center p-4 bg-gray-50 rounded-lg shadow-sm">
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

    <div id="deleteAccountModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold dark:text-white">
                        Delete Account
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="deleteAccountModal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 text-center">
                    <p class="mb-5 text-lg font-semibold dark:text-gray-400">Deactivate your Vastel account?<br>You will
                        lose your data and account history in the Vastel app</p>

                    <div class="flex flex-col justify-between gap-7">
                        <form action="{{ route('delete') }}" method="post">
                            @csrf
                            <div class="mb-4 relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" id="delete-input" name="password"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                                    placeholder="Enter Password to Continue" required>
                                <button type="button" id="delete-button"
                                    onclick="togglePassword('delete-input', 'delete-button')"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-eye text-gray-400"></i>
                                </button>
                            </div>
                            <button type="submit"
                                class="text-white bg-[#FF0000] focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2">
                                Delete Account
                            </button>
                        </form>
                        <button data-modal-hide="deactivateAccountModal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border-none border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
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
    <div id="step2" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 items-center justify-center">
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
            <p class="text-blue-500 mt-4 cursor-pointer">Didn't receive the OTP? <span class="underline">Resend
                    code</span></p>
            <button class="bg-blue-500 text-white px-4 py-2 rounded mt-6">Continue</button>
        </div>
    </div>

    <!-- Step 3: Enter New Transaction Pin Modal -->
    <div id="step3" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 items-center justify-center">
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
@push('scripts')
    <script>
        // const openChangePinResetBtn = document.getElementById('openChangePinResetBtn');
        // const pinModal = document.getElementById('pinModal');
        // openChangePinResetBtn.addEventListener('click', function () {
        //     pinModal.classList.remove('hidden');
        //     pinModal.classList.add('flex');
        // });

        // Optionally, close modal when clicking outside
        // window.addEventListener('click', (e) => {
        //     if (e.target === pinModal) {
        //         pinModal.classList.add('hidden');
        //     }
        // });

        const handleOtps = document.querySelectorAll('.handleOtp');
        handleOtps.forEach((handleOtp, index) => {
            handleOtp.addEventListener('click', function() {
                const xhr = new XMLHttpRequest();
                const url = "{{ route('pin.send-otp') }}";

                xhr.open('POST', url, true);
                xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        const responseMessage = document.getElementById('responseMessage');
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);

                        } else {
                            console.log(`Failed to send OTP. Error: ${xhr.status}`);
                        }
                    }
                };

                xhr.send();
            });
        });

        function setupOtpForm(formId, inputClass, hiddenInputId) {
            const otpForm = document.getElementById(formId);
            const otpInputs = otpForm.querySelectorAll(`.${inputClass}`);
            const otpHiddenInput = document.getElementById(hiddenInputId);

            if (!otpForm || !otpInputs.length || !otpHiddenInput) {
                console.error('Invalid OTP form configuration.');
                return;
            }

            otpForm.addEventListener('submit', (e) => {
                let otp = '';
                otpInputs.forEach(input => {
                    otp += input.value;
                });

                if (otp.length !== otpInputs.length) {
                    e.preventDefault();
                    alert('Please fill all fields.');
                    return;
                }

                otpHiddenInput.value = otp;
            });

            otpInputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            setupOtpForm('otp-form', 'otp-input', 'otp-hidden');
            setupOtpForm('pin-form', 'new-pin-input', 'pin-input-hidden');
            setupOtpForm('pin-form', 'confirm-pin-input', 'confirm-pin-input-hidden');
        });


        document.addEventListener('DOMContentLoaded', () => {
            if (window.location.search.indexOf('otp_verified=true') !== -1) {
                const modal = document.getElementById('handlePin');

                const showModal = () => {
                    // Add backdrop
                    const backdrop = document.createElement('div');
                    backdrop.setAttribute('modal-backdrop', '');
                    backdrop.className = 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40';
                    backdrop.id = 'modal-backdrop';
                    document.body.appendChild(backdrop);

                    // Show modal
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    // Close modal when clicking on backdrop
                    backdrop.addEventListener('click', hideModal);
                };

                const hideModal = () => {
                    const backdrop = document.getElementById('modal-backdrop');
                    if (backdrop) backdrop.remove();

                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                };

                showModal();

                const closeButton = modal.querySelector('[data-modal-hide="handlePin"]');
                closeButton.addEventListener('click', hideModal);
            }
        });
    </script>
@endpush
