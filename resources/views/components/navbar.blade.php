<div class="navbar bg-white shadow">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-primary">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8">
            </a>
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="relative">
                <button class="flex items-center space-x-2 focus:outline-none">
                    <span class="text-gray-700">{{ auth()->user()->name }}</span>
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="h-8 w-8 rounded-full">
                </button>
                
                <!-- Dropdown menu -->
                <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>