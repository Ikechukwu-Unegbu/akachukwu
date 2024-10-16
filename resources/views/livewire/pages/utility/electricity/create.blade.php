<div class="airtime_form">
    <form wire:submit.prevent="validateIUC" class="utility-form">        
         <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
               <h3 class="text-warning">Electricity Bill Payment</h3>
            </div>
        <div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <select name="disco_name" id="disco_name" class="form-select @error('disco_name') is-invalid @enderror" wire:model.live="disco_name">
                    <option value="">----------</option>
                    @foreach ($electricity as $__electricity)
                        <option value="{{ $__electricity->disco_id }}">{{ $__electricity->disco_name }}</option>
                    @endforeach
                </select>
                <label for="disco_name">Disco Name <span class="text-danger">*</span></label>
                @error('disco_name')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
        </div>       
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="number" wire:model.live="meter_number" class="form-control @error('meter_number') is-invalid @enderror" id="meter_number" placeholder="">
                <label for="meter_number">Meter Number <span class="text-danger">*</span></label>
                @error('meter_number')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <select name="meter_type" id="meter_type" class="form-select @error('meter_type') is-invalid @enderror" wire:model.live="meter_type">
                    <option value="">----------</option>
                    @foreach ($meter_types as $key => $__meterType)
                        <option value="{{ $key }}">{{ $__meterType }}</option>
                    @endforeach
                </select>
                <label for="meter_type">Meter Type <span class="text-danger">*</span></label>
                @error('meter_type')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
        </div> 
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="number" wire:model="customer_phone_number" class="form-control @error('customer_phone_number') is-invalid @enderror" id="customer_phone_number" placeholder="">
                <label for="customer_phone_number">Customer Phone Number <span class="text-danger">*</span></label>
                @error('customer_phone_number')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="number" wire:model.live="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                @error('amount')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
        </div> 
        @if ($disco_name && $electricity->where('disco_id', $disco_name)->first()?->discount > 0)
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <div class="text-danger">
                    Amount to Pay (₦{{ $calculatedDiscount }}) {{ $disco_name ? $electricity->where('disco_id', $disco_name)->first()?->discount . '% Discount' : '' }}
                </div>
            </div>
        </div>
        @endif
        @if(count($beneficiaries))
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <a href="javascript:void(0)" wire:click='beneficiary_action' class="beneficiary-link">Click Here to Select a Beneficiary</a>
            </div>
        </div>
    
        <div id="modal" class="modal {{ $beneficiary_modal ? 'd-inline' : 'd-none' }}" wire:target='beneficiary'>
            <div class="modal-content">
                @foreach ($beneficiaries as $__beneficiary)
                    <a href="javascript:void(0)" class="link" wire:click="beneficiary({{ $__beneficiary->id }})">{{ $__beneficiary->beneficiary }}</a>
                    {!! !$loop->last ? '<hr />' : '' !!}
                @endforeach
            </div>
          </div>
        @endif
        @if ($validate_action)
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="text" wire:model="customer_name" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" placeholder="" disabled>
                <label for="customer">Customer Name <span class="text-danger">*</span></label>
                @error('customer_name')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="text" wire:model="customer_address" class="form-control @error('customer_address') is-invalid @enderror" id="customer_address" placeholder="" disabled>
                <label for="customer_address">Customer Address <span class="text-danger">*</span></label>
                @error('customer_address')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
        </div>
        @endif
        
        <button type="submit" class="btn btn-warning text-light" wire:loading.attr="disabled" wire:target='validateIUC' wire:target='amount'>
            <span wire:loading.remove wire:target='validateIUC'> {{ $validate_action ? 'Continue' : 'Validate Meter Number' }} </span>
            <span wire:loading wire:target="validateIUC">
                <i class="fa fa-spinner fa-spin"></i> {{ $validate_action ? 'Please wait...' : 'Validating...' }}
            </span>
        </button>
        
    </form>
    <x-pin-validation 
    title="Electricity" 
    :formAction="$form_action" 
    :validatePinAction="$validate_pin_action"
>
    <div class="d-flex justify-content-between">
        <h6>Disco</h6>
        <h6>{{ $electricity->where('disco_id', $disco_name)->first()?->disco_name }}</h6>
    </div>
    <div class="d-flex justify-content-between">
        <h6>Meter No.</h6>
        <h6>{{ $meter_number }}</h6>
    </div>
    <div class="d-flex justify-content-between">
        <h6>Meter Type</h6>
        <h6>{{ $meter_type == 1 ? 'PREPAID' : 'POSTPAID' }}</h6>
    </div>
    <div class="d-flex justify-content-between">
        <h6>Customer Name</h6>
        <h6>{{ $customer_name }}</h6>
    </div>
    <div class="d-flex justify-content-between">
        <h6>Customer Phone No.</h6>
        <h6>{{ $customer_phone_number }}</h6>
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