@extends('layouts.admin.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<div class="container py-4">
    <h1 class="mb-4">Media Manager</h1>

    @can('create media')
    <!-- Upload Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Upload Media</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="mediaFile" class="form-label">Select Image or Video</label>
                    <input class="form-control" type="file" id="mediaFile" name="image" required>
                </div>
                <div class="mb-3">
                    <label for="mediaFile" class="form-label">Name</label>
                    <input class="form-control" type="text" id="mediaFile" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="mediaType" class="form-label">Media Type</label>
                    <select class="form-select" id="mediaType" name="media_type" required>
                        <option value="image">Image</option>
                        <option value="video">Video</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
    @endcan
    @can('view media')
    <!-- Media Library Section -->
    <div class="card">
        <div class="card-header">
            <h4>Media Library</h4>
        </div>
        <div class="card-body">
            <!-- Media Filters -->
            <div class="mb-3">
                <button class="btn btn-outline-primary btn-sm" data-filter="all">All</button>
                <button class="btn btn-outline-secondary btn-sm" data-filter="image">Images</button>
                <button class="btn btn-outline-info btn-sm" data-filter="video">Videos</button>
            </div>

            <!-- Media Grid -->
            <div class="row g-3" id="mediaGrid">
            @foreach($mediaFiles as $media)
                <div class="col-md-3 media-item" data-type="{{ $media->type }}">
                    <div class="card shadow-sm">
                        @if($media->type == 'image')
                            <img src="{{$media->path}}" class="card-img-top" alt="Media Image">
                        @else
                            <video class="card-img-top" controls>
                                <source src="{{ asset($media->path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $media->name }}</h5>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#viewMediaModal{{ $media->id }}">
                                <i class="fa-solid fa-eye"></i>
                                </button>
                                @can ('delete media')
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteMediaModal{{ $media->id }}">
                                <i class="fa-solid fa-trash-can"></i>
                                </button>
                                @endcan
                                <button class="btn btn-sm btn-info copy-url-btn" data-url="{{ $media->path }}" onclick="copyUrlToClipboard('{{ asset($media->path) }}')">
                                <i class="fa-solid fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View Media Modal -->
                <div class="modal fade" id="viewMediaModal{{ $media->id }}" tabindex="-1" aria-labelledby="viewMediaLabel{{ $media->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewMediaLabel{{ $media->id }}">View Media</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if($media->type == 'image')
                                    <img src="{{ $media->path }}" class="img-fluid" alt="Media Image">
                                @else
                                    <video class="img-fluid" controls>
                                        <source src="{{ $media->path }}" type="video/mp4">
                                    </video>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @can ('delete media')
                <!-- Delete Media Modal -->
                <div class="modal fade" id="deleteMediaModal{{ $media->id }}" tabindex="-1" aria-labelledby="deleteMediaLabel{{ $media->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteMediaLabel{{ $media->id }}">Delete Media</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete the media <strong>{{ $media->name }}</strong>?
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('admin.media.destroy', $media->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
            @endforeach
            </div>
        </div>
    </div>
    @endcan
</div>

<!-- Toast Element -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="copyToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                URL copied to clipboard!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    function copyUrlToClipboard(url) {
        navigator.clipboard.writeText(url).then(() => {
            // Show toast notification
            var toastEl = document.getElementById('copyToast');
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        }).catch(err => {
            console.error('Failed to copy URL: ', err);
        });
    }
</script>

<!-- Optional JS for Filtering (you can adjust or replace with custom JS) -->
<script>
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            document.querySelectorAll('.media-item').forEach(item => {
                if (filter === 'all' || item.getAttribute('data-type') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
