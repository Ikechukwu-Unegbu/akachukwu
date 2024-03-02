<div class="airtime_form">
    <form wire:submit.prevent="submit" class="utility-form">
        
         <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
               <h3 class="text-warning">Pay Electricity Bills</h3>
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
                <input type="number" wire:model="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                @error('amount')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
        </div> 
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="text" wire:model="customer_phone_number" class="form-control @error('customer_phone_number') is-invalid @enderror" id="customer_phone_number" placeholder="">
                <label for="customer_phone_number">Customer Phone Number <span class="text-danger">*</span></label>
                @error('customer_phone_number')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
        </div>      
        
        
        @if (!$validate_action)
            <button type="button" class="btn bg-basic text-light" wire:loading.attr="disabled" wire:click.prevent="validateMeterNumber">
                <span wire:loading.remove wire:target='validateMeterNumber'> Validate Meter Number</span>
                <span wire:loading wire:target="validateMeterNumber">
                    <i class="fa fa-spinner fa-spin"></i> Validating...
                </span>
            </button>
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
        
        <button type="submit" class="btn bg-basic text-light" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target='submit'> Continue</span>
            <span wire:loading wire:target="submit">
                <i class="fa fa-spinner fa-spin"></i> Please wait...
            </span>
        </button>
        @endif
    </form>
</div>
