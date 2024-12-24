@extends('layouts.admin.app')
@section('content')
    <div>
        <x-admin.page-title title="Content">
            <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
            <x-admin.page-title-item subtitle="Blacklist" status="true" />
        </x-admin.page-title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

        <div class="card">
            <div class="card-header">
                <div class="container mt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-12 d-flex align-items-center">
                            <!-- Search Form -->
                            <form action="" method="GET" class="d-flex me-4"> <!-- Added me-4 for spacing -->
                                <input 
                                    type="text" 
                                    name="query" 
                                    value="{{ request('query') }}"
                                    class="form-control me-2" 
                                    placeholder="Search..." 
                                    >
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>

                            <!-- Add Blacklist Modal Button -->
                            <button type="button" class="btn btn-success float-right" data-bs-toggle="modal" data-bs-target="#addBlacklistModal">
                                Add Blacklist
                            </button>
                        </div>

                        @include('components.error_message')

                    </div>
                </div>
            </div>

            <div class="card-body">
          
                <!-- Blacklist Table -->
                <x-admin.table>
                    <x-admin.table-header :headers="['ID', 'Type', 'Value', 'Action']" />
                    <x-admin.table-body>
                        @foreach($blacklists as $blacklist)
                        <tr>
                            <td>{{ $blacklist->id }}</td>
                            <td>{{ $blacklist->type }}</td>
                            <td>{{ $blacklist->value }}</td>
                            <td>
                                <!-- Remove Button -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#removeBlacklistModal-{{ $blacklist->id }}">
                                    Remove
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="removeBlacklistModal-{{ $blacklist->id }}" tabindex="-1" aria-labelledby="removeBlacklistModalLabel-{{ $blacklist->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="removeBlacklistModalLabel-{{ $blacklist->id }}">Confirm Removal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to remove this item from the blacklist?
                                                <div class="mt-2">
                                                    <strong>Blacklist ID:</strong> {{ $blacklist->id }}<br>
                                                    <strong>Value:</strong> {{ $blacklist->value }}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <form method="POST" action="{{ route('admin.blacklist.remove', [$blacklist->id]) }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $blacklist->id }}">
                                                    <button type="submit" class="btn btn-danger">Yes, Remove</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </x-admin.table-body>
                </x-admin.table>

            </div>
        </div>

        <!-- Add Blacklist Modal -->
        <div class="modal fade" id="addBlacklistModal" tabindex="-1" aria-labelledby="addBlacklistModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBlacklistModalLabel">Add New Blacklist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.blacklist.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="blacklistValue" class="form-label">Value</label>
                                <input type="text" name="value" class="form-control" id="blacklistValue" placeholder="Enter value" required>
                            </div>
                            <div class="mb-3">
                                <label for="blacklistType" class="form-label">Type</label>
                                <select name="type" class="form-select" id="blacklistType" required>
                                    <option value="">Select type</option>
                                    <option value="email">Email</option>
                                    <option value="phone">Phone</option>
                                    <option value="bvn">BVN</option>
                                    <option value="nin">NIN</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('title')
    Blacklist Management
@endpush
