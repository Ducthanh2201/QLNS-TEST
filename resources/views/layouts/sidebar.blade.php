<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">QLNS Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Admin</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Bảng điều khiển</p>
                    </a>
                </li>
                
                <!-- Quản lý nhân sự - Chỉnh sửa đường dẫn view -->
                <li class="nav-item {{ request()->is('admin/employee*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/employee*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Quản lý nhân sự
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.employees.index') }}" class="nav-link {{ request()->is('admin/employees') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách nhân viên</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.employees.create') }}" class="nav-link {{ request()->is('admin/employees/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm nhân viên</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Quản lý chức vụ -->
                <li class="nav-item">
                    <a href="{{ route('admin.positions.index') }}" class="nav-link {{ request()->is('admin/positions') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-sitemap"></i>
                        <p>Quản lý chức vụ</p>
                    </a>
                </li>

                <!-- Quản lý giờ làm -->
                <li class="nav-item">
                    <a href="{{ route('admin.worktime.index') }}" class="nav-link {{ request()->is('admin/worktime') ? 'active' : '' }}">
                        <i class="nav-icon far fa-clock"></i>
                        <p>Quản lý giờ làm</p>
                    </a>
                </li>

                <!-- Quản lý chấm công -->
                <li class="nav-item">
                    <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ request()->is('admin/attendance') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Quản lý chấm công</p>
                    </a>
                </li>

                <!-- Quản lý lương -->
                <li class="nav-item">
                    <a href="{{ route('admin.salary.index') }}" class="nav-link {{ request()->is('admin/salary') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>Quản lý lương</p>
                    </a>
                </li>

                <!-- Khen thưởng/kỷ luật -->
                <li class="nav-item">
                    <a href="{{ route('admin.reward-discipline.index') }}" class="nav-link {{ request()->is('admin/reward-discipline') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-trophy"></i>
                        <p>Khen thưởng/kỷ luật</p>
                    </a>
                </li>

                <!-- Thống kê -->
                <li class="nav-item">
                    <a href="{{ route('admin.statistics.index') }}" class="nav-link {{ request()->is('admin/statistics') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Thống kê</p>
                    </a>
                </li>

                <!-- Cài đặt hệ thống -->
                <li class="nav-item {{ request()->is('admin/settings*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Cài đặt hệ thống
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thông tin công ty</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cấu hình hệ thống</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Đăng xuất -->
                <li class="nav-item">
                    <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Đăng xuất</p>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>