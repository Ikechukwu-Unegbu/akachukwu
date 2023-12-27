<div>
    <form wire:submit.prevent="submit" class="utility-form">
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <select name="" id="" class="form-select @error('network') is-invalid @enderror" wire:model.live="network">
                    @foreach ($networks as $__network)
                        <option value="{{ $__network->network_id }}">{{ $__network->name }}</option>
                    @endforeach
                </select>
                <label for="floatingInput">Network <span class="text-danger">*</span></label>
                @error('network')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <select name="" id="" class="form-select @error('dataType') is-invalid @enderror" wire:model.live="dataType" wire:target="network" wire:loading.attr="disabled">
                    <option value=""></option>
                    @foreach ($dataTypes as $__dataType)
                        <option value="{{ $__dataType->id }}">{{ $__dataType->name }}</option>
                    @endforeach
                </select>
                <label for="floatingInput">Data Type <span class="text-danger">*</span></label>
                @error('dataType')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
            <div class="mt-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                <div wire:loading wire:target="network" class="">
                    <i style="font-size: 20px" class="fa fa-circle-notch fa-spin"></i>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="text" wire:model="phone_number" class="form-control @error('phone_number') is-invalid @enderror" id="mobile" placeholder="">
                <label for="mobile">Phone Number</label>
                @error('phone_number')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <select name="" id="" class="form-select @error('plan') is-invalid @enderror" wire:model.live="plan" wire:target="dataType" wire:loading.attr="disabled">
                    <option value=""></option>
                    @foreach ($plans as $__plan)
                        <option value="{{ $__plan->data_id }}">{{ $__plan->size }} {{ $__plan->type->name }} = ₦ {{ number_format($__plan->amount, 1) }}  {{ $__plan->validity }}</option>
                    @endforeach
                </select>
                <label for="floatingInput">Plan <span class="text-danger">*</span></label>
                @error('plan')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
            <div class="mt-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                <div wire:loading wire:target="dataType" class="">
                    <i style="font-size: 20px" class="fa fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="text" wire:model="amount" class="form-control" id="amount" disabled>
                <label for="amount">Amount</label>
            </div>
        </div>
       
        <button type="submit" class="btn bg-basic text-light" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target='submit'> Continue</span>
            <span wire:loading wire:target="submit">
                <i class="fa fa-spinner fa-spin"></i> Please wait...
            </span>
        </button>
    </form>
</div>
