<div class="datatable-top">
    <div class="datatable-dropdown">
        <label>
            <select class="datatable-selector" {{ $wirePageAction }}>
                @foreach ($perPages as $perPage)
                <option value="{{ $perPage }}">{{ $perPage }}</option>
                @endforeach
            </select>
        </label>
    </div>
    <div class="datatable-search">
        <input class="datatable-input" placeholder="Search..." {{ $wireSearchAction }} type="search" title="Search...">
    </div>
</div>

<style>
    /* Styles for screens smaller than 600px (phones) */
    @media only screen and (max-width: 600px) {
        .datatable-top {
            display: flex !important;
            justify-content: center !important;
        }
    }

    /* Styles for screens between 600px and 900px (tablets) */
    @media only screen and (min-width: 600px) and (max-width: 900px) {
        .datatable-top {
            display: flex !important;
            justify-content: center !important;
        }
    }
</style>