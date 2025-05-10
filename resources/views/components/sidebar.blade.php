<div class="sidebar w-64 bg-white shadow-md min-h-screen">
    <div class="py-4">
        <div class="px-4 mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Savings</h2>
        </div>
        
        <nav class="mt-2">
            <a href="{{ route('savings.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('savings.index') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                <span class="ml-2">Overview</span>
            </a>
            
            <a href="{{ route('savings.vassave') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('savings.vassave') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                <span class="ml-2">VasSave</span>
            </a>
            
            <a href="{{ route('savings.autosave') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('savings.autosave') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                <span class="ml-2">AutoSave</span>
            </a>
            
            <a href="{{ route('savings.vasfixed') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('savings.vasfixed*') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                <span class="ml-2">VasFixed</span>
            </a>
            
            <a href="{{ route('savings.vastarget') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('savings.vastarget*') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                <span class="ml-2">VasTarget</span>
            </a>
            
            <a href="{{ route('savings.withdraw') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('savings.withdraw') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                <span class="ml-2">Withdraw</span>
            </a>
            
            <a href="{{ route('savings.interest') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('savings.interest') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                <span class="ml-2">Interest Breakdown</span>
            </a>
            
            <a href="{{ route('savings.settings') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('savings.settings') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                <span class="ml-2">Settings</span>
            </a>
        </nav>
    </div>
</div>