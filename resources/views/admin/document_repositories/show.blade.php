@extends('layouts.admin.app')
@section('title', $page_title)
@section('content')
<style>
    table {
        margin: 0 auto;
    }

   
    table {
        color: #333;
        background: white;
        border: 1px solid grey;
        font-size: 10pt;
        width: 100%;
        border-collapse: collapse;
    }

    table thead th,
    table tfoot th {
        color: #000;
        background: rgb(254 153 1);
    }

    table caption {
        padding: .5em;
    }

    table th,
    table td {
        padding: 0.3em;
        height: 10px;
        border: 1px solid lightgrey;
    }

    p {
        margin: 20px 15px 10px;
    }

    .col-md-4 {
        width: 25%;
        float: right;
    }

    /* Zebra Table Style */
    [data-table-theme*=zebra] tbody tr:nth-of-type(odd) {
        background: rgba(0, 0, 0, .05);
    }

    [data-table-theme*=zebra][data-table-theme*=dark] tbody tr:nth-of-type(odd) {
        background: rgba(255, 255, 255, .05);
    }

    /* Dark Style */
    [data-table-theme*=dark] {
        color: #ddd;
        background: #333;
        font-size: 10px;
        border-collapse: collapse;
    }

    [data-table-theme*=dark] thead th,
    [data-table-theme*=dark] tfoot th {
        color: #aaa;
        background: rgba(0255, 255, 255, .15);
    }

    [data-table-theme*=dark] caption {
        padding: .5em;
    }

    [data-table-theme*=dark] th,
    [data-table-theme*=dark] td {
        padding: .5em;
        border: 1px solid grey;
    }

    .main,
    .sec-head,
    .third-head,
    .fourth-head,
    .fifth-head,
    .sixth-head,
    .seventh-head,
    .eight-head,
    .nineth-head,
    .tenth-head,
    .eleventh-head,
    .twelve-head,
    .thirteen-head,
    .fourteen-head {
        text-transform: uppercase;
        margin: 5px 0px;
        font-size: 16px;
        font-weight: 700;
        color: #031d5a;
        text-shadow: 1px 1px 2px black;
    }


    .back-image {
        padding: 5px 0;
        width: 30%;
        display: block !important;
        margin: 0 auto !important;
    }

    .billing,
    .shipping {
        margin-left: 4%;
        width: 43%;
        display: inline-block;
        vertical-align: top;
        margin-right: 1%;
    }

    .shipping {
        margin-right: 0;
       
    }



    .complete {
        position: absolute;
        top: 810px;
        left: 360px;
        width: 50%;
    }

    .complete2 {
        position: absolute;
        top: 585px;
        left: 360px;
        width: 50%;
    }

    .current-address {
        height: 46px !important;
        position: fixed;
    }

    .permanent-address {
        height: 46px !important;
        position: fixed;
    }

    .complete-table1 {
        margin-bottom: 5px;
    }

    .children-detail {
        height: 123px !important;
    }

    .first-page {
        margin-bottom: 20px;
    }

    .first-page-2 {
        margin-bottom: 150px;
    }

    .second-page {
        margin-bottom: 100px;
    }

    .reference1 {
        margin-bottom: 30px;
    }

    .third-page {
        margin-bottom: 520px;
    }

    .documents {
        width: 75%;
        margin-left: 0px;
        margin-top: 20px;
        margin-bottom: 70px;
    }

    .documents2 {
        width: 75%;
        margin-left: 0px;
    }


    .information-first {
        margin-bottom: 20px;
    }

    .information-signature {
        width: 35%;
        margin-left: 0px;
        margin-bottom: 30px;
    }

    .invoice-dates {
        font-size: 16px;
    }
    
    .content-fourth {
        height: 700px !important;
        margin-bottom: 300px;
    }
</style>
<style>
    .file-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
    padding: 20px;
}

/* File Item Style */
.file-item {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
    max-width: 200px;
    overflow: hidden;
    white-space: normal;
}

/* Hover effect for file items */
.file-item:hover {
    box-shadow: 0 4px 8px rgba(26, 1, 1, 0.52);
    transform: translateY(-5px);
}

/* File icons style */
.file-icon {
    width: 80%; /* Set icon size */
    height: auto;
    margin-bottom: 10px;
}

/* Image files */
.file-image {
    width: 100%;
    height: auto;
    border-radius: 5px;
    margin-bottom: 10px;
}

/* File link styles */
.file-link {
    text-decoration: none;
    color: #004b85;
    font-weight: bold;
}

/* Adjust the file name text */
.file-item p {
    font-size: 12px;
    color: #333;
    margin-top: 10px;
    word-wrap: break-word;
}
</style>

<section class="content-header">
    <div class="content-header-left">
        <h1>Document Details</h1>
    </div>
    <div class="content-header-right">
        <a href="{{ route('documents.index') }}" class="btn btn-primary btn-sm">View All</a>
        <!-- <a href="{{ route('documents.pdf', $document->id) }}" class="btn btn-primary btn-sm">Download PDF</a> -->
    </div>
</section>

<section class="content">
    <div class="container">
        <img class="back-image" src="{{ asset('public/admin/assets/images') }}/main-logo.png" alt="">
        <h4 class="main" style="text-align: center;">DOCUMENT DETAILS</h4>
        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-body">
                        <div class="row box-header">
                            <div class="col-md-6">
                                <h4 class="box-title">Project Category: <h3>{{ ucfirst($document->parent_id) }}</h3></h4>
                            </div>
                            <div class="col-md-6">
                                <h4 class="box-title"> Project Name:<h3>{{ isset($document->project) ? ucfirst($document->project->name) : 'N/A' }}</h3></h4>
                            </div>
                        </div>

                        <div class="row invoice-dates">
                            <div class="col-md-6">
                                <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($document->start_date)->format('d/M/Y') ?? 'N/A' }}</p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($document->end_date)->format('d/M/Y') ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <br>

                        <!-- Displaying the document files -->
                        <h4 class="main text-center">Attached Files</h4>
                        @if($document->document_file && count(json_decode($document->document_file)) > 0)
                            <div class="file-grid">
                                @foreach(json_decode($document->document_file) as $file)
                                    @php
                                        // Get the file extension
                                        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                                        
                                        // Define the file URL for storage in the public directory
                                        $fileUrl = asset('public/admin/assets/images/documents/' . $file);
                                    @endphp

                                    <div class="file-item">
                                        @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) 
                                            <!-- Display image files -->
                                            <a href="{{ $fileUrl }}" target="_blank">
                                                <img src="{{ $fileUrl }}" alt="{{ $file }}" class="file-image">
                                            </a>
                                        @elseif($fileExtension == 'pdf') 
                                            <!-- Display PDF icon -->
                                            <a href="{{ $fileUrl }}" target="_blank" class="file-link">
                                                <img src="{{ asset('public/admin/assets/icons/pdf-icon.png') }}" alt="PDF Icon" class="file-icon">
                                            </a>
                                        @elseif(in_array($fileExtension, ['docx', 'doc'])) 
                                            <!-- Display Word document icon -->
                                            <a href="{{ $fileUrl }}" target="_blank" class="file-link">
                                                <img src="{{ asset('public/admin/assets/icons/word-icon.png') }}" alt="Word Icon" class="file-icon">
                                            </a>
                                        @elseif(in_array($fileExtension, ['xlsx', 'xls'])) 
                                            <!-- Display Excel file icon -->
                                            <a href="{{ $fileUrl }}" target="_blank" class="file-link">
                                                <img src="{{ asset('public/admin/assets/icons/excel-icon.png') }}" alt="Excel Icon" class="file-icon">
                                            </a>
                                        @elseif($fileExtension == 'zip')
                                            <!-- Display Zip file icon, user will probably download -->
                                            <a href="{{ $fileUrl }}" target="_blank" class="file-link">
                                                <img src="{{ asset('public/admin/assets/icons/zip-icon.png') }}" alt="Zip Icon" class="file-icon">
                                            </a>
                                        @else
                                            <!-- Display generic file icon for other file types -->
                                            <a href="{{ $fileUrl }}" target="_blank" class="file-link">
                                                <img src="{{ asset('public/admin/assets/icons/file-icon.png') }}" alt="File Icon" class="file-icon">
                                            </a>
                                        @endif
                                        <p>{{ $file }}</p> <!-- Display file name -->
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>No files attached to this document.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection