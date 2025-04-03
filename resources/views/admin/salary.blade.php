@extends('layouts.admin')

@section('title', 'Quản lý lương')

@section('page-title', 'Quản lý lương')

@section('breadcrumb')
    <li class="breadcrumb-item active">Lương</li>
@endsection

@section('content')
<!-- Tổng quan lương -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>980.500.000</h3>
                <p>Tổng lương tháng này</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>152</h3>
                <p>Tổng số nhân viên</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>6.450.000</h3>
                <p>Mức lương trung bình</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>23.500.000</h3>
                <p>Tổng thưởng</p>
            </div>
            <div class="icon">
                <i class="fas fa-award"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- Bộ lọc và chức năng -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tháng/Năm:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="salaryMonth" value="04/2025">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Phòng ban:</label>
                            <select class="form-control select2" id="departmentFilter">
                                <option value="0">Tất cả phòng ban</option>
                                <option value="1">Kỹ thuật</option>
                                <option value="2">Kinh doanh</option>
                                <option value="3">Nhân sự</option>
                                <option value="4">Marketing</option>
                                <option value="5">Tài chính</option>
                                <option value="6">Hành chính</option>
                                <option value="7">IT</option>
                                <option value="8">Pháp lý</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Trạng thái:</label>
                            <select class="form-control" id="statusFilter">
                                <option value="0">Tất cả trạng thái</option>
                                <option value="1">Đã tạm ứng</option>
                                <option value="2">Đã thanh toán</option>
                                <option value="3">Chưa thanh toán</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tìm kiếm:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Tìm kiếm...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#calculateSalaryModal">
                                <i class="fas fa-calculator"></i> Tính lương
                            </button>
                            <button type="button" class="btn btn-info" data-toggle="dropdown">
                                <i class="fas fa-file-export"></i> Xuất báo cáo
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Excel - Danh sách lương</a>
                                <a class="dropdown-item" href="#">PDF - Bảng lương tổng</a>
                                <a class="dropdown-item" href="#">PDF - Phiếu lương cá nhân</a>
                            </div>
                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-envelope"></i> Gửi phiếu lương
                            </button>
                            <button type="button" class="btn btn-warning">
                                <i class="fas fa-cog"></i> Cấu hình lương
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bảng thông tin lương -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bảng lương tháng 04/2025</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Nhân viên</th>
                                <th>Mã NV</th>
                                <th>Phòng ban</th>
                                <th>Chức vụ</th>
                                <th>Lương cơ bản</th>
                                <th>Ngày công</th>
                                <th>Thưởng</th>
                                <th>Phụ cấp</th>
                                <th>Tạm ứng</th>
                                <th>Bảo hiểm</th>
                                <th>Thuế TNCN</th>
                                <th>Thực lãnh</th>
                                <th>Trạng thái</th>
                                <th style="width: 120px">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1.</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg" alt="Avatar" class="img-circle mr-2" width="30">
                                    Nguyễn Văn A
                                </td>
                                <td>NV001</td>
                                <td>Kỹ thuật</td>
                                <td>Trưởng phòng</td>
                                <td>25.000.000</td>
                                <td>22/22</td>
                                <td>3.000.000</td>
                                <td>1.500.000</td>
                                <td>0</td>
                                <td>1.125.000</td>
                                <td>2.975.000</td>
                                <td>25.400.000</td>
                                <td><span class="badge bg-success">Đã thanh toán</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewSalaryModal">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editSalaryModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#payrollSlipModal">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user8-128x128.jpg" alt="Avatar" class="img-circle mr-2" width="30">
                                    Trần Thị B
                                </td>
                                <td>NV002</td>
                                <td>Kinh doanh</td>
                                <td>Nhân viên</td>
                                <td>15.000.000</td>
                                <td>21/22</td>
                                <td>2.500.000</td>
                                <td>1.000.000</td>
                                <td>2.000.000</td>
                                <td>675.000</td>
                                <td>1.585.000</td>
                                <td>14.240.000</td>
                                <td><span class="badge bg-warning">Đã tạm ứng</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user3-128x128.jpg" alt="Avatar" class="img-circle mr-2" width="30">
                                    Lê Văn C
                                </td>
                                <td>NV003</td>
                                <td>Nhân sự</td>
                                <td>Trưởng phòng</td>
                                <td>22.000.000</td>
                                <td>22/22</td>
                                <td>2.000.000</td>
                                <td>1.500.000</td>
                                <td>0</td>
                                <td>990.000</td>
                                <td>2.455.000</td>
                                <td>22.055.000</td>
                                <td><span class="badge bg-success">Đã thanh toán</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user4-128x128.jpg" alt="Avatar" class="img-circle mr-2" width="30">
                                    Phạm Thị D
                                </td>
                                <td>NV004</td>
                                <td>Marketing</td>
                                <td>Nhân viên</td>
                                <td>13.000.000</td>
                                <td>20/22</td>
                                <td>1.000.000</td>
                                <td>800.000</td>
                                <td>0</td>
                                <td>585.000</td>
                                <td>1.215.000</td>
                                <td>13.000.000</td>
                                <td><span class="badge bg-secondary">Chưa thanh toán</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user5-128x128.jpg" alt="Avatar" class="img-circle mr-2" width="30">
                                    Hoàng Văn E
                                </td>
                                <td>NV005</td>
                                <td>Tài chính</td>
                                <td>Nhân viên</td>
                                <td>14.000.000</td>
                                <td>20/22</td>
                                <td>500.000</td>
                                <td>1.000.000</td>
                                <td>0</td>
                                <td>630.000</td>
                                <td>1.370.000</td>
                                <td>13.500.000</td>
                                <td><span class="badge bg-secondary">Chưa thanh toán</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Hiển thị 1 đến 5 của 152 bản ghi</div>
                    <ul class="pagination pagination-sm m-0">
                        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">...</a></li>
                        <li class="page-item"><a class="page-link" href="#">31</a></li>
                        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê lương -->
<div class="row">
    <!-- Biểu đồ phân bổ lương -->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Phân bổ lương theo phòng ban</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="salaryDistributionChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Biểu đồ so sánh lương -->
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">So sánh lương trung bình theo phòng ban</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="averageSalaryChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal tính lương -->
<div class="modal fade" id="calculateSalaryModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tính lương tháng 04/2025</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tháng lương</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" id="calculationMonth" value="04/2025" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phòng ban</label>
                                <select class="form-control select2" style="width: 100%;">
                                    <option value="0" selected>Tất cả phòng ban</option>
                                    <option value="1">Kỹ thuật</option>
                                    <option value="2">Kinh doanh</option>
                                    <option value="3">Nhân sự</option>
                                    <option value="4">Marketing</option>
                                    <option value="5">Tài chính</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ngày bắt đầu</label>
                                <div class="input-group date" id="startDatePicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#startDatePicker" value="01/04/2025"/>
                                    <div class="input-group-append" data-target="#startDatePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ngày kết thúc</label>
                                <div class="input-group date" id="endDatePicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#endDatePicker" value="30/04/2025"/>
                                    <div class="input-group-append" data-target="#endDatePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Số ngày làm việc chuẩn</label>
                                <input type="number" class="form-control" value="22" min="1" max="31">
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkAttendance" checked>
                                    <label for="checkAttendance" class="custom-control-label">Sử dụng dữ liệu chấm công</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkOvertime" checked>
                                    <label for="checkOvertime" class="custom-control-label">Tính lương làm thêm giờ</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkBonus" checked>
                                    <label for="checkBonus" class="custom-control-label">Tính thưởng hiệu suất</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkInsurance" checked>
                                    <label for="checkInsurance" class="custom-control-label">Trừ bảo hiểm xã hội</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkTax" checked>
                                    <label for="checkTax" class="custom-control-label">Tính thuế thu nhập cá nhân</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkAdvance">
                                    <label for="checkAdvance" class="custom-control-label">Trừ tạm ứng lương</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary">Tính lương</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xem chi tiết lương -->
<div class="modal fade" id="viewSalaryModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chi tiết lương nhân viên</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <img src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg" alt="Avatar" class="img-circle" width="100">
                        <h4 class="mt-2">Nguyễn Văn A</h4>
                        <p>Trưởng phòng Kỹ thuật</p>
                        <p class="text-muted">Mã NV: NV001</p>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-striped">
                            <tr>
                                <th style="width: 40%">Tháng lương:</th>
                                <td>04/2025</td>
                            </tr>
                            <tr>
                                <th>Lương cơ bản:</th>
                                <td>25.000.000 VND</td>
                            </tr>
                            <tr>
                                <th>Ngày công thực tế:</th>
                                <td>22/22 ngày</td>
                            </tr>
                            <tr>
                                <th>Lương theo ngày công:</th>
                                <td>25.000.000 VND</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Cấu phần lương</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <tr>
                                        <th style="width: 50%">Lương cơ bản theo ngày công:</th>
                                        <td class="text-right">25.000.000 VND</td>
                                    </tr>
                                    <tr>
                                        <th>Phụ cấp chức vụ:</th>
                                        <td class="text-right">1.500.000 VND</td>
                                    </tr>
                                    <tr>
                                        <th>Thưởng hiệu suất:</th>
                                        <td class="text-right">3.000.000 VND</td>
                                    </tr>
                                    <tr>
                                        <th>Tiền làm thêm giờ:</th>
                                        <td class="text-right">0 VND</td>
                                    </tr>
                                    <tr class="bg-success">
                                        <th>Tổng thu nhập (A):</th>
                                        <td class="text-right font-weight-bold">29.500.000 VND</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-danger">
                            <div class="card-header">
                                <h3 class="card-title">Các khoản giảm trừ</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <tr>
                                        <th style="width: 50%">Bảo hiểm xã hội (8%):</th>
                                        <td class="text-right">800.000 VND</td>
                                    </tr>
                                    <tr>
                                        <th>Bảo hiểm y tế (1.5%):</th>
                                        <td class="text-right">225.000 VND</td>
                                    </tr>
                                    <tr>
                                        <th>Bảo hiểm thất nghiệp (1%):</th>
                                        <td class="text-right">100.000 VND</td>
                                    </tr>
                                    <tr>
                                        <th>Thuế thu nhập cá nhân:</th>
                                        <td class="text-right">2.975.000 VND</td>
                                    </tr>
                                    <tr>
                                        <th>Tạm ứng:</th>
                                        <td class="text-right">0 VND</td>
                                    </tr>
                                    <tr class="bg-danger">
                                        <th>Tổng giảm trừ (B):</th>
                                        <td class="text-right font-weight-bold">4.100.000 VND</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="callout callout-success">
                            <h5>Thực lãnh (A - B):</h5>
                            <h3 class="text-success">25.400.000 VND</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-info">Xuất phiếu lương</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa lương -->
<div class="modal fade" id="editSalaryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chỉnh sửa lương</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Nhân viên</label>
                        <input type="text" class="form-control" value="Nguyễn Văn A - NV001" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tháng lương</label>
                        <input type="text" class="form-control" value="04/2025" readonly>
                    </div>
                    <div class="form-group">
                        <label>Lương cơ bản</label>
                        <input type="text" class="form-control" value="25.000.000">
                    </div>
                    <div class="form-group">
                        <label>Ngày công thực tế</label>
                        <input type="number" class="form-control" value="22" min="0" max="31">
                    </div>
                    <div class="form-group">
                        <label>Thưởng</label>
                        <input type="text" class="form-control" value="3.000.000">
                    </div>
                    <div class="form-group">
                        <label>Phụ cấp</label>
                        <input type="text" class="form-control" value="1.500.000">
                    </div>
                    <div class="form-group">
                        <label>Tạm ứng</label>
                        <input type="text" class="form-control" value="0">
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select class="form-control">
                            <option value="1">Chưa thanh toán</option>
                            <option value="2">Đã tạm ứng</option>
                            <option value="3" selected>Đã thanh toán</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea class="form-control" rows="3" placeholder="Nhập ghi chú nếu có..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal phiếu lương -->
<div class="modal fade" id="payrollSlipModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Phiếu lương tháng 04/2025</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="invoice p-3 mb-3">
                            <div class="row">
                                <div class="col-12">
                                    <h4>
                                        <i class="fas fa-globe"></i> CÔNG TY TNHH ABC
                                        <small class="float-right">Ngày: 25/04/2025</small>
                                    </h4>
                                </div>
                            </div>
                            
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    Thông tin công ty
                                    <address>
                                        <strong>CÔNG TY TNHH ABC</strong><br>
                                        123 Đường Lê Lợi, Q.1<br>
                                        TP Hồ Chí Minh<br>
                                        Điện thoại: (028) 3123-4567<br>
                                        Email: info@abc.com
                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    Thông tin nhân viên
                                    <address>
                                        <strong>Nguyễn Văn A</strong><br>
                                        Mã nhân viên: NV001<br>
                                        Phòng ban: Kỹ thuật<br>
                                        Chức vụ: Trưởng phòng<br>
                                        Email: a@abc.com
                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    <b>Phiếu lương #007612</b><br>
                                    <br>
                                    <b>Kỳ lương:</b> 04/2025<br>
                                    <b>Ngày thanh toán:</b> 05/05/2025<br>
                                    <b>Tài khoản:</b> 9876543210
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Mô tả</th>
                                                <th class="text-right">Số tiền (VND)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Lương cơ bản</td>
                                                <td class="text-right">25.000.000</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Phụ cấp chức vụ</td>
                                                <td class="text-right">1.500.000</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Thưởng hiệu suất</td>
                                                <td class="text-right">3.000.000</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Tiền làm thêm giờ</td>
                                                <td class="text-right">0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <p class="lead">Phương thức thanh toán:</p>
                                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                        Chuyển khoản ngân hàng<br>
                                        Ngân hàng: Vietcombank<br>
                                        Số tài khoản: 9876543210<br>
                                        Chủ tài khoản: Nguyễn Văn A
                                    </p>
                                </div>
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">Tổng thu nhập:</th>
                                                <td class="text-right">29.500.000 VND</td>
                                            </tr>
                                            <tr>
                                                <th>Bảo hiểm xã hội (8%):</th>
                                                <td class="text-right">800.000 VND</td>
                                            </tr>
                                            <tr>
                                                <th>Bảo hiểm y tế (1.5%):</th>
                                                <td class="text-right">225.000 VND</td>
                                            </tr>
                                            <tr>
                                                <th>Bảo hiểm thất nghiệp (1%):</th>
                                                <td class="text-right">100.000 VND</td>
                                            </tr>
                                            <tr>
                                                <th>Thuế thu nhập cá nhân:</th>
                                                <td class="text-right">2.975.000 VND</td>
                                            </tr>
                                            <tr>
                                                <th>Tạm ứng:</th>
                                                <td class="text-right">0 VND</td>
                                            </tr>
                                            <tr>
                                                <th>Thực lãnh:</th>
                                                <td class="text-right font-weight-bold">25.400.000 VND</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-6">
                                    <p class="text-center font-weight-bold">Người lập phiếu</p>
                                    <p class="text-center mt-5">Trần Thị X</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-center font-weight-bold">Người nhận</p>
                                    <p class="text-center mt-5">Nguyễn Văn A</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <div>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-print"></i> In phiếu lương
                    </button>
                    <button type="button" class="btn btn-success">
                        <i class="fas fa-envelope"></i> Gửi Email
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Date picker
        $('#salaryMonth').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'MM/YYYY'
            }
        });
        
        $('#startDatePicker, #endDatePicker').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        
        // Initialize select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        
        // Salary distribution chart
        var distributionCtx = document.getElementById('salaryDistributionChart').getContext('2d');
        var distributionChart = new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Kỹ thuật', 'Kinh doanh', 'Nhân sự', 'Marketing', 'Tài chính', 'Hành chính', 'IT', 'Pháp lý'],
                datasets: [
                    {
                        data: [280, 210, 105, 125, 95, 75, 60, 30],
                        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d', '#6f42c1', '#fd7e14']
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    position: 'right'
                }
            }
        });
        
        // Average salary chart
        var avgSalaryCtx = document.getElementById('averageSalaryChart').getContext('2d');
        var avgSalaryChart = new Chart(avgSalaryCtx, {
            type: 'bar',
            data: {
                labels: ['Kỹ thuật', 'Kinh doanh', 'Nhân sự', 'Marketing', 'Tài chính', 'Hành chính', 'IT', 'Pháp lý'],
                datasets: [
                    {
                        label: 'Lương trung bình (triệu VNĐ)',
                        backgroundColor: '#28a745',
                        borderColor: '#28a745',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: [8.5, 9.2, 7.8, 6.5, 7.2, 5.5, 10.2, 9.8]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    });
</script>
@endpush