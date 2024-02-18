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
                        <x-admin.dashboard-card title="Customers" :data=$customers_count icon="bi-people" />
                    </div>
                </div>
            </div>
        </div>

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