<div class="mt-4 row">
    <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-start">
        <div class="dataTables_length">
            <small>Showing {{ $paginate->firstItem() ? $paginate->firstItem() : 0 }} to {{ $paginate->lastItem() }} of
                {{ $paginate->total() }} entries</small>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">
        <div class="dataTables_filter">
            <small>{{ $paginate->links() }} </small>
        </div>
    </div>
</div>
<style>
    .small.text-muted {
        display: none;
    }
</style>