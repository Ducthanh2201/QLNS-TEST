@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Tổng quan hệ thống')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tổng nhân viên</span>
                <span class="info-box-number">152</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Đi làm hôm nay</span>
                <span class="info-box-number">145 <small>(95.4%)</small></span>
            </div>
        </div>
    </div>

    <!-- fix for small devices only -->
    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-business-time"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Phòng ban</span>
                <span class="info-box-number">8</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Vắng mặt hôm nay</span>
                <span class="info-box-number">7 <small>(4.6%)</small></span>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->

<div class="row">
    <div class="col-md-8">
        <!-- Attendance Chart -->
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">Thống kê chấm công trong tháng</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-wrench"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                <a href="#" class="dropdown-item">Xuất PDF</a>
                                <a href="#" class="dropdown-item">Xuất Excel</a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">Cài đặt</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="attendanceChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <!-- /.card -->

        <!-- Recent Attendance Table -->
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Chấm công gần đây</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-tool btn-sm">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Nhân viên</th>
                            <th>Phòng ban</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <img src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-circle img-size-32 mr-2">
                                Nguyễn Văn A
                            </td>
                            <td>Kỹ thuật</td>
                            <td>07:55</td>
                            <td>17:05</td>
                            <td><span class="badge bg-success">Đúng giờ</span></td>
                        </tr>
                        <tr>
                            <td>
                                <img src="https://adminlte.io/themes/v3/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-circle img-size-32 mr-2">
                                Trần Thị B
                            </td>
                            <td>Kinh doanh</td>
                            <td>08:10</td>
                            <td>17:00</td>
                            <td><span class="badge bg-warning">Đi muộn</span></td>
                        </tr>
                        <tr>
                            <td>
                                <img src="https://adminlte.io/themes/v3/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-circle img-size-32 mr-2">
                                Lê Văn C
                            </td>
                            <td>Nhân sự</td>
                            <td>08:00</td>
                            <td>17:00</td>
                            <td><span class="badge bg-success">Đúng giờ</span></td>
                        </tr>
                        <tr>
                            <td>
                                <img src="https://adminlte.io/themes/v3/dist/img/user4-128x128.jpg" alt="User Avatar" class="img-circle img-size-32 mr-2">
                                Phạm Thị D
                            </td>
                            <td>Marketing</td>
                            <td>07:45</td>
                            <td>17:15</td>
                            <td><span class="badge bg-success">Đúng giờ</span></td>
                        </tr>
                        <tr>
                            <td>
                                <img src="https://adminlte.io/themes/v3/dist/img/user5-128x128.jpg" alt="User Avatar" class="img-circle img-size-32 mr-2">
                                Hoàng Văn E
                            </td>
                            <td>Tài chính</td>
                            <td>08:30</td>
                            <td>16:45</td>
                            <td><span class="badge bg-danger">Bất thường</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <div class="col-md-4">
        <!-- Department Statistics -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Phân bố nhân sự theo phòng ban</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-responsive">
                    <canvas id="departmentPieChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <!-- /.card -->

        <!-- Recent Activities -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hoạt động gần đây</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                    <li class="item">
                        <div class="product-img">
                            <i class="fas fa-user-plus fa-2x text-primary"></i>
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">Nhân viên mới
                                <span class="badge badge-success float-right">Hôm nay</span>
                            </a>
                            <span class="product-description">
                                Đã thêm nhân viên Trần Văn F vào phòng Kỹ thuật
                            </span>
                        </div>
                    </li>

                    <li class="item">
                        <div class="product-img">
                            <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">Lương tháng
                                <span class="badge badge-info float-right">Hôm qua</span>
                            </a>
                            <span class="product-description">
                                Đã hoàn thành tính lương tháng 03/2025
                            </span>
                        </div>
                    </li>

                    <li class="item">
                        <div class="product-img">
                            <i class="fas fa-award fa-2x text-warning"></i>
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">Khen thưởng
                                <span class="badge badge-warning float-right">3 ngày trước</span>
                            </a>
                            <span class="product-description">
                                Khen thưởng nhân viên xuất sắc quý I/2025
                            </span>
                        </div>
                    </li>

                    <li class="item">
                        <div class="product-img">
                            <i class="fas fa-calendar-alt fa-2x text-danger"></i>
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">Lịch họp
                                <span class="badge badge-danger float-right">5 ngày trước</span>
                            </a>
                            <span class="product-description">
                                Đã lên lịch họp tổng kết quý I cho các trưởng phòng
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="javascript:void(0)" class="uppercase">Xem tất cả hoạt động</a>
            </div>
        </div>
        <!-- /.card -->

        <!-- Calendar Mini -->
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Lịch</h3>
            </div>
            <div class="card-body pt-0">
                <div id="calendar" style="width: 100%"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Attendance Chart
        var attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        var attendanceChart = new Chart(attendanceCtx, {
            type: 'line',
            data: {
                labels: ['01/04', '02/04', '03/04', '04/04', '05/04', '06/04', '07/04', '08/04', '09/04', '10/04', '11/04', '12/04', '13/04', '14/04'],
                datasets: [
                    {
                        label: 'Đúng giờ',
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        borderColor: 'rgba(40, 167, 69, 0.8)',
                        pointBackgroundColor: '#28a745',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#28a745',
                        data: [142, 138, 140, 145, 90, 85, 143, 144, 139, 141, 140, 92, 88, 145]
                    },
                    {
                        label: 'Đi muộn',
                        backgroundColor: 'rgba(255, 193, 7, 0.2)',
                        borderColor: 'rgba(255, 193, 7, 0.8)',
                        pointBackgroundColor: '#ffc107',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#ffc107',
                        data: [8, 10, 9, 5, 2, 0, 7, 6, 10, 8, 9, 3, 0, 5]
                    },
                    {
                        label: 'Vắng mặt',
                        backgroundColor: 'rgba(220, 53, 69, 0.2)',
                        borderColor: 'rgba(220, 53, 69, 0.8)',
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#dc3545',
                        data: [2, 4, 3, 2, 60, 67, 2, 2, 3, 3, 3, 57, 64, 2]
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Department Pie Chart
        var departmentCtx = document.getElementById('departmentPieChart').getContext('2d');
        var departmentChart = new Chart(departmentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Kỹ thuật', 'Kinh doanh', 'Nhân sự', 'Marketing', 'Tài chính', 'Hành chính', 'IT', 'Pháp lý'],
                datasets: [{
                    data: [42, 30, 12, 18, 15, 10, 20, 5],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d', '#6f42c1', '#fd7e14'],
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Calendar
        $('#calendar').datetimepicker({
            format: 'L',
            inline: true
        });
    });
</script>
@endpush