<?php 
namespace App\Services\Admin\Blog\Post;

use App\Models\Blog\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostService{

    public function storePost(Request $request)
    {

        $title = $request->input('model') === 'faq' ? 'Faq ' . now()->format('Y-m-d H:i:s') : $request->input('title');

        $post = Post::create([
            'title' => $title,
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'status' => $request->input('status'),
            'is_featured' => $request->input('is_featured') ? 1 : 0,
            'author_id' => Auth::user()->id,
            'slug' => Str::slug($title),
        ]);

        return $post;
    }

    public function updatePost(Request $request, Post $post)
    {
        $post->update([
            'title' => $request->input('title'),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'status' => $request->input('status'),
            'is_featured' => $request->input('is_featured') ? 1 : 0,
            'slug' => Str::slug($request->input('title')),
        ]);

        return $post;
    }

}