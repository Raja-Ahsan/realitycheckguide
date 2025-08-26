@extends('layouts.creator.app')

@section('title', 'My Videos')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-video"></i> My Videos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('creator.dashboard') }}">Creator Dashboard</a></li>
                        <li class="breadcrumb-item active">My Videos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $videos->total() }}</h3>
                            <p>Total Videos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-video"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $videos->where('is_intro', true)->count() }}</h3>
                            <p>Intro Videos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $videos->where('is_intro', false)->count() }}</h3>
                            <p>Paid Videos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $videos->where('status', 'active')->count() }}</h3>
                            <p>Active Videos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <a href="{{ route('creator.videos.create') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-plus"></i> Upload New Video
                    </a>
                    <a href="{{ route('creator.dashboard') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
                <div class="col-md-6 text-right">
                    <div class="btn-group btn-group-lg">
                        <button type="button" class="btn btn-outline-primary active" data-filter="all">
                            <i class="fas fa-list"></i> All Videos
                        </button>
                        <button type="button" class="btn btn-outline-info" data-filter="intro">
                            <i class="fas fa-play-circle"></i> Intro Videos
                        </button>
                        <button type="button" class="btn btn-outline-success" data-filter="paid">
                            <i class="fas fa-dollar-sign"></i> Paid Videos
                        </button>
                    </div>
                </div>
            </div>

            <!-- Videos Grid -->
            <div class="row" id="videos-container">
                @forelse($videos as $video)
                    <div class="col-md-6 col-lg-4 video-item mb-4" data-type="{{ $video->is_intro ? 'intro' : 'paid' }}">
                        <div class="card h-100 shadow-sm hover-shadow">
                            <!-- Video Thumbnail -->
                            <div class="position-relative">
                                @if($video->thumbnail_path && !empty($video->thumbnail_path))
                                    <img src="{{ asset('storage/app/public/' . $video->thumbnail_path) }}" 
                                         class="card-img-top" alt="{{ $video->title }}" 
                                         style="height: 200px; object-fit: cover; width: 100%;"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="card-img-top bg-gradient-secondary d-flex align-items-center justify-content-center" 
                                         style="height: 200px; background: linear-gradient(135deg, #6c757d 0%, #495057 100%); display: none;">
                                        <div class="text-center text-white">
                                            <i class="fas fa-video fa-4x mb-2"></i>
                                            <p class="mb-0">No Thumbnail</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="card-img-top bg-gradient-secondary d-flex align-items-center justify-content-center" 
                                         style="height: 200px; background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                                        <div class="text-center text-white">
                                            <i class="fas fa-video fa-4x mb-2"></i>
                                            <p class="mb-0">No Thumbnail</p>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Video Status Badges -->
                                <div class="position-absolute top-0 left-0 m-2">
                                    @if($video->is_intro)
                                        <span class="badge badge-info badge-pill">
                                            <i class="fas fa-play-circle"></i> Intro
                                        </span>
                                    @endif
                                    @if($video->status === 'active')
                                        <span class="badge badge-success badge-pill">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    @else
                                        <span class="badge badge-secondary badge-pill">
                                            <i class="fas fa-clock"></i> {{ ucfirst($video->status) }}
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Price Badge -->
                                <div class="position-absolute top-0 right-0 m-2">
                                    @if($video->price > 0)
                                        <span class="badge badge-warning badge-pill">
                                            <i class="fas fa-dollar-sign"></i> ${{ number_format($video->price, 2) }}
                                        </span>
                                    @else
                                        <span class="badge badge-success badge-pill">
                                            <i class="fas fa-gift"></i> Free
                                        </span>
                                    @endif
                                </div>

                                <!-- Video Duration Overlay -->
                                <div class="position-absolute bottom-0 right-0 m-2">
                                    <span class="badge badge-dark badge-pill">
                                        <i class="fas fa-clock"></i> {{ $video->duration ? gmdate('i:s', $video->duration) : 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="{{ route('videos.show', $video) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit($video->title, 50) }}
                                    </a>
                                </h5>
                                
                                <p class="card-text text-muted flex-grow-1">
                                    {{ Str::limit($video->description, 100) ?: 'No description available' }}
                                </p>

                                <!-- Video Stats -->
                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <i class="fas fa-eye text-info"></i>
                                            <div class="stat-value">{{ number_format($video->views_count ?? 0) }}</div>
                                            <small class="text-muted">Views</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <i class="fas fa-shopping-cart text-success"></i>
                                            <div class="stat-value">{{ number_format($video->purchases_count ?? 0) }}</div>
                                            <small class="text-muted">Sales</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <i class="fas fa-dollar-sign text-warning"></i>
                                            <div class="stat-value">${{ number_format(($video->purchases_count ?? 0) * $video->price, 2) }}</div>
                                            <small class="text-muted">Revenue</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Video Details -->
                                <div class="mb-3">
                                    <div class="row text-muted small">
                                        <div class="col-6">
                                            <i class="fas fa-folder"></i> {{ $video->category->title ?? 'Uncategorized' }}
                                        </div>
                                        <div class="col-6 text-right">
                                            <i class="fas fa-calendar"></i> {{ $video->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="btn-group btn-block mt-auto">
                                    <a href="{{ route('videos.show', $video) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('creator.videos.edit', $video) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm delete-video-btn" 
                                            data-video-id="{{ $video->id }}" data-video-title="{{ $video->title }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-video fa-5x text-muted mb-4"></i>
                                <h3 class="text-muted">No Videos Yet</h3>
                                <p class="text-muted lead">Start building your video library by uploading your first video.</p>
                                <a href="{{ route('creator.videos.create') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-upload"></i> Upload Your First Video
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            {{ $videos->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete "<strong id="videoTitle"></strong>"?</p>
                    <p class="text-danger">
                        <i class="fas fa-exclamation-triangle"></i> This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Video
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    transition: all 0.3s ease;
}

.stat-item {
    padding: 8px 0;
}

.stat-value {
    font-size: 1.1rem;
    font-weight: bold;
    margin: 4px 0;
}

.badge-pill {
    padding: 6px 12px;
    font-size: 0.8rem;
}

.empty-state {
    padding: 40px 20px;
}

.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.card-img-top {
    border-radius: 0;
}

.btn-group .btn {
    border-radius: 6px;
    margin: 0 2px;
}

.btn-group .btn:first-child {
    margin-left: 0;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.shadow-sm {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}

.small-box {
    border-radius: 12px;
    overflow: hidden;
}

.small-box .inner {
    padding: 20px;
}

.small-box .icon {
    font-size: 3rem;
    opacity: 0.3;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Filter videos by type
    $('[data-filter]').on('click', function() {
        let filter = $(this).data('filter');
        
        // Update active button
        $('[data-filter]').removeClass('active btn-primary').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('active btn-primary');
        
        // Show/hide videos based on filter
        if (filter === 'all') {
            $('.video-item').fadeIn(300);
        } else {
            $('.video-item').hide();
            $('.video-item[data-type="' + filter + '"]').fadeIn(300);
        }
    });
    
    // Set "All" as default active filter
    $('[data-filter="all"]').removeClass('btn-outline-primary').addClass('active btn-primary');
    
    // Delete video function
    $('.delete-video-btn').on('click', function() {
        let videoId = $(this).data('video-id');
        let videoTitle = $(this).data('video-title');
        
        $('#videoTitle').text(videoTitle);
        $('#deleteForm').attr('action', '/creator/videos/' + videoId);
        $('#deleteModal').modal('show');
    });

    // Add loading animation for images
    $('.card-img-top').on('load', function() {
        $(this).addClass('loaded');
    }).on('error', function() {
        $(this).attr('src', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjNmM3NTdkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5ObyBUaHVtYm5haWw8L3RleHQ+PC9zdmc+');
    });
});
</script>
@endpush
