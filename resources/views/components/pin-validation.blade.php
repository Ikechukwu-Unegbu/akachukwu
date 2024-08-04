<!-- Off-canvas modal -->
<div class="offcanvas-custom {{ $formAction ? 'show' : 'close' }}" id="offcanvasPinModal">
    <div class="offcanvas-custom-header">
        <h5 class="offcanvas-custom-title">{{ $title }}</h5>
        <button type="button" class="btn-close" id="closeOffcanvas" wire:click="closeModal">&times;</button>
    </div>
    <div class="offcanvas-custom-body">
        @if (empty(auth()->user()->pin))
            <h6>Unable to process transaction. Your PIN is required for this transaction.</h6>
            <a class="link" href="{{ route('profile.edit') }}#pin-setup" style="color: #FF9900">Click here to create a PIN.</a>
        @endif
        @if (!$validatePinAction && !empty(auth()->user()->pin))
        <form wire:submit="validatePin">
            <div class="pin-request">
                <div class="pin-display">
                    <input type="password" class="form-control @error('pin') is-invalid @enderror" id="pin-input"  maxlength="4" readonly />
                    @error('pin')
                        <span style="font-size: 15px" class="text-danger mt-0 pt-0">{{ $message }}</span>
                    @enderror
                </div>
                <div class="pin-buttons">
                    <div class="">
                        <button type="button" onclick="addPin(1)">1</button>
                        <button type="button" onclick="addPin(2)">2</button>
                        <button type="button" onclick="addPin(3)">3</button>
                    </div>
                    <div class="">
                        <button type="button" onclick="addPin(4)">4</button>
                        <button type="button" onclick="addPin(5)">5</button>
                        <button type="button" onclick="addPin(6)">6</button>
                    </div>
                    <div class="">
                        <button type="button" onclick="addPin(7)">7</button>
                        <button type="button" onclick="addPin(8)">8</button>
                        <button type="button" onclick="addPin(9)">9</button>
                    </div>
                    <div class="">
                        <button type="button" class="btn-danger" onclick="deletePin()">
                            <i class="fa-solid fa-eraser"></i>
                        </button>
                        <button type="button" onclick="addPin(0)">0</button>
                        <button type="button" onclick="submitPin()" class="btn-success">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        @endif
        @if ($validatePinAction && !empty(auth()->user()->pin))
        <form wire:submit="submit">
            <div class="confirmation-content">
                {{ $slot ?? '' }}
                
                <div class="mt-4 pb-4 mb-5">
                    <p class="m-0 p-0">Do you want to continue?</p>
                    <div class="confirmation-content" wire:loading wire:target="submit">
                        <p style="font-size: 25px" class="m-0 p-0"><i class="fa fa-spinner fa-spin"></i></p>
                        <p class="m-0 p-0">Processing...</p>
                    </div>
                    <button type="button" wire:loading.remove wire:target='submit' class="btn btn-danger btn-sm" id="closeModal" wire:click="closeModal">No</button>
                    <button type="submit" wire:loading.remove wire:target='submit' class="btn bg-basic text-light btn-sm" id="confirmYes">Yes</button>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>

@push('scripts')
<script>
    let pin = '';

    function addPin(num) {
        if (pin.length < 4) {
            pin += num;
            document.getElementById('pin-input').value = pin;
            @this.set('pin', pin);
        }
    }

    function deletePin() {
        pin = pin.slice(0, -1);
        document.getElementById('pin-input').value = pin;
        @this.set('pin', pin);
    }

    function clearPin() {
        pin = '';
        document.getElementById('pin-input').value = pin;
        @this.set('pin', pin);
    }

    function submitPin() {
        @this.call('validatePin').then(response => {
        }).catch(error => console.error('Error:', error));
    }

    const closeOffcanvas = document.getElementById('closeOffcanvas');
    closeOffcanvas.addEventListener('click', function () {
        pin = "";
        document.getElementById('pin-input').value = pin;
    });

    const closeModal = document.getElementById('closeModal');
    closeModal.addEventListener('click', function () {
        pin = "";
        document.getElementById('pin-input').value = pin;
    });
</script>
@endpush