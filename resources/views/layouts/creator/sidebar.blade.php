<style>
    .treeview-menu {
    display: none;
}

.treeview.active .treeview-menu {
    display: block;
}
</style>
<aside class="main-sidebar" style="margin-top: 120px;">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="treeview mt-2">
                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') || request()->is('profile/*') ? 'active' : '' }}">
                    <i class="fa fa-tachometer" aria-hidden="true"></i> <span>Dashboard</span>
                </a>
            </li>

            <!-- Video Platform Management -->
            <li class="treeview {{ (request()->is('creator/videos*') || request()->is('creator/analytics*') || request()->is('creator/earnings*') || request()->is('creator/wallet*') || request()->is('creator/pricing*')) ? 'active' : '' }}" style="height: auto;">
                <a href="#" class="{{ (request()->is('creator/videos*') || request()->is('creator/analytics*') || request()->is('creator/earnings*') || request()->is('creator/wallet*') || request()->is('creator/pricing*')) ? 'active' : '' }}">
                    <i class="fa fa-video-camera"></i>
                    <span>Video Platform</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: {{ (request()->is('creator/videos*') || request()->is('creator/analytics*') || request()->is('creator/earnings*') || request()->is('creator/wallet*') || request()->is('creator/pricing*')) ? 'block' : 'none' }};">
                    <li class="treeview mt-2">
                        <a href="{{ route('creator.videos.create') }}" class="{{ request()->is('creator/videos/create') ? 'active' : '' }}">
                            <i class="fa fa-upload"></i> <span>Upload Video</span>
                        </a>
                    </li>
                    <li class="treeview mt-2">
                        <a href="{{ route('creator.videos.index') }}" class="{{ request()->is('creator/my-videos*') ? 'active' : '' }}">
                            <i class="fas fa-list"></i> <span>My Videos</span>
                        </a>
                    </li>
                    <li class="treeview mt-2">
                        <a href="{{ route('creator.analytics') }}" class="{{ request()->is('creator/analytics*') ? 'active' : '' }}">
                            <i class="fa fa-chart-bar"></i> <span>Analytics</span>
                        </a>
                    </li>
                    <li class="treeview mt-2">
                        <a href="{{ route('creator.earnings') }}" class="{{ request()->is('creator/earnings*') ? 'active' : '' }}">
                            <i class="fa fa-dollar-sign"></i> <span>Earnings</span>
                        </a>
                    </li>
                    <li class="treeview mt-2">
                        <a href="{{ route('creator.wallet.dashboard') }}" class="{{ request()->is('creator/wallet/dashboard*') ? 'active' : '' }}">
                            <i class="fa fa-wallet"></i> <span>Wallet</span>
                        </a>
                    </li>
                    <li class="treeview mt-2">
                        <a href="{{ route('creator.wallet.payouts') }}" class="{{ request()->is('creator/wallet/payouts*') ? 'active' : '' }}">
                            <i class="fa fa-money-bill-wave"></i> <span>Payouts</span>
                        </a>
                    </li>
                    <li class="treeview mt-2">
                        <a href="{{ route('creator.pricing-rules') }}" class="{{ request()->is('creator/pricing-rules*') ? 'active' : '' }}">
                            <i class="fa fa-cog"></i> <span>Pricing Rules</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- <li class="treeview {{ ( request()->is('electrician/jobs') || request()->is('electrician/jobs/*')) ? 'active' : '' }}" style="height: auto;">
                <a href="#" class="{{ ( request()->is('electrician/jobs') || request()->is('electrician/jobs/*')) ? 'active' : '' }}">
                    <i class="fa fa-files-o"></i>
                    <span>Job Post Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: {{ ( request()->is('electrician/jobs') || request()->is('electrician/jobs/*')) ? 'block' : 'none' }};">
                
                    <li class="treeview mt-2 {{ ( request()->is('electrician/jobs') || request()->is('electrician/jobs/*')) ? 'active' : '' }}" style="height: auto;">
                        <a href="#" class="{{ ( request()->is('electrician/jobs') || request()->is('electrician/jobs/*')) ? 'active' : '' }}">
                            <i class="fa fa-files-o"></i>
                            <span>Job Post</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
        
                        <ul class="treeview-menu" style="display: {{ (request()->is('electrician/jobs') || request()->is('electrician/jobs/*')) ? 'block' : 'none' }};">
                           
                            @can('projects-list')
                            <li class="treeview mt-2">
                                <a href="{{ route('electrician.jobs') }}" class="{{ request()->is('electrician/jobs') || request()->is('electrician/jobs/*') ? 'active' : '' }}">
                                    <i class="fa fa-sitemap"></i> <span>Available Jobs</span>
                                </a>
                            </li>
                            @endcan

                            
                        </ul>
                    </li>

                   {{--  <li class="treeview mt-2 {{ (request()->is('services') || request()->is('services/create') || request()->is('services/*/edit') || request()->is('member_directory') || request()->is('member_directory/create') || request()->is('member_directory/*/edit')) ? 'active' : '' }}" style="height: auto;">
                        <a href="#" class="{{ (request()->is('services') || request()->is('services/create') || request()->is('services/*/edit') || request()->is('member_directory') || request()->is('member_directory/create') || request()->is('member_directory/*/edit')) ? 'active' : '' }}">
                            <i class="fa fa-files-o"></i>
                            <span>Member Directory</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
        
                        <ul class="treeview-menu" style="display: {{ (request()->is('member_directory') || request()->is('member_directory/create') || request()->is('services') || request()->is('services/create') || request()->is('services/*/edit')) ? 'block' : 'none' }};">
                        
                            @can('services-list')
                            <li class="treeview mt-2" >
                                <a href="{{ route('services.index') }}" class="{{ request()->is('services') || request()->is('services/create') || request()->is('services/*/edit') ? 'active' : '' }}">
                                    <i class="fa fa-code-fork"></i> <span>All Services</span>
                                </a>
                            </li>
                            @endcan
                           
                            @can('member_directory-list')
                            <li class="treeview mt-2">
                                <a href="{{ route('member_directory.index') }}" class="{{ request()->is('member_directory') || request()->is('member_directory/create') || request()->is('member_directory/*/edit') ? 'active' : '' }}">
                                    <i class="fa fa-sitemap"></i> <span>Add Member</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li> --}}
                    
                </ul>
            </li> -->
           {{--  @can('client_contact-list')
                <li class="treeview"> 
                    <a href="{{ route('client_contact.index') }}" class="{{ request()->is('client_contact') || request()->is('client_contact/create') || request()->is('client_contact/*/edit') ? 'active' : '' }}">
                        <i class="fa fa-envelope"></i> <span>Contact Me</span>
                    </a>
                </li>
            @endcan  --}}
    </section>
</aside>
<script>
    $(document).ready(function() {
    // Toggle only the direct child submenus (i.e., prevent inner submenus from opening)
    $('.treeview > a').click(function(e) {
        var parent = $(this).parent('.treeview');
        
        // Toggle only the first level submenu
        var submenu = parent.find('.treeview-menu').first();
        
        // Check if the submenu is already active, if not, slide it down; if yes, slide it up
        submenu.stop(true, true).slideToggle();
        
        // Toggle 'active' class on parent
        parent.toggleClass('active');

        // Prevent event bubbling so inner submenus don't get toggled
        e.stopPropagation();
    });
});

</script>