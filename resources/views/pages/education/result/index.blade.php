@extends('layouts.new-guest')

@section('head')
<link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}"/>
<link rel="stylesheet" href="{{asset('css/ut/airtime.css')}}"/>
<link rel="stylesheet" href="{{asset('css/ut/network_picker.css')}}"/>
<link rel="stylesheet" href="{{asset('css/ut/offcanvas.css?t=' . time() )}}"/>

@endsection

@section('body')
<div class="dashboard_body">
    <div class="sidebar_body">
        @include('components.dasboard_sidebar')
    </div>
    <div class="dashboard_section">
        <div class="dashboard_section_inner">
            @livewire('pages.education.result-checker.create')
        </div>
        <!-- end of card indicators -->    
    </div>
</div>
@endsection 