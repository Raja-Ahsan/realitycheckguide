<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VideoDownload;

class VideoDownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Viewer']);
    }

    /**
     * Display a listing of the user's video downloads
     */
    public function myDownloads(Request $request)
    {
        $user = Auth::user();
        
        $downloads = $user->videoDownloads()
            ->with(['video', 'video.creator'])
            ->latest()
            ->paginate(20);

        $page_title = 'My Video Downloads';
        
        return view('viewer.downloads.index', compact('downloads', 'page_title'));
    }

    /**
     * Display the specified video download
     */
    public function show(VideoDownload $download)
    {
        // Ensure user owns this download
        if (Auth::user()->id !== $download->user_id) {
            abort(403, 'Unauthorized access to this download.');
        }

        $page_title = 'Video Download Details';
        
        return view('viewer.downloads.show', compact('download', 'page_title'));
    }
}
