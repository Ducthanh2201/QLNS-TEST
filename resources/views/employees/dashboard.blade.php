@extends('layouts_employees.employee')

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
                            Ngày làm việc trong tháng</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">22/30</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
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
                            Lương tháng này</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">15.000.000 VNĐ</div>
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
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ngày phép còn lại
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">10/12</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: 83%" aria-valuenow="10" aria-valuemin="0"
                                        aria-valuemax="12"></div>
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
                            Thông báo mới</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-bell fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance and Profile section -->
<div class="row">
    <div class="col-lg-5 col-md-12">
        <!-- Attendance Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Chấm công hôm nay</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="attendanceDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="attendanceDropdown">
                        <a class="dropdown-item" href="{{ route('employee.attendance') }}">Xem lịch sử chấm công</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="current-time h2 mb-0" id="currentTime">00:00:00</div>
                    <div class="text-muted">{{ date('d/m/Y') }}</div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <form action="{{ route('employee.check-in') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-sign-in-alt mr-2"></i> Check-in
                            </button>
                        </form>
                    </div>
                    <div class="col-6">
                        <form action="{{ route('employee.check-out') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-sign-out-alt mr-2"></i> Check-out
                            </button>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <div class="row">
                        <div class="col-6 border-right">
                            <div class="text-muted small">Check-in</div>
                            <div class="font-weight-bold">08:30 AM</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Check-out</div>
                            <div class="font-weight-bold">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7 col-md-12">
        <!-- Profile Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin cá nhân</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        @php
                            $employee = Auth::guard('employee')->user();
                            $avatar = $employee->HinhAnh ? asset('storage/employees/'.$employee->HinhAnh) : asset('img/default-avatar.png');
                        @endphp
                        <img src="{{ $avatar }}" class="img-profile rounded-circle img-thumbnail mb-2" width="150">
                        <div class="font-weight-bold">{{ $employee->TenNV }}</div>
                        <div class="text-muted">Mã NV: {{ $employee->MaNV }}</div>
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-2">
                            <div class="col-sm-4 font-weight-bold">Chức vụ:</div>
                            <div class="col-sm-8">{{ $employee->ChucVu->TenCV ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 font-weight-bold">Email:</div>
                            <div class="col-sm-8">{{ $employee->email }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 font-weight-bold">Điện thoại:</div>
                            <div class="col-sm-8">{{ $employee->DienThoai }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 font-weight-bold">Ngày sinh:</div>
                            <div class="col-sm-8">{{ date('d/m/Y', strtotime($employee->NgaySinh)) }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 font-weight-bold">Địa chỉ:</div>
                            <div class="col-sm-8">{{ $employee->DiaChi }}</div>
                        </div>
                        <div class="text-right mt-3">
                            <a href="{{ route('employee.profile') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit fa-sm"></i> Xem & Chỉnh sửa
                            </a>
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
        <!-- Salary Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Lương & thưởng</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="salaryDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="salaryDropdown">
                        <a class="dropdown-item" href="{{ route('employee.salary') }}">Xem chi tiết lương</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="salaryChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Lương cơ bản
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Thưởng
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Phụ cấp
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <!-- Performance Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Chỉ số công việc</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">Hiệu suất <span class="float-right">80%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Đúng giờ <span class="float-right">95%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Hoàn thành công việc <span class="float-right">90%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Phản hồi của đồng nghiệp <span class="float-right">85%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Khen thưởng <span class="float-right">3 lần</span></h4>
                <div class="progress">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities & Notes -->
<div class="row">
    <div class="col-md-8">
        <!-- Recent Activities Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Hoạt động gần đây</h6>
            </div>
            <div class="card-body">
                <div class="timeline timeline-xs">
                    <div class="timeline-item">
                        <div class="timeline-item-marker">
                            <div class="timeline-item-marker-text">1h</div>
                            <div class="timeline-item-marker-indicator bg-green"></div>
                        </div>
                        <div class="timeline-item-content">
                            Đã check-in <span class="fw-bold text-success">08:30</span>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-item-marker">
                            <div class="timeline-item-marker-text">2d</div>
                            <div class="timeline-item-marker-indicator bg-yellow"></div>
                        </div>
                        <div class="timeline-item-content">
                            Đã nộp báo cáo tháng 4/2025
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-item-marker">
                            <div class="timeline-item-marker-text">1w</div>
                            <div class="timeline-item-marker-indicator bg-purple"></div>
                        </div>
                        <div class="timeline-item-content">
                            Đã nhận khen thưởng từ trưởng phòng
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-item-marker">
                            <div class="timeline-item-marker-text">2w</div>
                            <div class="timeline-item-marker-indicator bg-blue"></div>
                        </div>
                        <div class="timeline-item-content">
                            Đã hoàn thành dự án ABC
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Notes Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Ghi chú</h6>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addNoteModal">
                    <i class="fas fa-plus fa-sm"></i> Thêm
                </button>
            </div>
            <div class="card-body">
                <div class="note-item mb-3 p-2 border-left border-primary">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>Họp phòng ban</strong>
                        <small class="text-muted">1 ngày trước</small>
                    </div>
                    <p class="mb-0">Họp phòng ban lúc 14:00 tại phòng họp lớn</p>
                </div>
                <div class="note-item mb-3 p-2 border-left border-warning">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>Deadline dự án</strong>
                        <small class="text-muted">3 ngày trước</small>
                    </div>
                    <p class="mb-0">Hoàn thành báo cáo dự án trước 20/04</p>
                </div>
                <div class="note-item mb-3 p-2 border-left border-success">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>Mục tiêu tháng</strong>
                        <small class="text-muted">1 tuần trước</small>
                    </div>
                    <p class="mb-0">Hoàn thành 3 mục tiêu đã đề ra cho tháng 4</p>
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
                        <label for="noteContent">Nội dung</label>
                        <textarea class="form-control" id="noteContent" rows="3" placeholder="Nhập nội dung ghi chú"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="noteColor">Màu sắc</label>
                        <select class="form-control" id="noteColor">
                            <option value="primary">Xanh dương</option>
                            <option value="success">Xanh lá</option>
                            <option value="warning">Vàng</option>
                            <option value="danger">Đỏ</option>
                            <option value="info">Xanh nước biển</option>
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
// Hiển thị đồng hồ thời gian thực
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;
    setTimeout(updateClock, 1000);
}
updateClock();

// Biểu đồ lương
const ctx = document.getElementById('salaryChart').getContext('2d');
const salaryChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6'],
        datasets: [{
            label: 'Lương cơ bản',
            backgroundColor: 'rgba(78, 115, 223, 0.8)',
            data: [12000000, 12000000, 12000000, 12000000, 12000000, 12000000],
            stack: 'Stack 0',
        }, {
            label: 'Thưởng',
            backgroundColor: 'rgba(40, 167, 69, 0.8)',
            data: [1000000, 1500000, 2000000, 1000000, 2500000, 3000000],
            stack: 'Stack 0',
        }, {
            label: 'Phụ cấp',
            backgroundColor: 'rgba(23, 162, 184, 0.8)',
            data: [800000, 800000, 800000, 800000, 800000, 800000],
            stack: 'Stack 0',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', maximumFractionDigits: 0 }).format(value);
                    }
                }
            }
        }
    }
});
</script>
@endpush