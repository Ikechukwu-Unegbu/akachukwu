@extends('layouts.new-guest')

@section('body')
   


<div class="bg-white p-6 w-full md:w-[60%] ">
    <a href="#" class="text-vastel_blue mb-4 block">&lt; Back</a>
  

  
        <!-- Personal Information Section -->
        <div>
            <h2 class="text-lg font-semibold mb-4">Personal Information</h2>

            <!-- First Name and Last Name -->
          
            <!-- First Name -->
            <div class="mb-4">
                <label for="first-name" class="block text-sm font-medium text-gray-700">First name</label>
                <input type="text" id="first-name" placeholder="First name" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                <input type="text" id="last-name" placeholder="Last name" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
          

            <!-- Gender -->
            <div class="mb-4">
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select id="gender" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option selected>Select Gender</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select>
            </div>

            <!-- Date of Birth -->
            <div class="mb-4">
                <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" id="dob" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>

        <!-- BVN/NIN Section -->
        <div class="mt-8">
            <h2 class="text-lg font-semibold mb-4">BVN/NIN</h2>

            <!-- BVN Number -->
            <div class="mb-4">
                <label for="bvn" class="block text-sm font-medium text-gray-700">BVN Number</label>
                <input type="text" id="bvn" placeholder="BVN" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- NIN Number -->
            <div class="mb-4">
                <label for="nin" class="block text-sm font-medium text-gray-700">National Identification Number</label>
                <input type="text" id="nin" placeholder="NIN" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>


           <!-- Address Section -->
    <div>
        <h2 class="text-lg font-semibold mb-4">Address</h2>

        <!-- State -->
        <div class="mb-4">
            <label for="state" class="block text-sm font-medium text-gray-700">State</label>
            <select id="state" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option>Select state</option>
                <option>Lagos</option>
                <option>Abuja</option>
                <!-- Add other states here -->
            </select>
        </div>

        <!-- Local Government -->
        <div class="mb-4">
            <label for="local-government" class="block text-sm font-medium text-gray-700">Local government</label>
            <select id="local-government" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option>Select local government</option>
                <!-- Add local governments here -->
            </select>
        </div>

        <!-- Address -->
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
            <input type="text" id="address" placeholder="Enter your detailed address" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <!-- House Number -->
        <div class="mb-4">
            <label for="house-number" class="block text-sm font-medium text-gray-700">House number</label>
            <input type="text" id="house-number" placeholder="House number" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <!-- Utility Bill Type -->
        <div class="mb-4">
            <label for="utility-bill-type" class="block text-sm font-medium text-gray-700">Utility bill type</label>
            <select id="utility-bill-type" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option>Electricity Bill</option>
                <option>Water Bill</option>
                <option>Gas Bill</option>
                <!-- Add other bill types -->
            </select>
        </div>

        <!-- Upload Utility Bill -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Upload valid utility bill (not later than 3 months ago)</label>
            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H20v4h8V8zM28 4H20a4 4 0 00-4 4v4a4 4 0 004 4h8a4 4 0 004-4V8a4 4 0 00-4-4zm0 0v2m0 0v6m8 16a4 4 0 01-4 4H16a4 4 0 01-4-4v-6a4 4 0 014-4h16a4 4 0 014 4v6zm-8 0h-4m4-4v2m0-2h-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <div class="text-sm text-gray-600">
                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                            <span>Click to upload</span>
                            <input id="file-upload" name="file-upload" type="file" class="sr-only">
                        </label>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF (Max 2MB)</p>
                </div>
            </div>
        </div>

        <!-- File Upload Progress (Simulated) -->
        <div class="mb-4">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-900">Uploaded file</span>
                <span class="text-sm text-gray-500">200 KB</span>
            </div>
            <div class="relative pt-1">
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                    <div style="width:100%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <button type="submit" class="w-[8rem] bg-vastel_blue text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit
            </button>
        </div>
    </div>

  </div>

  <script>
  const imageUpload = document.getElementById('image-upload');
  const profileImage = document.getElementById('profile-image');

  imageUpload.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      
      reader.onload = function(e) {
        profileImage.src = e.target.result;
      };
      
      reader.readAsDataURL(file);
    }
  });
</script>
@endsection 