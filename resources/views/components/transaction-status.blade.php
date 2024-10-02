

<div id="transaction_modal" class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full justify-center items-center bg-black bg-opacity-50 {{ $modal ? 'flex' : 'hidden' }}">
    <div class="relative w-full max-w-md max-h-full bg-white dark:bg-gray-700 rounded-lg shadow">
        <button wire:click="closeModal" type="button" class="close-modal absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-500 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="failedModal">
            <i class="fa-solid fa-times w-5 h-5"></i>
        </button>
        <div class="px-6 py-6 lg:px-8 text-gray-900">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-{{ $status ? 'green' : 'red' }}-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-{{ $status ? 'thumbs-up' : 'times' }} text-{{ $status ? 'green' : 'red' }}-500 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-1 text-gray-900">Transaction {{ $status ? 'Successful' : 'Failed' }}</h3>
                <p class="text-gray-900 text-sm mb-4">{{ $utility }} Purchased {{ $status ? 'Successfully' : 'Failed' }}</p>
                @if (!empty($link))
                <a href="{{ $link }}" class="bg-vastel_blue text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                   View Transaction
                </a>
                @endif
            </div>
        </div>
    </div>
</div>


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