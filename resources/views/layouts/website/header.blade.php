<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <!-- ***** Logo Start ***** -->
            <a href="{{ route('index') }}" class="logo">
              <img src="{{ asset('public/assets/website') }}/img/logo.png" alt="logo" class="logo-img">
            </a>
            <!-- ***** Logo End ***** -->
            <!-- ***** Menu Start ***** -->
            <ul class="nav">
              <li class="scroll-to-section"><a href="{{ route('index') }}" class="active">Home</a></li>
              <li><a href="{{ route('about-us') }}">About</a></li>
              <li class="scroll-to-section"><a href="#">instructions for easy upload</a></li>
              <!-- <li class="has-sub">
                              <a href="javascript:void(0)">intake</a>
                              <ul class="sub-menu">
                                  <li><a href="meetings.html">Upcoming Meetings</a></li>
                                  <li><a href="meeting-details.html">Meeting Details</a></li>
                              </ul>
                          </li> -->
              <li class="scroll-to-section"><a href="#courses">intake</a></li>
              <li class="scroll-to-section"><a href="{{ route('contact-us') }}">Contact</a></li>
              <li class="scroll-to-section"><a href="{{ route('creators.index') }}">Creators</a></li>
              <li class="scroll-to-section"><a href="{{ route('login') }}">Login</a></li>
              <li class="scroll-to-section signup"><a href="{{ route('sign-up') }}">Signup</a></li>
            </ul>
            <a class='menu-trigger'>
              <span>Menu</span>
            </a>
            <!-- ***** Menu End ***** -->
          </nav>
        </div>
      </div>
    </div>
  </header>
  <!-- ***** Header Area End ***** -->