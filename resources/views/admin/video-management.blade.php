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
                <h3 class="box-title">All Videos</h3>
            </div>
            <div class="box-body">
                @if($videos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Creator</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Videos Sold</th>
                                    <th>Upload Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($videos as $video)
                                <tr>
                                    <td>
                                        <strong>{{ $video->title }}</strong><br>
                                        <small>{{ Str::limit($video->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $video->creator->name ?? 'Unknown' }}</strong><br>
                                        <small>{{ $video->creator->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $video->category->name ?? 'Uncategorized' }}</td>
                                    <td>
                                        @if($video->is_intro)
                                            <span class="label label-success">Free (Intro)</span>
                                        @else
                                            ${{ number_format($video->price, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($video->status === 'active')
                                            <span class="label label-success">Active</span>
                                        @elseif($video->status === 'pending')
                                            <span class="label label-warning">Pending</span>
                                        @else
                                            <span class="label label-danger">{{ ucfirst($video->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $video->videos_sold ?? 0 }}</td>
                                    <td>{{ $video->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center">
                        {{ $videos->links() }}
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-muted">No videos found.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection
