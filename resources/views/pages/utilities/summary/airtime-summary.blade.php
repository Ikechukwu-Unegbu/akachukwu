@extends('layouts.new-guest')

@section('head')
<link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}"/>
<link rel="stylesheet" href="{{asset('css/ut/summary.css')}}"/>

@endsection

@section('body')
<div class="dashboard_body">
    <div class="sidebar_body">
        @include('components.dasboard_sidebar')
    </div>
    <div class="dashboard_section  summary">

        <!-- card indicators -->
        <h4>Please confirm that your transaction details are as follows.</h4>
        <div class="">
            <h4>Transaction Details:</h4>
            
        

            
        </div>
        <!-- end of card indicators -->    
    </div>

</div>
@endsection 