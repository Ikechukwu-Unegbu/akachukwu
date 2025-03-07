@extends('layouts.admin.app')
@push('title') Dashboard @endpush
@section('content')
    @livewire('admin.dashboard')
@endsection
{{-- @push('scripts')
<script src="{{ asset('admin-pages/js/chart.js') }}"></script>
<script>
    var __yMonths =  {{ Js::from($months) }};
    var __xMonthlyUsersData =  {{ Js::from($registeredUser) }};

    const dataUsers = {
        labels: __yMonths,
        datasets: [{
            label: 'Users Chart',
            backgroundColor: 'rgb(65, 84, 241, 1)',
            borderColor: 'rgb(65, 84, 200, 1)',
            data: __xMonthlyUsersData,

        }]
    };
    const configUsers = {
        type: 'line',
        data: dataUsers,
        options: {}
    };
    const users = new Chart(
        document.getElementById('usersChart'),
        configUsers
    );
</script>
@endpush --}}
{{-- @push('scripts')
<script src="{{ asset('admin-pages/js/chart.js') }}"></script>
<script>
    var __yMonths =  {{ Js::from($months) }};
    var __xMonthlyUsersData =  {{ Js::from($registeredUser) }};

    const dataUsers = {
        labels: __yMonths,
        datasets: [{
            label: 'Users Chart',
            backgroundColor: 'rgb(65, 84, 241, 1)',
            borderColor: 'rgb(65, 84, 200, 1)',
            data: __xMonthlyUsersData,

        }]
    };
    const configUsers = {
        type: 'line',
        data: dataUsers,
        options: {}
    };
    const users = new Chart(
        document.getElementById('usersChart'),
        configUsers
    );
</script>
@endpush
@push('title') Dashboard @endpush --}}