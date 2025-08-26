<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Video;
use App\Models\VideoPurchase;
use App\Models\VideoDownload;
use App\Models\CreatorPricingRule;
use App\Models\Category;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Creator']);
    }

    /**
     * Display a listing of videos
     */
    public function index(Request $request)
    {
        $query = Video::with(['creator', 'category'])->active();

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by price range
        if ($request->has('price_min') && $request->price_min) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && $request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }

        // Filter by creator
        if ($request->has('creator_id') && $request->creator_id) {
            $query->where('creator_id', $request->creator_id);
        }

        // Search by title or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $videos = $query->latest()->paginate(12);
        $categories = Category::where('status', '1')->get();

        return view('videos.index', compact('videos', 'categories'));
    }

    /**
     * Display creator's own videos
     */
    public function myVideos(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->videos()->with(['category']);

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type (intro vs paid)
        if ($request->has('type') && $request->type) {
            if ($request->type === 'intro') {
                $query->where('is_intro', true);
            } else {
                $query->where('is_intro', false);
            }
        }

        // Search by title or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $videos = $query->latest()->paginate(12);
        $categories = Category::where('status', '1')->get();

        return view('creator.videos.index', compact('videos', 'categories', 'user'));
    }

    /**
     * Show the form for creating a new video
     */
    public function create()
    {
        // User must have Creator role (enforced by middleware)
        $categories = Category::where('status', '1')->get();
        $user = Auth::user();
        $pricingRules = $user->pricingRules;

        return view('creator.videos.create', compact('categories', 'pricingRules'));
    }

    /**
     * Store a newly created video
     */
    public function store(Request $request)
    {
        // User must have Creator role (enforced by middleware)

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_file' => 'required|file|mimes:mp4,avi,mov,wmv,flv,webm|max:102400', // 100MB max
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999.99',
            'is_intro' => 'boolean',
            'downloads_enabled' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Check if this is an intro video
        if ($request->boolean('is_intro')) {
            // Only allow one intro video per creator
            if ($user->hasIntroVideo()) {
                return redirect()->back()
                    ->withErrors(['is_intro' => 'You already have an intro video. Only one intro video is allowed per creator.'])
                    ->withInput();
            }

            // Intro videos must be free
            if ($request->price > 0) {
                return redirect()->back()
                    ->withErrors(['price' => 'Intro videos must be free.'])
                    ->withInput();
            }
        }

        // Validate pricing based on creator's rules
        if (!$this->validatePricing($user, $request->price)) {
            return redirect()->back()
                ->withErrors(['price' => 'Price is outside the allowed range for your account.'])
                ->withInput();
        }

        // Handle video file upload
        $videoPath = $request->file('video_file')->store('videos', 'public');
        
        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('video-thumbnails', 'public');
        }

        // Create video record
        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_path' => $videoPath,
            'thumbnail_path' => $thumbnailPath,
            'duration' => $this->getVideoDuration($videoPath),
            'is_intro' => $request->boolean('is_intro'),
            'price' => $request->price,
            'downloads_enabled' => $request->boolean('downloads_enabled', true),
            'creator_id' => $user->id,
            'category_id' => $request->category_id,
            'tags' => $request->tags ? explode(',', $request->tags) : [],
            'status' => 'active',
        ]);

        return redirect()->route('videos.show', $video)
            ->with('success', 'Video uploaded successfully!');
    }

    /**
     * Display the specified video
     */
    public function show(Video $video)
    {
        $user = Auth::user();
        $canAccess = $video->canUserAccess($user->id);
        $canDownload = $video->canUserDownload($user->id);
        $hasPurchased = $video->purchases()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->exists();

        // Increment view count if user can access
        if ($canAccess) {
            $video->incrementViews();
        }

        // Get related videos
        $relatedVideos = Video::where('creator_id', $video->creator_id)
            ->where('id', '!=', $video->id)
            ->where('status', 'active')
            ->limit(6)
            ->get();

        return view('videos.show', compact('video', 'canAccess', 'canDownload', 'hasPurchased', 'relatedVideos'));
    }

    /**
     * Show the form for editing the specified video
     */
    public function edit(Video $video)
    {
        // Check if user owns this video
        if (Auth::user()->id !== $video->creator_id) {
            abort(403, 'You can only edit your own videos.');
        }

        $categories = Category::where('status', '1')->get();
        $user = Auth::user();
        $pricingRules = $user->pricingRules;

        return view('creator.videos.edit', compact('video', 'categories', 'pricingRules'));
    }

    /**
     * Update the specified video
     */
    public function update(Request $request, Video $video)
    {
        // Check if user owns this video
        if (Auth::user()->id !== $video->creator_id) {
            abort(403, 'You can only update your own videos.');
        }

        // Debug: Log the request data
        \Log::info('Video update request', [
            'video_id' => $video->id,
            'request_data' => $request->all(),
            'user_id' => Auth::user()->id
        ]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm|max:102400', // 100MB max
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999.99',
            'downloads_enabled' => 'boolean',
            'is_intro' => 'boolean',
        ]);

        if ($validator->fails()) {
            \Log::error('Video update validation failed', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Check if this is an intro video
        $isIntro = $request->boolean('is_intro', false);
        
        if ($isIntro) {
            // Intro videos must be free
            if ($request->price > 0) {
                \Log::error('Intro video cannot have price', [
                    'price' => $request->price,
                    'user_id' => $user->id
                ]);
                return redirect()->back()
                    ->withErrors(['price' => 'Intro videos must be free (price = $0).'])
                    ->withInput();
            }
            
            // Check if user already has an intro video (if this is not the current intro video)
            if (!$video->is_intro && $user->hasIntroVideo()) {
                return redirect()->back()
                    ->withErrors(['is_intro' => 'You already have an intro video. Only one intro video is allowed per creator.'])
                    ->withInput();
            }
        } else {
            // For non-intro videos, validate pricing based on creator's rules
            if (!$this->validatePricing($user, $request->price)) {
                \Log::error('Video update pricing validation failed', [
                    'price' => $request->price,
                    'user_id' => $user->id
                ]);
                return redirect()->back()
                    ->withErrors(['price' => 'Price is outside the allowed range for your account.'])
                    ->withInput();
            }
        }

        try {
            // Handle video file upload
            $videoPath = $video->video_path; // Keep current path by default
            if ($request->hasFile('video_file')) {
                // Delete old video file
                if ($video->video_path) {
                    Storage::disk('public')->delete($video->video_path);
                }
                $videoPath = $request->file('video_file')->store('videos', 'public');
                
                // Update duration for new video
                $duration = $this->getVideoDuration($videoPath);
            } else {
                $duration = $video->duration; // Keep current duration
            }
            
            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($video->thumbnail_path) {
                    Storage::disk('public')->delete($video->thumbnail_path);
                }
                $thumbnailPath = $request->file('thumbnail')->store('video-thumbnails', 'public');
            } else {
                $thumbnailPath = $video->thumbnail_path;
            }

            // Update video
            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'video_path' => $videoPath,
                'thumbnail_path' => $thumbnailPath,
                'duration' => $duration,
                'is_intro' => $isIntro,
                'price' => $request->price,
                'downloads_enabled' => $request->has('downloads_enabled') ? true : false,
                'category_id' => $request->category_id,
                'tags' => $request->tags ? explode(',', $request->tags) : [],
            ];

            \Log::info('Updating video with data', $updateData);
            
            $video->update($updateData);

            \Log::info('Video updated successfully', ['video_id' => $video->id]);

            return redirect()->route('creator.videos.index')
                ->with('success', 'Video updated successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Video update failed', [
                'error' => $e->getMessage(),
                'video_id' => $video->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update video: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified video
     */
    public function destroy(Video $video)
    {
        // Check if user owns this video
        if (Auth::user()->id !== $video->creator_id) {
            abort(403, 'You can only delete your own videos.');
        }

        // Delete video file
        if ($video->video_path) {
            Storage::disk('public')->delete($video->video_path);
        }

        // Delete thumbnail
        if ($video->thumbnail_path) {
            Storage::disk('public')->delete($video->thumbnail_path);
        }

        $video->delete();

        return redirect()->route('videos.index')
            ->with('success', 'Video deleted successfully!');
    }

    /**
     * Download the specified video
     */
    public function download(Video $video)
    {
        $user = Auth::user();
        
        if (!$video->canUserDownload($user->id)) {
            abort(403, 'You do not have permission to download this video.');
        }

        // Record download
        VideoDownload::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Get video file path
        $filePath = storage_path('app/public/' . $video->video_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'Video file not found.');
        }

        return response()->download($filePath, $video->title . '.mp4');
    }

    /**
     * Purchase a video
     */
    public function purchase(Request $request, Video $video)
    {
        $user = Auth::user();

        // Check if user already purchased this video
        if ($video->purchases()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'You have already purchased this video.');
        }

        // Check if video is free
        if ($video->is_intro || $video->price == 0) {
            return redirect()->back()->with('error', 'This video is free and does not require purchase.');
        }

        // Here you would integrate with your payment gateway (Stripe, PayPal, etc.)
        // For now, we'll create a purchase record directly
        
        $purchase = VideoPurchase::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'amount_paid' => $video->price,
            'payment_method' => 'manual', // Replace with actual payment method
            'transaction_id' => 'TXN_' . Str::random(10),
            'status' => 'completed',
        ]);

        // Increment video purchase count
        $video->incrementPurchases();

        return redirect()->route('videos.show', $video)
            ->with('success', 'Video purchased successfully!');
    }

    /**
     * Validate pricing based on creator's rules
     */
    private function validatePricing($user, $price)
    {
        if (!$user->isCreator()) {
            return false;
        }

        // Get pricing rules from admin settings instead of user relationship
        $minPrice = \App\Models\AdminSetting::getMinVideoPrice();
        $maxPrice = \App\Models\AdminSetting::getMaxVideoPrice();
        
        // Check if price is within allowed range
        if ($price < $minPrice || $price > $maxPrice) {
            return false;
        }
        
        // For now, allow all prices within the range
        // You can add more complex logic here later
        return true;
    }

    /**
     * Get video duration (placeholder - you'll need to implement actual video processing)
     */
    private function getVideoDuration($videoPath)
    {
        // This is a placeholder. In production, you'd use FFmpeg or similar
        // to extract actual video duration
        return 60; // Default 1 minute
    }
}
