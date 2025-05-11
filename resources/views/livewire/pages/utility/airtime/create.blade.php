<div>
    <style>

    </style>
    <form wire:submit="validateForm">
        @if (count($beneficiaries))
            <button type="button" data-modal-target="beneficiaryModal" data-modal-toggle="beneficiaryModal"
                class="w-full bg-gray-100 text-gray-900 border border-gray-300 py-2 rounded-lg mb-4">
                Select Beneficiary
            </button>
            <!-- Modal -->
      
            <div id="beneficiaryModal" tabindex="1" class="fixed inset-0 z-50 justify-center items-center bg-black bg-opacity-50 {{ $beneficiary_modal ? 'flex' : 'hidden' }}">
                <div class="bg-white rounded-lg w-full max-w-sm p-4 opacity-100 z-100 shadow-lg">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-medium text-gray-900">Select Beneficiary</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500" data-modal-hide="beneficiaryModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="py-4" style="z-index: 10;">
                        <!-- Beneficiary List -->
                        <ul class="space-y-2" style="z-index: 10;">
                            @foreach ($beneficiaries as $__beneficiary)
                                <li onClick="toggleBodyScroll()" wire:click="beneficiary({{ $__beneficiary->id }})" class="bg-gray-100 py-2 px-4 rounded cursor-pointer hover:bg-gray-200">
                                    {{ $__beneficiary->beneficiary }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>


        @endif

        <div class="relative z-10 mb-6 w-full group pt-6">
            <!-- Button to toggle dropdown -->
            <button type="button" id="networkDropdownButton"
                class="w-full text-left bg-transparent border-0 border-b-2 border-gray-300 text-gray-900 focus:ring-0 focus:border-blue-600 peer pb-3">
                @if ($networks->where('network_id', $network)->count())
                    <div class="flex">
                        <img src="{{ $networks->where('network_id', $network)->first()?->logo() }}" alt="Logo" class="mr-3 w-6 h-6"> 
                        <h4>{{ $networks->where('network_id', $network)->first()->name  }}</h4>
                    </div>
                @else
                    Select Network
                @endif
            </button>

            <!-- Dropdown menu -->
            <div id="networkDropdown" class="hidden absolute z-10 w-full bg-white rounded-lg shadow-lg">
                <ul class="py-2 text-sm text-gray-700">
                    @foreach ($networks as $__network)
                        <li wire:click="selectedNetwork({{ $__network->id }})" class="px-4 py-2 flex items-center cursor-pointer hover:bg-gray-100">
                            <img src="{{ $__network->logo() }}" alt="{{ $__network->name }} Logo" class="mr-3 w-6 h-6"> {{ $__network->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="relative z-0 mb-6 w-full group">
            <input type="number" wire:model="phone_number" name="phone_number" id="phone_number"
                class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " />
            <label for="phone_number"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Mobile
                Number</label>
            <i class="fas fa-mobile-retro absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-400"></i>
            @error('phone_number')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>        
        <div class="relative z-0 mb-6 w-full group">
            <input type="number" name="amount" wire:model.live="amount" id="amount" required
                class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=""  />
            <label for="amount"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Amount(₦)</label>
            @error('amount')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>

        <!-- schedule fields -->
        <h1>Schedule fields</h1>

        <!-- end schedule fields -->
        @if ($network && $networks->where('network_id', $network)->first()?->airtime_discount > 0)
        <div class="text-red-500 font-semibold pb-7 mt-3">
            Amount to Pay (₦{{ $calculatedDiscount }}) {{ $network ? $networks->where('network_id', $network)->first()?->airtime_discount . '% Discount' : '' }}
        </div>
        @endif
        <button type="submit" wire:loading.attr="disabled" wire:target='validateForm' wire:target='airtime'
            class="w-[8rem] bg-vastel_blue text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
            <span wire:loading.remove wire:target='validateForm'>Proceed</span>
            <span wire:loading wire:target="validateForm">
                <i class="fa fa-circle-notch fa-spin text-sm"></i>
            </span>
        </button>
    </form>

    <x-pin-validation title="Airtime" :formAction="$form_action" :validatePinAction="$validate_pin_action">
        <div class="flex justify-between">
            <h6>Network</h6>
            @if (count($networks))
                <h6>{{ $networks->where('network_id', $network)->first()?->name }}</h6>
            @endif
        </div>
        <div class="flex justify-between">
            <h6>Number</h6>
            <h6>{{ $phone_number }}</h6>

        </div>
        <div class="flex justify-between">
            <h6>Amount</h6>
            <h6>₦{{ number_format(floatval($amount), 2) }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>Wallet</h6>
            <h6>₦{{ number_format(auth()->user()->account_balance, 2) }}</h6>
        </div>
    </x-pin-validation>
    <x-transaction-status 
        :status="$transaction_status"
        utility="Airtime"
        :link="$transaction_link"
        :modal="$transaction_modal"
    />
</div>
@push('scripts')
    <script>
       
        var modal = document.getElementById("beneficiaryModal");


//         document.addEventListener('DOMContentLoaded', () => {
//     const beneficiaryModal = document.getElementById('beneficiaryModal');
//     const body = document.body;

//     // Create an observer instance to monitor modal visibility changes
//     const observer = new MutationObserver(() => {
//         if (beneficiaryModal.classList.contains('hidden')) {
//             // Modal is hidden, allow page scroll
//             body.classList.remove('overflow-hidden');
//         } else {
//             // Modal is visible, disable page scroll
//             body.classList.add('overflow-hidden');
//         }
//     });

//     // Start observing modal visibility changes
//     observer.observe(beneficiaryModal, { attributes: true, attributeFilter: ['class'] });

//     // Add event listeners to close modal on background click
//     beneficiaryModal.addEventListener('click', (event) => {
//         if (event.target === beneficiaryModal) {
//             beneficiaryModal.classList.add('hidden');
//         }
//     });

//     document.querySelectorAll('.close-modal').forEach(button => {
//         button.addEventListener('click', () => {
//             beneficiaryModal.classList.add('hidden');
//         });
//     });
// });



document.addEventListener('DOMContentLoaded', () => {
    const beneficiaryModal = document.getElementById('beneficiaryModal');
    const body = document.body;

    function removeBackdrop() {
        const modalBackdrop = document.querySelector('div[modal-backdrop]');
            
                if (modalBackdrop && modalBackdrop.classList.contains('fixed')) {
                    modalBackdrop.classList.replace('fixed', 'hidden');
                }
    }

    function toggleBodyScroll(isModalVisible) {
        if (isModalVisible) {
            body.classList.add('overflow-hidden'); // Disable scroll
        } else {
            body.classList.remove('overflow-hidden'); // Enable scroll
            removeBackdrop();
        }
    }

    // Observer to detect changes in the modal's visibility
    const observer = new MutationObserver(() => {
        toggleBodyScroll(!beneficiaryModal.classList.contains('hidden'));
    });

    observer.observe(beneficiaryModal, { attributes: true, attributeFilter: ['class'] });

    // Close modal and remove backdrop immediately on beneficiary selection
    document.querySelectorAll('.beneficiary-option').forEach(item => {
        item.addEventListener('click', () => {
            beneficiaryModal.classList.add('hidden');
            removeBackdrop();
            toggleBodyScroll(false);
        });
    });

    // Close modal and remove backdrop on outside click
    beneficiaryModal.addEventListener('click', (event) => {
        if (event.target === beneficiaryModal) {
            beneficiaryModal.classList.add('hidden');
            removeBackdrop();
        }
    });

    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', () => {
            beneficiaryModal.classList.add('hidden');
            removeBackdrop();
        });
    });
});




    </script>
         <script>
            document.addEventListener('DOMContentLoaded', function() {
                initializeDropdown('networkDropdownButton', 'networkDropdown', 'selectedPackage');
            });
        </script>
        <script>
            /**
             * Initializes a dropdown menu with specified behavior.
             * @param {string} dropdownButtonId - The ID of the button that toggles the dropdown.
             * @param {string} dropdownMenuId - The ID of the dropdown menu.
             * @param {string} selectedTextId - The ID of the element where the selected item text is displayed.
             */
            function initializeDropdown(dropdownButtonId, dropdownMenuId, selectedTextId) {
                const dropdownButton = document.getElementById(dropdownButtonId);
                const dropdownMenu = document.getElementById(dropdownMenuId);
                const selectedText = document.getElementById(selectedTextId);
                
                // Toggle dropdown visibility
                dropdownButton.addEventListener('click', () => {
                    dropdownMenu.classList.toggle('hidden');
                });
    
                // Create and assign a unique selectItem function to the global scope
                window[`selectItem_${dropdownButtonId}`] = function(itemName) {
                    selectedText.innerHTML = `${itemName}`;
                    dropdownMenu.classList.add('hidden');
                };
    
                // Close the dropdown if clicked outside
                document.addEventListener('click', function (event) {
                    if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }
        </script>
@endpush
