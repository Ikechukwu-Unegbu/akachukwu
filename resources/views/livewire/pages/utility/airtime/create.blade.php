<div>
    <form wire:submit.prevent="submit" class="utility-form">
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <select name="network" id="network" class="form-select @error('network') is-invalid @enderror" wire:model.live="network">
                    @foreach ($networks as $__network)
                        <option value="{{ $__network->network_id }}">{{ $__network->name }}</option>
                    @endforeach
                </select>
                <label for="network">Network <span class="text-danger">*</span></label>
                @error('network')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
        </div>       
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="number" wire:model="phone_number" class="form-control @error('phone_number') is-invalid @enderror" id="mobile" placeholder="">
                <label for="mobile">Phone Number <span class="text-danger">*</span></label>
                @error('phone_number')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
        </div>       
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="number" wire:model="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                @error('amount')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
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
