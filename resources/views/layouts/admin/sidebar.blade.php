<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <x-admin.menu title="Dashboard" icon="bi-grid" link="{{ route('admin.dashboard') }}" />
    
    @if(auth()->user()->can('view data utility') || auth()->user()->can('view cable utility') || auth()->user()->can('view electricity utility'))
      <li class="nav-heading">Manage Utilities</li>   
      <x-admin.dropdown title="Utilities" icon="bxs-data">
        @can('view data utility')
          <x-admin.dropdown-item title="Data" link="{{ route('admin.utility.data') }}" />
        @endcan
        @can('view cable utility')
        <x-admin.dropdown-item title="Cable TV" link="{{ route('admin.utility.cable') }}" />
        @endcan
        @can('view electricity utility')
        <x-admin.dropdown-item title="Electricity" link="{{ route('admin.utility.electricity') }}" />
        @endcan
      </x-admin.dropdown>
    @endif

    @if(auth()->user()->can('view airtime transaction') || auth()->user()->can('view data transaction') || auth()->user()->can('view cable transaction') || auth()->user()->can('view electricity transaction'))
    <li class="nav-heading">Manage Transactions</li>
    <x-admin.dropdown title="Transactions" icon="bx-money">
      @can('view airtime transaction')
        <x-admin.dropdown-item title="Airtime" link="{{ route('admin.transaction.airtime') }}" />
      @endcan
      @can('view data transaction')
      <x-admin.dropdown-item title="Data" link="{{ route('admin.transaction.data') }}" />
      @endcan
      @can('view cable transaction')
      <x-admin.dropdown-item title="Cable TV" link="{{ route('admin.transaction.cable') }}" />
      @endcan
      @can('view electricity transaction')
      <x-admin.dropdown-item title="Electricity" link="{{ route('admin.transaction.electricity') }}" />
      @endcan
    </x-admin.dropdown>
    @endif

    @if(auth()->user()->can('view users') || auth()->user()->can('view administrators'))
    <li class="nav-heading">Human Resource Mgt.</li>
    <x-admin.dropdown title="HR. Mgt." icon="bx-user-plus">
      @can('view users')
      <x-admin.dropdown-item title="Users" link="{{ route('admin.hr.user') }}" />
      @endcan
      @can('view administrators')
      <x-admin.dropdown-item title="Administrators" link="{{ route('admin.hr.administrator') }}" />
      @endcan
    </x-admin.dropdown>
    @endif

    @if(auth()->user()->can('view vendor api') || auth()->user()->can('view payment api'))
    <li class="nav-heading">Manage APIs</li>
    <x-admin.dropdown title="APIs" icon="bx-code-curly">
      @can('view vendor api')
      <x-admin.dropdown-item title="Vendors" link="{{ route('admin.api.vendor') }}" />
      @endcan
      @can('view payment api')
      <x-admin.dropdown-item title="Payment Gateway" link="{{ route('admin.api.payment') }}" />
      @endcan
    </x-admin.dropdown>
    @endif
    
    @if(auth()->user()->can('view role'))
    <li class="nav-heading">Settings</li>
    <x-admin.dropdown title="App Settings" icon="bxs-cog">
      @can('view role')
      <x-admin.dropdown-item title="Roles & Permissons" link="{{ route('admin.settings.role') }}" />
      @endcan
      {{-- <x-admin.dropdown-item title="Pofile" link="{{ route('admin.settings.profile') }}" /> --}}
    </x-admin.dropdown>
    @endif
  </ul>
</aside>
<!-- End Sidebar-->
