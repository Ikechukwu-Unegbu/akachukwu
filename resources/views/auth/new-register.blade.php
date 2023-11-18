@extends('layouts.new-guest')

@section('head')
<link href="{{asset('css\auth.css')}}"  rel="stylesheet"/>
@endsection  

@section('body')
<div class="auth_class">
    <div class="left_auth">
    
    </div>
    <div class="right_auth">
        <form class="form_auth">
            <div class="mb-3 fs-3 fw-semibold ">
                <div class="auth_header">
                    Sign Up and Enjoy Affordable Utilities
                </div>
            <div>
            @include("components.error_message")
            <div class="form-floating mb-3">
                <input type="text" name="name" class="form-control" id="floatingInput" placeholder="">
                <label for="floatingInput">Full Name</label>
            </div>
             <div class="form-floating mb-3">
                <input type="text" name="phone" class="form-control" id="floatingInput" placeholder="">
                <label for="floatingInput">Phone</label>
            </div>
             <div class="form-floating mb-3">
                <input type="text" class="form-control" name="email" id="floatingInput" placeholder="">
                <label for="floatingInput">Email</label>
            </div>
             <div class="form-floating mb-3">
                <input type="text" class="form-control" name="password" id="floatingInput" placeholder="">
                <label for="floatingInput">Password</label>
            </div>
             <div class="form-floating mb-3">
                <input type="text" class="form-control" name="confirm_password" id="floatingInput" placeholder="">
                <label for="floatingInput">Confirm Password</label>
            </div>
            <div>
                <button type="button" class="btn btn-warning">Create Account</button>
            </div>
        </form>
    
    </div>
</div>
@endsection 
