@extends('layouts.new-ui')


@section('body')

   
    @include('components.menu-navigation')
   
    <section class="py-16" style="background-color: #F9FAFB;">
        <div class="container mx-auto px-4 bg-red" >
            <!-- Featured Article -->
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-8 mb-12">
                <a href="{{route('blog.show', [$featured->slug])}}" class="bg-[#F9FAFB] p-5 rounded-lg flex flex-col md:flex-row  md:h-[60vh] justify-between items-center">
                    <img src="{{ $featured->featured_image ? asset($featured->featured_image) : asset('images/blog-logo.png') }}" 
                            alt="Featured Article Image" 
                            class="w-full h-64 object-cover rounded-t-lg">

                    <div class="p-6 text-left w-full">
                        <p class="text-sm text-gray-500 uppercase mb-2">{{$featured->author->name}}</p>
                        <h3 class="text-2xl font-semibold mb-4">{{$featured->title}}</h3>
                        <p class="text-gray-600 mb-4">
                           {!!$featured->excerpt!!}
                        </p>
                        <p class="text-sm text-gray-400">{{$featured->created_at->diffForHumans()}}</p>
                    </div>
                </a>
            </div>

            <!-- Grid of Articles -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Article 1 -->
                @foreach($blogPosts as $post)
                <a href="{{route('blog.show', [$post->slug])}}" class="bg-[#F9FAFB]  rounded-lg shadow-lg">
                <img src="{{ $post->featured_image ? asset($post->featured_image) : asset('images/blog-logo.png') }}" 
                        alt="Article Image" 
                        class="w-full h-48 object-cover rounded-t-lg">

                    <div class="p-6">
                        <p class="text-sm text-gray-500 uppercase mb-2">{{$post->author->name}}</p>
                        <h4 class="text-xl font-semibold mb-4">
                            {!!$post->title!!}
                        </h4>
                        <p class="text-sm text-gray-400">{{$post->created_at->diffForHumans()}}</p>
                    </div>
                </a>
                @endforeach 

         
            </div>
            <div class="flex justify-center items-center mt-[2rem]">
            {{$blogPosts->links()}}
            </div>
          
        </div>
    </section>

    @include('components.footer')


@endsection