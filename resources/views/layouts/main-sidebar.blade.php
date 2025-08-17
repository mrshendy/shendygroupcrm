        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('assets/images/logo.png') }}" alt=""
                            style="height: 70px;width: 100% !important;">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('assets/images/logo.png') }}" alt="" height="17"
                            style="height: 70px;width:100% !important;">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span>إدارة النظام</span></li>

                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                                href="{{ route('clients.index') }}">
                                <i class="mdi mdi-account-group-outline"></i> العملاء
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ request()->routeIs('projects.*') ? 'active' : '' }}"
                                href="{{ route('projects.index') }}">
                                <i class="mdi mdi-briefcase-outline"></i> المشاريع
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ request()->routeIs('offers.*') ? 'active' : '' }}"
                                href="{{ route('offers.index') }}">
                                <i class="mdi mdi-tag-multiple-outline"></i> العروض
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ request()->routeIs('contracts.*') ? 'active' : '' }}"
                                href="{{ route('contracts.index') }}">
                                <i class="mdi mdi-briefcase-outline"></i> التعاقدات
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ request()->routeIs('finance.*') ? 'active' : '' }}"
                                href="{{ route('finance.index') }}">
                                <i class="mdi mdi-cash-multiple"></i> الحسابات المالية
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ request()->routeIs('attendance.*') ? 'active' : '' }}"
                                href="{{ route('attendance.check') }}">
                                <i class="mdi mdi-clock-outline"></i> تسجيل حضور وانصراف
                            </a>

                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ request()->routeIs('attendance.*') ? 'active' : '' }}"
                                href="{{ route('leaves.create') }}">
                                <i class="mdi mdi-beach me-3 fs-5"></i>تقديم على اجازه
                            </a>

                        </li>


                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                                href="{{ route('employees.index') }}">
                                <i class="mdi mdi-account-tie-outline"></i> موظفي الشركة
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'active' : '' }}"
                                href="{{ route('users.index') }}">
                                <i class="mdi mdi-shield-account-outline"></i> إدارة المستخدمين
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
                                href="{{ route('notifications.index') }}">
                                <i class="mdi mdi-bell-outline"></i> الإشعارات
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                                href="{{ route('settings.index') }}">
                                <i class="mdi mdi-cog-outline"></i> الإعدادات
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link font" href="#">
                                <i class="mdi mdi-help-circle-outline"></i> المساعدة
                            </a>
                        </li>

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>
