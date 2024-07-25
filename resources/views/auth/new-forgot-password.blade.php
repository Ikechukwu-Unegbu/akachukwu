@extends('layouts.new-guest')

@section('head')
<link href="{{asset('css\auth.css')}}"  rel="stylesheet"/>
@endsection  

@section('body')
<div class="auth_class">
    <div class="left_auth">
    
    </div>
    <div class="right_auth">
        <form class="form_auth" method="POST" action="{{route('password.email')}}">
           @csrf 
            <div class="mb-3 fs-3 fw-semibold ">
                <div class="auth_header">
                    Enter Your Email For Password Reset Link
                </div>
            <div>
            @if (session('status'))
                <div class="text-green-700 text-sm bg-green-100 border border-green-300 rounded p-4 mb-4">
                    {{session('status')}}
                </div>
            @endif 
            @include("components.error_message")
    
            

            
            <div class="form-floating mb-3">
                <input type="text" name="email" class="form-control" id="floatingInput" placeholder="">
                <label for="floatingInput">Email</label>
            </div>
          
            <div>
                <button type="submit" class="btn vastel-bg">Email Password Reset Link</button>
            </div>
        </form>
    
    </div>
</div>
@endsection 
