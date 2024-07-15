<div class="airtime_form">
    <form wire:submit="validateForm" class="utility-form">
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <h3 class="text-warning">Generate Result Checker PIN</h3>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <select name="exam_name" id="exam_name" class="form-select @error('exam_name') is-invalid @enderror" wire:model.live="exam_name" wire:loading.attr="disabled" wire:target='quantity'>
                   <option value="">----</option>
                   @foreach ($resultCheckers as $result)
                       <option value="{{ $result->name }}">{{ $result->name }}</option>
                   @endforeach
                </select>
                <label for="exam_name">Exam Name <span class="text-danger">*</span></label>
                @error('exam_name')
                    <span style="font-size: 15px" class="text-danger"> {{ $message }} </span>
                @enderror   
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="number" wire:model.live="quantity" class="form-control @error('quantity') is-invalid @enderror" wire:loading.attr="disabled" wire:target='exam_name' id="quantity" placeholder="">
                <label for="quantity">No. of Pins/Tokens <span class="text-danger">*</span></label>
                @error('quantity')
                <span style="font-size: 15px" class="text-danger"> {{ $message }} </span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <input type="text" wire:model="amount" class="form-control" id="amount" readonly>
                <label for="amount">Amount</label>
            </div>
        </div>
        <button type="submit" class="btn btn-warning text-light" wire:loading.attr="disabled" wire:target='validateForm'>
            <span wire:loading.remove wire:target='validateForm'> Continue</span>
            <span wire:loading wire:target="validateForm">
                <i class="fa fa-spinner fa-spin"></i> Please wait...
            </span>
        </button>
    </form>

    <x-pin-validation 
        title="Result Checker" 
        :formAction="$form_action" 
        :validatePinAction="$validate_pin_action"
    >
        <div class="d-flex justify-content-between">
            <h6>Exam Name</h6>
            <h6>{{ $resultCheckers->where('name', $exam_name)->first()?->name }}</h6>
        </div>
        <div class="d-flex justify-content-between">
            <h6>No. of Pins/Tokens</h6>
            <h6>{{ $quantity }}</h6>
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
