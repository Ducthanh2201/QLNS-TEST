<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('employee.dashboard') }}" class="brand-link">
        <img src="{{ asset('img/AdminLTELogo.png') }}" alt="QLNS Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">QLNS Portal</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @php
                    $employee = Auth::guard('employee')->user();
                    $avatar = $employee->HinhAnh ? asset('storage/employees/'.$employee->HinhAnh) : asset('img/default-avatar.png');
                @endphp
                <img src="{{ $avatar }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('employee.profile') }}" class="d-block">{{ $employee->TenNV }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->is('employee/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Bảng điều khiển</p>
                    </a>
                </li>
                
                <!-- Thông tin cá nhân -->
                <li class="nav-item">
                    <a href="{{ route('employee.profile') }}" class="nav-link {{ request()->is('employee/profile') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Thông tin cá nhân</p>
                    </a>
                </li>

                <!-- Chấm công -->
                <li class="nav-item">
                    <a href="{{ route('employee.attendance') }}" class="nav-link {{ request()->is('employee/attendance') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Chấm công</p>
                    </a>
                </li>

                <!-- Đơn xin nghỉ phép -->
                <li class="nav-item">
                    <a href="{{ route('employee.leave-request') }}" class="nav-link {{ request()->is('employee/leave-request') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-minus"></i>
                        <p>Đơn xin nghỉ phép</p>
                    </a>
                </li>

                <!-- Lương & Phúc lợi -->
                <li class="nav-item">
                    <a href="{{ route('employee.salary') }}" class="nav-link {{ request()->is('employee/salary') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Lương & Phúc lợi</p>
                    </a>
                </li>

                <!-- Đăng xuất -->
                <li class="nav-item">
                    <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Đăng xuất</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>