<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <x-admin.menu title="Dashboard" icon="bi-grid" link="{{ route('admin.dashboard') }}" />

    <li class="nav-heading">Manage Utilities</li>
    <x-admin.dropdown title="Utilities" icon="bxs-data">
      <x-admin.dropdown-item title="Data" link="{{ route('admin.utility.data') }}" />
      <x-admin.dropdown-item title="Cable TV" link="{{ route('admin.utility.cable') }}" />
      <x-admin.dropdown-item title="Electricity" link="{{ route('admin.utility.electricity') }}" />
    </x-admin.dropdown>

    <li class="nav-heading">Manage Transactions</li>
    <x-admin.dropdown title="Transactions" icon="bx-money">
      <x-admin.dropdown-item title="Airtime" link="{{ route('admin.transaction.airtime') }}" />
      <x-admin.dropdown-item title="Data" link="{{ route('admin.transaction.data') }}" />
      <x-admin.dropdown-item title="Cable TV" link="{{ route('admin.transaction.cable') }}" />
      <x-admin.dropdown-item title="Electricity" link="{{ route('admin.transaction.electricity') }}" />
    </x-admin.dropdown>

    <li class="nav-heading">Human Resource Mgt.</li>
    <x-admin.dropdown title="HR. Mgt." icon="bx-user-plus">
      <x-admin.dropdown-item title="Users" />
      <x-admin.dropdown-item title="Administrators" />
    </x-admin.dropdown>

    <li class="nav-heading">Manage APIs</li>
    <x-admin.dropdown title="APIs" icon="bx-code-curly">
      <x-admin.dropdown-item title="Vendors" link="{{ route('admin.api.vendor') }}" />
      <x-admin.dropdown-item title="Payment Gateway" link="{{ route('admin.api.payment') }}" />
    </x-admin.dropdown>

    <li class="nav-heading">Settings</li>
    <x-admin.dropdown title="App Settings" icon="bxs-cog">
      <x-admin.dropdown-item title="Roles" />
      <x-admin.dropdown-item title="Permissons" />
      <x-admin.dropdown-item title="Pofile" />
    </x-admin.dropdown>
  </ul>
</aside>
<!-- End Sidebar-->
