@extends('layouts.new-guest')
@section('head')
<title>Vastel | Cable</title>
@endsection 
@section('body')
    <div class="max-w-lg w-full bg-white  p-6">
        <!-- Back Button -->
        <a href="{{ route('services') }}" class="flex items-center text-blue-600 mb-6">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>

        <!-- Form Header -->
        <h1 class="text-xl font-semibold mb-4">TV Purchase</h1>
        @livewire('pages.utility.cable.create')

    </div>
@endsection
