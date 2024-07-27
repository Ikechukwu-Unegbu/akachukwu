@extends('layouts.new-guest')
@section('head')
<style>

</style>
@endsection
@section('body')
<div class="container">
    <form method="POST" action="{{ route('password.store') }}" class="form">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control w-[60%] block mt-1" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-control block mt-1" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="form-control block mt-1"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="">
            <x-primary-button class="btn btn-warning mt-4 vastel-bg">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</div>
@endsection