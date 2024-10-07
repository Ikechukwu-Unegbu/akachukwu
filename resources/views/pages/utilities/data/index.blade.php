@extends('layouts.new-guest')
@section('body')
<div class="max-w-lg w-full p-6">
    <!-- Back Button -->
    <a href="{{ route('services') }}" class="flex items-center text-vastel_blue mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </a>
    <!-- Form Header -->
    <h1 class="text-xl font-semibold mb-4">Data Purchase</h1>
    @livewire('pages.utility.data.create')
</div>
@endsection 