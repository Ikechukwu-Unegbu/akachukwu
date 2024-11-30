<div>

    <button style="display: none;" id="modal_button" data-modal-target="feature-modal" data-modal-toggle="feature-modal"
        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button">
        Toggle modal
    </button>
    <div id="feature-modal" tabindex="-1" aria-hidden="true"
        class="hidden fixed top-0 left-0 right-0 z-50 justify-center items-center w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex justify-between items-start p-4  rounded-t ">
                    <!-- <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            <i class="fas fa-party-horn text-vastel_blue"></i> New Features Now Available!
            </h3> -->
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="feature-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-2">
                    @if (!$hasCompletedKyc)
                        <div class="flex justify-center items-center">
                            <img class="h-[2.5rem] w-[2.5rem]" src="{{ asset('images/info.svg') }}" alt="KYC Info">
                            <h3 class="text-xl font-semibold text-vastel_blue">
                                Complete Your KYC!
                            </h3>
                        </div>
                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                            To continue enjoying our services, please complete your KYC by providing your BVN or NIN.
                        </p>
                        <div class="flex justify-center items-center">
                            <a href="{{ route('settings.kyc') }}" 
                            class="text-white bg-vastel_blue hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Complete KYC Now
                            </a>
                        </div>
                    @endif
                    @if ($hasCompletedKyc)
                    @foreach ($announcements as $update)
                        <div class="flex justify-center items-center">
                            @if ($update->type == 'warning')
                                <img class="h-[2.5rem] w-[2.5rem]" src="{{ asset('images/warning.svg') }}"
                                    alt="">
                            @elseif($update->type == 'success')
                                <img class="h-[2.5rem] w-[2.5rem]" src="{{ asset('images/success.svg') }}"
                                    alt="">
                            @elseif($update->type == 'info')
                                <img class="h-[2.5rem] w-[2.5rem]" src="{{ asset('images/info.svg') }}" alt="">
                            @endif
                            <h3 class="text-xl font-semibold text-vastel_blue ">


                                {{ $update->title }}!
                            </h3>
                        </div>
                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                            {!! $update->message !!}
                        </p>
                        @if ($update->link)
                        <div class="flex justify-center items-center">
                            <a target="_blank" href="{{ $update->link }}" class="text-white bg-vastel_blue hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Learn
                                More</a>
                        </div>
                        @endif
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        <input type="hidden" id="has-announcements" value="{{ $hasAnnouncements ? 'true' : 'false' }}">
        <input type="hidden" id="has-kyc" value="{{ $hasCompletedKyc ? 'true' : 'false' }}">

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalElement = document.getElementById('feature-modal');
            const hasAnnouncements = document.getElementById('has-announcements').value === 'true';
            const hasKyc = document.getElementById('has-kyc').value === 'true';
            const closeButton = modalElement.querySelector('[data-modal-hide="feature-modal"]');



            // Initialize Flowbite modal instance
            const modal = new Modal(modalElement);

            // Show the modal on page load
            if (hasAnnouncements) {
                modal.show();
            }
            if (!hasKyc) {
                modal.show();
            }

            // Set an interval to show the modal every 10 minutes
            setInterval(() => {
                // modal.show();
                 // Show the modal on page load
                if (hasAnnouncements) {
                    modal.show();
                }
                if (!hasKyc) {
                    modal.show();
                }
            }, 30000); // 10 minutes  600000

            closeButton.addEventListener('click', () => {
                modal.hide(); // Close the modal and hide the backdrop
            });
        });
    </script>






</div>
