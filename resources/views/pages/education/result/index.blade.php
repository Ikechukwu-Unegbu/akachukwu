@extends('layouts.new-guest')
@section('body')
<div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
    <a href="{{ route('services') }}" class="text-sm text-blue-500 mb-4 inline-block"><i class="fas fa-arrow-left"></i> Back</a>
    <h2 class="text-xl font-semibold mb-4">Education</h2>
    @livewire('pages.education.result-checker.create')
</div>
@endsection 