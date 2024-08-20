<div>
    <x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Site Settings" />
        <x-admin.page-title-item subtitle="Users" status="true" />
        <style>
        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 1px solid #ddd;
            margin-top: 10px;
        }
    </style>
    </x-admin.page-title>

    <section class="section">
       
                    @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="container mt-5">
        <h2 class="mb-4">Upload Images</h2>
        <form id="image-upload-form">
            <div class="row">
                <!-- Loop over this block for five images -->
                <div class="col-md-4 mb-3">
                    <label for="image1" class="form-label">Image 1</label>
                    <input type="file" class="form-control" id="image1" name="images[]" accept="image/*">
                    <img id="preview-image1" src="#" alt="Preview Image 1" class="image-preview d-none">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="image2" class="form-label">Image 2</label>
                    <input type="file" class="form-control" id="image2" name="images[]" accept="image/*">
                    <img id="preview-image2" src="#" alt="Preview Image 2" class="image-preview d-none">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="image3" class="form-label">Image 3</label>
                    <input type="file" class="form-control" id="image3" name="images[]" accept="image/*">
                    <img id="preview-image3" src="#" alt="Preview Image 3" class="image-preview d-none">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="image4" class="form-label">Image 4</label>
                    <input type="file" class="form-control" id="image4" name="images[]" accept="image/*">
                    <img id="preview-image4" src="#" alt="Preview Image 4" class="image-preview d-none">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="image5" class="form-label">Image 5</label>
                    <input type="file" class="form-control" id="image5" name="images[]" accept="image/*">
                    <img id="preview-image5" src="#" alt="Preview Image 5" class="image-preview d-none">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const imageInputs = document.querySelectorAll('input[type="file"]');
            
            imageInputs.forEach((input, index) => {
                input.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewImage = document.getElementById(`preview-image${index + 1}`);
                            previewImage.src = e.target.result;
                            previewImage.classList.remove('d-none');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            });
        });
    </script>
</div>
@push('title')
App Logos 
@endpush