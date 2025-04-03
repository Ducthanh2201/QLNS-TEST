@extends('layouts.admin')

@section('title', 'Quản lý giờ làm')

@section('page-title', 'Quản lý giờ làm')

@section('breadcrumb')
    <li class="breadcrumb-item active">Giờ làm</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Form cấu hình giờ làm -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Cấu hình giờ làm</h3>
            </div>
            <form>
                <div class="card-body">
                    <div class="form-group">
                        <label>Loại ca làm việc</label>
                        <select class="form-control" id="workShiftType">
                            <option value="1" selected>Ca hành chính (8h-17h)</option>
                            <option value="2">Ca sáng (6h-14h)</option>
                            <option value="3">Ca chiều (14h-22h)</option>
                            <option value="4">Ca đêm (22h-6h)</option>
                            <option value="5">Ca linh hoạt</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="startTime">Giờ bắt đầu</label>
                        <div class="input-group date" id="startTimePicker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#startTimePicker" id="startTime" value="08:00"/>
                            <div class="input-group-append" data-target="#startTimePicker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="endTime">Giờ kết thúc</label>
                        <div class="input-group date" id="endTimePicker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#endTimePicker" id="endTime" value="17:00"/>
                            <div class="input-group-append" data-target="#endTimePicker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="breakTime">Thời gian nghỉ giữa giờ (phút)</label>
                        <input type="number" class="form-control" id="breakTime" placeholder="60" value="60">
                    </div>
                    
                    <div class="form-group">
                        <label for="lateThreshold">Mức cho phép đi muộn (phút)</label>
                        <input type="number" class="form-control" id="lateThreshold" placeholder="15" value="15">
                    </div>
                    
                    <div class="form-group">
                        <label for="earlyLeaveThreshold">Mức cho phép về sớm (phút)</label>
                        <input type="number" class="form-control" id="earlyLeaveThreshold" placeholder="15" value="15">
                    </div>
                    
                    <div class="form-group">
                        <label>Ngày làm việc trong tuần</label>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="monday" checked>
                            <label for="monday" class="custom-control-label">Thứ 2</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="tuesday" checked>
                            <label for="tuesday" class="custom-control-label">Thứ 3</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="wednesday" checked>
                            <label for="wednesday" class="custom-control-label">Thứ 4</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="thursday" checked>
                            <label for="thursday" class="custom-control-label">Thứ 5</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="friday" checked>
                            <label for="friday" class="custom-control-label">Thứ 6</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="saturday">
                            <label for="saturday" class="custom-control-label">Thứ 7</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="sunday">
                            <label for="sunday" class="custom-control-label">Chủ nhật</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="flexibleTime">
                            <label class="custom-control-label" for="flexibleTime">Cho phép giờ làm linh hoạt</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="overtimeAllowed" checked>
                            <label class="custom-control-label" for="overtimeAllowed">Cho phép làm thêm giờ</label>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Lưu cấu hình</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Lịch giờ làm -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Lịch giờ làm</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                            <a href="#" class="dropdown-item">Tạo lịch tự động</a>
                            <a href="#" class="dropdown-item">Xuất Excel</a>
                            <a href="#" class="dropdown-item">Nhập từ file</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Xóa tất cả</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="dateRange" value="01/04/2025 - 30/04/2025">
                        </div>
                    </div>
                    <div class="col-md-4">
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
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Ngày</th>
                                <th>Nhân viên</th>
                                <th>Phòng ban</th>
                                <th>Ca làm việc</th>
                                <th>Giờ vào</th>
                                <th>Giờ ra</th>
                                <th>Tổng giờ làm</th>
                                <th>Trạng thái</th>
                                <th style="width: 120px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>01/04/2025</td>
                                <td>Nguyễn Văn A</td>
                                <td>Kỹ thuật</td>
                                <td>Ca hành chính</td>
                                <td>08:00</td>
                                <td>17:00</td>
                                <td>8 giờ</td>
                                <td><span class="badge bg-success">Đúng giờ</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>01/04/2025</td>
                                <td>Trần Thị B</td>
                                <td>Kinh doanh</td>
                                <td>Ca hành chính</td>
                                <td>08:15</td>
                                <td>17:00</td>
                                <td>7h 45p</td>
                                <td><span class="badge bg-warning">Đi muộn</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>01/04/2025</td>
                                <td>Lê Văn C</td>
                                <td>Nhân sự</td>
                                <td>Ca hành chính</td>
                                <td>08:00</td>
                                <td>16:45</td>
                                <td>7h 45p</td>
                                <td><span class="badge bg-info">Về sớm</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>01/04/2025</td>
                                <td>Phạm Thị D</td>
                                <td>Marketing</td>
                                <td>Ca hành chính</td>
                                <td>07:45</td>
                                <td>17:30</td>
                                <td>8h 45p</td>
                                <td><span class="badge bg-purple">Làm thêm</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>01/04/2025</td>
                                <td>Hoàng Văn E</td>
                                <td>Tài chính</td>
                                <td>Ca hành chính</td>
                                <td>-</td>
                                <td>-</td>
                                <td>0h</td>
                                <td><span class="badge bg-danger">Vắng mặt</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <ul class="pagination pagination-sm">
                        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Tổng kết thời gian làm việc -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tổng kết thời gian làm việc (Tháng 04/2025)</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="far fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tổng giờ làm</span>
                                <span class="info-box-number">4,260 giờ</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Đúng giờ</span>
                                <span class="info-box-number">142 lượt</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-user-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Đi muộn/Về sớm</span>
                                <span class="info-box-number">18 lượt</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-user-times"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Vắng mặt</span>
                                <span class="info-box-number">10 lượt</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa giờ làm -->
<div class="modal fade" id="editWorkTimeModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chỉnh sửa giờ làm</h4>
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
                        <input type="text" class="form-control" value="01/04/2025" readonly>
                    </div>
                    <div class="form-group">
                        <label>Ca làm việc</label>
                        <select class="form-control">
                            <option selected>Ca hành chính (8h-17h)</option>
                            <option>Ca sáng (6h-14h)</option>
                            <option>Ca chiều (14h-22h)</option>
                            <option>Ca đêm (22h-6h)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giờ vào</label>
                        <input type="text" class="form-control" value="08:00">
                    </div>
                    <div class="form-group">
                        <label>Giờ ra</label>
                        <input type="text" class="form-control" value="17:00">
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
    $(function() {
        // Time picker for start time and end time
        $('#startTimePicker, #endTimePicker').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
            icons: {
                time: 'far fa-clock'
            }
        });
        
        // Date range picker
        $('#dateRange').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        
        // Edit work time button
        $('.btn-primary.btn-sm').on('click', function() {
            $('#editWorkTimeModal').modal('show');
        });
        
        // Work shift type change
        $('#workShiftType').on('change', function() {
            const val = $(this).val();
            
            if (val == 1) { // Ca hành chính
                $('#startTime').val('08:00');
                $('#endTime').val('17:00');
                $('#breakTime').val('60');
            } else if (val == 2) { // Ca sáng
                $('#startTime').val('06:00');
                $('#endTime').val('14:00');
                $('#breakTime').val('30');
            } else if (val == 3) { // Ca chiều
                $('#startTime').val('14:00');
                $('#endTime').val('22:00');
                $('#breakTime').val('30');
            } else if (val == 4) { // Ca đêm
                $('#startTime').val('22:00');
                $('#endTime').val('06:00');
                $('#breakTime').val('30');
            } else if (val == 5) { // Ca linh hoạt
                $('#startTime').val('');
                $('#endTime').val('');
                $('#breakTime').val('30');
                $('#flexibleTime').prop('checked', true);
            }
        });
    });
</script>
@endpush