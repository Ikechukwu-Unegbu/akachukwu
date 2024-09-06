@extends('layouts.new-dashboard')

@section('body')



<div class="max-w-lg w-full bg-white  p-6">
        <!-- Back Button -->
        <a href="#" class="flex items-center text-blue-600 mb-6">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>

        <!-- Form Header -->
        <h1 class="text-xl font-semibold mb-4">TV Purchase</h1>

        <!-- Form Start -->
        <form>

            <div class="relative z-50 mb-6 w-full group">
                <!-- Button to show selected package and trigger dropdown -->
                <button id="dropdownPackage" data-dropdown-toggle="packageDropdown" class="w-full text-left bg-transparent border-0 border-b-2 border-gray-300 text-gray-400 focus:ring-0 focus:border-blue-600 peer">
                    <span id="selectedPackage">Select Package</span>
                </button>

                <!-- Dropdown menu for package selection -->
                <div id="packageDropdown" style="z-index:1;" class="hidden z-10 w-full bg-white rounded-lg shadow-lg">
                    <ul class="py-2 text-sm text-gray-700">
                        <li class="px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-gray-100" onclick="selectPackage('Compact', '₦12,500.00')">
                            Compact <span class="text-green-600">₦12,500.00</span>
                        </li>
                        <li class="px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-gray-100" onclick="selectPackage('Compact Plus', '₦19,800.00')">
                            Compact Plus <span class="text-green-600">₦19,800.00</span>
                        </li>
                        <li class="px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-gray-100" onclick="selectPackage('Padi', '₦2,950.00')">
                            Padi <span class="text-green-600">₦2,950.00</span>
                        </li>
                        <li class="px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-gray-100" onclick="selectPackage('Premium Asia', '₦25,500.00')">
                            Premium Asia <span class="text-green-600">₦25,500.00</span>
                        </li>
                        <li class="px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-gray-100" onclick="selectPackage('Yanga', '₦4,200.00')">
                            Yanga <span class="text-green-600">₦4,200.00</span>
                        </li>
                        <li class="px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-gray-100" onclick="selectPackage('Premium French', '₦45,600.00')">
                            Premium French <span class="text-green-600">₦45,600.00</span>
                        </li>
                        <li class="px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-gray-100" onclick="selectPackage('Confam', '₦7,400.00')">
                            Confam <span class="text-green-600">₦7,400.00</span>
                        </li>
                        <li class="px-4 py-2 flex items-center justify-between cursor-pointer hover:bg-gray-100" onclick="selectPackage('Asia', '₦9,900.00')">
                            Asia <span class="text-green-600">₦9,900.00</span>
                        </li>
                    </ul>
                </div>
            </div>




            <!-- Smart Card Number -->
            <div class="relative z-0 mb-6 w-full group">
                <input type="text" id="smartCardNumber" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                <label for="smartCardNumber" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Enter Smart Card Number</label>
            </div>

            <!-- Amount -->
            <div class="relative z-0 mb-6 w-full group">
                <input type="text" id="amount" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                <label for="amount" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Amount</label>
            </div>

            <!-- Proceed Button -->
            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">Proceed</button>
        </form>
    </div>



    <script>
    // Toggle the dropdown visibility
    const dropdownButton = document.getElementById('dropdownPackage');
    const dropdownMenu = document.getElementById('packageDropdown');
    const selectedPackageText = document.getElementById('selectedPackage');

    dropdownButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('hidden');
    });

    // Function to select package and display the selected value
    function selectPackage(packageName, packagePrice) {
        selectedPackageText.innerHTML = `${packageName} - ${packagePrice}`;
        dropdownMenu.classList.add('hidden');
    }

    // Close the dropdown if clicked outside
    document.addEventListener('click', function (event) {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
</script>
@endsection 