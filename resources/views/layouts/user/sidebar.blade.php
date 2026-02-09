<aside class="main-sidebar" style="margin-top: 120px;">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="treeview">
                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') || request()->is('profile/*') ? 'active' : '' }}">
                    <i class="fa fa-tachometer" aria-hidden="true"></i> <span>Dashboard</span>
                </a>
            </li> 
            
            <!-- Video Platform Navigation -->
            <li class="treeview {{ ( request()->is('videos*') || request()->is('my-orders*') || request()->is('my-video-purchases*') || request()->is('my-downloads*')) ? 'active' : '' }}" style="height: auto;">
                <a href="#" class="{{ ( request()->is('videos*') || request()->is('my-orders*') || request()->is('my-video-purchases*') || request()->is('my-downloads*')) ? 'active' : '' }}">
                    <i class="fa fa-video-camera"></i>
                    <span>Video Platform</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: {{ ( request()->is('videos*') || request()->is('my-orders*') || request()->is('my-video-purchases*') || request()->is('my-downloads*')) ? 'block' : 'none' }};">
                
                    <li class="treeview mt-2">
                        <a href="{{ route('videos.index') }}" class="{{ request()->is('videos*') && !request()->is('my-orders*') && !request()->is('my-video-purchases*') && !request()->is('my-downloads*') ? 'active' : '' }}">
                            <i class="fa fa-search"></i> <span>Browse Videos</span>
                        </a>
                    </li> 
        
                    <li class="treeview mt-2">
                        <a href="{{ route('viewer.orders.index') }}" class="{{ request()->is('my-orders*') ? 'active' : '' }}">
                            <i class="fa fa-shopping-cart"></i> <span>My Orders</span>
                        </a>
                    </li> 
                    
                    <li class="treeview mt-2">
                        <a href="{{ route('viewer.video-purchases.index') }}" class="{{ request()->is('my-video-purchases*') ? 'active' : '' }}">
                            <i class="fa fa-credit-card"></i> <span>My Purchases</span>
                        </a>
                    </li> 
                    
                    <li class="treeview mt-2">
                        <a href="{{ route('viewer.downloads.index') }}" class="{{ request()->is('my-downloads*') ? 'active' : '' }}">
                            <i class="fa fa-download"></i> <span>My Downloads</span>
                        </a>
                    </li> 
                </ul>
            </li>
            
            <!--<li class="treeview {{ ( request()->is('jobpost') || request()->is('jobpost/create') || request()->is('jobpost/*/edit')) ? 'active' : '' }}" style="height: auto;">
                <a href="#" class="{{ ( request()->is('jobpost') || request()->is('jobpost/create') || request()->is('jobpost/*/edit')) ? 'active' : '' }}">
                    <i class="fa fa-files-o"></i>
                    <span>Job Post</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: {{ ( request()->is('jobpost') || request()->is('jobpost/create') || request()->is('jobpost/*/edit')) ? 'block' : 'none' }};">
                
                    <li class="treeview mt-2">
                        <a href="{{ route('jobpost.index') }}" class="{{ request()->is('jobpost') || request()->is('jobpost/*/edit') ? 'active' : '' }}">
                            <i class="fa fa-handshake-o"></i> <span>Browse Project Jobs</span>
                        </a>
                    </li> 
        
                    <li class="treeview mt-2">
                        <a href="{{ route('jobpost.create') }}" class="{{ request()->is('jobpost/create') ? 'active' : '' }}">
                            <i class="fa fa-handshake-o"></i> <span>Post a Job</span>
                        </a>
                    </li> 
                </ul>
            </li>-->
        </ul>
    </section>
</aside>
