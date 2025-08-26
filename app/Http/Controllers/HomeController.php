<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use App\Models\MemberDirectory;
use App\Models\DocumentRepository;
use App\Models\Project;
use App\Models\JobPost;
use App\Models\Bid;
use App\Models\ContactUs;
use App\Models\Contact;
use App\Models\ClientContact;
use App\Models\news_letter;
use Google\Service\CivicInfo\Resource\Elections;
use Illuminate\Support\Facades\Session;
class HomeController extends Controller
{
    /** 
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check() && Auth::user()->hasRole('Admin')) {
            // Admin dashboard
            $page_title = 'Admin Dashboard';
            $total_users = User::where('id', '!=', 1)->count(); 
            $total_jobpost = JobPost::where('status', 1)->count(); 
            $total_bids = Bid::count();
            $pending_bids = Bid::where('status', 'pending')->count();
            $accepted_bids = Bid::where('status', 'accepted')->count(); 
            $rejected_bids = Bid::where('status', 'rejected')->count();
            $total_contactus = ContactUs::where('status', 1)->count();
            $total_subscriber = news_letter::where('status', 1)->count();
            return view('admin.dashboard.dashboard', compact('page_title', 'total_users','total_jobpost', 'total_contactus','total_subscriber','total_bids','pending_bids','accepted_bids','rejected_bids'));
  
        } elseif (Auth::check() && Auth::user()->hasRole('Creator')) {
            // Redirect creators to the video platform dashboard
            return redirect()->route('creator.dashboard');
       
        } elseif (Auth::check() && Auth::user()->hasRole('Viewer')) {
            // Viewer dashboard
            $page_title = 'Dashboard';
            $user = Auth::user();
            $totalJobPosts = $user->jobPosts()->count();
            $activeJobPosts = $user->jobPosts()->where('status', 1)->count();
            $totalBids = $user->receivedBids()->count();
            $pendingBids = $user->receivedBids()->where('status', 'pending')->count();
            $recentJobPosts = $user->jobPosts()->latest()->take(5)->get();
            $recentBids = $user->receivedBids()->with(['jobPost', 'creator'])->latest()->take(5)->get();
            return view('website.viewer-dashboard.dashboard', compact('page_title', 'totalJobPosts', 'activeJobPosts', 'totalBids', 'pendingBids', 'recentJobPosts', 'recentBids'));
        } else {
            return redirect()->route('index');
        }
    }
}
