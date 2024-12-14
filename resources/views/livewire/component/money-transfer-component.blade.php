<div wire:ignore.self id="transfer-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
          
            <div class="flex  p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-center text-gray-900 dark:text-white">
                    Credit Recipient's Account
                </h3>
                <button wire:click='handleCloseTransferMoneyModal' style="float: left;" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="transfer-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form wire:submit="@if ($recipient) handleMoneyTransfer @else handleVerifyRecipient @endif">
                <!-- Modal body -->
                <div class="p-6 space-y-4">
                    <!-- Account Number Input -->
                    @if ($recipient)
                        <div class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <img src="{{ $recipient?->profilePicture }}" class="rounded-full w-100 h-12"  alt="{{ $recipient?->name }}" />
                                <span class="text-blue-600 font-medium">{{ $recipient?->name }} <p>{{ '@' . $recipient?->username }}</p></span>
                            </div>
                            {{-- <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Noteworthy technology acquisitions 2021</h5>
                            <p class="font-normal text-gray-700 dark:text-gray-400">Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.</p> --}}
                        </div>
                        <div class="mt-2">
                            <label for="" class="dark:text-white mb-3">Enter Amount</label>
                            <input type="number" placeholder="Enter Amount" id="amount_input" wire:model="amount" class="w-full p-2 border-none rounded-lg shadow focus:ring-vastel_blue focus:border-vastel_blue dark:bg-gray-700 dark:border-white dark:placeholder-white dark:text-white">
                            @error ('amount')
                                <span class="text-red-500 text-sm font-bold flex justify-center">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                    <div>
                        <label for="" class="dark:text-white mb-3">Enter Recipient Username or Email</label>
                        <input type="text" placeholder="Enter Username or Email" wire:model="username" class="w-full p-2 border-none rounded-lg shadow focus:ring-vastel_blue focus:border-vastel_blue dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        @if ($error_msg)
                            <span class="text-red-500 text-sm font-bold flex justify-center">{{ $error_msg }}</span>
                        @endif
                    </div>
                    @endif
                    {{-- <!-- Bank Selection Dropdown -->
                    <select class="w-full p-2 border-none rounded-lg shadow focus:ring-vastel_blue focus:border-vastel_blue dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option>Select bank</option>
                        <!-- Populate with bank options as needed -->
                    </select> --}}
                </div>
                <!-- Modal footer -->
                <div class="p-6">
                    <button wire:loading.attr="disabled" type="submit" class="w-full text-white bg-vastel_blue hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-vastel_blue dark:focus:ring-blue-800">
                        <span wire:loading.remove>Proceed</span>
                        <span wire:loading>
                            <i class="fa fa-circle-notch fa-spin text-sm"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
   document.getElementById('amount_input').addEventListener('change', function (event) {
        // Remove any '+' or '-' characters from the input
        event.target.value = event.target.value.replace(/[+-]/g, '');
    });

    </script>
</div>