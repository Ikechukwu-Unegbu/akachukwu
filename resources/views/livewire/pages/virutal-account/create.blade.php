<div>
    <div class="mt-5">
        <h5>Secure Your Transactions with a Static Account</h5>
        <button type="button" class="btn btn-warning submit text-light btn-sm" wire:click="virtualAccountAction" wire:loading.attr="disabled">            
            <div wire:loading.remove wire:target="virtualAccountAction">
                Create Static Account
            </div>

            <div wire:loading wire:target="virtualAccountAction">  
                <i class="fa fa-spinner fa-spin"></i> Please wait...
            </div>
        </button>
    </div>
</div>
