@extends('layouts.website.master')
@section('title', $page_title)
@section('content')
<!-- ***** Page Header Start ***** -->
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
                        <h1 class="hd-70 mt-5" >Learn More About Us</h1>
                        <p class="hd-20 text-white">About Reality Check Guide</p>
                    </div>
                </div>
            </div>
        </div>
    </section> 
<!-- ***** Page Header End ***** -->

  <!-- ***** Our Story Section Start ***** -->
  <section class="our-story" id="our-story" data-section="our-story">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="our-story-img-overlay position-relative">
            <img src="{{ asset('public/assets/website') }}/img/about-us.png" alt="our-story" class="our-story-img">
            <img src="{{ asset('public/assets/website') }}/img/logo.png" alt="our-story-overlay"
              class="our-story-img-overlay-img position-absolute">
          </div>
          <div class="our-story-img-overlay-text">
            <p>After seeing so many people unhappy in their careers, I wanted to create a place where we could learn
              from each other's real stories. This isn't just a site it's a movement toward clarity, purpose, and truth.
            </p>
          </div>
          <div class="our-story-img-overlay-text-bottom">
            <p>Jeanmarie, Founder of Reality Check Guide</p>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="section-heading">
            <h4>Our Story</h4>
            <h2>Why We <span>Started</span></h2>
          </div>
          <p>Reality Check Guide was born from a simple observation: too many people are stuck in careers that don't fulfill them, simply because they didn't know what they were getting into. We saw friends, family, and colleagues making career decisions based on limited information, only to regret their choices years later.</p>
          
          <p>Our founder, Jeanmarie, experienced this firsthand. After years of watching people struggle with career dissatisfaction, she realized that the traditional approach to career guidance was missing something crucial - the real, unfiltered truth about what jobs are actually like.</p>
          
          <p>That's when the idea for Reality Check Guide was born. Instead of relying on polished job descriptions or theoretical career advice, we decided to create a platform where real people share their real experiences, helping others make informed decisions about their professional futures.</p>
          
          <div class="main-button-yellow">
            <a href="{{ route('index') }}#courses">Start Exploring Careers</a>
            <a href="{{ route('index') }}#upload-your-story">Share Your Story</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** Our Story Section End ***** -->

  <!-- ***** Our Mission Section Start ***** -->
  <section class="our-mission" id="our-mission" data-section="our-mission">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading text-center">
            <h2>Our <span>Mission</span></h2>
            <p class="mb-5">Empowering career decisions through authentic experiences</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <div class="mission-item">
            <div class="mission-icon">
              <img src="{{ asset('public/assets/website') }}/img/about-icon1.svg" alt="mission-1" class="img-fluid">
            </div>
            <h4>Authentic Stories</h4>
            <p>We believe in the power of experiences. Every story on our platform comes from someone who actually works in that field, providing genuine insights you can't find anywhere else.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <div class="mission-item">
            <div class="mission-icon">
              <img src="{{ asset('public/assets/website') }}/img/about-icon2.svg" alt="mission-2" class="img-fluid">
            </div>
            <h4>Informed Decisions</h4>
            <p>Our goal is to help you make career choices based on real information, not just job descriptions. We want you to know exactly what you're getting into before you commit.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <div class="mission-item">
            <div class="mission-icon">
              <img src="{{ asset('public/assets/website') }}/img/about-icon3.svg" alt="mission-3" class="img-fluid">
            </div>
            <h4>Community Support</h4>
            <p>We're building a community where people help each other navigate their career journeys. By sharing your story, you're helping someone else make a better decision.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** Our Mission Section End ***** -->

  <!-- ***** Our Values Section Start ***** -->
  <section class="our-values" id="our-values" data-section="our-values">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="section-heading">
            <h2>Our <span>Values</span></h2>
          </div>
          <div class="values-list">
            <div class="value-item">
              <div class="value-icon">
                <i class="fa fa-check-circle"></i>
              </div>
              <div class="value-content">
                <h5>Authenticity</h5>
                <p>We believe in honest, unfiltered stories that show the real picture of what careers are like.</p>
              </div>
            </div>
            <div class="value-item">
              <div class="value-icon">
                <i class="fa fa-users"></i>
              </div>
              <div class="value-content">
                <h5>Community</h5>
                <p>We're building a supportive community where people help each other make better career decisions.</p>
              </div>
            </div>
            <div class="value-item">
              <div class="value-icon">
                <i class="fa fa-lightbulb-o"></i>
              </div>
              <div class="value-content">
                <h5>Empowerment</h5>
                <p>We empower people with the knowledge they need to make informed career choices.</p>
              </div>
            </div>
            <div class="value-item">
              <div class="value-icon">
                <i class="fa fa-heart"></i>
              </div>
              <div class="value-content">
                <h5>Purpose</h5>
                <p>We help people find careers that align with their values, interests, and life goals.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="values-image">
            <img src="{{ asset('public/assets/website') }}/img/discover-logo.png" alt="our-values" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** Our Values Section End ***** -->


  <!-- ***** Join Our Community Section Start ***** -->
  <section class="join-community" id="join-community" data-section="join-community">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="join-community-content text-center">
            <h2>Join Our <span>Community</span></h2>
            <p>Be part of a movement that's changing how people make career decisions. Whether you're exploring careers or ready to share your story, there's a place for you here.</p>
            <div class="join-buttons">
              <a href="{{ route('index') }}#courses" class="btn btn-primary">Explore Careers</a>
              <a href="{{ route('index') }}#upload-your-story" class="btn btn-outline">Share Your Story</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** Join Our Community Section End ***** -->
@endsection