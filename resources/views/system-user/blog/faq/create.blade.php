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
                        <h3 class="mb-4" id="form-heading">Create FAQ</h3>
                        <form action="{{ route('admin.faq.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf


                            <!-- Excerpt/Question -->
                            <div class="mb-3">
                                <label for="excerpt" id="excerpt-label" class="form-label">Excerpt</label>
                                <textarea class="form-control" id="excerpt" name="excerpt" rows="2" placeholder="Enter short description"></textarea>
                            </div>

                            <!-- Content/Answer -->
                            <div class="mb-3">
                                <label for="content" id="content-label" class="form-label">Content</label>
                                <textarea class="form-control" id="" name="content" rows="5" placeholder="Enter post content"></textarea>
                            </div>

                            <!-- Category (Visible for both Blog and FAQ) -->
                            <div class="mb-3" id="categories-field">
                                <label class="form-label">Categories</label>
                                <div>
                                    @foreach($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="category_{{ $category->id }}" name="category_id[]" value="{{ $category->id }}">
                                        <label class="form-check-label" for="category_{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-link" id="new-category-btn" data-bs-toggle="modal" data-bs-target="#newCategoryModal">+ Add New Category</button>
                            </div>

                            <!-- Status (For both Blog and FAQ) -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>

                            <!-- Featured Post (Only for Blog) -->
                            <div class="form-check mb-3" id="featured-post-field">
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
    <script src="https://cdn.tiny.cloud/1/api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

@endpush

@push('title')
    Settings / Roles & Permissions
@endpush
