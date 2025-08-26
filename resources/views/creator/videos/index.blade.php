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
            <div class="row mt-4" id="videos-container">
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
                                <div class="d-flex " style="display: flex;     justify-content: space-evenly; margin:5px">
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
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="{{ route('videos.show', $video) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit($video->title, 50) }}
                                    </a>
                                </h5>
                                
                                <p class="card-text text-center flex-grow-1" style="padding: 10px;">
                                    {{ Str::limit($video->description, 100) ?: 'No description available' }}
                                </p>

                                <!-- Video Stats -->
                                <div class="row text-center mb-3" style="background: rgba(0,0,0,0.02); padding: 15px; margin: 0 0px;">
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <i class="fas fa-eye" style="color: #17a2b8; font-size: 18px; margin-bottom: 8px;"></i>
                                            <div class="stat-value" style="font-size: 16px; font-weight: 700; color: #495057; margin: 5px 0;">{{ number_format($video->views_count ?? 0) }}</div>
                                            <small class="text-muted" style="font-size: 11px; font-weight: 600;">Views</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <i class="fas fa-shopping-cart" style="color: #28a745; font-size: 18px; margin-bottom: 8px;"></i>
                                            <div class="stat-value" style="font-size: 16px; font-weight: 700; color: #495057; margin: 5px 0;">{{ number_format($video->purchases_count ?? 0) }}</div>
                                            <small class="text-muted" style="font-size: 11px; font-weight: 600;">Sales</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <i class="fas fa-dollar-sign" style="color: #ffc107; font-size: 18px; margin-bottom: 8px;"></i>
                                            <div class="stat-value" style="font-size: 16px; font-weight: 700; color: #495057; margin: 5px 0;">${{ number_format(($video->purchases_count ?? 0) * $video->price, 2) }}</div>
                                            <small class="text-muted" style="font-size: 11px; font-weight: 600;">Revenue</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Video Details -->
                                <div class="mb-3" style="background: rgba(0,0,0,0.02); padding: 12px;">
                                    <div class="row text-muted small">
                                        <div class="col-md-6">
                                            <i class="fas fa-folder" style="color: #6c757d; margin-right: 5px;"></i> 
                                            <span style="font-weight: 600;">{{ $video->category->title ?? 'Uncategorized' }}</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <i class="fas fa-calendar" style="color: #6c757d; margin-right: 5px;"></i> 
                                            <span style="font-weight: 600;">{{ $video->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="btn-group btn-block mt-auto" style="gap: 10px; display: flex;    padding: 10px;">
                                    <a href="{{ route('videos.show', $video) }}" class="btn btn-outline-primary btn-sm" style="border-radius: 20px; padding: 8px 16px; font-weight: 600; border: 2px solid #007bff; transition: all 0.3s ease; flex: 1;">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('creator.videos.edit', $video) }}" class="btn btn-outline-warning btn-sm" style="border-radius: 20px; padding: 8px 16px; font-weight: 600; border: 2px solid #ffc107; transition: all 0.3s ease; flex: 1;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm delete-video-btn" 
                                            data-video-id="{{ $video->id }}" data-video-title="{{ $video->title }}"
                                            style="border-radius: 20px; padding: 8px 16px; font-weight: 600; border: 2px solid #dc3545; transition: all 0.3s ease; flex: 1;">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="empty-state" style="background: rgba(0,0,0,0.02); border-radius: 20px; padding: 40px;">
                                <i class="fas fa-video fa-5x text-muted mb-4" style="opacity: 0.5;"></i>
                                <h3 class="text-muted" style="font-weight: 600; margin-bottom: 15px;">No Videos Yet</h3>
                                <p class="text-muted lead" style="font-size: 18px; margin-bottom: 30px;">Start building your video library by uploading your first video.</p>
                                <a href="{{ route('creator.videos.create') }}" class="btn btn-primary btn-lg" style="border-radius: 25px; padding: 15px 30px; font-weight: 600; box-shadow: 0 4px 15px rgba(0,123,255,0.3); transition: all 0.3s ease; border: none;">
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
                        <div class="d-flex justify-content-center" style="margin-top: 30px;">
                            <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 20px;">
                                {{ $videos->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; border-radius: 20px 20px 0 0; padding: 25px;">
                    <h5 class="modal-title" style="font-weight: 700; margin: 0;">
                        <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.8; font-size: 24px;">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 30px;">
                    <div class="text-center mb-4">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <p style="font-size: 16px; margin-bottom: 15px;">Are you sure you want to delete "<strong id="videoTitle"></strong>"?</p>
                        <p class="text-danger" style="font-size: 14px;">
                            <i class="fas fa-exclamation-triangle"></i> This action cannot be undone.
                        </p>
                    </div>
                </div>
                <div class="modal-footer" style="border: none; padding: 0 30px 30px 30px; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 20px; padding: 10px 25px; font-weight: 600; margin-right: 15px; box-shadow: 0 2px 8px rgba(108,117,125,0.3);">
                        Cancel
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="border-radius: 20px; padding: 10px 25px; font-weight: 600; box-shadow: 0 2px 8px rgba(220,53,69,0.3); border: none;">
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
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
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
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
}

.card-img-top {
    border-radius: 0;
}

.btn-group .btn {
    border-radius: 20px;
    margin: 0 2px;
    transition: all 0.3s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
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
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.small-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;
}

.small-box .inner {
    padding: 20px;
}

.small-box .icon {
    font-size: 3rem;
    opacity: 0.8;
}

/* Enhanced filter buttons */
.btn-group .btn[data-filter] {
    transition: all 0.3s ease;
}

.btn-group .btn[data-filter]:hover {
    transform: translateY(-2px);
}

.btn-group .btn[data-filter].active {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(0,123,255,0.3);
}

/* Enhanced pagination */
.pagination {
    margin: 0;
}

.page-link {
    border-radius: 10px;
    margin: 0 2px;
    border: none;
    color: #495057;
    font-weight: 600;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,123,255,0.3);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
    box-shadow: 0 4px 15px rgba(0,123,255,0.3);
}

/* Video card enhancements */
.video-item {
    transition: all 0.3s ease;
}

.video-item:hover .card {
    transform: translateY(-5px);
}

/* Action button enhancements */
.btn-outline-primary:hover,
.btn-outline-warning:hover,
.btn-outline-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Modal enhancements */
.modal-content {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    border-bottom: none;
}

.modal-footer {
    border-top: none;
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

    // Enhanced hover effects for statistics cards
    $('.small-box').hover(
        function() {
            $(this).css('transform', 'translateY(-5px)');
            $(this).css('box-shadow', '0 15px 35px rgba(0,0,0,0.2)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
            $(this).css('box-shadow', '0 8px 25px rgba(0,0,0,0.3)');
        }
    );

    // Enhanced hover effects for action buttons
    $('.btn').hover(
        function() {
            $(this).css('transform', 'translateY(-2px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );

    // Enhanced hover effects for video cards
    $('.video-item').hover(
        function() {
            $(this).find('.card').css('transform', 'translateY(-5px)');
            $(this).find('.card').css('box-shadow', '0 15px 35px rgba(0,0,0,0.15)');
        },
        function() {
            $(this).find('.card').css('transform', 'translateY(0)');
            $(this).find('.card').css('box-shadow', '0 8px 25px rgba(0,0,0,0.1)');
        }
    );

    // Enhanced hover effects for filter buttons
    $('[data-filter]').hover(
        function() {
            if (!$(this).hasClass('active')) {
                $(this).css('transform', 'translateY(-2px)');
            }
        },
        function() {
            if (!$(this).hasClass('active')) {
                $(this).css('transform', 'translateY(0)');
            }
        }
    );
});
</script>
@endpush
