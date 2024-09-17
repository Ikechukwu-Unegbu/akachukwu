<div>
    <h1 class="text-2xl font-bold mb-6 mt-4">{{ !empty($user->pin) ? 'Update Your PIN' : 'Setup Your PIN' }}</h1>
    <form wire:submit.prevent="{{ !empty($user->pin) ? 'update' : 'submit' }}">
        @if (!empty($user->pin))
            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="old-password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                        placeholder="Current PIN" wire:model="current_pin" required>
                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <i class="fas fa-eye text-gray-400"></i>
                    </button>
                </div>
                @error('current_pin')
                    <span class="text-red-500 font-bold text-sm mt-0 pt-0"> {{ $message }} </span>
                @enderror
            </div>
        @endif

        <div class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="old-password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                    placeholder="{{ !empty($user->pin) ? 'New PIN' : 'Enter Pin' }}" wire:model="pin" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>
            @error('pin')
                <span class="text-red-500 font-bold text-sm mt-0 pt-0"> {{ $message }} </span>
            @enderror
        </div>

        <div class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="old-password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                    placeholder="Re-Enter PIN " wire:model="pin_confirmation" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>
            @error('pin_confirmation')
                <span class="text-red-500 font-bold text-sm mt-0 pt-0"> {{ $message }} </span>
            @enderror
        </div>

        <!-- Save Changes Button -->
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full mb-6">
            Save Changes
        </button>
    </form>
</div>