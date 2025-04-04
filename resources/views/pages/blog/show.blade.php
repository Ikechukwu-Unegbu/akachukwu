@extends('layouts.new-ui')


@section('body')

   
@include('components.menu-navigation')
   
 <section class="bg-[#F9FAFB]">
         <!-- Container for the article -->
         <div class="max-w-4xl mx-auto p-6 bg-[#F9FAFB] ">

            <!-- Article title -->
            <h1 class="text-3xl font-bold text-gray-900">{{$post->title}}</h1>

            <!-- Category and Author Information -->
            <div class="mt-2 text-gray-600">
                <span class="block text-sm">Category: 
                    <strong>{{ $post->categories->pluck('name')->implode(', ') }}</strong>
                </span>
                <span class="block text-sm">Written by {{$post->author->name}}</span>
            </div>

            <!-- Featured Image -->
            <div class="my-6">
                <img src="{{ $post->featured_image ? asset($post->featured_image) : asset('images/blog-logo.png') }}" alt="Article Image" class="w-full h-64 object-cover rounded-lg">
            </div>

            <!-- Article body -->
            <div class="text-gray-700 leading-relaxed space-y-4">
                {!!$post->content!!}
            </div>

        </div>

        <div class=" max-w-4xl mx-auto p-6 bg-[#F9FAFB] shadow-lg mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Posts</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Post 1 -->
                @foreach($relatedPosts as $related)

                <div class="bg-[#F9FAFB] shadow-md rounded-lg overflow-hidden">
                    <img src="https://via.placeholder.com/400x200" alt="Post 1 Image" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <span class="text-sm text-gray-500">{{$related->title}}</span>
                        <h3 class="mt-2 text-lg font-semibold text-gray-800">{{$related->excerpt}}</h3>
                        <p class="mt-2 text-gray-600"></p>
                        <p class="mt-4 text-sm text-gray-400">{{$related->created_at->diffForHumans()}}</p>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

 </section>


@include('components.footer')


@endsection