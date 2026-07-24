@extends('layouts.website.master')
@section('title', $page_title)
@section('content')

  <!-- ***** Main Banner Area Start ***** -->
  <section class="section main-banner" id="top" data-section="section1">
    <video autoplay muted loop id="bg-video">
      <source src="{{ asset('assets/website') }}/img/banner-video.mp4" type="video/mp4" />
    </video>

    <div class="video-overlay header-text">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="caption">
              <h2>See the Job <span>Before</span> You Choose It.</span></h2>
              <p>Explore honest, user-made videos about careers that matter.</p>
              <div class="main-button-red">
                <div class="scroll-to-section"><a href="{{ route('creators.index') }}"><i class="fa fa-play-circle-o"></i> Watch Real Stories </a>
                </div>
                <div class="scroll-to-section"><a href="#contact">Upload Your Own <img src="{{ asset('assets/website') }}/img/file-upload.svg"
                      alt="upload" class="upload-icon"></a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** Main Banner Area End ***** -->

  
  <!-- ***** HOw It Work ***** -->
  <section class="how-it-work" id="how-it-work">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading">
            <h2>Instructions for  <span class="">Easy  Upload</span></h2>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-4">
              <div class="how-it-work-item">
                
                  <div class="how-it-work-item-image-number position-relative">
                    <div class="how-it-work-item-img1">
                      <img src="{{ asset('assets/website') }}/img/icon1.svg" alt="how-it-work-1" class="img-fluid">
                    </div>
                    <span class="how-it-work-item-image-number-text position-absolute d-block">01</span>
                  </div>
                
                <div class="how-it-work-item-text">
                  <h4>Watch Real Stories</h4>
                  <p>Watch other successful people’s careers in free 1-minute videos to start your journey.</p>
                  <div class="how-it-work-item-button">
                    <a href="#">Learn More</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="how-it-work-item">
                <div class="how-it-work-item-image-number position-relative">
                  <div class="how-it-work-item-img">
                    <img src="{{ asset('assets/website') }}/img/icon2.svg" alt="how-it-work-1" class="img-fluid">
                  </div>
                  <span class="how-it-work-item-image-number-text position-absolute d-block">02</span>
                </div>
                <div class="how-it-work-item-text">
                  <h4>Discover Your Fit</h4>
                  <p>Take career quizzes and assessments to see what suits your interests, personality, and lifestyle.Find guidance before you commit to a path.</p>
                  <div class="how-it-work-item-button">
                    <a href="#">Learn More</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="how-it-work-item">
                <div class="how-it-work-item-image-number position-relative">
                  <div class="how-it-work-item-img">
                    <img src="{{ asset('assets/website') }}/img/icon3.svg" alt="how-it-work-1" class="img-fluid">
                  </div>
                  <span class="how-it-work-item-image-number-text position-absolute d-block">03</span>
                </div>
                <div class="how-it-work-item-text">
                  <h4>Share Your Experience</h4>
                  <p>Share your career success with others and start with a 1-minute video to introduce yourself. Upload your videos below.</p>
                  <div class="how-it-work-item-button">
                    <a href="#">Learn More</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** HOw It Work ***** -->
  
  <!-- ***** Browse Career Categories ***** -->
  <section class="browse-career-categories" id="browse-career-categories">
    <div class="container-fluid p-0">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading">
            <h2>Browse Career <span>Categories</span></h2>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="owl-category-item owl-carousel">
            @forelse($categories as $category)
            <div class="item">
              <a href="{{ route('category.detail', $category->slug) }}">
                @if($category->image)
                  <img src="{{ asset('admin/assets/images/categories/'.$category->image) }}" alt="{{ $category->title }}">
                @else
                  <img src="{{ asset('assets/website') }}/img/cat{{ ($loop->index % 6) + 1 }}.png" alt="{{ $category->title }}">
                @endif
                <div class="down-content">
                  <h4>{{ $category->title }}</h4>
                  <p>{{ $category->subtitle }}</p>
                </div>
              </a>
            </div>
            @empty
            <!-- Fallback to default categories if none exist -->
            @endforelse
          </div>
          <div class="browse-career-categories-button">
            <a href="{{ route('categories') }}">Browse All Categories</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** Browse Career Categories ***** -->
  <!-- ***** Discover Your Strengths ***** -->
  <section class="discover-your-strengths" id="discover-your-strengths">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 col-md-5">
          <div class="section-heading">
            <h2>Discover Your <span>Strengths</span></h2>
          </div>
          <p>Not sure which career is right for you? Use our free tools and assessments to discover what matches your skills, passions, and goals. Whether you're just starting or ready to switch paths, these tools will guide your way.</p>
            <div class="discover-your-strengths-button">
              <!--<a href="#">Discover Careers</a>-->
              <!--<a href="{{ route('quiz.index') }}">Start a Quiz</a>-->
              <a href="https://www.careerfitter.com/free_test/careerbuilder/test?afid=2802" target="_blank">Start a Quiz</a>
            </div>
            
        </div>
        <div class="col-lg-3 col-md-3">
          <div class="bottom-text">Find Your Fit</div>
        </div>
        <div class="col-lg-4">
          <img src="{{ asset('assets/website') }}/img/discover-logo.png" alt="discover-your-strengths" class="img-fluid discover-your-strengths-img">
        </div>
      </div>
    </div>
  </section>
  <!-- ***** Discover Your Strengths ***** -->  
  <!-- ***** Upload Your Story ***** -->
  <section class="upload-your-story" id="upload-your-story">
    <div class="container-fluid p-0">
      <div class="row">
        <div class="col-lg-12">
          <form action="#" method="POST" enctype="multipart/form-data">
            <div class="row">
              <!-- Upload Box -->
              <div class="col-lg-2">
                <div class="section-heading">
                  <h2>Inspire Others</h2>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="upload-your-story-item-box">
                  <div class="upload-your-story-item text-center">
                    <img src="{{ asset('assets/website') }}/img/upload-file.svg" alt="upload-your-story-1" class="img-fluid">
                    <span class="mt-2 mb-0">Upload here</span>
                  </div>
                  <div class="upload-your-story-sm text-center">
                    <p>“Video / Audio / Written” format</p>
                    <p class="text-secondary mb-2">or</p>
                    <label for="story-file" class="btn btn-file">
                      Browse File
                    </label>
                    <input type="file" name="story_file" id="story-file" class="d-none" accept=".mp4,.mp3,.wav,.pdf,.doc,.docx,.txt" required>
                  </div>
                </div>
                <div class="upload-your-story-item-box-checkbox mt-2">
                  <input type="checkbox" name="title" id="title" placeholder="Title" required>
                  <label for="title">I agree to share this story publicly on Reality Check Guide</label>
                </div>
              </div>
  
              <!-- Story Details -->
              <div class="col-lg-6">
                <div class="upload-your-story-heading">
                  <h3>Upload Your <span>Story</span></h3>
                  <p>
                    Everyone’s career path is different. Whether you faced challenges, changed directions, or followed a dream — your experience is valuable. Upload your story and help others see what’s possible. Inspire the next generation of thinkers, builders, creators, and leaders.
                  </p>
                  <div class="upload-your-story-button">
                    <button type="submit" class="btn">Submit Your Journey</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** Upload Your Story ***** -->  
   <!-- ***** Featured Video ***** -->
  <!-- <section class="featured-video" id="featured-video">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading">
            <h2>Featured Video of the <span>Week</span></h2>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="featured-video-item position-relative">
            <video src="{{ asset('assets/website') }}/img/intro.mp4" autoplay muted loop class="img-fluid"></video>
            <div class="featured-video-item-text position-absolute">
              <h4>Planning Your Path: The <span>Power of Focus</span></h4>
              <p>Watch how clarity and dedication can lay the groundwork for any career—whether it’s digital, trade-based, or creative. Sometimes the first step is just showing up and doing the work.</p>
              <div class="featured-video-item-button">
                <a href="#">view full video</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section> -->
  <section class="featured-video" id="featured-video">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading">
            <h2>Featured Video of the <span>Week</span></h2>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="featured-video-item position-relative">
            <!-- <video src="{{ asset('assets/website') }}/img/intro.mp4" autoplay muted loop class="img-fluid"></video> -->
            <iframe width="100%" height="500" src="https://www.youtube.com/embed/sTHk9e0XLgI?si=Fk44zQvtFpuXeho8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            <!-- <div class="featured-video-item-text position-absolute">
              <h4>Planning Your Path: The <span>Power of Focus</span></h4>
              <p>Watch how clarity and dedication can lay the groundwork for any career—whether it's digital, trade-based, or creative. Sometimes the first step is just showing up and doing the work.</p>
              <div class="featured-video-item-button">
                <a href="#">view full video</a>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** Featured Video ***** --> 
  <!-- ***** Education & Resources ***** -->
  <section class="education-resources" id="education-resources">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading">
            <h2>Education & <span>Resources</span></h2>
            <p>Find the tools and knowledge to shape your future.</p>
          </div>
          <div class="row">
            <div class="col-lg-4">
              <div class="education-resources-item">
                <img src="{{ asset('assets/website') }}/img/education-resources-1.png" alt="education-resources-1" class="img-fluid">
                <h4>Education & Resources</h4>
                <ul>
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"> GED / High School Equivalency</li>  
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"> Community Colleges</li>
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"> Trade Schools</li>
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"> Online Courses & Certifications</li>
                </ul>
                <div class="education-resources-item-button">
                  <a href="#">Find a Path</a>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="education-resources-item">
                <img src="{{ asset('assets/website') }}/img/education-resources-2.png" alt="education-resources-1" class="img-fluid">
                <h4>Career Essentials</h4>
                <ul>
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"> Resume Builder</li>  
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"> Interview Prep</li>
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"> Soft Skills</li>
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"> Work Readiness Checklists</li>
                </ul>
                <div class="education-resources-item-button">
                  <a href="#">Get Job Ready</a>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="education-resources-item">
                <img src="{{ asset('assets/website') }}/img/education-resources-3.png" alt="education-resources-1" class="img-fluid">
                <h4>Paying for School</h4>
                <ul>
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"><a href="https://usafundingapplications.org/v9/front-page-060325/" target="_blank"> Scholarships & Grants</a></li>  
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"> FAFSA Guide</li>
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"> Help for Adult Learners</li>
                  <li><img src="{{ asset('assets/website') }}/img/check.svg" alt="check" class="img-fluid"> Financial Aid</li>
                 </ul>
                <div class="education-resources-item-button">
                  <a href="#">Explore Financial Options.</a>
                </div>
              </div>
            </div>
            <div class="col-lg-12 mt-4">
              <div class="education-resources-item">
                <div class="education-resources-item-button text-center">
                  <a href="https://www.fastweb.com/" target="_blank" class="mt-0">Explore a wide range of scholarships and grants designed to support students in achieving their academic goals.</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** Education & Resources ***** -->
   <!-- ***** Contact Us ***** -->
   <section class="contact-us" id="contact-us">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="contact-us-item position-relative">
            <div class="contact-img-wrapper">
              <img src="{{ asset('assets/website') }}/img/logo.png" alt="contact-img" class="img-fluid position-absolute">
            </div>
            <ul>
              <li><a href=""><i class="fa fa-phone"></i> <span>Phone</span> (123) 456-7890</a></li>
              <li><i class="fa fa-envelope"></i> <span>Email</span> info@meeting.edu</li>
            </ul>
            <div class="contact-us-social">
              <label class="mb-3">Follow Us On:</label>
              <ul class="d-flex gap-3" style="max-width: 100px;">
                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
              </ul>
            </div>
            <div class="contact-us-text">
              <p class="text-one text-white">We aim to respond</p>
              <p class="text-two">Within 24–48 hours</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="section-heading">
            <h2>Contact <span>Us</span></h2>
          </div>
          <div class="contact-us-form">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 20px; padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 10px; top: 10px; background: none; border: none; font-size: 20px; cursor: pointer;">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif
            @if($errors->any())
              <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 20px; padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
                <strong>Error!</strong> Please correct the following errors:
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 10px; top: 10px; background: none; border: none; font-size: 20px; cursor: pointer;">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif
            <form action="{{ route('contact.form.submit') }}" method="POST">
              @csrf
              <div class="row">
                <div class="col-lg-12">
                  <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ old('name') }}" required>
                </div>
                <div class="col-lg-12">
                  <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                </div>
                <div class="col-lg-12">
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone" value="{{ old('phone') }}" required>
                </div>
                <div class="col-lg-12">
                  <textarea name="message" id="message" class="form-control" rows="6" placeholder="Message" required>{{ old('message') }}</textarea>
                </div>
                <div class="col-lg-12">
                  <button type="submit" class="btn">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
   </section>

  <!-- ***** About us ***** -->
  <section class="about-us" id="about-us">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="about-us-img-overlay position-relative">
            <!-- <img src="{{ asset('assets/website') }}/img/about-us.png" alt="about-us" class="about-us-img"> -->   
              <img src="{{ asset('assets/website') }}/img/logo.png" alt="about-us-img-overlay"
                class="about-us-img-overlay-img position-absolute">
              <video src="{{ asset('assets/website') }}/img/about.mov" autoplay controls loop width="100%" height="100%" class="about-us-img-overlay-video"></video>
          
          </div>
          <div class="about-us-img-overlay-text">
            <p>After seeing so many people unhappy in their careers, I wanted to create a place where we could learn
              from each other’s real stories. This isn’t just a site it’s a movement toward clarity, purpose, and truth.
            </p>
          </div>
          <div class="about-us-img-overlay-text-bottom">
            <p>Jeanmarie, Founder of Reality Check Guide</p>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="section-heading">
            <h4>About</h4>
            <h2>Reality <span>Check Guide</span></h2>
          </div>
          <p>Reality Check Guide is a video-based platform created to help people explore careers through honest,
            real-life experiences. Whether you're a student, a career changer, or simply curious, our mission is to show
            you what jobs are really like — before you commit time, money, or energy into the wrong path.
          </p>
          <p>
            Instead of polished resumes or job descriptions, you’ll find authentic stories from people who live the job
            every day. We believe guidance should come from experience, not just theory.
          </p>
          <p>
            By watching or sharing a video, you're helping others make smarter, more informed career choices. One real
            story can save someone years of regret — or inspire a dream they didn’t know they had.</p>
          <!--<div class="main-button-yellow">
            <a href="#">Start Exploring Careers</a>
            <a href="#">Share Your Story</a>
          </div>-->
          <div class="bottom-text">
            <p class="bottom-text-bold">Real People.</p>
            <p class="bottom-text-bold text-right d-color">Real Careers. </p>
            <p class="bottom-text-bold d-color">Real Insight.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** About us ***** -->
  


 
@endsection