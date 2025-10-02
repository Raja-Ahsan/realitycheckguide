@extends('layouts.website.master')

@section('title', $creator->name . ' - Creator Profile')

@section('content')
    @if (!empty($banner->image))
        <section class="inner-banner creator-profile-banner"
            style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('admin/assets/images/banner') }}/{{ $banner->image }}');">
        @else
        <section class="inner-banner creator-profile-banner" 
            style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('public/admin/assets/images/images.png') }}');">
    @endif
        <div class="banner-wrapper position-relative z-1">
            <div class="container">
                <div class="row"> 
                    <div class="col-lg-12 col-xl-12" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1500"> 
                        <h1 class="hd-70 mt-5">{{ $creator->name }}</h1>
                        <p class="hd-20 text-white">{{ $creator->designation ?? 'Content Creator' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="creator-profile-sec pt-100 pb-100">
        <div class="container">
            <!-- Creator Header Info -->
            <div class="creator-header-info mb-5 mt-5">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        @if($creator->image)
                            <img src="{{ asset('storage/' . $creator->image) }}" 
                                 alt="{{ $creator->name }}" 
                                 class="creator-profile-avatar rounded-circle" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 150px; height: 150px;">
                                <i class="fa fa-user fa-4x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <div class="creator-profile-details">
                            <h2 class="mb-3">{{ $creator->name }}</h2>
                            <p class="creator-designation text-muted mb-3">
                                <i class="fa fa-briefcase"></i> {{ $creator->designation ?? 'Content Creator' }}
                            </p>
                            <p class="creator-bio mb-4">
                                {{ $creator->about_me ?? 'Passionate content creator sharing knowledge and skills with the world.' }}
                            </p>
                            
                            <!-- Creator Stats -->
                            <div class="creator-stats row text-center">
                                <div class="col-3">
                                    <div class="stat-item">
                                        <div class="stat-value">{{ $creator->videos()->count() }}</div>
                                        <small class="text-muted">Total Videos</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="stat-item">
                                        <div class="stat-value">{{ number_format($creator->total_views ?? 0) }}</div>
                                        <small class="text-muted">Total Views</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="stat-item">
                                        <div class="stat-value">{{ number_format($creator->total_earnings ?? 0) }}</div>
                                        <small class="text-muted">Total Earnings</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="stat-item">
                                        <div class="stat-value">{{ $creator->videos()->where('is_intro', false)->count() }}</div>
                                        <small class="text-muted">Paid Videos</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badges -->
                            <div class="creator-badges mt-3">
                                @if($creator->is_verified)
                                    <span class="badge badge-success badge-pill mr-2">
                                        <i class="fa fa-check-circle"></i> Verified Creator
                                    </span>
                                @endif
                                @if($creator->is_featured)
                                    <span class="badge badge-warning badge-pill mr-2">
                                        <i class="fa fa-star"></i> Featured Creator
                                    </span>
                                @endif
                                @if($creator->top_rated)
                                    <span class="badge badge-info badge-pill">
                                        <i class="fa fa-trophy"></i> Top Rated
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Intro Video Section -->
            @if($creator->introVideo)
                <div class="intro-video-section mb-5">
                    <div class="section-header mb-4">
                        <h3 class="hd-30">
                            <i class="fa fa-play-circle text-info"></i> Free Introduction Video
                        </h3>
                        <p class="text-muted">Watch this free video to get to know {{ $creator->name }} and their content style.</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="video-player-container">
                                @if($creator->introVideo->video_path)
                                    <video controls class="w-100" style="max-height: 400px;">
                                        <source src="{{ asset('storage/app/public/' . $creator->introVideo->video_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 400px;">
                                        <div class="text-center text-white">
                                            <i class="fa fa-video fa-4x mb-3"></i>
                                            <h4>Video Not Available</h4>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="intro-video-info">
                                <h4>{{ $creator->introVideo->title }}</h4>
                                <p class="text-muted">{{ $creator->introVideo->description ?: 'No description available.' }}</p>
                                
                                <div class="video-meta">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="meta-item">
                                                <i class="fa fa-eye text-info"></i>
                                                <div class="meta-value">{{ number_format($creator->introVideo->views_count ?? 0) }}</div>
                                                <small class="text-muted">Views</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="meta-item">
                                                <i class="fa fa-calendar text-success"></i>
                                                <div class="meta-value">{{ $creator->introVideo->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">Uploaded</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--<div class="intro-video-actions mt-3">
                                    <a href="{{ route('videos.show', $creator->introVideo) }}" class="btn btn-info btn-block">
                                        <i class="fa fa-external-link-alt"></i> View Full Video
                                    </a>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Paid Videos Section -->
            <div class="paid-videos-section">
                <div class="section-header mb-4">
                    <h3 class="hd-30">
                        <i class="fa fa-dollar-sign text-success"></i> Premium Content
                    </h3>
                    <p class="text-muted">Unlock exclusive content from {{ $creator->name }} by purchasing these premium videos.</p>
                </div>

                <!-- Videos Grid -->
                <div class="row" id="videos-container">
                    @forelse($paidVideos as $video)
                        <div class="col-lg-4 col-md-6 mb-4 video-item">
                            <div class="video-card h-100 shadow-sm hover-shadow">
                                <!-- Video Thumbnail -->
                                <div class="position-relative">
                                    @if($video->thumbnail_path)
                                        <img src="{{ asset('storage/app/public/' . $video->thumbnail_path) }}" 
                                             alt="{{ $video->title }}" 
                                             class="card-img-top" 
                                             style="height: 200px; object-fit: cover; width: 100%;">
                                    @else
                                        <div class="card-img-top bg-gradient-secondary d-flex align-items-center justify-content-center" 
                                             style="height: 200px; background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                                            <div class="text-center text-white">
                                                <i class="fa fa-video fa-4x mb-2"></i>
                                                <p class="mb-0">No Thumbnail</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Price Badge -->
                                    <div class="position-absolute top-0 right-0 m-2">
                                        <span class="badge badge-warning badge-pill">
                                            <i class="fa fa-dollar-sign"></i> ${{ number_format($video->price, 2) }}
                                        </span>
                                    </div>

                                    <!-- Duration Badge -->
                                    <div class="position-absolute bottom-0 right-0 m-2">
                                        <span class="badge badge-dark badge-pill">
                                            <i class="fa fa-clock"></i> {{ $video->duration ? gmdate('i:s', $video->duration) : 'N/A' }}
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
                                        <div class="col-6">
                                            <div class="stat-item">
                                                <i class="fa fa-eye text-info"></i>
                                                <div class="stat-value">{{ number_format($video->views_count ?? 0) }}</div>
                                                <small class="text-muted">Views</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="stat-item">
                                                <i class="fa fa-shopping-cart text-success"></i>
                                                <div class="stat-value">{{ number_format($video->purchases_count ?? 0) }}</div>
                                                <small class="text-muted">Sales</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Video Details -->
                                    <div class="mb-3">
                                        <div class="row text-muted small">
                                            <div class="col-6">
                                                <i class="fa fa-folder"></i> {{ $video->category->title ?? 'Uncategorized' }}
                                            </div>
                                            <div class="col-6 text-right">
                                                <i class="fa fa-calendar"></i> {{ $video->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="video-actions">
                                        @if(auth()->check() && $video->purchases()->where('user_id', auth()->id())->exists())
                                            <!-- User has purchased this video -->
                                            <div class="alert alert-success mb-2">
                                                <i class="fa fa-check-circle"></i> Purchased!
                                            </div>
                                            <a href="{{ route('videos.show', $video) }}" class="btn btn-success btn-block">
                                                <i class="fa fa-play"></i> Watch Video
                                            </a>
                                        @else
                                            <!-- User needs to purchase -->
                                            <div class="alert alert-warning mb-2">
                                                <i class="fa fa-lock"></i> Purchase Required
                                            </div>
                                            <a href="{{ route('videos.buy', $video) }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-shopping-cart"></i> Buy for ${{ number_format($video->price, 2) }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fa fa-video fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted">No Premium Videos Yet</h4>
                                <p class="text-muted">{{ $creator->name }} hasn't uploaded any paid videos yet. Check back later!</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination for Paid Videos -->
                @if($paidVideos->hasPages())
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                {{ $paidVideos->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
.creator-profile-avatar {
    border: 5px solid white;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.creator-profile-details h2 {
    color: #333;
    font-weight: 600;
}

.creator-designation {
    font-size: 1.1rem;
}

.creator-bio {
    font-size: 1rem;
    line-height: 1.6;
    color: #666;
}

.stat-item {
    padding: 15px 0;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #007bff;
    margin: 5px 0;
}

.creator-badges .badge {
    font-size: 0.9rem;
    padding: 8px 15px;
}

.section-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 15px;
}

.section-header h3 {
    color: #333;
    margin-bottom: 10px;
}

.video-player-container {
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

.video-player-container video {
    width: 100%;
    height: auto;
}

.intro-video-info h4 {
    color: #333;
    margin-bottom: 15px;
}

.meta-item {
    padding: 10px 0;
}

.meta-value {
    font-size: 1.1rem;
    font-weight: bold;
    color: #007bff;
    margin: 5px 0;
}

.hover-shadow:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
    transition: all 0.3s ease;
}

.video-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card-img-top {
    border-radius: 0;
}

.badge-pill {
    padding: 6px 12px;
    font-size: 0.8rem;
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.shadow-sm {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}

.video-actions .alert {
    padding: 8px 12px;
    margin-bottom: 10px;
    font-size: 0.9rem;
}
</style>
@endpush
