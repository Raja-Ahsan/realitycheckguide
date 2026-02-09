@extends('layouts.admin.app')
@section('title', $page_title)
@section('content')

<style>
        .file-preview {
            margin-bottom: 10px;
            display: inline-block;
            text-align: center;
            padding: 10px;
        }

        .file-preview img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            margin-bottom: 5px;
        }

        .file-preview p {
            margin-top: 5px;
            font-size: 12px;
        }

        .file-preview .remove-file {
            color: red;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
<section class="content-header">
    <div class="content-header-left">
        <h1>{{ $page_title }}</h1>
    </div>
    <div class="content-header-right">
        <a href="{{ route('documents.index') }}" class="btn btn-primary btn-sm">View All</a>
    </div>
</section>

<section class="content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{ route('documents.store') }}" id="invoiceForm" class="form-horizontal" enctype="multipart/form-data" method="post">
                @csrf
                <div class="box box-info">
                    <div class="box-body"> 
                        <!-- Project Name -->
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Project Name <span style='color:red'>*</span></label>
                            <div class="col-md-8">
                                <select name="project_id" id="project_id" class="form-control" required>
                                    <option value="" selected>Select Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                <span style="color: red">{{ $errors->first('project_id') }}</span>
                            </div>
                        </div>

                        <!-- Project Category -->
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Project Category</label>
                            <div class="col-md-8">
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="0" selected> Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->slug }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>
                                <span style="color: red">{{ $errors->first('parent_id') }}</span>
                            </div>
                        </div>

                        <!-- Relevant Documents -->
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Relevant Documents<span style='color:red'>*</span></label>
                            <div class="col-sm-8" style="padding-top:5px">  
                                <input type="file" class="form-control" accept="image*, .pdf, .doc, .docx, .xls, .xlsx, .csv" name="document_file[]" id="document_file" multiple required>
                                <span style="color: red">{{ $errors->first('document_file') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-8" id="file-preview-container">
                                <!-- Preview of selected files will be appended here -->
                            </div>
                        </div>

                        <!-- Displaying file names -->
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">File Names</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="file_name" name="file_name" placeholder="File names will appear here" readonly>
                            </div>
                        </div>



                        <!-- Submit Button -->
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success pull-left">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection

@push('js')
<script>

// Handle file selection for preview
document.getElementById('document_file').addEventListener('change', function(evt) {
    const files = evt.target.files; // Get all selected files
    const previewContainer = document.getElementById('file-preview-container');
    const fileNameInput = document.getElementById('file_name');

    // Clear previous previews and file names
    previewContainer.innerHTML = '';
    fileNameInput.value = ''; // Reset file name input

    // Initialize an array to store file names (without extension)
    let fileNames = [];

    // Loop through selected files and create previews
    Array.from(files).forEach(file => {
        const reader = new FileReader();
        const previewDiv = document.createElement('div');
        previewDiv.classList.add('file-preview'); // Add class for styling

        reader.onload = function(e) {
            const fileExtension = file.name.split('.').pop().toLowerCase(); // Get file extension

            // Render file preview based on file type
            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                // Display image preview
                const img = document.createElement('img');
                img.style.width = '100px';
                img.src = e.target.result; // Set image preview
                previewDiv.appendChild(img);
            } else if (fileExtension === 'pdf') {
                // Display PDF link
                const link = document.createElement('a');
                link.href = e.target.result;
                link.target = '_blank';
                link.classList.add('message-file');
                link.textContent = 'View PDF';
                previewDiv.appendChild(link);
            } else if (['xls', 'xlsx', 'csv'].includes(fileExtension)) {
                // Display Excel file link
                const link = document.createElement('a');
                link.href = e.target.result;
                link.target = '_blank';
                link.classList.add('message-file');
                link.textContent = 'Download Excel File';
                previewDiv.appendChild(link);
            } else {
                // Default file link for other file types
                const link = document.createElement('a');
                link.href = e.target.result;
                link.target = '_blank';
                link.classList.add('message-file');
                link.textContent = 'Download File';
                previewDiv.appendChild(link);
            }

            // Show the file name without extension
            const fileName = document.createElement('p');
            const fileNameWithoutExtension = file.name.split('.').slice(0, -1).join('.'); // Remove the extension
            fileName.textContent = fileNameWithoutExtension; // Display the file name without extension
            previewDiv.appendChild(fileName);

            // Add the file name (without extension) to the fileNames array
            fileNames.push(fileNameWithoutExtension);

            // Update the file name input once the reader loads the file
            fileNameInput.value = fileNames.join(', '); // Join file names with a comma
        };

        // Read the file
        reader.readAsDataURL(file);

        // Append the preview to the container
        previewContainer.appendChild(previewDiv);
    });
});




</script>
@endpush
