@if (Route::currentRouteName() !== 'sign-up' && Route::currentRouteName() !== 'login' && Route::currentRouteName() !== 'registration' && Route::currentRouteName() !== 'jobpost.details' && Route::currentRouteName() !== 'careers')
    {{-- Default footer for all routes except epc-developer-sign-up --}}
    <section class="form-sec position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @if (Session::has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <form action="{{ route('contactus.store') }}" id="contactForm" class="form-horizontal contact-form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        @csrf
                        <div class="form-wrapper mb-80 position-relative">
                            <div class="shape-1"></div>
                            <div class="form-content">
                                <h5 class="hd-24 text-white text-center">Get in Touch</h5>
                                <h4 class="hd-55 text-white text-uppercase text-center mb-20">Send us a Message</h4>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="field-wrap">
                                            <input type="text" class="input-field" name="name" id="name" placeholder="Name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="field-wrap">
                                            <input type="text" class="input-field" name="email" id="email" placeholder="Email" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="field-wrap">
                                            <input type="text" class="input-field" name="phone" id="phone" placeholder="Phone">
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-12 col-md-6">
                                        <div class="field-wrap"> 
                                            <textarea class="input-field" name="address" rows="1" id="address" placeholder="Enter your address" required></textarea>
                                        </div>
                                    </div> --}}
                                    <div class="col-lg-12">
                                        <div class="field-wrap">
                                            <textarea class="input-field" name="message" rows="4" id="message" placeholder="Enter your message"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary mx-auto d-flex justify-content-center text-capitalize" type="submit" id="submitBtn">Send Now</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer position-relative">
        <div class="container">
            <div class="row row-gap-30 justify-content-center">
                <div class="col-lg-4" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">
                    <div class="footer-logo radius-10 mb-10" style="max-width: 22rem;">
                        <img src="{{ asset('admin/assets/images/page') }}/{{ $home_page_data['footer_image'] }}"
                            class="footer-logo" alt="footer-logo">
                    </div>
                    <div class="fs-18 text-white fw-500 footer-des">
                        {!! $home_page_data['footer_description'] !!}
                    </div>
                </div>
                <div class="col-lg-3" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">
                    <h4 class="hd-24 mb-15 fw-bold text-white">Useful Links</h4>
                    <ul class="footer-links pl-20">
                        <li class="nav-item"><a class="navs {{ Request::routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Home</a></li>
                        <li class="nav-item"><a class="navs {{ Request::routeIs('about-us') ? 'active' : '' }}" href="#">About Us</a></li>
                        <li class="nav-item"><a class="navs {{ Request::routeIs('contact-us') ? 'active' : '' }}" href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-3" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">
                    <h4 class="hd-24 mb-15 fw-bold text-white">Contact Info</h4>
                    <a href="tel:{{ $home_page_data['footer_number'] }}" class="d-flex align-items-center gap-20">
                        <span><i class="fa-solid fa-phone"></i></span>
                        <span>{{ $home_page_data['footer_number'] }}</span>
                    </a>
                    <a href="mailto:{{ $home_page_data['footer_email'] }}" class="d-flex align-items-center gap-20">
                        <span><i class="fa-solid fa-envelope"></i></span>
                        <span>{{ $home_page_data['footer_email'] }}</span>
                    </a>
                    <a href="javascript:;" class="d-flex gap-20">
                        <span><i class="fa-solid fa-location-dot"></i></span>
                        <span>{{ $home_page_data['footer_address'] }}</span>
                    </a>
                </div>
                <div class="col-lg-2" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">
                    <h4 class="hd-24 mb-15 fw-bold text-start text-lg-center text-white">Social Networks</h4>
                    <ul
                        class="social-links justify-content-start justify-content-lg-center d-flex align-items-center gap-20">
                        <li><a href="{{ $home_page_data['footer_facebok'] }}"><i
                                    class="fa-brands fa-facebook-f"></i></a></li>
                        <li><a href="{{ $home_page_data['footer_twiter'] }}"><i
                                    class="fa-brands fa-x-twitter"></i></a></li>
                        <li><a href="{{ $home_page_data['footer_linkdin'] }}"><i
                                    class="fa-brands fa-linkedin-in"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom mt-30 mt-lg-0 py-20">
            <div class="container d-flex align-items-center justify-content-between">
                <p class="text-white fs-16 fw-500 text-center">{!! $home_page_data['footer_copy_right_left_side'] !!}</p>
                <p class="text-white fs-16 fw-500 text-center">{!! $home_page_data['footer_copy_right_right_side'] !!}</p>
            </div>
        </div>
        <a class="scroll-to-top" href="javascript:;">
            <div class="top-to-bottom bottom-to-top">
                <i class="fa-solid fa-arrow-up"></i>
            </div>
        </a>
    </footer>
@else
    {{-- Special footer for epc-developer-sign-up route only --}}
    <footer class="footer position-relative pt-100">
        <div class="container">
            <div class="row row-gap-30 justify-content-center">
                <div class="col-lg-4">
                    <div class="footer-logo radius-10 mb-10" style="max-width: 22rem;">
                        <img src="{{ asset('admin/assets/images/page') }}/{{ $home_page_data['footer_image'] }}"
                            class="footer-logo" alt="footer-logo">
                    </div>
                    <div class="fs-18 text-white fw-500 footer-des">
                        {!! $home_page_data['footer_description'] !!}
                    </div>
                </div>
                <div class="col-lg-3">
                    <h4 class="hd-24 mb-15 fw-bold text-white">Useful Links</h4>
                    <ul class="footer-links pl-20">
                        <li class="nav-item"><a class="navs {{ Request::routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Home</a></li>
                        <li class="nav-item"><a class="navs {{ Request::routeIs('about-us') ? 'active' : '' }}" href="">About Us</a></li>
                        <li class="nav-item"><a class="navs {{ Request::routeIs('contact-us') ? 'active' : '' }}" href="">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h4 class="hd-24 mb-15 fw-bold text-white">Contact Info</h4>
                    <a href="tel:{{ $home_page_data['footer_number'] }}" class="d-flex align-items-center gap-20">
                        <span><i class="fa-solid fa-phone"></i></span>
                        <span>{{ $home_page_data['footer_number'] }}</span>
                    </a>
                    <a href="mailto:{{ $home_page_data['footer_email'] }}" class="d-flex align-items-center gap-20">
                        <span><i class="fa-solid fa-envelope"></i></span>
                        <span>{{ $home_page_data['footer_email'] }}</span>
                    </a>
                    <a href="javascript:;" class="d-flex gap-20">
                        <span><i class="fa-solid fa-location-dot"></i></span>
                        <span>{{ $home_page_data['footer_address'] }}</span>
                    </a>
                </div>
                <div class="col-lg-2">
                    <h4 class="hd-24 mb-15 fw-bold text-start text-lg-center text-white">Social Networks</h4>
                    <ul
                        class="social-links justify-content-start justify-content-lg-center d-flex align-items-center gap-20">
                        <li><a href="{{ $home_page_data['footer_facebok'] }}"><i
                                    class="fa-brands fa-facebook-f"></i></a></li>
                        <li><a href="{{ $home_page_data['footer_twiter'] }}"><i
                                    class="fa-brands fa-x-twitter"></i></a></li>
                        <li><a href="{{ $home_page_data['footer_linkdin'] }}"><i
                                    class="fa-brands fa-linkedin-in"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom mt-30 mt-lg-0 py-20">
            <div class="container d-flex align-items-center justify-content-between">
                <p class="text-white fs-16 fw-500 text-center">{!! $home_page_data['footer_copy_right_left_side'] !!}</p>
                <p class="text-white fs-16 fw-500 text-center">{!! $home_page_data['footer_copy_right_right_side'] !!}</p>
            </div>
        </div>
        <a class="scroll-to-top" href="javascript:;">
            <div class="top-to-bottom bottom-to-top">
                <i class="fa-solid fa-arrow-up"></i>
            </div>
        </a>
    </footer>
@endif