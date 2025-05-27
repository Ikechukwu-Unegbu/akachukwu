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
                        <a href="{{ route('admin.bank.create') }}" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Bank List</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
                <div class="card-header">Edit Bank</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.bank.update', $bank->id) }}" class="mt-3">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $bank->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">Bank Code</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{ $bank->code }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                @foreach ($vendors as $type)
                                    <option value="{{ $type }}" {{ $bank->type === $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image URL</label>
                            <input type="text" class="form-control" id="image" name="image" value="{{ $bank->image }}">
                        </div>

                        <div class="mb-3">
                            <label for="ussd_template" class="form-label">USSD Template</label>
                            <input type="text" class="form-control" id="ussd_template" name="ussd_template" value="{{ $bank->ussd_template }}">
                        </div>

                        <div class="mb-3">
                            <label for="base_ussd_code" class="form-label">Base USSD Code</label>
                            <input type="text" class="form-control" id="base_ussd_code" name="base_ussd_code" value="{{ $bank->base_ussd_code }}">
                        </div>

                        <div class="mb-3">
                            <label for="transfer_ussd_template" class="form-label">Transfer USSD Template</label>
                            <input type="text" class="form-control" id="transfer_ussd_template" name="transfer_ussd_template" value="{{ $bank->transfer_ussd_template }}">
                        </div>

                        <div class="mb-3">
                            <label for="bank_id" class="form-label">Bank ID</label>
                            <input type="text" class="form-control" id="bank_id" name="bank_id" value="{{ $bank->bank_id }}">
                        </div>

                        <div class="mb-3">
                            <label for="nip_bank_code" class="form-label">NIP Bank Code</label>
                            <input type="text" class="form-control" id="nip_bank_code" name="nip_bank_code" value="{{ $bank->nip_bank_code }}">
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ $bank->status ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Active
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Bank</button>
                        <a href="{{ route('admin.bank.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('title')
        {{ $bank->name }} :: Bank Mgt.
    @endpush
@endsection
