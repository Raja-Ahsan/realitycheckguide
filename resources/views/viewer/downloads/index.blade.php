@extends('layouts.user.app')

@section('title', $page_title)

@section('content')
<section class="content-header">
    <h1 style="color:#c98900 !important; font-weight: 700;">My Video Downloads</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">All Video Downloads</h3>
            <div class="box-tools pull-right">
                <a href="{{ route('videos.index') }}" class="btn btn-sm btn-success">
                    <i class="fa fa-plus"></i> Browse More Videos
                </a>
            </div>
        </div>
        <div class="box-body">
            @if($downloads->count() > 0)
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
                            @foreach($downloads as $download)
                                <tr>
                                    <td>
                                        <strong>{{ $download->video->title ?? 'N/A' }}</strong>
                                        @if($download->video)
                                            <br><small>{{ Str::limit($download->video->description ?? '', 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $download->video->creator->name ?? 'N/A' }}</strong>
                                        @if($download->video && $download->video->creator)
                                            <br><small>{{ $download->video->creator->email ?? 'N/A' }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $download->downloaded_at ? $download->downloaded_at->format('M d, Y H:i') : $download->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($download->video && $download->video->downloads_enabled)
                                            <a href="{{ route('videos.download', $download->video) }}" class="btn btn-sm btn-success">
                                                <i class="fa fa-download"></i> Download Again
                                            </a>
                                        @else
                                            <span class="text-muted">Downloads disabled</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center">
                    {{ $downloads->links() }}
                </div>
            @else
                <div class="text-center">
                    <p>No video downloads found.</p>
                    <a href="{{ route('videos.index') }}" class="btn btn-primary">
                        <i class="fa fa-video-camera"></i> Browse Videos
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
