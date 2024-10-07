<div>
    <form wire:submit.prevent="validateIUC">

        @if (count($beneficiaries))
            <button type="button" data-modal-target="beneficiaryModal" data-modal-toggle="beneficiaryModal"
                class="w-full bg-gray-100 text-gray-900 border border-gray-300 py-2 rounded-lg mb-4">
                Select Beneficiary
            </button>
            <!-- Modal -->
            <div id="beneficiaryModal" tabindex="-1"
                class="fixed inset-0 z-50 justify-center items-center bg-black bg-opacity-50 {{ $beneficiary_modal ? 'flex' : 'hidden' }}">
                <div class="bg-white rounded-lg w-full max-w-sm p-4">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-medium text-gray-900">Select Beneficiary</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500"
                            data-modal-hide="beneficiaryModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="py-4">
                        <!-- Beneficiary List -->
                        <ul class="space-y-2">
                            @foreach ($beneficiaries as $__beneficiary)
                                <li wire:click="beneficiary({{ $__beneficiary->id }})"
                                    class="bg-gray-100 py-2 px-4 rounded cursor-pointer hover:bg-gray-200">
                                    {{ $__beneficiary->beneficiary }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="relative space-x-0 z-50 mb-6 mt-10 w-full group">
            <button type="button" id="dropdownCable" class="w-full text-left bg-transparent border-0 border-b-2 border-gray-300 text-gray-900 focus:ring-0 focus:border-blue-600 peer">
                {{-- <span>{{ $cable_name ? $cables->where('cable_id', $cable_name)->first()?->cable_name : 'Select TV' }}</span> --}}
                @if ($cables->where('cable_id', $cable_name)->count())
                    <div class="flex">
                        <img src="{{ $cables->where('cable_id', $cable_name)->first()?->image_url }}" alt="Logo" class="mr-3 w-100 h-15"> 
                        <h4>{{ $cables->where('cable_id', $cable_name)->first()?->cable_name  }}</h4>
                    </div>
                @else
                    Select Cable
                @endif
            </button>

            <!-- Dropdown menu -->
            <div id="cableDropdown" class="hidden absolute z-10 w-full bg-white rounded-lg shadow-lg">
                <ul class="py-2 text-sm text-gray-700">
                    @foreach ($cables as $__cable)
                        <li wire:click='selectedCable({{ $__cable->id }})' class="px-4 py-2 flex items-center cursor-pointer hover:bg-gray-100">
                            <img src="{{ $__cable->image_url }}" alt="{{ $__cable->cable_name }} Logo" class="mr-3 w-12 h-15"> {{ $__cable->cable_name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Smart Card Number -->
        <div class="relative z-0 mb-6 w-full group mt-10">
            <input type="text" id="smartCardNumber"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder="" wire:model.live="iuc_number" />
            <label for="smartCardNumber"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Enter
                Smart Card Number
            </label>
            @error('iuc_number')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>

        <div class="relative z-50 mb-6 w-full group pt-6">
            <!-- Button to show selected package and trigger dropdown -->
            <!-- Button to toggle dropdown -->
            <button type="button" id="dropdownPackage"
                class="w-full text-left bg-transparent border-0 border-b-2 border-gray-300 text-gray-900 focus:ring-0 focus:border-blue-600 peer">
                <span>{{ $cable_plan ? $cable_plans->where('cable_plan_id', $cable_plan)->first()?->package : 'Select Package' }}</span>
            </button>

            <!-- Dropdown menu -->
            <div id="packageDropdown" class="hidden absolute z-10 w-full bg-white rounded-lg shadow-lg">
                <ul class="py-2 text-sm text-gray-700">
                    @foreach ($cable_plans as $__cablePlan)
                        <li wire:click="selectedPackage({{ $__cablePlan->id }})" class="px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-gray-100">
                            {{ $__cablePlan->package }} <span class="text-green-600">₦{{ number_format($__cablePlan->amount, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @if ($validate_action)
        <div class="relative z-0 mb-6 w-full group mt-10">
            <input type="text" id="customer"  class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="" wire:model="customer" readonly />
            <label for="customer" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Owner Card
            </label>
            @error('customer')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>
        @endif
        @if ($cable_name && $cables->where('cable_id', $cable_name)->first()?->discount > 0)
            <div class="text-red-500 font-semibold pb-7 mt-3">
                Amount to Pay (₦{{ number_format($calculatedDiscount, 2) }})
                {{ $cables->where('cable_id', $cable_name)->first()?->discount }}% Discount
            </div>
        @endif

        <!-- Proceed Button -->
        <button type="submit" class="text-white bg-vastel_blue hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-[8rem] px-5 py-2.5 text-center">
            <span wire:loading.remove wire:target='validateIUC'> {{ $validate_action ? 'Proceed' : 'Validate IUC' }}</span>
            <span wire:loading wire:target="validateIUC">
                <i class="fa fa-circle-notch fa-spin text-sm"></i> {{ $validate_action ? 'Please wait...' : 'Validating...' }}
            </span>
        
        </button>
    </form>

    <x-pin-validation title="Cable TV" :formAction="$form_action" :validatePinAction="$validate_pin_action">
        <div class="flex justify-between">
            <h6>Cable TV</h6>
            <h6>{{ $cables->where('cable_id', $cable_name)->first()?->cable_name }}</h6>
        </div>
        @if (count($cable_plans))
        <div class="flex justify-between">
            <h6>Plan</h6>
            @php
                $get_plan = $cable_plans->where('cable_plan_id', $cable_plan)->first();
            @endphp
            <h6>{{ $get_plan?->package ?? '' }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>IUC No.</h6>
            <h6>{{ $iuc_number }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>Amount</h6>
            <h6>₦{{ number_format($get_plan?->amount, 2) ?? '' }}</h6>
        </div>
        @endif
        <div class="flex justify-between">
            <h6>Wallet</h6>
            <h6>₦{{ number_format(auth()->user()->account_balance, 2) }}</h6>
        </div>
    </x-pin-validation>
    <x-transaction-status :status="$transaction_status" utility="Cable TV" :link="$transaction_link" :modal="$transaction_modal" />
</div>

@push('scripts')
    <script>
        var modal = document.getElementById("beneficiaryModal");

        document.addEventListener('click', function(event) {
            const modal = document.getElementById('beneficiaryModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });

        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', function() {
                const modal = document.getElementById('beneficiaryModal');
                modal.classList.add('hidden');
            });
        });

        const observer = new MutationObserver((mutationsList) => {
            for (let mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    hideModalBackdrop();
                }
            }
        });

        function hideModalBackdrop() {
            const modalBackdrop = document.querySelector('div[modal-backdrop]');

            if (modalBackdrop && modalBackdrop.classList.contains('fixed')) {
                modalBackdrop.classList.replace('fixed', 'hidden');
            }
        }

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        window.onclick = function(event) {
            if (event.target == modal) {
                hideModalBackdrop();
                @this.call('beneficiary_action')
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeDropdown('dropdownPackage', 'packageDropdown', 'selectedPackage');
            initializeDropdown('dropdownCable', 'cableDropdown', 'selectedCable');
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
