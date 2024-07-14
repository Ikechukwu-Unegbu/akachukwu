<!-- Off-canvas modal -->
<div class="offcanvas {{ $formAction ? 'show' : 'close' }}" id="offcanvasPinModal">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">{{ $title }}</h5>
        <button type="button" class="btn-close" id="closeOffcanvas" wire:click="closeModal">&times;</button>
    </div>
    <div class="offcanvas-body">
        @if (empty(auth()->user()->pin))
            <h6>Unable to process transaction. Your PIN is required for this transaction.</h6>
            <a class="link" href="{{ route('profile.edit') }}#pin-setup" style="color: #FF9900">Click here to create a PIN.</a>
        @endif
        @if (!$validatePinAction && !empty(auth()->user()->pin))
        <form wire:submit="validatePin">
            <div class="pin-request">
                <div class="pin-display">
                    <input type="password" class="form-control @error('pin') is-invalid @enderror" wire:model="pin" id="pin"  readonly />
                    @error('pin')
                        <span style="font-size: 15px" class="text-danger mt-0 pt-0">{{ $message }}</span>
                    @enderror
                </div>
                <div class="pin-buttons">
                    <div class="">
                        @foreach (range(1, 3) as $digit)
                            <button type="button" wire:click="addDigit({{ $digit }})">{{ $digit }}</button>
                        @endforeach
                    </div>
                    <div class="">
                        @foreach (range(4, 6) as $digit)
                            <button type="button" wire:click="addDigit({{ $digit }})">{{ $digit }}</button>
                        @endforeach
                    </div>
                    <div class="">
                        @foreach (range(7, 9) as $digit)
                            <button type="button" wire:click="addDigit({{ $digit }})">{{ $digit }}</button>
                        @endforeach
                    </div>
                    <div class="">
                        <button type="button" class="btn-danger" wire:click="deletePin">
                            <i class="fa-solid fa-eraser"></i>
                        </button>
                        <button type="button" wire:click="addDigit(0)">0</button>
                        <button type="submit" class="btn-success">
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
                    <button type="button" wire:loading.remove wire:target='submit' class="btn btn-danger btn-sm" wire:click="closeModal">No</button>
                    <button type="submit" wire:loading.remove wire:target='submit' class="btn bg-basic text-light btn-sm" id="confirmYes">Yes</button>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>