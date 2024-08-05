<div class="profile_form border-bottom mb-7 border-3 " id="pin-setup">
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


    <div class="mt-2">
        <h3>{{ !empty($user->pin) ? 'Update Your PIN' : 'Setup Your PIN' }}</h3>
    </div>
    <div class="mb-5">
        <a href="/dashboard"><i class="fa-solid fa-arrow-left-long"></i></a>
    </div>
    <form wire:submit.prevent="{{ !empty($user->pin) ? 'update' : 'submit' }}">
        @if (!empty($user->pin))
        <div class="mb-3">
            <label for="form-lable">Current PIN <span class="text-danger">*</span></label>
            <input type="password" class="form-control" wire:model="current_pin" name="current_pin" id="current_pin">
            @error('current_pin')<div id="current_pin_error" class="form-text text-danger">{{$message}}</div>@enderror
        </div>
        @endif
        <div class="mb-3">
            <label for="form-lable">{{ !empty($user->pin) ? 'New PIN' : 'Enter Pin' }} <span class="text-danger">*</span></label>
            <input type="password" class="form-control" wire:model="pin" name="pin" id="pin">
            @error('pin')<div id="pin_error" class="form-text text-danger">{{$message}}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="form-lable">Re-Enter PIN <span class="text-danger">*</span></label>
            <input type="password" class="form-control" wire:model="pin_confirmation" name="pin_confirmation" id="pin_confirmation">
            @error('pin_confirmation')<div id="pin_confirmation_error" class="form-text text-danger">{{$message}}</div>@enderror
        </div>       
        <div class="mb-3">
            <button class="btn btn-dark">
                {{ !empty($user->pin) ? 'Update' : 'Save' }}
            </button>
        </div>
    </form>
</div>