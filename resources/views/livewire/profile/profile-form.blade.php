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