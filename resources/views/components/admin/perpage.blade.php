<div class="datatable-top">
    <div class="datatable-dropdown">
            <label>
                <select class="datatable-selector" {{ $wirePageAction }}>
                    @foreach ($perPages as $perPage)
                    <option value="{{ $perPage }}">{{ $perPage }}</option>
                    @endforeach
                </select> entries per page
            </label>
        </div>
    <div class="datatable-search">
        <input class="datatable-input" placeholder="Search..." {{ $wireSearchAction }} type="search" title="Search...">
    </div>
</div>