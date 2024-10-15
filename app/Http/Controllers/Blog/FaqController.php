<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Services\Admin\Blog\Post\PostService;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Post::whereHas('categories', function ($query) {
            $query->where('type', 'faq');
        })->paginate(10);

        return view('system-user.blog.faq.index', compact('faqs'));
    }

    public function create()
    {
        $categories = Category::where('type', 'faq')->get();
        return view('system-user.blog.faq.create')->with('categories', $categories);
    }

    public function store(Request $request, PostService $postService)
    {
        $blog = $postService->storePost($request);
    
        $blog->categories()->sync($request->input('category_id'));
    
        return redirect()->route('admin.faq.index')->with('success', 'FAQ post created successfully.');
    }
    
}
