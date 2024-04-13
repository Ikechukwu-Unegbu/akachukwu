<div>
    <div class="dashboard_body">
        @section('head')
        <link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}" />
        <link rel="stylesheet" href="{{asset('css/index.css')}}" />
        <link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}" />
        @endsection
        <div class="sidebar_body">
            @include('components.dasboard_sidebar')
        </div>

    </div>
</div>