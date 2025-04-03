@extends('layouts.admin')

@section('title', 'Quản lý chấm công')

@section('page-title', 'Quản lý chấm công')

@section('breadcrumb')
    <li class="breadcrumb-item active">Chấm công</li>
@endsection

@section('content')
<!-- Quick Stats -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>145</h3>
                <p>Đã chấm công hôm nay</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>95<sup style="font-size: 20px">%</sup></h3>
                <p>Tỷ lệ đi làm hôm nay</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>8</h3>
                <p>Đi muộn/về sớm</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-clock"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>7</h3>
                <p>Vắng mặt không phép</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-times"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Bảng chấm công -->
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Danh sách chấm công</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter and controls -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="attendanceDate" value="03/04/2025">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-filter"></i>
                                </span>
                            </div>
                            <select class="form-control" id="departmentFilter">
                                <option value="0">Tất cả phòng ban</option>
                                <option value="1">Kỹ thuật</option>
                                <option value="2">Kinh doanh</option>
                                <option value="3">Nhân sự</option>
                                <option value="4">Marketing</option>
                                <option value="5">Tài chính</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-filter"></i>
                                </span>
                            </div>
                            <select class="form-control" id="statusFilter">
                                <option value="0">Tất cả trạng thái</option>
                                <option value="1">Đã chấm công</option>
                                <option value="2">Chưa chấm công</option>
                                <option value="3">Đi muộn</option>
                                <option value="4">Về sớm</option>
                                <option value="5">Vắng mặt</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Tìm nhân viên...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#manualAttendanceModal">
                                <i class="fas fa-plus"></i> Chấm công thủ công
                            </button>
                            <button type="button" class="btn btn-info">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </button>
                            <button type="button" class="btn btn-warning">
                                <i class="fas fa-file-import"></i> Nhập từ file
                            </button>
                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-sync"></i> Làm mới
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Attendance table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Nhân viên</th>
                                <th>Mã NV</th>
                                <th>Phòng ban</th>
                                <th>Ngày</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Tổng thời gian</th>
                                <th>Trạng thái</th>
                                <th style="width: 150px">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg" alt="Avatar" class="img-circle mr-2" width="30">
                                    Nguyễn Văn A
                                </td>
                                <td>NV001</td>
                                <td>Kỹ thuật</td>
                                <td>03/04/2025</td>
                                <td>07:55</td>
                                <td>17:05</td>
                                <td>8h 10p</td>
                                <td><span class="badge bg-success">Đúng giờ</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewAttendanceModal">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editAttendanceModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user8-128x128.jpg" alt="Avatar" class="img-circle mr-2" width="30">
                                    Trần Thị B
                                </td>
                                <td>NV002</td>
                                <td>Kinh doanh</td>
                                <td>03/04/2025</td>
                                <td>08:20</td>
                                <td>17:00</td>
                                <td>7h 40p</td>
                                <td><span class="badge bg-warning">Đi muộn</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user3-128x128.jpg" alt="Avatar" class="img-circle mr-2" width="30">
                                    Lê Văn C
                                </td>
                                <td>NV003</td>
                                <td>Nhân sự</td>
                                <td>03/04/2025</td>
                                <td>08:00</td>
                                <td>16:45</td>
                                <td>7h 45p</td>
                                <td><span class="badge bg-info">Về sớm</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user4-128x128.jpg" alt="Avatar" class="img-circle mr-2" width="30">
                                    Phạm Thị D
                                </td>
                                <td>NV004</td>
                                <td>Marketing</td>
                                <td>03/04/2025</td>
                                <td>07:45</td>
                                <td>17:30</td>
                                <td>8h 45p</td>
                                <td><span class="badge bg-purple">Làm thêm giờ</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user5-128x128.jpg" alt="Avatar" class="img-circle mr-2" width="30">
                                    Hoàng Văn E
                                </td>
                                <td>NV005</td>
                                <td>Tài chính</td>
                                <td>03/04/2025</td>
                                <td>--</td>
                                <td>--</td>
                                <td>0h</td>
                                <td><span class="badge bg-danger">Vắng mặt</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
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

<div class="row">
    <!-- Biểu đồ xu hướng chấm công -->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Xu hướng chấm công (30 ngày gần đây)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="attendanceChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Thống kê theo phòng ban -->
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Thống kê chấm công theo phòng ban (hôm nay)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Phòng ban</th>
                                <th>Tổng nhân viên</th>
                                <th>Đã chấm công</th>
                                <th>Đi muộn/Về sớm</th>
                                <th>Vắng mặt</th>
                                <th>Tỷ lệ đi làm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kỹ thuật</td>
                                <td>42</td>
                                <td>40</td>
                                <td>2</td>
                                <td>2</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-success" style="width: 95%"></div>
                                    </div>
                                    <span class="badge bg-success">95%</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Kinh doanh</td>
                                <td>30</td>
                                <td>28</td>
                                <td>3</td>
                                <td>2</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-success" style="width: 93%"></div>
                                    </div>
                                    <span class="badge bg-success">93%</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Nhân sự</td>
                                <td>12</td>
                                <td>12</td>
                                <td>1</td>
                                <td>0</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-success" style="width: 100%"></div>
                                    </div>
                                    <span class="badge bg-success">100%</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Marketing</td>
                                <td>18</td>
                                <td>17</td>
                                <td>0</td>
                                <td>1</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-success" style="width: 94%"></div>
                                    </div>
                                    <span class="badge bg-success">94%</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Tài chính</td>
                                <td>15</td>
                                <td>13</td>
                                <td>1</td>
                                <td>2</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-success" style="width: 87%"></div>
                                    </div>
                                    <span class="badge bg-success">87%</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal chấm công thủ công -->
<div class="modal fade" id="manualAttendanceModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chấm công thủ công</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nhân viên</label>
                                <select class="form-control select2" style="width: 100%;">
                                    <option selected disabled>Chọn nhân viên</option>
                                    <option>Nguyễn Văn A</option>
                                    <option>Trần Thị B</option>
                                    <option>Lê Văn C</option>
                                    <option>Phạm Thị D</option>
                                    <option>Hoàng Văn E</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ngày</label>
                                <div class="input-group date" id="attendanceDatePicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#attendanceDatePicker" value="03/04/2025"/>
                                    <div class="input-group-append" data-target="#attendanceDatePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Giờ vào</label>
                                <div class="input-group date" id="checkInTimePicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#checkInTimePicker"/>
                                    <div class="input-group-append" data-target="#checkInTimePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Giờ ra</label>
                                <div class="input-group date" id="checkOutTimePicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#checkOutTimePicker"/>
                                    <div class="input-group-append" data-target="#checkOutTimePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select class="form-control">
                            <option value="1" selected>Đúng giờ</option>
                            <option value="2">Đi muộn</option>
                            <option value="3">Về sớm</option>
                            <option value="4">Làm thêm giờ</option>
                            <option value="5">Vắng mặt</option>
                            <option value="6">Nghỉ phép</option>
                            <option value="7">Công tác</option>
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
                <button type="button" class="btn btn-primary">Lưu lại</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xem chi tiết chấm công -->
<div class="modal fade" id="viewAttendanceModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chi tiết chấm công</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg" alt="Avatar" class="img-circle" width="100">
                    <h4 class="mt-2">Nguyễn Văn A</h4>
                    <p class="text-muted">Kỹ thuật - Trưởng phòng</p>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%">Ngày:</th>
                            <td>03/04/2025</td>
                        </tr>
                        <tr>
                            <th>Check-in:</th>
                            <td>07:55 <small class="text-success">(đúng giờ)</small></td>
                        </tr>
                        <tr>
                            <th>Check-out:</th>
                            <td>17:05 <small class="text-success">(đúng giờ)</small></td>
                        </tr>
                        <tr>
                            <th>Tổng thời gian:</th>
                            <td>8 giờ 10 phút</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td><span class="badge bg-success">Đúng giờ</span></td>
                        </tr>
                        <tr>
                            <th>Ghi chú:</th>
                            <td>Không có</td>
                        </tr>
                        <tr>
                            <th>Cập nhật lần cuối:</th>
                            <td>03/04/2025 17:05:23</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal sửa chấm công -->
<div class="modal fade" id="editAttendanceModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chỉnh sửa chấm công</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Nhân viên</label>
                        <input type="text" class="form-control" value="Nguyễn Văn A" readonly>
                    </div>
                    <div class="form-group">
                        <label>Ngày</label>
                        <input type="text" class="form-control" value="03/04/2025" readonly>
                    </div>
                    <div class="form-group">
                        <label>Giờ vào</label>
                        <div class="input-group date" id="editCheckInTimePicker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#editCheckInTimePicker" value="07:55"/>
                            <div class="input-group-append" data-target="#editCheckInTimePicker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Giờ ra</label>
                        <div class="input-group date" id="editCheckOutTimePicker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#editCheckOutTimePicker" value="17:05"/>
                            <div class="input-group-append" data-target="#editCheckOutTimePicker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select class="form-control">
                            <option value="1" selected>Đúng giờ</option>
                            <option value="2">Đi muộn</option>
                            <option value="3">Về sớm</option>
                            <option value="4">Làm thêm giờ</option>
                            <option value="5">Vắng mặt</option>
                            <option value="6">Nghỉ phép</option>
                            <option value="7">Công tác</option>
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
@endsection

@push('scripts')
<script>
    $(function () {
        // Date picker
        $('#attendanceDate, #attendanceDatePicker').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        
        // Time pickers
        $('#checkInTimePicker, #checkOutTimePicker, #editCheckInTimePicker, #editCheckOutTimePicker').datetimepicker({
            format: 'HH:mm',
            stepping: 5,
            icons: {
                time: 'far fa-clock'
            }
        });
        
        // Initialize select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        
        // Attendance trend chart
        var ctx = document.getElementById('attendanceChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['04/03', '05/03', '06/03', '07/03', '08/03', '09/03', '10/03', '11/03', '12/03', '13/03', '14/03', '15/03', '16/03', '17/03', '18/03', '19/03', '20/03', '21/03', '22/03', '23/03', '24/03', '25/03', '26/03', '27/03', '28/03', '29/03', '30/03', '31/03', '01/04', '02/04', '03/04'],
                datasets: [
                    {
                        label: 'Tỷ lệ đi làm',
                        backgroundColor: 'rgba(60,141,188,0.2)',
                        borderColor: '#3c8dbc',
                        pointRadius: 3,
                        pointColor: '#3c8dbc',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: [93, 94, 95, 95, 96, 65, 60, 94, 93, 92, 95, 70, 68, 95, 94, 94, 93, 95, 62, 67, 93, 92, 95, 94, 94, 65, 60, 95, 96, 94, 95]
                    },
                    {
                        label: 'Đi muộn/Về sớm',
                        backgroundColor: 'rgba(210, 214, 222, 0.2)',
                        borderColor: '#f39c12',
                        pointRadius: 3,
                        pointColor: '#f39c12',
                        pointStrokeColor: '#f39c12',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: '#f39c12',
                        data: [5, 4, 3, 3, 3, 4, 5, 4, 5, 6, 4, 5, 5, 3, 4, 4, 4, 3, 3, 4, 5, 6, 3, 4, 5, 3, 3, 3, 2, 5, 5]
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 100
                        }
                    }]
                }
            }
        });
    });
</script>
@endpush