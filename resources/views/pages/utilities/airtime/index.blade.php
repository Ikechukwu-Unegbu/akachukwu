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
          
            @livewire('pages.utility.airtime.create')
            {{-- <form class="utility-form">
                @include('pages.utilities.components._network_selections')
                <div class="airtime-group">
                    <div class="mb-3 form-floating">
                        <input type="number" name="phone" class="form-control" id="floatingInput" placeholder="">
                        <label for="floatingInput">Amount</label>
                    </div>
                    <div class="mb-3 form-floating">
                        <input type="text" name="phone" class="form-control" id="floatingInput" placeholder="">
                        <label for="floatingInput">Phone Number</label>
                    </div>
                </div>
                <button class="btn bg-basic text-light">Continue</button>
            </form> --}}
        </div>
        <!-- end of card indicators -->    
    </div>

</div>
@endsection 