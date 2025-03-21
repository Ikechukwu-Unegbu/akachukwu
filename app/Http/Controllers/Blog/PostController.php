<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StorePostRequest;
use App\Http\Requests\Blog\UpdatePostRequest;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\Blog\Tag;
use App\Services\Admin\Blog\Post\PostService;
use App\Services\Uploads\ImageService;
use Illuminate\Http\Request;

class PostController extends Controller
{



    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('query');
        $categories = Category::all();

        // Query to fetch posts with the related category type 'blog'
        $postsQuery = Post::whereHas('categories', function ($query) {
            $query->where('type', 'blog');
        });

        if ($searchQuery) {
            $posts = $postsQuery->where('title', 'LIKE', '%' . $searchQuery . '%')
                                ->latest()
                                ->paginate(10);
        } else {
            $posts = $postsQuery->latest()->paginate(10);
        }

        return view('system-user.blog.posts.posts-index')
            ->with('posts', $posts)
            ->with('categories', $categories)
            ->with('searchQuery', $searchQuery);
    }


    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('create post')) {
            abort(403, 'Unauthorized action.');
        }
        // Return a list of posts
        $categories = Category::where('type', 'blog')->get();
        return view('system-user.blog.posts.create')->with('categories', $categories);
    }

    /**
     * Fetch a post item for editing.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('edit post')) {
            abort(403, 'Unauthorized action.');
        }
        // Return a list of posts
        $post = Post::findOrFail($id);
        $categories = Category::where('type', 'blog')->get();
        return view('system-user.blog.posts.edit')->with('post', $post)->with('categories', $categories);
    }

    /**
     * Fetch a post item for editing.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('view post')) {
            abort(403, 'Unauthorized action.');
        }
        // Return a list of posts
        $post = Post::findOrFail($id);
        $categories = Category::where('type', 'blog')->get();
        return view('system-user.blog.posts.show')->with('post', $post)->with('categories', $categories);
    }

    /**
     * Store a newly created post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request, PostService $postService, ImageService $imageService)
    {
        if (!auth()->user()->can('create post')) {
            abort(403, 'Unauthorized action.');
        }

        $blog = $postService->storePost($request);
    
        if ($request->hasFile('image')) {
            $imageService->fileUploader($request, '/blog', $blog, 'featured_image');
        }
        

        if ($request->has('image_url') && !empty($request->image_url)) {
            $blog->featured_image = $request->image_url;
            $blog->save();
        }
    
        $blog->categories()->sync($request->input('category_id'));

        $tags = array_map('trim', explode(',', $request->input('seo')));

        foreach ($tags as $tagName) {
            $tag = Tag::create(['name' => $tagName, 'post_id'=>$blog->id]);
        }
        
    
        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
    }
    
    /**
     * Update the specified post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $id, PostService $postService, ImageService $imageService)
    {
        if (!auth()->user()->can('edit post')) {
            abort(403, 'Unauthorized action.');
        }

        $blog = Post::find($id);
        if (is_null($blog->id)) {
            return redirect()->back()->withErrors('Post ID is null.');
        }
    
        $postService->updatePost($request, $blog);

        if ($request->hasFile('image')) {
            $imageService->fileUploader($request, '/blog', $blog, 'featured_image');
        }

        $blog->categories()->sync($request->input('category_id'));

        Tag::where('post_id', $blog->id)->delete();
        $tags = array_map('trim', explode(',', $request->input('seo')));

        foreach ($tags as $tagName) {
            $tag = Tag::create(['name' => $tagName, 'post_id'=>$blog->id]);
        }
        

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }


    /**
     * Remove the specified post from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete the specified post
    }
}
