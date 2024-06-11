<div class="utility-form">
    <form wire:submit.prevent="validateForm" >
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <h3 class="text-warning">Buy Data Plan</h3>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-9 col-lg-6 col-xl-6 form-floating">
                <select name="" id="" class="form-select @error('network') is-invalid @enderror"
                    wire:model.live="network">
                    @foreach ($networks as $__network)
                    <option value="{{ $__network->network_id }}">{{ $__network->name }}</option>
                    @endforeach
                </select>
                <label for="floatingInput">Network <span class="text-danger">*</span></label>
                @error('network')
                <span style="font-size: 15px" class="text-danger"> {{ $message }} </span>
                @enderror
            </div>
            <div class="col-12 col-sm-6 col-md-9 col-lg-6 col-xl-6"></div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-9 col-lg-6 col-xl-6 form-floating">
                <select name="" id="" class="form-select @error('dataType') is-invalid @enderror"
                    wire:model.live="dataType" wire:target="network" wire:loading.attr="disabled">
                    <option value=""></option>
                    @foreach ($dataTypes as $__dataType)
                    <option value="{{ $__dataType->id }}">{{ $__dataType->name }}</option>
                    @endforeach
                </select>
                <label for="floatingInput">Data Type <span class="text-danger">*</span></label>
                @error('dataType')
                <span style="font-size: 15px" class="text-danger"> {{ $message }} </span>
                @enderror
            </div>
            <div class="mt-3 col-12 col-sm-6 col-md-9 col-lg-6 col-xl-6">
                <div wire:loading wire:target="network" class="">
                    <i style="font-size: 20px" class="fa fa-circle-notch fa-spin"></i>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-9 col-lg-6 col-xl-6 form-floating">
                <input type="number" wire:model="phone_number"
                    class="form-control @error('phone_number') is-invalid @enderror" id="mobile" placeholder="">
                <label for="mobile">Phone Number</label>
                @error('phone_number')
                <span style="font-size: 15px" class="text-danger"> {{ $message }} </span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-9 col-lg-6 col-xl-6 form-floating">
                <select class="form-select @error('plan') is-invalid @enderror" wire:model.live="plan"
                    wire:target="dataType" wire:loading.attr="disabled">
                    <option></option>
                    @foreach ($plans as $__plan)
                    <option value="{{ $__plan->data_id }}" {{ ($plan && $plan === $__plan->data_id) ? 'selected' : ''
                        }}>{{ $__plan->size }} {{ $__plan->type->name }} = ₦
                        {{ number_format($__plan->amount, 1) }} {{ $__plan->validity }}</option>
                    @endforeach
                </select>
                <label for="floatingInput">Plan <span class="text-danger">*</span></label>
                @error('plan')
                <span style="font-size: 15px" class="text-danger"> {{ $message }} </span>
                @enderror
            </div>
            <div class="mt-3 col-12 col-sm-6 col-md-9 col-lg-6 col-xl-6">
                <div wire:loading wire:target="dataType" class="">
                    <i style="font-size: 20px" class="fa fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-9 col-lg-6 col-xl-6 form-floating">
                <input type="text" wire:model="amount" class="form-control" id="amount" disabled>
                <label for="amount">Amount</label>
            </div>
        </div>
        @if(count($beneficiaries))
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <a href="javascript:void(0)" wire:click='beneficiary_action' class="beneficiary-link">Click Here to
                    Select a Beneficiary</a>
            </div>
        </div>

        <div id="modal" class="modal {{ $beneficiary_modal ? 'd-inline' : 'd-none' }}" wire:target='beneficiary'>
            <div class="modal-content">
                @foreach ($beneficiaries as $__beneficiary)
                <a href="javascript:void(0)" class="link" wire:click="beneficiary({{ $__beneficiary->id }})">{{
                    $__beneficiary->beneficiary }}</a>
                {!! !$loop->last ? '
                <hr />' : '' !!}
                @endforeach
            </div>
        </div>
        @endif
        <button type="submit" class="btn bg-basic text-light" wire:loading.attr="disabled" wire:target='validateForm'>
            <span wire:loading.remove wire:target='validateForm'> Continue</span>
            <span wire:loading wire:target="validateForm">
                <i class="fa fa-spinner fa-spin"></i> Please wait...
            </span>
        </button>
    </form>

    <!-- Off-canvas modal -->
    <div class="offcanvas {{ $form_action ? 'show' : 'close' }}" id="offcanvasPinModal">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Data</h5>
            <button type="button" class="btn-close" id="closeOffcanvas" wire:click="closeModal">&times;</button>
        </div>
        <div class="offcanvas-body">
            @if (empty(auth()->user()->pin))
                <h6>Unable to process transaction. Your PIN is required for this transaction.</h6>
                <a class="link" href="{{ route('profile.edit') }}#pin-setup" style="color: #FF9900">Click here to create a PIN.</a>
            @endif
            @if (!$validate_pin_action && !empty(auth()->user()->pin))
            <form wire:submit="validatePin">
                <div>
                    <label for="pin" class="form-label">PIN</label>
                    <input type="password" class="form-control @error('pin') is-invalid @enderror" wire:model="pin"
                        id="pin" placeholder="Enter your PIN">
                    @error('pin')
                    <span style="font-size: 15px" class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn bg-primary text-white">
                    <span wire:loading.remove wire:target='validatePin'> Submit</span>
                    <span wire:loading wire:target="validatePin">
                        <i class="fa fa-spinner fa-spin"></i> Validating...
                    </span>
                </button>
            </form>
            @endif
            @if ($validate_pin_action && !empty(auth()->user()->pin))
            <form wire:submit="submit">
                <div class="confirmation-content">
                    <div class="d-flex justify-content-between">
                        <h6>Network</h6>
                        <h6>{{ $networks->where('network_id', $network)->first()?->name }}</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6>Plan</h6>
                        @php
                            $get_plan = $plans->where('data_id', $plan)->first();
                        @endphp
                        <h6>{{ $get_plan?->size }} {{ $get_plan?->validity }} ({{ $get_plan?->type->name }})</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6>Number</h6>
                        <h6>{{ $phone_number }}</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6>Amount</h6>
                        <h6>₦{{ $amount }}</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6>Wallet</h6>
                        <h6>₦{{ auth()->user()->account_balance }}</h6>
                    </div>

                    <div class="mt-4 pb-4 mb-5">
                        <p class="m-0 p-0">Do you want to continue?</p>
                        <div class="confirmation-content" wire:loading wire:target="submit">
                            <p style="font-size: 25px" class="m-0 p-0"><i class="fa fa-spinner fa-spin"></i></p>
                            <p class="m-0 p-0">Processing...</p>
                        </div>
                        <button type="button" wire:loading.remove wire:target='submit' class="btn btn-danger btn-sm"
                            wire:click="closeModal">No</button>
                        <button type="submit" wire:loading.remove wire:target='submit' class="btn btn-primary btn-sm"
                            id="confirmYes">Yes</button>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>

</div>
@push('scripts')
<script>
    var modal = document.getElementById("modal");
        var span = document.getElementsByClassName("close")[0];
        
        span.onclick = function() {
            modal.classList.remove("d-inline");
            modal.classList.add("d-none");
        }

        window.onclick = function(event) {
        if (event.target == modal) {
                modal.classList.remove("d-inline");
                modal.classList.add("d-none");
                @this.call('beneficiary_action')
            }
        }
</script>
@endpush