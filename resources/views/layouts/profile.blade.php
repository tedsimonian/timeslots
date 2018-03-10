<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span style="margin-right: 10px;">{{Auth::user()->first_name}}</span><img src="/images/male.png" alt="user" class="profile-pic" /></a>
    <div class="dropdown-menu dropdown-menu-right scale-up">
        <ul class="dropdown-user">

            <li>
                <div class="dw-user-box">
                    <div class="u-img"><img src="/images/male.png" alt="user"></div>
                    <div class="u-text">
                        <h4>{{Auth::user()->first_name}} {{Auth::user()->last_name}}</h4>
                        <p class="text-muted">{{Auth::user()->email}}</p>
                        @can('edit user profile')<a href="profile" class="btn btn-rounded btn-danger btn-sm">View Profile</a>@endcan
                        @can('edit employee profile')<a href="profile" class="btn btn-rounded btn-danger btn-sm">View Profile</a>@endcan
                    </div>
                </div>
            </li>
            <li role="separator" class="divider"></li>
            @can('edit user profile')
            <li><a href="profile"><i class="ti-settings"></i> Account Settings</a></li>
            @endcan
            @can('edit employee profile')
                <li><a href="profile"><i class="ti-settings"></i> Account Settings</a></li>
            @endcan


            <li role="separator" class="divider"></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form></li>
        </ul>
    </div>
</li>