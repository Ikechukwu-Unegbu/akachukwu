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
            <div class="form-floating mb-3">
                <input type="text" name="name" class="form-control" id="floatingInput" placeholder="">
                <label for="floatingInput">Full Name</label>
            </div>
             <div class="form-floating mb-3">
                <input type="text" name="username" class="form-control" id="floatingInput" placeholder="">
                <label for="floatingInput">Username</label>
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
                <input type="password" class="form-control" name="password" id="floatingInput" placeholder="">
                <label for="floatingInput">Password</label>
            </div>
             <div class="form-floating mb-3">
                <input type="text" class="form-control" name="password_confirmation" id="floatingInput" placeholder="">
                <label for="floatingInput">Confirm Password</label>
            </div>
            <div class="form-floating mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label text-xs" for="exampleCheck1">I agree to <a class="text-danger" href="/terms"> terms and conditions</a></label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" style="float:right;" class="btn float-right btn-warning">Create Account</button>
            </div>
            <div class="mt-4 mb-4">
                <br/>
                <hr class="mt-3 mb-3"/>
                <br/>
            </div>
             <div class="form-floating mb-3">
                <div class="form-check">
                    {{-- <input type="checkbox" class="form-check-input" id="exampleCheck1"> --}}
                    <label class="form-check-label text-xs" for="">Do you have account already? <a class="text-warning" href="/terms">Login here</a></label>
                </div>
            </div>

        </form>
    
    </div>
</div>
@endsection 
