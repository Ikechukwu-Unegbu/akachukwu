<div>
    <x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="HR Mgt." />
        <x-admin.page-title-item subtitle="Resellers" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Manage Resellers</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Name', 'Username', 'Account Balance', 'Level', 'Joined', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($resellers as $reseller)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $reseller->name }}</td>
                                <td>{{ $reseller->username }}</td>
                                <td>₦ {{ $reseller->account_balance }}</td>
                                <td>{{ Str::title($reseller->user_level) }}</td>
                                <td>{{ $reseller->created_at->format('d M, Y') }}</td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a href="{{ route('admin.hr.reseller.show', $reseller->username) }}" class="dropdown-item text-primary"><i class="bx bx-list-ul"></i> View</a></li>
                                            <li><a href="{{ route('admin.crd-dbt', ['username' => $reseller->username]) }}" class="dropdown-item text-primary"><i class="bx bx-list-ul"></i>Top</a></li>
                                            <li><a href="{{ route('admin.hr.reseller.upgrade', $reseller->username) }}" class="dropdown-item text-success"><i class="bx bx-vertical-top"></i>Upgrade</a></li>
                                        </ul>
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>

                <x-admin.paginate :paginate=$resellers />
            </div>
        </div>
    </section>
</div>
@push('title')
Human Resource Mgt. / Resellers
@endpush