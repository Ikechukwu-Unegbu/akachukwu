@extends('layouts.new-guest')
@section('body')
<div>
    <div id="pinModal" tabindex="-1" aria-hidden="true" class="flex fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full items-center justify-center">
        <div class="relative w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Verify OTP
                    </h3>
                </div>
                <!-- Modal body (PIN input) -->
                <livewire:otp.verify />
            </div>
        </div>
    </div>
</div>
@endsection
