<div>
    <x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="HR Mgt." />
        <x-admin.page-title-item subtitle="Users" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Manage Users</h5>
            </div>

            <select class="form-select" aria-label="Default select example" id="filter-select">
                <option value="">All Users</option>
                <option value="blocked">Blocked Users</option>
                <option value="negative-balance">Negative Balance</option>
                <option value="flagged">FLAGGED</option>
                <option value="post-no-debit">POST NO DEBIT</option>
                <option value="balance-high">BALANCE (high)</option>
                <option value="balance-low">BALANCE (low)</option>
            </select>



        </div>
        <div class="container p-3 border rounded bg-light">
            <form method="GET" class="row align-items-end gx-3">
                <div class="col-md-4">
                    <label for="start-date" class="form-label">Start Date</label>
                    <input type="date" id="start-date" name="startDate" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="end-date" class="form-label">End Date</label>
                    <input type="date" id="end-date" name="endDate" class="form-control">
                </div>
                <div class="mt-2 col-md-4 d-flex justify-content-start mt-md-0">
                    <button type="submit" class="btn btn-primary w-100">
                        Filter
                    </button>
                </div>
            </form>
        </div>



        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage"
                    wireSearchAction="wire:model.live=search" />
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Name', 'Username', 'Bal.', 'Level', 'Joined', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>
                                    {{ $user->name }}
                                    <div class="d-flex">
                                        @if ($user->is_flagged)
                                            <span class="badge bg-danger me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                                  title="{{ $user->flaggedByAdmin ? 'Flagged by: ' . $user->flaggedByAdmin->name : 'Flagged by: Unknown' }}">
                                                <i class="bi bi-info-circle me-1"></i>Flagged {{ $user->flaggedByAdmin ? 'Flagged by: ' . $user->flaggedByAdmin->name : 'Flagged by: System' }}
                                            </span>
                                        @endif
                                        @if ($user->post_no_debit)
                                            <span class="badge bg-danger me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                                  title="{{ $user->postNoDebitByAdmin ? 'Post no Debit by: ' . $user->postNoDebitByAdmin->username : 'Post no Debit by: Unknown' }}">
                                                <i class="bi bi-info-circle me-1"></i>Post no Debit {{ $user->postNoDebitByAdmin ? 'Post no Debit by: ' . $user->postNoDebitByAdmin->username : 'Post no Debit by: System' }}
                                            </span>
                                        @endif
                                        @if ($user->is_blacklisted)
                                            <span class="badge bg-danger me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                                  title="{{ $user->blacklistedByAdmin ? 'Blacklisted by: ' . $user->blacklistedByAdmin->username : 'Blacklisted by: Unknown' }}">
                                                <i class="bi bi-info-circle me-1"></i>Blacklisted {{ $user->blacklistedByAdmin ? 'Blacklisted by: ' . $user->blacklistedByAdmin->username : 'Blacklisted by: System' }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>₦ {{ $user->account_balance }}</td>
                                <td>{{ Str::title($user->user_level) }}</td>
                                <td>{{ $user->created_at->format('d M, Y') }}</td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a href="{{ route('admin.hr.user.show', $user->username) }}"
                                                    class="dropdown-item text-primary"><i class="bx bx-list-ul"></i>
                                                    View</a></li>
                                            <li><a href="{{ route('admin.crd-dbt', ['username' => $user->username]) }}"
                                                    class="dropdown-item text-primary"><i class="bx bx-list-ul"></i>Top</a>
                                            </li>
                                            <li><a href="{{ route('admin.hr.user.upgrade', $user->username) }}"
                                                    class="dropdown-item text-success"><i
                                                        class="bx bx-vertical-top"></i>Upgrade</a></li>
                                        </ul>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>

                <x-admin.paginate :paginate=$users />
            </div>
        </div>
    </section>
</div>
<script>
    // Get the current URL and query parameters
    const url = new URL(window.location.href);
    const select = document.getElementById('filter-select');
    const param = url.searchParams.get('param');

    // Set the initial value of the select input based on the URL
    if (param) {
        select.value = param;
    } else {
        select.value = ""; // Default to the first option if no param is present
    }

    // Add event listener for select input changes
    select.addEventListener('change', function () {
        const selectedValue = this.value;

        if (selectedValue) {
            url.searchParams.set('param', selectedValue); // Set or update the `param` query parameter
        } else {
            url.searchParams.delete('param'); // Remove the `param` query parameter if the first option is selected
        }

        window.location.href = url.toString(); // Navigate to the updated URL
    });

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@push('title')
    Human Resource Mgt. / Users
@endpush
