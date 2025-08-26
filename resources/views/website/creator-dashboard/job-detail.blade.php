@extends('layouts.electrician.app')
@section('title', $job->name)
@section('content')
<section class="content-header">
    <h1 style="color:#c98900 !important; font-weight: 700;">Job Details</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $job->name }}</h3>
                    <div class="box-tools pull-right">
                        <span class="label label-info">{{ $job->hasCategory->title ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Posted by:</strong> {{ $job->user->name ?? 'N/A' }} {{ $job->user->last_name ?? 'N/A' }}</p>
                            <p><strong>Location:</strong> {{ $job->hasCity->city ?? 'N/A' }}, {{ $job->hasState->state ?? 'N/A' }}</p>
                            <p><strong>Budget Range:</strong> ${{ number_format($job->budget_min ?? 0, 2) }} - ${{ number_format($job->budget_max ?? 0, 2) }}</p>
                            <p><strong>Deadline:</strong> {{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('M d, Y') : 'N/A' }}</p>
                            <p><strong>Posted:</strong> {{ $job->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Bids:</strong> {{ $job->bids->count() }}</p>
                            <p><strong>Status:</strong> 
                                <span class="label label-{{ $job->status == 1 ? 'success' : 'danger' }}">
                                    {{ $job->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h4><strong>Job Description:</strong></h4>
                    <p>{!! $job->description !!}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Place Your Bid</h3>
                </div>
                <div class="box-body">
                    @if($existingBid)
                        <div class="alert alert-info">
                            <h4>You have already bid on this job</h4>
                            <p><strong>Your Bid Amount:</strong> ${{ number_format($existingBid->bid_amount, 2) }}</p>
                            <p><strong>Status:</strong> 
                                <span class="label label-{{ $existingBid->status == 'accepted' ? 'success' : ($existingBid->status == 'pending' ? 'warning' : ($existingBid->status == 'rejected' ? 'danger' : 'success')) }}">
                                    {{ ucfirst($existingBid->status) }}
                                </span>
                            </p>
                            <p><strong>Your Proposal:</strong></p>
                            <p>{{ $existingBid->proposal }}</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('electrician.submit-bid', $job->id) }}">
                            @csrf
                            <div class="form-group">
                                <label for="bid_amount">Bid Amount ($)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="bid_amount" name="bid_amount" required>
                                @error('bid_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="proposal">Your Proposal</label>
                                <textarea class="form-control" id="proposal" name="proposal" rows="6" placeholder="Describe your approach, experience, and why you're the best fit for this job..." required></textarea>
                                @error('proposal')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fa fa-gavel"></i> Submit Bid
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Other Bids Section (if any) -->
    @if($job->bids->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Other Bids ({{ $job->bids->count() }} total)</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Electrician</th>
                                        <th>Bid Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($job->bids as $bid)
                                        <tr>
                                            <td>{{ $bid->electrician->name ?? 'N/A' }} {{ $bid->electrician->last_name ?? 'N/A' }}</td>
                                            <td>${{ number_format($bid->bid_amount, 2) }}</td>
                                            <td>
                                                <span class="label label-{{ $bid->status == 'accepted' ? 'success' : ($bid->status == 'pending' ? 'warning' : ($bid->status == 'rejected' ? 'danger' : 'success')) }}">
                                                    {{ ucfirst($bid->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $bid->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
@endsection 