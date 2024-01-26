<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <x-admin.menu title="Dashboard" icon="bi-grid" link="{{ route('admin.dashboard') }}" />

    <li class="nav-heading">Utilities</li>
    <x-admin.dropdown title="Utilities" icon="bi-menu-button-wide">
      <x-admin.dropdown-item title="Data" link="{{ route('admin.utility.data') }}" />
      <x-admin.dropdown-item title="Cable TV" link="hello" />
      <x-admin.dropdown-item title="Electricity" link="hello" />
    </x-admin.dropdown>

    <li class="nav-heading">Transactions</li>
    <x-admin.dropdown title="Transactions" icon="bi-menu-button-wide">
      <x-admin.dropdown-item title="Airtime" link="hello" />
      <x-admin.dropdown-item title="Data" link="hello" />
      <x-admin.dropdown-item title="Cable TV" link="hello" />
      <x-admin.dropdown-item title="Electricity" link="hello" />
    </x-admin.dropdown>

    <li class="nav-heading">Human Resource Mgt.</li>
    <x-admin.dropdown title="HR. Mgt." icon="bi-user">
      <x-admin.dropdown-item title="Users" link="hello" />
      <x-admin.dropdown-item title="Administrators" link="hello" />
    </x-admin.dropdown>

    <li class="nav-heading">Settings</li>
    <x-admin.dropdown title="App Settings" icon="bxs-cog">
      <x-admin.dropdown-item title="Roles" link="hello" />
      <x-admin.dropdown-item title="Permissons" link="hello" />
      <x-admin.dropdown-item title="Pofile" link="hello" />
    </x-admin.dropdown>
  </ul>
</aside>
<!-- End Sidebar-->
