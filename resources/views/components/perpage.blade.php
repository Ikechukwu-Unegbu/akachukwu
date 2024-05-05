<div class="datatable">
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
            <input class="datatable-input" type="text" placeholder="Search..." {{ $wireSearchAction }}>
        </div>
        @endisset
    </div>
</div>

<style>
    .datatable {
        padding: 2px;
    }

    .datatable-top,
    .datatable-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .datatable-selector,
    .datatable-input {
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .datatable-selector {
        margin-right: 10px;
    }

    .datatable-input {
        flex: 1;
        margin-top: 10px;
    }

    .pagination {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: center;
    }

    .pagination li {
        display: inline;
        margin-right: 5px;
    }

    .pagination li a {
        text-decoration: none;
        padding: 6px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        color: #333;
    }

    .pagination li a:hover {
        background-color: #f0f0f0;
    }

    @media only screen and (max-width: 600px) {

        .datatable-selector,
        .datatable-input {
            width: 100%;
            margin-right: 0;
        }

        .datatable-input {
            margin-top: 0;
        }

        .datatable-top {
            justify-content: center;
        }
    }
</style>