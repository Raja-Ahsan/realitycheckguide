<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CoverTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoverTemplateController extends Controller
{
    public function index()
    {
        $models = CoverTemplate::orderByDesc('id')->paginate(10);
        $page_title = 'Cover Templates';
        return view('admin.cover-templates.index', compact('models', 'page_title'));
    }

    public function create()
    {
        $page_title = 'Upload Template';
        return view('admin.cover-templates.create', compact('page_title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf',
        ]);
        $path = $request->file('file')->store('cover-templates/'.date('Y/m'), 'public');
        CoverTemplate::create([
            'name' => $request->name,
            'stored_path' => $path,
            'created_by' => Auth::id(),
        ]);
        return redirect()->route('cover-templates.index')->with('success', 'Template uploaded');
    }

    public function destroy($id)
    {
        $model = CoverTemplate::findOrFail($id);
        $model->delete();
        return back()->with('success', 'Deleted');
    }
}


