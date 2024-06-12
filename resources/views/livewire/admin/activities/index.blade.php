<div>
    <x-admin.page-title title="Activitiy Logs">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Activitiy Log" />
        <x-admin.page-title-item subtitle="Logs" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">Activitiy Logs</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage"
                    wireSearchAction="wire:model.live=search" />
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'User', 'Description', 'Timestamp', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($activity_logs as $log)
                        <tr>
                            <th scope="row">{{ $loop->index+$activity_logs->firstItem() }}</th>
                            <td>{{ $log->causer->name }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->created_at->format('M d, Y h:ia') }}</td>
                            <td>
                                <button type="button"  class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#action-{{ $log->id }}">
                                    View
                                </button>
                            </td>
                        </tr>

                        <!-- Vertically centered Modal -->
                        
                        <div class="modal fade" id="action-{{ $log->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Actions</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ json_encode($log->properties) }}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Vertically centered Modal-->
                        @empty
                        <tr>
                            <td colspan="5">No records available</td>
                        </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
                <x-admin.paginate :paginate=$activity_logs />
            </div>
        </div>
    </section>
</div>
@push('title')
Activity Logs / Logs
@endpush