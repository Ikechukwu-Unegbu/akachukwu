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
        @if ($network && $networks->where('network_id', $network)->first()?->data_discount > 0)
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <div class="text-danger">
                    Amount to Pay (₦{{ $calculatedDiscount }}) {{ $network ? $networks->where('network_id', $network)->first()?->data_discount . '% Discount' : '' }}
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
        <button type="submit" class="btn btn-warning text-light" wire:loading.attr="disabled" wire:target='validateForm'>
            <span wire:loading.remove wire:target='validateForm'> Continue</span>
            <span wire:loading wire:target="validateForm">
                <i class="fa fa-spinner fa-spin"></i> Please wait...
            </span>
        </button>
    </form>

    <x-pin-validation 
        title="Data" 
        :formAction="$form_action" 
        :validatePinAction="$validate_pin_action"
    >
        <div class="d-flex justify-content-between">
            <h6>Network</h6>
            <h6>{{ $networks->where('network_id', $network)->first()?->name }}</h6>
        </div>
        <div class="d-flex justify-content-between">
            <h6>Plan</h6>
            @if (count($plans))
            @php
                $get_plan = $plans->where('data_id', $plan)->first();
            @endphp
            <h6>{{ $get_plan?->size }} {{ $get_plan?->validity }} ({{ $get_plan?->type->name }})</h6>
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