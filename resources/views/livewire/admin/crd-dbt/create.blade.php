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
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                </select>
            </div>

            <button type="submit" id="submitButton" class="btn btn-primary float-right">Submit</button>
        </form>
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