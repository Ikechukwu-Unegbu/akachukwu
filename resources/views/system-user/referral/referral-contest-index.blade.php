@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <x-admin.page-title title="Plan Settings">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Savings Plan Settings" status="true" />
    </x-admin.page-title>

    <div class="card shadow-sm">
     

    </div>
</div>



@endsection

@push('title')
Manage Plan Settings
@endpush
