@extends('layouts.website.master')
@section('title', $page_title)
@section('content')
    <style>
        .contact-info-card {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            text-align: center;
        }

        .contact-info-card:hover {
            transform: translateY(-5px);
        }

        .contact-info-icon {
            background: #ffc430;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 20px;
        }

        .contact-form-section {
            background: #f8f9fa;
            padding: 80px 0;
        }

        .contact-form {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #ffc430;
            box-shadow: 0 0 0 0.2rem rgba(255, 196, 48, 0.25);
        }

        .btn-submit {
            background: #ffc430;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: #e6b02a;
            transform: translateY(-2px);
        }

        .map-section {
            padding: 80px 0;
            background: white;
        }

        .map-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .faq-contact {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .contact-accordion .accordion-button {
            background: #fff;
            border: none;
            padding: 20px 25px;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            box-shadow: none;
            transition: all 0.3s ease;
        }

        .contact-accordion .accordion-button:not(.collapsed) {
            background: #ffc430;
            color: white;
            box-shadow: none;
        }

        .contact-accordion .accordion-button:focus {
            box-shadow: none;
            border: none;
        }

        .contact-accordion .accordion-body {
            padding: 20px 25px;
            background: #f8f9fa;
            font-size: 15px;
            line-height: 1.6;
            color: #666;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 0px;
        }

        .social-links a {
            background: #ffc430;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: #e6b02a;
            transform: translateY(-3px);
        }
    </style>
    <!-- ======= Hero Section ======= -->
    <!-- banner  -->
    @if (!empty($banner->image))
        <section class="inner-banner creators-banner"
            style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('admin/assets/images/banner') }}/{{ $banner->image }}');">
        @else
        <section class="inner-banner creators-banner" 
            style="margin-top: 80px; height: 200px; background-size: cover; background-image: url('{{ asset('admin/assets/images/images.png') }}');">
    @endif
        <div class="banner-wrapper position-relative z-1">
            <div class="container">
                <div class="row"> 
                    <div class="col-lg-12 col-xl-12" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1500"> 
                        <h1 class="hd-70 mt-5" >Contact Us</h1>
                        <p class="hd-20 text-white">Contact Reality Check Guide</p>
                    </div>
                </div>
            </div>
        </div>
    </section> 





    <!-- ***** Contact Info Section Start ***** -->
    <section class="contact-info" id="contact-info">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 featured-video py-0">
                    <div class="section-heading text-center">
                        <h2>Get In <span>Touch</span></h2>
                        <p class="mb-5">We're here to help you make informed career decisions. Reach out to us anytime!</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <h5>Email Us</h5>
                        <p><a href="mailto:{{ $home_page_data['contact_email'] }}">{{ $home_page_data['contact_email'] }}</a></p>
                        <!-- <p>support@realitycheckguide.com</p> -->
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <h5>Call Us</h5>
                        <p> <a href="tel:{{ $home_page_data['contact_phone'] }}">{{ $home_page_data['contact_phone'] }}</a></p>
                        <!-- <p>Mon-Fri: 9AM-6PM EST</p> -->
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <h5>Visit Us</h5>
                        <p>{{ $home_page_data['contact_address'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Contact Info Section End ***** -->

    <!-- ***** Contact Form Section Start ***** -->
    <section class="contact-form-section" id="contact-form">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 featured-video py-0">
                    <div class="section-heading text-center">
                        <h2>Send Us a <span>Message</span></h2>
                        <p class="mb-5">Have questions about career guidance? We'd love to hear from you!</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="map-container h-100">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.215573012345!2d-74.00594168459418!3d40.7127753!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a0b4b3b3b3b%3A0x1234567890abcdef!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus"
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
                <div class="col-lg-6">
                @if(session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
                    <div class="contact-form">
                        <form action="{{ route('contactus.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Your Name" required>
                                </div>
                                <div class="col-lg-6">
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Your Email" required>
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        placeholder="Phone number" required>
                                </div>
                                <div class="col-lg-12">
                                    <textarea name="message" id="message" class="form-control" rows="6"
                                        placeholder="Your Message" required></textarea>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- ***** Contact Form Section End ***** -->
    <!-- ***** Social Media Section Start ***** -->
    <section class="social-media" id="social-media">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 featured-video py-0">
                    <div class="section-heading text-center">
                        <h2>Follow Us <span>Online</span></h2>
                        <p class="mb-5">Stay connected with us on social media for career tips and updates</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="social-links">
                        <a href="{{ $home_page_data['footer_facebok'] }}"><i class="fa fa-facebook"></i></a>
                        <a href="{{ $home_page_data['footer_twiter'] }}"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="{{ $home_page_data['footer_linkdin'] }}"><i class="fa fa-linkedin"></i></a>
                        <a href="#"><i class="fa fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Social Media Section End ***** -->

@endsection
