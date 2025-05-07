<!-- View Plan Modal Background Overlay -->
<div id="viewPlanModal"
    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50 hidden">
    <!-- Modal Content -->
    <div class="bg-white p-8 rounded-2xl shadow-lg w-[90%] max-w-lg">
        <div class="flex justify-center items-center my-2">
            <h2 class="text-[#000000] font-semibold text-xl">View Your Plan</h2>
        </div>

        <div class="mx-1 text-[] mt-3">
            <label for="" class="block">
                <h4 class="text-sm text-[#000000] font-semibold">Amount</h4>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-lg">₦</span>
                    <input type="text" id="viewAmount" readonly
                        class="pl-7 w-full border border-l-0 border-r-0 border-t-0 border-[#B0B0B080] focus:outline-none bg-gray-50">
                </div>
            </label>

            <label for="saving-frequency" class="mt-2 block">
                <div class="flex gap-2">
                    <h4 class="mb-1 font-semibold text-sm text-[#333333]">Plan Name</h4>
                </div>
                <input type="text" id="viewPlanName" readonly
                    class="w-full border border-[#B0B0B080] focus:outline-none bg-gray-50">
            </label>

            <label for="saving-frequency" class="mt-2 block">
                <h4 class="mb-1 font-semibold text-sm text-[#333333]">Maturity Date</h4>
                <input type="text" id="viewMaturityDate" readonly
                    class="w-full border border-[#B0B0B080] focus:outline-none bg-gray-50">
            </label>

            <div class="mt-4">
                <h4 class="mb-1 font-semibold text-sm text-[#333333]">Interest Rate</h4>
                <p class="text-[#0018A8] font-semibold">30% p.a</p>
            </div>

            <div class="mt-4">
                <h4 class="mb-1 font-semibold text-sm text-[#333333]">Interest Earned at Maturity</h4>
                <p id="viewInterestEarned" class="text-[#0018A8] font-semibold">₦0.00</p>
            </div>

            <div class="mt-4">
                <h4 class="mb-1 font-semibold text-sm text-[#333333]">Total Amount at Maturity</h4>
                <p id="viewTotalAmount" class="text-[#0018A8] font-semibold">₦0.00</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between pt-8 pb-4">
            <button onclick="goBackToCreateModal()"
                class="text-[#0018A8] border border-[#0018A8] px-6 py-1 rounded-lg">Edit Plan</button>
            <button onclick="confirmPlan()" class="text-white bg-[#0018A8] px-6 py-1 rounded-lg">Confirm Plan</button>
        </div>
    </div>
</div>
