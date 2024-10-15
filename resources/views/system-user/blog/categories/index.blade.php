@extends('layouts.admin.app')
@section('content')
<div>
    <x-admin.page-title title="Settings">
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $cate)
                                    <tr>
                                        <td>{{ $cate->id }}</td>
                                        <td>{{ $cate->name }}</td>
                                        <td>{{ $cate->type }}</td> <!-- Displaying the type -->
                                        <td>
                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $cate->id }}">
                                                Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{ $cate->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Modals for Edit and Delete (remain unchanged) -->
                                    ...
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
