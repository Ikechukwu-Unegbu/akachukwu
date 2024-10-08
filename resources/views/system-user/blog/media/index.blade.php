@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Media Manager</h1>

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
                    <input class="form-control" type="file" id="mediaFile" name="media[]" multiple required>
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
                                <img src="{{ asset('uploads/'.$media->path) }}" class="card-img-top" alt="Media Image">
                            @else
                                <video class="card-img-top" controls>
                                    <source src="{{ asset('uploads/'.$media->path) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $media->name }}</h5>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#viewMediaModal{{ $media->id }}">
                                        View
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteMediaModal{{ $media->id }}">
                                        Delete
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
                                        <img src="{{ asset('uploads/'.$media->path) }}" class="img-fluid" alt="Media Image">
                                    @else
                                        <video class="img-fluid" controls>
                                            <source src="{{ asset('uploads/'.$media->path) }}" type="video/mp4">
                                        </video>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

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
                                    <form action="{{ route('admin.media.delete', $media->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

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
