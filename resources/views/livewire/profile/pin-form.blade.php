<div>
    {{-- <h1 class="text-2xl font-bold mb-6 mt-4">{{ !empty($user->pin) ? 'Update Your PIN' : 'Setup Your PIN' }}</h1> --}}

    <form wire:submit.prevent="{{ !empty($user->pin) ? 'update' : 'submit' }}">
        <h2 class="text-lg font-semibold">{{ !empty($user->pin) ? 'Change Transaction Pin ' : 'Setup Transaction PIN' }}
        </h2>

        <div x-data="pinForm()">

            @if (!empty($user->pin))
                <p class="mt-4 text-sm text-gray-600">Current PIN</p>
                <!-- Confirm Pin Inputs -->
                <div class="flex justify-center mt-4 space-x-2">
                    @foreach (range(1, 4) as $index)
                        <input type="text" maxlength="1"
                            class="w-12 h-12 text-center border rounded-md"
                            x-on:input="handleInput($event, {{ $index }}, 'current_pin')" 
                            x-ref="current_pin{{ $index }}"
                            x-on:keyup.backspace="handleBackspace($event, {{ $index }}, 'current_pin')"
                            wire:change="updateCurrentPin({{ $index }}, $event.target.value)"
                            wire:model.defer="current_pin.{{ $index }}"
                            :class="{ 'border-blue-500': isComplete }"
                        />
                    @endforeach
                </div>
                @error('current_pin')
                    <span class="text-red-500 text-sm font-bold flex justify-center">{{ $message }}</span>
                @enderror
            @endif

            <!-- New Pin Inputs -->
            <p class="mt-2 text-sm text-gray-600">4-digit new transaction PIN</p>
            <div class="flex justify-center mt-4 space-x-2">
                @foreach (range(1, 4) as $index)
                    <input type="text" maxlength="1"
                        class="w-12 h-12 text-center border rounded-md"
                        x-on:input="handleInput($event, {{ $index }}, 'pin')" 
                        x-ref="pin{{ $index }}"
                        x-on:keyup.backspace="handleBackspace($event, {{ $index }}, 'pin')"
                        wire:change="updatePin({{ $index }}, $event.target.value)"
                        wire:model.defer="pin.{{ $index }}"
                        :class="{ 'border-blue-500': isComplete }"
                    />
                @endforeach
            </div>
            @error('pin')
                <span class="text-red-500 text-sm font-bold flex justify-center">{{ $message }}</span>
            @enderror
            
            <p class="mt-4 text-sm text-gray-600">Confirm your transaction pin</p>
            <!-- Confirm Pin Inputs -->
            <div class="flex justify-center mt-4 space-x-2">
                @foreach (range(1, 4) as $index)
                    <input type="text" maxlength="1"
                        class="w-12 h-12 text-center border rounded-md"
                        x-on:input="handleInput($event, {{ $index }}, 'pin_confirmation')" 
                        x-ref="pin_confirmation{{ $index }}"
                        x-on:keyup.backspace="handleBackspace($event, {{ $index }}, 'pin_confirmation')"
                        wire:change="updatePinConfirmation({{ $index }}, $event.target.value)"
                        wire:model.defer="pin_confirmation.{{ $index }}"
                        :class="{ 'border-blue-500': isComplete }"
                    />
                @endforeach
            </div>
            @error('pin_confirmation')
                <span class="text-red-500 text-sm font-bold flex justify-center">{{ $message }}</span>
            @enderror
            
            <button x-show="isComplete" type="submit" id="closeChangePinModal" class="mt-6 bg-vastel_blue text-white py-2 px-4 rounded hover:bg-blue-600">                
                <span wire:loading.remove wire:target="{{ !empty($user->pin) ? 'update' : 'submit' }}">Proceed</span>
                <span wire:loading wire:target="{{ !empty($user->pin) ? 'update' : 'submit' }}">
                    <i class="fa fa-circle-notch fa-spin text-sm"></i>
                </span>
            </button>
        </div>
    </form>

    {{-- @if (!empty($user->pin))
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
        </button> --}}

</div>
@push('scripts')
<script>
function pinForm() {
    const requireCurrentPin =  "{{ !empty($user->pin) ? true : false }}";

    return {
        pin: Array(4).fill(''),
        current_pin: requireCurrentPin === "1" ? Array(4).fill('') : null,
        pinConfirmation: Array(4).fill(''),
        get isComplete() {
            const pinComplete = this.pin.every(digit => digit.length === 1) && this.pinConfirmation.every(digit => digit.length === 1);
            if (requireCurrentPin) {
                return pinComplete && this.current_pin.every(digit => digit.length === 1);
            }
            return pinComplete;
        },
        handleInput(event, index, type) {
            const input = event.target;
            if (input.value.length && this.$refs[`${type}${index + 1}`]) {
                this.$refs[`${type}${index + 1}`].focus();
            }
            if (type === 'pin') {
                this.pin[index - 1] = input.value;
            } else if (type === 'current_pin' && requireCurrentPin) {
                this.current_pin[index - 1] = input.value;
            } else {
                this.pinConfirmation[index - 1] = input.value;
            }
        },
        handleBackspace(event, index, type) {
            if (!event.target.value.length && this.$refs[`${type}${index - 1}`]) {
                this.$refs[`${type}${index - 1}`].focus();
            }
            if (type === 'pin') {
                this.pin[index - 1] = event.target.value;
            } else if (type === 'current_pin' && requireCurrentPin) {
                this.current_pin[index - 1] = event.target.value;
            } else {
                this.pinConfirmation[index - 1] = event.target.value;
            }
        }
    }
}
</script>   
@endpush