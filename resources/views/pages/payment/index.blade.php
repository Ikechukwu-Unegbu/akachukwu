@extends('layouts.new-guest')
@section('body')
<div class="max-w-lg w-full bg-white p-8 ">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard') }}" class="text-blue-600">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-6">Fund Your Wallet</h2>
    <form action="{{ route('payment.process') }}" method="GET">
        @csrf    
        <div class="relative z-0 mb-6 w-full group mt-4">
            <input type="number" wire:model="amount" name="amount" id="amount" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
            <input type="hidden" value="{{ auth()->user()->username }}" name="user">
            <label for="amount" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Enter Amount</label>
            <i class="fas fa-money-bill-wave absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-400"></i>
            @error('amount')<span class="text-red-500 font-bold text-sm"> {{ $message }} </span>@enderror
        </div>

        <div class="relative z-0 mb-6 w-full group mt-4">
            {{-- <h6 class="mb-3">Choose Payment Gateway</h6> --}}
            <div class="flex align-middle">
                @foreach ($payments as $payment)
                    <div class="me-3">
                        <input type="radio" name="gateway" id="{{ Str::lower($payment->name) }}" value="{{ Str::lower($payment->name) }}" {{ $loop->first ? 'checked' : '' }}>
                    </div>
                    <div class="me-3">
                        <label for="{{ Str::lower($payment->name) }}">
                            <img src="{{ asset('images/' . Str::lower($payment->name) . '.png') }}" width="100" alt="">
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        
        <button type="submit" class="w-full bg-vastel_blue text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
            <span wire:loading.remove wire:target='validateForm'>Proceed</span>
        </button>

        @if (!count($accounts))
            @livewire('pages.virutal-account.create', [App\Models\User::find(auth()->user()->id)])
        @endif

        {{-- @if (count($accounts))
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="dashboard-container ml-0">
                    <div class="text-center">
                        <h3>Bank Transfer</h3>
                        <p>Add Money via mobile or internet banking</p>
                    </div>
                    <div class="row">
                        @foreach ($accounts as $__account)
                            <div class="mb-3 col-6 col-md-6 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Account Number: {{ $__account->account_number }}</h6>
                                        <h6>Account Name: {{ $__account->account_name }}</h6>
                                        <h6>Bank Name: {{ $__account->bank_name }}</h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
            @livewire('pages.virutal-account.create', [App\Models\User::find(auth()->user()->id)])
        @endif --}}
    </form>
</div>
@endsection 