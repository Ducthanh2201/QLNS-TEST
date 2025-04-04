<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('employee.dashboard') }}" class="nav-link">Trang chủ</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Quick Check-in/Check-out Buttons -->
        <li class="nav-item d-none d-sm-inline-block">
            <div class="btn-group mr-2">
                <form action="{{ route('employee.check-in') }}" method="POST" class="mr-1">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-sign-in-alt"></i> Check-in
                    </button>
                </form>
                <form action="{{ route('employee.check-out') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fas fa-sign-out-alt"></i> Check-out
                    </button>
                </form>
            </div>
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">3 Thông báo</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> Đơn nghỉ phép đã được duyệt
                    <span class="float-right text-muted text-sm">3 phút</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> Lương tháng 4 đã được cập nhật
                    <span class="float-right text-muted text-sm">2 ngày</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-bullhorn mr-2"></i> Thông báo cuộc họp mới
                    <span class="float-right text-muted text-sm">2 ngày</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">Xem tất cả thông báo</a>
            </div>
        </li>

        <!-- User Menu -->
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                @php
                    $employee = Auth::guard('employee')->user();
                    $avatar = $employee->HinhAnh ? asset('storage/employees/'.$employee->HinhAnh) : asset('img/default-avatar.png');
                @endphp
                <img src="{{ $avatar }}" class="user-image img-circle elevation-2" alt="User Image">
                <span class="d-none d-md-inline">{{ $employee->TenNV }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    <img src="{{ $avatar }}" class="img-circle elevation-2" alt="User Image">
                    <p>
                        {{ $employee->TenNV }}
                        <small>Mã NV: {{ $employee->MaNV }}</small>
                    </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="{{ route('employee.profile') }}" class="btn btn-default btn-flat">Hồ sơ</a>
                    <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-default btn-flat float-right">
                        Đăng xuất
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>