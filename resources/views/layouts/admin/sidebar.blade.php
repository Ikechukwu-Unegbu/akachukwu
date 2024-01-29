<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <x-admin.menu title="Dashboard" icon="bi-grid" link="{{ route('admin.dashboard') }}" />

    <li class="nav-heading">Manage Utilities</li>
    <x-admin.dropdown title="Utilities" icon="bxs-data">
      <x-admin.dropdown-item title="Data" link="{{ route('admin.utility.data') }}" />
      <x-admin.dropdown-item title="Cable TV" link="{{ route('admin.utility.cable') }}" />
      <x-admin.dropdown-item title="Electricity" link="" />
    </x-admin.dropdown>

    <li class="nav-heading">Manage Transactions</li>
    <x-admin.dropdown title="Transactions" icon="bx-money">
      <x-admin.dropdown-item title="Airtime" link="" />
      <x-admin.dropdown-item title="Data" link="" />
      <x-admin.dropdown-item title="Cable TV" link="" />
      <x-admin.dropdown-item title="Electricity" link="" />
    </x-admin.dropdown>

    <li class="nav-heading">Human Resource Mgt.</li>
    <x-admin.dropdown title="HR. Mgt." icon="bx-user-plus">
      <x-admin.dropdown-item title="Users" link="" />
      <x-admin.dropdown-item title="Administrators" link="" />
    </x-admin.dropdown>

    <li class="nav-heading">Manage APIs</li>
    <x-admin.dropdown title="APIs" icon="bx-code-curly">
      <x-admin.dropdown-item title="Vendors" link="" />
      <x-admin.dropdown-item title="Payment Gateway" link="" />
    </x-admin.dropdown>

    <li class="nav-heading">Settings</li>
    <x-admin.dropdown title="App Settings" icon="bxs-cog">
      <x-admin.dropdown-item title="Roles" link="" />
      <x-admin.dropdown-item title="Permissons" link="" />
      <x-admin.dropdown-item title="Pofile" link="" />
    </x-admin.dropdown>
  </ul>
</aside>
<!-- End Sidebar-->
