

<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Electricity" link="{{ route('admin.utility.electricity') }}" />
        <x-admin.page-title-item subtitle="Edit Electricity" status="true" />
    </x-admin.page-title>

    <section class="section">
        <form action="{{ route('admin.utility.electricity.update', [$vendor->id, $electricity->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5 class="p-0 m-0 card-title">
                        {{ $vendor->name }} ({{ $electricity->disco_name }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mt-3 mb-3 form-group">
                                <label for="disco_id" class="form-label">API ID / Disco ID</label>
                                <input type="text" name="disco_id" class="form-control @error('disco_id') is-invalid @enderror" value="{{ $disco_id }}"
                                 name="disco_id">
                                @error('disco_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-4 form-group">
                                <label for="disco_name" class="mb-2 form-label">Disco Name</label>
                                <input type="text" name="disco_name" class="form-control @error('disco_name') is-invalid @enderror" name="disco_name" value="{{ $disco_name }}">
                                @error('disco_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="discount" class="mb-2 form-label">Discount %</label>
                                <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror" name="discount" value="{{ $discount }}">
                                @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="image" class="mb-2 form-label">Image</label>
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" name="image" onchange="loadFile(event)">
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-4">
                                <img src="{{ $electricity->image_url }}" width="80" id="preview_image" class="img-fluid img-thumbnail" alt="course image" id="output" />
                                
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" name="status" @checked($status)>
                                <label class="form-check-label" for="status">Status</label>
                            </div>
                            @error('status') <span class="text-danger" style="font-size: .875em">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <a href="{{ route('admin.utility.electricity') }}{{ "?vendor={$vendor->id}" }}" class="btn btn-md btn-danger">
                            <i class="bx bx-x-circle"></i> 
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-md">
                            <div id="update">
                                <i class="bx bx-refresh"></i>  Update
                            </div>

                            <div class="d-none" id="updating">  
                                <i class="bx bx-loader-circle bx-spin"></i>  Updating...
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

@push('title')
    Utilities / Electricity / Edit - Electricity
@endpush

@push('scripts')
<script>
    var loadFile = function(event) {
        var image = document.getElementById('preview_image');
        image.src = URL.createObjectURL(event.target.files[0]);
    };
</script>
@endpush