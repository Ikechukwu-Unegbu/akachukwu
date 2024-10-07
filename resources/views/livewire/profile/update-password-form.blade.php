<div>
    <h1 class="text-2xl font-bold mb-6">Change Password</h1>
    <form wire:submit.prevent="submit">
        @if (session()->has('password_updated'))
            <div class="alert alert-success mb-3">
                {{ session('password_updated') }}
            </div>
        @endif
        <!-- Old Password -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="old-password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                    placeholder="Old Password" wire:model="current_password" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>
            @error('current_password')
                <span class="text-red-500 font-bold text-sm mt-0 pt-0"> {{ $message }} </span>
            @enderror
        </div>

        <!-- New Password -->
        <div class="mb-4 ">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="new-password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                    placeholder="New Password" wire:model="password" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>    
                @error('password')
                    <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
                @enderror
            </div>
        </div>

        <!-- Confirm New Password -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="confirm-new-password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                    placeholder="Confirm New Password" wire:model="password_confirmation" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>
            @error('password_confirmation')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>

        <!-- Save Changes Button -->
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full mb-6">
            Save Changes
        </button>
    </form>
</div>


{{-- <div class="profile_form border-bottom mb-7 border-3 ">
    <div>
        <h3>Change Password</h3>
    </div>
    <form wire:submit.prevent="submit">
        @if (session()->has('password_updated'))
            <div class="alert alert-success mb-3">
                {{ session('password_updated') }}
            </div>
        @endif
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="text" class="form-control" wire:model="current_password" name="current_password"
                id="current_password" aria-describedby="emailHelp">
            @error('current_password')
                <div id="current_password_error" class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Pasword</label>
            <input type="text" class="form-control" wire:model="password" id="password" name="password"
                id="password" aria-describedby="emailHelp">
            @error('password')
                <div id="password_error" class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="text" class="form-control"wire:model="password_confirmation" id="password_confirmation"
                name="password_confirmation" aria-describedby="emailHelp">
            @error('password_confirmation')
                <div id="password_confirm_error" class="form-text text-danger">We'll never share your email with anyone
                    else.</div>
            @enderror
        </div>
        <div class="mb-3">
            <button class="btn btn-dark">Save</button>
        </div>

    </form>

</div> --}}
