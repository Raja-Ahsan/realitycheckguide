<!--==============================
 Header Area
    ==============================-->
<header class="header" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1000">
    <div class="container">
        <div class="header-container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="">
                    <div class="logo">
                        <a href="{{ route('login') }}">
                            <img src="{{ asset('public/admin/assets/images/page') }}/{{ $home_page_data['header_logo'] }}" alt="logo">
                        </a>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="primary-navs">
                        <div class="primary-navs-inner">
                            <ul class="primary-navs-list d-flex align-items-center justify-content-between">
                                <div class="close-icon">
                                    <i class="fa-solid fa-xmark menu-toggle"></i>
                                </div>
                                <div class="close-icon">
                                    <i class="fa-solid fa-xmark menu-toggle"></i>
                                </div>
                                <li class="nav-item">
                                    <a class="navs {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="navs {{ request()->is('about-us') ? 'active' : '' }}" href="#">About Us</a>
                                </li>
                               
                                @if (!Auth::check())
                                  <li class="nav-item">
                                      <a class="navs {{ request()->is('registration') ? 'active' : '' }}" href="{{ route('registration') }}">Registration</a>
                                  </li> 
                                @endif
                                 
                                <li class="nav-item">
                                    <a class="navs {{ request()->is('contact-us') ? 'active' : '' }}" href="#">Contact Us</a>
                                </li>
                                @if (!Auth::check())
                                    <li class="nav-item">
                                        <a class="navs {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <img src="{{ asset('assets/website') }}/images/login.png" alt="login" style="width:100px; height:auto;">
                                            </div>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::check())
                                    <li class="nav-item">
                                        <a class="navs {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('home') }}">Dashboard</a>
                                    </li>
                                @endif
                                 
                            </ul>
                        </div>
                    </div>
                </div>
                 
            </div>
        </div>
    </div>
</header>
