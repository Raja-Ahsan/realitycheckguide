@extends('layouts.user.app')

@section('title', $page_title)

@section('content')
<section class="content-header">
    <h1 style="color:#c98900 !important; font-weight: 700;">My Video Purchases</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">All Video Purchases</h3>
            <div class="box-tools pull-right">
                <a href="{{ route('videos.index') }}" class="btn btn-sm btn-success">
                    <i class="fa fa-plus"></i> Browse More Videos
                </a>
            </div>
        </div>
        <div class="box-body">
            @if($purchases->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Video Title</th>
                                <th>Creator</th>
                                <th>Amount Paid</th>
                                <th>Status</th>
                                <th>Purchase Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $purchase)
                                <tr>
                                    <td>
                                        <strong>{{ $purchase->video->title ?? 'N/A' }}</strong>
                                        @if($purchase->video)
                                            <br><small>{{ Str::limit($purchase->video->description ?? '', 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $purchase->video->creator->name ?? 'N/A' }}</strong>
                                        @if($purchase->video && $purchase->video->creator)
                                            <br><small>{{ $purchase->video->creator->email ?? 'N/A' }}</small>
                                        @endif
                                    </td>
                                    <td>${{ number_format($purchase->amount_paid, 2) }}</td>
                                    <td>
                                        <span class="label label-{{ $purchase->status == 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $purchase->purchased_at ? $purchase->purchased_at->format('M d, Y H:i') : $purchase->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($purchase->video && $purchase->status == 'completed')
                                            @if($purchase->video->downloads_enabled)
                                                <a href="{{ route('videos.download', $purchase->video) }}" class="btn btn-sm btn-success">
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
                    {{ $purchases->links() }}
                </div>
            @else
                <div class="text-center">
                    <p>No video purchases found.</p>
                    <a href="{{ route('videos.index') }}" class="btn btn-primary">
                        <i class="fa fa-video-camera"></i> Browse Videos
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
