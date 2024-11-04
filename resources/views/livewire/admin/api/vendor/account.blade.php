<div>
    <x-admin.page-title title="APIs">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="APIs" />
        <x-admin.page-title-item subtitle="Vendors Account" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Manage Vendors Account</h5>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form>
                    <div class="row">
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" wire:model="startDate" name="start_date" id="start_date">
                            </div>
                        </div>
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" wire:model="endDate" name="end_date" id="end_date">
                            </div>
                        </div>
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="filter"></label>
                                <input type="submit" class="form-control btn btn-primary" value="Filter" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Vendor', 'Date', 'Starting Balance', 'Closing Balance']" />
                    <x-admin.table-body>
                        @forelse ($vendors as $key => $vendor)
                            @forelse ($vendor->balances as $balance)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $vendor->name }}</td>
                                    <td>{{ date('d M, Y', strtotime($balance->date)) }}</td>
                                    <td>₦{{ number_format($balance->starting_balance, 2) }}</td>
                                    <td>₦{{ number_format($balance->closing_balance, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No Records Found!</td>
                                </tr>
                            @endforelse
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No Records Found!</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
            </div>
        </div>
    </section>
</div>
@push('title')
    API / Vendor / Account
@endpush