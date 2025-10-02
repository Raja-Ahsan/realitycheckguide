<header class="main-header">
    <a href="{{ route('dashboard') }}" class="logo">
        <img id="header-logo" src="{{ asset('admin/assets/images/page') }}/{{ $home_page_data['header_logo'] }}" style="width: 200px;position:absolute;left: 1%;top: 20%;height: 140px;" alt="">
        <!--  <span class="logo-lg" style="position:absolute;top:230%;left:3%;">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</span> -->
    </a>
    <nav class="navbar navbar-static-top">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <span style="float:left;line-height:50px;color:rgb(255, 255, 255);font-weight: 600;padding-left:15px;font-size:15px;"><span class="logo-lg">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</span></span>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav"> 

                @php
                    $notifications = Auth::user()->unreadNotifications;

                    $projectStatusNotifications = $notifications->filter(fn($n) =>
                        isset($n->data['type']) && $n->data['type'] === 'project_update'
                    );

                    $memberDirectoryNotifications = $notifications->filter(fn($n) =>
                        isset($n->data['type']) && $n->data['type'] === 'member_update'
                    );

                    $totalBadgeCount = $projectStatusNotifications->count() + $memberDirectoryNotifications->count();
                @endphp

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>
                        @if($totalBadgeCount)
                            <span class="badge badge-warning">{{ $totalBadgeCount }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-right notifications-dropdown">
                        {{-- Add CSS for scrolling --}}
                        <style>
                            .notifications-dropdown {
                                max-height: 354px; /* Adjust as needed to show approx 6 items */
                                overflow-y: auto;
                            }
                            .notification-item {
                                /* Optional: Add specific height or padding if items have variable height */
                                padding: 10px 15px; /* Example padding */
                                border-bottom: 1px solid #eee; /* Example separator */
                            }
                            .dropdown-header {
                                position: sticky;
                                top: 0;
                                background-color: #fff; /* Ensure header is visible when scrolling */
                                z-index: 1; /* Keep header above scrolling items */
                                padding: 10px 15px;
                                border-bottom: 1px solid #eee;
                            }
                        </style>

                        {{-- Project Status Updates --}}
                        @if($projectStatusNotifications->isNotEmpty())
                            <div class="dropdown-header">Project Status Updates</div>
                            @foreach($projectStatusNotifications as $notification)
                                <a href="{{ route('projects.edit', $notification->data['project_id']) }}"
                                class="dropdown-item notification-item{{ $notification->read_at ? '' : ' unread' }}">
                                    @if(!$notification->read_at)
                                        <span class="notification-dot"></span>
                                    @endif
                                    <span>{!! ($notification->data['message']) !!}</span>
                                </a>
                            @endforeach
                        @endif

                        {{-- Member Directory Updates --}}
                        @if($memberDirectoryNotifications->isNotEmpty())
                            <div class="dropdown-header">Member Directory Updates</div>
                            @foreach($memberDirectoryNotifications as $notification)
                                <a href="{{ route('member_directory.edit', $notification->data['member_id']) }}"
                                class="dropdown-item notification-item{{ $notification->read_at ? '' : ' unread' }}">
                                    @if(!$notification->read_at)
                                        <span class="notification-dot"></span>
                                    @endif
                                    <span>{!! ($notification->data['message']) !!}</span>
                                </a>
                            @endforeach
                        @endif

                        @if($totalBadgeCount === 0)
                            <span class="dropdown-item notification-item">No new notifications</span>
                        @endif
                    </div>
                </li>

            

                <li>
                    <a href="{{ url('/') }}" target="_blank">Visit Website</a>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        @if (!empty( Auth::user()->image ))
                             <img  src="{{ asset('admin/assets/images/UserImage') }}/{{  Auth::user()->image }}" style="object-fit: cover;width: 40px;height: 40px;border-radius: 50px;margin-top: -10px;margin-right: 8px;" alt="">
                        @else 
                             <i class="fa fa-user-circle" style="font-size: 20px;" aria-hidden="true"></i>
                        @endif 
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-footer">
                            <div>

                                <a href="{{ route('creator.profile.edit') }}" class="btn btn-default btn-flat" >Edit Profile</a>
                            </div>
                            <div>
                                <a class="dropdown-item btn btn-default btn-flat" href="{{ route('user.logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

    </nav>
</header><!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Custom Script -->
<script>
    $(document).ready(function() {
        $('.sidebar-toggle').click(function() {
            $('#header-logo').toggleClass('hide-logo');
        });
    });
</script>

<!-- CSS for hiding the logo -->
<style>
    .hide-logo {
        display: none;
    }

    @media (max-width: 425px) {
        #header-logo {
            display: block !important; /* Ensure logo stays visible */
        }
    }
    @media (max-width: 375px) {
        #header-logo {
            display: block !important; /* Ensure logo stays visible */
        }
    }
    @media (max-width: 320px) {
        #header-logo {
            display: block !important; /* Ensure logo stays visible */
        }
    }

    .sidebar-mini.sidebar-collapse .main-header .logo {
        width: 50px;
        display: none;
    }
</style>
