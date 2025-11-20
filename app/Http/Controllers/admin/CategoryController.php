<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:services-list|services-create|services-edit|services-delete', ['only' => ['index','store']]);
        $this->middleware('permission:services-create', ['only' => ['create','store']]);
        $this->middleware('permission:services-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:services-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        if($request->ajax()){
            $query = Category::orderby('id', 'ASC');
            if($request['search'] != ""){
                $query->where('title', 'like', '%'. $request['search'] .'%');
            }
            if($request['status']!="All"){
                if($request['status']==2){
                    $request['status'] = 0;
                }
                $query->where('status', $request['status']);
            }
            $models = $query->paginate(10);
            return (string) view('admin.category.search', compact('models'));
        }
        $page_title = 'All Career Categories';
        $models = Category::orderby('id', 'ASC')->paginate(10);
        return view('admin.category.index', compact("models","page_title"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Add Career Category';
        $categories = Category::orderby('id', 'desc')->where('status', 1)->get();
        return View('admin.category.create', compact('page_title','categories'));
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
            'title' => 'required',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $model = new Category();
		
		if (isset($request->image)) {
            $photo = date('YmdHis').'.'.$request->file('image')->getClientOriginalExtension();
            $request->image->move(public_path('/admin/assets/images/categories'), $photo);
            $model->image = $photo;
        }

        $model->created_by = Auth::user()->id;
        $model->title = $request->title;
        $model->subtitle = $request->subtitle ?? null;
        $model->description = $request->description ?? null;
        $model->slug = \Str::slug($request->title);
        $model->status = $request->status ?? 1;
        
        // Handle discover points - convert array to JSON
        if ($request->has('discover_points') && is_array($request->discover_points)) {
            $discoverPoints = array_filter($request->discover_points); // Remove empty values
            $model->discover_points = !empty($discoverPoints) ? json_encode($discoverPoints) : null;
        }
        
        $model->save();

        return redirect()->route('services.index')->with('message', 'Category Added Successfully !');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
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
        $page_title = 'Edit Career Category';
        $model = Category::where('slug', $slug)->first();
        $categories = Category::orderby('id', 'desc')->where('status', 1)->get();
        return View('admin.category.edit', compact("model", "page_title",'categories'));
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
            'title' => 'required|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $update = Category::where('slug', $slug)->first();
		
		if (isset($request->image)) {
            // Delete old image if exists
            if($update->image && file_exists(public_path('/admin/assets/images/categories/'.$update->image))) {
                unlink(public_path('/admin/assets/images/categories/'.$update->image));
            }
            $photo = date('YmdHis').'.'.$request->file('image')->getClientOriginalExtension();
            $request->image->move(public_path('/admin/assets/images/categories'), $photo);
            $update->image = $photo;
        }

        $update->title = $request->title;
        $update->subtitle = $request->subtitle ?? null;
        $update->description = $request->description ?? null;
        $update->slug = \Str::slug($request->title);
        $update->status = $request->status ?? 1;
        
        // Handle discover points - convert array to JSON
        if ($request->has('discover_points') && is_array($request->discover_points)) {
            $discoverPoints = array_filter($request->discover_points); // Remove empty values
            $update->discover_points = !empty($discoverPoints) ? json_encode($discoverPoints) : null;
        }
        
        $update->update();

        return redirect()->route('services.index')->with('message', 'Category Updated Successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $model = Category::where('slug', $slug)->first();
        if ($model) {
            $model->delete();
            return true;
        } else {
            return response()->json(['message' => 'Failed '], 404);
        }
    }
}
