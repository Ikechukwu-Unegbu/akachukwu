@extends('layouts.new-guest')
@section('head')
<link rel="stylesheet" href="{{asset('css/profile.css')}}"/>
@endsection

@section('body')
<div>
    <div class="profile_form border-bottom border-3 mb-7">
        <div>
            <h3>Profile Information</h3>
        </div>
        <form>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" id="name" aria-describedby="emailHelp">
                @error('name')<div id="name_error" class="form-text text-danger">{{$message}}</div> @enderror
            </div>
             <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" id="username" aria-describedby="emailHelp">
                @error('username')<div id="user_name_error" class="form-text text-danger">{{$message}}</div>@enderror 
            </div>
             <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
                @error('email')<div id="email_error" class="form-text text-danger">{{$message}}</div>@enderror 
            </div>
             <div class="mb-3">
               <button class="btn btn-dark">Save</button>
            </div>

        </form>
         
    </div>

     <div class="profile_form border-bottom mb-7 border-3 ">
        <div>
            <h3>Change Password</h3>
        </div>
        <form>
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="text" class="form-control" name="current_password" id="current_password" aria-describedby="emailHelp">
                @error('current_password')<div id="current_password_error" class="form-text">{{$message}}</div>@enderror
            </div>
             <div class="mb-3">
                <label for="password" class="form-label">New Pasword</label>
                <input type="text" class="form-control" id="password" id="password" aria-describedby="emailHelp">
                @error('password')<div id="password_error" class="form-text text-danger">{{$message}}</div>@enderror
            </div>
             <div class="mb-3">
                <label for="password_confirm" class="form-label">Confirm Password</label>
                <input type="email" class="form-control" id="confirm" name="confirm" aria-describedby="emailHelp">
                @error('confirm')<div id="password_confirm_error" class="form-text text-danger">We'll never share your email with anyone else.</div> @enderror
            </div>
             <div class="mb-3">
               <button class="btn btn-dark">Save</button>
            </div>

        </form>
         
    </div>

     <div class="profile_form border-bottom border-3  mb-10">
        <div>
            <h3>Delete Account</h3>
        </div>
        <div>
            Deleting your account is irreversible. All your data will be permanently removed. Download your data before proceeding. Once deleted, you cannot recover your account or data.
        </div>
        
         <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#delete-account-modal">Warning</button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete-account-modal" tabindex="-1" aria-labelledby="delete-account-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="delete-account-modal">Account Deletion</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            Deleting your account is irreversible. All your data will be permanently removed. Download your data before proceeding. Once deleted, you cannot recover your account or data.
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger">Delete Account</button>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection