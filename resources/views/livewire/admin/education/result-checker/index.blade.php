<div>
    <x-admin.page-title title="Education">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Education" />
        <x-admin.page-title-item subtitle="Result Checker" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="">Manage Exam</h5>
            </div>
            <div class="card-body">
                <div class="mt-3 row">
                    <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="form-label">Vendor</label>
                            <select class="form-select" wire:model.live="vendor">
                                <option value="">---- Select Vendor -----</option>
                                @forelse ($vendors as $__vendor)
                                    <option value="{{ $__vendor->id }}">{{ $__vendor->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (count($result_checkers))
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $vendors->find($vendor)->name ?? '' }} - Result Checker</h5>
                    <x-admin.table>
                        <x-admin.table-header :headers="['#', 'Exam', 'Amount', 'Live Price', 'Status', 'Action']" />
                        <x-admin.table-body>
                            @forelse ($result_checkers as $result_checker)
                                <tr>
                                    <th scope="row">{{ $loop->index+1 }}</th>
                                    <td>{{ $result_checker->name }}</td>
                                    <td>₦{{ number_format($result_checker->amount, 2) }}</td>
                                    <td>
                                        @if ($result_checker->live_amount)
                                        <p class="m-0 p-0 text-{{ $result_checker->amount !== $result_checker->live_amount ? 'danger fw-bold' : 'black' }}"><small>₦{{ number_format($result_checker->live_amount, 2) }}</small></p>
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td><span class="badge bg-{{ $result_checker->status ? 'success' : 'danger' }}">{{ $result_checker->status ? 'Active' : 'Not-Active' }}</span></td>
                                    <td>
                                        <div class="filter">
                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                @can('edit e-pin')
                                                <li><a href="{{ route('admin.education.result-checker.edit', [$vendor, $result_checker->id]) }}" class="dropdown-item text-secondary"><i class="bx bx-edit"></i> Edit</a></li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No records available</td>
                                </tr>
                            @endforelse
                        </x-admin.table-body>
                    </x-admin.table>
                </div>
            </div>
        @endif
    </section>
</div>
@push('title')
    Education :: Result Checker
@endpush