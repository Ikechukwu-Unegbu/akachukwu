@extends('layouts.new-guest')
@section('head')
<title>Vastel | Airtime</title>
@endsection 
@section('body')
<div class="max-w-lg w-full bg-white p-8 ">
    <div class="flex items-center mb-6">
        <a href="{{ route('services') }}" class="text-blue-600">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Airtime Purchase</h2>
    @livewire('pages.utility.airtime.create')
</div>
@endsection 