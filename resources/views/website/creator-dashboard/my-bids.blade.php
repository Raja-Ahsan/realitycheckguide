@extends('layouts.electrician.app')
@section('title', 'My Bids')
@section('content')
<section class="content-header">
    <h1 style="color:#c98900 !important; font-weight: 700;">My Bids</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">My Bid History</h3>
                </div>
                <div class="box-body">
                    @if($bids->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Bid Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bids as $bid)
                                        <tr>
                                            <td>
                                                <strong>{{ $bid->jobPost->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">Posted by: {{ $bid->user->name ?? 'N/A' }} {{ $bid->user->last_name ?? 'N/A' }}</small>
                                            </td>
                                            <td>${{ number_format($bid->bid_amount, 2) }}</td>
                                            <td>
                                                <span class="label label-{{ $bid->status == 'accepted' ? 'success' : ($bid->status == 'pending' ? 'warning' : ($bid->status == 'rejected' ? 'danger' : 'info')) }}">
                                                    {{ ucfirst($bid->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $bid->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('electrician.job-detail', $bid->job_post_id) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i> View Job
                                                </a>
                                                
                                                @if($bid->status == 'pending')
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editBidModal{{ $bid->id }}">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </button>
                                                    <form method="POST" action="{{ route('electrician.withdraw-bid', $bid->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to withdraw this bid?')">
                                                            <i class="fa fa-times"></i> Withdraw
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        <!-- Edit Bid Modal -->
                                        @if($bid->status == 'pending')
                                            <div class="modal fade" id="editBidModal{{ $bid->id }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Edit Bid</h4>
                                                        </div>
                                                        <form method="POST" action="{{ route('electrician.update-bid', $bid->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="bid_amount">Bid Amount ($)</label>
                                                                    <input type="number" step="0.01" min="0" class="form-control" name="bid_amount" value="{{ $bid->bid_amount }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="proposal">Your Proposal</label>
                                                                    <textarea class="form-control" name="proposal" rows="6" required>{{ $bid->proposal }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Update Bid</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="text-center">
                            {{ $bids->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center"> 
                            <h3>No {{ $status }} bids</h3>
                            <p>You haven't placed any bids yet. <a href="{{ route('electrician.jobs') }}">Browse available jobs</a> to get started.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 