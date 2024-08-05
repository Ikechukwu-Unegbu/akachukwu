  <div class="profile_form border-bottom border-3 mb-7">
  <div class="mt-4">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}" href="{{ route('profile.edit') }}">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('pins') ? 'active' : '' }}" href="{{ route('profile.pin') }}">Pin</a>
        </li>
    </ul>
</div>

        <div class="mt-1">
            <h3>Profile Information</h3>
        </div>
        <div class="mb-5">
            <a href="/dashboard"><i class="fa-solid fa-arrow-left-long"></i></a>
        </div>
        <form wire:submit.prevent="save">
            @if(session()->has('profile_upated'))
            <div class="alert alert-success mb-3">
                {{ session('profile_upated') }}
            </div>
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" wire:model="name" value="{{$user->name}}" class="form-control" name="name" id="name" aria-describedby="emailHelp">
                @error('name')<div id="name_error" class="form-text text-danger">{{$message}}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="hpone" class="form-label">Phone</label>
                <input type="text" value="{{$user->phone}}" wire:model="phone" class="form-control" name="phone" id="phone" aria-describedby="emailHelp">
                @error('phone')<div id="name_error" class="form-text text-danger">{{$message}}</div> @enderror
            </div>
             <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" wire:model="username" class="form-control" id="username" id="username" aria-describedby="emailHelp">
                @error('username')<div id="user_name_error" class="form-text text-danger">{{$message}}</div>@enderror 
            </div>
             {{-- <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" wire:model="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
                @error('email')<div id="email_error" class="form-text text-danger">{{$message}}</div>@enderror 
            </div> --}}
             <div class="mb-3">
               <button class="btn btn-dark">Save</button>
            </div>

        </form>
         
    </div>