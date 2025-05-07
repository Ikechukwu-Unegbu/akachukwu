<!-- Create Plan Modal -->
<div id="createPlanModal"
    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-[90%] max-w-lg">
        <div class="flex justify-center items-center my-2">
            <h2 class="text-[#000000] font-semibold text-xl">Create Your Plan</h2>
        </div>
        <div class="mx-1 mt-3">
            <label for="" class="block">
                <h4 class="text-sm text-[#000000] font-semibold">Amount</h4>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-lg">₦</span>
                    <input type="text" placeholder="100~99,000"
                        class="pl-7 w-full border border-l-0 border-r-0 border-t-0 border-[#B0B0B080] focus:outline-none">
                </div>
            </label>
            <label for="plan-name" class="mt-2 block">
                <div class="flex gap-2">
                    <h4 class="mb-1 font-semibold text-sm text-[#333333]">Give this plan a name</h4>
                    <span class="text-[#545454]">(Optional)</span>
                </div>
                <input type="text" id="plan-name" placeholder="Please enter"
                    class="w-full border border-[#B0B0B080] focus:outline-none">
            </label>
            <label for="maturity-date" class="mt-2 block">
                <h4 class="mb-1 font-semibold text-sm text-[#333333]">Maturity Date</h4>
                <input type="date" id="maturity-date" placeholder="Please enter"
                    class="w-full border border-[#B0B0B080] focus:outline-none">
            </label>
        </div>
        <!-- Next Button -->
        <div class="flex justify-center pt-4 pb-20">
            <button id="nextToViewPlan" class="text-white bg-[#0018A8] px-28 py-1 rounded-lg">Next</button>
        </div>
    </div>
</div>

<!-- View Plan Modal -->
<div id="viewPlanModal"
    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50 hidden">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-[90%] max-w-lg">
        <div class="flex justify-center items-center my-2">
            <h2 class="text-[#000000] font-semibold text-xl">Create Your Plan</h2>
        </div>
        <div class="mx-1 mt-3">
            <label for="" class="block">
                <h4 class="text-sm text-[#000000] font-semibold">Amount</h4>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-lg">₦</span>
                    <input type="text" value="1,000"
                        class="pl-7 w-full border border-l-0 border-r-0 border-t-0 border-[#B0B0B080] focus:outline-none bg-gray-50"
                        readonly>
                </div>
            </label>
            <label for="plan-name-view" class="mt-2 block">
                <div class="flex gap-2">
                    <h4 class="mb-1 font-semibold text-sm text-[#333333]">Give this plan a name</h4>
                    <span class="text-[#545454]">(Optional)</span>
                </div>
                <input type="text" id="plan-name-view" value="House Rent"
                    class="w-full border border-[#B0B0B080] focus:outline-none bg-gray-50 text-[#333333]" readonly>
            </label>
            <label for="maturity-date" class="mt-2 block">
                <h4 class="mb-1 font-semibold text-sm text-[#333333]">Maturity Date</h4>
                <input type="text" id="maturity-date" placeholder="100 Days - 11/Dec. 2025"
                    class="w-full border border-[#B0B0B080] focus:outline-none">
            </label>
        </div>

        <div
            class="flex my-6 flex-col justify-center items-center py-6 bg-[#B0B7E41A] border border-[#0018A84D] rounded-xl text-[#333333]">
            <p>You will earn an interest rate 18% p.a.</p>
            <p class="mt-2">You will receive 1,180 at maturity - 100 Days</p>
        </div>

        <!-- Close Button -->
        <div class="flex justify-center pt-4 pb-20">
            <button id="closeViewPlan" class="text-white bg-[#0018A8] px-28 py-2 rounded-lg">Next</button>
        </div>
    </div>
</div>

<!-- JavaScript to handle modal transitions -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const createPlanModal = document.getElementById('createPlanModal');
        const viewPlanModal = document.getElementById('viewPlanModal');
        const nextButton = document.getElementById('nextToViewPlan');
        const closeButton = document.getElementById('closeViewPlan');

        // Function to handle moving from create to view modal
        nextButton.addEventListener('click', function() {
            // Hide the create modal
            createPlanModal.classList.add('hidden');

            // Get values from create form (if needed)
            // const amount = document.getElementById('amount').value;
            // const planName = document.getElementById('plan-name').value;
            // const maturityDate = document.getElementById('maturity-date').value;

            // Show the view modal
            viewPlanModal.classList.remove('hidden');
        });

        // Function to close the view modal
        closeButton.addEventListener('click', function() {
            viewPlanModal.classList.add('hidden');
        });
    });
</script>
