@extends('layouts.new-guest')

@section('head')
<link href="{{asset('css\auth.css')}}"  rel="stylesheet"/>
@endsection  

@section('body')
<div class="auth_class">
    <div class="left_auth">
    
    </div>
    <div class="right_auth">
        <form class="form_auth" action="{{route('login')}}" method="POST" >
            @csrf
            <div class="mb-3 fs-3 fw-semibold ">
                <div class="auth_header">
                    Login and Flex
                </div>
            <div>
            @include("components.error_message")
            <div class="mb-3 form-floating">
                <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}" placeholder="">
                <label for="username">Username</label>
            </div>
             <div class="mb-3 form-floating">
                <input type="password" name="password" class="form-control" id="password" placeholder="">
                <label for="password">Password</label>
            </div>
            <div class=""></div>
            <div class="mb-3 form-floating row">
                <small>Forgot your password? <a href="{{route('password.request')}}">Click here</a></small>
            </div>
             <div class="mb-3 form-floating" style="display:flex; flex-direction:row; align-items:center; jusify-content:center; gap:1rem;">
                {{-- <div class=""> --}}
                    {{-- <input type="checkbox" class="form-check-input" style="" name='remember' id="exampleCheck1"> --}}
                    <small class="text-xs " style="font-size: 15px;" for="exampleCheck1">Dont have account? <a class="text-danger" href="{{route('register')}}">Register Here</a></small>
                {{-- </div> --}}
            </div>
          
            <div>
                <button type="submit" class="btn btn-warning">Login</button>
            </div>
        </form>
    
    </div>
</div>
@endsection 
