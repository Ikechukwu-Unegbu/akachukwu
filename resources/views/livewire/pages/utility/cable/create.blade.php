<div>
    <form wire:submit.prevent="submit" class="utility-form">
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <select name="cable_name" id="cable_name" class="form-select @error('cable_name') is-invalid @enderror" wire:model.live="cable_name">
                    <option value="">----------</option>
                    @foreach ($cables as $__cable)
                        <option value="{{ $__cable->cable_id }}">{{ $__cable->cable_name }}</option>
                    @endforeach
                </select>
                <label for="cable_name">Cable Name <span class="text-danger">*</span></label>
                @error('cable_name')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
        </div>       
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="text" wire:model.live="iuc_number" class="form-control @error('iuc_number') is-invalid @enderror" id="iuc_number" placeholder="">
                <label for="iuc_number">Smart Card number / IUC number <span class="text-danger">*</span></label>
                @error('iuc_number')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <select name="cable_plan" id="cable_plan" class="form-select @error('cable_plan') is-invalid @enderror" wire:model.live="cable_plan" wire:target="cable_name" wire:loading.attr="disabled">
                    <option value="">----------</option>
                    @foreach ($cable_plans as $__cablePlan)
                        <option value="{{ $__cablePlan->cable_plan_id }}">{{ $__cablePlan->package }} = ₦ {{ number_format($__cablePlan->amount, 2) }}</option>
                    @endforeach
                </select>
                <label for="cable_plan">Cable Plan <span class="text-danger">*</span></label>
                @error('cable_plan')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
            <div class="mt-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                <div wire:loading wire:target="cable_name" class="">
                    <i style="font-size: 20px" class="fa fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
        @if (!$validate_action)
            <button type="button" class="btn bg-basic text-light" wire:loading.attr="disabled" wire:click.prevent="validateIUCNumber">
                <span wire:loading.remove wire:target='validateIUCNumber'> Validate IUC</span>
                <span wire:loading wire:target="validateIUCNumber">
                    <i class="fa fa-spinner fa-spin"></i> Validating...
                </span>
            </button>
        @endif

        @if ($validate_action)
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="text" wire:model="customer" class="form-control @error('customer') is-invalid @enderror" id="customer" placeholder="" disabled>
                <label for="customer">Customer Name <span class="text-danger">*</span></label>
                @error('customer')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
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
