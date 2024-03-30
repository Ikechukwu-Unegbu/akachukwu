 <div class="profile_form border-bottom mb-7 border-3 ">
        <div>
            <h3>Change Password</h3>
        </div>
        <form wire:submit.prevent="submit">
             @if(session()->has('profile_upated'))
            <div class="alert alert-success mb-3">
                {{ session('profile_upated') }}
            </div>
            @endif
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="text" class="form-control" wire:model="current_password" name="current_password" id="current_password" aria-describedby="emailHelp">
                @error('current_password')<div id="current_password_error" class="form-text text-danger">{{$message}}</div>@enderror
            </div>
             <div class="mb-3">
                <label for="password" class="form-label">New Pasword</label>
                <input type="text" class="form-control" wire:model="password" id="password" name="password" id="password" aria-describedby="emailHelp">
                @error('password')<div id="password_error" class="form-text text-danger">{{$message}}</div>@enderror
            </div>
             <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="text" class="form-control"wire:model="password_confirmation" id="password_confirmation" name="password_confirmation" aria-describedby="emailHelp">
                @error('password_confirmation')<div id="password_confirm_error" class="form-text text-danger">We'll never share your email with anyone else.</div> @enderror
            </div>
             <div class="mb-3">
               <button class="btn btn-dark">Save</button>
            </div>

        </form>
         
    </div>