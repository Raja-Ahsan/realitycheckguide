@extends('layouts.website.master')
@section('title', $page_title)
@section('content')

    <!-- Home Slider Section -->
    <div class="swiper hero-banner">
        <div class="swiper-wrapper">
            @foreach ($homesliders as $homeslider)
                <div class="swiper-slide"
                    style="background-image: url('{{ asset('admin/assets/images/HomeSlider/' . ($homeslider->image ? $homeslider->image : 'no-photo1.jpg')) }}');">
                    <div class="banner-wrapper">
                        <div class="container">
                            <div class="row">
                                @include('website.include.social-links')
                                <div class="col-lg-8">
                                    <div class="card" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1500">
                                        <div class="shape-1"></div>
                                        <div class="fw-700 fs-30 text-white text-uppercase">
                                            {!! $homeslider->description !!}
                                        </div>
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
                </div>
            @endforeach
        </div>
        {{-- <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div> --}}
    </div>

    @if(isset($home_page_data['about_status']) && $home_page_data['about_status'] == 1)
        <section class="who-we-are py-70" id="sec-1">
            <div class="container">
                <div class="row row-gap-70">
                    <div class="col-lg-6" data-aos="fade-right" data-aos-easing="linear" data-aos-duration="1500">
                        <div class="img-wrapper">
                            <img src="{{ asset('/public/admin/assets/images/page/'.$home_page_data['home_about_image']) }}" class=""
                                alt="About scvba">
                        </div>
                    </div>
                    <div class="col-lg-6" data-aos="fade-left" data-aos-easing="linear" data-aos-duration="1500">
                        <span class="text-uppercase hd-24 ">{{ $home_page_data['home_about_title'] }}</span>
                        <h2 class="text-uppercase hd-42 mb-20">{{ $home_page_data['home_about_heading'] }}</h2>
                        <div class="para mb-20">
                        {!! $home_page_data['home_about_description'] !!}
                        </div>
                    </div>
                    <div class="col-lg-3" data-aos="fade-right" data-aos-easing="linear" data-aos-duration="1500">
                        <h3 class="hd-42 mb-10 text-uppercase">{{ $home_page_data['home_our_mission_heading'] }}</h3>
                        <div class="para">
                        {!! $home_page_data['home_our_mission_description'] !!}
                        </div>
                    </div>
                    <div class="col-lg-9" data-aos="fade-left" data-aos-easing="linear" data-aos-duration="1500">
                        <img src="{{ asset('/public/admin/assets/images/page/'.$home_page_data['home_our_mission_image']) }}" class="our-mission"
                            alt="our mission">
                    </div>
                </div>
            </div>
        </section>
    @endif
    <section class="sec-3" id="alliance-sec">
        <div class="container-flid">
            <div class="row row-gap-40">
                <div class="col-lg-8">
                    <img src="{{ asset('assets/website') }}/images/welcome.png" class="h-100 object-fit-cover"
                        alt="">
                </div>
                <div class="col-lg-4" data-aos="fade-left" data-aos-easing="linear" data-aos-duration="1500">
                    <h3 class="hd-55 text-white text-uppercase mb-30">Alliance Area</h3>
                    <p class="para text-white mb-30">
                        SCVBA includes, but is not limited to, <br> businesses within the following counties:
                    </p>
                    <p class="para text-white mb-30">
                        Amelia, Nottoway, Goochland, Halifax, Charlotte, Pittsylvania, Stafford, Spotsylvania,
                    </p>
                    <p class="para text-white mb-30">
                        Expansion anticipated into Buckingham, Henrico, and Louisa Counties coming in the near future!
                    </p>
                    <a href="javascript:;" type="button" class="btn btn-primary">JOIN US</a>
                </div>
            </div>
        </div>
    </section>
    <section class="member-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6" data-aos="flip-left" data-aos-easing="linear" data-aos-duration="1500">
                    <div class="card position-relative">
                        <h4 class="hd-55 text-white text-uppercase">Become a Member</h4>
                        <p class="text-white para mb-20">
                            The South-Central Virginia Business Alliance connects the best local talent, services, goods,
                            and supplies to the major economic opportunities of construction, maintenance, and operations.
                        </p>
                        <p class="text-white para mb-20">
                            We promote and advocate for all local businesses within our <a href="#alliance-sec"
                                class="text-secondry-theme">Alliance Areas.</a>
                        </p>
                        <div class="ms-auto">
                            <a href="javascript:;" class="btn btn-primary text-capitalize">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
