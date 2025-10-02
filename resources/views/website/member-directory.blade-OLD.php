@extends('layouts.website.master')
@section('title', $page_title)
@section('content')
    @if (!empty($banner->image))
        <section class="inner-banner benefits-banner"
            style="background-image: url('{{ asset('public/admin/assets/images/banner') }}/{{ $banner->image }}');">
        @else
            <section class="inner-banner benefits-banner"
                style="background-image: url('{{ asset('public/admin/assets/images/images.png') }}');" style="width:100%">
    @endif
    <div class="banner-wrapper position-relative z-1">
        <div class="container">
            <div class="row">
                @include('website.include.social-links')
                <div class="col-lg-7 col-xl-6" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1500">
                    <div class="card">
                        <div class="shape-1"></div>
                        @if (isset($banner))
                            <h1 class="hd-70">{{ $banner->name }}</h1>
                        @endif
                    </div>
                </div>
            </div>
            <a href="#sec-1" class="">
                <div class="top-to-bottom">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>
            </a>
        </div>
    </div>
    </section>

    <section class="member-page-sec pt-150" id="sec-1">
        <div class="container">
            <h2 class="hd-70 heading mb-20 text-center" data-aos="flip-left" data-aos-easing="linear"
                data-aos-duration="1500">
                <span class="text-primary-theme text-center text-capitalize">Member</span> <span>Directory</span>
            </h2>

            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs member-tabs mb-4 justify-content-center flex-wrap" id="memberTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button"
                        role="tab" aria-controls="all" aria-selected="true">ALL</button>
                </li>
                @foreach ($categories as $category)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="{{ $category->slug }}-tab" data-bs-toggle="tab"
                            data-bs-target="#{{ $category->slug }}" type="button" role="tab"
                            aria-controls="{{ $category->slug }}">{{ $category->title }}</button>
                    </li>
                @endforeach
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="memberTabsContent">
                <!-- All Members Tab -->
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                    @php
                        $shownTitles = [];
                        $sortedMembers = collect($member_directories)->flatten()->sortBy('title');
                    @endphp
                    <div class="row row-gap-50">
                        @foreach ($sortedMembers as $member)
                            @if (!in_array($member->title, $shownTitles))
                                @php $shownTitles[] = $member->title; @endphp
                                <div class="col-lg-3 col-md-6">
                                    <div class="member-card-flip">
                                        <div class="member-card-inner">
                                            <!-- Front Side -->
                                            <div class="member-card-front">
                                                <div class="text-center">
                                                    <div class="card-img-wrapper mb-20">
                                                        @if ($member->image)
                                                            <img src="{{ asset('public/admin/assets/images/member_directory') }}/{{ $member->image }}"
                                                                alt="{{ $member->title }}">
                                                        @else
                                                            <img src="{{ asset('assets/website') }}/images/default-member.png"
                                                                alt="{{ $member->title }}">
                                                        @endif
                                                    </div>
                                                    <h3 class="fs-29 mb-10 fw-semibold text-primary-theme">
                                                        {{ $member->title }}</h3>
                                                    <h5 class="hd-20 text-secondry-theme mb-10">{{ $member->name }}</h5>
                                                </div>
                                            </div>
                                            <!-- Back Side -->
                                            <div class="member-card-back">
                                                <div class="text-center p-3">
                                                    <p class="hd-20 fw-normal mb-10">{{ $member->description }}</p>
                                                    <div class="member-contact-links">
                                                        @if ($member->phone_no)
                                                            <a href="tel:{{ $member->phone_no }}"
                                                                class="d-block fs-20 fw-semibold text-primary-theme">{{ $member->phone_no }}</a>
                                                        @endif
                                                        <div class="social-links mt-3">
                                                            @if ($member->url)
                                                                <a href="{{ $member->url }}" target="_blank"
                                                                    class="social-icon">
                                                                    <i class="fas fa-link" aria-hidden="true"></i>
                                                                </a>
                                                            @endif
                                                            @if ($member->email)
                                                                <a href="mailto:{{ $member->email }}" class="social-icon">
                                                                    <i class="fas fa-envelope" aria-hidden="true"></i>
                                                                </a>
                                                            @endif
                                                            @if ($member->facebook)
                                                                <a href="{{ $member->facebook }}" target="_blank"
                                                                    class="social-icon">
                                                                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <!-- Category Tabs -->
                @foreach ($categories as $category)
                    <div class="tab-pane fade" id="{{ $category->slug }}" role="tabpanel"
                        aria-labelledby="{{ $category->slug }}-tab">
                        <div class="row row-gap-50">
                            @if (isset($member_directories[$category->id]))
                                @php
                                    $categorySortedMembers = collect($member_directories[$category->id])->sortBy('title');
                                @endphp
                                @foreach ($categorySortedMembers as $member)
                                    <div class="col-lg-3 col-md-6">
                                        <div class="member-card-flip">
                                            <div class="member-card-inner">
                                                <!-- Front Side -->
                                                <div class="member-card-front">
                                                    <div class="text-center">
                                                        <div class="card-img-wrapper mb-20">
                                                            @if ($member->image)
                                                                <img src="{{ asset('public/admin/assets/images/member_directory') }}/{{ $member->image }}"
                                                                    alt="{{ $member->title }}">
                                                            @else
                                                                <img src="{{ asset('assets/website') }}/images/default-member.png"
                                                                    alt="{{ $member->title }}">
                                                            @endif
                                                        </div>
                                                        <h3 class="fs-29 mb-10 fw-semibold text-primary-theme">
                                                            {{ $member->title }}</h3>
                                                        <h5 class="hd-20 text-secondry-theme mb-10">{{ $member->name }}</h5>
                                                    </div>
                                                </div>
                                                <!-- Back Side -->
                                                <div class="member-card-back">
                                                    <div class="text-center p-3">
                                                        <p class="hd-20 fw-normal mb-10">{{ $member->description }}</p>
                                                        <div class="member-contact-links">
                                                            @if ($member->phone_no)
                                                                <a href="tel:{{ $member->phone_no }}"
                                                                    class="d-block fs-20 fw-semibold text-primary-theme">{{ $member->phone_no }}</a>
                                                            @endif
                                                            <div class="social-links mt-3">
                                                                @if ($member->url)
                                                                    <a href="{{ $member->url }}" target="_blank"
                                                                        class="social-icon">
                                                                        <i class="fas fa-link" aria-hidden="true"></i>
                                                                    </a>
                                                                @endif
                                                                @if ($member->email)
                                                                    <a href="mailto:{{ $member->email }}"
                                                                        class="social-icon">
                                                                        <i class="fas fa-envelope" aria-hidden="true"></i>
                                                                    </a>
                                                                @endif
                                                                @if ($member->facebook)
                                                                    <a href="{{ $member->facebook }}" target="_blank"
                                                                        class="social-icon">
                                                                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
