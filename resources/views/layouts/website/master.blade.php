<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="TemplateMo">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('public/admin/assets/images/page') }}/{{ $home_page_data['header_favicon'] }}" type="image/png" sizes="32x32">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('public/assets/website') }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('public/assets/website') }}/css/fontawesome.css">
    <link rel="stylesheet" href="{{ asset('public/assets/website') }}/css/templatemo-edu-meeting.css">
    <link rel="stylesheet" href="{{ asset('public/assets/website') }}/css/owl.css">
    <link rel="stylesheet" href="{{ asset('public/assets/website') }}/css/lightbox.css">
    @stack('styles')
</head>

<body>

    <!-- if check rout id index then show sub header -->
    @if (Route::is('index'))
        <!-- Sub Header -->
    <div class="sub-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="left-content text-center">
                        <p>Real People. Real Jobs. Real Advice</p>
                    </div>
                </div>
                <!-- <div class="col-lg-4 col-sm-4">
          <div class="right-icons">
            <ul>
              <li><a href="#"><i class="fa fa-facebook"></i></a></li>
              <li><a href="#"><i class="fa fa-twitter"></i></a></li>
              <li><a href="#"><i class="fa fa-behance"></i></a></li>
              <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
            </ul>
          </div>
        </div> -->
            </div>
        </div>
    </div>
    @else
    <style>
        .header-area {
            background-color: #212121B2;
            position: absolute;
            top: 0px !important;
        }
        .header-area .logo img {
            width: 120px;
        }
    </style>
    @endif
    

    @include('layouts.website.header')

    @yield('content')

    @include('layouts.website.footer')

    <!-- Scripts -->
    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('public/assets/website') }}/vendor/jquery/jquery.min.js"></script>
    <script src="{{ asset('public/assets/website') }}/vendor/bootstrap/js/bootstrap.min.js"></script>

    <script src="{{ asset('public/assets/website') }}/js/isotope.min.js"></script>
    <script src="{{ asset('public/assets/website') }}/js/owl-carousel.js"></script>
    <script src="{{ asset('public/assets/website') }}/js/lightbox.js"></script>
    <script src="{{ asset('public/assets/website') }}/js/tabs.js"></script>
    <script src="{{ asset('public/assets/website') }}/js/video.js"></script>
    <script src="{{ asset('public/assets/website') }}/js/slick-slider.js"></script>
    <script src="{{ asset('public/assets/website') }}/js/custom.js"></script>
    @stack('scripts')
    <script>
        var checkSection = function checkSection() {
            $('.section').each(function() {
                var
                    $this = $(this),
                    topEdge = $this.offset().top - 80,
                    bottomEdge = topEdge + $this.height(),
                    wScroll = $(window).scrollTop();
                if (topEdge < wScroll && bottomEdge > wScroll) {
                    var
                        currentId = $this.data('section'),
                        reqLink = $('a').filter('[href*=\\#' + currentId + ']');
                    reqLink.closest('li').addClass('active').
                    siblings().removeClass('active');
                }
            });
        };

        $(window).scroll(function() {
            checkSection();
        });
    </script>
</body>

</html>