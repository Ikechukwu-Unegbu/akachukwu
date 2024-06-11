@extends('layouts.new-guest')
@section('head')
<link rel="stylesheet" href="{{asset('css/profile.css')}}"/>
@endsection

@section('body')
<div>
    
    @livewire('profile.profile-form', [App\Models\User::find(auth()->user()->id)])

    @livewire('profile.kyc-form', [App\Models\User::find(auth()->user()->id)])

    @livewire('profile.pin-form', [App\Models\User::find(auth()->user()->id)])

    @livewire('profile.update-password-form', [App\Models\User::find(auth()->user()->id)])

    @livewire('profile.delete-account', [App\Models\User::find(auth()->user()->id)])
    
</div>
@endsection