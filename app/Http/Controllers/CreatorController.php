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
        // get Creator whare also statu is active
        $query = User::role('Creator')->with(['videos', 'introVideo']);
        $query->where('status', 1);

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

        $creators = $query->latest()->paginate(12);

        // Get banner for the page (using slug instead of page)
        $banner = \App\Models\Banner::where('slug', 'creators')->first();

        return view('website.creators', compact('creators', 'banner'));
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
