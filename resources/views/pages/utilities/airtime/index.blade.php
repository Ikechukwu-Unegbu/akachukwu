@extends('layouts.new-guest')

@section('head')
<link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}"/>
<link rel="stylesheet" href="{{asset('css/ut/airtime.css')}}"/>
<link rel="stylesheet" href="{{asset('css/ut/network_picker.css')}}"/>


@endsection

@section('body')
<div class="dashboard_body">
    <div class="sidebar_body">
        @include('components.dasboard_sidebar')
    </div>
    <div class="dashboard_section">

        <!-- card indicators -->
        <div class="">
            <div>
                <h3 class="text-warning">Airtime VTU Top up</h3>
                <h4>Select Network</h6>
            </div>
            <form class="utility-form">
                @include('pages.utilities.components._network_selections')
                <div class="airtime-group">
                    <div class="form-floating mb-3">
                        <input type="number" name="phone" class="form-control" id="floatingInput" placeholder="">
                        <label for="floatingInput">Amount</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="phone" class="form-control" id="floatingInput" placeholder="">
                        <label for="floatingInput">Phone Number</label>
                    </div>
                </div>
                <button class="btn bg-basic text-light">Continue</button>
            </form>
        </div>
        <!-- end of card indicators -->    
    </div>

</div>
@endsection 