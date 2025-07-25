<div id="transaction_modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full justify-center items-center {{ $modal ? 'flex' : 'hidden' }}">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button wire:click="closeModal" type="button" class="close-modal absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-500 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="failedModal">
                <i class="fa-solid fa-times w-5 h-5"></i>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-{{ $status ? 'green' : 'red' }}-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-{{ $status ? 'thumbs-up' : 'times' }} text-{{ $status ? 'green' : 'red' }}-500 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-white">Transaction {{ $status ? 'Successful' : 'Failed' }}</h3>
                    <p class="text-gray-900 dark:text-white text-sm mb-4">{{ $utility }} {{ (isset($action) && $action) ? 'Scheduled' : 'Purchased' }} {{ $status ? 'Successfully' : 'Failed' }}</p>
                    @if (!empty($link))
                    <div class="flex justify-center items-center gap-5">
                        <a href="{{ $link }}" class="bg-vastel_blue text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                        View Transaction
                        </a>
                        @php $apiLink = str_replace('transactions', 'api-response', $link); @endphp

                        <a href="{{$apiLink}}" class="bg-vastel_blue text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                        View API Response
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@if ($modal)
<div class="bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40"></div>
@endif
@push('scripts')
    <script>
        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', function() {
                const modal = document.getElementById('transaction_modal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            });
        });
    </script>
@endpush
