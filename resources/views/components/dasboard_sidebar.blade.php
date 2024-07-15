<div class="sidebar_container ">
    <div class="sidebar-list">
        <div class="sidebar-item {{ Route::currentRouteName() == 'dashboard' ? 'vastel-bg' : '' }}">
            <a class="sidebar_link fs-2 " href="{{ route('dashboard') }}">
                <span class="link_key">
                    <i class="fa-solid fa-gauge"></i>
                </span>
                <span class="link_val">Dashboard</span>
            </a>
        </div>
          <div class="sidebar-item {{ Route::currentRouteName() == 'airtime.index' ? 'vastel-bg' : '' }}">
            <a class="sidebar_link fs-2" href="{{route('airtime.index')}}">
                <span class="link_key">
                    <i class="fa-solid fa-mobile-retro"></i>
                </span>
                <span class="link_val">Buy Airtime</span>
            </a>
        </div>
        <div class="sidebar-item {{ Route::currentRouteName() == 'data.index' ? 'vastel-bg' : '' }}">
            <a class="sidebar_link fs-2" href="{{route('data.index')}}">
                <span class="link_key">
                    <i class="fa-solid fa-wifi"></i>
                </span>
                <span class="link_val">Buy Data</span>
            </a>
        </div>
             <div class="sidebar-item {{ Route::currentRouteName() == 'electricity.index' ? 'vastel-bg' : '' }}">
            <a class="sidebar_link fs-2" href="{{route('electricity.index')}}">
                <span class="link_key">
                   <i class="fa-solid fa-lightbulb"></i>
                </span>
                <span class="link_val">Electricity</span>
            </a>
        </div>
        <div class="sidebar-item {{ Route::currentRouteName() == 'cable.index' ? 'vastel-bg' : '' }}">
            <a class="sidebar_link fs-2" href="{{route('cable.index')}}">
                <span class="link_key">
                   <i class="fa-solid fa-tv"></i>
                </span>
                <span class="link_val">Cable TV</span>
            </a>
        </div>

    
        <div class="sidebar-item {{ Route::currentRouteName() == 'education.result.index' ? 'vastel-bg' : '' }}">
            <a class="sidebar_link fs-2" href="{{route('education.result.index')}}">
                <span class="link_key">
                   <i class="fa-solid fa-list-alt"></i>
                </span>
                <span class="link_val">Result Checker</span>
            </a>
        </div>
        <div class="sidebar-item {{ Route::currentRouteName() == 'payment.index' ? 'vastel-bg' : '' }}">
            <a class="sidebar_link fs-2" href="{{route('payment.index')}}">
                <span class="link_key">
                   <i class="fa-solid fa-credit-card"></i>
                </span>
                <span class="link_val">Fund Wallet</span>
            </a>
        </div>
        {{-- <div class="sidebar-item {{ Route::currentRouteName() == 'user.money-transfer' ? 'vastel-bg' : '' }}">
            <a class="sidebar_link fs-2" href="{{ route('user.money-transfer') }}">
                <span class="link_key">
                   <i class="fa-solid fa-money-bill"></i>
                </span>
                <span class="link_val">Money Transfer</span>
            </a>
        </div> --}}
         <div class="sidebar-item {{ Route::currentRouteName() == 'profile.edit' ? 'vastel-bg' : '' }}">
            <a class="sidebar_link fs-2" href="{{route('profile.edit')}}">
                <span class="link_key">
                   {{-- <i class="fa-solid fa-credit-card"></i> --}}
                   <i class="fa-solid fa-user"></i>
                </span>
                <span class="link_val">Profile</span>
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    .active {
        background-color: #FF9900;
        color: white;
    }
</style>
@endpush