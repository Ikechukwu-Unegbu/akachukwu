<div class="card">

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <ol class="list-group list-group-numbered">
        @foreach ($user->virtualAccounts as $accounts)
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">{{$accounts->account_number}}</div>
                    {{$accounts->account_name}} - {{$accounts->bank_name}}
                    <small style="font-style: bold;" class="fw-bold">{{$accounts->gateway->name}}</small>
                </div>

                <!-- Modal Trigger with unique ID per account -->
                <span class="badge text-xs text-bg-primary rounded-pill"
                    data-bs-toggle="modal" 
                    data-bs-target="#confirmChangeModal{{$accounts->id}}">
                    Change
                </span>
            </li>

            <!-- Unique Modal for each account -->
            <div class="modal fade" id="confirmChangeModal{{$accounts->id}}" tabindex="-1" aria-labelledby="confirmChangeModalLabel{{$accounts->id}}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmChangeModalLabel{{$accounts->id}}">Confirm Account Change</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to change the account <strong>{{$accounts->account_name}} | {{$accounts->account_number}}</strong> from {{$accounts->gateway->name}}?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            
                            <button type="button" class="btn btn-primary" 
                                    wire:click="changeAccount({{ $accounts->id }}, '{{ $accounts->bank_code }}', {{$user->id}})"
                                    data-bs-dismiss="modal"
                                    wire:loading.attr="disabled"
                                    wire:target="changeAccount">
                                <!-- Spinner while loading -->
                                <span wire:loading wire:target="changeAccount" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <!-- Button text -->
                                <span wire:loading.remove wire:target="changeAccount">Yes, Change</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </ol>
    <script>
    Livewire.on('accountChanged', () => {
        // Find all open modals and hide them
        document.querySelectorAll('.modal.show').forEach(modal => {
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();
        });

        // Remove any modal backdrops
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.remove();
        });
    });
</script>

</div>

