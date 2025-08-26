<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\Bid;
use App\Models\JobPostCategory;
use App\Models\City;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:User');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $page_title = 'Dashboard';
        $totalJobPosts = $user->jobPosts()->count();
        $activeJobPosts = $user->jobPosts()->where('status', 1)->count();
        $totalBids = $user->receivedBids()->count();
        $pendingBids = $user->receivedBids()->where('status', 'pending')->count();
        
        $recentJobPosts = $user->jobPosts()->latest()->take(5)->get();
        $recentBids = $user->receivedBids()->with(['jobPost', 'electrician'])->latest()->take(5)->get();
        
        return view('website.user-dashboard.dashboard', compact('totalJobPosts', 'activeJobPosts', 'totalBids', 'pendingBids', 'recentJobPosts', 'recentBids', 'page_title'));
    }
}
