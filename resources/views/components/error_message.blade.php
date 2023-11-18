<div>
    <!-- Display Validation Errors -->
    @if($errors->any())
        <div style="color: red;">
            <strong>Validation error(s):</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Display Session Messages -->
    @if(session('success'))
        <div style="color: green;">
            <strong>Success:</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="color: red;">
            <strong>Error:</strong> {{ session('error') }}
        </div>
    @endif
</div>
