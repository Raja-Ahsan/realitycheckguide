@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>{{ $page_title }}</h3>
        <a class="btn btn-primary" href="{{ route('submittals.create') }}">Create Submittal</a>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($models as $model)
                        <tr>
                            <td>{{ $model->id }}</td>
                            <td>{{ $model->title }}</td>
                            <td>{{ optional($model->project)->name }}</td>
                            <td>{{ ucfirst(str_replace('_',' ', $model->status)) }}</td>
                            <td>{{ $model->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('submittals.edit', $model->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <a href="{{ route('submittals.export-pdf', $model->id) }}" class="btn btn-sm btn-secondary">PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No submittals found</td></tr>
                    @endforelse
                </tbody>
            </table>

            {{ $models->links() }}
        </div>
    </div>
</div>
@endsection


