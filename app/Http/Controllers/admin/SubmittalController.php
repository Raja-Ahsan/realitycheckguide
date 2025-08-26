<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Submittal;
use App\Models\Project;
use App\Models\Specification;
use App\Models\SubmittalSection;
use App\Models\SubmittalTimelineEvent;
use App\Models\CoverTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SubmittalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:submittals-list|submittals-create|submittals-edit|submittals-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:submittals-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:submittals-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:submittals-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Submittal::query()->with(['project']);
        $user = Auth::user();
        $isAdmin = $user && isset($user->role) && strtolower($user->role) === 'admin';
        if (!$isAdmin) {
            $query->where('created_by', Auth::id());
        }
        $models = $query->orderByDesc('id')->paginate(10);
        $page_title = 'Submittals';
        return view('admin.submittals.index', compact('models', 'page_title'));
    }

    public function create(Request $request)
    {
        $projects = Project::orderByDesc('id')->get();
        $page_title = 'Create Submittal';
        return view('admin.submittals.create', compact('projects', 'page_title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
        ]);

        $submittal = Submittal::create([
            'project_id' => $request->project_id,
            'created_by' => Auth::id(),
            'title' => $request->title,
            'status' => 'pending',
        ]);

        SubmittalTimelineEvent::create([
            'submittal_id' => $submittal->id,
            'event_type' => 'created',
            'message' => 'Submittal created',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('submittals.edit', $submittal->id)->with('success', 'Submittal created');
    }

    public function edit($id)
    {
        $submittal = Submittal::with(['sections'])->findOrFail($id);
        $project = $submittal->project;
        $specs = Specification::where('project_id', $project->id)->orderByDesc('id')->get();
        $templates = CoverTemplate::orderByDesc('id')->get();
        $page_title = 'Edit Submittal';
        return view('admin.submittals.edit', compact('submittal', 'project', 'specs', 'templates', 'page_title'));
    }

    public function uploadSpec(Request $request, $id)
    {
        $request->validate([
            'spec_file' => 'required|file|mimes:pdf,doc,docx',
        ]);

        $submittal = Submittal::findOrFail($id);
        $path = $request->file('spec_file')->store('specs/'.date('Y/m'), 'public');

        $spec = Specification::create([
            'project_id' => $submittal->project_id,
            'uploaded_by' => Auth::id(),
            'original_filename' => $request->file('spec_file')->getClientOriginalName(),
            'stored_path' => $path,
        ]);

        return back()->with('success', 'Specification uploaded');
    }

    public function breakoutSections(Request $request, $id)
    {
        $request->validate([
            'sections' => 'required|array',
        ]);
        $submittal = Submittal::findOrFail($id);

        foreach ($request->sections as $section) {
            SubmittalSection::updateOrCreate(
                [
                    'submittal_id' => $submittal->id,
                    'spec_section' => $section['spec_section'] ?? null,
                ],
                [
                    'title' => $section['title'] ?? null,
                    'manufacturer' => $section['manufacturer'] ?? null,
                    'product_type' => $section['product_type'] ?? null,
                    'extracted_data' => $section['extracted_data'] ?? null,
                    'included' => $section['included'] ?? true,
                ]
            );
        }

        return back()->with('success', 'Sections updated');
    }

    public function exportPdf($id)
    {
        $submittal = Submittal::with(['sections'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.submittals.pdf', [
            'submittal' => $submittal,
        ]);
        return $pdf->download('submittal-'.$submittal->id.'.pdf');
    }

    public function sendEmail(Request $request, $id)
    {
        $request->validate([
            'to_email' => 'required|email',
        ]);
        $submittal = Submittal::findOrFail($id);
        $pdf = Pdf::loadView('admin.submittals.pdf', [ 'submittal' => $submittal ]);
        $pdfData = $pdf->output();

        Mail::raw('Please find attached the submittal.', function ($message) use ($request, $pdfData, $submittal) {
            $message->to($request->to_email)
                ->subject('Submittal: '.$submittal->title)
                ->attachData($pdfData, 'submittal-'.$submittal->id.'.pdf');
        });

        $submittal->update(['status' => 'sent']);
        SubmittalTimelineEvent::create([
            'submittal_id' => $submittal->id,
            'event_type' => 'sent',
            'message' => 'Submittal sent to '.$request->to_email,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Email sent');
    }

    public function setReminder(Request $request, $id)
    {
        $request->validate([
            'remind_at' => 'required|date',
        ]);
        $submittal = Submittal::findOrFail($id);
        $submittal->update(['remind_at' => $request->remind_at]);
        SubmittalTimelineEvent::create([
            'submittal_id' => $submittal->id,
            'event_type' => 'reminder_set',
            'message' => 'Reminder set for '.$request->remind_at,
            'created_by' => Auth::id(),
        ]);
        return back()->with('success', 'Reminder scheduled');
    }

    public function markReceived(Request $request, $id)
    {
        $request->validate([
            'received_file' => 'nullable|file|mimes:pdf,doc,docx',
            'comment' => 'nullable|string',
        ]);
        $submittal = Submittal::findOrFail($id);
        $path = null;
        if ($request->hasFile('received_file')) {
            $path = $request->file('received_file')->store('submittals/'.date('Y/m'), 'public');
        }
        $submittal->update([
            'status' => 'pending',
            'received_document_path' => $path,
        ]);
        SubmittalTimelineEvent::create([
            'submittal_id' => $submittal->id,
            'event_type' => 'received',
            'message' => $request->comment,
            'created_by' => Auth::id(),
        ]);
        return back()->with('success', 'Marked as received');
    }

    public function sendToVendor(Request $request, $id)
    {
        $request->validate([
            'vendor_email' => 'required|email',
            'body' => 'nullable|string',
        ]);
        $submittal = Submittal::findOrFail($id);
        Mail::raw($request->body ?: 'Please see comments for corrections.', function ($message) use ($request) {
            $message->to($request->vendor_email)->subject('Submittal Corrections');
        });
        $submittal->update([
            'vendor_email' => $request->vendor_email,
            'last_sent_to_vendor_at' => now(),
        ]);
        SubmittalTimelineEvent::create([
            'submittal_id' => $submittal->id,
            'event_type' => 'vendor_sent',
            'message' => 'Sent to vendor '.$request->vendor_email,
            'created_by' => Auth::id(),
        ]);
        return back()->with('success', 'Sent to vendor');
    }

    public function vendorReturned(Request $request, $id)
    {
        $request->validate([
            'vendor_file' => 'nullable|file|mimes:pdf,doc,docx',
            'comment' => 'nullable|string',
        ]);
        $submittal = Submittal::findOrFail($id);
        $path = null;
        if ($request->hasFile('vendor_file')) {
            $path = $request->file('vendor_file')->store('submittals/vendor/'.date('Y/m'), 'public');
        }
        SubmittalTimelineEvent::create([
            'submittal_id' => $submittal->id,
            'event_type' => 'vendor_returned',
            'message' => $request->comment,
            'created_by' => Auth::id(),
        ]);
        return back()->with('success', 'Vendor returned file logged');
    }

    public function extractComments(Request $request, $id)
    {
        $request->validate([
            'comments' => 'required|string',
        ]);
        $submittal = Submittal::findOrFail($id);
        SubmittalTimelineEvent::create([
            'submittal_id' => $submittal->id,
            'event_type' => 'comment_extracted',
            'message' => $request->comments,
            'created_by' => Auth::id(),
        ]);
        return back()->with('success', 'Comments extracted');
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,sent,approved,revise_resubmit,rejected',
            'comment' => 'nullable|string',
        ]);
        $submittal = Submittal::findOrFail($id);
        $submittal->update(['status' => $request->status]);
        SubmittalTimelineEvent::create([
            'submittal_id' => $submittal->id,
            'event_type' => $request->status,
            'message' => $request->comment,
            'created_by' => Auth::id(),
        ]);
        return back()->with('success', 'Status updated');
    }
}


