<div>
    @if ($check_bvn_exists)
    <div>
        <h2 class="text-lg mb-4">
            Your KYC has been updated, and your {{ (!empty($bvn) ? 'BVN' : 'NIN') }} is now <span class="font-semibold">{{ $bvn ?? $nin }}</span>
        </h2>
    </div>
    @endif
    @if (!$check_bvn_exists)
    <form wire:submit.prevent="verifyBvn">
      
        <div class="mb-4">
            <label for="bvn" class="block text-sm font-medium text-gray-700">BVN Number</label>
            <input type="text" id="bvn" placeholder="Enter your BVN" wire:model="bvn" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" @if ($check_bvn_exists) readonly @endif>
            @error('bvn')<div class="text-red-500 font-bold">{{$message}}</div>@enderror
        </div>
     
       
        <div class="mb-4">
            <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
            <small>Note: Please provide the account number linked to the specified BVN.</small>
            <input type="text" id="account_number" placeholder="Enter your Account No." wire:model="account_number" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('account_number')<div class="text-red-500 font-bold">{{$message}}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="nin" class="block text-sm font-medium text-gray-700">Bank</label>
            <select name="" id="" wire:model='bank' class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value=""></option>
                @foreach ($banks as $__bank)
                    <option value="{{ $__bank->code }}">{{ $__bank->name }}</option>
                @endforeach
            </select>
            @error('bank')<div class="text-red-500 font-bold">{{$message}}</div>@enderror
        </div>
        
        <!-- Submit Button -->
        <div class="mt-6">
            <button type="submit" class="w-[8rem] bg-vastel_blue text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span wire:loading.remove wire:target="verifyBvn">Verify</span>
                <span wire:loading wire:target="verifyBvn">
                    <i class="fa fa-circle-notch fa-spin text-sm"></i>
                </span>
            </button>
        </div>
 
    </form>
    <div class="mt-5 "></div>
   
    <hr>
    <h4 class="font-extrabold text-center mt-4">
        OR
    </h4>
    
   <div class="pb-6 mb-6">
        <form wire:submit.prevent="verifyNin">

            <div class="mb-4">
                <label for="nin" class="block text-sm font-medium text-gray-700">NIN</label>
                <input type="text" id="nin" placeholder="Enter your NIN" wire:model="nin" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" @if ($check_bvn_exists) readonly @endif>
                @error('nin')<div class="text-red-500 font-bold">{{$message}}</div>@enderror
            </div>

            <div class="mb-4">
                <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" id="dob" wire:model="dob" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" @if ($check_bvn_exists) readonly @endif>
                @error('dob')<div class="text-red-500 font-bold">{{$message}}</div>@enderror
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-[8rem] bg-vastel_blue text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span wire:loading.remove wire:target="verifyNin">Verify</span>
                    <span wire:loading wire:target="verifyNin">
                        <i class="fa fa-circle-notch fa-spin text-sm"></i>
                    </span>
                </button>
            </div>
        </form>
   </div>

   @endif
</div>