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
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">All Orders</h3>
            </div>
            <div class="box-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Buyer</th>
                                    <th>Video</th>
                                    <th>Creator</th>
                                    <th>Amount</th>
                                    <th>Commission</th>
                                    <th>Creator Earning</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $order->user->name ?? 'Unknown' }}</strong><br>
                                        <small>{{ $order->user->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $order->video->title ?? 'Unknown' }}</strong><br>
                                        <small>{{ Str::limit($order->video->description ?? '', 50) }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $order->creator->name ?? 'Unknown' }}</strong><br>
                                        <small>{{ $order->creator->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>${{ number_format($order->amount, 2) }}</td>
                                    <td>${{ number_format($order->commission, 2) }}</td>
                                    <td>${{ number_format($order->creator_earning, 2) }}</td>
                                    <td>
                                        @if($order->status === 'completed')
                                            <span class="label label-success">Completed</span>
                                        @elseif($order->status === 'pending')
                                            <span class="label label-warning">Pending</span>
                                        @elseif($order->status === 'failed')
                                            <span class="label label-danger">Failed</span>
                                        @else
                                            <span class="label label-info">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
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
                        <p class="text-muted">No orders found.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection
