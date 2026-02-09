<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MemberDirectory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\NewMemberDirectorySubmitted;
use App\Notifications\MemberDirectoryUpdatedNotification;
use Symfony\Contracts\Service\Attribute\Required;

class MemberDirectoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
    {
        $this->middleware('permission:member_directory-list|member_directory-create|member_directory-edit|member_directory-delete', ['only' => ['index','store']]);
        $this->middleware('permission:member_directory-create', ['only' => ['create','store']]);
        $this->middleware('permission:member_directory-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:member_directory-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user

        $query = MemberDirectory::orderBy('id', 'desc');

        // Only show records belonging to the current user if not Admin
        if (in_array($user->role, ['Member', 'EPC Developer'])) {
            $query->where('created_by', $user->id);
        }

        // Handle AJAX requests
        if ($request->ajax()) {
            if (!empty($request->search)) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            if (!empty($request->status) && $request->status != 'All') {
                $status = ($request->status == 2) ? 0 : $request->status;
                $query->where('status', $status);
            }

            $models = $query->paginate(10);
            return (string) view('admin.member_directory.search', compact('models'));
        }

        //  Normal page load
        $page_title = 'All Members';
        $models = $query->paginate(10);
        return view('admin.member_directory.index', compact('models', 'page_title'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Add Member';
        $categories = Category::orderby('id', 'desc')->where('status', 1)->get();
        return view('admin.member_directory.create', compact('page_title','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|array|max:3',
            'category_id.*' => 'exists:categories,id',
           /*  'address' => 'required|max:255', 
            'email' => 'required|email|max:255', */
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,avif,svg|max:2048',
            'name' => 'required|max:255',
            'phone_no' => 'required|max:20',
            /* 'hear_about_us' => 'required|max:255', */
        ]); 

        $model = new MemberDirectory();

        if ($request->hasFile('image')) {
            $photo = date('YmdHis') . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('/admin/assets/images/member_directory'), $photo);
            $model->image = $photo;
        }

        $model->created_by = Auth::id();
        $model->category_id = json_encode($request->category_id);
        $model->title = $request->title;
        $model->slug = Str::slug($request->title);
        $model->description = $request->description;
        $model->url = $request->url;
        $model->email = $request->email;
        $model->address = $request->address;
        $model->facebook = $request->facebook;
        $model->name = $request->name;
        $model->phone_no = $request->phone_no;
        $model->hear_about_us = $request->hear_about_us;

        $model->save();

        // 🔔 Notify Admins
        if (Auth::user()->hasRole('Member') || Auth::user()->hasRole('EPC Developer')) {
            $admins = \App\Models\User::where('id', 1)
            ->orWhere('email', 'asjadmmc67@gmail.com')
            ->where('id', '!=', Auth::user()->id)
            ->get();

            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\NewMemberDirectorySubmitted($model, Auth::user()));
            }
        }

        return redirect()->route('member_directory.index')->with('message', 'Member Directory Added Successfully!');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MemberDirectory  $memberDirectory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_title = "Show Details";
        $member = MemberDirectory::where('id', $id)->firstOrFail();
        $categories = Category::where('status', 1)->get();

        // Mark notification as read if accessed via notification
        /* if (auth()->user()) {
            $notification = auth()->user()->unreadNotifications()
                ->where('data->member_id', $id)
                ->first();
            if ($notification) {
                $notification->markAsRead();
            }
        } */

        return view('admin.member_directory.show', compact('member', 'categories', 'page_title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MemberDirectory  $memberDirectory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = 'Edit Member Directory';
        $model = MemberDirectory::where('id', $id)->first();
        $categories = Category::orderby('id', 'desc')->where('status', 1)->get();

        if (auth()->user()) {
            $notification = auth()->user()->unreadNotifications()
                ->where('data->member_id', $id)
                ->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }
        return view('admin.member_directory.edit', compact("model", "page_title",'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MemberDirectory  $memberDirectory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Basic validation rules
        $rules = [
            'title' => 'required|string|max:100',
            'category_id' => 'required|array|max:3',
            'category_id.*' => 'exists:categories,id',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif,svg|max:2048',
            'name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:20',
            'url' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'facebook' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'hear_about_us' => 'nullable|string|max:255',
        ];
    
        // Extra validation for Admins
        if (Auth::user()->hasRole('Admin')) {
            $rules['status'] = 'required|in:pending,approved,rejected';
            $rules['rejection_reason'] = 'nullable|string|max:1000';
        }
    
        $validated = $request->validate($rules);
    
        // Retrieve record
        $member = MemberDirectory::findOrFail($id);
    
        // Handle image upload
        if ($request->hasFile('image')) {
            if ($member->image && file_exists(public_path('/admin/assets/images/member_directory/' . $member->image))) {
                unlink(public_path('/admin/assets/images/member_directory/' . $member->image));
            }
            $filename = date('YmdHis') . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('/admin/assets/images/member_directory'), $filename);
            $member->image = $filename;
        }
    
        // Update member data
        $member->title = $request->title;
        $member->category_id = json_encode($request->category_id);
        $member->address = $request->address;
        $member->name = $request->name;
        $member->phone_no = $request->phone_no;
        $member->url = $request->url;
        $member->email = $request->email;
        $member->facebook = $request->facebook;
        $member->description = $request->description;
        $member->hear_about_us = $request->hear_about_us;
    
        // Add status and rejection reason validation only for admins
        if (Auth::user()->hasRole('Admin')) {
            $member->status = $request->status;
            $member->rejection_reason = $request->status === 'rejected' ? $request->rejection_reason : null;
        }

        $member->save();
    
        /**
         * 📧 Notify the creator when status is updated by Admin
         */
        if (Auth::user()->hasRole('Admin') && in_array($member->status, ['approved', 'rejected'])) {
            $creator = \App\Models\User::find($member->created_by);
            if ($creator) {
                $rejectionReason = $member->status === 'rejected' ? $member->rejection_reason : null;
        
                $creator->notify(new \App\Notifications\MemberDirectoryStatusUpdatedNotification($member, $rejectionReason));
                // Corrected this line:

                \Mail::to($creator->email)->send(new \App\Mail\MemberDirectoryStatusUpdated($member, $rejectionReason));
            }
        }
    
        return redirect()->route('member_directory.index')->with('message', 'Member updated successfully.');
    }
    


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MemberDirectory  $memberDirectory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = MemberDirectory::where('id', $id)->first();
        if ($model) {
            $model->delete();
            return true;
        } else {
            return response()->json(['message' => 'Failed '], 404);
        }
        //
    }
}
