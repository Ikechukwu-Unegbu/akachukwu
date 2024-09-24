<div>
    <form wire:submit.prevent="validateIUC">
    
        @if (count($beneficiaries))
            <button type="button" data-modal-target="beneficiaryModal" data-modal-toggle="beneficiaryModal"
                class="w-full bg-gray-100 text-gray-900 border border-gray-300 py-2 rounded-lg mb-6">
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
    
        {{-- <div class="relative z-0 w-full mb-6 group">
            <select id="provider" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" wire:model.live="disco_name">
                <option>Select Provider</option>
                @foreach ($electricity as $__electricity)
                    <option value="{{ $__electricity->disco_id }}">{{ $__electricity->disco_name }}</option>
                @endforeach
            </select>
            <label for="provider" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-vastel_blue peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Select Provider</label>
            @error('disco_name')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div> --}}

        <div class="relative z-50 mb-6 w-full group pt-6">
            <!-- Button to toggle dropdown -->
            <button type="button" id="dropdown"
                class="w-full text-left bg-transparent border-0 border-b-2 border-gray-300 text-gray-900 focus:ring-0 focus:border-blue-600 peer pb-3">
                @if ($electricity->where('disco_id', $disco_name)->count())
                    <div class="flex">
                        <img src="{{ $electricity->where('disco_id', $disco_name)->first()?->image_url }}" alt="Logo" class="mr-3 w-6 h-6"> 
                        <h4>{{ $electricity->where('disco_id', $disco_name)->first()?->disco_name  }}</h4>
                    </div>
                @else
                    Select Disco
                @endif
            </button>

            <!-- Dropdown menu -->
            <div id="electricityDropdown" class="hidden absolute z-10 w-full bg-white rounded-lg shadow-lg">
                <ul class="py-2 text-sm text-gray-700">
                    @foreach ($electricity as $__electricity)
                        <li wire:click="selectedElectricity({{ $__electricity->id }})" class="px-4 py-2 flex items-center cursor-pointer hover:bg-gray-100">
                            <img src="{{ $__electricity->image_url }}" alt="{{ $__electricity->disco_name }} Logo" class="mr-3 w-6 h-6"> {{ $__electricity->disco_name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    
        <div class="relative z-0 w-full mb-6 group">
            <input type="text" id="meter-number" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " wire:model.live="meter_number" />
            <label for="meter-number" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-vastel_blue peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Enter Meter Number</label>
    
            @error('meter_number')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>
    
        <div class="relative z-0 w-full mb-6 mt-4 group">
            <select id="provider" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" wire:model.live="meter_type">
                <option value="">----------</option>
                @foreach ($meter_types as $key => $__meterType)
                    <option value="{{ $key }}">{{ $__meterType }}</option>
                @endforeach
            </select>
            <label for="provider" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-vastel_blue peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Select Meter Type</label>
            @error('meter_type')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>

        <div class="relative z-0 mb-6 w-full group mt-10">
            <input type="number" id="customer_phone_number"  class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="" wire:model="customer_phone_number" />
            <label for="customer_phone_number" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Phone Number
            </label>
            @error('customer_phone_number')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>
    
        <div class="relative z-0 w-full mb-6 group">
            <input type="text" id="amount" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " wire:model.live="amount" />
            <label for="amount" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-vastel_blue peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Amount</label>
            @error('amount')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>
    
        @if ($validate_action)
        <div class="relative z-0 mb-6 w-full group mt-10">
            <input type="text" id="customer"  class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="" wire:model="customer_name" readonly />
            <label for="customer" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Owner Card
            </label>
            @error('customer_name')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>
        <div class="relative z-0 mb-6 w-full group mt-10">
            <input type="text" id="customer"  class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="" wire:model="customer_address" readonly />
            <label for="customer" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Owner Address
            </label>
            @error('customer_address')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>
        @endif
    
        @if ($disco_name && $electricity->where('disco_id', $disco_name)->first()?->discount > 0)
        <div class="text-red-500 font-bold text-sm mb-6">
            Amount to Pay (₦{{ number_format($calculatedDiscount, 2) }}) {{ $disco_name ? $electricity->where('disco_id', $disco_name)->first()?->discount . '% Discount' : '' }}
        </div>
        @endif
    
        <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded text-sm font-semibold hover:bg-blue-800 transition"> 
            <span wire:loading.remove wire:target='validateIUC'> {{ $validate_action ? 'Proceed' : 'Validate IUC' }}</span>
            <span wire:loading wire:target="validateIUC">
                <i class="fa fa-circle-notch fa-spin text-sm"></i> {{ $validate_action ? 'Please wait...' : 'Validating...' }}
            </span>
        </button>
    </form>
    
    <x-pin-validation title="Electricity" :formAction="$form_action" :validatePinAction="$validate_pin_action">
        <div class="flex justify-between">
            <h6>Disco</h6>
            <h6>{{ $electricity->where('disco_id', $disco_name)->first()?->disco_name }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>Meter No.</h6>
            <h6>{{ $meter_number }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>Meter Type</h6>
            <h6>{{ $meter_type == 1 ? 'PREPAID' : 'POSTPAID' }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>Customer Name</h6>
            <h6>{{ $customer_name }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>Customer Phone No.</h6>
            <h6>{{ $customer_phone_number }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>Amount</h6>
            <h6>₦{{ $amount }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>Wallet</h6>
            <h6>₦{{ number_format(auth()->user()->account_balance, 2) }}</h6>
        </div>
    </x-pin-validation>
    <x-transaction-status :status="$transaction_status" utility="Electricity" :link="$transaction_link" :modal="$transaction_modal" />
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
        initializeDropdown('dropdown', 'electricityDropdown', 'selectedPackage');
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