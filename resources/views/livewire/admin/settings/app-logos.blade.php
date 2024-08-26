<div>
    <x-admin.page-title title="Manage App Logos.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="App Logos" />
        <x-admin.page-title-item subtitle="Logos" status="true" />
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
       
         
        <div class="container mt-5">
        <h2 class="mb-4">Upload App Images - Logos and Banners</h2>
        <form id="image-upload-form" wire:submit="saveImage">
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

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="row">
                <!-- Loop over this block for five images -->
                <div class="col-md-12 mb-3">
                    <!-- <label for="image1" class="form-label">Image 1</label> -->
                    <input type="file" class="form-control" wire:model="image" id="image1" name="image" accept="image/*">
                    <img id="preview-image1" src="#" alt="Preview Image 1" class="image-preview d-none">
                </div>
                <div class="mb-3">
                    <label for="exampleSelect" class="form-label">Select an type</label>
                    <select class="form-select" id="exampleSelect" name="type" wire:model="type">
                        <option selected>Choose...</option>
                        <option value="app_logo">Vastel Logo</option>
                        <option value="app_banner">App Banner</option>
                        <option value="mtn_logo">MTN Logo</option>
                        <option value="9mobile_logo">9mobile Logo</option>
                        <option value="glo_logo">Glo Logo</option>
                        <option value="airtel_logo">Airtel Logo</option>

                    </select>
                </div>
              
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <!-- images displayed -->
        <div class="container mt-5">
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $siteSetting->mtn_logo ?? 'https://via.placeholder.com/400x150?text=Image+1' }}" alt="Image 1" class="card-img-top">
                    <!-- <div class="card-body">
                        <h5 class="card-title">Card Title 1</h5>
                        <p class="card-text">Some description for the first card.</p>
                    </div> -->
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $siteSetting->airtel_logo ?? 'https://via.placeholder.com/400x150?text=Image+airtel' }}" alt="Image 2" class="card-img-top" onerror="this.src='https://via.placeholder.com/400x150?text=Default+Icon'">
                    <!-- <div class="card-body">
                        <h5 class="card-title">Card Title 2</h5>
                        <p class="card-text">Some description for the second card.</p>
                    </div> -->
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $siteSetting->glo_logo ?? 'https://via.placeholder.com/400x150?text=Image+glo' }}" alt="Image 3" class="card-img-top" onerror="this.src='https://via.placeholder.com/400x150?text=Default+Icon'">
                    <!-- <div class="card-body">
                        <h5 class="card-title">Card Title 3</h5>
                        <p class="card-text">Some description for the third card.</p>
                    </div> -->
                </div>
            </div>
            <!-- Card 4 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ data_get($siteSetting, '9mobile_logo', 'https://via.placeholder.com/400x150?text=Image+1') }}"  alt="Image 4" class="card-img-top" onerror="this.src='https://via.placeholder.com/400x150?text=Default+Icon'">
                    <!-- <div class="card-body">
                        <h5 class="card-title">Card Title 4</h5>
                        <p class="card-text">Some description for the fourth card.</p>
                    </div> -->
                </div>
            </div>
            <!-- Card 5 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $siteSetting->app_logo ?? 'https://via.placeholder.com/400x150?text=Image+glo' }}" alt="Image 5" class="card-img-top" onerror="this.src='https://via.placeholder.com/400x150?text=Default+Icon'">
                    <!-- <div class="card-body">
                        <h5 class="card-title">Card Title 5</h5>
                        <p class="card-text">Some description for the fifth card.</p>
                    </div> -->
                </div>
            </div>
            <!-- Card 6 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $siteSetting->app_banner ?? 'https://via.placeholder.com/400x150?text=Image+glo' }}" alt="Image 6" class="card-img-top" onerror="this.src='https://via.placeholder.com/400x150?text=Default+Icon'">
                    <!-- <div class="card-body">
                        <h5 class="card-title">Card Title 6</h5>
                        <p class="card-text">Some description for the sixth card.</p>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
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