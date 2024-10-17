{{-- <div class="airtime_form">
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

</div> --}}

<div>
    <form wire:submit="validateForm">
        <div class="mb-6">
            <label for="exam-type" class="block text-sm font-medium mb-1">Exam Type</label>
            <select id="exam-type"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                wire:model.live="exam_name" wire:loading.attr="disabled" wire:target='quantity'>
                <option>Select Exam Type</option>
                @foreach ($resultCheckers as $result)
                    <option value="{{ $result->name }}">{{ $result->name }}</option>
                @endforeach
            </select>
            @error('exam_name')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>

        <div class="relative z-0 mb-6 w-full group">
            <input type="number" name="quantity" wire:model.live="quantity" id="quantity"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="" />
            <label for="quantity"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">No.
                of Pins/Tokens</label>
            @error('quantity')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>

        <div class="mb-6">
            <label for="amount" class="block text-sm font-medium mb-1">Amount</label>
            <input type="text" wire:model="amount" id="amount"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="" readonly>
        </div>

        <button type="submit"
            wire:loading.attr="disabled" wire:target='validateForm'
            class="w-full bg-vastel_blue text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
            <span wire:loading.remove wire:target='validateForm'>Proceed</span>
            <span wire:loading wire:target="validateForm">
                <i class="fa fa-circle-notch fa-spin text-sm"></i>
            </span>
        </button>
    </form>

    <x-pin-validation title="E-PINS" :formAction="$form_action" :validatePinAction="$validate_pin_action">
        <div class="flex justify-between">
            <h6>Exam Name</h6>
            <h6>{{ $resultCheckers->where('name', $exam_name)->first()?->name }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>No. of Pins/Tokens</h6>
            <h6>{{ $quantity }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>Amount</h6>
            <h6>₦{{ $amount }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>Wallet</h6>
            <h6>₦{{ auth()->user()->account_balance }}</h6>
        </div>
    </x-pin-validation>

    <x-transaction-status :status="$transaction_status" utility="E-PINS" :link="$transaction_link" :modal="$transaction_modal" />
</div>
