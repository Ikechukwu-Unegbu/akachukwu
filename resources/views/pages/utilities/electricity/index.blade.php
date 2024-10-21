@extends('layouts.new-guest')
@section('body')
<div class="w-full max-w-md p-6 bg-white ">
    <a href="{{ route('services') }}" class="text-sm text-vastel_blue mb-4 inline-block"><i class="fas fa-arrow-left"></i> Back</a>
    <h2 class="text-xl font-semibold mb-4">Electricity Purchase</h2>
    @livewire('pages.utility.electricity.create')
</div>            
@endsection 