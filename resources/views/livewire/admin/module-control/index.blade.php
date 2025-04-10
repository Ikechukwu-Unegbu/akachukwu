@push('title')
    Settings :: Module Control
@endpush
<div>
    <x-admin.page-title title="Settings">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Module Control" status="true" />
    </x-admin.page-title>

    <section class="section profile" x-data>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Manage Module Control</h5>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mt-4 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input form-check-input-lg" type="checkbox"
                                    id="cardFunding" x-on:click="$wire.handleSiteSettings('card_funding_status')" @checked($settings->card_funding_status)>
                                <label class="form-check-label" for="cardFunding">
                                    Card Funding Status
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input form-check-input-lg" type="checkbox"
                                    id="money_transfer" x-on:click="$wire.handleSiteSettings('money_transfer_status')" @checked($settings->money_transfer_status)>
                                <label class="form-check-label" for="money_transfer">
                                    Vastel Money Transfer
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input form-check-input-lg" type="checkbox"
                                    id="airtime_sales" x-on:click="$wire.handleSiteSettings('airtime_sales')" @checked($settings->airtime_sales)>
                                <label class="form-check-label" for="airtime_sales">
                                    Airtime Sales
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Vendor Module Control</h5>
                </div>
            </div>
        </div>
        @foreach ($vendors as $vendor)
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">{{ $vendor->name }} Networks & Data Types</h5>
                    </div>
                </div>
                <div class="card-body mt-3">
                    @forelse ($vendor->networks as $network)
                        <table class="table">
                            <tbody>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input form-check-input-lg" type="checkbox" x-on:click="$wire.handleNetwork({{ $network->id }})" id="network" @checked($network->status)>
                                        <label class="form-check-label" for="network">
                                            {{ $network->name }}
                                        </label>
                                    </div>
                                </td>
                            </tbody>
                            <tbody>
                                @foreach ($network->dataTypes->where('vendor_id', $vendor->id) as $dataType)
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input form-check-input-lg" type="checkbox" x-on:click="$wire.handleDataType({{ $dataType->id }})" id="dataType" @checked($dataType->status)>
                                            <label class="form-check-label" for="dataType">
                                                {{ $dataType->name }}
                                            </label>
                                        </div>
                                    </td>
                                @endforeach
                            </tbody>
                        </table>
                    @empty
                    @endforelse
                </div>
            </div>
        @endforeach
    </section>
</div>
