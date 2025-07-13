<div>
  <x-admin.page-title title="Transactions">
    <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
    <x-admin.page-title-item subtitle="Transaction" />
    <x-admin.page-title-item subtitle="Airtime" link="{{ route('admin.transaction.airtime') }}" />
    <x-admin.page-title-item subtitle="Manage" status="true" />
  </x-admin.page-title>

  <section class="section profile">
    <div class="row">
      <div class="col-xl-4">
        <div class="card">
          <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
            <img src="{{ $airtime->user->profilePicture }}" alt="Profile" class="rounded-circle">
            <h2>{{ $airtime->user->name }}</h2>
            <h3>{{ $airtime->user->email }}</h3>
          </div>
        </div>
      </div>

      <div class="col-xl-8">
        <div class="card">
          <h5 class="card-header">
            Airtime Transaction Details
          </h5>
          <div class="pt-2 card-body profile-overview">
            <div class="row">
              <div class="col-lg-3 col-md-4 label ">Vendor</div>
              <div class="col-lg-9 col-md-8">{{ $airtime->vendor->name }}</div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-md-4 label ">Service Name</div>
              <div class="col-lg-9 col-md-8">{{ $airtime->network_name }} Airtime</div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-md-4 label ">Phone</div>
              <div class="col-lg-9 col-md-8">{{ $airtime->mobile_number }}</div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-md-4 label ">Amount</div>
              <div class="col-lg-9 col-md-8">₦ {{ $airtime->amount }}</div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-md-4 label ">Date</div>
              <div class="col-lg-9 col-md-8">{{ $airtime->created_at->format('M d, Y. h:ia') }}</div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-md-4 label ">Transaction ID</div>
              <div class="col-lg-9 col-md-8">{{ $airtime->transaction_id }}</div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-md-4 label ">Status</div>
              <div class="col-lg-9 col-md-8">
                <span class="badge bg-{{ $airtime->status === 1 ? 'success' : ($airtime->status === 0 ? 'danger' : 'warning') }}">
                  {{ Str::title($airtime->vendor_status) }}</span>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-md-4 label ">Balance Before</div>
              <div class="col-lg-9 col-md-8">₦ {{ $airtime->balance_before }}</div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-md-4 label ">Balance After</div>
              <div class="col-lg-9 col-md-8">{{ $airtime->balance_after ? "₦ {$airtime->balance_after}" : 'N/A' }}</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>
@push('title')
Transactions / Airtime / Manage
@endpush