<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VideoPurchase;

class VideoPurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Viewer']);
    }

    /**
     * Display a listing of the user's video purchases
     */
    public function myPurchases(Request $request)
    {
        $user = Auth::user();
        
        $purchases = $user->videoPurchases()
            ->with(['video', 'video.creator'])
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        $page_title = 'My Video Purchases';
        
        return view('viewer.video-purchases.index', compact('purchases', 'page_title'));
    }

    /**
     * Display the specified video purchase
     */
    public function show(VideoPurchase $purchase)
    {
        // Ensure user owns this purchase
        if (Auth::user()->id !== $purchase->user_id) {
            abort(403, 'Unauthorized access to this purchase.');
        }

        $page_title = 'Video Purchase Details';
        
        return view('viewer.video-purchases.show', compact('purchase', 'page_title'));
    }
}
