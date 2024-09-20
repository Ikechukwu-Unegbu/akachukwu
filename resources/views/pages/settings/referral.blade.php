@extends('layouts.new-guest')
@section('body')
    <!-- Back Button -->
    <a href="{{ route('settings.index') }}" class="text-vastel_blue p-6 flex items-center mb-0 pb-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Back
    </a>

    <!-- Referral & Bonus Section -->

    <div class="bg-white p-6  w-[70%]">
        <h2 class="text-xl font-semibold mb-4">Referral & Bonus</h2>
        <div class="flex justify-between items-center mb-6">
            <!-- Total Earned -->
            <div class="flex flex-col items-center space-x-2">
                <i class="fas fa-money-bill-wave text-4xl text-vastel_blue"></i>
                <div class="flex flex-row gap-5 font-semibold text-vastel_blue">
                    <p class="text-sm text-vastel_blue">Total Earned:</p>
                    <p class="text-sm ">₦ {{ number_format(auth()->user()->getReferredUsersWithEarnings()->sum('referrerEarning'), 2) }}</p>
                </div>
            </div>
            <!-- Total Invited -->
            <div class="flex flex-col items-center space-x-2">
                <i class="fas fa-users text-4xl text-vastel_blue"></i>
                <div class="flex flex-row font-semibold text-vastel_blue">
                    <p class="text-sm text-vastel_blue">Total Invited:</p>
                    <p class="text-sm ">{{ auth()->user()->getReferredUsersWithEarnings()->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Share Link and Earn Button -->
        <div class="flex flex-col items-start">
            <button class="bg-vastel_blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all duration-300"
                data-modal-target="shareModal" data-modal-toggle="shareModal">
                Share Link And Earn
            </button>

            <p class="text-sm text-gray-500 mt-2">Share your invitation link with your friends to earn</p>
        </div>
    </div>

    <!-- Referral History Section -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Referral History</h2>
            <button class="bg-vastel_blue text-white py-2 px-4 rounded-lg" data-modal-target="transferModal"
                data-modal-toggle="transferModal">Withdraw Bonus</button>
        </div>

        <!-- Referral History List -->
        <div class="space-y-4">
            <!-- Repeat this block for each referral -->
            @forelse (auth()->user()->getReferredUsersWithEarnings() as $referral)
                <div class="bg-gray-100 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-bold">{{ $referral['user']->name }}</div>
                            <div class="text-gray-500">Phone No: {{ $referral['user']->mobile ?? 'N/A' }}</div>
                            <div class="text-gray-500 text-sm">{{ $referral['user']->created_at->format('d M, Y') }}
                            </div>
                        </div>
                        <div class="text-vastel_blue font-bold">₦{{ $referral['referrerEarning'] }}</div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-100 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-bold">No Referrals Found!</div>
                        </div>
                    </div>
                </div>
            @endforelse
            <!-- End of block -->
        </div>
    </div>

    <!-- sharing to social modal -->
    <!-- Modal -->
    <div id="shareModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-md md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold">Share</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="shareModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-4">
                    <div class="flex justify-around">
                        <!-- Social buttons -->
                        <div class="flex flex-col items-center">
                            <i class="fab fa-facebook text-vastel_blue text-4xl"></i>
                            <span class="text-sm mt-2">Facebook</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <i class="fab fa-instagram text-pink-600 text-4xl"></i>
                            <span class="text-sm mt-2">Instagram</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <i class="fab fa-whatsapp text-green-600 text-4xl"></i>
                            <span class="text-sm mt-2">WhatsApp</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <i class="fas fa-times text-gray-600 text-4xl"></i>
                            <span class="text-sm mt-2">X</span>
                        </div>
                    </div>
                    <!-- Or copy link section -->
                    <p class="text-center">Or copy link</p>
                    <div class="flex items-center justify-between bg-gray-100 p-2 rounded-lg">
                        <input id="shareLink" type="text" value="https://www.yourlink.com/share"
                            class="bg-gray-100 outline-none text-vastel_blue w-full border-none">
                        <button class="ml-2 text-vastel_blue" onclick="copyToClipboard()">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end of share to social modal -->

    <!-- withdrawal modal -->
    <!-- Modal -->
    <div id="transferModal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full h-full bg-black bg-opacity-30 items-center justify-center">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex justify-between items-center p-5 rounded-t">
                    <h3 class="text-xl font-medium text-gray-900">
                        Confirm Transfer
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="transferModal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 9.293a1 1 0 011.414 0L10 13.586l4.293-4.293a1 1 0 011.414 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="p-6 space-y-6 text-center">
                    <p>You are about to transfer <strong>₦
                            {{ number_format(auth()->user()->referrerEarning(auth()->id()), 2) }}</strong> to your wallet
                        balance.</p>
                </div>

                <!-- Modal footer -->
                <div class="flex justify-between p-6">
                    <button data-modal-toggle="transferModal"
                        class="text-vastel_blue bg-white border border-vastel_blue focus:ring-4 focus:outline-none focus:ring-vastel_blue rounded-lg text-sm px-5 py-2.5 hover:text-gray-900 hover:bg-gray-100">
                        Cancel
                    </button>
                    <button
                        class="text-white bg-vastel_blue focus:ring-4 focus:outline-none focus:ring-blue-600 font-medium rounded-lg text-sm px-5 py-2.5">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- end of withdrawal modal -->

    <script>
        // Function to copy link to clipboard
        function copyToClipboard() {
            var copyText = document.getElementById("shareLink");
            copyText.select();
            document.execCommand("copy");
            alert("Link copied to clipboard: " + copyText.value);
        }
    </script>
@endsection
