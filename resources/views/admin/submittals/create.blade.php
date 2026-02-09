@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <h3>{{ $page_title }}</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('submittals.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Project</label>
                    <select name="project_id" class="form-control" required>
                        <option value="">Select a project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required />
                </div>
                <button class="btn btn-primary" type="submit">Create</button>
            </form>
        </div>
    </div>
</div>
@endsection


