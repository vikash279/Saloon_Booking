<!--sidebar start-->
<style>
    .shead li{
        padding-right:12%;
        color:white;
    }
</style>
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
           <div class="col-md-12" style="margin:39% auto 8% 0;">
                <div class="col-md-4">
                    <img src="<?php echo base_url('assets/global/images/2.png');?>" class="img-responsive">
                </div>
                <div class="col-md-8">
                    <h5 style="color:white;padding:4% 0 0">Admin</h5></br>
                    <!-- p style="color:white">Panel</p></br> -->
                    <!--<ul class="nav top-menu shead">-->
                    <!--    <li> <i class="fa fa-user"></i></li>-->
                    <!--    <li> <i class="fa fa-pencil"></i></li>-->
                    <!--    <li> <i class="fa fa-dashboard"></i></li>-->
                    <!--</ul>-->
                </div>
            </div>
            
            <ul class="sidebar-menu" id="nav-accordion">
                 
                <li>
                <h4 style="color:white;padding-left:7%;padding-bottom:3%">GENERAL</h4>
                </li>
                 <li>
                    <a class="active" href="<?php echo base_url('Welcome/dashboard'); ?>">
                        <i><img src="<?php echo base_url('assets/dashboard.png');?>" width="16" height="18"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                 <li>
                    <a class="active" href="<?php echo base_url('Welcome/category'); ?>">
                        <i class="fa fa-desktop" aria-hidden="true"></i>
                        <span>Category</span>
                    </a>
                </li>
                  <li>
                    <a class="active" href="<?php echo base_url('Welcome/services'); ?>">
                        <i class="fa fa-dashboard"></i>
                        <span>Services</span>
                    </a>
                </li> 
                <!--<li>-->
                <!--    <a class="active" href="#">-->
                <!--        <i class="fa fa-dashboard"></i>-->
                <!--        <span>Master Shop</span>-->
                <!--    </a>-->
                <!--</li> -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-folder-open-o"></i>
                        <span>Master Shop</span>
                    </a>
                    <ul class="sub">
						<li><a href="<?php echo base_url('Welcome/shopBookings'); ?>">Appointments/booking</a></li>
						<li><a href="<?php echo base_url('Welcome/shopOrders'); ?>">Orders</a></li>
                        <!--<li><a href="grids">Payments</a></li>-->
                    </ul>
                </li>
                <!-- <li>-->
                <!--    <a class="active" href="#">-->
                <!--        <i class="fa fa-folder-open-o" aria-hidden="true"></i>-->
                <!--        <span>Clients</span>-->
                <!--    </a>-->
                <!--</li>-->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>Clients</span>
                    </a>
                    <ul class="sub">
						<li><a href="<?php echo base_url('Welcome/clientDetails'); ?>">Details</a></li>
					<!--	<li><a href="#">Appointments</a></li>-->
                        <li><a href="<?php echo base_url('Welcome/clientOrders'); ?>">Orders</a></li>
                    </ul>
                </li>
               
                <!-- <li>-->
                <!--    <a class="active" href="#">-->
                <!--        <i><img src="<?php //echo base_url('assets/push.png');?>" width="12" height="18"></i>-->
                <!--        <span>Option</span>-->
                <!--    </a>-->
                <!--</li>-->
                <!-- <li>-->
                <!--    <a class="active" href="#">-->
                <!--        <i class="fa fa-signal" aria-hidden="true"></i>-->
                <!--        <span>Option</span>-->
                <!--    </a>-->
                <!--</li>-->
                
               <!--- <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>UI Elements</span>
                    </a>
                    <ul class="sub">
						<li><a href="typography">Typography</a></li>
						<li><a href="glyphicon">glyphicon</a></li>
                        <li><a href="grids">Grids</a></li>
                    </ul>
                </li>
                <li>
                    <a href="fontawesome">
                        <i class="fa fa-bullhorn"></i>
                        <span>Font awesome </span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Data Tables</span>
                    </a>
                    <ul class="sub">
                        <li><a href="basic_table">Basic Table</a></li>
                        <li><a href="responsive_table">Responsive Table</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-tasks"></i>
                        <span>Form Components</span>
                    </a>
                    <ul class="sub">
                        <li><a href="form_component">Form Elements</a></li>
                        <li><a href="form_validation">Form Validation</a></li>
						<li><a href="dropzone">Dropzone</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-envelope"></i>
                        <span>Mail </span>
                    </a>
                    <ul class="sub">
                        <li><a href="mail">Inbox</a></li>
                        <li><a href="mail_compose">Compose Mail</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class=" fa fa-bar-chart-o"></i>
                        <span>Charts</span>
                    </a>
                    <ul class="sub">
                        <li><a href="chartjs">Chart js</a></li>
                        <li><a href="flot_chart">Flot Charts</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class=" fa fa-bar-chart-o"></i>
                        <span>Maps</span>
                    </a>
                    <ul class="sub">
                        <li><a href="google_map">Google Map</a></li>
                        <li><a href="vector_map">Vector Map</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-glass"></i>
                        <span>Extra</span>
                    </a>
                    <ul class="sub">
                        <li><a href="gallery">Gallery</a></li>
						<li><a href="404">404 Error</a></li>
                        <li><a href="registration">Registration</a></li>
                    </ul>
                </li>-->
                <li>
                    <a href="<?php echo base_url('Welcome/logout'); ?>">
                        <i class="fa fa-user"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>            </div>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->