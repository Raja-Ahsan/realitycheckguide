<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\QuizCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizCategoryController extends Controller
{
    /**
     * Display a listing of quiz categories
     */
    public function index()
    {
        $categories = QuizCategory::withCount(['questions', 'activeQuestions'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.quiz.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new quiz category
     */
    public function create()
    {
        return view('admin.quiz.categories.create');
    }

    /**
     * Store a newly created quiz category
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:quiz_categories,name',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        QuizCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.quiz.categories.index')
            ->with('success', 'Quiz category created successfully.');
    }

    /**
     * Show the form for editing the specified quiz category
     */
    public function edit(QuizCategory $category)
    {
        return view('admin.quiz.categories.edit', compact('category'));
    }

    /**
     * Update the specified quiz category
     */
    public function update(Request $request, QuizCategory $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:quiz_categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.quiz.categories.index')
            ->with('success', 'Quiz category updated successfully.');
    }

    /**
     * Remove the specified quiz category
     */
    public function destroy(QuizCategory $category)
    {
        // Check if category has questions
        if ($category->questions()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category that has questions. Please delete all questions first.');
        }

        $category->delete();

        return redirect()->route('admin.quiz.categories.index')
            ->with('success', 'Quiz category deleted successfully.');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(QuizCategory $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Quiz category {$status} successfully.");
    }
}
