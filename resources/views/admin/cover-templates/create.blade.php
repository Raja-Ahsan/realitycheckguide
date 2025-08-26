@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <h3>{{ $page_title }}</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('cover-templates.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Template Name</label>
                    <input type="text" name="name" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Template PDF</label>
                    <input type="file" name="file" class="form-control" accept="application/pdf" required />
                </div>
                <button class="btn btn-primary" type="submit">Upload</button>
            </form>
        </div>
    </div>
</div>
@endsection


