@extends('layouts.admin.app')
@section('content')
<div>
    <x-admin.page-title title="Content Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Blog Post" status="true" />
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
                        <h3 class="mb-4" id="form-heading">Create New Post</h3>
                        <form action="{{ route('admin.post.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Title (Only for Blog) -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" required>
                            </div>

                            <div class="mb-3">
                                <label for="seo" class="form-label">SEO Tags</label>
                                <textarea class="form-control" id="seo" name="seo" rows="2" placeholder="Enter short description" required></textarea>
                                <small>This should be comma separated.</small>
                            </div>

                            <!-- Excerpt/Question -->
                            <div class="mb-3">
                                <label for="excerpt" class="form-label">Excerpt</label>
                                <textarea class="form-control" id="excerpt" name="excerpt" rows="2" placeholder="Enter short description" required></textarea>
                            </div>

                            <!-- Content/Answer -->
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="5" required>{{ old('content') }}</textarea>
                            </div>

                            <!-- Featured Image Upload / Image URL Tabs -->
                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <ul class="nav nav-tabs" id="imageTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-image" type="button" role="tab" aria-controls="upload-image" aria-selected="true">Upload Image</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="url-tab" data-bs-toggle="tab" data-bs-target="#image-url" type="button" role="tab" aria-controls="image-url" aria-selected="false">Add Image URL</button>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3" id="imageTabContent">
                                    <div class="tab-pane fade show active" id="upload-image" role="tabpanel" aria-labelledby="upload-tab">
                                        <input type="file" class="form-control" id="featured_image" name="image">
                                    </div>
                                    <div class="tab-pane fade" id="image-url" role="tabpanel" aria-labelledby="url-tab">
                                        <input type="url" class="form-control" id="image_url" name="image_url" placeholder="Enter image URL">
                                    </div>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="mb-3">
                                <label class="form-label">Categories</label>
                                <div class="form-check-group">
                                    @foreach($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="category_{{ $category->id }}" name="category_id[]" value="{{ $category->id }}">
                                        <label class="form-check-label" for="category_{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
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

                            <!-- Featured Post -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
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
    <!-- TinyMCE -->
    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>

    {{-- <script>
        tinymce.init({
            selector: 'textarea#content',
            plugins: 'lists link',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
            height: 400,
            menubar: false,
            branding: false,
        });

        document.querySelector('#post-form').addEventListener('submit', function () {
            tinymce.triggerSave(); // Ensure content is synced with textarea before submission
        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#content',
                height: 250,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | bold italic backcolor | \
                                        alignleft aligncenter alignright alignjustify | \
                                        bullist numlist outdent indent | removeformat | help',
                setup: function (editor) {
                    // Save content to textarea on change
                    editor.on('change', function () {
                        editor.save();
                    });
                }
            });
        });
    </script>
@endpush

@push('title')
    Settings / Roles & Permissions
@endpush
