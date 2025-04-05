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
                <h3>{{ $statistics['attendedCount'] }}</h3>
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
                <h3>{{ $statistics['attendanceRate'] }}<sup style="font-size: 20px">%</sup></h3>
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
                <h3>{{ $statistics['lateEarlyCount'] }}</h3>
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
                <h3>{{ $statistics['absentCount'] }}</h3>
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
                <form action="{{ route('admin.attendance.index') }}" method="GET" id="filterForm">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="attendanceDate" name="date" value="{{ $date->format('d/m/Y') }}" onchange="document.getElementById('filterForm').submit()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-filter"></i>
                                    </span>
                                </div>
                                <select class="form-control" id="departmentFilter" name="department_id" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">Tất cả phòng ban</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->IDPB }}" {{ request('department_id') == $department->IDPB ? 'selected' : '' }}>
                                            {{ $department->TenPB }}
                                        </option>
                                    @endforeach
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
                                <select class="form-control" id="statusFilter" name="status" onchange="document.getElementById('filterForm').submit()">
                                    <option value="all">Tất cả trạng thái</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_ONTIME }}" {{ request('status') == App\Models\Attendance::ATTENDANCE_ONTIME ? 'selected' : '' }}>Đúng giờ</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_LATE }}" {{ request('status') == App\Models\Attendance::ATTENDANCE_LATE ? 'selected' : '' }}>Đi muộn</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_EARLY_LEAVE }}" {{ request('status') == App\Models\Attendance::ATTENDANCE_EARLY_LEAVE ? 'selected' : '' }}>Về sớm</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_OVERTIME }}" {{ request('status') == App\Models\Attendance::ATTENDANCE_OVERTIME ? 'selected' : '' }}>Làm thêm giờ</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_ABSENT }}" {{ request('status') == App\Models\Attendance::ATTENDANCE_ABSENT ? 'selected' : '' }}>Vắng mặt</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_LEAVE }}" {{ request('status') == App\Models\Attendance::ATTENDANCE_LEAVE ? 'selected' : '' }}>Nghỉ phép</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_BUSINESS }}" {{ request('status') == App\Models\Attendance::ATTENDANCE_BUSINESS ? 'selected' : '' }}>Công tác</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Tìm nhân viên..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="showDeletedRecords" name="show_deleted" 
                                    {{ request()->boolean('show_deleted') ? 'checked' : '' }}
                                    onchange="document.getElementById('filterForm').submit()">
                                <label class="custom-control-label" for="showDeletedRecords">Hiển thị bản ghi đã xóa</label>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#manualAttendanceModal">
                                <i class="fas fa-plus"></i> Chấm công thủ công
                            </button>
                            <a href="{{ route('admin.attendance.export') }}" class="btn btn-info">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </a>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#generateModal">
                                <i class="fas fa-magic"></i> Tạo tự động
                            </button>
                            <a href="{{ route('admin.attendance.index', ['date' => $date->format('d/m/Y')]) }}" class="btn btn-primary">
                                <i class="fas fa-sync"></i> Làm mới
                            </a>
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
                            @forelse($attendances as $index => $attendance)
                                <tr class="{{ $attendance->isDeleted() ? 'text-muted bg-light' : '' }}">
                                    <td>{{ $attendances->firstItem() + $index }}</td>
                                    <td>
                                        <img src="{{ asset('img/default-avatar.jpg') }}" alt="Avatar" class="img-circle mr-2" width="30">
                                        {{ $attendance->employee->TenNV ?? 'N/A' }}
                                    </td>
                                    <td>{{ $attendance->employee->MaNV ?? 'N/A' }}</td>
                                    <td>{{ $attendance->employee->department->TenPB ?? 'N/A' }}</td>
                                    <td>{{ $attendance->formattedDate }}</td>
                                    <td>{{ $attendance->checkInTime }}</td>
                                    <td>{{ $attendance->checkOutTime }}</td>
                                    <td>{{ $attendance->totalWorkTime }}</td>
                                    <td>
                                        <span class="badge {{ $attendance->statusClass }}">
                                            {{ $attendance->statusText }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($attendance->isDeleted())
                                            <a href="{{ route('admin.attendance.restore', $attendance->MABC) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Khôi phục"
                                               onclick="return confirm('Bạn có chắc muốn khôi phục bản ghi này?')">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                        @else
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm view-attendance" 
                                                    data-toggle="modal" 
                                                    data-target="#viewAttendanceModal" 
                                                    data-id="{{ $attendance->MABC }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm edit-attendance" 
                                                    data-toggle="modal" 
                                                    data-target="#editAttendanceModal" 
                                                    data-id="{{ $attendance->MABC }}"
                                                    data-employee="{{ $attendance->employee->TenNV ?? 'N/A' }}"
                                                    data-date="{{ $attendance->formattedDate }}"
                                                    data-checkin="{{ $attendance->checkInTime }}"
                                                    data-checkout="{{ $attendance->checkOutTime }}"
                                                    data-status="{{ $attendance->TrangThai }}"
                                                    data-worktype="{{ $attendance->IDLC }}"
                                                    data-note="{{ $attendance->GhiChu }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm delete-attendance" 
                                                    data-id="{{ $attendance->MABC }}"
                                                    data-employee="{{ $attendance->employee->TenNV ?? 'N/A' }}"
                                                    data-date="{{ $attendance->formattedDate }}"
                                                    onclick="if(confirm('Bạn có chắc muốn xóa bản ghi chấm công này?')) { 
                                                        event.preventDefault(); 
                                                        document.getElementById('delete-form-{{ $attendance->MABC }}').submit(); 
                                                    }">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <form id="delete-form-{{ $attendance->MABC }}" 
                                                    action="{{ route('admin.attendance.destroy', $attendance->MABC) }}" 
                                                    method="POST" 
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Hiển thị {{ $attendances->firstItem() ?? 0 }} đến {{ $attendances->lastItem() ?? 0 }} của {{ $attendances->total() }} bản ghi</div>
                    {{ $attendances->appends(request()->query())->links() }}
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
                            @foreach($statistics['departmentStats'] as $depStat)
                                <tr>
                                    <td>{{ $depStat['name'] }}</td>
                                    <td>{{ $depStat['totalEmployees'] }}</td>
                                    <td>{{ $depStat['attendedCount'] }}</td>
                                    <td>{{ $depStat['lateEarlyCount'] }}</td>
                                    <td>{{ $depStat['absentCount'] }}</td>
                                    <td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar bg-success" style="width: {{ $depStat['attendanceRate'] }}%"></div>
                                        </div>
                                        <span class="badge bg-success">{{ $depStat['attendanceRate'] }}%</span>
                                    </td>
                                </tr>
                            @endforeach
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
            <form action="{{ route('admin.attendance.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nhân viên <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="employee_id" style="width: 100%;" required>
                                    <option selected disabled>Chọn nhân viên</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->MaNV }}">{{ $employee->MaNV }} - {{ $employee->TenNV }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ngày <span class="text-danger">*</span></label>
                                <div class="input-group date" id="attendanceDatePicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" name="date" data-target="#attendanceDatePicker" value="{{ $date->format('d/m/Y') }}" required/>
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
                                    <input type="text" class="form-control datetimepicker-input" name="check_in" data-target="#checkInTimePicker" value="08:00"/>
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
                                    <input type="text" class="form-control datetimepicker-input" name="check_out" data-target="#checkOutTimePicker" value="17:00"/>
                                    <div class="input-group-append" data-target="#checkOutTimePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Trạng thái <span class="text-danger">*</span></label>
                                <select class="form-control" name="status" required>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_ONTIME }}" selected>Đúng giờ</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_LATE }}">Đi muộn</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_EARLY_LEAVE }}">Về sớm</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_OVERTIME }}">Làm thêm giờ</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_ABSENT }}">Vắng mặt</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_LEAVE }}">Nghỉ phép</option>
                                    <option value="{{ App\Models\Attendance::ATTENDANCE_BUSINESS }}">Công tác</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Loại công <span class="text-danger">*</span></label>
                                <select class="form-control" name="work_type_id" required>
                                    @foreach($workTypes as $workType)
                                        <option value="{{ $workType->IDLC }}">{{ $workType->TenLC }} ({{ $workType->HeSo }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea class="form-control" name="note" rows="3" placeholder="Nhập ghi chú nếu có..."></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                </div>
            </form>
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
                    <img src="" alt="Avatar" id="employeeAvatar" class="img-circle" width="100">
                    <h4 class="mt-2" id="employeeName"></h4>
                    <p class="text-muted" id="employeePosition"></p>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%">Ngày:</th>
                            <td id="viewDate"></td>
                        </tr>
                        <tr>
                            <th>Check-in:</th>
                            <td id="viewCheckIn"></td>
                        </tr>
                        <tr>
                            <th>Check-out:</th>
                            <td id="viewCheckOut"></td>
                        </tr>
                        <tr>
                            <th>Tổng thời gian:</th>
                            <td id="viewTotalTime"></td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td id="viewStatus"></td>
                        </tr>
                        <tr>
                            <th>Loại công:</th>
                            <td id="viewWorkType"></td>
                        </tr>
                        <tr>
                            <th>Ghi chú:</th>
                            <td id="viewNote"></td>
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
            <form id="editAttendanceForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nhân viên</label>
                        <input type="text" class="form-control" id="editEmployeeName" readonly>
                    </div>
                    <div class="form-group">
                        <label>Ngày</label>
                        <input type="text" class="form-control" id="editDate" readonly>
                    </div>
                    <div class="form-group">
                        <label>Giờ vào</label>
                        <div class="input-group date" id="editCheckInTimePicker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" name="check_in" id="editCheckIn" data-target="#editCheckInTimePicker"/>
                            <div class="input-group-append" data-target="#editCheckInTimePicker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Giờ ra</label>
                        <div class="input-group date" id="editCheckOutTimePicker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" name="check_out" id="editCheckOut" data-target="#editCheckOutTimePicker"/>
                            <div class="input-group-append" data-target="#editCheckOutTimePicker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select class="form-control" name="status" id="editStatus">
                            <option value="{{ App\Models\Attendance::ATTENDANCE_ONTIME }}">Đúng giờ</option>
                            <option value="{{ App\Models\Attendance::ATTENDANCE_LATE }}">Đi muộn</option>
                            <option value="{{ App\Models\Attendance::ATTENDANCE_EARLY_LEAVE }}">Về sớm</option>
                            <option value="{{ App\Models\Attendance::ATTENDANCE_OVERTIME }}">Làm thêm giờ</option>
                            <option value="{{ App\Models\Attendance::ATTENDANCE_ABSENT }}">Vắng mặt</option>
                            <option value="{{ App\Models\Attendance::ATTENDANCE_LEAVE }}">Nghỉ phép</option>
                            <option value="{{ App\Models\Attendance::ATTENDANCE_BUSINESS }}">Công tác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Loại công</label>
                        <select class="form-control" name="work_type_id" id="editWorkType">
                            @foreach($workTypes as $workType)
                                <option value="{{ $workType->IDLC }}">{{ $workType->TenLC }} ({{ $workType->HeSo }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea class="form-control" name="note" id="editNote" rows="3" placeholder="Nhập ghi chú nếu có..."></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal tạo tự động -->
<div class="modal fade" id="generateModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tạo chấm công tự động</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.attendance.generate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Ngày <span class="text-danger">*</span></label>
                        <div class="input-group date" id="generateDatePicker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" name="date" data-target="#generateDatePicker" value="{{ $date->format('d/m/Y') }}" required/>
                            <div class="input-group-append" data-target="#generateDatePicker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Phòng ban</label>
                        <select class="form-control" name="department_id">
                            <option value="">Tất cả phòng ban</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->IDPB }}">{{ $department->TenPB }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái mặc định <span class="text-danger">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="{{ App\Models\Attendance::ATTENDANCE_ONTIME }}">Đúng giờ</option>
                            <option value="{{ App\Models\Attendance::ATTENDANCE_ABSENT }}">Vắng mặt</option>
                            <option value="{{ App\Models\Attendance::ATTENDANCE_LEAVE }}">Nghỉ phép</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea class="form-control" name="note" rows="3" placeholder="Nhập ghi chú nếu có..."></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Tạo chấm công</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Debug logs
        console.log('Attendance script loaded');
        console.log('Show deleted: ' + $('#showDeletedRecords').is(':checked'));
        
        // Hiển thị thông báo nếu có
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        
        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
        
        @if(session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        // Date picker
        $('#attendanceDate, #attendanceDatePicker, #generateDatePicker').daterangepicker({
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
                time: 'far fa-clock',
                up: 'fas fa-chevron-up',
                down: 'fas fa-chevron-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'far fa-trash-alt',
                close: 'far fa-times-circle'
            }
        });
        
        // Initialize select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        
        // Xem chi tiết chấm công
        $('.view-attendance').on('click', function() {
            var id = $(this).data('id');
            console.log('Viewing attendance ID: ' + id);
            
            // Gọi Ajax để lấy thông tin chi tiết
            $.ajax({
                url: '{{ url("admin/attendance") }}/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Attendance data loaded', response);
                    
                    // Hiển thị thông tin trong modal
                    $('#employeeName').text(response.employee ? response.employee.TenNV : 'N/A');
                    $('#employeePosition').text(
                        (response.department ? response.department.TenPB : 'N/A') + 
                        ' - ' + 
                        (response.position ? response.position.TenCV : 'N/A')
                    );
                    $('#employeeAvatar').attr('src', response.employee && response.employee.AnhDaiDien 
                        ? '{{ asset("storage") }}/' + response.employee.AnhDaiDien 
                        : '{{ asset("img/default-avatar.jpg") }}'
                    );
                    $('#viewDate').text(response.formattedDate || 'N/A');
                    $('#viewCheckIn').text(response.checkInTime || 'N/A');
                    $('#viewCheckOut').text(response.checkOutTime || 'N/A');
                    $('#viewTotalTime').text(response.totalWorkTime || 'N/A');
                    $('#viewStatus').html('<span class="badge ' + response.statusClass + '">' + response.statusText + '</span>');
                    $('#viewWorkType').text(response.workType ? response.workType.TenLC : 'N/A');
                    $('#viewNote').text(response.GhiChu || 'Không có');
                },
                error: function(xhr) {
                    console.error('Error loading attendance data', xhr);
                    toastr.error('Có lỗi xảy ra khi tải thông tin! Vui lòng thử lại.');
                }
            });
        });
        
        // Sửa chấm công
        $('.edit-attendance').on('click', function() {
            var id = $(this).data('id');
            var employee = $(this).data('employee');
            var date = $(this).data('date');
            var checkin = $(this).data('checkin');
            var checkout = $(this).data('checkout');
            var status = $(this).data('status');
            var worktype = $(this).data('worktype');
            var note = $(this).data('note') || '';
            
            console.log('Editing attendance ID: ' + id);
            console.log('Note: ' + note);
            
            // Cập nhật form
            $('#editAttendanceForm').attr('action', '{{ url("admin/attendance") }}/' + id);
            $('#editEmployeeName').val(employee);
            $('#editDate').val(date);
            $('#editCheckIn').val(checkin === '--' ? '' : checkin);
            $('#editCheckOut').val(checkout === '--' ? '' : checkout);
            $('#editStatus').val(status);
            $('#editWorkType').val(worktype);
            $('#editNote').val(note);
        });
        
        // Attendance trend chart
        if (document.getElementById('attendanceChart')) {
            try {
                // Kiểm tra dữ liệu biểu đồ và log chi tiết
                console.log('Checking statistics data:', {!! json_encode($statistics ?? []) !!});
                
                var trendData = @json($statistics['trendData'] ?? []);
                console.log('Trend data loaded:', trendData);
                
                if (trendData && trendData.length > 0) {
                    var ctx = document.getElementById('attendanceChart').getContext('2d');
                    var labels = trendData.map(function(item) { return item.date; });
                    var rates = trendData.map(function(item) { return item.rate; });
                    var lateCounts = trendData.map(function(item) { return item.lateEarlyCount; });
                    
                    console.log('Chart data prepared:', {
                        labels: labels,
                        rates: rates,
                        lateCounts: lateCounts
                    });
                    
                    // Sử dụng Chart.js v2.9.4 với cấu trúc cũ
                    var chartConfig = {
                        type: 'line',
                        data: {
                            labels: labels,
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
                                    data: rates
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
                                    data: lateCounts
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
                    };
                    
                    console.log('Creating chart with config:', chartConfig);
                    var chart = new Chart(ctx, chartConfig);
                    console.log('Chart created successfully');
                } else {
                    console.log('Không có dữ liệu xu hướng để hiển thị biểu đồ');
                }
            } catch (e) {
                console.error('Lỗi khi vẽ biểu đồ:', e);
            }
        }
        
        // Debug checkbox for deleted records
        $('#showDeletedRecords').on('change', function() {
            console.log('Show deleted changed to: ' + $(this).is(':checked'));
            $('#filterForm').submit();
        });
    });
</script>
@endpush