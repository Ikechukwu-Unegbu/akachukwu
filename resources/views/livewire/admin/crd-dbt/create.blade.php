<div>
    <x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="HR Mgt." />
        <x-admin.page-title-item subtitle="Users" status="true" />
    </x-admin.page-title>

    <section class="section">
         <form method="POST" id="myForm" action={{route('admin.crdt-dbt.store')}}>
            @csrf 
            <!-- Amount Input Field -->
            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            <div class="mb-3">
                <label for="amountInput" class="form-label">Amount</label>
                <input type="text" name="amount" class="form-control" id="amountInput" placeholder="Enter amount">
            </div>

            <!-- Reason Textarea -->
            <div class="mb-3">
                <label for="reasonTextarea" class="form-label">Reason</label>
                <textarea name="reason" class="form-control" id="reasonTextarea" rows="3" placeholder="Enter reason"></textarea>
            </div>

            <!-- Username Input Field -->
            <div class="mb-3">
                <label for="usernameInput" class="form-label">Username</label>
                <input type="text" name="username"  class="form-control" value="{{$username}}" id="usernameInput" placeholder="Enter username">
            </div>

            <!-- Dropdown -->
            <div class="mb-3">
                <label for="dropdownSelect" class="form-label">Select</label>
                <select name="action" class="form-select" id="dropdownSelect">
                    <option selected>Select one...</option>
                    @if(auth()->user()->can('can debit'))
                    <option value="debit">Debit</option>
                    @endif 
                    @if(auth()->user()->can('can top-up'))
                    <option value="credit">Credit</option>
                    @endif 
                </select>
            </div>

            <button type="submit" id="submitButton" class="btn btn-primary float-right">Submit</button>
        </form>

        <div class="card mt-5">
            <div class="card-header">
                <h4 class="card-title">Wallet History</h4>
            </div>
            <div class="card-body">                        
                <x-table>
                    <x-table-header :headers="['#', 'Reference', 'Gateway', 'Amount', 'Date', 'Status']" />
                    <x-table-body>
                        @forelse ($walletHistories as $wallet_transaction)
                        <tr>
                            <th scope="row">{{ $loop->index + 1 }}</th>
                            <td>
                                <small>{{ $wallet_transaction->reference_id }}</small>    
                            </td>
                            <td>{{ Str::title($wallet_transaction->gateway_type) }}</td>
                            <td>₦ {{ number_format($wallet_transaction->amount, 2) }}</td>
                            <td>
                                @php $createdAt = \Carbon\Carbon::parse($wallet_transaction->created_at); @endphp
                                <small>{{ $createdAt->format('M d, Y. h:ia') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $wallet_transaction->status ? 'success' : 'danger' }}">
                                    {{ $wallet_transaction->status ? 'Successful' : 'Failed' }}
                                    @if($wallet_transaction->gateway_type==='Vastel' && $wallet_transaction->type == false)
                                        <small class="text-xs">Debit</small>
                                    @endif 
                                </span>
                            </td>
                        </tr>
                        @if ($loop->last)
                            <tr>
                                <td colspan="7">
                                    <a href="{{ route('admin.wallet.history', $user->username) }}" class="btn btn-sm btn-primary">Show More</a>
                                </td>
                            </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="7">No Records Found!</td>
                        </tr>
                        @endforelse
                    </x-table-body>
                </x-table>                            
            </div>
        </div>
    </section>

    <script>
    document.getElementById('myForm').addEventListener('submit', function() {
        document.getElementById('submitButton').disabled = true;
    });
</script>
</div>
@push('title')
    Credit or Debit User - {{$username}}
@endpush