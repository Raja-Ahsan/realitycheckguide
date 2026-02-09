@extends('layouts.creator.app')

@section('title', 'Creator Dashboard')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Creator Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('creator.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Creator Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalVideos }}</h3>
                            <p>Total Videos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <a href="{{ route('creator.videos.index') }}" class="small-box-footer">
                            View All Videos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($totalViews) }}</h3>
                            <p>Total Views</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <a href="{{ route('creator.analytics') }}" class="small-box-footer">
                            View Analytics <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalPurchases }}</h3>
                            <p>Total Purchases</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="{{ route('creator.earnings') }}" class="small-box-footer">
                            View Earnings <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>${{ number_format($totalEarnings, 2) }}</h3>
                            <p>Total Earnings</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <a href="{{ route('creator.earnings') }}" class="small-box-footer">
                            View Details <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-plus-circle"></i> Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <a href="{{ route('creator.videos.create') }}" class="btn btn-success btn-block mb-2">
                                        <i class="fas fa-upload"></i> Upload Video
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('creator.videos.index') }}" class="btn btn-info btn-block mb-2">
                                        <i class="fas fa-list"></i> My Videos
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('creator.analytics') }}" class="btn btn-warning btn-block mb-2">
                                        <i class="fas fa-chart-bar"></i> Analytics
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('creator.earnings') }}" class="btn btn-danger btn-block mb-2">
                                        <i class="fas fa-dollar-sign"></i> Earnings
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('creator.wallet.dashboard') }}" class="btn btn-dark btn-block mb-2">
                                        <i class="fas fa-wallet"></i> Wallet
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('creator.wallet.payouts') }}" class="btn btn-secondary btn-block mb-2">
                                        <i class="fas fa-money-bill-wave"></i> Payouts
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Videos -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-video"></i> Recent Videos
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('creator.videos.index') }}" class="btn btn-primary btn-sm">
                                    View All
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($recentVideos->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Views</th>
                                                <th>Purchases</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentVideos as $video)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('videos.show', $video) }}">
                                                            {{ Str::limit($video->title, 30) }}
                                                        </a>
                                                        @if($video->is_intro)
                                                            <span class="badge badge-info">Intro</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $video->category->name ?? 'Uncategorized' }}</td>
                                                    <td>
                                                        @if($video->price > 0)
                                                            ${{ number_format($video->price, 2) }}
                                                        @else
                                                            <span class="text-success">Free</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($video->views_count) }}</td>
                                                    <td>{{ number_format($video->purchases_count) }}</td>
                                                    <td>
                                                        @if($video->status === 'active')
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ ucfirst($video->status) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-video fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No videos uploaded yet.</p>
                                    <a href="{{ route('creator.videos.create') }}" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Upload Your First Video
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-shopping-cart"></i> Recent Purchases
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            @if($recentPurchases->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentPurchases as $purchase)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $purchase->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $purchase->video->title }}</small>
                                                </div>
                                                <div class="text-right">
                                                    <span class="badge badge-success">${{ number_format($purchase->amount, 2) }}</span><br>
                                                    <small class="text-muted">{{ $purchase->paid_at ? $purchase->paid_at->diffForHumans() : 'Recently' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No purchases yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh dashboard every 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000);
});
</script>
@endpush
