@extends('layouts.website.master')
@section('title', $page_title)
@section('content')

<!-- ***** Page Header Start ***** -->
@if (!empty($banner->image))
    <section class="inner-banner instructions-banner"
        style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('admin/assets/images/banner') }}/{{ $banner->image }}');">
@else
    <section class="inner-banner instructions-banner" 
        style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('admin/assets/images/images.png') }}');">
@endif
    <div class="banner-wrapper position-relative z-1">
        <div class="container">
            <div class="row"> 
                <div class="col-lg-12 col-xl-12" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1500"> 
                    <h1 class="hd-70 mt-5">Instructions for Easy Upload</h1>
                    <p class="hd-20 text-white">Step-by-step guide to upload your career videos</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Page Header End ***** -->

<!-- ***** Instructions Section Start ***** -->
<section class="instructions-section pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading text-center mb-5">
                    <h2>How to <span>Upload Your Career Video</span></h2>
                    <p class="mt-3">Follow these simple steps to share your career experience with our community</p>
                </div>
            </div>
        </div>

        <!-- Step 1 -->
        <div class="row mb-5 align-items-center">
            <div class="col-lg-6 col-md-6">
                <div class="instruction-step-card">
                    <div class="step-number">01</div>
                    <h3 class="step-title">Create Your Account</h3>
                    <p class="step-description">
                        Sign up as a Creator on Reality Check Guide. You'll need to provide basic information and verify your email address. Once verified, you'll have access to the video upload dashboard.
                    </p>
                    <ul class="step-features">
                        <li><i class="fa fa-check-circle"></i> Register with your email</li>
                        <li><i class="fa fa-check-circle"></i> Verify your account</li>
                        <li><i class="fa fa-check-circle"></i> Complete your profile</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="instruction-image">
                    <div class="image-placeholder" style="background: linear-gradient(135deg, #1a355e 0%, #ffc430 100%); height: 300px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;">
                        <div class="text-center">
                            <i class="fa fa-user-plus" style="font-size: 60px; margin-bottom: 20px;"></i>
                            <p>Account Creation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="row mb-5 align-items-center flex-row-reverse">
            <div class="col-lg-6 col-md-6">
                <div class="instruction-step-card">
                    <div class="step-number">02</div>
                    <h3 class="step-title">Prepare Your Video</h3>
                    <p class="step-description">
                        Before uploading, make sure your video meets our requirements. Record in a quiet, well-lit environment and keep it focused on your career experience.
                    </p>
                    <ul class="step-features">
                        <li><i class="fa fa-check-circle"></i> Video format: MP4, MOV, or AVI</li>
                        <li><i class="fa fa-check-circle"></i> Maximum file size: 500MB</li>
                        <li><i class="fa fa-check-circle"></i> Recommended duration: 5-15 minutes</li>
                        <li><i class="fa fa-check-circle"></i> Good audio and video quality</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="instruction-image">
                    <div class="image-placeholder" style="background: linear-gradient(135deg, #ffc430 0%, #1a355e 100%); height: 300px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;">
                        <div class="text-center">
                            <i class="fa fa-video-camera" style="font-size: 60px; margin-bottom: 20px;"></i>
                            <p>Video Preparation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="row mb-5 align-items-center">
            <div class="col-lg-6 col-md-6">
                <div class="instruction-step-card">
                    <div class="step-number">03</div>
                    <h3 class="step-title">Upload Your Video</h3>
                    <p class="step-description">
                        Navigate to your Creator Dashboard and click "Upload Video". Select your video file and wait for the upload to complete. The upload progress will be displayed.
                    </p>
                    <ul class="step-features">
                        <li><i class="fa fa-check-circle"></i> Go to Creator Dashboard</li>
                        <li><i class="fa fa-check-circle"></i> Click "Upload Video" button</li>
                        <li><i class="fa fa-check-circle"></i> Select your video file</li>
                        <li><i class="fa fa-check-circle"></i> Wait for upload completion</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="instruction-image">
                    <div class="image-placeholder" style="background: linear-gradient(135deg, #1a355e 0%, #ffc430 100%); height: 300px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;">
                        <div class="text-center">
                            <i class="fa fa-cloud-upload" style="font-size: 60px; margin-bottom: 20px;"></i>
                            <p>Video Upload</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="row mb-5 align-items-center flex-row-reverse">
            <div class="col-lg-6 col-md-6">
                <div class="instruction-step-card">
                    <div class="step-number">04</div>
                    <h3 class="step-title">Add Video Details</h3>
                    <p class="step-description">
                        Fill in the video information including title, description, category, and set your pricing. Add a thumbnail image to make your video stand out.
                    </p>
                    <ul class="step-features">
                        <li><i class="fa fa-check-circle"></i> Enter video title and description</li>
                        <li><i class="fa fa-check-circle"></i> Select appropriate category</li>
                        <li><i class="fa fa-check-circle"></i> Set video price (if premium)</li>
                        <li><i class="fa fa-check-circle"></i> Upload thumbnail image</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="instruction-image">
                    <div class="image-placeholder" style="background: linear-gradient(135deg, #ffc430 0%, #1a355e 100%); height: 300px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;">
                        <div class="text-center">
                            <i class="fa fa-edit" style="font-size: 60px; margin-bottom: 20px;"></i>
                            <p>Video Details</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 5 -->
        <div class="row mb-5 align-items-center">
            <div class="col-lg-6 col-md-6">
                <div class="instruction-step-card">
                    <div class="step-number">05</div>
                    <h3 class="step-title">Review & Publish</h3>
                    <p class="step-description">
                        Review all your video information, preview the video, and then click "Publish" to make it live. Your video will be available to viewers once published.
                    </p>
                    <ul class="step-features">
                        <li><i class="fa fa-check-circle"></i> Review all details</li>
                        <li><i class="fa fa-check-circle"></i> Preview your video</li>
                        <li><i class="fa fa-check-circle"></i> Click "Publish" button</li>
                        <li><i class="fa fa-check-circle"></i> Video goes live immediately</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="instruction-image">
                    <div class="image-placeholder" style="background: linear-gradient(135deg, #1a355e 0%, #ffc430 100%); height: 300px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;">
                        <div class="text-center">
                            <i class="fa fa-check-circle" style="font-size: 60px; margin-bottom: 20px;"></i>
                            <p>Publish Video</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="tips-section">
                    <div class="section-heading text-center mb-4">
                        <h3>Pro <span>Tips</span></h3>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="tip-card">
                                <div class="tip-icon">
                                    <i class="fa fa-lightbulb-o"></i>
                                </div>
                                <h4>Be Authentic</h4>
                                <p>Share your real experiences, both the good and the challenging aspects of your career.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="tip-card">
                                <div class="tip-icon">
                                    <i class="fa fa-microphone"></i>
                                </div>
                                <h4>Clear Audio</h4>
                                <p>Use a good microphone or record in a quiet space to ensure viewers can hear you clearly.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="tip-card">
                                <div class="tip-icon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <h4>Keep It Focused</h4>
                                <p>Stay on topic and cover the most important aspects of your career in a concise manner.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="cta-section text-center">
                    <h3>Ready to Share Your Story?</h3>
                    <p class="mb-4">Join our community of creators and help others make informed career decisions.</p>
                    @if(Auth::check() && Auth::user()->hasRole('Creator'))
                        <a href="{{ route('creator.dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard</a>
                    @elseif(Auth::check())
                        <a href="{{ route('sign-up') }}" class="btn btn-primary btn-lg">Become a Creator</a>
                    @else
                        <a href="{{ route('sign-up') }}" class="btn btn-primary btn-lg">Sign Up as Creator</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Instructions Section End ***** -->

<style>
    .pt-100 {
        padding-top: 100px;
    }
    .pb-100 {
        padding-bottom: 100px;
    }
.instructions-section {
    background: #f8f9fa;
}

.instructions-section .section-heading h2 {
    font-family: 'Playfair Display', serif;
    font-weight: 800;
    font-style: Bold;
    font-size: 40px;
    line-height: 100%;
    letter-spacing: -4%;
    color: #303030;
    text-transform: capitalize;
    text-align: center;
    margin-bottom: 40px;
}
.instructions-section .section-heading h2 span {
    color: #f5a425;
    font-style: italic;
}
.instruction-step-card {
    background: #ffffff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    position: relative;
    height: 100%;
}

.step-number {
    position: absolute;
    top: -20px;
    left: 30px;
    background: linear-gradient(135deg, #1a355e 0%, #ffc430 100%);
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.step-title {
    color: #1a355e;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 20px;
    margin-top: 20px;
}

.step-title span {
    color: #ffc430;
}

.step-description {
    color: #666;
    font-size: 16px;
    line-height: 1.8;
    margin-bottom: 25px;
}

.step-features {
    list-style: none;
    padding: 0;
    margin: 0;
}

.step-features li {
    padding: 10px 0;
    color: #555;
    font-size: 15px;
}

.step-features li i {
    color: #ffc430;
    margin-right: 10px;
    font-size: 18px;
}

.instruction-image {
    padding: 20px;
}

.instruction-image img {
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.image-placeholder {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.tips-section {
    background: #ffffff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.tip-card {
    text-align: center;
    padding: 30px 20px;
    background: #f8f9fa;
    border-radius: 10px;
    height: 100%;
    transition: transform 0.3s ease;
}

.tip-card:hover {
    transform: translateY(-5px);
}

.tip-icon {
    background: linear-gradient(135deg, #1a355e 0%, #ffc430 100%);
    color: white;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    margin: 0 auto 20px;
}

.tip-card h4 {
    color: #1a355e;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 15px;
}

.tip-card p {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
}

.cta-section {
    background: linear-gradient(135deg, #1a355e 0%, #ffc430 100%);
    padding: 50px 30px;
    border-radius: 12px;
    color: white;
}

.cta-section h3 {
    color: white;
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 15px;
}

.cta-section p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 18px;
}

.cta-section .btn {
    background: white;
    color: #1a355e;
    border: none;
    padding: 15px 40px;
    font-size: 18px;
    font-weight: 600;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.cta-section .btn:hover {
    background: #ffc430;
    color: #1a355e;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
    .instruction-step-card {
        margin-bottom: 30px;
    }
    
    .step-number {
        top: -15px;
        left: 20px;
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
}
</style>

@endsection

