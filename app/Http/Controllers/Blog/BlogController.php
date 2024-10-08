<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    
    /**
     * Display a listing of the blog posts.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Retrieve all blog posts, optionally paginate
        $posts = Post::latest()->paginate(10);
        $categories = Category::all();
        
        // Return the view and pass the posts data to it
        return view('system-user.blog.posts.index')->with('posts', $posts)->with('categories', $categories);
    }

}
