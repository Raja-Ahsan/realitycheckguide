@extends('layouts.creator.app') 
@section('title', 'Creator Dashboard')
@section('content')
  <section class="content-header">
    <h1 style="color:#c98900 !important; font-weight: 700;">Creator Dashboard</h1>
  </section>
  <section class="content">
    <div class="row">
        <a href="{{ route('creator.jobs') }}" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Available Opportunities</span>
                        <span class="info-box-number">{{ $total_jobpost ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('creator.my-bids') }}" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-gavel" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Submissions</span>
                        <span class="info-box-number">{{ $total_bids ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('creator.my-bids', ['status' => 'pending']) }}" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Submissions</span>
                        <span class="info-box-number">{{ $pending_bids ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('creator.my-bids', ['status' => 'accepted']) }}" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Accepted Submissions</span>
                        <span class="info-box-number">{{ $accepted_bids ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Bids Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Recent Submissions</h3>
                </div>
                <!-- <div class="box-body">
                    @if(isset($recentBids) && $recentBids->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Opportunity Title</th>
                                        <th>Submission Value</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBids as $bid)
                                        <tr>
                                            <td>{{ $bid->jobPost->name ?? 'N/A' }}</td>
                                            <td>${{ number_format($bid->bid_amount, 2) }}</td>
                                            <td>
                                                <span class="label label-{{ $bid->status == 'accepted' ? 'success' : ($bid->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($bid->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $bid->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('creator.job-detail', $bid->job_post_id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <p>No submissions yet. <a href="{{ route('creator.jobs') }}">Browse available opportunities</a></p>
                        </div>
                    @endif
                </div> -->
            </div>
        </div>
    </div>
  </section>
@endsection
