@extends('layouts.website.master')
@section('title', $page_title)
@section('content')

@if(!empty($banner->image))
    <section class="inner-banner category-detail-banner" style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('admin/assets/images/banner') }}/{{ $banner->image }}');">
@else
    <section class="inner-banner category-detail-banner" style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('admin/assets/images/images.png') }}');">
@endif
    <div class="banner-wrapper position-relative z-1">
        <div class="container">
            <div class="row"> 
                <div class="col-lg-12 col-xl-12" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1500"> 
                    <h1 class="hd-70 mt-5">{{ $category->title }}</h1>
                    <p class="hd-20 text-white">Explore real career stories in this category</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="category-detail-sec pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="category-detail-content">
                    <div class="category-header-section mb-4">
                        <h2 class="section-title mb-3">{{ $category->title }}</h2>
                        <!-- @if($category->subtitle)
                        <p class="category-subtitle mb-4">{{ $category->subtitle }}</p>
                        @endif -->
                        @if($category->image)
                        <div class="category-detail-image">
                            <img src="{{ asset('admin/assets/images/categories/'.$category->image) }}" alt="{{ $category->title }}" class="img-fluid rounded category-main-image">
                        </div>
                        @else
                        <div class="category-detail-image">
                            <img src="{{ asset('assets/website') }}/img/cat1.png" alt="{{ $category->title }}" class="img-fluid rounded category-main-image">
                        </div>
                        @endif
                    </div>
                    
                    <div class="category-description-section">
                        <h3 class="section-subtitle">About {{ $category->title }}</h3>
                        @if($category->description)
                            <div class="category-description-text">
                                {!! $category->description !!}
                            </div>
                        @else
                            <p class="text-muted">No description available for this category.</p>
                        @endif
                    </div>
                    
                    @php
                        $discoverPoints = !empty($category->discover_points) ? json_decode($category->discover_points, true) : [];
                    @endphp
                    @if(!empty($discoverPoints) && is_array($discoverPoints) && count($discoverPoints) > 0)
                    <div class="category-features mt-5">
                        <h3 class="section-subtitle">What You'll Discover</h3>
                        <div class="row">
                            @foreach($discoverPoints as $index => $point)
                                @if($index % 2 == 0)
                                    <div class="col-md-6">
                                        <ul class="feature-list">
                                @endif
                                            <li><i class="fa fa-check-circle"></i> {{ $point }}</li>
                                @if($index % 2 == 1 || $index == count($discoverPoints) - 1)
                                        </ul>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-4 col-md-12">
                <div class="category-sidebar">
                    <div class="sidebar-widget mb-4">
                        <h4 class="widget-title">Quick Links</h4>
                        <ul class="widget-links">
                            <li><a href="{{ route('categories') }}"><i class="fa fa-arrow-left"></i> All Categories</a></li>
                            <li><a href="{{ route('index') }}"><i class="fa fa-home"></i> Home</a></li>
                            <li><a href="{{ route('creators.index') }}"><i class="fa fa-users"></i> Content Creators</a></li>
                        </ul>
                    </div>
                    
                    @if($relatedCategories->count() > 0)
                    <div class="sidebar-widget">
                        <h4 class="widget-title">Related Categories</h4>
                        <div class="related-categories">
                            @foreach($relatedCategories as $index => $related)
                            <div class="related-category-item mb-3">
                                <a href="{{ route('category.detail', $related->slug) }}" class="d-flex align-items-center">
                                    @if($related->image)
                                        <img src="{{ asset('admin/assets/images/categories/'.$related->image) }}" alt="{{ $related->title }}" class="related-category-img">
                                    @else
                                        @php
                                            // Use different fallback images based on index to avoid all showing the same image
                                            $fallbackIndex = ($index % 6) + 1;
                                        @endphp
                                        <img src="{{ asset('assets/website') }}/img/cat{{ $fallbackIndex }}.png" alt="{{ $related->title }}" class="related-category-img">
                                    @endif
                                    <span class="related-category-title">{{ $related->title }}</span>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .pt-100 {
        padding-top: 100px;
    }
    .pb-100 {
        padding-bottom: 100px;
    }
.category-detail-sec {
    background: #f8f9fa;
}

.category-detail-content {
    background: #ffffff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}


.category-header-section {
    text-align: center;
    margin-bottom: 30px;
}

.category-subtitle {
    font-size: 18px;
    color: #666;
    font-style: italic;
    margin-bottom: 20px;
}

.section-title {
    font-size: 32px;
    font-weight: 700;
    color: #1a355e;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 3px solid #ffc430;
    text-align: left;
}

.category-main-image {
    max-width: 100%;
    max-height: 400px;
    object-fit: cover;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: 3px solid #ffc430;
    transition: transform 0.3s ease;
}

.category-main-image:hover {
    transform: scale(1.02);
}

.section-subtitle {
    font-size: 24px;
    font-weight: 600;
    color: #1a355e;
    margin-bottom: 20px;
}

.category-description-text {
    font-size: 16px;
    line-height: 1.8;
    color: #555;
    text-align: justify;
}

.category-description-text p {
    margin-bottom: 15px;
}

.feature-list {
    list-style: none;
    padding: 0;
}

.feature-list li {
    padding: 10px 0;
    color: #555;
    font-size: 15px;
}

.feature-list li i {
    color: #ffc430;
    margin-right: 10px;
    font-size: 18px;
}

.category-sidebar {
    position: sticky;
    top: 100px;
}

.sidebar-widget {
    background: #ffffff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.widget-title {
    font-size: 20px;
    font-weight: 600;
    color: #1a355e;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ffc430;
}

.widget-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.widget-links li {
    margin-bottom: 10px;
}

.widget-links li a {
    color: #555;
    text-decoration: none;
    display: block;
    padding: 10px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.widget-links li a:hover {
    background: #f8f9fa;
    color: #ffc430;
    padding-left: 15px;
}

.widget-links li a i {
    margin-right: 10px;
    color: #ffc430;
}

.related-category-item a {
    text-decoration: none;
    color: #555;
    transition: all 0.3s ease;
}

.related-category-item a:hover {
    color: #ffc430;
}

.related-category-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 15px;
}

.related-category-title {
    font-weight: 500;
    font-size: 15px;
}

@media (max-width: 768px) {
    .category-detail-content {
        padding: 25px;
    }
    
    .section-title {
        font-size: 26px;
    }
    
    .category-sidebar {
        position: relative;
        top: 0;
        margin-top: 30px;
    }
}
</style>

@endsection

