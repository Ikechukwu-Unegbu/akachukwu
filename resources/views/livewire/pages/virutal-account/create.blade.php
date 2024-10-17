<div>
    <div class="mt-5 pt-5">
        <h5 class="mb-4 font-bold">Secure Your Transactions with a Static Account</h5>
        
        <button type="button" class="w-full bg-vastel_blue text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:bg-blue-700" wire:click="virtualAccountAction" wire:loading.attr="disabled">            
            <div wire:loading.remove wire:target="virtualAccountAction">
                Create Static Account
            </div>

            <div wire:loading wire:target="virtualAccountAction">  
                <i class="fa fa-spinner fa-spin"></i> Please wait...
            </div>
        </button>
    </div>
</div>
