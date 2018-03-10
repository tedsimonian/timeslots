<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
        <div class="user-profile">
            <!-- User profile image -->
            <div class="profile-img"> <img src="/images/male.png" alt="user">

            </div>
            <!-- User profile text-->
            <div class="profile-text">
                <h5>{{Auth::user()->first_name}} {{Auth::user()->last_name}}</h5>
                @can('edit user profile')
                <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="mdi mdi-settings"></i></a>
                @endcan
                @can('edit employee profile')
                    <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="mdi mdi-settings"></i></a>
                @endcan
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="mdi mdi-power"></i></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>

                <div class="dropdown-menu animated flipInY">
                    <!-- text-->

                    <a href="profile" class="dropdown-item"><i class="ti-user"></i> My Profile</a>

                    <!-- text-->

                    <!-- text-->

                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="mdi mdi-power"></i> Logout</a>
                    <!-- text-->
                </div>
            </div>
        </div>
        <!-- End User profile text-->
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-devider"></li>
                @if(Auth()->user()->hasRole('admin'))

                    <li> <a class="waves-effect waves-dark" href="/admin/home" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Home</span></a>

                    </li>
                    <li> <a class="waves-effect waves-dark" href="/admin/acl" aria-expanded="false"><i class="mdi mdi-account-key"></i><span class="hide-menu">ACL</span></a>

                    </li>
                    <li> <a class="waves-effect waves-dark" href="/admin/users" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">Users</span></a>

                    </li>
                    <li> <a class="waves-effect waves-dark" href="/admin/events" aria-expanded="false"><i class="mdi mdi-calendar-multiple-check"></i><span class="hide-menu">Events</span></a>

                    </li>
                    <li> <a class="waves-effect waves-dark" href="/admin/transactions" aria-expanded="false"><i class="mdi mdi-cart-outline"></i><span class="hide-menu">Transactions</span></a>

                    </li>
                @endif

                @if(Auth()->user()->hasRole('employee'))

                    <li> <a class="waves-effect waves-dark" href="/employee/home" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Home</span></a>

                    </li>

                    @can('edit rules')
                        <li> <a class="waves-effect waves-dark" href="/employee/settings" aria-expanded="false"><i class="mdi mdi-calendar-clock"></i><span class="hide-menu">Business Rules</span></a>

                        </li>
                    @endcan

                    @can('view employee transactions')
                        <li> <a class="waves-effect waves-dark" href="/employee/transactions" aria-expanded="false"><i class="mdi mdi-cart-outline"></i><span class="hide-menu">Transactions</span></a>

                        </li>
                    @endcan

                    @can('view employee events')

                        <li> <a class="waves-effect waves-dark" href="/employee/events" aria-expanded="false"><i class="mdi mdi-calendar-multiple-check"></i><span class="hide-menu">Events</span></a>

                        </li>
                    @endcan


                @endif

                @if(Auth()->user()->hasRole('user'))

                    <li> <a class="waves-effect waves-dark" href="/user/home" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Home</span></a></li>

                    @can('book appointment')
                        <li> <a class="waves-effect waves-dark" href="/user/book-appointment" aria-expanded="false"><i class="mdi mdi-calendar-plus"></i><span class="hide-menu">Book Appointment</span></a>

                        </li>
                    @endcan

                    @can('view user transactions')
                        <li> <a class="waves-effect waves-dark" href="/user/transactions" aria-expanded="false"><i class="mdi mdi-cart-outline"></i><span class="hide-menu">Transactions</span></a>

                        </li>
                    @endcan



                @endif

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

