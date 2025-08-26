@if (Auth::user()->hasRole('User'))
    @extends('layouts.user.app')
@endif

@section('title', $page_title)
@section('content')
  <section class="content-header">
	<h1 style="color:#c98900 !important; font-weight: 700;">{{ Auth::user()->role}} Dashboard</h1>
  </section>
  <section class="content">
    <div class="row">
        <a href="{{ route('jobpost.index') }}" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Job Posts</span>
                        <span class="info-box-number">{{ $totalJobPosts ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('jobpost.index') }}" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Active Job Posts</span>
                        <span class="info-box-number">{{ $activeJobPosts ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="#" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-gavel" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Bids Received</span>
                        <span class="info-box-number">{{ $totalBids ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="#" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-orange"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Bids</span>
                        <span class="info-box-number">{{ $pendingBids ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Job Posts Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Recent Job Posts</h3>
                </div>
                <div class="box-body">
                    @if(isset($recentJobPosts) && $recentJobPosts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Status</th>
                                        <th>Bids</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentJobPosts as $jobPost)
                                        <tr>
                                            <td>{{ $jobPost->name }}</td>
                                            <td>
                                                <span class="label label-{{ $jobPost->status == 1 ? 'success' : 'info' }}">
                                                    {{ $jobPost->status == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $jobPost->bids->count() ?? 0 }}</td>
                                            <td>{{ $jobPost->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('jobpost.job-post-bids', $jobPost->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i> View Bids
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <p>No job posts yet. <a href="#">Create your first job post</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Bids Section -->
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Recent Bids Received</h3>
                </div>
                <div class="box-body">
                    @if(isset($recentBids) && $recentBids->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Electrician</th>
                                        <th>Bid Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBids as $bid)
                                        <tr>
                                            <td>{{ $bid->jobPost->name ?? 'N/A' }}</td>
                                            <td>{{ $bid->electrician->name ?? 'N/A' }}</td>
                                            <td>${{ number_format($bid->bid_amount, 2) }}</td>
                                            <td>
                                                <span class="label label-{{ $bid->status == 'accepted' ? 'success' : ($bid->status == 'pending' ? 'warning' : 'success') }}">
                                                    {{ ucfirst($bid->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $bid->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <p>No bids received yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
  </section>
@endsection
