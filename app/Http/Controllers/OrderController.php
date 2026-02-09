<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Viewer']);
    }

    /**
     * Display a listing of the user's orders
     */
    public function myOrders(Request $request)
    {
        $user = Auth::user();
        
        $orders = $user->orders()
            ->with(['video', 'creator'])
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        $page_title = 'My Video Orders';
        
        return view('viewer.orders.index', compact('orders', 'page_title'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Ensure user owns this order
        if (Auth::user()->id !== $order->user_id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $page_title = 'Order Details';
        
        return view('viewer.orders.show', compact('order', 'page_title'));
    }
}
