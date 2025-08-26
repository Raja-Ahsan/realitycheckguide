<aside class="main-sidebar" style="margin-top: 120px;">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="treeview">
                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') || request()->is('profile/*') ? 'active' : '' }}">
                    <i class="fa fa-tachometer" aria-hidden="true"></i> <span>Dashboard</span>
                </a>
            </li> 
            <li class="treeview {{ ( request()->is('jobpost') || request()->is('jobpost/create') || request()->is('jobpost/*/edit')) ? 'active' : '' }}" style="height: auto;">
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
            </li>
        </ul>
    </section>
</aside>
