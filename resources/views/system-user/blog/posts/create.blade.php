@extends('layouts.admin.app')
@section('content')
<div>
    <x-admin.page-title title="Settings">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Roles & Permissions" status="true" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f8f9fa;
            }

            .main-content {
                padding: 20px;
            }

            .card-custom {
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
            }
        </style>
    </x-admin.page-title>

    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-custom p-4">
                        <h3 class="mb-4">Create New Post</h3>
                        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" required>
                            </div>

                            <!-- Slug -->
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="Enter slug" required>
                            </div>

                            <!-- Excerpt -->
                            <div class="mb-3">
                                <label for="excerpt" class="form-label">Excerpt</label>
                                <textarea class="form-control" id="excerpt" name="excerpt" rows="2" placeholder="Enter short description"></textarea>
                            </div>

                            <!-- Content -->
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="5" placeholder="Enter post content" required></textarea>
                            </div>

                            <!-- Featured Image -->
                            <div class="mb-3">
                                <label for="featured_image" class="form-label">Featured Image</label>
                                <input type="file" class="form-control" id="featured_image" name="featured_image">
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>

                            <!-- Category -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option selected disabled>Choose a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Featured Post -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="is_featured" name="is_featured">
                                <label class="form-check-label" for="is_featured">Feature this post</label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Create Post</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('system-user.blog.components._new_category_modal')

@endsection

@push('scripts')
    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/6ujt7ohjfqxztcoi2uv8nx6tk4tqxzan7cb3rfcvdhbxgxtm/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector: 'textarea#content', // Target the content textarea
            plugins: 'lists link image preview', // Add more TinyMCE plugins if needed
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview',
            height: 400, // Set the height of the editor
            menubar: false, // Disable menubar if not needed
            branding: false // Disable "Powered by TinyMCE"
        });
    </script>
@endpush

@push('title')
    Settings / Roles & Permissions
@endpush
