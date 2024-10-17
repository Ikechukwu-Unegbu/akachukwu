@extends('layouts.admin.app')

@section('content')
<div>
    <x-admin.page-title title="View Post">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Blog Post" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card p-4">
                        <!-- Post Title -->
                        <h3>{{ $post->title }}</h3>

                        <!-- Featured Image -->
                        @if($post->featured_image)
                            <p><strong>Featured Image:</strong></p>
                            <img src="{{ $post->featured_image }}" alt="Featured Image" style="max-width: 100%;">
                        @else
                            <p>No featured image.</p>
                        @endif

                        <!-- Excerpt -->
                        <p><strong>Excerpt:</strong> {{ $post->excerpt }}</p>

                        <!-- Content -->
                        <p><strong>Content:</strong></p>
                        <p>{!! nl2br(e($post->content)) !!}</p>

                        <!-- Categories -->
                        <p><strong>Categories:</strong></p>
                        <ul>
                            @foreach($post->categories as $category)
                                <li>{{ $category->name }}</li>
                            @endforeach
                        </ul>

                        <!-- Featured Post -->
                        <p><strong>Featured Post:</strong> {{ $post->is_featured ? 'Yes' : 'No' }}</p>

                        <!-- Status -->
                        <p><strong>Status:</strong> {{ ucfirst($post->status) }}</p>

                        <!-- Edit Button -->
                        <a href="{{ route('admin.post.edit', $post->id) }}" class="btn btn-primary mt-4">Edit Post</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
