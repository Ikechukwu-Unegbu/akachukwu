<div>
<div wire:loading wire:target="filterByDate" wire:loading.class="d-block">
    @include('livewire.component.placeholder')
</div>
<div wire:loading.remove>
    <style>
        .card-custom {
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }
        .card-custom:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .balance {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</div>
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <div class="d-flex justify-content-between align-items-center">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
            <div class="form-group">
                <input type="date" class="form-control form-control-lg" wire:model.live="filterByDate">
            </div>
        </div>
    </div>
    <section class="section dashboard" wire:loading.remove>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12">
                       <x-admin.dashboard-card title="Airtime Sales" currency="₦" :data=$airtime_sale icon="bi-cart" day="Today" />
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12">
                        <x-admin.dashboard-card title="Data Sales" currency="₦" :data=$data_sale icon="bi-cart" day="Today" />
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12">
                        <x-admin.dashboard-card title="Cable Sales" currency="₦" :data=$cable_sale icon="bi-cart" day="Today" />
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12">
                        <x-admin.dashboard-card title="Electricity Sales" currency="₦" :data=$electricity_sale icon="bi-cart" day="Today" />
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12">
                        <x-admin.dashboard-card title="Education" :data=$result_checker_count icon="bi-cart" />
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12">
                        <x-admin.dashboard-card title="Customers" :data=$customers_count icon="bi-people" />
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12">
                        <x-admin.dashboard-card title="Resellers" :data=$resellers_count icon="bi-people" />
                    </div>
                     <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12">
                        <x-admin.dashboard-card title="Intra Transfer" currency="₦" :data=$vastel_transfer_count icon="bi-cash" />
                    </div>
                     <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12">
                        <x-admin.dashboard-card title="Bank Transfer" currency="₦" :data=$bank_transfer_count icon="bi-cash" />
                    </div>

                    {{-- @foreach ($vendors as $vendor)
                        @foreach ($vendor->balances as $balance)
                        <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 col-12">
                            <div class="card info-card sales-card" style="padding-bottom: 0px !important;">
                                <div class="card-body" style="display: inline !important">
                                    <h5 class="card-title" style="margin-top: 10px !important; padding: 0px !important;">{{ $vendor->name }}<span>| Yesterday</span></h5>
                                    
                                    <div style="margin: 0 !important; padding: 0px !important;">
                                        <p class="m-0 p-0">Starting Date: {{ number_format($balance->starting_balance), 2 }}</p>
                                        <p class="m-0 p-0">Closing Date: {{ number_format($balance->closing_balance), 2 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endforeach --}}
                </div>
            </div>
        </div>
       
        <!-- vendor balance -->
     
         @if(auth()->user()->can('view vendor api'))
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card card-custom">
                    <div class="card-header">User Wallet</div>
                    <div class="card-body">
                        <span class="vendor-name">Balance</span>
                        <span class="balance">NGN {{$total_wallet}}</span>
                    </div>
                </div>
            </div>
            <!-- Card 1 -->
            <div class="col-md-6 mb-4">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>Postranet</div>
                            <div><a class="" href="{{ route('admin.api.vendor.account') }}">More</a></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="vendor-name">Balance</span>
                        <span class="balance">NGN {{$postranetBlance}}</span>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <h6>Starting:</h6>
                            <h6>₦{{ number_format(optional($vendors->firstWhere('name', 'POSTRANET')?->balances()->whereDate('date', \Carbon\Carbon::yesterday())->first())->starting_balance, 2) ?? 'N/A' }}</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6>Closing:</h6>
                            <h6>₦{{ number_format(optional($vendors->firstWhere('name', 'POSTRANET')?->balances()->whereDate('date', \Carbon\Carbon::yesterday())->first())->closing_balance, 2 ) ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-6 mb-4">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>Gladtidings</div>
                            <div><a class="" href="{{ route('admin.api.vendor.account') }}">More</a></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="vendor-name">Balance</span>
                        <span class="balance">NGN {{$gladBalance}}</span>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <h6>Starting:</h6>
                            <h6>₦{{ number_format(optional($vendors->firstWhere('name', 'GLADTIDINGSDATA')?->balances()->whereDate('date', \Carbon\Carbon::yesterday())->first())->starting_balance, 2) ?? 'N/A' }}</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6>Closing:</h6>
                            <h6>₦{{ number_format(optional($vendors->firstWhere('name', 'GLADTIDINGSDATA')?->balances()->whereDate('date', \Carbon\Carbon::yesterday())->first())->closing_balance, 2 ) ?? 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-6 mb-4">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>VTPass</div>
                            <div><a class="" href="{{ route('admin.api.vendor.account') }}">More</a></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="vendor-name">Balance</span>
                        <span class="balance">NGN {{$vtBalance}}</span>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <h6>Starting:</h6>
                            <h6>₦{{ number_format(optional($vendors->firstWhere('name', 'VTPASS')?->balances()->whereDate('date', \Carbon\Carbon::yesterday())->first())->starting_balance, 2) ?? 'N/A' }}</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6>Closing:</h6>
                            <h6>₦{{ number_format(optional($vendors->firstWhere('name', 'VTPASS')?->balances()->whereDate('date', \Carbon\Carbon::yesterday())->first())->closing_balance, 2) ?? 'N/A' }}</h6>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- vendor balance ends -->

        <!-- Quick Links Section -->
      <section class="container my-4">
        <div class="card shadow-sm">
          <div class="card-header">
            <h5 class="card-title mb-0">Quick Links</h5>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <a href="{{route('admin.hr.user')}}" class="text-decoration-none text-primary">Users</a>
              <p class="mb-0 small text-muted">Manage Users in one place .</p>
            </li>
            <li class="list-group-item">
              <a href="{{route('admin.hr.reseller')}}" class="text-decoration-none text-primary">Resellers</a>
              <p class="mb-0 small text-muted">Manage resellers in one place.</p>
            </li>
            <li class="list-group-item">
              <a href="{{route('admin.transaction')}}" class="text-decoration-none text-primary">All Transactions</a>
              <p class="mb-0 small text-muted">View all transactions records in one place.</p>
            </li>
            <li class="list-group-item">
              <a href="{{route('admin.utility.data')}}" class="text-decoration-none text-primary">Manage Data</a>
              <p class="mb-0 small text-muted">Manage Data for all vendors - Gladtidings, Postranet and VtPass</p>
            </li>
            <li class="list-group-item">
              <a href="{{route('admin.utility.cable')}}" class="text-decoration-none text-primary">Manage Cable TV</a>
              <p class="mb-0 small text-muted">Manage Cable for all vendors - Gladtidings, Postranet and VtPass</p>
            </li>
            <li class="list-group-item">
              <a href="{{route('admin.utility.electricity')}}" class="text-decoration-none text-primary">Manage Electricity</a>
              <p class="mb-0 small text-muted">Manage Electricity for all vendors - Gladtidings, Postranet and VtPass</p>
            </li>
            <li class="list-group-item">
              <a href="{{route('admin.education.result-checker')}}" class="text-decoration-none text-primary">Manage Result Checker</a>
              <p class="mb-0 small text-muted">Educational product management</p>
            </li>
            <li class="list-group-item">
              <a href="{{route('admin.api.vendor')}}" class="text-decoration-none text-primary">Manage Vendor APIs</a>
              <p class="mb-0 small text-muted">Manage API of our vendors.</p>
            </li>
            <li class="list-group-item">
              <a href="{{route('admin.api.vendor.account')}}" class="text-decoration-none text-primary">Manage Vendor Account APIs</a>
              <p class="mb-0 small text-muted">Manage Vendors Account.</p>
            </li>
            <li class="list-group-item">
              <a href="{{route('admin.api.payment')}}" class="text-decoration-none text-primary">Payment Gateway APIs</a>
              <p class="mb-0 small text-muted">Manage Payment gateway APIs.</p>
            </li>
            <li class="list-group-item">
              <a href="{{route('admin.app-logos')}}" class="text-decoration-none text-primary">Manage Mobile App Assets</a>
              <p class="mb-0 small text-muted">Logos and Images displayed on mobile app.</p>
            </li>
            @can('blacklist')
            <li class="list-group-item">
              <a href="{{route('admin.blacklist')}}" class="text-decoration-none text-primary">Blacklist</a>
              <p class="mb-0 small text-muted">Restrain malicious users from spending while allowing them to fund.</p>
            </li>
            @endcan
            <li class="list-group-item">
              <a href="{{route('admin.api.vendor.service')}}" class="text-decoration-none text-primary">Manage Services and Vendors</a>
              <p class="mb-0 small text-muted">Manage which vendor powers each of the services.</p>
            </li>
          @if(Auth::user()->role =='superadmin')
          <li class="list-group-item">
              <a href="{{route('admin.settings.role')}}" class="text-decoration-none text-primary">Roles and Permissions</a>
              <p class="mb-0 small text-muted">Manage Admins.</p>
            </li>
          @endif 
            <li class="list-group-item">
              <a href="{{route('admin.site.settings')}}" class="text-decoration-none text-primary">Site Settings</a>
              <p class="mb-0 small text-muted">Manage site variables like phone, email, address, logo, etc.</p>
            </li>
            @if(Auth::user()->can('announcer'))
            <li class="list-group-item">
              <a href="{{route('admin.announcement')}}" class="text-decoration-none text-primary">Create Announcement</a>
              <p class="mb-0 small text-muted">Post Announcement</p>
            </li>
            <li class="list-group-item">
              <a href="{{route('admin.announcement')}}" class="text-decoration-none text-primary">Manage Announcement</a>
              <p class="mb-0 small text-muted">Delete, Edit Announcements</p>
            </li>
            @endif 
          </ul>
        </div>
      </section>

        <!-- links end -->

        <div class="card" wire:ignore>
            <div class="card-body">
                <div>
                    <canvas id="usersChart"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>
@push('scripts')
<script src="{{ asset('admin-pages/js/chart.js') }}"></script>
<script>
    var __yMonths =  {{ Js::from($months) }};
    var __xMonthlyUsersData =  {{ Js::from($registeredUser) }};

    const dataUsers = {
        labels: __yMonths,
        datasets: [{
            label: 'Users Chart',
            backgroundColor: 'rgb(65, 84, 241, 1)',
            borderColor: 'rgb(65, 84, 200, 1)',
            data: __xMonthlyUsersData,

        }]
    };
    const configUsers = {
        type: 'line',
        data: dataUsers,
        options: {}
    };
    const users = new Chart(
        document.getElementById('usersChart'),
        configUsers
    );
</script>
@endpush
@push('title') Dashboard @endpush