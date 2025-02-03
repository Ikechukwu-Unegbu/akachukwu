@extends('layouts.admin.app')
@section('content')
<div>
    <x-admin.page-title title="Content Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Content Categories" status="true" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-admin.page-title>

    <section class="section">
        <!-- Filter Section -->
        <div class="mb-4">
            <form method="GET" action="{{ route('admin.category.index') }}">
                <div class="input-group">
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="" disabled selected>Filter by Type</option>
                        <option value="blog" {{ request('type') == 'blog' ? 'selected' : '' }}>Blog</option>
                        <option value="media" {{ request('type') == 'media' ? 'selected' : '' }}>Media</option>
                        <option value="faq" {{ request('type') == 'faq' ? 'selected' : '' }}>FAQ</option>
                    </select>
                    <button class="btn btn-primary" type="submit">Filter</button>
                </div>
            </form>
        </div>

        <!-- Categories Management Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card card-custom">
                    <div class="card-header">
                        <h5 class="card-title">Manage Categories</h5>
                        <button data-bs-toggle="modal" data-bs-target="#newcateModal" class="btn btn-primary float-end">Add New Category</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $cate)
                                    <tr>
                                        <td>{{ $cate->id }}</td>
                                        <td>{{ $cate->name }}</td>
                                        <td>{{ ucfirst($cate->type) }}</td> <!-- Displaying the type -->
                                        <td>{{ $cate->description }}</td> <!-- Displaying the description -->
                                        <td>
                                            @can('edit post category')
                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $cate->id }}">
                                                Edit
                                            </button>
                                            @endcan
                                            @can('delete post category')
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{ $cate->id }}">
                                                Delete
                                            </button>
                                            @endcan
                                        </td>
                                    </tr>
                                    @can('edit post category')
                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editCategoryModal{{ $cate->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $cate->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin.category.update', $cate->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editCategoryModalLabel{{ $cate->id }}">Edit Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="categoryName{{ $cate->id }}" class="form-label">Name</label>
                                                            <input type="text" class="form-control" id="categoryName{{ $cate->id }}" name="name" value="{{ $cate->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="categoryType{{ $cate->id }}" class="form-label">Type</label>
                                                            <select class="form-select" id="categoryType{{ $cate->id }}" name="type" required>
                                                                <option value="faq" {{ $cate->type == 'faq' ? 'selected' : '' }}>FAQ</option>
                                                                <option value="blog" {{ $cate->type == 'blog' ? 'selected' : '' }}>Blog</option>
                                                                <option value="media" {{ $cate->type == 'media' ? 'selected' : '' }}>Media</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="categoryDescription{{ $cate->id }}" class="form-label">Description</label>
                                                            <textarea class="form-control" id="categoryDescription{{ $cate->id }}" name="description" rows="3" required>{{ $cate->description }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endcan
                                    @can('delete post category')
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteCategoryModal{{ $cate->id }}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel{{ $cate->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteCategoryModalLabel{{ $cate->id }}">Delete Category</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete the category "<strong>{{ $cate->name }}</strong>"?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.category.destroy', $cate->id) }}" method="POST">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('system-user.blog.components._new_category_modal')

@endsection

@push('title')
    Content / Categories
@endpush
