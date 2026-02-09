@extends('layouts.user.app')

@section('title', 'Browse Videos')

@section('content')
<section class="content-header">
    <h1 style="color:#c98900 !important; font-weight: 700;"><i class="fa fa-video-camera"></i> Browse Videos</h1>
</section>

<section class="content">
    <div class="row">
        <!-- Search and categories -->
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-body">
                    <form action="{{ route('videos.index') }}" method="GET" class="form-inline mb-3">
                        <div class="input-group" style="width: 100%; max-width: 500px;">
                            <input type="text" name="search" class="form-control" placeholder="Search by title, description, or tags..." value="{{ request('search') }}">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                            </span>
                        </div>
                    </form>
                    @if($categories->isNotEmpty())
                    <p class="text-muted mb-2"><strong>Browse by category:</strong></p>
                    <div class="flex-wrap d-flex gap-2 mb-2" style="gap: 8px;">
                        <a href="{{ route('videos.index', request()->only('search')) }}" class="btn btn-sm {{ !request('category_id') ? 'btn-primary' : 'btn-default' }}">All</a>
                        @foreach($categories as $cat)
                        <a href="{{ route('videos.index', array_merge(request()->only('search'), ['category_id' => $cat->id])) }}" class="btn btn-sm {{ request('category_id') == $cat->id ? 'btn-primary' : 'btn-default' }}">{{ $cat->title }}</a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($videos as $video)
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="box box-solid h-100">
                <div class="box-body">
                    <a href="{{ route('videos.show', $video) }}" class="text-decoration-none text-dark">
                        @if($video->thumbnail_path)
                        <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="img-responsive w-100" style="height: 180px; object-fit: cover;">
                        @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center w-100" style="height: 180px;">
                            <i class="fa fa-video-camera fa-3x text-white"></i>
                        </div>
                        @endif
                    </a>
                    <h4 class="mt-2 mb-1" style="font-size: 1rem;">
                        <a href="{{ route('videos.show', $video) }}" class="text-decoration-none text-dark">{{ Str::limit($video->title, 50) }}</a>
                    </h4>
                    <p class="text-muted small mb-1">{{ $video->creator->name ?? 'Creator' }}</p>
                    @if($video->category)
                    <span class="badge badge-info">{{ $video->category->title }}</span>
                    @endif
                    @if($video->is_intro)
                    <span class="badge badge-success">Free Intro</span>
                    @else
                    <span class="badge badge-warning">${{ number_format($video->price, 2) }}</span>
                    @endif
                    <div class="mt-2">
                        <a href="{{ route('videos.show', $video) }}" class="btn btn-primary btn-sm">View</a>
                        @if(!$video->is_intro)
                        <a href="{{ route('videos.buy', $video) }}" class="btn btn-success btn-sm">Purchase</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-body text-center py-5">
                    <i class="fa fa-video-camera fa-4x text-muted mb-3"></i>
                    <h4>No videos found</h4>
                    <p class="text-muted">Try adjusting your search or category filter.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    @if($videos->hasPages())
    <div class="row">
        <div class="col-md-12 text-center">
            {{ $videos->withQueryString()->links() }}
        </div>
    </div>
    @endif
</section>
@endsection
