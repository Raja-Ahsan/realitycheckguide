<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentRepository;
use App\Models\ProjectCategory; 
use App\Models\Project; 
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Str;
use Auth;

class DocumentRepositoryController extends Controller
{
    /**
     * Constructor to apply middleware for permissions
     */
    public function __construct()
    {
        $this->middleware('permission:documents-list|documents-create|documents-edit|documents-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:documents-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:documents-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:documents-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the document repositories.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DocumentRepository::orderBy('id', 'desc');
            if ($request['search'] != "") {
                $query->where('file_name', 'like', '%' . $request['search'] . '%')
                    ->orWhere('size', 'like', '%' . $request['search'] . '%')
                    ->orWhere('status', 'like', '%' . $request['search'] . '%');
            }

            if ($request['status'] != "All") {
                $query->where('status', $request['status']);
            }

            $models = $query->paginate(10);
            return view('admin.document_repositories.search', compact('models'));
        }

        $page_title = 'Project Documents';
        $models = DocumentRepository::orderBy('id', 'desc')->paginate(10);
        return view('admin.document_repositories.index', compact('models', 'page_title'));
    }

    /**
     * Show the form for creating a new document repository.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Add Project Documents';
        $projects = Project::where('created_by', Auth::user()->id)->get(); // Get active projects
        $categories = ProjectCategory::where('status', 1)->get(); // Get active categories
        return view('admin.document_repositories.create', compact('projects', 'categories', 'page_title'));
    }

    /**
     * Store a newly created document repository.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
   


    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'parent_id' => 'required',
            'project_id' => 'required|exists:projects,id', // Ensure project_id exists in the projects table
            'document_file' => 'required|array', // Expecting an array of files
            'document_file.*' => 'file',  // Ensure each file is valid
        ]);
    
        // Create a new document repository instance
        $documentRepository = new DocumentRepository();
        
        // Initialize an array to hold all file names (with and without extensions)
        $allFileNames = [];
        $totalSize = 0; // To store the total size of all files
    
        // Loop through all the files in the document_file array
        foreach ($request->file('document_file') as $file) {
            // Handle the document file upload
            $fileNameWithExtension = $file->getClientOriginalName(); // Full file name with extension
            $fileSize = $file->getSize(); // Get file size in bytes
            $totalSize += $fileSize; // Add file size to total size
    
            // Move the file to the server directory with the original file name
            $file->move(public_path('/admin/assets/images/documents'), $fileNameWithExtension);
    
            // Append the file name with extension to the array
            $allFileNames[] = $fileNameWithExtension;
    
            // Store the file name (without extension) in 'file_name' column
            $documentRepository->file_name = pathinfo($fileNameWithExtension, PATHINFO_FILENAME); // Without extension
        }
    
        // Store the full file names as a JSON array in 'document_file' column
        $documentRepository->document_file = json_encode($allFileNames);
    
        // Store the size of the files in the 'size' column (in MB/GB)
        if ($totalSize >= 1073741824) { // If the total size is greater than 1GB
            $documentRepository->size = round($totalSize / 1073741824, 2) . ' GB'; // Convert to GB
        } elseif ($totalSize >= 1048576) { // If the total size is greater than 1MB
            $documentRepository->size = round($totalSize / 1048576, 2) . ' MB'; // Convert to MB
        } elseif ($totalSize >= 1024) { // If the total size is greater than 1KB
            $documentRepository->size = round($totalSize / 1024, 2) . ' KB'; // Convert to KB
        } else {
            $documentRepository->size = $totalSize . ' bytes'; // For very small files
        }
    
        // Assign other attributes for the document repository
        $documentRepository->created_by = Auth::user()->id;
        $documentRepository->parent_id = $request->parent_id;
        $documentRepository->project_id = $request->project_id; // Link document to project
    
        // Save the document record to the DocumentRepository table
        $documentRepository->save();
    
        // Redirect with a success message
        return redirect()->route('documents.index')->with('message', 'Document repository added successfully!');
    }
    
    



    /**
     * Display the specified document repository.
     *
     * @param DocumentRepository $documentRepository
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_title = "Project Document Details";

        // Fetch the game using the slug
        $document = DocumentRepository::where('id', $id)->firstOrFail();

        // Fetch categories (if needed)
        $categories = ProjectCategory::where('status', 1)->get();

        // Pass the game data to the view
        return view('admin.document_repositories.show', compact('document', 'categories', 'page_title'));
    }
   

    /**
     * Show the form for editing the specified document repository.
     *
     * @param DocumentRepository $documentRepository
     * @return \Illuminate\Http\Response
     */
   /*  public function edit($id)
    {
        $page_title = 'Edit Document Repository'; 
        $document = DocumentRepository::where('id', $id)->first();
        $categories = ProjectCategory::where('status', 1)->get(); // Get active categories
        return view('admin.document_repositories.edit', compact('document', 'categories', 'page_title'));
    } */

    public function edit($id)
    {
        $page_title = 'Edit Project Documents'; 
        $document = DocumentRepository::findOrFail($id); // Find the document by id
        $categories = ProjectCategory::where('status', 1)->get(); // Get active categories
        $projects = Project::where('created_by', Auth::user()->id)->get(); // Get projects for the current user (if applicable)

        return view('admin.document_repositories.edit', compact('document', 'categories', 'projects', 'page_title'));
    }


    /**
     * Update the specified document repository.
     *
     * @param Request $request
     * @param DocumentRepository $documentRepository
     * @return \Illuminate\Http\Response
     */
  
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'parent_id' => 'required',
            'project_id' => 'required|exists:projects,id', // Ensure project_id exists in the projects table
            'size' => 'nullable|string',
            'document_file' => 'nullable|array', // Expecting an array of files
            'document_file.*' => 'file',  // Ensure each file is valid 
        ]);
    
        // Find the document repository by id
        $documentRepository = DocumentRepository::findOrFail($id);
    
        // Store the existing files in an array
        $existingFiles = json_decode($documentRepository->document_file, true) ?: [];
    
        // Handle file upload if new files are uploaded
        if ($request->hasFile('document_file')) {
            $uploadedFiles = $request->file('document_file');
            
            // Initialize an array to hold all file names (existing + new)
            $newFiles = [];
            $totalSize = 0; // To store the total size of files
    
            // Loop through uploaded files and handle them
            foreach ($uploadedFiles as $file) {
                $fileNameWithExtension = $file->getClientOriginalName(); // Full file name with extension
    
                // Extract only the file name without extension
                $fileNameWithoutExtension = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
    
                // Get the size of the uploaded document before moving the file
                $fileSize = $file->getSize(); // Get file size in bytes
                $totalSize += $fileSize; // Add the file size to the total size
    
                // Move the file to the server directory with the original file name
                $file->move(public_path('/admin/assets/images/documents'), $fileNameWithExtension);
    
                // Add the new file name to the new files array
                $newFiles[] = $fileNameWithExtension;
            }
    
            // Combine existing files with new files
            $allFiles = array_merge($existingFiles, $newFiles);
            $documentRepository->document_file = json_encode($allFiles); // Store the files as a JSON array
    
            // Optionally, calculate the total size
            if ($totalSize >= 1073741824) {
                $documentRepository->size = round($totalSize / 1073741824, 2) . ' GB'; // Convert to GB
            } elseif ($totalSize >= 1048576) {
                $documentRepository->size = round($totalSize / 1048576, 2) . ' MB'; // Convert to MB
            } elseif ($totalSize >= 1024) {
                $documentRepository->size = round($totalSize / 1024, 2) . ' KB'; // Convert to KB
            } else {
                $documentRepository->size = $totalSize . ' bytes'; // For very small files
            }
        }
    
        // Update other document details
        $documentRepository->update([
            'parent_id' => $request->parent_id,
            'project_id' => $request->project_id, // Link document to project
            /* 'status' => $request->status, */
        ]);
    
        // Redirect with success message
        return redirect()->route('documents.index')->with('message', 'Document repository updated successfully!');
    }
    

    public function deleteFile($id, Request $request)
    {
        
        // Get the file name from the request
        $fileName = $request->input('file_name');
        $document = DocumentRepository::findOrFail($id);  // Find the document by ID

        // Define the file path
        $filePath = public_path('admin/assets/images/documents/' . $fileName);

        // Check if the file exists on the server
        if (file_exists($filePath)) {
            // Attempt to delete the file
            if (unlink($filePath)) {
                // File successfully deleted, now remove the file name from the document's `document_file` field
                $documentFiles = json_decode($document->document_file, true); // Decode the document file array

                // Remove the deleted file from the array
                $documentFiles = array_diff($documentFiles, [$fileName]);

                // Update the document with the new list of files
                $document->document_file = json_encode(array_values($documentFiles));
                $document->save();  // Save the changes to the document

                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'File could not be deleted from the server.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'File not found on the server.']);
        }
    }

    
    /**
     * Remove the specified document repository.
     *
     * @param DocumentRepository $documentRepository
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = DocumentRepository::where('id', $id)->first();
        if ($model) {
            $model->delete();
            return true;
        } else {
            return response()->json(['message' => 'Failed '], 404);
        }
    }

    public function downloadPDF($id)
    {
        $document = DocumentRepository::where('id', $id)->firstOrFail();

        $pdf = PDF::loadView('admin.document_repositories.pdf', compact('document'));
        $formattedParentId = ucfirst($document->parent_id);
        return $pdf->download($formattedParentId .'-'. $document->name . '.pdf');
    }


     public function downloadDocumentsPDF($id)
    {
        $document = DocumentRepository::findOrFail($id); 
        $project_categories = ProjectCategory::where('status', 1)->get(); 

        // Load the view and pass project and project_categories
        $pdf = PDF::loadView('admin.document_repositories.documentpdf', compact('document', 'project_categories'));
        $fileName = 'document-details-' . '.pdf';

        return $pdf->download($fileName);
    }
}
