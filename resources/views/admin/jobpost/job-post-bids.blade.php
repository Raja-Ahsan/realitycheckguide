@extends('layouts.user.app')
@section('title', 'Job Post Bids')
@section('content')
<section class="content-header">
    <h1 style="color:#c98900 !important; font-weight: 700;">Job Post Bids</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Bids for: {{ $jobPost->name }}</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('jobpost.index') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Job Posts
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <!-- Job Post Details -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h4><strong>Job Details:</strong></h4>
                                <p><strong>Description:</strong> {!! $jobPost->description !!}</p>
                                <p><strong>Location:</strong> {{ $jobPost->hasCity->city ?? 'N/A' }}, {{ $jobPost->hasState->state ?? 'N/A' }}</p>
                                <p><strong>Budget:</strong> ${{ number_format($jobPost->budget_min ?? 0, 2) }} - ${{ number_format($jobPost->budget_max ?? 0, 2) }}</p>
                                <p><strong>Deadline:</strong> {{ $jobPost->deadline ? \Carbon\Carbon::parse($jobPost->deadline)->format('M d, Y') : 'N/A' }}</p>
                                <p><strong>Status:</strong> 
                                    <span class="label label-{{ $jobPost->status == 1 ? 'success' : 'danger' }}">
                                        {{ $jobPost->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bids List -->
                    @if($jobPost->bids->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Electrician</th>
                                        <th>Bid Amount</th>
                                        <th>Proposal</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobPost->bids as $bid)
                                        <tr>
                                            <td>
                                                <strong>{{ $bid->electrician->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $bid->electrician->email ?? 'N/A' }}</small>
                                            </td>
                                            <td>${{ number_format($bid->bid_amount, 2) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#proposalModal{{ $bid->id }}">
                                                    <i class="fa fa-eye"></i> View Proposal
                                                </button>
                                            </td>
                                            <td>
                                                <span class="label label-{{ $bid->status == 'accepted' ? 'success' : ($bid->status == 'pending' ? 'warning' : ($bid->status == 'rejected' ? 'danger' : 'info')) }}">
                                                    {{ ucfirst($bid->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $bid->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($bid->status == 'pending')
                                                    <form method="POST" action="{{ route('jobpost.accept-bid', $bid->id) }}" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-xs" onclick="return confirm('Are you sure you want to accept this bid?')">
                                                            <i class="fa fa-check"></i> Accept
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('jobpost.reject-bid', $bid->id) }}" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to reject this bid?')">
                                                            <i class="fa fa-times"></i> Reject
                                                        </button>
                                                    </form>
                                                @elseif($bid->status == 'accepted')
                                                    <form method="POST" action="{{ route('jobpost.complete-job', $jobPost->id) }}" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info btn-xs" onclick="return confirm('Mark this job as completed?')">
                                                            <i class="fa fa-check-circle"></i> Complete Job
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Proposal Modal -->
                                        <div class="modal fade" id="proposalModal{{ $bid->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Proposal from {{ $bid->electrician->name ?? 'N/A' }}</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5><strong>Bid Amount:</strong> ${{ number_format($bid->bid_amount, 2) }}</h5>
                                                        <hr>
                                                        <h5><strong>Proposal:</strong></h5>
                                                        <p>{{ $bid->proposal }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        @if($bid->status == 'pending')
                                                           <form method="POST" action="{{ route('jobpost.accept-bid', $bid->id) }}" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to accept this bid?')">
                                                                    <i class="fa fa-check"></i> Accept Bid
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <h3>No bids yet</h3>
                            <p>No electricians have bid on this job post yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 