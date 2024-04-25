<div>
    <div class="dashboard_body">
        @section('head')
        <link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}" />
        <link rel="stylesheet" href="{{asset('css/index.css')}}" />
        <link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}" />
        @endsection
        <div class="sidebar_body">
            @include('components.dasboard_sidebar')
        </div>
        <div class="dashboard_section">
            <div class="airtime_form">
                <form wire:submit="submit" class="utility-form">
                    <div class="row">
                        <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                            <h3 class="text-warning">Money Transfer</h3>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                            <input type="number" wire:model="recipient_account"
                                class="form-control @error('recipient_account') is-invalid @enderror" id="mobile"
                                placeholder="">
                            <label for="mobile">Recipient Account <span class="text-danger">*</span></label>
                            @error('recipient_account')<span style="font-size: 15px" class="text-danger"> {{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                            <select name="bank" id="bank" class="form-select @error('bank') is-invalid @enderror" wire:model="bank">
                                <option value="">--------------</option>
                                {{-- @forelse ($banks as $__banks)
                                    <option value="{{ $__banks->id }}">{{ $__banks->name }}</option>
                                @empty
                                    <option value="">-----------</option>
                                @endforelse --}}
                            </select>
                            <label for="bank">Select Bank <span class="text-danger">*</span></label>
                            @error('bank')<span style="font-size: 15px" class="text-danger"> {{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                            <input type="number" wire:model="amount"
                                class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="">
                            <label for="amount">Amount <span class="text-danger">*</span></label>
                            @error('amount')<span style="font-size: 15px" class="text-danger"> {{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                            <input type="text" wire:model="remark"
                                class="form-control @error('remark') is-invalid @enderror" id="remark"
                                placeholder="">
                            <label for="remark">Remark </label>
                            @error('remark')<span style="font-size: 15px" class="text-danger"> {{ $message }}</span>@enderror
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
        </div>
    </div>
</div>