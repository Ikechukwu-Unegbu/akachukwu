<div class="airtime_form">
    <form wire:submit="validateForm" class="utility-form">
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <h3 class="text-warning">Airtime VTU Top up</h3>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <select name="network" id="network" class="form-select @error('network') is-invalid @enderror"
                    wire:model.live="network">
                    @foreach ($networks as $__network)
                    <option value="{{ $__network->network_id }}">{{ $__network->name }}</option>
                    @endforeach
                </select>
                <label for="network">Network <span class="text-danger">*</span></label>
                @error('network')
                <span style="font-size: 15px" class="text-danger"> {{ $message }} </span>
                @enderror
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="number" wire:model="phone_number"
                    class="form-control @error('phone_number') is-invalid @enderror" id="mobile" placeholder="">
                <label for="mobile">Phone Number <span class="text-danger">*</span></label>
                @error('phone_number')
                <span style="font-size: 15px" class="text-danger"> {{ $message }} </span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="number" wire:model.live="amount" class="form-control @error('amount') is-invalid @enderror"
                    id="amount" placeholder="">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                @error('amount')
                <span style="font-size: 15px" class="text-danger"> {{ $message }} </span>
                @enderror
            </div>
        </div>
        @if ($network && $networks->where('network_id', $network)->first()?->airtime_discount > 0)
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <div class="text-danger">
                    Amount to Pay (₦{{ $calculatedDiscount }}) {{ $network ? $networks->where('network_id', $network)->first()?->airtime_discount . '% Discount' : '' }}
                </div>
            </div>
        </div>
        @endif
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
        <button type="submit" class="btn btn-warning text-light" wire:loading.attr="disabled" wire:target='validateForm' wire:target='airtime'>
            <span wire:loading.remove wire:target='validateForm'> Continue</span>
            <span wire:loading wire:target="validateForm">
                <i class="fa fa-spinner fa-spin"></i> Please wait...
            </span>
        </button>
    </form>
    <x-pin-validation 
        title="Airtime" 
        :formAction="$form_action" 
        :validatePinAction="$validate_pin_action"
    >
        <div class="d-flex justify-content-between">
            <h6>Network</h6>
            @if (count($networks))
            <h6>{{ $networks->where('network_id', $network)->first()?->name }}</h6>
            @endif
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
    </x-pin-validation>
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