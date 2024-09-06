@extends('layouts.new-dashboard')

@section('body')

<div class="max-w-lg w-full bg-white p-8 ">
        <div class="flex items-center mb-6">
            <a href="#" class="text-blue-600">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <h2 class="text-2xl font-bold mb-4">Airtime Purchase</h2>
        
        <form>
        <button type="button" data-modal-target="beneficiaryModal" data-modal-toggle="beneficiaryModal" class="w-full bg-gray-100 text-gray-900 border border-gray-300 py-2 rounded-lg mb-4">
                Select Beneficiary
            </button>
            <div class="relative z-0 mb-6 w-full group">
                <input type="text" name="mobile-number" id="mobile-number" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="mobile-number" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Mobile Number</label>
                <i class="fas fa-user absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <div class="relative z-0 mb-6 w-full group">
                <input type="number" name="amount" id="amount" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="amount" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Amount (₦)</label>
            </div>
            {{--<div class="relative z-0 mb-6 w-full group">
                <select id="network" name="network" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                    <option disabled selected>Select Network</option>
                    <option>MTN</option>
                    <option>Airtel</option>
                    <option>Glo</option>
                    <option>9mobile</option>
                </select>
                <label for="network" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Select Network</label>
            </div>--}}
            <div class="relative z-0 mb-6 w-full group">
                <!-- <label for="network" class="block mb-2 text-sm text-gray-500 dark:text-gray-400">Select Network</label> -->
                <button id="dropdownNetwork" data-dropdown-toggle="networkDropdown" class="w-full text-left bg-transparent border-0 border-b-2 border-gray-300 text-gray-900 focus:ring-0 focus:border-blue-600 peer">
                    <span id="selectedNetwork">Select Network</span>
                </button>
                <div id="networkDropdown" class="hidden z-10 w-full bg-white rounded-lg shadow-lg">
                    <ul class="py-2 text-sm text-gray-700">
                        <li class="px-4 py-2 flex items-center cursor-pointer hover:bg-gray-100" onclick="selectNetwork('MTN', 'mtn.png')">
                            <img src="https://via.placeholder.com/24x24" alt="MTN Logo" class="mr-3 w-6 h-6"> MTN
                        </li>
                        <li class="px-4 py-2 flex items-center cursor-pointer hover:bg-gray-100" onclick="selectNetwork('Airtel', 'airtel.png')">
                            <img src="https://via.placeholder.com/24x24" alt="Airtel Logo" class="mr-3 w-6 h-6"> Airtel
                        </li>
                        <li class="px-4 py-2 flex items-center cursor-pointer hover:bg-gray-100" onclick="selectNetwork('Glo', 'glo.png')">
                            <img src="https://via.placeholder.com/24x24" alt="Glo Logo" class="mr-3 w-6 h-6"> Glo
                        </li>
                        <li class="px-4 py-2 flex items-center cursor-pointer hover:bg-gray-100" onclick="selectNetwork('9mobile', '9mobile.png')">
                            <img src="https://via.placeholder.com/24x24" alt="9mobile Logo" class="mr-3 w-6 h-6"> 9Mobile
                        </li>
                    </ul>
                </div>
            </div>
            <button type="submit" class="w-full bg-vastel_blue text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:bg-blue-700">Proceed</button>
        </form>
    </div>

    
  <!-- Main modal -->
   <!-- Modal -->
   <div id="beneficiaryModal" tabindex="-1" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg w-full max-w-sm p-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg font-medium text-gray-900">Select Beneficiary</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-modal-hide="beneficiaryModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="py-4">
                <!-- Beneficiary List -->
                <ul class="space-y-2">
                    <li class="bg-gray-100 py-2 px-4 rounded cursor-pointer hover:bg-gray-200">08123456789</li>
                    <li class="bg-gray-100 py-2 px-4 rounded cursor-pointer hover:bg-gray-200">08123456789</li>
                    <li class="bg-gray-100 py-2 px-4 rounded cursor-pointer hover:bg-gray-200">08123456789</li>
                </ul>
                
            </div>
        </div>
    </div>



    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.5.0/flowbite.min.js"></script> -->

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.5.0/flowbite.min.js"></script> -->
    <script>
        function selectNetwork(networkName, logoSrc) {
            document.getElementById('selectedNetwork').innerHTML = `<img src="${logoSrc}" alt="${networkName} Logo" class="inline-block mr-3 w-6 h-6"> ${networkName}`;
            document.getElementById('networkDropdown').classList.add('hidden');
        }
    </script>

<script>
        // Custom close modal functionality
        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', function() {
                const modal = document.getElementById('beneficiaryModal');
                modal.classList.add('hidden');
            });
        });

        document.addEventListener('click', function(event) {
            const modal = document.getElementById('beneficiaryModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>
@endsection 