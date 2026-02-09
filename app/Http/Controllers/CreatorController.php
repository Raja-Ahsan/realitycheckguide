<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Video;
use App\Models\Category;

class CreatorController extends Controller
{
    /**
     * Display a listing of creators
     */
    public function index(Request $request)
    {   
        // get Creator where also status is active
        $query = User::role('Creator')->with(['videos' => function($q) {
            $q->where('status', 'active');
        }]);
        $query->where('status', 1);
        
        // Eager load intro video separately to ensure it loads correctly
        $query->with(['introVideo' => function($q) {
            $q->where('is_intro', true)->where('status', 'active');
        }]);

        // Search by name, designation, or about_me
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%")
                  ->orWhere('about_me', 'like', "%{$search}%");
            });
        }

        // Filter by verification status
        if ($request->has('verified') && $request->verified) {
            $query->where('is_verified', true);
        }

        // Filter by featured status
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        // Filter by category: only creators who have at least one video in this category
        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('videos', function ($q) use ($request) {
                $q->where('status', 'active')->where('category_id', $request->category_id);
            });
        }

        $creators = $query->latest()->paginate(12);

        // Only categories that have at least one active video (for clickable filter buttons)
        $videoCategories = Category::where('status', '1')
            ->whereHas('videos', function ($q) {
                $q->where('status', 'active');
            })
            ->orderBy('title')
            ->get();

        // Get banner for the page (using slug instead of page)
        $banner = \App\Models\Banner::where('slug', 'creators')->first();

        return view('website.creators', compact('creators', 'banner', 'videoCategories'));
    }

    /**
     * Display the specified creator profile
     */
    public function show(User $creator)
    {
        // Ensure the user is a creator
        if (!$creator->hasRole('Creator')) {
            abort(404, 'Creator not found.');
        }

        // Get creator's intro video
        $introVideo = $creator->videos()->where('is_intro', true)->first();

        // Get creator's paid videos with pagination
        $paidVideos = $creator->videos()
            ->where('is_intro', false)
            ->where('status', 'active')
            ->latest()
            ->paginate(9);

        // Get banner for the page (using slug instead of page)
        $banner = \App\Models\Banner::where('slug', 'creator-profile')->first();

        return view('website.creator-profile', compact('creator', 'introVideo', 'paidVideos', 'banner'));
    }

    /**
     * Show creator's videos (for authenticated users)
     */
    public function videos(User $creator)
    {
        // Ensure the user is a creator
        if (!$creator->hasRole('Creator')) {
            abort(404, 'Creator not found.');
        }

        // Get all videos from this creator
        $videos = $creator->videos()
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('website.creator-videos', compact('creator', 'videos'));
    }

    /**
     * Show creator's intro video
     */
    public function introVideo(User $creator)
    {
        // Ensure the user is a creator
        if (!$creator->hasRole('Creator')) {
            abort(404, 'Creator not found.');
        }

        $introVideo = $creator->videos()->where('is_intro', true)->first();

        if (!$introVideo) {
            abort(404, 'Intro video not found.');
        }

        return redirect()->route('videos.show', $introVideo);
    }
}
