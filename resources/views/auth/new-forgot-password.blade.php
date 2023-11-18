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
                    Login and Flex
                </div>
            <div>
            @include("components.error_message")
            <div class="form-floating mb-3">
                <input type="text" name="email" class="form-control" id="floatingInput" placeholder="">
                <label for="floatingInput">Email</label>
            </div>
          
            <div>
                <button type="button" class="btn btn-warning">Email Password Reset Link</button>
            </div>
        </form>
    
    </div>
</div>
@endsection 
