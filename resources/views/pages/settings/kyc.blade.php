@extends('layouts.new-guest')
@section('body')
<div class="w-full pl-[1.5rem] max-w-md space-y-4">
    <!-- BVN/NIN Section -->
    <div class="mt-8">
        <!-- BVN Number -->
        <livewire:profile.kyc-form />
    </div>
</div>
@endsection