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
        $searchQuery = $request->input('query');
        $categories = Category::all();

        if ($searchQuery) {
            $posts = Post::where('title', 'LIKE', '%' . $searchQuery . '%')
                        ->latest()
                        ->paginate(10);
        } else {
            $posts = Post::latest()->paginate(10);
        }

        return view('system-user.blog.posts.index')
            ->with('posts', $posts)
            ->with('categories', $categories)
            ->with('searchQuery', $searchQuery);
    }


}
