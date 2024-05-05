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
    @isset($wireSearchAction)
    <div class="datatable-search">
        <input class="datatable-input" placeholder="Search..." {{ $wireSearchAction }} type="search" title="Search...">
    </div>
    @endisset
</div>

<style>
    .datatable-top,
    .datatable-bottom {
        padding: 8px 10px;
    }

    .datatable-top::after,
    .datatable-bottom::after {
        clear: both;
        content: " ";
        display: table;
    }

    .datatable-selector,
    .datatable-input {
        padding: 6px;
    }

    .datatable-top {
        display: flex;
        justify-content: space-between;
        max-width: 500px;
    }

    @media only screen and (max-width: 900px) {
        .datatable-top {
            flex-wrap: wrap;
            justify-content: center;
        }
    }

    @media only screen and (max-width: 375px) {
        .datatable-top {
            max-width: 100%;
        }
    }
</style>