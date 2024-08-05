<div>
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
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
                </div>
            </div>
        </div>
        <!-- links -->
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
        <a href="{{route('admin.api.payment')}}" class="text-decoration-none text-primary">Payment Gateway APIs</a>
        <p class="mb-0 small text-muted">Manage Payment gateway APIs.</p>
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
    </ul>
  </div>
</section>

        <!-- links end -->

        <div class="card">
            <div class="card-body">
                <div>
                    <canvas id="usersChart"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>
@push('title') Dashboard @endpush
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