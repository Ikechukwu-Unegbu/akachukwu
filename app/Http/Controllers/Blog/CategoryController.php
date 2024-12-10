<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{

      /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('view post category')) {
            abort(403, 'Unauthorized action.');
        }

        $categoriesQuery = Category::query();
    
        if ($request->has('type') && in_array($request->query('type'), ['media', 'blog', 'faq'])) {
            $categoriesQuery->where('type', $request->query('type'));
        }
    
        $categories = $categoriesQuery->get();
    
        return view('system-user.blog.categories.index', compact('categories'));
    }


    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create post category')) {
            abort(403, 'Unauthorized action.');
        }

       $request->validate([
            'type'=>'required|string',
            'name'=>'required|string',
            'description'=>'nullable'
       ]);
       Category::create($request->all());
       Session::flash('cate_success', 'New category successfully created.');
       return redirect()->back();
    }

    /**
     * Display the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Show the details of the specified category
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Show the form to edit the specified category
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit post category')) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'type' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);
    
        $category = Category::findOrFail($id);
        $category->update($request->all());
    
        Session::flash('success', 'Category successfully updated.');
        return redirect()->back();
    }
    

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('delete post category')) {
            abort(403, 'Unauthorized action.');
        }

        $category = Category::findOrFail($id);
        $category->delete();

        Session::flash('success', 'Category successfully deleted.');
        return redirect()->back();
    }

}
