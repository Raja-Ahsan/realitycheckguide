@extends('layouts.website.master')
@section('title', $page_title)
@section('content')
    <!-- ======= Hero Section ======= -->
    <!-- banner  -->
    @if (!empty($banner->image))
        <section class="inner-banner contact-banner"
            style="background-image: url('{{ asset('admin/assets/images/banner') }}/{{ $banner->image }}');">
        @else
            <section class="inner-banner contact-banner"
                style="background-image: url('{{ asset('admin/assets/images/images.png') }}');" style="width:100%">
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



    <section class="contact-page-sec pt-100" id="sec-1">
        <div class="container">
            <div class="row align-items-center row-gap-40">
                <div class="col-lg-6" data-aos="fade-right" data-aos-easing="linear" data-aos-duration="1500">
                    <h2 class="hd-70 heading text-primary-theme mb-20">{!! $home_page_data['contact_heading'] !!}</h2>
                    <ul class="contact-links">
                        <li class="d-flex align-items-center">
                            <span class="icon-wrapper">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <a href="tel:{{ $home_page_data['contact_phone'] }}">{{ $home_page_data['contact_phone'] }}</a>
                        </li>
                        <li class="d-flex align-items-center">
                            <span class="icon-wrapper">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <a
                                href="mailto:{{ $home_page_data['contact_email'] }}">{{ $home_page_data['contact_email'] }}</a>
                        </li>
                        <li class="d-flex align-items-center">
                            <span class="icon-wrapper">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <a href="#">
                                <span>{{ $home_page_data['contact_address'] }}</span>
                            </a>
                        </li>
                    </ul>
                    <h3 class="heading hd-70  text-primary-theme mb-20">{!! $home_page_data['form_heading'] !!}</h3>
                    <ul class="d-flex primary-list justify-content-between social-networks" style="max-width: 43rem;">
                        <li><a href="{{ $home_page_data['footer_facebok'] }}" class="text-uppercase">FACEBOOK</a></li>
                        <li><a href="{{ $home_page_data['footer_twiter'] }}" class="text-uppercase">TWITTER</a></li>
                        <li><a href="{{ $home_page_data['footer_linkdin'] }}" class="text-uppercase">LINKEDIN</a></li>
                    </ul>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-easing="linear" data-aos-duration="1500">
                    <div class="img-wrapper">
                        <img src="{{ asset('admin/assets/images/page') }}/{{ $home_page_data['contact_image'] }}" alt="contact">
                    </div>
                </div>
            </div>
        </div>
    </section>




@endsection
