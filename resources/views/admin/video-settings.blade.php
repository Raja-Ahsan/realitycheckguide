@extends('layouts.admin.app')

@section('title', $page_title)

@section('content')
    <section class="content-header">
        <h1>{{ $page_title }}</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <!-- Platform Statistics -->
            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-video-camera"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Videos</span>
                        <span class="info-box-number">{{ number_format($totalVideos) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Creators</span>
                        <span class="info-box-number">{{ number_format($totalCreators) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Orders</span>
                        <span class="info-box-number">{{ number_format($totalOrders) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Revenue</span>
                        <span class="info-box-number">${{ number_format($totalRevenue, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Platform Settings</h3>
                    </div>
                    <form action="{{ route('admin.video-settings.update') }}" method="POST">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="commission_rate">Commission Rate (%)</label>
                                        <input type="number" class="form-control" id="commission_rate" name="commission_rate" 
                                               value="{{ $commissionRate }}" min="0" max="100" step="0.1" required>
                                        <small class="text-muted">Percentage of video sales that goes to admin</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="max_video_price">Maximum Video Price ($)</label>
                                        <input type="number" class="form-control" id="max_video_price" name="max_video_price" 
                                               value="{{ $maxVideoPrice }}" min="0.99" step="0.01" required>
                                        <small class="text-muted">Maximum price creators can set for videos</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="min_video_price">Minimum Video Price ($)</label>
                                        <input type="number" class="form-control" id="min_video_price" name="min_video_price" 
                                               value="{{ $minVideoPrice }}" min="0.99" step="0.01" required>
                                        <small class="text-muted">Minimum price creators can set for videos</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="videos_sold_threshold">Videos Sold Threshold</label>
                                        <input type="number" class="form-control" id="videos_sold_threshold" name="videos_sold_threshold" 
                                               value="{{ $videosSoldThreshold }}" min="1" required>
                                        <small class="text-muted">Number of videos creator must sell before setting custom pricing</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="stripe_publishable_key">Stripe Publishable Key</label>
                                        <input type="text" class="form-control" id="stripe_publishable_key" name="stripe_publishable_key" 
                                               value="{{ $settings['stripe_publishable_key'] ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="stripe_secret_key">Stripe Secret Key</label>
                                        <input type="password" class="form-control" id="stripe_secret_key" name="stripe_secret_key" 
                                               value="{{ $settings['stripe_secret_key'] ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="stripe_webhook_secret">Stripe Webhook Secret</label>
                                        <input type="password" class="form-control" id="stripe_webhook_secret" name="stripe_webhook_secret" 
                                               value="{{ $settings['stripe_webhook_secret'] ?? '' }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Update Settings</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quick Actions</h3>
                    </div>
                    <div class="box-body">
                        <a href="{{ route('admin.video-management') }}" class="btn btn-app">
                            <i class="fa fa-video-camera"></i>
                            <span>Manage Videos</span>
                        </a>
                        <a href="{{ route('admin.order-management') }}" class="btn btn-app">
                            <i class="fa fa-shopping-cart"></i>
                            <span>View Orders</span>
                        </a>
                        <a href="{{ route('admin.payout-management') }}" class="btn btn-app">
                            <i class="fa fa-money"></i>
                            <span>Process Payouts</span>
                        </a>
                        <a href="{{ route('admin.creator-analytics') }}" class="btn btn-app">
                            <i class="fa fa-bar-chart"></i>
                            <span>Creator Analytics</span>
                        </a>
                        <a href="{{ route('admin.wallet-overview') }}" class="btn btn-app">
                            <i class="fa fa-credit-card"></i>
                            <span>Wallet Overview</span>
                        </a>
                    </div>
                </div>

                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Payout Summary</h3>
                    </div>
                    <div class="box-body">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending Payouts</span>
                                <span class="info-box-number">{{ $pendingPayouts }}</span>
                            </div>
                        </div>
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Paid Out</span>
                                <span class="info-box-number">${{ number_format($totalPayouts, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function() {
        var commissionRate = parseFloat($('#commission_rate').val());
        var maxPrice = parseFloat($('#max_video_price').val());
        var minPrice = parseFloat($('#min_video_price').val());
        
        if (minPrice >= maxPrice) {
            alert('Minimum video price must be less than maximum video price.');
            return false;
        }
        
        if (commissionRate < 0 || commissionRate > 100) {
            alert('Commission rate must be between 0 and 100.');
            return false;
        }
        
        return true;
    });
});
</script>
@endpush
