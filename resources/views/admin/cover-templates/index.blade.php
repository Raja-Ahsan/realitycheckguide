@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h3>{{ $page_title }}</h3>
        <a href="{{ route('cover-templates.create') }}" class="btn btn-primary">Upload Template</a>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Path</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($models as $m)
                        <tr>
                            <td>{{ $m->id }}</td>
                            <td>{{ $m->name }}</td>
                            <td>{{ $m->stored_path }}</td>
                            <td>
                                <form method="POST" action="{{ route('cover-templates.destroy', $m->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $models->links() }}
        </div>
    </div>
</div>
@endsection


