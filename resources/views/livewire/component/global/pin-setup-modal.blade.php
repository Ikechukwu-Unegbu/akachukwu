<div>
    <button style="display: none;" id="modal_button" data-modal-target="pin-setup-modal" data-modal-toggle="pin-setup-modal"
        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button">
        Toggle modal
    </button>
    <div id="pin-setup-modal" tabindex="-1" aria-hidden="true" wire:ignore.self
        class="hidden fixed top-0 left-0 right-0 z-50 justify-center items-center w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold dark:text-white">
                        Setup Transaction Pin
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="pin-setup-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-2">
                    <form wire:submit.prevent="update">
                        <div class="text-center" x-data="pinForm()">
                            <!-- New Pin Inputs -->
                            <p class="mt-2 text-sm dark:text-white">4-digit new transaction PIN</p>
                            <div class="flex justify-center mt-4 space-x-2">
                                @foreach (range(1, 4) as $index)
                                    <input type="password" maxlength="1"
                                        class="w-12 h-12 text-center border rounded-md"
                                        x-on:input="handleInput($event, {{ $index }}, 'pin')"
                                        x-ref="pin{{ $index }}"
                                        x-on:keyup.backspace="handleBackspace($event, {{ $index }}, 'pin')"
                                        wire:change="updatePin({{ $index }}, $event.target.value)"
                                        wire:model.defer="pin.{{ $index }}"
                                        :class="{ 'border-blue-500': isComplete }" />
                                @endforeach
                            </div>
                            @error('pin')
                                <span class="text-red-500 text-sm font-bold flex justify-center">{{ $message }}</span>
                            @enderror

                            <p class="mt-4 text-sm dark:text-white">Confirm your transaction PIN</p>
                            <!-- Confirm Pin Inputs -->
                            <div class="flex justify-center mt-4 space-x-2">
                                @foreach (range(1, 4) as $index)
                                    <input type="password" maxlength="1" class="w-12 h-12 text-center border rounded-md"
                                        x-on:input="handleInput($event, {{ $index }}, 'pin_confirmation')"
                                        x-ref="pin_confirmation{{ $index }}"
                                        x-on:keyup.backspace="handleBackspace($event, {{ $index }}, 'pin_confirmation')"
                                        wire:change="updatePinConfirmation({{ $index }}, $event.target.value)"
                                        wire:model.defer="pin_confirmation.{{ $index }}"
                                        :class="{ 'border-blue-500': isComplete }" />
                                @endforeach
                            </div>
                            @error('pin_confirmation')
                                <span class="text-red-500 text-sm font-bold flex justify-center">{{ $message }}</span>
                            @enderror

                            <div class="mt-6">
                                <button class="bg-vastel_blue text-white px-4 py-2 rounded"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="update">Proceed</span>
                                    <span wire:loading wire:target="update">
                                        <i class="fa fa-circle-notch fa-spin text-sm"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="has-pin-setup" value="{{ $hasPinSetup ? 'true' : 'false' }}">
</div>

@push('scripts')
    <script>
        function pinForm() {
            return {
                pin: Array(4).fill(''),
                pinConfirmation: Array(4).fill(''),
                get isComplete() {
                    const pinComplete = this.pin.every(digit => digit.length === 1) && this.pinConfirmation.every(
                        digit => digit.length === 1);
                    return pinComplete;
                },
                handleInput(event, index, type) {
                    const input = event.target;
                    if (input.value.length && this.$refs[`${type}${index + 1}`]) {
                        this.$refs[`${type}${index + 1}`].focus();
                    }
                    if (type === 'pin') {
                        this.pin[index - 1] = input.value;
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
                    } else {
                        this.pinConfirmation[index - 1] = event.target.value;
                    }
                }
            }
        }
    </script>
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalElement = document.getElementById('pin-setup-modal');
            const hasPinSetup = document.getElementById('has-pin-setup').value === 'true';
            const closeButton = modalElement.querySelector('[data-modal-hide="pin-setup-modal"]');
            // Initialize Flowbite modal instance
            const modal = new Modal(modalElement);

            // Show the modal on page load
            if (hasPinSetup) {
                modal.show();
            }
            // Set an interval to show the modal every 10 minutes
            setInterval(() => {
                // Show the modal on page load
                if (hasPinSetup) {
                    modal.show();
                }
            }, 10000); // 1 minute

            closeButton.addEventListener('click', () => {
                modal.hide(); // Close the modal and hide the backdrop
            });
        });
    </script>
@endpush
