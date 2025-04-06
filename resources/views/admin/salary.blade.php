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
                <h3>{{ number_format($totalSalary ?? 0, 0, ',', '.') }}</h3>
                <p>Tổng lương tháng {{ $month }}/{{ $year }}</p>
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
                <h3>{{ $salaries->total() ?? 0 }}</h3>
                <p>Tổng số nhân viên</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.employees.index') }}" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($salaries->avg('TongTien') ?? 0, 0, ',', '.') }}</h3>
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
                <h3>{{ number_format($pendingSalary ?? 0, 0, ',', '.') }}</h3>
                <p>Lương chưa thanh toán</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
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
                <form action="{{ route('admin.salary.index') }}" method="GET" id="filterForm">
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
                                    <input type="text" class="form-control" id="salaryMonth" name="month_year" value="{{ $month }}/{{ $year }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Phòng ban:</label>
                                <select class="form-control select2" id="departmentFilter" name="department_id">
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
                            <div class="form-group">
                                <label>Trạng thái:</label>
                                <select class="form-control" id="statusFilter" name="status">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="{{ App\Models\Salary::STATUS_PENDING }}" {{ request('status') == App\Models\Salary::STATUS_PENDING ? 'selected' : '' }}>Chưa thanh toán</option>
                                    <option value="{{ App\Models\Salary::STATUS_PAID }}" {{ request('status') == App\Models\Salary::STATUS_PAID ? 'selected' : '' }}>Đã thanh toán</option>
                                    <option value="{{ App\Models\Salary::STATUS_CANCELLED }}" {{ request('status') == App\Models\Salary::STATUS_CANCELLED ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tìm kiếm:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#calculateSalaryModal">
                                <i class="fas fa-calculator"></i> Tính lương
                            </button>
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-file-export"></i> Xuất báo cáo
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('admin.salary.index', ['export' => 'excel', 'month' => $month, 'year' => $year]) }}">Excel - Danh sách lương</a>
                                <a class="dropdown-item" href="{{ route('admin.salary.index', ['export' => 'pdf', 'month' => $month, 'year' => $year]) }}">PDF - Bảng lương tổng</a>
                            </div>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#salaryConfigModal">
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
                <h3 class="card-title">Bảng lương tháng {{ $month }}/{{ $year }}</h3>
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
                                <th>Thực lãnh</th>
                                <th>Trạng thái</th>
                                <th style="width: 120px">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salaries as $index => $salary)
                                <tr>
                                    <td>{{ $salaries->firstItem() + $index }}.</td>
                                    <td>
                                        <img src="{{ $salary->employee && $salary->employee->AnhDaiDien ? asset('storage/' . $salary->employee->AnhDaiDien) : asset('img/default-avatar.jpg') }}" 
                                            alt="Avatar" class="img-circle mr-2" width="30">
                                        {{ $salary->employee->TenNV ?? 'N/A' }}
                                    </td>
                                    <td>{{ $salary->MaNV }}</td>
                                    <td>{{ $salary->employee->department->TenPB ?? 'N/A' }}</td>
                                    <td>{{ $salary->employee->position->TenCV ?? 'N/A' }}</td>
                                    <td>{{ number_format($salary->LuongCoBan, 0, ',', '.') }}</td>
                                    <td>{{ $salary->TongNgayCong }}/22</td>
                                    <td>{{ number_format($salary->TongTien, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $salary->statusClass }}">
                                            {{ $salary->statusName }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm view-salary"
                                                data-id="{{ $salary->ID }}" data-toggle="modal" data-target="#viewSalaryModal">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm edit-salary"
                                                data-id="{{ $salary->ID }}" data-toggle="modal" data-target="#editSalaryModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm payslip-salary"
                                                data-id="{{ $salary->ID }}" data-toggle="modal" data-target="#payrollSlipModal">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Không có dữ liệu lương</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Hiển thị {{ $salaries->firstItem() ?? 0 }} đến {{ $salaries->lastItem() ?? 0 }} của {{ $salaries->total() ?? 0 }} bản ghi</div>
                    {{ $salaries->appends(request()->query())->links() }}
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
<div class="modal fade" id="calculateSalaryModal" tabindex="-1" role="dialog" aria-labelledby="calculateSalaryModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="calculateSalaryModalLabel">Tính lương tháng {{ $month }}/{{ $year }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="batchCalculateForm" action="{{ route('admin.salary.batchCalculate') }}" method="POST">
                @csrf
                <div class="modal-body">
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
                                    <input type="text" class="form-control" id="calculationMonth" value="{{ $month }}/{{ $year }}" readonly>
                                    <input type="hidden" name="month" value="{{ $month }}">
                                    <input type="hidden" name="year" value="{{ $year }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phòng ban</label>
                                <select class="form-control select2" name="department_id" style="width: 100%;">
                                    <option value="">Tất cả phòng ban</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->IDPB }}">{{ $department->TenPB }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkAttendance" name="include_attendance" checked>
                                    <label for="checkAttendance" class="custom-control-label">Sử dụng dữ liệu chấm công</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkAdvance" name="include_advance" checked>
                                    <label for="checkAdvance" class="custom-control-label">Trừ tạm ứng lương</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary calculate-btn">Tính lương</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal xem chi tiết lương -->
<div class="modal fade" id="viewSalaryModal" tabindex="-1" role="dialog" aria-labelledby="viewSalaryModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="viewSalaryModalLabel">Chi tiết lương nhân viên</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Đang tải...</span>
                    </div>
                </div>
                <div id="salaryDetailContent" style="display: none;">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="" alt="Avatar" id="employeeAvatar" class="img-circle" width="100">
                            <h4 class="mt-2" id="employeeName"></h4>
                            <p id="employeePosition"></p>
                            <p class="text-muted" id="employeeCode"></p>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-striped">
                                <tr>
                                    <th style="width: 40%">Tháng lương:</th>
                                    <td id="salaryMonth"></td>
                                </tr>
                                <tr>
                                    <th>Lương cơ bản:</th>
                                    <td id="baseSalary"></td>
                                </tr>
                                <tr>
                                    <th>Ngày công thực tế:</th>
                                    <td id="workingDays"></td>
                                </tr>
                                <tr>
                                    <th>Lương theo ngày công:</th>
                                    <td id="salaryByDays"></td>
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
                                            <td class="text-right" id="salaryByWorkingDays"></td>
                                        </tr>
                                        <tr>
                                            <th>Phụ cấp:</th>
                                            <td class="text-right" id="allowanceAmount"></td>
                                        </tr>
                                        <tr>
                                            <th>Thưởng:</th>
                                            <td class="text-right" id="rewardAmount"></td>
                                        </tr>
                                        <tr class="bg-success">
                                            <th>Tổng thu nhập:</th>
                                            <td class="text-right font-weight-bold" id="totalIncome"></td>
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
                                            <th style="width: 50%">Tạm ứng:</th>
                                            <td class="text-right" id="advanceAmount"></td>
                                        </tr>
                                        <tr>
                                            <th>Phạt:</th>
                                            <td class="text-right" id="penaltyAmount"></td>
                                        </tr>
                                        <tr class="bg-danger">
                                            <th>Tổng giảm trừ:</th>
                                            <td class="text-right font-weight-bold" id="totalDeduction"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-success">
                                <h5>Thực lãnh:</h5>
                                <h3 class="text-success" id="netSalary"></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-info export-payslip" id="exportPayslipBtn">Xuất phiếu lương</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa lương -->
<div class="modal fade" id="editSalaryModal" tabindex="-1" role="dialog" aria-labelledby="editSalaryModalLabel" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editSalaryModalLabel">Chỉnh sửa lương</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editSalaryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nhân viên</label>
                        <input type="text" class="form-control" id="edit_employee_name" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tháng lương</label>
                        <input type="text" class="form-control" id="edit_salary_month" readonly>
                    </div>
                    <div class="form-group">
                        <label>Lương cơ bản</label>
                        <input type="number" class="form-control" name="luong_co_ban" id="edit_luong_co_ban">
                    </div>
                    <div class="form-group">
                        <label>Ngày công thực tế</label>
                        <input type="number" class="form-control" name="tong_ngay_cong" id="edit_tong_ngay_cong" min="0" max="31">
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select class="form-control" name="trang_thai" id="edit_trang_thai">
                            <option value="{{ App\Models\Salary::STATUS_PENDING }}">Chưa thanh toán</option>
                            <option value="{{ App\Models\Salary::STATUS_PAID }}">Đã thanh toán</option>
                            <option value="{{ App\Models\Salary::STATUS_CANCELLED }}">Đã hủy</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea class="form-control" name="ghi_chu" id="edit_ghi_chu" rows="3" placeholder="Nhập ghi chú nếu có..."></textarea>
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

<!-- Modal phiếu lương -->
<div class="modal fade" id="payrollSlipModal" tabindex="-1" role="dialog" aria-labelledby="payrollSlipModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="payrollSlipModalLabel">Phiếu lương</h4>
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
                                        <i class="fas fa-globe"></i> CÔNG TY TNHH QLNS
                                        <small class="float-right">Ngày: {{ date('d/m/Y') }}</small>
                                    </h4>
                                </div>
                            </div>
                            
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    Thông tin công ty
                                    <address>
                                        <strong>CÔNG TY TNHH QLNS</strong><br>
                                        123 Đường Lê Lợi, Q.1<br>
                                        TP Hồ Chí Minh<br>
                                        Điện thoại: (028) 3123-4567<br>
                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    Thông tin nhân viên
                                    <address>
                                        <strong id="payslip_employee_name"></strong><br>
                                        Mã NV: <span id="payslip_employee_code"></span><br>
                                        Phòng ban: <span id="payslip_department"></span><br>
                                        Chức vụ: <span id="payslip_position"></span><br>
                                    </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    <b>Phiếu lương</b><br>
                                    <br>
                                    <b>Kỳ lương:</b> <span id="payslip_month"></span><br>
                                    <b>Ngày thanh toán:</b> {{ date('d/m/Y', strtotime('first day of next month')) }}<br>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Khoản mục</th>
                                                <th class="text-right">Số tiền (VNĐ)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="payslip_items">
                                            <tr>
                                                <td>1</td>
                                                <td>Lương cơ bản</td>
                                                <td class="text-right" id="payslip_base_salary"></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Lương theo ngày công <span id="payslip_working_days"></span></td>
                                                <td class="text-right" id="payslip_salary_by_days"></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Phụ cấp</td>
                                                <td class="text-right" id="payslip_allowance"></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Thưởng</td>
                                                <td class="text-right" id="payslip_reward"></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Khấu trừ</td>
                                                <td class="text-right text-danger" id="payslip_deduction"></td>
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
                                        Ngày thanh toán: 10 hàng tháng
                                    </p>
                                </div>
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">Tổng thu nhập:</th>
                                                <td class="text-right" id="payslip_total_income"></td>
                                            </tr>
                                            <tr>
                                                <th>Tổng khấu trừ:</th>
                                                <td class="text-right" id="payslip_total_deduction"></td>
                                            </tr>
                                            <tr>
                                                <th>Thực lãnh:</th>
                                                <td class="text-right" id="payslip_net_salary"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-6">
                                    <p class="text-center font-weight-bold">Người lập phiếu</p>
                                    <p class="text-center mt-5">Phòng Nhân sự</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-center font-weight-bold">Người nhận</p>
                                    <p class="text-center mt-5" id="payslip_employee_name2"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <div>
                    <button type="button" class="btn btn-primary" id="printPayslipBtn">
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

<!-- Modal Cấu hình lương -->
<div class="modal fade" id="salaryConfigModal" tabindex="-1" role="dialog" aria-labelledby="salaryConfigModalLabel" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="salaryConfigModalLabel">Cấu hình lương</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Số ngày công tiêu chuẩn</label>
                        <input type="number" class="form-control" value="22" min="1" max="31">
                    </div>
                    <div class="form-group">
                        <label>Ngày thanh toán lương</label>
                        <input type="number" class="form-control" value="10" min="1" max="31">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary">Lưu cấu hình</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Setup CSRF token cho tất cả ajax requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Debug AJAX errors
        $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
            console.error("AJAX Error:", thrownError);
            console.error("Status:", jqxhr.status);
            console.error("Response:", jqxhr.responseText);
        });
        
        // Date picker
        $('#salaryMonth').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'MM/YYYY'
            }
        });
        
        // Initialize select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        
        // Khi thay đổi select hoặc ngày tháng, submit form filter
        $('#departmentFilter, #statusFilter, #salaryMonth').change(function() {
            var monthYear = $('#salaryMonth').val().split('/');
            $('input[name="month"]').val(monthYear[0]);
            $('input[name="year"]').val(monthYear[1]);
            $('#filterForm').submit();
        });
        
        // Xem chi tiết lương
        $('.view-salary').click(function() {
            var id = $(this).data('id');
            console.log("Viewing salary ID:", id); // Debug
            $('#salaryDetailContent').hide();
            $('.spinner-border').show();
            
            $.ajax({
                url: '{{ url("admin/salary") }}/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log("Response success:", response); // Debug
                    // Ẩn spinner, hiện nội dung
                    $('.spinner-border').hide();
                    $('#salaryDetailContent').show();
                    
                    // Cập nhật thông tin nhân viên
                    $('#employeeAvatar').attr('src', response.employee && response.employee.AnhDaiDien 
                        ? '{{ asset("storage") }}/' + response.employee.AnhDaiDien 
                        : '{{ asset("img/default-avatar.jpg") }}');
                    $('#employeeName').text(response.employee ? response.employee.TenNV : 'N/A');
                    $('#employeePosition').text(
                        (response.employee && response.employee.position ? response.employee.position.TenCV : 'N/A') + 
                        ' - ' + 
                        (response.employee && response.employee.department ? response.employee.department.TenPB : 'N/A')
                    );
                    $('#employeeCode').text('Mã NV: ' + (response.employee ? response.employee.MaNV : 'N/A'));
                    
                    // Cập nhật thông tin lương
                    $('#salaryMonth').text(response.basic_info.month + '/' + response.basic_info.year);
                    $('#baseSalary').text(formatCurrency(response.salary_components.base_salary));
                    $('#workingDays').text(response.salary_components.working_days + '/22');
                    $('#salaryByDays').text(formatCurrency(response.salary_components.salary_by_days));
                    
                    // Cập nhật cấu phần lương
                    $('#salaryByWorkingDays').text(formatCurrency(response.salary_components.salary_by_days));
                    $('#allowanceAmount').text(formatCurrency(response.salary_components.allowance));
                    $('#rewardAmount').text(formatCurrency(response.salary_components.reward));
                    
                    var totalIncome = response.salary_components.salary_by_days + 
                                       response.salary_components.allowance + 
                                       response.salary_components.reward;
                    $('#totalIncome').text(formatCurrency(totalIncome));
                    
                    // Cập nhật các khoản giảm trừ
                    $('#advanceAmount').text(formatCurrency(response.salary_components.advance));
                    $('#penaltyAmount').text(formatCurrency(response.salary_components.penalty));
                    
                    var totalDeduction = response.salary_components.advance + 
                                         response.salary_components.penalty;
                    $('#totalDeduction').text(formatCurrency(totalDeduction));
                    
                    // Cập nhật thực lãnh
                    $('#netSalary').text(formatCurrency(response.total));
                    
                    // Cập nhật ID cho nút xuất phiếu lương
                    $('#exportPayslipBtn').data('id', id);
                },
                error: function(xhr, status, error) {
                    $('.spinner-border').hide();
                    console.error("Error details:", xhr.responseText);
                    toastr.error('Có lỗi xảy ra khi tải thông tin! Vui lòng thử lại. Chi tiết: ' + error);
                }
            });
        });
        
        // Edit salary
        $('.edit-salary').click(function() {
            var id = $(this).data('id');
            console.log("Editing salary ID:", id); // Debug
            
            $.ajax({
                url: '{{ url("admin/salary") }}/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log("Edit response:", response); // Debug
                    // Cập nhật form
                    $('#editSalaryForm').attr('action', '{{ url("admin/salary") }}/' + id);
                    $('#edit_employee_name').val(response.employee ? (response.employee.TenNV + ' - ' + response.employee.MaNV) : 'N/A');
                    $('#edit_salary_month').val(response.basic_info.month + '/' + response.basic_info.year);
                    $('#edit_luong_co_ban').val(response.salary_components.base_salary);
                    $('#edit_tong_ngay_cong').val(response.salary_components.working_days);
                    
                    var statusValue;
                    if (response.basic_info.status === 'Đã thanh toán') {
                        statusValue = {{ App\Models\Salary::STATUS_PAID }};
                    } else if (response.basic_info.status === 'Đã hủy') {
                        statusValue = {{ App\Models\Salary::STATUS_CANCELLED }};
                    } else {
                        statusValue = {{ App\Models\Salary::STATUS_PENDING }};
                    }
                    
                    $('#edit_trang_thai').val(statusValue);
                    $('#edit_ghi_chu').val(response.basic_info.note || '');
                },
                error: function(xhr, status, error) {
                    console.error("Edit error details:", xhr.responseText);
                    toastr.error('Có lỗi xảy ra khi tải thông tin! Vui lòng thử lại. Chi tiết: ' + error);
                }
            });
        });
        
        // Payslip
        $('.payslip-salary').click(function() {
            var id = $(this).data('id');
            console.log("Payslip for salary ID:", id); // Debug
            
            $.ajax({
                url: '{{ url("admin/salary") }}/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log("Payslip response:", response); // Debug
                    // Cập nhật thông tin phiếu lương
                    $('#payslip_employee_name, #payslip_employee_name2').text(response.employee ? response.employee.TenNV : 'N/A');
                    $('#payslip_employee_code').text(response.employee ? response.employee.MaNV : 'N/A');
                    $('#payslip_department').text(response.employee && response.employee.department ? response.employee.department.TenPB : 'N/A');
                    $('#payslip_position').text(response.employee && response.employee.position ? response.employee.position.TenCV : 'N/A');
                    $('#payslip_month').text(response.basic_info.month + '/' + response.basic_info.year);
                    
                    // Cập nhật các khoản mục lương
                    $('#payslip_base_salary').text(formatCurrency(response.salary_components.base_salary));
                    $('#payslip_working_days').text('(' + response.salary_components.working_days + '/22)');
                    $('#payslip_salary_by_days').text(formatCurrency(response.salary_components.salary_by_days));
                    $('#payslip_allowance').text(formatCurrency(response.salary_components.allowance));
                    $('#payslip_reward').text(formatCurrency(response.salary_components.reward));
                    
                    var totalDeduction = response.salary_components.advance + response.salary_components.penalty;
                    $('#payslip_deduction').text(formatCurrency(totalDeduction));
                    
                    // Cập nhật tổng
                    var totalIncome = response.salary_components.salary_by_days + 
                                      response.salary_components.allowance + 
                                      response.salary_components.reward;
                    
                    $('#payslip_total_income').text(formatCurrency(totalIncome));
                    $('#payslip_total_deduction').text(formatCurrency(totalDeduction));
                    $('#payslip_net_salary').text(formatCurrency(response.total));
                    
                    // Cập nhật ID cho nút in
                    $('#printPayslipBtn').data('id', id);
                },
                error: function(xhr, status, error) {
                    console.error("Payslip error details:", xhr.responseText);
                    toastr.error('Có lỗi xảy ra khi tải thông tin! Vui lòng thử lại. Chi tiết: ' + error);
                }
            });
        });
        
        // In phiếu lương
        $('#printPayslipBtn').click(function() {
            var id = $(this).data('id');
            if (!id) {
                toastr.error('Không tìm thấy ID lương để in!');
                return;
            }
            window.open('{{ url("admin/salary") }}/' + id + '/payslip', '_blank');
        });
        
        // Xử lý form tính lương
        $('#batchCalculateForm').submit(function(e) {
            e.preventDefault();
            
            var formData = $(this).serialize();
            $('.calculate-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang tính...');
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        toastr.error(response.message || 'Có lỗi xảy ra khi tính lương');
                        $('.calculate-btn').prop('disabled', false).html('Tính lương');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Calculate error details:", xhr.responseText);
                    toastr.error('Có lỗi xảy ra khi tính lương! Vui lòng thử lại. Chi tiết: ' + error);
                    $('.calculate-btn').prop('disabled', false).html('Tính lương');
                }
            });
        });
        
        try {
            // Biểu đồ phân bổ lương theo phòng ban
            var distributionCtx = document.getElementById('salaryDistributionChart');
            if (distributionCtx) {
                distributionCtx = distributionCtx.getContext('2d');
                
                // Chuẩn bị dữ liệu từ thống kê
                var deptLabels = [];
                var deptData = [];
                var deptColors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d', '#6f42c1', '#fd7e14', '#20c997', '#6610f2'];
                
                @if(!empty($statistics['department_stats']))
                    @foreach($statistics['department_stats'] as $index => $dept)
                        deptLabels.push('{{ $dept['name'] }}');
                        deptData.push({{ $dept['total_salary'] }});
                    @endforeach
                @endif
                
                var distributionChart = new Chart(distributionCtx, {
                    type: 'doughnut',
                    data: {
                        labels: deptLabels.length > 0 ? deptLabels : ['Không có dữ liệu'],
                        datasets: [
                            {
                                data: deptData.length > 0 ? deptData : [100],
                                backgroundColor: deptColors
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });
            }
            
            // Biểu đồ lương trung bình theo phòng ban
            var avgSalaryCtx = document.getElementById('averageSalaryChart');
            if (avgSalaryCtx) {
                avgSalaryCtx = avgSalaryCtx.getContext('2d');
                var avgSalaryData = [];
                
                @if(!empty($statistics['department_stats']))
                    @foreach($statistics['department_stats'] as $dept)
                        avgSalaryData.push({{ $dept['avg_salary'] / 1000000 }}); // Đổi ra đơn vị triệu
                    @endforeach
                @endif
                
                var avgSalaryChart = new Chart(avgSalaryCtx, {
                    type: 'bar',
                    data: {
                        labels: deptLabels.length > 0 ? deptLabels : ['Không có dữ liệu'],
                        datasets: [
                            {
                                label: 'Lương trung bình (triệu VNĐ)',
                                backgroundColor: '#28a745',
                                borderColor: '#28a745',
                                data: avgSalaryData.length > 0 ? avgSalaryData : [0]
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        } catch (e) {
            console.error("Chart error:", e);
        }
        
        // Định dạng tiền tệ
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
        }
    });
</script>
@endpush