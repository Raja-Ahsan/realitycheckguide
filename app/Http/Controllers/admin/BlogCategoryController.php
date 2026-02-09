<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Auth;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:blog category-list|blogcategory-create|blogcategory-edit|blogcategory-delete', ['only' => ['index','store']]);
        $this->middleware('permission:blog category-create', ['only' => ['create','store']]);
        $this->middleware('permission:blog category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:blog category-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        if($request->ajax()){
            $query = BlogCategory::orderby('id', 'desc')->where('id', '>', 0);
            if($request['search'] != ""){
                $query->where('name', 'like', '%'. $request['search'] .'%');
            }
            if($request['status']!="All"){
                if($request['status']==2){
                    $request['status'] = 0;
                }
                $query->where('status', $request['status']);
            }
            $models = $query->paginate(1);
            return (string) view('admin.blogcategory.search', compact('models'));
        }
        $page_title = 'All BlogCategories';
        $models = BlogCategory::orderby('id', 'desc')->paginate(10);
        return View('admin.blogcategory.index', compact("models","page_title"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Add BlogCategory';
        return View('admin.blogcategory.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|max:100',
            'description' => 'max:250',
        ]);

        $model = new BlogCategory();
        $model->created_by = Auth::user()->id;
        $model->name = $request->name;
        $model->slug = \Str::slug($request->name);
        $model->description = $request->description;
        $model->save();

        return redirect()->route('blogcategory.index')->with('message', 'BlogCategory Added Successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(BlogCategory $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $page_title = 'Edit BlogCategory';
        $model = BlogCategory::where('slug', $slug)->first();
        return View('admin.blogcategory.edit', compact("model", "page_title"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $validator = $request->validate([
            'name' => 'required|max:100',
            'description' => 'max:250',
        ]);

        $update = BlogCategory::where('slug', $slug)->first();
        $update->name = $request->name;
        $update->slug = \Str::slug($request->name);
        $update->description = $request->description;
        $update->status = $request->status;
        $update->update();

        return redirect()->route('blogcategory.index')->with('message', 'BlogCategory Updated Successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $model = BlogCategory::where('slug', $slug)->first();
        if ($model) {
            $model->delete();
            return true;
        } else {
            return response()->json(['message' => 'Failed '], 404);
        }
    }
}
