@extends('layouts.user.app')
@section('title', 'My Job Posts')
@section('content')
<section class="content-header">
    <h1 style="color:#c98900 !important; font-weight: 700;">My Job Posts</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">My Job Posts</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('user.create-job-post') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> Create New Job Post
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    @if($jobposts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                        <th>Budget</th>
                                        <th>Status</th>
                                        <th>Bids</th>
                                        <th>Posted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobposts as $jobPost)
                                        <tr>
                                            <td>{{ $jobPost->name }}</td>
                                            <td>{{ $jobPost->hasCategory->name ?? 'N/A' }}</td>
                                            <td>{{ $jobPost->hasCity->name ?? 'N/A' }}, {{ $jobPost->hasState->name ?? 'N/A' }}</td>
                                            <td>${{ number_format($jobPost->budget_min ?? 0, 2) }} - ${{ number_format($jobPost->budget_max ?? 0, 2) }}</td>
                                            <td>
                                                <span class="label label-{{ $jobPost->status == 1 ? 'success' : 'danger' }}">
                                                    {{ $jobPost->status == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('user.job-post-bids', $jobPost->id) }}" class="btn btn-info btn-xs">
                                                    {{ $jobPost->bids->count() }} Bids
                                                </a>
                                            </td>
                                            <td>{{ $jobPost->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('user.edit-job-post', $jobPost->id) }}" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <form method="POST" action="{{ route('user.delete-job-post', $jobPost->id) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this job post?')">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="text-center">
                            {{ $jobposts->links() }}
                        </div>
                    @else
                        <div class="text-center">
                            <h3>No job posts yet</h3>
                            <p>You haven't created any job posts yet. <a href="{{ route('user.create-job-post') }}">Create your first job post</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 