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
            <form action="{{ route('admin.worktime.config') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Loại ca làm việc</label>
                        <select class="form-control" id="workShiftType" name="workShiftType">
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
                            <input type="text" class="form-control datetimepicker-input" data-target="#startTimePicker" id="startTime" name="startTime" value="08:00"/>
                            <div class="input-group-append" data-target="#startTimePicker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="endTime">Giờ kết thúc</label>
                        <div class="input-group date" id="endTimePicker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#endTimePicker" id="endTime" name="endTime" value="17:00"/>
                            <div class="input-group-append" data-target="#endTimePicker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="breakTime">Thời gian nghỉ giữa giờ (phút)</label>
                        <input type="number" class="form-control" id="breakTime" name="breakTime" placeholder="60" value="60">
                    </div>
                    
                    <div class="form-group">
                        <label for="lateThreshold">Mức cho phép đi muộn (phút)</label>
                        <input type="number" class="form-control" id="lateThreshold" name="lateThreshold" placeholder="15" value="15">
                    </div>
                    
                    <div class="form-group">
                        <label for="earlyLeaveThreshold">Mức cho phép về sớm (phút)</label>
                        <input type="number" class="form-control" id="earlyLeaveThreshold" name="earlyLeaveThreshold" placeholder="15" value="15">
                    </div>
                    
                    <div class="form-group">
                        <label>Ngày làm việc trong tuần</label>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="monday" name="workdays[]" value="1" checked>
                            <label for="monday" class="custom-control-label">Thứ 2</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="tuesday" name="workdays[]" value="2" checked>
                            <label for="tuesday" class="custom-control-label">Thứ 3</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="wednesday" name="workdays[]" value="3" checked>
                            <label for="wednesday" class="custom-control-label">Thứ 4</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="thursday" name="workdays[]" value="4" checked>
                            <label for="thursday" class="custom-control-label">Thứ 5</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="friday" name="workdays[]" value="5" checked>
                            <label for="friday" class="custom-control-label">Thứ 6</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="saturday" name="workdays[]" value="6">
                            <label for="saturday" class="custom-control-label">Thứ 7</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="sunday" name="workdays[]" value="7">
                            <label for="sunday" class="custom-control-label">Chủ nhật</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="flexibleTime" name="flexibleTime">
                            <label class="custom-control-label" for="flexibleTime">Cho phép giờ làm linh hoạt</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="overtimeAllowed" name="overtimeAllowed" checked>
                            <label class="custom-control-label" for="overtimeAllowed">Cho phép làm thêm giờ</label>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Lưu cấu hình</button>
                </div>
            </form>
        </div>
        
        <!-- Tạo lịch làm việc tự động -->
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Tạo lịch làm việc tự động</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.worktime.generate') }}" method="POST" id="generateForm">
                    @csrf
                    <div class="form-group">
                        <label>Tháng/Năm</label>
                        <div class="input-group">
                            <select class="form-control" name="month">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                            <select class="form-control" name="year">
                                @for ($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Chọn nhân viên</label>
                        <select class="form-control select2" name="employees[]" multiple="multiple" required>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->MaNV }}">{{ $employee->TenNV }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Loại công</label>
                        <select class="form-control" name="IDLC" required>
                            @foreach($workTypes as $workType)
                                <option value="{{ $workType->IDLC }}">{{ $workType->TenLC }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Giờ làm việc</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="input-group">
                                    <select class="form-control" name="Giovao">
                                        @for ($i = 0; $i < 24; $i++)
                                            <option value="{{ $i }}" {{ $i == 8 ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                    <span class="input-group-text">:</span>
                                    <select class="form-control" name="Phutvao">
                                        @for ($i = 0; $i < 60; $i += 5)
                                            <option value="{{ $i }}" {{ $i == 0 ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <select class="form-control" name="GioRa">
                                        @for ($i = 0; $i < 24; $i++)
                                            <option value="{{ $i }}" {{ $i == 17 ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                    <span class="input-group-text">:</span>
                                    <select class="form-control" name="PhutRa">
                                        @for ($i = 0; $i < 60; $i += 5)
                                            <option value="{{ $i }}" {{ $i == 0 ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Ngày làm việc trong tuần</label>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="gen-monday" name="workdays[]" value="1" checked>
                            <label for="gen-monday" class="custom-control-label">Thứ 2</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="gen-tuesday" name="workdays[]" value="2" checked>
                            <label for="gen-tuesday" class="custom-control-label">Thứ 3</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="gen-wednesday" name="workdays[]" value="3" checked>
                            <label for="gen-wednesday" class="custom-control-label">Thứ 4</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="gen-thursday" name="workdays[]" value="4" checked>
                            <label for="gen-thursday" class="custom-control-label">Thứ 5</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="gen-friday" name="workdays[]" value="5" checked>
                            <label for="gen-friday" class="custom-control-label">Thứ 6</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="gen-saturday" name="workdays[]" value="6">
                            <label for="gen-saturday" class="custom-control-label">Thứ 7</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="gen-sunday" name="workdays[]" value="7">
                            <label for="gen-sunday" class="custom-control-label">Chủ nhật</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-block">Tạo lịch tự động</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Lịch giờ làm -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Lịch giờ làm</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{ route('admin.worktime.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Thêm mới
                        </a>
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#generateModal">Tạo lịch tự động</a>
                            <a href="{{ route('admin.worktime.export') }}" class="dropdown-item">Xuất Excel</a>
                            <a href="#" class="dropdown-item">Nhập từ file</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item text-danger">Xóa tất cả</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Thêm trước bảng chấm công -->
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                        {!! session('warning') !!}
                    </div>
                @endif
                <form action="{{ route('admin.worktime.index') }}" method="GET" id="filterForm">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <select class="form-control" name="month" onchange="document.getElementById('filterForm').submit()">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                                    @endfor
                                </select>
                                <select class="form-control" name="year" onchange="document.getElementById('filterForm').submit()">
                                    @for ($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
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
                                <select class="form-control" name="position_id" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">Tất cả chức vụ</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->IDCV }}" {{ request('position_id') == $position->IDCV ? 'selected' : '' }}>
                                            {{ $position->TenCV }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <select class="form-control" name="employee_id" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">Tất cả nhân viên</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->MaNV }}" {{ request('employee_id') == $employee->MaNV ? 'selected' : '' }}>
                                            {{ $employee->TenNV }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-flag"></i>
                                    </span>
                                </div>
                                <select class="form-control" name="status" onchange="document.getElementById('filterForm').submit()">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="{{ \App\Models\TimeKeeping::ATTENDANCE_ONTIME }}" {{ request('status') == \App\Models\TimeKeeping::ATTENDANCE_ONTIME ? 'selected' : '' }}>Đúng giờ</option>
                                    <option value="{{ \App\Models\TimeKeeping::ATTENDANCE_LATE }}" {{ request('status') == \App\Models\TimeKeeping::ATTENDANCE_LATE ? 'selected' : '' }}>Đi muộn</option>
                                    <option value="{{ \App\Models\TimeKeeping::ATTENDANCE_EARLY_LEAVE }}" {{ request('status') == \App\Models\TimeKeeping::ATTENDANCE_EARLY_LEAVE ? 'selected' : '' }}>Về sớm</option>
                                    <option value="{{ \App\Models\TimeKeeping::ATTENDANCE_OVERTIME }}" {{ request('status') == \App\Models\TimeKeeping::ATTENDANCE_OVERTIME ? 'selected' : '' }}>Làm thêm giờ</option>
                                    <option value="{{ \App\Models\TimeKeeping::ATTENDANCE_ABSENT }}" {{ request('status') == \App\Models\TimeKeeping::ATTENDANCE_ABSENT ? 'selected' : '' }}>Vắng mặt</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Ngày</th>
                                <th>Nhân viên</th>
                                <th>Chức vụ</th>
                                <th>Loại công</th>
                                <th>Giờ vào</th>
                                <th>Giờ ra</th>
                                <th>Tổng giờ làm</th>
                                <th>Trạng thái</th>
                                <th style="width: 120px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($timeKeepings as $index => $timeKeeping)
                                <tr class="worktime-record" 
                                    data-id="{{ $timeKeeping->MABC }}" 
                                    data-hours="{{ $timeKeeping->workHours['hours'] }}" 
                                    data-minutes="{{ $timeKeeping->workHours['minutes'] }}" 
                                    data-status="{{ $timeKeeping->TrangThai }}">
                                    <td>{{ $timeKeepings->firstItem() + $index }}</td>
                                    <td>{{ $timeKeeping->formattedDate }}</td>
                                    <td>{{ $timeKeeping->employee->TenNV ?? 'N/A' }}</td>
                                    <td>{{ $timeKeeping->employee->position->TenCV ?? 'N/A' }}</td>
                                    <td>{{ $timeKeeping->workType->TenLC ?? 'N/A' }}</td>
                                    <td>{{ $timeKeeping->formattedTimeIn }}</td>
                                    <td>{{ $timeKeeping->formattedTimeOut }}</td>
                                    <td>{{ $timeKeeping->workHours['formatted'] }}</td>
                                    <td>
                                        <span class="badge {{ $timeKeeping->attendanceStatusClass }} status-badge">
                                            {{ $timeKeeping->attendanceStatusText }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.worktime.edit', $timeKeeping->MABC) }}" class="btn btn-primary btn-sm edit-worktime" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm delete-worktime" 
                                            data-id="{{ $timeKeeping->MABC }}" 
                                            data-toggle="modal" 
                                            data-target="#deleteModal"
                                            data-employee-name="{{ $timeKeeping->employee->TenNV ?? 'N/A' }}"
                                            data-date="{{ $timeKeeping->formattedDate }}"
                                            title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                
                <div class="mt-3">
                    {{ $timeKeepings->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        
        <!-- Tổng kết thời gian làm việc -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tổng kết thời gian làm việc (Tháng {{ $month }}/{{ $year }})</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="far fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tổng giờ làm</span>
                                <span class="info-box-number">{{ $statistics['total_hours'] }} giờ</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Đúng giờ</span>
                                <span class="info-box-number">{{ $statistics['ontime_count'] }} lượt</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-user-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Đi muộn/Về sớm</span>
                                <span class="info-box-number">{{ $statistics['late_early_count'] }} lượt</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-user-times"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Vắng mặt</span>
                                <span class="info-box-number">{{ $statistics['absent_count'] }} lượt</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xóa giờ làm -->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác nhận xóa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa bản ghi chấm công của <strong id="delete-employee-name"></strong> ngày <strong id="delete-date"></strong>?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <form id="delete-form" action="" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal tạo lịch tự động -->
<div class="modal fade" id="generateModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tạo lịch làm việc tự động</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nội dung form giống với form tạo lịch tự động bên trái -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Hiển thị thông báo qua session flash
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    
        // Time picker cho giờ bắt đầu và kết thúc
        $('#startTimePicker, #endTimePicker').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
            icons: {
                time: 'far fa-clock'
            }
        });
        
        // Khởi tạo Select2 cho dropdown chọn nhiều nhân viên
        $('.select2').select2({
            placeholder: 'Chọn nhân viên...',
            allowClear: true
        });
        
        // Xử lý sự kiện nút xóa
        $('.delete-worktime').on('click', function() {
            var id = $(this).data('id');
            var employeeName = $(this).data('employee-name');
            var date = $(this).data('date');
            
            $('#delete-employee-name').text(employeeName);
            $('#delete-date').text(date);
            
            // Đường dẫn chính xác
            $('#delete-form').attr('action', '{{ url("admin/worktime") }}/' + id + '/delete');
            
            console.log('Delete URL set to: ' + $('#delete-form').attr('action')); // Debug
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
        
        // Kiểm tra và cập nhật tự động các trạng thái không nhất quán
        function checkAndUpdateStatusConsistency() {
            $('.worktime-record').each(function() {
                const row = $(this);
                const hours = parseInt(row.attr('data-hours') || 0);
                const minutes = parseInt(row.attr('data-minutes') || 0);
                const status = parseInt(row.attr('data-status'));
                
                if (hours > 0 || minutes > 0) {
                    // Có giờ làm việc nhưng trạng thái là vắng mặt
                    if (status == {{ \App\Models\TimeKeeping::ATTENDANCE_ABSENT }}) {
                        const recordId = row.attr('data-id');
                        
                        // Gửi AJAX request để cập nhật trạng thái
                        $.ajax({
                            url: '{{ route("admin.worktime.update-status") }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: recordId,
                                auto_fix: true
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Cập nhật UI
                                    row.find('.status-badge')
                                       .removeClass('bg-danger')
                                       .addClass(response.statusClass)
                                       .text(response.statusText);
                                    
                                    row.attr('data-status', response.status);
                                    
                                    toastr.info('Đã tự động cập nhật trạng thái cho 1 bản ghi.');
                                }
                            }
                        });
                    }
                }
            });
        }
        
        // Chạy kiểm tra tự động sau khi trang tải xong
        setTimeout(checkAndUpdateStatusConsistency, 1000);
    });
</script>
@endpush