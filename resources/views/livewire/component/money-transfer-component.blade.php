<div wire:ignore.self id="transfer-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex  p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-center text-gray-900 dark:text-white">
                    {{ $transferMethod === 1 ? "Credit Recipient's Account" : ($transferMethod === 2 ? 'Bank Transfer' : 'Transfer') }}
                </h3>
                <button wire:click='handleCloseTransferMoneyModal' style="float: left;" type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="transfer-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            @if (!$transferMethod)
            <div class="p-4 pb-5" x-data="{ open: false, selectedMethod: '' }">
                <!-- Button to toggle dropdown -->
                <button @click="open = !open" type="button"
                    class="w-full text-left bg-transparent dark:text-white border-0 border-b-2 border-gray-300 text-gray-900 focus:ring-0 focus:border-blue-600 pb-3">
                    <span x-text="selectedMethod || 'Select Transfer Method'"></span>
                </button>
            
                <!-- Dropdown menu -->
                <div x-show="open" @click.away="open = false" class="w-full bg-white rounded-lg shadow-lg mt-2">
                    <ul class="py-2 text-sm text-gray-700">
                        @foreach ($transferMethods as $key => $__transferMethod)
                            <li @click="$wire.selectedTransferMethod({{ $key }}); selectedMethod = '{{ $__transferMethod }}'; open = false"
                                class="px-4 py-2 flex items-center cursor-pointer hover:bg-gray-100">
                                {{ $__transferMethod }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>            
            @endif

            @if ($transferMethod === 1)
                <form wire:submit="@if ($recipient) handleMoneyTransfer @else handleVerifyRecipient @endif">
                    <!-- Modal body -->
                    <div class="p-6 space-y-4">
                        <!-- Account Number Input -->
                        @if ($recipient)
                            <div
                                class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $recipient?->profilePicture }}" class="rounded-full w-100 h-12"
                                        alt="{{ $recipient?->name }}" />
                                    <span class="text-blue-600 font-medium">{{ $recipient?->name }} <p>
                                            {{ '@' . $recipient?->username }}</p></span>
                                </div>
                                {{-- <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Noteworthy technology acquisitions 2021</h5>
                            <p class="font-normal text-gray-700 dark:text-gray-400">Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.</p> --}}
                            </div>
                            <div class="mt-2">
                                <label for="" class="dark:text-white mb-3">Enter Amount</label>
                                <input type="number" placeholder="Enter Amount" id="amount_input" wire:model="amount"
                                    class="w-full p-2 border-none rounded-lg shadow focus:ring-vastel_blue focus:border-vastel_blue dark:bg-gray-700 dark:border-white dark:placeholder-white dark:text-white">
                                @error('amount')
                                    <span
                                        class="text-red-500 text-sm font-bold flex justify-center">{{ $message }}</span>
                                @enderror
                            </div>
                        @else
                            <div>
                                <label for="" class="dark:text-white mb-3">Enter Recipient Username or
                                    Email</label>
                                <input type="text" placeholder="Enter Username or Email" wire:model="username"
                                    class="w-full p-2 border-none rounded-lg shadow focus:ring-vastel_blue focus:border-vastel_blue dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                @if ($error_msg)
                                    <span
                                        class="text-red-500 text-sm font-bold flex justify-center">{{ $error_msg }}</span>
                                @endif
                            </div>
                        @endif
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
            @endif

            @if ($transferMethod === 2)
                <form class="px-3" wire:submit="{{ $handleMethodAction['method'] }}">
                    @if ($initiateBankTransfer)
                        <div>
                            <div class="relative z-0 mb-6 w-full group mt-5">
                                <input type="number" id="account_number"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 dark:text-white bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder="Enter 10-digit Account Number" wire:model="account_number" />
                                <label for="account_number"
                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-white duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                </label>
                                @error('account_number')
                                    <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
                                @enderror
                            </div>
                            <div x-data="{ open: false, search: '', selectedBank: {{ Js::from($bankDetails) }} }" class="relative">
                                <button @click="open = !open" type="button"
                                    class="w-full text-left dark:text-white bg-transparent border-0 border-b-2 pb-4 border-gray-300 text-gray-900 focus:ring-0 focus:border-blue-600 peer">
                                    <template x-if="selectedBank">
                                        <div class="flex items-center">
                                            <img :src="selectedBank.image" alt="Logo" class="mr-3 w-7 h-7">
                                            <h4 x-text="selectedBank.name"></h4>
                                        </div>
                                    </template>
                                    <template x-if="!selectedBank">
                                        <div>Select Bank</div>
                                    </template>
                                </button>
                                <div x-show="open" @click.away="open = false"  class="overflow-auto relative z-10 w-full bg-white dark:bg-gray-600 rounded shadow-lg h-[500px]">
                                    <div
                                        class="sticky top-0 bg-white dark:bg-gray-600 px-4 py-2 flex items-center shadow-md dark:shadow-lg text-gray-900 dark:text-white">
                                        <input type="text" x-model="search"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 dark:text-white bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder="Search Bank..." />
                                    </div>
                                    <div class="overflow-auto h-[440px]">
                                        <ul class="py-2 text-sm text-gray-700">
                                            @foreach ($banks as $__bank)
                                                @php 
                                                    $id = $__bank->id;
                                                    $bankName = $__bank->name;
                                                    $image = $__bank->image;
                                                @endphp
                                                <li x-show="search === '' || '{{ $__bank->name }}'.toLowerCase().includes(search.toLowerCase())" class="px-4 py-2 flex items-center cursor-pointer text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800"
                                                    @click="selectedBank={id:{{ $id }}, name:'{{ $bankName }}', image:'{{ $image }}'}; $wire.selectedBank({{ $id }}); open=false"
                                                    >
                                                    <img src="{{ $image }}" alt="{{ $bankName }} Logo"                  
                                                        class="mr-3 w-7 h-7">
                                                    {{ $bankName }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @error('bank')
                                    <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
                                @enderror
                            </div>
                            @if ($account_name)
                                <div>
                                <div class="bg-indigo-50 text-black font-semibold text-center py-2 px-4 rounded-full my-3 transition-all">
                                    {{ $account_name }}
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        
                    @endif

                    @if ($initiateTransferAmount)
                        <div class="bg-indigo-50 text-black font-semibold text-center py-2 px-4 rounded-full my-3">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center">
                                    <img src="{{ $bankDetails->image }}" alt="Profile" class="w-7 h-7 text-gray-400" />
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-black">{{ $account_name }}</p>
                                    <p class="text-gray-500 dark:text-black">
                                        {{ $account_number . ' ' . $bankDetails->name }}
                                    </p>    
                                </div>
                            </div>
                        </div>

                        <div class="relative z-0 mb-6 w-full group mt-10" x-data="{ amount: @entangle('amount').defer }">
                            <input 
                                type="text" 
                                id="amount" 
                                x-model="amount"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 dark:text-white bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="Enter Amount" 
                                wire:model.debounce.500ms="amount" 
                                @input="amount = amount.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1'); if (amount.includes('.')) { let parts = amount.split('.'); if (parts[1] && parts[1].length > 2) { amount = parts[0] + '.' + parts[1].slice(0,2); } }"
                                />
                            <label for="amount"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-white duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            </label>
                            @error('amount')
                                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="relative z-0 mb-6 w-full group mt-10">
                            <input type="text" id="remark"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 dark:text-white bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="Remark (Optional)" wire:model="remark" />
                            <label for="remark"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-white duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            </label>
                            @error('remark')
                                <span class="text-red-500 font-bold text-sm"> {{ $message }} </span>
                            @enderror
                        </div>
                    @endif

                    @if ($initiatePreviewTransaction)
                        <div class="text-center mt-4">
                            <h2 class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                ₦{{ number_format($amount, 2) }}</h2>
                        </div>

                        <div class="mt-4">
                            <div class="grid grid-cols-2 gap-y-4 text-gray-500 dark:text-gray-400">
                                <p>Bank</p>
                                <p class="text-gray-900 dark:text-white">{{ $bankDetails->name }}</p>

                                <p>Account Number</p>
                                <p class="text-gray-900 dark:text-white">{{ $account_number }}</p>

                                <p>Name</p>
                                <p class="text-gray-900 dark:text-white">{{ $account_name }}</p>

                                <p>Amount</p>
                                <p class="text-gray-900 dark:text-white">₦{{ number_format($amount, 2) }}</p>

                                <p>Transaction Fee</p>
                                <p class="text-gray-900 dark:text-white">₦{{ number_format($transactionFee, 2) }}</p>
                            </div>
                        </div>

                        <hr class="my-4 border-gray-200 dark:border-gray-700">

                        <div>
                            <h3 class="text-gray-900 dark:text-white font-semibold mb-2">Payment Method</h3>
                            <div
                                class="flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-800 rounded-lg cursor-pointer">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                        <img src="{{ auth()->user()->profilePicture }}" alt="Payment Icon"
                                            class="w-4 h-4 text-gray-400">
                                    </div>
                                    <p class="text-gray-900 dark:text-white">Total Amount
                                        (₦{{ number_format($amount + $transactionFee, 2) }})
                                    </p>
                                </div>
                                <span class="text-blue-600 dark:text-blue-400">&#10003;</span>
                            </div>
                        </div>
                    @endif

                    @if ($initiateTransactionPin)
                        <div x-data="transferPinForm()">
                            <div class="p-6 space-y-6">
                                <div class="flex justify-center space-x-4">
                                    @foreach (range(1, 4) as $index)
                                        <input type="password" maxlength="1"
                                            class="w-12 h-12 text-2xl text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            x-on:input="handleInput($event, {{ $index }})"
                                            x-ref="pin{{ $index }}"
                                            x-on:keyup.backspace="handleBackspace($event, {{ $index }})"
                                            wire:change="updatePin({{ $index }}, $event.target.value)"
                                            wire:model.defer="pin.{{ $index }}"
                                            :class="{ 'border-blue-500': isComplete }" />
                                    @endforeach
                                </div>
                                @error('pin')
                                    <span
                                        class="text-red-500 text-sm font-bold flex justify-center">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center dark:text-white font-semibold">
                            Enter Transaction Pin
                        </div>
                    @endif

                    @if ($transactionStatusModal)
                    <div class="px-6 py-6 lg:px-8">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-{{ $transactionStatus ? 'green' : 'red' }}-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-{{ $transactionStatus ? 'check-circle' : 'times' }} text-{{ $transactionStatus ? 'green' : 'red' }}-500 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-white">Transaction {{ $transactionStatus ? 'Completed' : 'Failed' }}</h3>
                            <p class="text-sm font-semibold mb-1 text-gray-900 dark:text-white">Transaction {{ $transactionStatus ? 'was Successful' : 'Failed' }}</p>
                            <div class="flex justify-center items-center gap-5 mt-3">
                                <a href="#" class="bg-vastel_blue text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                                    Transactions
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                  
                    <div class="text-center p-6 {{ ($transactionStatusModal) ? 'hidden' : '' }} {{ ($initiateTransactionPin || $transactionStatusModal || $initiatePreviewTransaction || $initiateTransferAmount) ? 'flex items-center space-x-4' : '' }}">
                        <div wire:loading wire:target="handleInitiateTransactionPin" class="dark:text-white">
                            <p ><i class="fa fa-circle-notch fa-spin"></i> Processing...</p>
                        </div>
                        @if ($initiateTransactionPin || $transactionStatusModal || $initiatePreviewTransaction || $initiateTransferAmount)
                        <button 
                            wire:loading.attr="disabled"
                            @click="$wire.handleCloseTransferMoneyModal"
                            type="button"
                            class="w-full text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600  dark:focus:ring-red-800">
                              <i class="fa fa-times-circle"></i>  Cancel
                        </button>   
                        @endif

                        <button wire:loading.attr="disabled"
                            @if ($handleMethodAction['method'] === 'handleInitiateTransactionPin') wire:loading.remove @endif
                            wire:target="{{ $handleMethodAction['method'] }}" type="submit"
                            class="w-full text-white bg-vastel_blue hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-vastel_blue dark:focus:ring-blue-800">
                            <span wire:loading.remove wire:target="{{ $handleMethodAction['method'] }}">
                                {{ $handleMethodAction['action'] }}
                            </span>
                            <span wire:loading wire:target="{{ $handleMethodAction['method'] }}">
                                <i class="fa fa-circle-notch fa-spin text-sm"></i>
                            </span>
                        </button>                        
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.getElementById('amount_input').addEventListener('change', function(event) {
            // Remove any '+' or '-' characters from the input
            event.target.value = event.target.value.replace(/[+-]/g, '');
        });
    </script>
    <script>
        function transferPinForm() {
            return {
                pin: Array(4).fill(''),
                get isComplete() {
                    return this.pin.every(digit => digit.length === 1);
                },
                handleInput(event, index) {
                    const input = event.target;
                    if (input.value.length && this.$refs[`pin${index + 1}`]) {
                        this.$refs[`pin${index + 1}`].focus();
                    }
                    this.pin[index - 1] = input.value;
                    // Sync with Livewire
                    this.$wire.set(`pin.${index}`, input.value);
                },
                handleBackspace(event, index) {
                    if (!event.target.value.length && this.$refs[`pin${index - 1}`]) {
                        this.$refs[`pin${index - 1}`].focus();
                    }
                    this.pin[index - 1] = event.target.value;
                    // Sync with Livewire
                    this.$wire.set(`pin.${index}`, event.target.value);
                }
            }
        }
    </script>
@endpush
