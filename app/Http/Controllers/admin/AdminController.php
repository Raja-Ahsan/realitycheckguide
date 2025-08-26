<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Video;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Wallet;
use App\Models\AdminSetting;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            return redirect()->route('login');
        }
        $page_title = 'Admin Dashboard';
        return view('admin.dashboard.dashboard', compact('page_title'));
    }

    public function editProfile()
    {
        // Check if the user is authenticated and their name is not null
        if (!Auth::check() || empty(Auth::user()->name)) {
            return redirect()->route('admin.login');
        }
    
        return view('admin.dashboard.edit');
    }
    
    public function updateProfile(Request $request)
    {
        // Check if the user is authenticated and their name is not null
        if (!Auth::check() || empty(Auth::user()->name)) {
            return redirect()->route('admin.login');
        }
    
        $user = User::findOrFail(Auth::user()->id);
        $this->validate($request, [
            'name' => 'required',
        ]);
    
        $user->name = $request->name;
    
        if (!empty($request->password)) {
            $this->validate($request, [
                'password' => 'required|same:confirm-password',
            ]);
    
            $user->password = Hash::make($request->password);
        }
    
        $user->update();
    
        return redirect()->back()->with('message', 'Profile updated successfully');
    }

    public function login()
    {
        
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        $page_title = 'Log In';
        return view('admin-auth.login', compact('page_title'));
    }

    public function authenticate(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!empty($user) && $user->hasRole($request->user_type)){
            $credentials = $request->only('email', 'password');
            
            if (Auth::attempt($credentials)) {
                return redirect()->route('dashboard');
            }
            return redirect()->back()->with('error', 'Failed to login try again.!');
        }else{
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
    public function logOut()
    {
        
        if(Auth::check() && Auth::user()->hasRole('Admin')){
            Auth::logout();
            return redirect()->route('admin.login');
        }elseif(Auth::check() && Auth::user()->hasRole('Contractor')){
            Auth::logout();
            return redirect()->route('login');
        }else{
            Auth::logout();
            return redirect()->route('login');
        }

    }

    //Password reset
    public function forgotPassword()
    {
        $page_title = 'Forgot Password';
        return view('admin-auth.passwords.forgot-password', compact('page_title'));
    }
    public function passwordResetLink(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('status', 1)->first();
        if(!empty($user)){
            $page_title = 'Change Password';
            do{
                $verify_token = uniqid();
            }while(User::where('verify_token', $verify_token)->first());

            $user->verify_token = $verify_token;
            $user->update();

            $details = [
                'from' => 'admin-password-reset',
                'title' => "Hello! ". $user->name,
                'body' => "You are receiving this email because we recieved a password reset request for your account.",
                'verify_token' => $user->verify_token,
            ];
			
            \Mail::to($user->email)->send(new \App\Mail\Email($details));
            return redirect()->route('admin.login')->with('message', 'We have emailed your password reset link!');
        }else{
            return redirect()->back()->with('error', 'Your account not found.');
        }
    }
    public function resetPassword($verify_token)
    {
        $page_title = 'Reset Password';
        $user = User::where('verify_token', $verify_token)->first();
        if($user->hasRole('Admin')){
            return view('admin-auth.passwords.change-password', compact('page_title', 'verify_token'));
        }else{
            return view('admin-auth.passwords.change-password', compact('page_title', 'verify_token'));
        }
    }
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|same:confirm-password',
        ]);

        $user = User::where('verify_token', $request->verify_token)->first();
        $user->password = Hash::make($request->password);
        $user->verify_token = null;
        $user->update();

        if($user){
            return redirect()->route('admin.login')->with('message', 'You have updated password. You can login again.');
        }else{
            return redirect()->back()->with('error', 'Something went wrong try again');
        }
    }

    // Video Platform Management
    public function videoSettings()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            return redirect()->route('admin.login');
        }

        $settings = AdminSetting::getStripeSettings();
        $commissionRate = AdminSetting::getCommissionRate();
        $maxVideoPrice = AdminSetting::getMaxVideoPrice();
        $minVideoPrice = AdminSetting::getMinVideoPrice();
        $videosSoldThreshold = AdminSetting::getVideosSoldThreshold();

        // Get platform statistics
        $totalVideos = Video::count();
        $totalCreators = User::role('Creator')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('amount');
        $pendingPayouts = Payout::where('status', 'pending')->count();
        $totalPayouts = Payout::where('status', 'completed')->sum('amount');

        $page_title = 'Video Platform Settings';
        return view('admin.video-settings', compact(
            'page_title', 
            'settings', 
            'commissionRate', 
            'maxVideoPrice', 
            'minVideoPrice', 
            'videosSoldThreshold',
            'totalVideos',
            'totalCreators',
            'totalOrders',
            'totalRevenue',
            'pendingPayouts',
            'totalPayouts'
        ));
    }

    public function updateVideoSettings(Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'max_video_price' => 'required|numeric|min:0.99',
            'min_video_price' => 'required|numeric|min:0.99',
            'videos_sold_threshold' => 'required|integer|min:1',
            'stripe_publishable_key' => 'required|string',
            'stripe_secret_key' => 'required|string',
            'stripe_webhook_secret' => 'required|string',
        ]);

        AdminSetting::setValue('commission_rate', $request->commission_rate);
        AdminSetting::setValue('max_video_price', $request->max_video_price);
        AdminSetting::setValue('min_video_price', $request->min_video_price);
        AdminSetting::setValue('videos_sold_threshold', $request->videos_sold_threshold);
        AdminSetting::setValue('stripe_publishable_key', $request->stripe_publishable_key);
        AdminSetting::setValue('stripe_secret_key', $request->stripe_secret_key);
        AdminSetting::setValue('stripe_webhook_secret', $request->stripe_webhook_secret);

        return redirect()->back()->with('message', 'Video platform settings updated successfully!');
    }

    public function videoManagement()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            return redirect()->route('admin.login');
        }

        $videos = Video::with(['creator', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $page_title = 'Video Management';
        return view('admin.video-management', compact('page_title', 'videos'));
    }

    public function orderManagement()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            return redirect()->route('admin.login');
        }

        $orders = Order::with(['user', 'video', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $page_title = 'Order Management';
        return view('admin.order-management', compact('page_title', 'orders'));
    }

    public function payoutManagement()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            return redirect()->route('admin.login');
        }

        $pendingPayouts = Payout::with(['creator', 'wallet'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $completedPayouts = Payout::with(['creator', 'wallet'])
            ->whereIn('status', ['completed', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        $page_title = 'Payout Management';
        return view('admin.payout-management', compact('page_title', 'pendingPayouts', 'completedPayouts'));
    }

    public function processPayout(Request $request, $payoutId)
    {
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $payout = Payout::findOrFail($payoutId);
        
        if ($payout->status !== 'pending') {
            return redirect()->back()->with('error', 'Payout is not in pending status.');
        }

        if ($request->action === 'approve') {
            $payout->approve($request->admin_notes);
            $message = 'Payout approved successfully!';
        } else {
            $payout->reject($request->admin_notes);
            $message = 'Payout rejected successfully!';
        }

        return redirect()->back()->with('message', $message);
    }

    public function creatorAnalytics()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            return redirect()->route('admin.login');
        }

        $creators = User::role('Creator')->with(['wallet', 'videos'])
            ->withCount(['videos', 'orders'])
            ->orderBy('created_at', 'desc')
            ->get();

        $page_title = 'Creator Analytics';
        return view('admin.creator-analytics', compact('page_title', 'creators'));
    }

    public function walletOverview()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            return redirect()->route('admin.login');
        }

        $totalBalance = Wallet::sum('balance');
        $totalPendingBalance = Wallet::sum('pending_balance');
        $totalEarned = Wallet::sum('total_earned');
        $totalPaidOut = Wallet::sum('total_paid_out');

        $recentTransactions = WalletTransaction::with(['wallet.creator'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $page_title = 'Wallet Overview';
        return view('admin.wallet-overview', compact(
            'page_title', 
            'totalBalance', 
            'totalPendingBalance', 
            'totalEarned', 
            'totalPaidOut',
            'recentTransactions'
        ));
    }

    // Creator Dashboard Methods
    public function creatorDashboard()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Creator')) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $recentVideos = $user->videos()->latest()->limit(5)->get();
        $totalVideos = $user->videos()->count();
        $totalViews = $user->videos()->sum('views_count');
        $totalEarnings = $user->wallet ? $user->wallet->total_earned : 0;
        
        // Calculate total purchases (orders where this creator's videos were sold)
        $totalPurchases = \App\Models\Order::whereHas('video', function($query) use ($user) {
            $query->where('creator_id', $user->id);
        })->where('status', 'completed')->count();
        
        // Get recent purchases for this creator's videos
        $recentPurchases = \App\Models\Order::whereHas('video', function($query) use ($user) {
            $query->where('creator_id', $user->id);
        })->with(['user', 'video'])
          ->where('status', 'completed')
          ->latest()
          ->limit(10)
          ->get();
        
        // Get pricing rules from admin settings
        $pricingRules = (object) [
            'min_price_floor' => \App\Models\AdminSetting::getMinVideoPrice(),
            'max_price_cap' => \App\Models\AdminSetting::getMaxVideoPrice(),
            'videos_sold_threshold' => \App\Models\AdminSetting::getVideosSoldThreshold(),
            'custom_pricing_enabled' => true // For now, assume it's enabled
        ];
        
        // Get wallet information
        $wallet = $user->wallet;
        $pendingBalance = $wallet ? $wallet->pending_balance : 0;
        $availableBalance = $wallet ? $wallet->balance : 0;

        $page_title = 'Creator Dashboard';
        return view('creator.dashboard', compact(
            'page_title', 
            'recentVideos', 
            'totalVideos', 
            'totalViews', 
            'totalEarnings',
            'totalPurchases',
            'recentPurchases',
            'pricingRules',
            'wallet',
            'pendingBalance',
            'availableBalance'
        ));
    }

    public function creatorPricingRules()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Creator')) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Get pricing rules from admin settings
        $pricingRules = (object) [
            'min_price_floor' => \App\Models\AdminSetting::getMinVideoPrice(),
            'max_price_cap' => \App\Models\AdminSetting::getMaxVideoPrice(),
            'videos_sold_threshold' => \App\Models\AdminSetting::getVideosSoldThreshold(),
            'custom_pricing_enabled' => true // For now, assume it's enabled
        ];
        
        $page_title = 'Pricing Rules';
        return view('creator.pricing-rules', compact('page_title', 'user', 'pricingRules'));
    }

    public function updateCreatorPricingRules(Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasRole('Creator')) {
            return redirect()->route('login');
        }

        $request->validate([
            'default_price' => 'required|numeric|min:0',
            'price_per_minute' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $user->update([
            'default_video_price' => $request->default_price,
            'price_per_minute' => $request->price_per_minute,
        ]);

        return redirect()->back()->with('message', 'Pricing rules updated successfully!');
    }

    public function creatorEarnings()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Creator')) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Get total earnings from wallet
        $totalEarnings = $user->wallet ? $user->wallet->total_earned : 0;
        
        // Get recent transactions (orders for this creator's videos)
        $recentTransactions = \App\Models\Order::whereHas('video', function($query) use ($user) {
            $query->where('creator_id', $user->id);
        })->with(['user', 'video'])
          ->where('status', 'completed')
          ->latest()
          ->paginate(20);
        
        // Get monthly earnings breakdown
        $monthlyEarnings = \App\Models\Order::whereHas('video', function($query) use ($user) {
            $query->where('creator_id', $user->id);
        })->where('status', 'completed')
          ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(creator_earning) as earnings')
          ->groupBy('month')
          ->orderBy('month', 'desc')
          ->get();
        
        $page_title = 'My Earnings';
        return view('creator.earnings', compact('page_title', 'totalEarnings', 'recentTransactions', 'monthlyEarnings'));
    }

    public function checkCreatorPricingUnlock()
    {
        if (!Auth::check() || !Auth::user()->hasRole('Creator')) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $hasIntroVideo = $user->videos()->where('is_intro', true)->exists();
        
        return response()->json([
            'unlocked' => $hasIntroVideo,
            'message' => $hasIntroVideo ? 'Pricing unlocked!' : 'Upload an intro video to unlock pricing features.'
        ]);
    }
}
