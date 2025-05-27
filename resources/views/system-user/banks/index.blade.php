@extends('layouts.admin.app')
@section('content')
    <x-admin.page-title title="Banks">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Bank Management" />
    </x-admin.page-title>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="p-1 m-1 card-title">Bank Mgt.</h5>
                    <div class="">
                        <a href="{{ route('admin.bank.create') }}" class="btn btn-primary">Create New Bank</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.bank.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Name or code...">
                        </div>

                        <div class="col-md-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-control" id="type" name="type">
                                <option value="">All Types</option>
                                <option value="monnify" {{ request('type') == 'monnify' ? 'selected' : '' }}>Monnify
                                </option>
                                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="3%">ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banks as $bank)
                                <tr>
                                    <td>{{ $loop->index + $banks->firstItem() }}</td>
                                    <td>{{ $bank->name }}</td>
                                    <td>{{ $bank->code }}</td>
                                    <td>{{ ucfirst($bank->type) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $bank->status ? 'success' : 'danger' }}">
                                            {{ $bank->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.bank.edit', $bank->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('admin.bank.delete', $bank->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $banks->links() }}
                </div>
            </div>


    </section>

    @push('title')
        Bank Mgt.
    @endpush
@endsection
