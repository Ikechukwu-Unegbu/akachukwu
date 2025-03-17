<div>
    <x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Site Settings" />
        <x-admin.page-title-item subtitle="Users" status="true" />
    </x-admin.page-title>

    <section class="section">
       
                    @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{route('admin.site.update')}}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="site_title" class="form-label">Site Title</label>
              <input type="text" value="{{$setting->stie_title}}" class="form-control" id="site_title" name="site_title">
            </div>
            <div class="mb-3">
              <label for="site_logo" class="form-label">Site Logo</label>
              <input type="file"  class="form-control" id="site_logo" name="site_logo">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" value="{{$setting->email}}" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
              <label for="phone1" class="form-label">Phone 1</label>
              <input type="text" class="form-control" value="{{$setting->phone1}}" id="phone1" name="phone1">
            </div>
            <div class="mb-3">
              <label for="address_one" class="form-label">Address 1</label>
              <input type="text" class="form-control" value="{{$setting->address_one}}" id="address_one" name="address_one">
            </div>
            <div class="mb-3">
              <label for="address_two" class="form-label">Address 2</label>
              <input type="text" class="form-control" value="{{$setting->address_two}}" id="address_two" name="address_two">
            </div>
            <div class="mb-3">
              <label for="phone2" class="form-label">Phone 2</label>
              <input type="text" class="form-control" value="{{$setting->phone2}}" id="phone2" name="phone2">
            </div>
            <div class="mb-3">
              <label for="total_users" class="form-label">Total Users</label>
              <input type="text" class="form-control" value="{{$setting->total_users}}" id="total_users" name="total_users" >
            </div>
            <div class="mb-3">
              <label for="money_transfer_status" class="form-label">Vastel Money Transfer</label>
              <select class="form-select" id="money_transfer_status" required="" name="money_transfer_status">
                <option value="1" @selected($setting->money_transfer_status === 1)>Activated</option>
                <option value="0" @selected($setting->money_transfer_status === 0)>Deactivated</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="airtime_limit" class="form-label">Airtime Purchase Limit</label>
              <input type="number" class="form-control" value="{{$setting->airtime_limit}}" id="airtime_limit" name="airtime_limit">
            </div>
            <div class="mb-3">
              <label for="transfer_charges" class="form-label">Money Transfer Charges</label>
              <input type="number" class="form-control" value="{{$setting->transfer_charges}}" id="transfer_charges" name="transfer_charges">
            </div>
            <div class="mb-3">
              <label for="minimum_transfer" class="form-label">Minimum Transfer Amount</label>
              <input type="number" class="form-control" value="{{$setting->minimum_transfer}}" id="minimum_transfer" name="minimum_transfer">
            </div>
            <div class="mb-3">
              <label for="maximum_transfer" class="form-label">Maximum Daily Transfer</label>
              <input type="number" class="form-control" value="{{$setting->maximum_transfer}}" id="maximum_transfer" name="maximum_transfer">
            </div>
            <div class="mb-3">
              <label for="card_charges" class="form-label">Percent Charge on Card Funding</label>
              <input type="text" class="form-control" value="{{$setting->card_charges}}" id="card_charges" name="card_charges">
            </div>
            <div class="mb-3">
              <label for="card_funding_status" class="form-label">Card Funding Status</label>
              <select class="form-select" id="card_funding_status" required="" name="card_funding_status">
                <option value="1" @selected($setting->card_funding_status === 1)>Activated</option>
                <option value="0" @selected($setting->card_funding_status === 0)>Deactivated</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" value="{{$setting->name}}">
            </div>
            <div class="mb-3">
              <label for="twitter" class="form-label">Twitter</label>
              <input type="text" class="form-control" id="twitter" name="twitter" value="{{$setting->twitter}}">
            </div>
            <div class="mb-3">
              <label for="facebook" class="form-label">Facebook</label>
              <input type="text" class="form-control" id="facebook" name="facebook" value="{{$setting->facebook}}">
            </div>
            <div class="mb-3">
              <label for="instagram" class="form-label">Instagram</label>
              <input type="text" class="form-control" id="instagram" name="instagram" value="{{$setting->instagram}}">
            </div>
            <div class="mb-3">
              <label for="linkedin" class="form-label">LinkedIn</label>
              <input type="text" class="form-control" id="linkedin" name="linkedin" value="{{$setting->linkedin}}">
            </div>
            <button type="submit" class="btn btn-primary float-right">Submit</button>
        </form>
    </section>

    <script>
    document.getElementById('myForm').addEventListener('submit', function() {
        document.getElementById('submitButton').disabled = true;
    });
</script>
</div>
@push('title')
Site Settings
@endpush