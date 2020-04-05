<!-- ============================================================== -->
<!-- User Profile -->
<!-- ============================================================== -->
<li class="nav-item dropdown u-pro">
    <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{Auth::user()->urlfoto}}" alt="user" class=""> <span class="hidden-md-down">{{Auth::user()->nama}} &nbsp;<i class="fa fa-angle-down"></i></span> </a>
    <div class="dropdown-menu dropdown-menu-right animated flipInY">
        <!-- text-->
        <a href="javascript:void(0)" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
        <!-- text-->
        <a href="javascript:void(0)" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a>
        <!-- text-->
        <a href="javascript:void(0)" class="dropdown-item"><i class="ti-email"></i> Inbox</a>
        <!-- text-->
        <div class="dropdown-divider"></div>
        <!-- text-->
        <a href="javascript:void(0)" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a>
        <!-- text-->
        <div class="dropdown-divider"></div>
        <!-- text-->
        <a href="{{route('logout')}}" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
        <!-- text-->
    </div>
</li>
<!-- ============================================================== -->
<!-- End User Profile -->
<!-- ============================================================== -->