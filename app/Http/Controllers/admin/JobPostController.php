<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobPostCategory;
use App\Models\User;
use App\Models\Banner;
use App\Models\ClientContact;
use App\Models\Bid;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobPostController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:jobpost-list|jobpost-create|jobpost-edit|jobpost-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:jobpost-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:jobpost-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:jobpost-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        if ($request->ajax()) {
            $query = JobPost::orderby('id', 'desc')->where('id', '>', 0);

            // Apply search filter
            if ($request->has('search') && $request['search'] != "") {
                $query->where('name', 'like', '%' . $request['search'] . '%');
            }

            // Apply status filter
            if ($request->has('status') && $request['status'] != 'All') {
                $status = $request['status'] == 2 ? 0 : $request['status'];
                $query->where('status', $status);
            }

            // Restrict to own JobPost for Viewers
            if ($user->hasRole('Viewer')) {
                $query->where('created_by', $user->id); // Show only the JobPost created by this user
            }

            $jobposts = $query->paginate(10);
            return (string) view('admin.jobpost.search', compact('jobposts'));
        }

        // Non-AJAX request
        $page_title = 'All Jobs';

        if ($user->hasRole('Viewer')) {
            // Show only own jobposts for Viewers
            $jobposts = JobPost::where('created_by', $user->id)->orderby('id', 'desc')->paginate(10);
            return view('admin.jobpost.index', compact('jobposts', 'page_title'));
        } else {
            // Admins or other roles see all jobposts
            $jobposts = JobPost::orderby('id', 'desc')->paginate(10);
            return view('admin.jobpost.index', compact('jobposts', 'page_title'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $page_title = "Add Job";
        $cities = City::where('status', 1)->get();
        $categories = JobPostCategory::where('status', 1)->get();
        $states = State::where('status', 1)->get();
        
        return view('admin.jobpost.create', compact('page_title' , 'categories', 'cities', 'states'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'job_category_id' => 'required|exists:job_post_categories,id',
            'city_id' => 'required|exists:cities,id',
            'state_id' => 'required|exists:states,id',
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|min:0|gte:budget_min',
            'deadline' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $jobpost = new JobPost();

        if (isset($request->image)) {
            $photo = date('y-m-d-His') . '.' . $request->file('image')->getClientOriginalExtension();
            $request->image->move(public_path('/admin/assets/images/jobpost'), $photo);
            $jobpost->image = $photo;
        }
        
        $jobpost->created_by = $user->id;
        $jobpost->name = $request->name;
        $jobpost->job_category_id = $request->job_category_id;
        $jobpost->city_id = $request->city_id;
        $jobpost->state_id = $request->state_id;
        $jobpost->short_description = $request->short_description ?? null;
        $jobpost->description = $request->description;
        $jobpost->budget_min = $request->budget_min;
        $jobpost->budget_max = $request->budget_max;
        $jobpost->deadline = $request->deadline; 
        $jobpost->save();

        return redirect()->route('jobpost.index')->with('message', 'Job Post added Successfully');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobPost  $jobPost
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_title = 'Job Post Details';

        // Fetch a single jobpost by its ID
        $jobpost = JobPost::find($id);
        if (!$jobpost) {
            return redirect()->back()->withErrors(['error' => 'Job Post not found.']);
        }

        // Fetch the contractor details based on the jobpost's created_by field
        $contractor_detail = User::find($jobpost->created_by);

        $banner = Banner::where('id', 18)->where('status', 1)->first();
        // Fetch the related client contacts
        $client_contacts = ClientContact::where('status', 1)->where('agent_id', $id)->get();
        $jobposts = JobPost::where('status', 1)->get();
        // Fetch related jobposts (assuming you want to fetch some related jobposts)
        $related_jobposts = JobPost::where('status', 1)->where('id', '!=', $id)->get();

        return view('admin.jobpost.details', compact('page_title','jobposts', 'contractor_detail', 'banner', 'jobpost', 'client_contacts', 'related_jobposts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobPost  $jobPost
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $page_title = 'Edit Job';
        $jobposts = JobPost::where('id', $id)->first();
        $categories = JobPostCategory::where('status', 1)->get();
        $cities = City::where('status', 1)->get();
        $states = State::where('status', 1)->get();
        
        return view('admin.jobpost.edit', compact('jobposts', 'page_title', 'categories', 'cities', 'states'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobPost  $jobPost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'job_category_id' => 'required|exists:job_post_categories,id',
            'city_id' => 'required|exists:cities,id',
            'state_id' => 'required|exists:states,id',
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|min:0|gte:budget_min',
            'deadline' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

            $updates = JobPost::where('id', $id)->first();

            if (isset($request->image)) {
                $photo = date('y-m-d-His') . '.' . $request->file('image')->getClientOriginalExtension();
                $request->image->move(public_path('/admin/assets/images/jobpost'), $photo);
                $updates->image = $photo;
            }

            $updates->created_by = $user->id;
            $updates->name = $request->name;
            $updates->job_category_id = $request->job_category_id;
            $updates->city_id = $request->city_id;
            $updates->state_id = $request->state_id;
            $updates->short_description = $request->short_description ?? null;
            $updates->description = $request->description;
            $updates->budget_min = $request->budget_min;
            $updates->budget_max = $request->budget_max;
            $updates->deadline = $request->deadline; 
            $updates->status = $request->status;
            $updates->update();

        return redirect()->route('jobpost.index')->with('message', 'Job Post updated Successfully');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobPost  $jobPost
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        $jobpost = JobPost::where('id', $id)->first();
        if ($jobpost) {
            $jobpost->delete();
            return true;
        } else {
            return response()->json(['message' => 'Failed'], 404);
        }
    }

    // Additional methods for bid management
    public function jobPostBids($jobId)
    {
        $user = Auth::user();
        $jobPost = JobPost::where('created_by', $user->id)->with(['bids.electrician'])->findOrFail($jobId);
        
        return view('admin.jobpost.job-post-bids', compact('jobPost'));
    }

    public function acceptBid($bidId)
    {
        $user = Auth::user();
        $bid = Bid::where('user_id', $user->id)->findOrFail($bidId);
        
        // Reject all other bids for this job
        $bid->jobPost->bids()->where('id', '!=', $bidId)->update(['status' => 'rejected']);
        
        // Accept this bid
        $bid->update(['status' => 'accepted']);
        
        return redirect()->back()->with('success', 'Bid accepted successfully!');
    }

    public function rejectBid($bidId)
    {
        $user = Auth::user();
        $bid = Bid::where('user_id', $user->id)->findOrFail($bidId);
        
        $bid->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Bid rejected successfully!');
    }

    public function completeJob($jobId)
    {
        $user = Auth::user();
        $jobPost = JobPost::where('created_by', $user->id)->findOrFail($jobId);
        
        // Mark the accepted bid as completed
        $acceptedBid = $jobPost->bids()->where('status', 'accepted')->first();
        if ($acceptedBid) {
            $acceptedBid->update(['status' => 'completed']);
        }
        
        return redirect()->back()->with('success', 'Job marked as completed!');
    }
}
