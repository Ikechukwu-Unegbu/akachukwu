<div class="airtime_form">
    <form wire:submit.prevent="validateIUC" class="utility-form">
        <div class="row">
            <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                <h3 class="text-warning">Cable Subscription</h3>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
        </div>
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
                        <option value="{{ $__cablePlan->cable_plan_id }}" {{ $cable_plan && $cable_plan == $__cablePlan->cable_plan_id ? 'selected' : '' }}>{{ $__cablePlan->package }} = ₦ {{ number_format($__cablePlan->amount, 2) }}</option>
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
                <input type="text" wire:model="customer" class="form-control @error('customer') is-invalid @enderror" id="customer" placeholder="" disabled>
                <label for="customer">Customer Name <span class="text-danger">*</span></label>
                @error('customer')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
            </div>
        </div>
        @endif
        <button type="submit" class="btn bg-basic text-light" wire:loading.attr="disabled" wire:target='validateIUC'>
            <span wire:loading.remove wire:target='validateIUC'> {{ $validate_action ? 'Continue' : 'Validate IUC' }}</span>
            <span wire:loading wire:target="validateIUC">
                <i class="fa fa-spinner fa-spin"></i> {{ $validate_action ? 'Please wait...' : 'Validating...' }}
            </span>
        </button>        
    </form>
    <!-- Off-canvas modal -->
    <div class="offcanvas {{ $form_action ? 'show' : 'close' }}" id="offcanvasPinModal">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Cable</h5>
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
                    <input type="password" class="form-control @error('pin') is-invalid @enderror" wire:model="pin" id="pin" placeholder="Enter your PIN">
                    @error('pin')
                        <span style="font-size: 15px" class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn bg-basic text-light">
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
                        <h6>Cable</h6>
                        <h6>{{ $cables->where('cable_id', $cable_name)->first()?->cable_name }}</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6>Plan</h6>
                        @php
                            $get_plan = $cable_plans->where('cable_plan_id', $cable_plan)->first();
                        @endphp
                        <h6>{{ $get_plan?->package }}</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6>IUC / Smartcard</h6>
                        <h6>{{ $iuc_number }}</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6>Amount</h6>
                        <h6>₦{{ number_format($get_plan?->amount, 2) }}</h6>
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
                        <button type="button" wire:loading.remove wire:target='submit' class="btn btn-danger btn-sm" wire:click="closeModal">No</button>
                        <button type="submit" wire:loading.remove wire:target='submit' class="btn bg-basic text-light btn-sm" id="confirmYes">Yes</button>
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