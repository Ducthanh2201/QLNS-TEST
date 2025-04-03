@extends('layouts.employee')

@section('title', 'Bảng Điều Khiển')

@section('page-title', 'Bảng Điều Khiển')

@section('breadcrumb')
    <li class="breadcrumb-item active">Bảng điều khiển</li>
@endsection

@section('content')
<!-- Overview Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Ngày công tháng này</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">22/25</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Lương tạm tính (Tháng 04)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">12.500.000 VNĐ</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tỷ lệ chuyên cần
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">88%</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 88%" aria-valuenow="88" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Đánh giá hiệu suất</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Tốt</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance and Profile section -->
<div class="row">
    <div class="col-lg-5 col-md-12">
        <!-- Attendance Check-in/out Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Chấm công hôm nay</h6>
            </div>
            <div class="card-body text-center">
                <h2 class="display-4 mb-3" id="currentTime">--:--:--</h2>
                <p class="lead mb-4">{{ date('l, d F Y') }}</p>
                
                @php
                    $checkedIn = true; // Giả sử đã check-in
                    $checkInTime = '08:00';
                    $checkOutTime = null; // Chưa check-out
                @endphp
                
                <div class="row justify-content-center mb-4">
                    <div class="col-md-8">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Trạng thái hôm nay</h5>
                                @if($checkedIn)
                                    <div class="mb-3">
                                        <i class="fas fa-sign-in-alt text-success mr-2"></i>
                                        <span>Check-in: <strong>{{ $checkInTime }}</strong></span>
                                    </div>
                                    @if($checkOutTime)
                                        <div>
                                            <i class="fas fa-sign-out-alt text-info mr-2"></i>
                                            <span>Check-out: <strong>{{ $checkOutTime }}</strong></span>
                                        </div>
                                    @else
                                        <div>
                                            <i class="fas fa-sign-out-alt text-danger mr-2"></i>
                                            <span>Check-out: <strong>Chưa thực hiện</strong></span>
                                        </div>
                                    @endif
                                @else
                                    <div>
                                        <i class="fas fa-clock text-warning mr-2"></i>
                                        <span>Chưa có dữ liệu chấm công</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        @if($checkedIn && !$checkOutTime)
                            <form action="#" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-lg btn-block">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Check-out
                                </button>
                            </form>
                        @elseif(!$checkedIn)
                            <form action="#" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Check-in
                                </button>
                            </form>
                        @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle mr-2"></i>
                                Bạn đã hoàn thành chấm công hôm nay!
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4 small text-muted">
                    <p><i class="fas fa-info-circle mr-1"></i> Giờ làm việc: 08:00 - 17:00</p>
                    <p><i class="fas fa-map-marker-alt mr-1"></i> Vị trí chấm công: Văn phòng</p>
                </div>
            </div>
        </div>

        <!-- Profile Summary -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin cá nhân</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img class="img-profile rounded-circle mb-3" src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg" width="100">
                    <h5 class="mb-1">Nguyễn Văn A</h5>
                    <p class="text-muted">Kỹ sư phần mềm</p>
                    <a href="/employees/profile" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit mr-1"></i> Cập nhật thông tin
                    </a>
                </div>

                <hr>

                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Phòng ban:</strong>
                        <p class="text-muted">Phòng Kỹ thuật</p>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <strong><i class="fas fa-user-tie mr-1"></i> Chức vụ:</strong>
                        <p class="text-muted">Nhân viên</p>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <strong><i class="fas fa-envelope mr-1"></i> Email:</strong>
                        <p class="text-muted">nguyenvana@gmail.com</p>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <strong><i class="fas fa-phone-alt mr-1"></i> Số điện thoại:</strong>
                        <p class="text-muted">0123456789</p>
                    </div>
                    <div class="col-lg-12">
                        <strong><i class="fas fa-calendar-alt mr-1"></i> Ngày vào công ty:</strong>
                        <p class="text-muted">01/01/2023</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7 col-md-12">
        <!-- Monthly Attendance Record -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Lịch sử chấm công ({{ date('m/Y') }})</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="#">Xuất báo cáo PDF</a>
                        <a class="dropdown-item" href="#">Xuất báo cáo Excel</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Thời gian làm việc</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01/04/2025</td>
                                <td>07:55</td>
                                <td>17:05</td>
                                <td>9h 10p</td>
                                <td><span class="badge bg-success">Đúng giờ</span></td>
                            </tr>
                            <tr>
                                <td>02/04/2025</td>
                                <td>08:10</td>
                                <td>17:00</td>
                                <td>8h 50p</td>
                                <td><span class="badge bg-warning">Đi muộn</span></td>
                            </tr>
                            <tr>
                                <td>03/04/2025</td>
                                <td>08:00</td>
                                <td>17:00</td>
                                <td>9h 00p</td>
                                <td><span class="badge bg-success">Đúng giờ</span></td>
                            </tr>
                            <tr>
                                <td>04/04/2025</td>
                                <td>07:45</td>
                                <td>17:15</td>
                                <td>9h 30p</td>
                                <td><span class="badge bg-success">Đúng giờ</span></td>
                            </tr>
                            <tr>
                                <td>05/04/2025</td>
                                <td>--:--</td>
                                <td>--:--</td>
                                <td>--</td>
                                <td><span class="badge bg-secondary">Cuối tuần</span></td>
                            </tr>
                            <tr>
                                <td>06/04/2025</td>
                                <td>--:--</td>
                                <td>--:--</td>
                                <td>--</td>
                                <td><span class="badge bg-secondary">Cuối tuần</span></td>
                            </tr>
                            <tr>
                                <td>07/04/2025</td>
                                <td>08:00</td>
                                <td>17:00</td>
                                <td>9h 00p</td>
                                <td><span class="badge bg-success">Đúng giờ</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <nav aria-label="Attendance navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- Attendance Summary -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tóm tắt chấm công</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center">
                            <h4 class="text-success mb-0">22</h4>
                            <div class="small text-gray-800">Đúng giờ</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center">
                            <h4 class="text-warning mb-0">2</h4>
                            <div class="small text-gray-800">Đi muộn</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center">
                            <h4 class="text-info mb-0">0</h4>
                            <div class="small text-gray-800">Về sớm</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center">
                            <h4 class="text-danger mb-0">1</h4>
                            <div class="small text-gray-800">Vắng mặt</div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="progress mb-2" style="height: 15px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 88%" aria-valuenow="88" aria-valuemin="0" aria-valuemax="100">88%</div>
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 8%" aria-valuenow="8" aria-valuemin="0" aria-valuemax="100">8%</div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 4%" aria-valuenow="4" aria-valuemin="0" aria-valuemax="100">4%</div>
                    </div>
                    <div class="small text-muted text-center">
                        Tổng số ngày làm việc trong tháng: 25 ngày
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Request & Upcoming Events -->
        <div class="row">
            <div class="col-md-6">
                <!-- Leave Request Summary -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tổng quan nghỉ phép</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Số ngày phép còn lại:</strong>
                            <div class="progress mt-2" style="height: 20px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 70%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="15">
                                    10.5/15 ngày
                                </div>
                            </div>
                        </div>
                        
                        <div class="small">
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Đã sử dụng:</span>
                                <span class="text-danger">4.5 ngày</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Đang chờ duyệt:</span>
                                <span class="text-warning">2 ngày</span>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-calendar-plus mr-1"></i> Đăng ký nghỉ phép
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- Upcoming Events -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Sự kiện sắp tới</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary text-white rounded p-2 mr-3">
                                    <i class="fas fa-birthday-cake"></i>
                                </div>
                                <div>
                                    <div class="small text-gray-500">10/04/2025</div>
                                    <div class="font-weight-bold">Sinh nhật công ty</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-warning text-white rounded p-2 mr-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <div class="small text-gray-500">15/04/2025</div>
                                    <div class="font-weight-bold">Họp toàn công ty</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-0">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-success text-white rounded p-2 mr-3">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div>
                                    <div class="small text-gray-500">30/04/2025</div>
                                    <div class="font-weight-bold">Nghỉ lễ 30/4 - 01/5</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Salary & Performance -->
<div class="row">
    <div class="col-md-6">
        <!-- Salary Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin lương</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownSalaryMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownSalaryMenuLink">
                        <a class="dropdown-item" href="#">Xem chi tiết</a>
                        <a class="dropdown-item" href="#">Xuất phiếu lương</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tháng</th>
                                <th>Lương cơ bản</th>
                                <th>Thưởng</th>
                                <th>Khấu trừ</th>
                                <th>Thực lãnh</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>03/2025</td>
                                <td>12.000.000</td>
                                <td>1.500.000</td>
                                <td>1.350.000</td>
                                <td>12.150.000</td>
                                <td><span class="badge bg-success">Đã thanh toán</span></td>
                            </tr>
                            <tr>
                                <td>02/2025</td>
                                <td>12.000.000</td>
                                <td>1.000.000</td>
                                <td>1.350.000</td>
                                <td>11.650.000</td>
                                <td><span class="badge bg-success">Đã thanh toán</span></td>
                            </tr>
                            <tr>
                                <td>01/2025</td>
                                <td>12.000.000</td>
                                <td>800.000</td>
                                <td>1.350.000</td>
                                <td>11.450.000</td>
                                <td><span class="badge bg-success">Đã thanh toán</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        Xem lịch sử lương
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <!-- Performance Overview -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Hiệu suất làm việc</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">Mục tiêu cá nhân <span class="float-right">75%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <h4 class="small font-weight-bold">Mục tiêu nhóm <span class="float-right">85%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <h4 class="small font-weight-bold">Mục tiêu dự án <span class="float-right">60%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <div class="mt-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Đánh giá gần nhất</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Tốt (4.2/5)</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-star fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        Xem báo cáo đầy đủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities & Notes -->
<div class="row">
    <div class="col-md-8">
        <!-- Recent Activities -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Hoạt động gần đây</h6>
            </div>
            <div class="card-body">
                <div class="timeline timeline-xs">
                    <div class="timeline-item">
                        <div class="timeline-item-marker">
                            <div class="timeline-item-marker-text">3h</div>
                            <div class="timeline-item-marker-indicator bg-success"></div>
                        </div>
                        <div class="timeline-item-content">
                            Bạn đã check-in lúc 08:00
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-item-marker">
                            <div class="timeline-item-marker-text">1d</div>
                            <div class="timeline-item-marker-indicator bg-warning"></div>
                        </div>
                        <div class="timeline-item-content">
                            Đơn xin nghỉ phép của bạn đã được phê duyệt
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-item-marker">
                            <div class="timeline-item-marker-text">3d</div>
                            <div class="timeline-item-marker-indicator bg-info"></div>
                        </div>
                        <div class="timeline-item-content">
                            Bạn đã được giao dự án mới: Website bán hàng
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-item-marker">
                            <div class="timeline-item-marker-text">1w</div>
                            <div class="timeline-item-marker-indicator bg-purple"></div>
                        </div>
                        <div class="timeline-item-content">
                            Bạn đã được trưởng phòng đánh giá hiệu suất
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-item-marker">
                            <div class="timeline-item-marker-text">2w</div>
                            <div class="timeline-item-marker-indicator bg-danger"></div>
                        </div>
                        <div class="timeline-item-content">
                            Bạn đã hoàn thành khóa đào tạo "Kỹ năng làm việc nhóm"
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Notes & Reminders -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Ghi chú & Nhắc nhở</h6>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addNoteModal">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="note-item mb-3 border-left-warning pl-3 py-2">
                    <div class="small text-gray-500 mb-1">15/04/2025</div>
                    <div class="font-weight-bold mb-1">Chuẩn bị báo cáo dự án</div>
                    <div class="small text-muted">Hoàn thiện báo cáo trước cuộc họp toàn công ty</div>
                </div>
                
                <div class="note-item mb-3 border-left-info pl-3 py-2">
                    <div class="small text-gray-500 mb-1">20/04/2025</div>
                    <div class="font-weight-bold mb-1">Deadline dự án ABC</div>
                    <div class="small text-muted">Hoàn thành các tính năng còn lại và triển khai</div>
                </div>
                
                <div class="note-item mb-0 border-left-primary pl-3 py-2">
                    <div class="small text-gray-500 mb-1">25/04/2025</div>
                    <div class="font-weight-bold mb-1">Đánh giá hiệu suất hàng quý</div>
                    <div class="small text-muted">Chuẩn bị tài liệu và báo cáo cá nhân</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel">Thêm ghi chú mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="noteTitle">Tiêu đề</label>
                        <input type="text" class="form-control" id="noteTitle" placeholder="Nhập tiêu đề ghi chú">
                    </div>
                    <div class="form-group">
                        <label for="noteDate">Ngày</label>
                        <input type="date" class="form-control" id="noteDate">
                    </div>
                    <div class="form-group">
                        <label for="noteContent">Nội dung</label>
                        <textarea class="form-control" id="noteContent" rows="3" placeholder="Nhập nội dung ghi chú"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="noteType">Loại</label>
                        <select class="form-control" id="noteType">
                            <option value="primary">Thông thường</option>
                            <option value="warning">Quan trọng</option>
                            <option value="info">Thông tin</option>
                            <option value="danger">Khẩn cấp</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary">Lưu ghi chú</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Function to update the current time
    function updateTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;
    }
    
    // Update time every second
    setInterval(updateTime, 1000);
    
    // Initial update
    updateTime();
</script>
@endpush