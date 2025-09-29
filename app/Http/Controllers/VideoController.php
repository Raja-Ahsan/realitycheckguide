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
use App\Models\VideoQuestion;
use App\Models\VideoQuestionOption;
use App\Models\VideoQuestionResponse;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function __construct()
    {
        // Only require Creator role for methods that modify videos
        $this->middleware('auth');
        $this->middleware('role:Creator')->only(['create', 'store', 'edit', 'update', 'destroy', 'myVideos']);
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

        // Debug: Log all request data
        Log::info('Video store request data', [
            'all_data' => $request->all(),
            'questions_data' => $request->questions,
            'questions_type' => gettype($request->questions ?? null)
        ]);

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
            // Q&A validation rules - Temporarily disabled for debugging
            // 'questions' => 'nullable|array|max:10',
            // 'questions.*.question' => 'required_with:questions|string|max:1000',
            // 'questions.*.options' => 'required_with:questions|array|size:4',
            // 'questions.*.options.*' => 'required_with:questions.*.options|string|max:500',
            // 'questions.*.correct_option' => 'required_with:questions|integer|min:1|max:4',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Check if this is an intro video
        if ($request->boolean('is_intro')) {
            // Only allow one intro video per creator
            if ($user->videos()->where('is_intro', true)->exists()) {
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

        // Create Q&A questions if provided - TESTING MODE
        Log::info('Q&A Debug Info', [
            'has_questions' => $request->has('questions'),
            'questions_value' => $request->input('questions'),
            'questions_type' => gettype($request->input('questions')),
            'all_request_keys' => array_keys($request->all()),
            'request_contains_questions' => strpos(json_encode($request->all()), 'questions') !== false
        ]);
        
        // Try to create Q&A questions with any data that looks like questions
        if ($request->has('questions')) {
            Log::info('Attempting to create Q&A questions', [
                'video_id' => $video->id,
                'questions_data' => $request->questions
            ]);
            try {
                $this->createVideoQuestions($video, $request->questions);
                Log::info('Q&A questions created successfully');
            } catch (\Exception $e) {
                Log::error('Failed to create Q&A questions', [
                    'error' => $e->getMessage(),
                    'questions_data' => $request->questions
                ]);
            }
        } else {
            Log::info('No questions field found in request');
        }

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

        // Load questions with options for Q&A functionality
        $video->load(['questions.options']);

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

        // Load questions with options for editing
        $video->load(['questions.options']);

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
        Log::info('Video update request', [
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
            // Q&A validation rules - Temporarily disabled for debugging
            // 'questions' => 'nullable|array|max:10',
            // 'questions.*.question' => 'required_with:questions|string|max:1000',
            // 'questions.*.options' => 'required_with:questions|array|size:4',
            // 'questions.*.options.*' => 'required_with:questions.*.options|string|max:500',
            // 'questions.*.correct_option' => 'required_with:questions|integer|min:1|max:4',
        ]);

        if ($validator->fails()) {
            Log::error('Video update validation failed', $validator->errors()->toArray());
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
                Log::error('Intro video cannot have price', [
                    'price' => $request->price,
                    'user_id' => $user->id
                ]);
                return redirect()->back()
                    ->withErrors(['price' => 'Intro videos must be free (price = $0).'])
                    ->withInput();
            }
            
            // Check if user already has an intro video (if this is not the current intro video)
            if (!$video->is_intro && $user->videos()->where('is_intro', true)->exists()) {
                return redirect()->back()
                    ->withErrors(['is_intro' => 'You already have an intro video. Only one intro video is allowed per creator.'])
                    ->withInput();
            }
        } else {
            // For non-intro videos, validate pricing based on creator's rules
            if (!$this->validatePricing($user, $request->price)) {
                Log::error('Video update pricing validation failed', [
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

            Log::info('Updating video with data', $updateData);
            
            $video->update($updateData);

            // Update Q&A questions if provided - TESTING MODE
            Log::info('Q&A Update Debug Info', [
                'has_questions' => $request->has('questions'),
                'questions_value' => $request->input('questions'),
                'questions_type' => gettype($request->input('questions')),
                'all_request_keys' => array_keys($request->all()),
                'request_contains_questions' => strpos(json_encode($request->all()), 'questions') !== false
            ]);
            
            // Try to update Q&A questions with any data that looks like questions
            if ($request->has('questions')) {
                Log::info('Attempting to update Q&A questions', [
                    'video_id' => $video->id,
                    'questions_data' => $request->questions
                ]);
                try {
                    $this->updateVideoQuestions($video, $request->questions);
                    Log::info('Q&A questions updated successfully');
                } catch (\Exception $e) {
                    Log::error('Failed to update Q&A questions', [
                        'error' => $e->getMessage(),
                        'questions_data' => $request->questions
                    ]);
                }
            } else {
                Log::info('No questions field found in update request');
                // If no questions provided, remove all existing questions
                $this->removeAllVideoQuestions($video);
            }

            Log::info('Video updated successfully', ['video_id' => $video->id]);

            return redirect()->route('creator.videos.index')
                ->with('success', 'Video updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('Video update failed', [
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

    /**
     * Create video questions and options
     */
    private function createVideoQuestions(Video $video, array $questions)
    {
        Log::info('createVideoQuestions called', [
            'video_id' => $video->id,
            'questions_count' => count($questions),
            'questions_data' => $questions
        ]);

        foreach ($questions as $index => $questionData) {
            Log::info('Processing question', [
                'index' => $index,
                'question_data' => $questionData
            ]);

            if (empty($questionData['question']) || !isset($questionData['options']) || !isset($questionData['correct_option'])) {
                Log::warning('Skipping invalid question data', [
                    'question_data' => $questionData
                ]);
                continue;
            }

            $question = VideoQuestion::create([
                'video_id' => $video->id,
                'question' => $questionData['question'],
                'order' => $index + 1,
                'is_active' => true,
            ]);

            Log::info('Created question', [
                'question_id' => $question->id,
                'question_text' => $question->question
            ]);

            // Create options for this question
            foreach ($questionData['options'] as $optionIndex => $optionText) {
                if (empty($optionText)) {
                    Log::warning('Skipping empty option', [
                        'option_index' => $optionIndex,
                        'option_text' => $optionText
                    ]);
                    continue;
                }

                $option = VideoQuestionOption::create([
                    'video_question_id' => $question->id,
                    'option_text' => $optionText,
                    'option_order' => $optionIndex + 1,
                    'is_correct' => ($optionIndex + 1) == $questionData['correct_option'],
                ]);

                Log::info('Created option', [
                    'option_id' => $option->id,
                    'option_text' => $option->option_text,
                    'is_correct' => $option->is_correct
                ]);
            }
        }
    }

    /**
     * Submit answer to a video question
     */
    public function submitAnswer(Request $request, Video $video, VideoQuestion $question)
    {
        $user = Auth::user();

        // Check if user can access this video
        if (!$video->canUserAccess($user->id)) {
            return response()->json(['error' => 'You do not have access to this video.'], 403);
        }

        // Check if question belongs to this video
        if ($question->video_id !== $video->id) {
            return response()->json(['error' => 'Invalid question for this video.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'option_id' => 'required|exists:video_question_options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid option selected.'], 400);
        }

        $selectedOption = VideoQuestionOption::find($request->option_id);

        // Check if option belongs to this question
        if ($selectedOption->video_question_id !== $question->id) {
            return response()->json(['error' => 'Invalid option for this question.'], 400);
        }

        // Check if user already answered this question
        $existingResponse = VideoQuestionResponse::where('user_id', $user->id)
            ->where('video_question_id', $question->id)
            ->first();

        if ($existingResponse) {
            return response()->json(['error' => 'You have already answered this question.'], 400);
        }

        // Create response
        VideoQuestionResponse::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'video_question_id' => $question->id,
            'video_question_option_id' => $selectedOption->id,
            'is_correct' => $selectedOption->is_correct,
            'answered_at' => now(),
        ]);

        // Calculate updated progress
        $progress = $video->calculateUserProgress($user->id);

        return response()->json([
            'success' => true,
            'is_correct' => $selectedOption->is_correct,
            'correct_option_id' => $question->correctOption()->first()->id,
            'progress' => $progress,
        ]);
    }

    /**
     * Get user's learning progress for a video
     */
    public function getLearningProgress(Video $video)
    {
        $user = Auth::user();

        // Check if user can access this video
        if (!$video->canUserAccess($user->id)) {
            return response()->json(['error' => 'You do not have access to this video.'], 403);
        }

        $progress = $video->calculateUserProgress($user->id);

        return response()->json([
            'progress' => $progress,
            'has_questions' => $video->hasQuestions(),
            'total_questions' => $video->getTotalQuestionsAttribute(),
        ]);
    }

    /**
     * Update video questions and options
     */
    private function updateVideoQuestions(Video $video, array $questions)
    {
        // Remove all existing questions and options
        $this->removeAllVideoQuestions($video);

        // Create new questions
        $this->createVideoQuestions($video, $questions);
    }

    /**
     * Remove all questions and options for a video
     */
    private function removeAllVideoQuestions(Video $video)
    {
        // Delete all question responses first
        VideoQuestionResponse::where('video_id', $video->id)->delete();
        
        // Delete all question options
        VideoQuestionOption::whereHas('question', function($query) use ($video) {
            $query->where('video_id', $video->id);
        })->delete();
        
        // Delete all questions
        VideoQuestion::where('video_id', $video->id)->delete();
    }
}
