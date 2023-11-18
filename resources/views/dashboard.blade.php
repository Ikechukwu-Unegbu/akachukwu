@extends('layouts.new-guest')

@section('head')
<link rel="stylesheet" href="{{asset('css.dashboard_index.css')}}"/>

@endsection

@section('body')
<div class="dasboard_body">
    <div class="sidebar_body">
        @include('components.dasboard_sidebar')
    </div>
    <div class="dashboard_section">

        <!-- card indicators -->
        <div class="d-flex box_container">
            <div class="box ">
                <div>
                    <div>Total Wallet</div>
                    <div>
                        <div>
                            0.00
                        </div>
                        <button>
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="box "></div>
            <div class="box "></div>
        </div>
        <!-- end of card indicators -->    
    </div>

</div>
@endsection 