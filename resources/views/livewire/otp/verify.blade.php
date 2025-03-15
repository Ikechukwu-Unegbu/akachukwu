<div>
    <form wire:submit="submit">
        <div x-data="otpForm()">
            <div class="p-6 space-y-6">
                <div class="flex justify-center space-x-4">
                    @foreach (range(1, 4) as $index)
                    <input type="text" maxlength="1"
                        class="w-12 h-12 text-2xl text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        x-on:input="handleInput($event, {{ $index }})" x-ref="otp{{ $index }}"
                        x-on:keyup.backspace="handleBackspace($event, {{ $index }})"
                        wire:change="updateOtp({{ $index }}, $event.target.value)"
                        wire:model.defer="otp.{{ $index }}" :class="{ 'border-blue-500': isComplete }" />
                @endforeach
                </div>
                @error('otp')
                    <span class="text-red-500 text-sm font-bold flex justify-center">{{ $message }}</span>
                @enderror
                <div class="flex justify-center">
                    <a href="#" wire:click='resend' class="text-sm text-red hover:text-red-300">
                        <span wire:loading.remove wire:target="resend">Resend OTP</span>
                        <span wire:loading wire:target="resend">
                            Please wait...
                        </span>                    
                    </a>
                </div>
            </div>
            <!-- Modal footer -->
            <div
                class="flex items-center justify-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button x-show="isComplete" @click="submit" type="submit"
                    class="w-full px-5 py-2.5 text-white bg-vastel_blue hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm">
                    <span wire:loading.remove wire:target="submit">Submit</span>
                    <span wire:loading wire:target="submit">
                        <i class="fa fa-circle-notch fa-spin text-sm"></i>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
@push('scripts')
<script>
    function otpForm() {
        return {
            otp: Array(4).fill(''),
            get isComplete() {
                return this.otp.every(digit => digit.length === 1);
            },
            handleInput(event, index) {
                const input = event.target;
                if (input.value.length && this.$refs[`otp${index + 1}`]) {
                    this.$refs[`otp${index + 1}`].focus();
                }
                this.otp[index - 1] = input.value;
            },
            handleBackspace(event, index) {
                if (!event.target.value.length && this.$refs[`otp${index - 1}`]) {
                    this.$refs[`otp${index - 1}`].focus();
                }
                this.otp[index - 1] = event.target.value;
            },
            submit() {
                this.$wire.call('submit', this.otp.join(''));
            }
        }
    }
</script>
@endpush