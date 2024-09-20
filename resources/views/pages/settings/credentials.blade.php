@extends('layouts.new-guest')
@section('body')
    <div class="max-w-lg w-full bg-white p-8 ">
        <div class="flex items-center mb-6">
            <a href="{{ route('settings.index') }}" class="text-blue-600">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        @livewire('profile.update-password-form', [App\Models\User::find(auth()->user()->id)])
        @livewire('profile.pin-form', [App\Models\User::find(auth()->user()->id)])
        {{-- 
        <!-- Change Security Pin Section -->
        <div class="border-t pt-6">
            <h2 class="text-xl font-semibold mb-2">Change Security Pin</h2>
            <p class="text-gray-600 text-sm mb-4">Send a request to change your security pin</p>
            <button type="button"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full">
                Request Security Pin Change
            </button>
        </div> --}}
    </div>
@endsection
