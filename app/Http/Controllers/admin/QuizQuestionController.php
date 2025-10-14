<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\QuizCategory;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizQuestionController extends Controller
{
    /**
     * Display a listing of quiz questions
     */
    public function index(Request $request)
    {
        $query = QuizQuestion::with('category');

        // Filter by category if specified
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status if specified
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $questions = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = QuizCategory::active()->get();

        return view('admin.quiz.questions.index', compact('questions', 'categories'));
    }

    /**
     * Show the form for creating a new quiz question
     */
    public function create()
    {
        $categories = QuizCategory::active()->get();
        return view('admin.quiz.questions.create', compact('categories'));
    }

    /**
     * Store a newly created quiz question
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:quiz_categories,id',
            'question' => 'required|string|max:2000',
            'option_a' => 'required|string|max:1000',
            'option_b' => 'required|string|max:1000',
            'option_c' => 'required|string|max:1000',
            'option_d' => 'required|string|max:1000',
            'correct_option' => 'required|in:A,B,C,D',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        QuizQuestion::create([
            'category_id' => $request->category_id,
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_option' => $request->correct_option,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.quiz.questions.index')
            ->with('success', 'Quiz question created successfully.');
    }

    /**
     * Show the form for editing the specified quiz question
     */
    public function edit(QuizQuestion $question)
    {
        $categories = QuizCategory::active()->get();
        return view('admin.quiz.questions.edit', compact('question', 'categories'));
    }

    /**
     * Update the specified quiz question
     */
    public function update(Request $request, QuizQuestion $question)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:quiz_categories,id',
            'question' => 'required|string|max:2000',
            'option_a' => 'required|string|max:1000',
            'option_b' => 'required|string|max:1000',
            'option_c' => 'required|string|max:1000',
            'option_d' => 'required|string|max:1000',
            'correct_option' => 'required|in:A,B,C,D',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $question->update([
            'category_id' => $request->category_id,
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_option' => $request->correct_option,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.quiz.questions.index')
            ->with('success', 'Quiz question updated successfully.');
    }

    /**
     * Remove the specified quiz question
     */
    public function destroy(QuizQuestion $question)
    {
        $question->delete();

        return redirect()->route('admin.quiz.questions.index')
            ->with('success', 'Quiz question deleted successfully.');
    }

    /**
     * Toggle question status
     */
    public function toggleStatus(QuizQuestion $question)
    {
        $question->update(['is_active' => !$question->is_active]);

        $status = $question->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Quiz question {$status} successfully.");
    }

    /**
     * Bulk actions for questions
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'question_ids' => 'required|array|min:1',
            'question_ids.*' => 'exists:quiz_questions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Invalid bulk action request.');
        }

        $questionIds = $request->question_ids;
        $action = $request->action;

        switch ($action) {
            case 'activate':
                QuizQuestion::whereIn('id', $questionIds)->update(['is_active' => true]);
                $message = 'Selected questions activated successfully.';
                break;
            case 'deactivate':
                QuizQuestion::whereIn('id', $questionIds)->update(['is_active' => false]);
                $message = 'Selected questions deactivated successfully.';
                break;
            case 'delete':
                QuizQuestion::whereIn('id', $questionIds)->delete();
                $message = 'Selected questions deleted successfully.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}
