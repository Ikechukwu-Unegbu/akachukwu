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

            /* .sidebar {
                height: 100vh;
                background-color: #343a40;
            }

            .sidebar a {
                color: white;
                text-decoration: none;
                padding: 15px;
                display: block;
                transition: all 0.3s;
            }

            .sidebar a:hover {
                background-color: #495057;
            } */

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


        <!-- Blog Analytics Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h5>Blog Analytics</h5>
            </div>
        </div>
        <div class="row mb-4">
            <!-- Total Posts -->
            <div class="col-md-3">
                <div class="card card-custom text-center">
                    <div class="card-body">
                        <h6>Total Posts</h6>
                        <h3>120</h3>
                    </div>
                </div>
            </div>
            <!-- Total Categories -->
            <div class="col-md-3">
                <div class="card card-custom text-center">
                    <div class="card-body">
                        <h6>Total Categories</h6>
                        <h3>8</h3>
                    </div>
                </div>
            </div>
            <!-- Total Views -->
            <div class="col-md-3">
                <div class="card card-custom text-center">
                    <div class="card-body">
                        <h6>Total Views</h6>
                        <h3>45,000</h3>
                    </div>
                </div>
            </div>
            <!-- Total Comments -->
            <div class="col-md-3">
                <div class="card card-custom text-center">
                    <div class="card-body">
                        <h6>Total Comments</h6>
                        <h3>320</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Chart Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card card-custom">
                    <div class="card-header">
                        <h5 class="card-title">Post Views Over Time</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="viewsChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-custom">
                    <div class="card-header">
                        <h5 class="card-title">Comments Over Time</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="commentsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts Management Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card card-custom">
                    <div class="card-header">
                        <h5 class="card-title">Manage Posts</h5>
                        <a href="#" class="btn btn-primary float-end">Add New Post</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Introduction to Bootstrap 5</td>
                                    <td>Web Development</td>
                                    <td>2024-10-05</td>
                                    <td>
                                        <button class="btn btn-success btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                                <!-- More rows as necessary -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $cate)
    <tr>
        <td>{{ $cate->id }}</td>
        <td>{{ $cate->name }}</td>
        <td>
            <!-- Edit Button -->
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $cate->id }}">
                Edit
            </button>

            <!-- Delete Button -->
            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{ $cate->id }}">
                Delete
            </button>
        </td>
    </tr>

    <!-- Edit Modal -->
    <div class="modal fade" id="editCategoryModal{{ $cate->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $cate->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel{{ $cate->id }}">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.category.update', $cate->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryName{{ $cate->id }}" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="categoryName{{ $cate->id }}" name="name" value="{{ $cate->name }}" required>
                        </div>
                        <!-- Add other form fields as needed -->
                            <!-- Type Select Input -->
                        <div class="mb-3">
                            <label for="categoryType" class="form-label">Category Type</label>
                            <select class="form-select" id="categoryType" name="type" required>
                            <option selected disabled>Choose a type</option>
                            <option value="category">Category</option>
                            <option value="blog">Blog</option>
                            <!-- <option value="type3">Type 3</option> -->
                            </select>
                        </div>

                        <!-- Description Input -->
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="categoryDescription" rows="3" placeholder="Enter description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteCategoryModal{{ $cate->id }}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel{{ $cate->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel{{ $cate->id }}">Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the category <strong>{{ $cate->name }}</strong>?
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
@endforeach

                                <!-- More rows as necessary -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    
    </section>

</div>

@include('system-user.blog.components._new_category_modal')

<!-- Chart.js Scripts -->
<script>
    var ctxViews = document.getElementById('viewsChart').getContext('2d');
    var viewsChart = new Chart(ctxViews, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May'],
            datasets: [{
                label: 'Post Views',
                data: [12000, 15000, 13000, 18000, 21000],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctxComments = document.getElementById('commentsChart').getContext('2d');
    var commentsChart = new Chart(ctxComments, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May'],
            datasets: [{
                label: 'Comments',
                data: [300, 400, 500, 350, 450],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection
@push('title')
    Settings / Roles & Permissions
@endpush
