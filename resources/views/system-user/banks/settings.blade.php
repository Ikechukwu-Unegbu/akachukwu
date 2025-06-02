@extends('layouts.admin.app')
@section('content')
    <x-admin.page-title title="BanksSettings">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Bank Settings" />
    </x-admin.page-title>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="p-1 m-1 card-title">Bank Settings</h5>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bank Configuration</h3>
            </div>
            <form action="{{ route('admin.bank.settings.update') }}" method="POST" class="mt-4">
            <div class="card-body">
                    @csrf

                    <div class="mb-4">
                        <h5>Default Banks</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="palm_pay" checked disabled>
                            <label class="form-check-label" for="palm_pay">
                                PalmPay (Default - Cannot be modified)
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Monnify Banks</h5>
                        <p>Select which Monnify banks should be available:</p>

                        @foreach ($monnifyBanks as $bank)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="enabled_monnify_banks[]"
                                    value="{{ $bank->id }}" id="bank_{{ $bank->id }}"
                                    {{ in_array($bank->id, $enabledBanks) ? 'checked' : '' }}>
                                <label class="form-check-label" for="bank_{{ $bank->id }}">
                                    {{ $bank->id }} {{ $bank->name }} ({{ $bank->code }})
                                </label>
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Configuration</button>
                </div>
            </form>
        </div>

    </section>

    @push('title')
        Bank Mgt. :: Settings
    @endpush
@endsection
