@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Blog Posts</h1>
        <a href="{{ route('admin.post.create') }}" class="btn btn-primary">Create New Post</a>
    </div>

    <div class="container mb-4">
    <form action="" method="GET" class="d-flex align-items-center">
        <input type="text" name="query" class="form-control me-2 search-input" value="{{$searchQuery}}" placeholder="Search blogs..." aria-label="Search" value="{{ request()->query('query') }}">
        <button class="btn btn-primary" type="submit">Search</button>
    </form>
</div>

    <div class="card">
        <div class="card-body">
            @if($posts->isEmpty())
                <p>No posts available.</p>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                        
                            <th>Status</th>
                            <th>Categories</th>
                            <th>Clicks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->title }}</td>
                           
                                <td>{{ ucfirst($post->status) }}</td>
                                <td>
                                    @foreach($post->categories as $category)
                                        <span class="badge bg-secondary">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $post->clicks }}</td>
                                <td style="display: flex;gap:1rem; ">
                                    <a href="{{ route('admin.post.show', $post->id) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('admin.post.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
