@extends('layouts.new-guest')

@section('head')
<link href="{{asset('css\auth.css')}}"  rel="stylesheet"/>
@endsection  

@section('body')
<div class="auth_class">
    <div class="left_auth">
    
    </div>
    <div class="right_auth">
        <form class="form_auth" method="POST" action="{{route('register')}}">
            @csrf
            <div class="mb-3 fs-3 fw-semibold ">
                <div class="auth_header">
                    Sign Up and Enjoy Affordable Utilities
                </div>
            <div>
            @include("components.error_message")
            <div class="mb-3 form-floating">
                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="">
                <label for="name">Full Name</label>
            </div>
             <div class="mb-3 form-floating">
                <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}" placeholder="">
                <label for="username">Username</label>
            </div>
             <div class="mb-3 form-floating">
                <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone') }}" placeholder="">
                <label for="phone">Phone</label>
            </div>
             <div class="mb-3 form-floating">
                <input type="text" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="">
                <label for="email">Email</label>
            </div>
             <div class="mb-3 form-floating">
                <input type="password" class="form-control" name="password" id="password" placeholder="">
                <label for="password">Password</label>
                <small style="font-size: 0.75rem; color:red;">Include capital letters and numbers</small>
            </div>
             <div class="mb-3 form-floating">
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="">
                <label for="password_confirmation">Confirm Password</label>
            </div>
            <div class="mb-3 form-floating">
                <div class="form-check">
                    <input type="checkbox" name="terms_and_conditions" class="form-check-input" id="exampleCheck1">
                    <label class="text-xs form-check-label" for="exampleCheck1">I agree to <a class="text-danger" href="#"> terms and conditions</a></label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" style="float:right;" class="float-right btn btn-warning">Register</button>
            </div>
            <div class="mt-4 mb-4">
                <br/>
                <hr class="mt-3 mb-3"/>
                <br/>
            </div>
             <div class="mb-3 form-floating">
                <div class="form-check">
                    {{-- <input type="checkbox" class="form-check-input" id="exampleCheck1"> --}}
                    <label class="text-xs form-check-label" for="">Do you have account already? <a class="text-warning" href="{{ route('new.login') }}">Login here</a></label>
                </div>
            </div>

        </form>
    
    </div>
</div>
@endsection 
