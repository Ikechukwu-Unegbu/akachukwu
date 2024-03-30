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