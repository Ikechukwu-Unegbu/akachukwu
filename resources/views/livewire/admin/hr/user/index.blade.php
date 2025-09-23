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

        <!-- Bulk action toolbar -->
        <div id="bulk-actions" class="mb-3 d-none">
            <button class="btn btn-danger me-2" data-action="blacklist">Blacklist</button>
            <button class="btn btn-warning me-2" data-action="block">Block (Post No Debit)</button>
            <button class="btn btn-outline-danger me-2" data-action="unblacklist">Un-Blacklist</button>
            <button class="btn btn-outline-warning" data-action="unblock">Un-Block</button>
            <span id="selected-count" class="ms-3 text-muted"></span>
        </div>

        <!-- Confirm Modal -->
        <div class="modal fade" id="bulkConfirmModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Confirm Bulk Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                You are about to <strong id="modal-action"></strong>
                <span id="modal-count"></span> user(s). Continue?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-bulk">Continue</button>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage"
                    wireSearchAction="wire:model.live=search" />
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Check', 'Name', 'Username', 'Bal.', 'Level', 'Joined', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>
                                    <input class="form-check-input" type="checkbox"
                                        value="{{ $user->id }}">
                                </td>

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
    // === Filter select keeps param in URL ===
    const url = new URL(window.location.href);
    const select = document.getElementById('filter-select');
    const param = url.searchParams.get('param');
    if (param) {
        select.value = param;
    } else {
        select.value = "";
    }
    select.addEventListener('change', function () {
        const selectedValue = this.value;
        if (selectedValue) {
            url.searchParams.set('param', selectedValue);
        } else {
            url.searchParams.delete('param');
        }
        window.location.href = url.toString();
    });

    // === Bulk Actions ===
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input.form-check-input[type="checkbox"][value]');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');
        const modalEl = document.getElementById('bulkConfirmModal');
        const modalActionEl = document.getElementById('modal-action');
        const modalCountEl = document.getElementById('modal-count');
        const confirmBtn = document.getElementById('confirm-bulk');
        let currentAction = null;

        const modal = new bootstrap.Modal(modalEl);

        function getSelectedIds() {
            return Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
        }

        function updateToolbar() {
            const ids = getSelectedIds();
            if (ids.length > 0) {
                bulkActions.classList.remove('d-none');
                selectedCount.textContent = ids.length + " selected";
            } else {
                bulkActions.classList.add('d-none');
                selectedCount.textContent = "";
            }
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateToolbar));

        bulkActions.addEventListener('click', function(e) {
            if (e.target.matches('button[data-action]')) {
                currentAction = e.target.getAttribute('data-action');
                const ids = getSelectedIds();
                if (ids.length === 0) return;
                modalActionEl.textContent = currentAction;
                modalCountEl.textContent = ids.length;
                modal.show();
            }
        });

        confirmBtn.addEventListener('click', function() {
            const ids = getSelectedIds();
            fetch(`/admin/users/bulk-action`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    action: currentAction,
                    user_ids: ids
                })
            })
            .then(res => res.json())
            .then(data => {
                modal.hide();
                alert(data.message || 'Action completed');
                window.location.reload();
            })
            .catch(err => {
                console.error(err);
                alert('Something went wrong.');
            });
        });

        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

@push('title')
    Human Resource Mgt. / Users
@endpush
