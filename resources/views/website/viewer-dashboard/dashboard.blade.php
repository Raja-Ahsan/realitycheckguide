@if (Auth::user()->hasRole('User'))
    @extends('layouts.user.app')
@endif

@section('title', $page_title)
@section('content')
  <section class="content-header">
	<h1 style="color:#c98900 !important; font-weight: 700;">{{ Auth::user()->role}} Dashboard</h1>
  </section>
  <section class="content">
    <!-- Video Platform Stats Row -->
    <div class="row">
        <a href="{{ route('viewer.orders.index') }}" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-purple"><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Orders</span>
                        <span class="info-box-number">{{ $totalOrders ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('viewer.orders.index') }}" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-teal"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Completed Orders</span>
                        <span class="info-box-number">{{ $completedOrders ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('viewer.video-purchases.index') }}" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-maroon"><i class="fa fa-video-camera" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Videos Purchased</span>
                        <span class="info-box-number">{{ $totalVideoPurchases ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('viewer.downloads.index') }}" style="pointer:cursor;">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-navy"><i class="fa fa-download" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Downloads</span>
                        <span class="info-box-number">{{ $totalDownloads ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Video Platform Section -->
    <div class="row">
        <!-- Recent Orders Section -->
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Recent Video Orders</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('viewer.orders.index') }}" class="btn btn-sm btn-primary">View All Orders</a>
                    </div>
                </div>
                <div class="box-body">
                    @if(isset($recentOrders) && $recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Video Title</th>
                                        <th>Creator</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->video->title ?? 'N/A' }}</td>
                                            <td>{{ $order->creator->name ?? 'N/A' }}</td>
                                            <td>${{ number_format($order->amount, 2) }}</td>
                                            <td>
                                                <span class="label label-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($order->video && $order->status == 'completed')
                                                    @if($order->video->downloads_enabled)
                                                        <a href="{{ route('videos.download', $order->video) }}" class="btn btn-sm btn-success">
                                                            <i class="fa fa-download"></i> Download
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Downloads disabled</span>
                                                    @endif
                                                    <a href="{{ route('videos.show', $order->video) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-play"></i> Watch
                                                    </a>
                                                @else
                                                    <span class="text-muted">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <p>No video orders yet. <a href="{{ route('videos.index') }}">Browse videos</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Video Purchases Section -->
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Recent Video Purchases</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('viewer.video-purchases.index') }}" class="btn btn-sm btn-primary">View All Purchases</a>
                    </div>
                </div>
                <div class="box-body">
                    @if(isset($recentVideoPurchases) && $recentVideoPurchases->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Video Title</th>
                                        <th>Creator</th>
                                        <th>Amount Paid</th>
                                        <th>Purchase Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentVideoPurchases as $purchase)
                                        <tr>
                                            <td>{{ $purchase->video->title ?? 'N/A' }}</td>
                                            <td>{{ $purchase->video->creator->name ?? 'N/A' }}</td>
                                            <td>${{ number_format($purchase->amount_paid, 2) }}</td>
                                            <td>{{ $purchase->purchased_at ? $purchase->purchased_at->format('M d, Y') : $purchase->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <span class="label label-{{ $purchase->status == 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($purchase->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($purchase->video && $purchase->status == 'completed')
                                                    @if($purchase->video->downloads_enabled)
                                                        <a href="{{ route('videos.download', $purchase->video) }}" class="btn btn-sm btn-success">
                                                            <i class="fa fa-download"></i> Download
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Downloads disabled</span>
                                                    @endif
                                                    <a href="{{ route('videos.show', $purchase->video) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-play"></i> Watch
                                                    </a>
                                                @else
                                                    <span class="text-muted">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <p>No video purchases yet. <a href="{{ route('videos.index') }}">Browse videos</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Downloads Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Recent Video Downloads</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('viewer.downloads.index') }}" class="btn btn-sm btn-primary">View All Downloads</a>
                    </div>
                </div>
                <div class="box-body">
                    @if(isset($recentDownloads) && $recentDownloads->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Video Title</th>
                                        <th>Creator</th>
                                        <th>Download Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentDownloads as $download)
                                        <tr>
                                            <td>{{ $download->video->title ?? 'N/A' }}</td>
                                            <td>{{ $download->video->creator->name ?? 'N/A' }}</td>
                                            <td>{{ $download->downloaded_at ? $download->downloaded_at->format('M d, Y H:i') : $download->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                @if($download->video && $download->video->downloads_enabled)
                                                    <a href="{{ route('videos.download', $download->video) }}" class="btn btn-sm btn-success">
                                                        <i class="fa fa-download"></i> Download Again
                                                    </a>
                                                @else
                                                    <span class="text-muted">Downloads disabled</span>
                                                @endif
                                                <a href="{{ route('videos.show', $download->video) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-play"></i> Watch
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <p>No video downloads yet. Purchase videos to download them!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Purchase Information Section -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-info-circle"></i> Detailed Purchase Information
                    </h3>
                </div>
                <div class="box-body">
                    @if($recentVideoPurchases->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Purchase ID</th>
                                        <th>Video Title</th>
                                        <th>Creator</th>
                                        <th>Amount Paid</th>
                                        <th>Purchase Date</th>
                                        <th>Status</th>
                                        <th>Download Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentVideoPurchases as $purchase)
                                        <tr>
                                            <td>
                                                <strong>#{{ $purchase->id }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $purchase->video->title ?? 'N/A' }}</strong>
                                                @if($purchase->video)
                                                    <br><small class="text-muted">{{ Str::limit($purchase->video->description ?? '', 60) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $purchase->video->creator->name ?? 'N/A' }}</strong>
                                                @if($purchase->video && $purchase->video->creator)
                                                    <br><small class="text-muted">{{ $purchase->video->creator->email ?? 'N/A' }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="label label-success">
                                                    ${{ number_format($purchase->amount_paid, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $purchase->purchased_at ? $purchase->purchased_at->format('M d, Y H:i') : $purchase->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td>
                                                <span class="label label-{{ $purchase->status == 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($purchase->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($purchase->video && $purchase->video->downloads_enabled)
                                                    <span class="label label-info">Downloads Enabled</span>
                                                @else
                                                    <span class="label label-default">Downloads Disabled</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($purchase->video && $purchase->status == 'completed')
                                                    @if($purchase->video->downloads_enabled)
                                                        <a href="{{ route('videos.download', $purchase->video) }}" class="btn btn-xs btn-success">
                                                            <i class="fa fa-download"></i> Download
                                                        </a>
                                                    @else
                                                        <span class="text-muted">No Download</span>
                                                    @endif
                                                    <a href="{{ route('videos.show', $purchase->video) }}" class="btn btn-xs btn-primary">
                                                        <i class="fa fa-play"></i> Watch
                                                    </a>
                                                @else
                                                    <span class="text-muted">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('viewer.video-purchases.index') }}" class="btn btn-success">
                                <i class="fa fa-list"></i> View All Purchase Details
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-muted">No video purchases found.</p>
                            <a href="{{ route('videos.index') }}" class="btn btn-primary">
                                <i class="fa fa-video-camera"></i> Browse Videos to Purchase
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
