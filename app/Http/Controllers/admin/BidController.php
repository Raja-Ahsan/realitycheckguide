<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Admin');
    }

    public function index(Request $request)
    {
        $query = Bid::with(['jobPost', 'electrician', 'user']);

        // Apply search filter
        if ($request->has('search') && $request->search != "") {
            $query->whereHas('jobPost', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Apply status filter
        if ($request->has('status') && $request->status != 'All') {
            $query->where('status', $request->status);
        }

        $bids = $query->latest()->paginate(15);
        
        return view('admin.bids.index', compact('bids'));
    }

    public function show($id)
    {
        $bid = Bid::with(['jobPost', 'electrician', 'user'])->findOrFail($id);
        
        return view('admin.bids.show', compact('bid'));
    }

    public function updateStatus(Request $request, $id)
    {
        $bid = Bid::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected,completed'
        ]);

        $bid->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Bid status updated successfully!');
    }

    public function destroy($id)
    {
        $bid = Bid::findOrFail($id);
        $bid->delete();

        return redirect()->route('admin.bids.index')->with('success', 'Bid deleted successfully!');
    }

    public function statistics()
    {
        $totalBids = Bid::count();
        $pendingBids = Bid::where('status', 'pending')->count();
        $acceptedBids = Bid::where('status', 'accepted')->count();
        $rejectedBids = Bid::where('status', 'rejected')->count();
        $completedBids = Bid::where('status', 'completed')->count();

        $recentBids = Bid::with(['jobPost', 'electrician'])->latest()->take(10)->get();

        return view('admin.bids.statistics', compact(
            'totalBids', 
            'pendingBids', 
            'acceptedBids', 
            'rejectedBids', 
            'completedBids',
            'recentBids'
        ));
    }
}
