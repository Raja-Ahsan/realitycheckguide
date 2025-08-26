@extends('layouts.website.master')

@section('title', 'Content Creators')

@section('content')
    @if (!empty($banner->image))
        <section class="inner-banner creators-banner"
            style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('public/admin/assets/images/banner') }}/{{ $banner->image }}');">
        @else
        <section class="inner-banner creators-banner" 
            style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('public/admin/assets/images/images.png') }}');">
    @endif
        <div class="banner-wrapper position-relative z-1">
            <div class="container">
                <div class="row"> 
                    <div class="col-lg-12 col-xl-12" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1500"> 
                        <h1 class="hd-70 mt-5" >Discover Amazing Content Creators</h1>
                        <p class="hd-20 text-white">Watch free intro videos and explore premium content from talented creators</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="creators-sec pt-100 pb-100">
        <div class="container">
            <!-- Search and Filter Section -->
            <div class="row mb-5 mt-5">
                <div class="col-md-8">
                    <form action="{{ route('creators.index') }}" method="GET" class="search-form">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-lg" 
                                   placeholder="Search creators by name, skills, or content..." 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-lg" type="submit">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group btn-group-lg">
                        <button type="button" class="btn btn-outline-primary active" data-filter="all">All</button>
                        <button type="button" class="btn btn-outline-success" data-filter="verified">Verified</button>
                        <button type="button" class="btn btn-outline-info" data-filter="featured">Featured</button>
                    </div>
                </div>
            </div>

            <!-- Creators Grid -->
            <div class="row" id="creators-container">
                @forelse($creators as $creator)
                    <div class="col-lg-4 col-md-6 mb-4 creator-item" 
                         data-verified="{{ $creator->is_verified ? 'verified' : 'unverified' }}"
                         data-featured="{{ $creator->is_featured ? 'featured' : 'regular' }}">
                        <div class="creator-card h-100 shadow-sm hover-shadow">
                            <!-- Creator Header -->
                            <div class="creator-header position-relative">
                                @if($creator->cover_image)
                                    <img src="{{ asset('storage/' . $creator->cover_image) }}" 
                                         alt="{{ $creator->name }}" 
                                         class="creator-cover" style="height: 150px; object-fit: cover; width: 100%;">
                                @else
                                    <div class="creator-cover bg-gradient-primary d-flex align-items-center justify-content-center" 
                                         style="height: 150px; background: linear-gradient(315deg, #ffc430, #ffffff);">
                                        <i class="fa fa-user-tie fa-3x text-white"></i>
                                    </div>
                                @endif
                                
                                <!-- Creator Avatar -->
                                <div class="creator-avatar">
                                    @if($creator->image)
                                        <img src="{{ asset('storage/' . $creator->image) }}" 
                                             alt="{{ $creator->name }}" 
                                             class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px;">
                                            <i class="fa fa-user fa-2x text-white"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Status Badges -->
                                <div class="position-absolute top-0 left-0 m-2">
                                    @if($creator->is_verified)
                                        <span class="badge badge-success badge-pill">
                                            <i class="fa fa-check-circle"></i> Verified
                                        </span>
                                    @endif
                                    @if($creator->is_featured)
                                        <span class="badge badge-warning badge-pill">
                                            <i class="fa fa-star"></i> Featured
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Creator Info -->
                            <div class="creator-body text-center pt-4">
                                <h4 class="creator-name mb-2">
                                    <a href="{{ route('creators.show', $creator) }}" class="text-decoration-none text-dark">
                                        {{ $creator->name }}
                                    </a>
                                </h4>
                                
                                <p class="creator-designation text-muted mb-3">
                                    {{ $creator->designation ?? 'Content Creator' }}
                                </p>

                                <p class="creator-bio mb-3">
                                    {{ Str::limit($creator->about_me ?? 'Passionate content creator sharing knowledge and skills.', 100) }}
                                </p>

                                <!-- Creator Stats -->
                                <div class="creator-stats row text-center mb-3">
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $creator->videos()->count() }}</div>
                                            <small class="text-muted">Videos</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ number_format($creator->total_views ?? 0) }}</div>
                                            <small class="text-muted">Views</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ number_format($creator->total_earnings ?? 0) }}</div>
                                            <small class="text-muted">Earnings</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Intro Video Section -->
                                @if($creator->introVideo)
                                    <div class="intro-video-section mb-3">
                                        <h6 class="text-info mb-2">
                                            <i class="fa fa-play-circle"></i> Free Intro Video
                                        </h6>
                                        <div class="intro-video-thumbnail position-relative">
                                            @if($creator->introVideo->thumbnail_path)
                                                <img src="{{ asset('storage/app/public/' . $creator->introVideo->thumbnail_path) }}" 
                                                     alt="{{ $creator->introVideo->title }}" 
                                                     class="img-fluid rounded" style="height: 120px; object-fit: cover; width: 100%;">
                                            @else
                                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                     style="height: 120px;">
                                                    <i class="fa fa-video fa-2x text-white"></i>
                                                </div>
                                            @endif
                                            
                                            <!-- Play Button Overlay -->
                                            <div class="play-button-overlay">
                                                <a href="{{ route('creators.show', $creator) }}" 
                                                   class="btn btn-light btn-circle">
                                                    <i class="fa fa-play text-primary"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <p class="intro-video-title small mb-0 mt-2">
                                            {{ Str::limit($creator->introVideo->title, 50) }}
                                        </p>
                                    </div>
                                @else
                                    <div class="no-intro-video mb-3">
                                        <div class="bg-light rounded p-3">
                                            <i class="fa fa-info-circle text-muted"></i>
                                            <small class="text-muted">No intro video available yet</small>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="creator-actions">
                                    <a href="{{ route('creators.show', $creator) }}" class="btn btn-primary btn-block">
                                        <i class="fa fa-eye"></i> View Creator Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fa fa-users fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted">No Creators Found</h4>
                            <p class="text-muted">Try adjusting your search criteria or check back later.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($creators->hasPages())
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            {{ $creators->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    transition: all 0.3s ease;
}

.creator-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.creator-header {
    position: relative;
}

.creator-avatar {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
}

.creator-body {
    padding: 0 20px 20px;
}

.creator-name {
    font-size: 1.25rem;
    font-weight: 600;
}

.creator-designation {
    font-size: 0.9rem;
    font-style: italic;
}

.creator-bio {
    font-size: 0.9rem;
    line-height: 1.5;
}

.stat-item {
    padding: 8px 0;
}

.stat-value {
    font-size: 1.1rem;
    font-weight: bold;
    color: #ffc430;
}

.intro-video-thumbnail {
    position: relative;
    cursor: pointer;
}

.play-button-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.btn-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #020202 0%, #000000 100%);
}
.btn-primary {
    color: #fff;
    background-color: #020202;
    border-color: #020202;
}

.shadow-sm {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}

.badge-pill {
    padding: 6px 12px;
    font-size: 0.8rem;
}

.search-form .form-control {
    border-radius: 25px 0 0 25px;
}

.search-form .btn {
    border-radius: 0 25px 25px 0;
}

.btn-group .btn {
    border-radius: 25px;
    margin: 0 5px;
}

.btn-group .btn:first-child {
    margin-left: 0;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
.btn-group>.btn-group:not(:first-child), .btn-group>.btn:not(:first-child){
    margin-left:  -6px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Filter creators
    $('[data-filter]').on('click', function() {
        let filter = $(this).data('filter');
        
        // Update active button
        $('[data-filter]').removeClass('active btn-primary').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('active btn-primary');
        
        // Show/hide creators based on filter
        if (filter === 'all') {
            $('.creator-item').fadeIn(300);
        } else {
            $('.creator-item').hide();
            $('.creator-item[data-' + filter + '="' + filter + '"]').fadeIn(300);
        }
    });
    
    // Set "All" as default active filter
    $('[data-filter="all"]').removeClass('btn-outline-primary').addClass('active btn-primary');
});
</script>
@endpush
