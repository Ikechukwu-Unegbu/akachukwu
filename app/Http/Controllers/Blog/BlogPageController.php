<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use Illuminate\Http\Request;

class BlogPageController extends Controller
{
    public function index(Request $request)
    {
        $blogPostsQuery = Post::whereHas('categories', function ($query) {
            $query->where('type', 'blog');
        });
    
        if ($request->has('category') && !empty($request->query('category'))) {
            $blogPostsQuery->whereHas('categories', function ($query) use ($request) {
                $query->where('slug', $request->query('category'));
            });
        }
    
        $blogPosts = $blogPostsQuery->paginate(20);
        $featured = Post::where('is_featured', true)->first();
    
        return view('pages.blog.index')
            ->with('blogPosts', $blogPosts)
            ->with('featured', $featured);
    }
    
    

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $post->clicks = $post->clicks++;
        $post->save();
    
        $relatedPosts = Post::whereHas('categories', function ($query) use ($post) {
            $query->whereIn('categories.id', $post->categories->pluck('id'));
        })->where('id', '!=', $post->id)->get();
    
        return view('pages.blog.show', compact('post', 'relatedPosts'));
    }
    
}
