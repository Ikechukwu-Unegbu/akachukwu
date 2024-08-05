@extends('layouts.new-guest')
@section('head')
<link rel="stylesheet" href="{{asset('css/profile.css')}}"/>
@endsection

@section('body')
<div>
    @livewire('profile.pin-form', [App\Models\User::find(auth()->user()->id)])
</div>
@endsection