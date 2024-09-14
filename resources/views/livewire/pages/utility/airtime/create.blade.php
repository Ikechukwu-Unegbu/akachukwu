<div>
    <form wire:submit="validateForm">
        @if (count($beneficiaries))
            <button type="button" data-modal-target="beneficiaryModal" data-modal-toggle="beneficiaryModal"
                class="w-full bg-gray-100 text-gray-900 border border-gray-300 py-2 rounded-lg mb-4">
                Select Beneficiary
            </button>
            <!-- Modal -->
            <div id="beneficiaryModal" tabindex="-1" class="fixed inset-0 z-50 justify-center items-center bg-black bg-opacity-50 {{ $beneficiary_modal ? 'flex' : 'hidden' }}">
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
        <div class="relative z-0 mb-6 w-full group">
            <input type="number" wire:model="phone_number" name="phone_number" id="phone_number"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            />
            <label for="phone_number"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Mobile
                Number</label>
            <i class="fas fa-mobile-retro absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-400"></i>
            @error('phone_number')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>
        <div class="relative z-0 mb-6 w-full group">
            <input type="number" name="amount" wire:model.live="amount" id="amount"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=""  />
            <label for="amount"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Amount(₦)</label>
            @error('amount')
                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
            @enderror
        </div>
        <div class="relative z-0 mb-6 w-full group">
            <select id="network" wire:model.live="network" name="network"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                <option disabled selected>Select Network</option>
                @foreach ($networks as $__network)
                    <option value="{{ $__network->network_id }}">{{ $__network->name }}</option>
                @endforeach
            </select>
            <label for="network"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Select
                Network</label>
        </div>
        {{-- <div class="relative z-0 pt-3 w-full group">
            <!-- <label for="network" class="block mb-2 text-sm text-gray-500 dark:text-gray-400">Select Network</label> -->
            <button type="button" id="dropdownNetwork" data-dropdown-toggle="networkDropdown"
                class="w-full text-left bg-transparent border-0 border-b-2 border-gray-300 text-gray-900 focus:ring-0 focus:border-blue-600 peer">
                <span id="selectedNetwork">Select Network</span>
            </button>
            @error('network')
                <span style="font-size: 15px" class="text-red-500"> {{ $message }} </span>
            @enderror
            <div id="networkDropdown" class="hidden z-10 w-full bg-white rounded-lg shadow-lg">
                <ul class="py-2 text-sm text-gray-700">
                    @foreach ($networks as $__network)
                    <li class="px-4 py-2 flex items-center cursor-pointer hover:bg-gray-100" wire:model.live="network" onclick="selectNetwork('{{ $__network->name }}', '{{ Str::lower($__network->name) }}.png')">
                        <img src="https://via.placeholder.com/24x24" alt="{{ $__network->name }} Logo" class="mr-3 w-6 h-6"> {{ $__network->name }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div> --}}
        @if ($network && $networks->where('network_id', $network)->first()?->airtime_discount > 0)
        <div class="text-red-500 font-semibold pb-7 mt-3">
            Amount to Pay (₦{{ $calculatedDiscount }}) {{ $network ? $networks->where('network_id', $network)->first()?->airtime_discount . '% Discount' : '' }}
        </div>
        @endif
        <button type="submit" wire:loading.attr="disabled" wire:target='validateForm' wire:target='airtime'
            class="w-full bg-vastel_blue text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
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
            <h6>₦{{ number_format($amount, 2) }}</h6>
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

        observer.observe(document.body, { childList: true, subtree: true });

        window.onclick = function(event) {
            // console.log(event.target);
            if (event.target == modal) {
                hideModalBackdrop();
                @this.call('beneficiary_action')
            }
        }
    </script>
    <script>
        function selectNetwork(networkName, logoSrc) {
            document.getElementById('selectedNetwork').innerHTML =
                `<img src="${logoSrc}" alt="${networkName} Logo" class="inline-block mr-3 w-6 h-6"> ${networkName}`;
            document.getElementById('networkDropdown').classList.add('hidden');
        }
    </script>
@endpush
