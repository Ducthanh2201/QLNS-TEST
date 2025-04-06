@extends('layouts.admin')

@section('title', 'Thống kê nhân sự')

@section('page-title', 'Thống kê nhân sự')

@section('breadcrumb')
    <li class="breadcrumb-item active">Thống kê</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css">
<style>
    .progress {
        height: 20px;
    }
    .progress-bar {
        line-height: 20px;
    }
    .stat-card {
        transition: all 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
    }
    .nav-tabs .nav-link.active {
        font-weight: bold;
        border-top: 3px solid #007bff;
    }
    .table-stats th {
        background-color: #f4f6f9;
    }
    .chart-container {
        position: relative;
        min-height: 300px;
    }
</style>
@endpush

@section('content')
<!-- Bộ lọc thống kê -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.statistics.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Khoảng thời gian:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" id="dateRange" name="date_range" 
                                        value="{{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}">
                                    <input type="hidden" name="start_date" id="startDate" value="{{ $startDate->format('d/m/Y') }}">
                                    <input type="hidden" name="end_date" id="endDate" value="{{ $endDate->format('d/m/Y') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Phòng ban:</label>
                                <select class="form-control select2" id="departmentFilter" name="department_id">
                                    <option value="" selected>Tất cả phòng ban</option>
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
                                <label>Chức vụ:</label>
                                <select class="form-control select2" id="positionFilter" name="position_id">
                                    <option value="" selected>Tất cả chức vụ</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->IDCV }}" {{ request('position_id') == $position->IDCV ? 'selected' : '' }}>
                                            {{ $position->TenCV }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group mb-0 w-100">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Lọc dữ liệu
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê tổng quan -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info stat-card">
            <div class="inner">
                <h3>{{ number_format($totalEmployees) }}</h3>
                <p>Tổng số nhân viên</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.employees.index') }}" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success stat-card">
            <div class="inner">
                <h3>{{ $growthRate }}<sup style="font-size: 20px">%</sup></h3>
                <p>Tỷ lệ tăng trưởng</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning stat-card">
            <div class="inner">
                <h3>{{ number_format($newEmployees) }}</h3>
                <p>Nhân viên mới</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger stat-card">
            <div class="inner">
                <h3>{{ number_format($leftEmployees) }}</h3>
                <p>Nhân viên nghỉ việc</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-minus"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- Biểu đồ xu hướng -->
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Biến động nhân sự trong năm {{ now()->year }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="employeeTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Tỷ lệ nhân viên theo phòng ban</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="departmentPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê nhân sự chi tiết -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="hr-stats-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="hr-stats-demographic-tab" data-toggle="pill" href="#hr-stats-demographic" role="tab" aria-controls="hr-stats-demographic" aria-selected="true">Cơ cấu nhân sự</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="hr-stats-attendance-tab" data-toggle="pill" href="#hr-stats-attendance" role="tab" aria-controls="hr-stats-attendance" aria-selected="false">Chuyên cần</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="hr-stats-salary-tab" data-toggle="pill" href="#hr-stats-salary" role="tab" aria-controls="hr-stats-salary" aria-selected="false">Lương và chi phí</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="hr-stats-performance-tab" data-toggle="pill" href="#hr-stats-performance" role="tab" aria-controls="hr-stats-performance" aria-selected="false">Khen thưởng & Kỷ luật</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="hr-stats-tabContent">
                    <!-- Cơ cấu nhân sự -->
                    <div class="tab-pane fade show active" id="hr-stats-demographic" role="tabpanel" aria-labelledby="hr-stats-demographic-tab">
                        <div class="row">
                            <div class="col-md-7">
                                <h5 class="mb-3">Phân bố nhân sự theo các tiêu chí</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Phân bố theo giới tính</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="chart-container">
                                                    <canvas id="genderChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Phân bố theo độ tuổi</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="chart-container">
                                                    <canvas id="ageChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Trình độ học vấn</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="chart-container">
                                                    <canvas id="educationChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Thâm niên công tác</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="chart-container">
                                                    <canvas id="tenureChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-5">
                                <h5 class="mb-3">Số liệu chi tiết</h5>
                                <div class="card">
                                    <div class="card-body p-0">
                                        <table class="table table-striped table-stats">
                                            <thead>
                                                <tr>
                                                    <th>Phòng ban</th>
                                                    <th>Số nhân viên</th>
                                                    <th>Tỷ lệ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $totalEmp = count($departmentStats) > 0 ? array_sum(array_column($departmentStats, 'count')) : 0 @endphp
                                                @foreach($departmentStats as $dept)
                                                <tr>
                                                    <td>{{ $dept['name'] }}</td>
                                                    <td>{{ $dept['count'] }}</td>
                                                    <td>
                                                        @if($totalEmp > 0)
                                                            {{ number_format(($dept['count'] / $totalEmp) * 100, 1) }}%
                                                        @else
                                                            0%
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="card card-success mt-3">
                                    <div class="card-header">
                                        <h3 class="card-title">Chỉ số nhân sự chính</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-box bg-light">
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Tổng số nhân viên</span>
                                                        <span class="info-box-number">{{ number_format($totalEmployees) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-box bg-light">
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Nhân viên mới</span>
                                                        <span class="info-box-number">{{ number_format($newEmployees) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chuyên cần -->
                    <div class="tab-pane fade" id="hr-stats-attendance" role="tabpanel" aria-labelledby="hr-stats-attendance-tab">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Tỷ lệ chuyên cần theo tháng</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="attendanceChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Top 5 nhân viên vắng mặt nhiều nhất</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nhân viên</th>
                                                    <th>Phòng ban</th>
                                                    <th>Số ngày vắng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($attendanceStats['top_absent_employees']))
                                                    @foreach($attendanceStats['top_absent_employees'] as $employee)
                                                    <tr>
                                                        <td>{{ $employee->employee_name ?? 'N/A' }}</td>
                                                        <td>{{ $employee->department ?? 'N/A' }}</td>
                                                        <td>{{ $employee->absent_count ?? 0 }}</td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lương và chi phí -->
                    <div class="tab-pane fade" id="hr-stats-salary" role="tabpanel" aria-labelledby="hr-stats-salary-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Chi phí lương theo tháng</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="salaryTrendChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Cơ cấu chi phí lương</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="salaryCostChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Khen thưởng & Kỷ luật -->
                    <div class="tab-pane fade" id="hr-stats-performance" role="tabpanel" aria-labelledby="hr-stats-performance-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Thống kê khen thưởng/kỷ luật theo tháng</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="rewardPenaltyChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Tổng quan khen thưởng/kỷ luật</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-box bg-success">
                                                    <span class="info-box-icon"><i class="fas fa-award"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Tổng khen thưởng</span>
                                                        <span class="info-box-number">{{ $rewardPenaltyStats['total_rewards'] ?? 0 }} lần</span>
                                                        <span class="info-box-number">{{ number_format($rewardPenaltyStats['total_reward_amount'] ?? 0, 0, ',', '.') }} VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-box bg-danger">
                                                    <span class="info-box-icon"><i class="fas fa-gavel"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Tổng kỷ luật</span>
                                                        <span class="info-box-number">{{ $rewardPenaltyStats['total_penalties'] ?? 0 }} lần</span>
                                                        <span class="info-box-number">{{ number_format($rewardPenaltyStats['total_penalty_amount'] ?? 0, 0, ',', '.') }} VNĐ</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Top nhân viên được khen thưởng</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nhân viên</th>
                                                    <th>Phòng ban</th>
                                                    <th>Số lần</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($rewardPenaltyStats['top_rewarded_employees']))
                                                    @foreach($rewardPenaltyStats['top_rewarded_employees'] as $employee)
                                                    <tr>
                                                        <td>{{ $employee->employee_name ?? 'N/A' }}</td>
                                                        <td>{{ $employee->department ?? 'N/A' }}</td>
                                                        <td>{{ $employee->reward_count ?? 0 }}</td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Top nhân viên bị kỷ luật</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nhân viên</th>
                                                    <th>Phòng ban</th>
                                                    <th>Số lần</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($rewardPenaltyStats['top_penalized_employees']))
                                                    @foreach($rewardPenaltyStats['top_penalized_employees'] as $employee)
                                                    <tr>
                                                        <td>{{ $employee->employee_name ?? 'N/A' }}</td>
                                                        <td>{{ $employee->department ?? 'N/A' }}</td>
                                                        <td>{{ $employee->penalty_count ?? 0 }}</td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Các báo cáo có sẵn -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Báo cáo có sẵn</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-gradient-info">
                            <span class="info-box-icon"><i class="fas fa-file-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Báo cáo nhân sự</span>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-light"><i class="fas fa-download"></i> PDF</button>
                                    <button class="btn btn-sm btn-light"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-gradient-success">
                            <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Báo cáo tăng trưởng</span>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-light"><i class="fas fa-download"></i> PDF</button>
                                    <button class="btn btn-sm btn-light"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-gradient-warning">
                            <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Báo cáo lương</span>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-light"><i class="fas fa-download"></i> PDF</button>
                                    <button class="btn btn-sm btn-light"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-gradient-danger">
                            <span class="info-box-icon"><i class="fas fa-user-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Báo cáo chuyên cần</span>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-light"><i class="fas fa-download"></i> PDF</button>
                                    <button class="btn btn-sm btn-light"><i class="fas fa-file-excel"></i> Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.js"></script>

<script>
    $(function () {
        // Date range picker
        $('#dateRange').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            },
            ranges: {
                'Hôm nay': [moment(), moment()],
                'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Quý này': [moment().startOf('quarter'), moment().endOf('quarter')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')]
            }
        }, function(start, end, label) {
            $('#startDate').val(start.format('DD/MM/YYYY'));
            $('#endDate').val(end.format('DD/MM/YYYY'));
        });
        
        // Initialize select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        
        // Khởi tạo các biểu đồ với kiểm tra sự tồn tại của phần tử canvas
        try {
            // Employee trend chart
            if (document.getElementById('employeeTrendChart')) {
                var employeeTrendCtx = document.getElementById('employeeTrendChart').getContext('2d');
                var employeeTrendChart = new Chart(employeeTrendCtx, {
                    type: 'line',
                    data: {
                        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                        datasets: [
                            {
                                label: 'Tổng nhân viên',
                                backgroundColor: 'rgba(60,141,188,0.2)',
                                borderColor: '#3c8dbc',
                                pointRadius: 3,
                                pointColor: '#3c8dbc',
                                pointStrokeColor: 'rgba(60,141,188,1)',
                                pointHighlightFill: '#fff',
                                pointHighlightStroke: 'rgba(60,141,188,1)',
                                data: [136, 138, 140, 145, 152, 156, 158, 158, 160, 162, 164, 165]
                            },
                            {
                                label: 'Nhân viên mới',
                                backgroundColor: 'rgba(40,167,69,0.2)',
                                borderColor: '#28a745',
                                pointRadius: 3,
                                pointColor: '#28a745',
                                pointStrokeColor: '#28a745',
                                pointHighlightFill: '#fff',
                                pointHighlightStroke: '#28a745',
                                data: [5, 4, 6, 8, 15, 8, 6, 3, 5, 4, 6, 3]
                            },
                            {
                                label: 'Nghỉ việc',
                                backgroundColor: 'rgba(220,53,69,0.2)',
                                borderColor: '#dc3545',
                                pointRadius: 3,
                                pointColor: '#dc3545',
                                pointStrokeColor: '#dc3545',
                                pointHighlightFill: '#fff',
                                pointHighlightStroke: '#dc3545',
                                data: [3, 2, 4, 3, 7, 4, 4, 3, 3, 2, 4, 2]
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            
            // Department pie chart
            if (document.getElementById('departmentPieChart')) {
                var departmentPieCtx = document.getElementById('departmentPieChart').getContext('2d');
                var departmentNames = [];
                var departmentCounts = [];
                var deptColors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d', '#6f42c1', '#fd7e14'];
                
                @foreach($departmentStats as $index => $dept)
                    departmentNames.push("{{ $dept['name'] }}");
                    departmentCounts.push({{ $dept['count'] }});
                @endforeach
                
                var departmentPieChart = new Chart(departmentPieCtx, {
                    type: 'pie',
                    data: {
                        labels: departmentNames,
                        datasets: [
                            {
                                data: departmentCounts,
                                backgroundColor: deptColors
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                    }
                });
            }
            
            // Gender chart
            if (document.getElementById('genderChart')) {
                var genderCtx = document.getElementById('genderChart').getContext('2d');
                var genderChart = new Chart(genderCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Nam', 'Nữ'],
                        datasets: [
                            {
                                data: [{{ $genderStats['male'] }}, {{ $genderStats['female'] }}],
                                backgroundColor: ['#007bff', '#dc3545']
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                    }
                });
            }
            
            // Age chart
            if (document.getElementById('ageChart')) {
                var ageCtx = document.getElementById('ageChart').getContext('2d');
                var ageChart = new Chart(ageCtx, {
                    type: 'bar',
                    data: {
                        labels: ['18-25', '26-30', '31-35', '36-40', '41-50', '50+'],
                        datasets: [
                            {
                                label: 'Số lượng',
                                backgroundColor: '#17a2b8',
                                data: [
                                    {{ $ageStats['18-25'] }}, 
                                    {{ $ageStats['26-30'] }}, 
                                    {{ $ageStats['31-35'] }}, 
                                    {{ $ageStats['36-40'] }}, 
                                    {{ $ageStats['41-50'] }}, 
                                    {{ $ageStats['50+'] }}
                                ]
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            
            // Education chart
            if (document.getElementById('educationChart')) {
                var educationCtx = document.getElementById('educationChart').getContext('2d');
                var educationChart = new Chart(educationCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Đại học', 'Cao đẳng', 'Trung cấp', 'Sau đại học', 'Khác'],
                        datasets: [
                            {
                                data: [
                                    {{ $educationStats['university'] }}, 
                                    {{ $educationStats['college'] }}, 
                                    {{ $educationStats['vocational'] }}, 
                                    {{ $educationStats['postgrad'] }}, 
                                    {{ $educationStats['other'] }}
                                ],
                                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#6f42c1', '#6c757d']
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                    }
                });
            }
            
            // Tenure chart
            if (document.getElementById('tenureChart')) {
                var tenureCtx = document.getElementById('tenureChart').getContext('2d');
                var tenureChart = new Chart(tenureCtx, {
                    type: 'bar',
                    data: {
                        labels: ['< 1 năm', '1-2 năm', '2-3 năm', '3-5 năm', '> 5 năm'],
                        datasets: [
                            {
                                label: 'Số lượng',
                                backgroundColor: '#28a745',
                                data: [
                                    {{ $tenureStats['less_than_1_year'] }}, 
                                    {{ $tenureStats['1_to_2_years'] }}, 
                                    {{ $tenureStats['2_to_3_years'] }}, 
                                    {{ $tenureStats['3_to_5_years'] }}, 
                                    {{ $tenureStats['more_than_5_years'] }}
                                ]
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            
            // Attendance chart
            if (document.getElementById('attendanceChart')) {
                var attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
                var monthLabels = [];
                var attendanceRates = [];
                var lateRates = [];
                var absentRates = [];
                
                @if(isset($attendanceStats['months']))
                    @foreach($attendanceStats['months'] as $index => $month)
                        monthLabels.push("{{ $month }}");
                        @if(isset($attendanceStats['attendance_rates'][$index]))
                            attendanceRates.push({{ $attendanceStats['attendance_rates'][$index] }});
                        @endif
                        @if(isset($attendanceStats['late_rates'][$index]))
                            lateRates.push({{ $attendanceStats['late_rates'][$index] }});
                        @endif
                        @if(isset($attendanceStats['absent_rates'][$index]))
                            absentRates.push({{ $attendanceStats['absent_rates'][$index] }});
                        @endif
                    @endforeach
                @endif
                
                if (monthLabels.length === 0) {
                    monthLabels = ['T1', 'T2', 'T3', 'T4'];
                    attendanceRates = [95.2, 96.1, 94.8, 95.5];
                    lateRates = [3.5, 2.9, 4.0, 3.2];
                    absentRates = [1.3, 1.0, 1.2, 1.3];
                }
                
                var attendanceChart = new Chart(attendanceCtx, {
                    type: 'line',
                    data: {
                        labels: monthLabels,
                        datasets: [
                            {
                                label: 'Tỷ lệ đi làm',
                                backgroundColor: 'rgba(40,167,69,0.2)',
                                borderColor: '#28a745',
                                pointRadius: 3,
                                pointColor: '#28a745',
                                pointStrokeColor: '#28a745',
                                pointHighlightFill: '#fff',
                                pointHighlightStroke: '#28a745',
                                data: attendanceRates
                            },
                            {
                                label: 'Đi muộn/Về sớm',
                                backgroundColor: 'rgba(255,193,7,0.2)',
                                borderColor: '#ffc107',
                                pointRadius: 3,
                                pointColor: '#ffc107',
                                pointStrokeColor: '#ffc107',
                                pointHighlightFill: '#fff',
                                pointHighlightStroke: '#ffc107',
                                data: lateRates
                            },
                            {
                                label: 'Vắng mặt',
                                backgroundColor: 'rgba(220,53,69,0.2)',
                                borderColor: '#dc3545',
                                pointRadius: 3,
                                pointColor: '#dc3545',
                                pointStrokeColor: '#dc3545',
                                pointHighlightFill: '#fff',
                                pointHighlightStroke: '#dc3545',
                                data: absentRates
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            }
            
            // Salary trend chart
            if (document.getElementById('salaryTrendChart')) {
                var salaryTrendCtx = document.getElementById('salaryTrendChart').getContext('2d');
                var monthLabels = [];
                var salaryTotals = [];
                
                @if(isset($salaryStats['months']))
                    @foreach($salaryStats['months'] as $index => $month)
                        monthLabels.push("{{ $month }}");
                        @if(isset($salaryStats['total_salaries'][$index]))
                            salaryTotals.push({{ $salaryStats['total_salaries'][$index] }});
                        @endif
                    @endforeach
                @endif
                
                if (monthLabels.length === 0) {
                    monthLabels = ['T1', 'T2', 'T3', 'T4'];
                    salaryTotals = [2250, 2320, 2380, 2414];
                }
                
                var salaryTrendChart = new Chart(salaryTrendCtx, {
                    type: 'bar',
                    data: {
                        labels: monthLabels,
                        datasets: [
                            {
                                label: 'Chi phí lương (triệu VND)',
                                backgroundColor: '#17a2b8',
                                borderColor: '#17a2b8',
                                data: salaryTotals
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
            
            // Salary cost chart
            if (document.getElementById('salaryCostChart')) {
                var salaryCostCtx = document.getElementById('salaryCostChart').getContext('2d');
                var salaryCostChart = new Chart(salaryCostCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Lương cơ bản', 'Phụ cấp', 'Thưởng', 'Làm thêm giờ', 'Khác'],
                        datasets: [
                            {
                                data: [
                                    @if(isset($salaryStats['salary_components']))
                                        {{ $salaryStats['salary_components']['base_salary'] ?? 65 }}, 
                                        {{ $salaryStats['salary_components']['allowance'] ?? 12 }}, 
                                        {{ $salaryStats['salary_components']['reward'] ?? 15 }}, 
                                        {{ $salaryStats['salary_components']['overtime'] ?? 5 }}, 
                                        {{ $salaryStats['salary_components']['other'] ?? 3 }}
                                    @else
                                        65, 12, 15, 5, 3
                                    @endif
                                ],
                                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d']
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                    }
                });
            }
            
            // Reward Penalty chart
            if (document.getElementById('rewardPenaltyChart')) {
                var rewardPenaltyCtx = document.getElementById('rewardPenaltyChart').getContext('2d');
                var monthLabels = [];
                var rewardCounts = [];
                var penaltyCounts = [];
                
                @if(isset($rewardPenaltyStats['months']))
                    @foreach($rewardPenaltyStats['months'] as $index => $month)
                        monthLabels.push("{{ $month }}");
                        @if(isset($rewardPenaltyStats['monthly_rewards'][$index]))
                            rewardCounts.push({{ $rewardPenaltyStats['monthly_rewards'][$index] }});
                        @endif
                        @if(isset($rewardPenaltyStats['monthly_penalties'][$index]))
                            penaltyCounts.push({{ $rewardPenaltyStats['monthly_penalties'][$index] }});
                        @endif
                    @endforeach
                @endif
                
                if (monthLabels.length === 0) {
                    monthLabels = ['T1', 'T2', 'T3', 'T4'];
                    rewardCounts = [8, 12, 15, 10];
                    penaltyCounts = [3, 5, 4, 6];
                }
                
                var rewardPenaltyChart = new Chart(rewardPenaltyCtx, {
                    type: 'bar',
                    data: {
                        labels: monthLabels,
                        datasets: [
                            {
                                label: 'Khen thưởng',
                                backgroundColor: '#28a745',
                                data: rewardCounts
                            },
                            {
                                label: 'Kỷ luật',
                                backgroundColor: '#dc3545',
                                data: penaltyCounts
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            
        } catch (e) {
            console.error("Error initializing charts:", e);
        }
    });
</script>
@endpush