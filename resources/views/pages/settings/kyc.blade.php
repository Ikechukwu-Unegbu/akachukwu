@extends('layouts.new-guest')
@section('body')
<div class="w-full md:pl-[1.5rem] px-5 max-w-md space-y-4">
    <!-- BVN/NIN Section -->
    <div class="mt-8">
        <!-- BVN Number -->
        <livewire:profile.kyc-form />
    </div>
</div>
@endsection