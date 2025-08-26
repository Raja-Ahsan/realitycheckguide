<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\Bid;
use App\Models\JobPostCategory;
use App\Models\City;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ElectricianController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:electrician-list|electrician-create|electrician-edit|electrician-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:electrician-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:electrician-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:electrician-delete', ['only' => ['destroy']]);
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Dashboard metrics (snake_case to match the blade expectations)
        $total_jobpost = JobPost::where('status', 1)->count();
        $total_bids = Bid::where('electrician_id', $user->id)->count();
        $accepted_bids = Bid::where('electrician_id', $user->id)->where('status', 'accepted')->count();
        $pending_bids = Bid::where('electrician_id', $user->id)->where('status', 'pending')->count();

        $recentBids = Bid::with('jobPost')
            ->where('electrician_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view(
            'website.electrician-dashboard.dashboard',
            compact('total_jobpost', 'total_bids', 'accepted_bids', 'pending_bids', 'recentBids')
        );
    }

    public function jobs(Request $request)
    {
        $query = JobPost::where('status', 1)
            ->with(['hasCategory', 'hasCity', 'hasState', 'user', 'bids']);

        // Apply search filter (name, description, city, state)
        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('budget_min', 'like', "%{$search}%")
                    ->orWhere('budget_max', 'like', "%{$search}%")
                    ->orWhereHas('hasCity', function ($cityQuery) use ($search) {
                        $cityQuery->where('city', 'like', "%{$search}%");
                    })
                    ->orWhereHas('hasState', function ($stateQuery) use ($search) {
                        $stateQuery->where('state', 'like', "%{$search}%");
                    });
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('job_category_id', $request->category);
        }

        // Apply location filter
        if ($request->filled('city')) {
            $query->where('city_id', $request->city);
        }

        $jobs = $query->latest()->paginate(10);
        $categories = JobPostCategory::where('status', 1)->get();
        $cities = City::where('status', 1)->get();

        return view('website.electrician-dashboard.jobs', compact('jobs', 'categories', 'cities'));
    }

    public function jobDetail($id)
    {
        $job = JobPost::with(['hasCategory', 'hasCity', 'hasState', 'user', 'bids'])->findOrFail($id);
        $user = Auth::user();
        
        // Check if user has already bid on this job
        $existingBid = Bid::where('electrician_id', $user->id)
            ->where('job_post_id', $id)
            ->first();
        
        return view('website.electrician-dashboard.job-detail', compact('job', 'existingBid'));
    }

    public function submitBid(Request $request, $jobId)
    {
        $validator = Validator::make($request->all(), [
            'bid_amount' => 'required|numeric|min:0',
            'proposal' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $job = JobPost::findOrFail($jobId);

        // Check if user has already bid on this job
        $existingBid = Bid::where('electrician_id', $user->id)
            ->where('job_post_id', $jobId)
            ->first();
        if ($existingBid) {
            return redirect()->back()->with('error', 'You have already bid on this job.');
        }

        // Create new bid
        Bid::create([
            'job_post_id' => $jobId,
            'electrician_id' => $user->id,
            'user_id' => $job->created_by,
            'bid_amount' => $request->bid_amount,
            'proposal' => $request->proposal,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Bid submitted successfully!');
    }

    public function myBids(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status');

        $query = Bid::with(['jobPost', 'user'])
            ->where('electrician_id', $user->id)
            ->latest();

        if (in_array($status, ['pending', 'accepted', 'rejected'])) {
            $query->where('status', $status);
        }

        $bids = $query->paginate(10);
        
        return view('website.electrician-dashboard.my-bids', compact('bids', 'status'));
    }

    public function updateBid(Request $request, $bidId)
    {
        $validator = Validator::make($request->all(), [
            'bid_amount' => 'required|numeric|min:0',
            'proposal' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $bid = Bid::where('electrician_id', $user->id)->findOrFail($bidId);

        // Only allow update if bid is still pending
        if ($bid->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot update bid that is not pending.');
        }

        $bid->update([
            'bid_amount' => $request->bid_amount,
            'proposal' => $request->proposal,
        ]);

        return redirect()->back()->with('success', 'Bid updated successfully!');
    }

    public function withdrawBid($bidId)
    {
        $user = Auth::user();
        $bid = Bid::where('electrician_id', $user->id)->findOrFail($bidId);

        // Only allow withdrawal if bid is pending
        if ($bid->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot withdraw bid that is not pending.');
        }

        $bid->delete();

        return redirect()->back()->with('success', 'Bid withdrawn successfully!');
    }
}
