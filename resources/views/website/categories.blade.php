@extends('layouts.website.master')
@section('title', $page_title)
@section('content')

@if(!empty($banner->image))
    <section class="inner-banner categories-banner" style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('admin/assets/images/banner') }}/{{ $banner->image }}');">
@else
    <section class="inner-banner categories-banner" style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('admin/assets/images/images.png') }}');">
@endif
    <div class="banner-wrapper position-relative z-1">
        <div class="container">
            <div class="row"> 
                <div class="col-lg-12 col-xl-12" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1500"> 
                    <h1 class="hd-70 mt-5">Browse Career Categories</h1>
                    <p class="hd-20 text-white">Explore all available career categories and discover real stories from professionals</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="categories-sec pt-100 pb-100">
    <div class="container">
        <div class="row">
            @forelse($categories as $category)
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="category-card h-100">
                    <div class="category-image-wrapper">
                        <a href="{{ route('category.detail', $category->slug) }}">
                            @if($category->image)
                                <img src="{{ asset('admin/assets/images/categories/'.$category->image) }}" alt="{{ $category->title }}" class="category-image">
                            @else
                                <img src="{{ asset('assets/website') }}/img/cat{{ ($loop->index % 6) + 1 }}.png" alt="{{ $category->title }}" class="category-image">
                            @endif
                        </a>
                    </div>
                    <div class="category-content">
                        <h4 class="category-title">
                            <a href="{{ route('category.detail', $category->slug) }}">{{ $category->title }}</a>
                        </h4>
                        <p class="category-description">{{ $category->subtitle }}</p>
                        <!-- @if($category->description)
                        <p class="category-description">{{ \Illuminate\Support\Str::limit(strip_tags($category->description), 120) }}</p>
                        @endif -->
                        <div class="category-action">
                            <a href="{{ route('category.detail', $category->slug) }}" class="btn btn-primary btn-sm">Explore Category</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>No Categories Available</h4>
                    <p>Categories will be displayed here once they are added by the administrator.</p>
                </div>
            </div>
            @endforelse
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
.categories-sec {
    background: #f8f9fa;
}

.category-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.category-image-wrapper {
    width: 100%;
    height: 200px;
    overflow: hidden;
    position: relative;
}

.category-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-image {
    transform: scale(1.1);
}

.category-content {
    padding: 25px;
}

.category-title {
    margin-bottom: 15px;
    font-size: 22px;
    font-weight: 600;
    color: #1a355e;
}

.category-title a {
    color: #1a355e;
    text-decoration: none;
    transition: color 0.3s ease;
}

.category-title a:hover {
    color: #ffc430;
}

.category-description {
    color: #555;
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 20px;
    min-height: 48px;
}

.category-action .btn {
    background: linear-gradient(135deg, #ffc430 0%, #df9816 100%);
    border: none;
    color: #ffffff;
    padding: 10px 25px;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.category-action .btn:hover {
    background: linear-gradient(135deg, #df9816 0%, #ffc430 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 196, 48, 0.4);
    color: #ffffff;
}

@media (max-width: 768px) {
    .category-image-wrapper {
        height: 180px;
    }
    
    .category-content {
        padding: 20px;
    }
    
    .category-title {
        font-size: 20px;
    }
}
</style>

@endsection

