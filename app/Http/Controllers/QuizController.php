<?php

namespace App\Http\Controllers;

use App\Models\QuizCategory;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    /**
     * Display the quiz page with category selection
     */
    public function index()
    {
        $categories = QuizCategory::active()->get();
        return view('quiz.index', compact('categories'));
    }

    /**
     * Load quiz questions for selected category (AJAX)
     */
    public function loadQuiz(Request $request)
    {
        \Log::info('Quiz loadQuiz method called', [
            'request_data' => $request->all(),
            'category_id' => $request->category_id,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email
        ]);

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:quiz_categories,id',
        ]);

        if ($validator->fails()) {
            \Log::error('Quiz validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid category selected.',
                'errors' => $validator->errors()
            ], 400);
        }

        $category = QuizCategory::with('activeQuestions')->find($request->category_id);
        
        \Log::info('Category found', [
            'category' => $category ? $category->toArray() : null,
            'active_questions_count' => $category ? $category->activeQuestions->count() : 0
        ]);
        
        if (!$category || $category->activeQuestions->isEmpty()) {
            \Log::warning('No questions found for category', ['category_id' => $request->category_id]);
            return response()->json([
                'success' => false,
                'message' => 'No questions available for this category.',
            ], 404);
        }

        // Shuffle questions for random order
        $questions = $category->activeQuestions->shuffle();

        \Log::info('Quiz loaded successfully', [
            'category_id' => $category->id,
            'questions_count' => $questions->count()
        ]);

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
            ],
            'questions' => $questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question' => $question->question,
                    'options' => [
                        'A' => $question->option_a,
                        'B' => $question->option_b,
                        'C' => $question->option_c,
                        'D' => $question->option_d,
                    ],
                ];
            }),
            'total_questions' => $questions->count(),
        ]);
    }

    /**
     * Submit quiz answers and calculate score
     */
    public function submitQuiz(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:quiz_categories,id',
            'user_name' => 'required|string|max:255',
            'user_email' => 'nullable|email|max:255',
            'answers' => 'required|array|min:1',
            'answers.*' => 'required|in:A,B,C,D',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid quiz submission.',
                'errors' => $validator->errors()
            ], 400);
        }

        $category = QuizCategory::find($request->category_id);
        $questions = QuizQuestion::where('category_id', $request->category_id)
            ->where('is_active', true)
            ->get();

        if ($questions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No questions found for this category.',
            ], 404);
        }

        $correctAnswers = 0;
        $totalQuestions = $questions->count();
        $results = [];

        // Calculate score
        foreach ($questions as $question) {
            $userAnswer = $request->answers[$question->id] ?? null;
            $isCorrect = $userAnswer && $question->isCorrectAnswer($userAnswer);
            
            if ($isCorrect) {
                $correctAnswers++;
            }

            $results[] = [
                'question_id' => $question->id,
                'question' => $question->question,
                'user_answer' => $userAnswer,
                'correct_answer' => $question->correct_option,
                'is_correct' => $isCorrect,
                'options' => [
                    'A' => $question->option_a,
                    'B' => $question->option_b,
                    'C' => $question->option_c,
                    'D' => $question->option_d,
                ],
            ];
        }

        $scorePercentage = QuizResult::calculateScorePercentage($correctAnswers, $totalQuestions);

        // Save quiz result
        $quizResult = QuizResult::create([
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'category_id' => $request->category_id,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'score_percentage' => $scorePercentage,
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'result' => [
                'id' => $quizResult->id,
                'user_name' => $quizResult->user_name,
                'category_name' => $category->name,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'score_percentage' => $scorePercentage,
                'grade' => $quizResult->score_grade,
                'completed_at' => $quizResult->completed_at->format('M d, Y H:i'),
            ],
            'detailed_results' => $results,
        ]);
    }

    /**
     * Display quiz results page
     */
    public function results(Request $request)
    {
        $query = QuizResult::with('category');

        // Filter by category if specified
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by date range if specified
        if ($request->filled('date_from')) {
            $query->where('completed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('completed_at', '<=', $request->date_to);
        }

        $results = $query->orderBy('completed_at', 'desc')->paginate(20);
        $categories = QuizCategory::active()->get();

        return view('quiz.results', compact('results', 'categories'));
    }

    /**
     * Get quiz statistics (AJAX)
     */
    public function getStats(Request $request)
    {
        $categoryId = $request->get('category_id');

        $query = QuizResult::query();
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $totalAttempts = $query->count();
        $averageScore = $query->avg('score_percentage');
        $highestScore = $query->max('score_percentage');
        $recentAttempts = $query->recent(7)->count();

        return response()->json([
            'success' => true,
            'stats' => [
                'total_attempts' => $totalAttempts,
                'average_score' => round($averageScore ?? 0, 2),
                'highest_score' => round($highestScore ?? 0, 2),
                'recent_attempts' => $recentAttempts,
            ],
        ]);
    }
}
