<div id="pinModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 {{ $formAction ? 'flex' : 'hidden' }} w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full items-center justify-center">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $validatePinAction ? 'Confirm Transaction' : 'Enter Transaction Pin' }}
                </h3>
                <button wire:click="closeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="pinModal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            @if (empty(auth()->user()->pin))
                <div class="p-6 space-y-6">
                    <h6 class="text-white mb-3 pb-2">Unable to process transaction. Your PIN is required for this transaction.</h6>
                    <a class="text-white hover:text-red-600 underline mt-3" href="{{ route('settings.credentials') }}">Click here to create a PIN.</a>
                </div>
            @endif
        
            @if (!$validatePinAction && !empty(auth()->user()->pin))
            <!-- Modal body (PIN input) -->
            <form wire:submit="validatePin">
                <div x-data="pinForm()">
                    <div class="p-6 space-y-6">
                        <div class="flex justify-center space-x-4">
                            @foreach (range(1, 4) as $index)
                                <input type="password" maxlength="1"
                                    class="w-12 h-12 text-2xl text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    x-on:input="handleInput($event, {{ $index }})" 
                                    x-ref="pin{{ $index }}"
                                    x-on:keyup.backspace="handleBackspace($event, {{ $index }})"
                                    wire:change="updatePin({{ $index }}, $event.target.value)"
                                    wire:model.defer="pin.{{ $index }}"
                                    :class="{ 'border-blue-500': isComplete }"
                                />
                            @endforeach
                        </div>
                        @error('pin')
                            <span class="text-red-500 text-sm font-bold flex justify-center">{{ $message }}</span>
                        @enderror
                        {{-- <div class="flex justify-center">
                            <a href="#" class="text-sm text-white hover:text-blue-800">Forgot Pin</a>
                        </div> --}}
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button x-show="isComplete" @click="validatePin" type="submit" class="w-full px-5 py-2.5 text-white bg-vastel_blue hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm">
                            <span wire:loading.remove wire:target="validatePin">Pay</span>
                            <span wire:loading wire:target="validatePin">
                                <i class="fa fa-circle-notch fa-spin text-sm"></i>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
            @endif

            @if ($validatePinAction && !empty(auth()->user()->pin))
            <form wire:submit="submit">
                <div class="p-4 text-white">
                    {{ $slot ?? '' }}
                    
                    <div class="mt-4 pb-4 mb-5 text-center">
                        <p class="mb-3 p-0" wire:loading.remove wire:target='submit'>Do you want to continue?</p>
                        <div class="confirmation-content" wire:loading wire:target="submit">
                            <p class="m-0 text-xl p-0"><i class="fa fa-circle-notch fa-spin"></i></p>
                            <p class="m-0 p-0">Processing...</p>
                        </div>
                        <div class="flex justify-center">
                            <button type="button" wire:loading.remove wire:target='submit' class="px-5 me-3 py-2.5 text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm" id="closeModal" wire:click="closeModal"><i class="fa-solid fa-times"></i> No</button>
                            <button type="submit" wire:loading.remove wire:target='submit' class="px-5 py-2.5 text-white bg-vastel_blue hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm" id="confirmYes"><i class="fa-solid fa-thumbs-up"></i> Yes</button>
                        </div>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function pinForm() {
        return {
            pin: Array(4).fill(''),
            get isComplete() {
                return this.pin.every(digit => digit.length === 1);
            },
            handleInput(event, index) {
                const input = event.target;
                if (input.value.length && this.$refs[`pin${index + 1}`]) {
                    this.$refs[`pin${index + 1}`].focus();
                }
                this.pin[index - 1] = input.value;
            },
            handleBackspace(event, index) {
                if (!event.target.value.length && this.$refs[`pin${index - 1}`]) {
                    this.$refs[`pin${index - 1}`].focus();
                }
                this.pin[index - 1] = event.target.value;
            },
            validatePin() {
                this.$wire.call('validatePin', this.pin.join(''));
            }
        }
    }
</script>   
@endpush