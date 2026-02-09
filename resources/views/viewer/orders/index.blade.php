@extends('layouts.user.app')

@section('title', $page_title)

@section('content')
<section class="content-header">
    <h1 style="color:#c98900 !important; font-weight: 700;">My Video Orders</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">All Video Orders</h3>
            <div class="box-tools pull-right">
                <a href="{{ route('videos.index') }}" class="btn btn-sm btn-success">
                    <i class="fa fa-plus"></i> Browse More Videos
                </a>
            </div>
        </div>
        <div class="box-body">
            @if($orders->count() > 0)
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
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $order->video->title ?? 'N/A' }}</strong>
                                        @if($order->video)
                                            <br><small>{{ Str::limit($order->video->description ?? '', 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $order->creator->name ?? 'N/A' }}</strong>
                                        @if($order->creator)
                                            <br><small>{{ $order->creator->email ?? 'N/A' }}</small>
                                        @endif
                                    </td>
                                    <td>${{ number_format($order->amount, 2) }}</td>
                                    <td>
                                        <span class="label label-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($order->video && $order->status == 'completed')
                                            @if($order->video->downloads_enabled)
                                                <a href="{{ route('videos.download', $order->video) }}" class="btn btn-sm btn-success">
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            @else
                                                <span class="text-muted">Downloads disabled</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center">
                    <p>No video orders found.</p>
                    <a href="{{ route('videos.index') }}" class="btn btn-primary">
                        <i class="fa fa-video-camera"></i> Browse Videos
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
