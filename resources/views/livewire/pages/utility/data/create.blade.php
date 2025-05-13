<div>
    <!-- Form Start -->
    <form wire:submit.prevent="validateForm">
        @if (count($beneficiaries))
            <button type="button" data-modal-target="beneficiaryModal" data-modal-toggle="beneficiaryModal"
                class="w-full bg-gray-100 text-gray-900 border border-gray-300 py-2 rounded-lg mb-4">
                Select Beneficiary
            </button>
            <!-- Modal -->
            <div id="beneficiaryModal" tabindex="-1"
                class="fixed inset-0 z-[9999] justify-center items-center bg-black bg-opacity-50 {{ $beneficiary_modal ? 'flex' : 'hidden' }}">
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
                                <li wire:click="beneficiary({{ $__beneficiary->id }})" onclick="toggleModal()"
                                    class="bg-gray-100 py-2 px-4 rounded cursor-pointer hover:bg-gray-200">
                                    {{ $__beneficiary->beneficiary }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        <!-- Network Type -->
        <div class="relative z-50 mb-6 w-full group pt-6">
            <!-- Button to toggle dropdown -->
            <button type="button" id="networkDropdownButton"
                class="w-full text-left bg-transparent border-0 border-b-2 border-gray-300 text-gray-900 focus:ring-0 focus:border-blue-600 peer pb-3">
                @if ($networks->where('network_id', $network)->count())
                    <div class="flex">
                        <img src="{{ $networks->where('network_id', $network)->first()?->logo() }}" alt="Logo"
                            class="mr-3 w-6 h-6">
                        <h4>{{ $networks->where('network_id', $network)->first()->name }}</h4>
                    </div>
                @else
                    Select Network
                @endif
            </button>

            <!-- Dropdown menu -->
            <div id="networkDropdown" class="hidden w-full bg-white rounded-lg shadow-lg">
                <ul class="py-2 text-sm text-gray-700">
                    @foreach ($networks as $__network)
                        <li wire:click="selectedNetwork({{ $__network->id }})"
                            class="px-4 py-2 flex items-center cursor-pointer hover:bg-gray-100">
                            <img src="{{ $__network->logo() }}" alt="{{ $__network->name }} Logo" class="mr-3 w-6 h-6">
                            {{ $__network->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Data Type -->
        <div class="relative space-x-0 z-50 mb-10 mt-10 w-full group">
            <button type="button" id="datatypeDropdown"
                class="w-full text-left bg-transparent border-0 border-b-2 border-gray-300 text-gray-900 focus:ring-0 focus:border-blue-600 peer">
                <span>{{ $dataType ? $dataTypes->where('id', $dataType)->first()?->name : 'Select Data Type' }}</span>
            </button>

            <!-- Dropdown menu -->
            <div id="datatypePackages" class="hidden w-full bg-white rounded-lg shadow-lg">
                <ul class="py-2 text-sm text-gray-700">
                    @foreach ($dataTypes as $__dataType)
                        <li wire:click='selectedDataType({{ $__dataType->id }})'
                            class="px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-gray-100">
                            {{ $__dataType->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Plan -->
        <div class="relative space-x-0 z-50 mb-6 mt-4 w-full group">
            <button type="button" id="dataPlanDropdown"
                class="w-full text-left bg-transparent border-0 border-b-2 border-gray-300 text-gray-900 focus:ring-0 focus:border-blue-600 peer pb-3">
                @if (count($plans) && $plans->where('data_id', $plan)->count())
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">{{ $plans->where('data_id', $plan)->first()?->size }} for
                            {{ $plans->where('data_id', $plan)->first()?->validity }}</span>
                        <div class="flex flex-row gap-3">
                            <span
                                class="text-green-600 font-bold">₦{{ number_format($plans->where('data_id', $plan)->first()?->amount, 1) }}</span>
                        </div>
                    </div>
                @else
                    Select Data Plan
                @endif
            </button>

            <div id="dataPlanPackages" class="w-full hidden absolute bg-white shadow-lg rounded-lg overflow-hidden z-0">
                <ul class="divide-y divide-gray-200">
                    @foreach ($plans as $__plan)
                        <li wire:click="selectPlan({{ $__plan->id }})"
                            class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-100">
                            <span class="text-gray-700">{{ $__plan->size }} for {{ $__plan->validity }}</span>
                            <div class="flex flex-row gap-3">
                                <span class="text-green-600 font-bold">₦{{ number_format($__plan->amount, 1) }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Mobile Number -->
        <div class="relative z-0 mb-6 w-full group">
            <input type="text" id="mobileNumber"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-vastel_blue peer"
                placeholder=" " wire:model="phone_number" />
            <label for="mobileNumber"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-vastel_blue peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Mobile
                Number*</label>

            @error('phone_number')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>

        <!-- Amount -->
        <div class="relative z-0 mb-6 w-full group">
            <input type="text" id="amount"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-vastel_blue peer"
                placeholder=" " wire:model="amount" readonly />
            <label for="amount"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-vastel_blue peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Amount</label>

            @error('amount')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>

        <!-- Wrapper with Alpine.js data -->
        <div x-data="{ showSchedule: false }">
            <!-- Toggle Section -->
            <div class="flex justify-between mt-3">
                <div>
                    <p class="text-[#969696] text-lg">Schedule this transaction</p>
                </div>
                <div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="toggleSchedule" class="sr-only peer" x-model="showSchedule">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                        </div>
                    </label>
                </div>
            </div>

            <!-- Schedule Form (Shows when toggle is on) -->
            <div x-show="showSchedule" class="my-4 flex flex-col">
                <label for="frequency" class="border border-[#D8D8D894] rounded-2xl p-2">
                    <h3 class="mb-2 text-base font-semibold text-[#646464]">Frequency</h3>
                    <select id="frequency" name="frequency"
                        class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </label>

                <label for="start-date" class="mt-2 border border-[#D8D8D894] rounded-2xl p-2">
                    <h3 class="mb-2 text-base font-semibold text-[#646464]">Start Date</h3>
                    <input type="date" id="start-date" name="start-date" placeholder="02/02/2025"
                        class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </label>

                <label for="time" class="mt-2 border border-[#D8D8D894] rounded-2xl p-2">
                    <h3 class="mb-2 text-base font-semibold text-[#646464]">Time</h3>
                    <div class="w-full">
                        <input type="time" id="time" name="time" placeholder="08:00AM"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                </label>
            </div>
        </div>

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        @endpush


        @if ($network && $networks->where('network_id', $network)->first()?->data_discount > 0)
            <div class="text-red-500 font-semibold pb-7 mt-3">
                Amount to Pay (₦{{ number_format($calculatedDiscount, 2) }})
                {{ $network ? $networks->where('network_id', $network)->first()?->data_discount . '% Discount' : '' }}
            </div>
        @endif

        <!-- Proceed Button -->
        <button type="submit" wire:loading.attr="disabled" wire:target='validateForm' wire:target='airtime'
            class="w-[8rem] bg-vastel_blue text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
            <span wire:loading.remove wire:target='validateForm'>Proceed</span>
            <span wire:loading wire:target="validateForm">
                <i class="fa fa-circle-notch fa-spin text-sm"></i>
            </span>
        </button>
    </form>
    <x-pin-validation title="Data" :formAction="$form_action" :validatePinAction="$validate_pin_action">
        <div class="flex justify-between">
            <h6>Network</h6>
            <h6>{{ $networks->where('network_id', $network)->first()?->name }}</h6>
        </div>
        <div class="flex justify-between">
            <h6>Plan</h6>
            @if (count($plans))
                @php
                    $get_plan = $plans->where('data_id', $plan)->first();
                @endphp
                <h6>{{ $get_plan?->size }} {{ $get_plan?->validity }} ({{ $get_plan?->type->name }})</h6>
            @endif
        </div>
        <div class="flex justify-between">
            <h6>Number</h6>
            <h6>{{ $phone_number }}</h6>
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
    <x-transaction-status :status="$transaction_status" utility="Data" :link="$transaction_link" :modal="$transaction_modal" />
</div>
@push('scripts')
    <script>
        var modal = document.getElementById("beneficiaryModal");

        // document.addEventListener('click', function(event) {
        //     const modal = document.getElementById('beneficiaryModal');
        //     if (event.target === modal) {
        //         modal.classList.add('hidden');
        //     }
        // });
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('beneficiaryModal');
            const body = document.body;

            if (event.target === modal) {
                modal.classList.add('hidden');
                body.classList.remove('overflow-hidden'); // Re-enable scrolling
            }
        });


        // document.querySelectorAll('.close-modal').forEach(button => {
        //     button.addEventListener('click', function() {
        //         const modal = document.getElementById('beneficiaryModal');
        //         modal.classList.add('hidden');
        //     });
        // });

        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', function() {
                const modal = document.getElementById('beneficiaryModal');
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden'); // Re-enable scrolling
            });
        });

        function toggleModal() {
            const modal = document.getElementById('beneficiaryModal');
            const body = document.body;

            // Toggle modal and body overflow
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                body.classList.add('overflow-hidden'); // Disable scrolling
            } else {
                modal.classList.add('hidden');
                body.classList.remove('overflow-hidden'); // Enable scrolling
            }
        }



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
            initializeDropdown('networkDropdownButton', 'networkDropdown', 'selectedPackage');
            initializeDropdown('datatypeDropdown', 'datatypePackages', 'selectedPackage');
            initializeDropdown('dataPlanDropdown', 'dataPlanPackages', 'selectedPackage');
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
            document.addEventListener('click', function(event) {
                if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }
    </script>
@endpush
