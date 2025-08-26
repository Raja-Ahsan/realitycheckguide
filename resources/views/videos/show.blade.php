@extends('layouts.website.master')

@section('title', $video->title)

@section('content')
    @if (!empty($banner->image))
        <section class="inner-banner creator-profile-banner"
            style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('public/admin/assets/images/banner') }}/{{ $banner->image }}');">
        @else
        <section class="inner-banner creator-profile-banner" 
            style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('public/admin/assets/images/images.png') }}');">
    @endif
        <div class="banner-wrapper position-relative z-1">
            <div class="container">
                <div class="row"> 
                    <div class="col-lg-12 col-xl-12" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1500"> 
                        <h1 class="hd-70 mt-5">Video</h1>
                        <p class="hd-20 text-white">Video Detail</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="video-detail-sec pt-100 pb-100 mt-5">
        <div class="container">
            <div class="row">
                <!-- Video Player Section -->
                <div class="col-lg-8">
                    <div class="video-player-container mb-4">
                        @if($video->video_path)
                            <video controls class="w-100" style="max-height: 500px;">
                                <source src="{{ asset('storage/app/public/' . $video->video_path) }}" type="video/mp4">
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

                    <!-- Video Information -->
                    <div class="video-info">
                        <h1 class="hd-40 mb-3">{{ $video->title }}</h1>
                        
                        <div class="video-meta mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="badge badge-{{ $video->is_intro ? 'info' : 'warning' }} badge-pill mr-2">
                                        {{ $video->is_intro ? 'Intro Video' : 'Paid Video' }}
                                    </span>
                                    @if($video->price > 0)
                                        <span class="badge badge-success badge-pill">
                                            ${{ number_format($video->price, 2) }}
                                        </span>
                                    @else
                                        <span class="badge badge-success badge-pill">Free</span>
                                    @endif
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted">
                                        <i class="fa fa-eye"></i> {{ number_format($video->views_count ?? 0) }} views
                                        <i class="fa fa-calendar ml-2"></i> {{ $video->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="video-description mb-4">
                            <h5>Description</h5>
                            <p>{{ $video->description ?: 'No description available.' }}</p>
                        </div>

                        <!-- Creator Information -->
                        <div class="creator-info mb-4">
                            <h5>Creator</h5>
                            <div class="d-flex align-items-center">
                                @if($video->creator->image)
                                    <img src="{{ asset('storage/app/public/' . $video->creator->image) }}" 
                                         alt="{{ $video->creator->name }}" 
                                         class="rounded-circle mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mr-3" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fa fa-user text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $video->creator->name }}</h6>
                                    <small class="text-muted">{{ $video->creator->designation ?? 'Content Creator' }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="video-actions">
                            @if($video->is_intro)
                                <!-- Intro video is always accessible -->
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> This is a free introduction video from {{ $video->creator->name }}.
                                </div>
                            @else
                                @if($hasPurchased)
                                    <!-- User has purchased this video -->
                                    <div class="alert alert-success">
                                        <i class="fa fa-check-circle"></i> You have purchased this video!
                                        @if($video->downloads_enabled)
                                            <a href="{{ route('videos.download', $video) }}" class="btn btn-outline-primary btn-sm ml-2">
                                                <i class="fa fa-download"></i> Download Video
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <!-- User needs to purchase -->
                                    <div class="alert alert-warning">
                                        <i class="fa fa-lock"></i> This is a paid video. Purchase required to watch.
                                        <a href="{{ route('videos.buy', $video) }}" class="btn btn-primary ml-2">
                                            <i class="fa fa-shopping-cart"></i> Buy for ${{ number_format($video->price, 2) }}
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Related Videos -->
                    <div class="related-videos">
                        <h5 class="mb-3">More from {{ $video->creator->name }}</h5>
                        @forelse($relatedVideos as $relatedVideo)
                            <div class="video-card mb-3">
                                <div class="row">
                                    <div class="col-4">
                                        @if($relatedVideo->thumbnail_path)
                                            <img src="{{ asset('storage/app/public/' . $relatedVideo->thumbnail_path) }}" 
                                                 alt="{{ $relatedVideo->title }}" 
                                                 class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                 style="height: 80px;">
                                                <i class="fa fa-video text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-8">
                                        <h6 class="mb-1">
                                            <a href="{{ route('videos.show', $relatedVideo) }}" class="text-decoration-none">
                                                {{ Str::limit($relatedVideo->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="video-meta-small">
                                            @if($relatedVideo->is_intro)
                                                <span class="label label-info">Free</span>
                                            @else
                                                <span class="label label-info">${{ number_format($relatedVideo->price, 2) }}</span>
                                            @endif
                                            <small class="text-muted ml-2">
                                                <i class="fa fa-eye"></i> {{ number_format($relatedVideo->views_count ?? 0) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No other videos from this creator yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('styles')
<style>
.video-player-container {
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

.video-player-container video {
    width: 100%;
    height: auto;
}

.video-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 10px;
    transition: all 0.3s ease;
}

.video-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.badge-sm {
    font-size: 0.7rem;
    padding: 4px 8px;
}

.video-meta-small {
    margin-top: 5px;
}
</style>
@endpush


