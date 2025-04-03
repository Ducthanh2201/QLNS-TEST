@extends('layouts.admin')

@section('title', 'Thống kê nhân sự')

@section('page-title', 'Thống kê nhân sự')

@section('breadcrumb')
    <li class="breadcrumb-item active">Thống kê</li>
@endsection

@section('content')
<!-- Bộ lọc thống kê -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
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
                                <input type="text" class="form-control" id="dateRange" value="01/01/2025 - 03/04/2025">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Phòng ban:</label>
                            <select class="form-control select2" id="departmentFilter">
                                <option value="0" selected>Tất cả phòng ban</option>
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
                            <label>Chức vụ:</label>
                            <select class="form-control select2" id="positionFilter">
                                <option value="0" selected>Tất cả chức vụ</option>
                                <option value="1">Giám đốc</option>
                                <option value="2">Phó giám đốc</option>
                                <option value="3">Trưởng phòng</option>
                                <option value="4">Phó phòng</option>
                                <option value="5">Trưởng nhóm</option>
                                <option value="6">Nhân viên</option>
                                <option value="7">Thực tập sinh</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-group mb-0 w-100">
                            <button type="button" class="btn btn-primary btn-block">
                                <i class="fas fa-filter"></i> Lọc dữ liệu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê tổng quan -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
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
        <div class="small-box bg-success">
            <div class="inner">
                <h3>12<sup style="font-size: 20px">%</sup></h3>
                <p>Tỷ lệ tăng trưởng</p>
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
                <h3>15</h3>
                <p>Nhân viên mới</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="#" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>7</h3>
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
                <h3 class="card-title">Biến động nhân sự trong năm 2025</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="employeeTrendChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
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
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="departmentPieChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
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
                        <a class="nav-link" id="hr-stats-performance-tab" data-toggle="pill" href="#hr-stats-performance" role="tab" aria-controls="hr-stats-performance" aria-selected="false">Hiệu suất</a>
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
                                                <h3 class="card-title">Theo giới tính</h3>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="genderChart" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Theo độ tuổi</h3>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="ageChart" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Theo trình độ học vấn</h3>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="educationChart" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Theo thâm niên</h3>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="tenureChart" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-5">
                                <h5 class="mb-3">Số liệu chi tiết</h5>
                                <div class="card">
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Phòng ban</th>
                                                    <th>Tổng</th>
                                                    <th>Nam</th>
                                                    <th>Nữ</th>
                                                    <th>Độ tuổi TB</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Kỹ thuật</td>
                                                    <td>42</td>
                                                    <td>35</td>
                                                    <td>7</td>
                                                    <td>32</td>
                                                </tr>
                                                <tr>
                                                    <td>Kinh doanh</td>
                                                    <td>30</td>
                                                    <td>12</td>
                                                    <td>18</td>
                                                    <td>29</td>
                                                </tr>
                                                <tr>
                                                    <td>Nhân sự</td>
                                                    <td>12</td>
                                                    <td>4</td>
                                                    <td>8</td>
                                                    <td>35</td>
                                                </tr>
                                                <tr>
                                                    <td>Marketing</td>
                                                    <td>18</td>
                                                    <td>7</td>
                                                    <td>11</td>
                                                    <td>28</td>
                                                </tr>
                                                <tr>
                                                    <td>Tài chính</td>
                                                    <td>15</td>
                                                    <td>6</td>
                                                    <td>9</td>
                                                    <td>33</td>
                                                </tr>
                                                <tr>
                                                    <td>Hành chính</td>
                                                    <td>10</td>
                                                    <td>3</td>
                                                    <td>7</td>
                                                    <td>31</td>
                                                </tr>
                                                <tr>
                                                    <td>IT</td>
                                                    <td>20</td>
                                                    <td>16</td>
                                                    <td>4</td>
                                                    <td>30</td>
                                                </tr>
                                                <tr>
                                                    <td>Pháp lý</td>
                                                    <td>5</td>
                                                    <td>2</td>
                                                    <td>3</td>
                                                    <td>36</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Tổng cộng</th>
                                                    <th>152</th>
                                                    <th>85</th>
                                                    <th>67</th>
                                                    <th>31.75</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="card card-success mt-3">
                                    <div class="card-header">
                                        <h3 class="card-title">Tỷ lệ Quản lý/Nhân viên</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="progress-group">
                                            <span class="progress-text">Quản lý cấp cao</span>
                                            <span class="float-right"><b>3</b>/152</span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-danger" style="width: 2%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="progress-group">
                                            <span class="progress-text">Trưởng/Phó phòng</span>
                                            <span class="float-right"><b>16</b>/152</span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-warning" style="width: 10.5%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="progress-group">
                                            <span class="progress-text">Trưởng nhóm</span>
                                            <span class="float-right"><b>25</b>/152</span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-primary" style="width: 16.5%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="progress-group">
                                            <span class="progress-text">Nhân viên</span>
                                            <span class="float-right"><b>108</b>/152</span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-success" style="width: 71%"></div>
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
                                        <canvas id="attendanceChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Tổng hợp chuyên cần</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Chỉ số</th>
                                                    <th>Giá trị</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Tỷ lệ đi làm trung bình</td>
                                                    <td>95.8%</td>
                                                </tr>
                                                <tr>
                                                    <td>Tỷ lệ đi muộn</td>
                                                    <td>4.2%</td>
                                                </tr>
                                                <tr>
                                                    <td>Tỷ lệ về sớm</td>
                                                    <td>3.5%</td>
                                                </tr>
                                                <tr>
                                                    <td>Nghỉ phép trung bình</td>
                                                    <td>1.2 ngày/người</td>
                                                </tr>
                                                <tr>
                                                    <td>Nghỉ không phép</td>
                                                    <td>0.3 ngày/người</td>
                                                </tr>
                                                <tr>
                                                    <td>Tăng ca trung bình</td>
                                                    <td>5.6 giờ/người</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="card card-info mt-3">
                                    <div class="card-header">
                                        <h3 class="card-title">Top 5 phòng ban chuyên cần</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Phòng ban</th>
                                                    <th>Tỷ lệ</th>
                                                    <th style="width: 40%">Đánh giá</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Nhân sự</td>
                                                    <td>98.7%</td>
                                                    <td>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-success" style="width: 98.7%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tài chính</td>
                                                    <td>97.9%</td>
                                                    <td>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-success" style="width: 97.9%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Pháp lý</td>
                                                    <td>97.5%</td>
                                                    <td>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-success" style="width: 97.5%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Hành chính</td>
                                                    <td>96.8%</td>
                                                    <td>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-success" style="width: 96.8%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Kỹ thuật</td>
                                                    <td>96.2%</td>
                                                    <td>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-success" style="width: 96.2%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
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
                                        <h3 class="card-title">Chi phí nhân sự theo tháng</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="salaryTrendChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Phân bổ chi phí nhân sự</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="salaryCostChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Lương trung bình theo phòng ban</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Phòng ban</th>
                                                    <th>Lương trung bình</th>
                                                    <th>Lương thấp nhất</th>
                                                    <th>Lương cao nhất</th>
                                                    <th>Tổng chi phí</th>
                                                    <th>Tỷ lệ chi phí</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Kỹ thuật</td>
                                                    <td>16.500.000 VND</td>
                                                    <td>8.000.000 VND</td>
                                                    <td>35.000.000 VND</td>
                                                    <td>693.000.000 VND</td>
                                                    <td>28.7%</td>
                                                </tr>
                                                <tr>
                                                    <td>Kinh doanh</td>
                                                    <td>18.600.000 VND</td>
                                                    <td>10.000.000 VND</td>
                                                    <td>38.000.000 VND</td>
                                                    <td>558.000.000 VND</td>
                                                    <td>23.1%</td>
                                                </tr>
                                                <tr>
                                                    <td>Nhân sự</td>
                                                    <td>14.800.000 VND</td>
                                                    <td>9.000.000 VND</td>
                                                    <td>28.000.000 VND</td>
                                                    <td>177.600.000 VND</td>
                                                    <td>7.4%</td>
                                                </tr>
                                                <tr>
                                                    <td>Marketing</td>
                                                    <td>15.300.000 VND</td>
                                                    <td>8.500.000 VND</td>
                                                    <td>30.000.000 VND</td>
                                                    <td>275.400.000 VND</td>
                                                    <td>11.4%</td>
                                                </tr>
                                                <tr>
                                                    <td>Tài chính</td>
                                                    <td>17.200.000 VND</td>
                                                    <td>9.500.000 VND</td>
                                                    <td>32.000.000 VND</td>
                                                    <td>258.000.000 VND</td>
                                                    <td>10.7%</td>
                                                </tr>
                                                <tr>
                                                    <td>Hành chính</td>
                                                    <td>12.500.000 VND</td>
                                                    <td>8.000.000 VND</td>
                                                    <td>25.000.000 VND</td>
                                                    <td>125.000.000 VND</td>
                                                    <td>5.2%</td>
                                                </tr>
                                                <tr>
                                                    <td>IT</td>
                                                    <td>18.900.000 VND</td>
                                                    <td>11.000.000 VND</td>
                                                    <td>35.000.000 VND</td>
                                                    <td>378.000.000 VND</td>
                                                    <td>15.7%</td>
                                                </tr>
                                                <tr>
                                                    <td>Pháp lý</td>
                                                    <td>19.600.000 VND</td>
                                                    <td>12.000.000 VND</td>
                                                    <td>40.000.000 VND</td>
                                                    <td>98.000.000 VND</td>
                                                    <td>4.1%</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Tổng cộng</th>
                                                    <th>16.675.000 VND</th>
                                                    <th>8.000.000 VND</th>
                                                    <th>40.000.000 VND</th>
                                                    <th>2.414.600.000 VND</th>
                                                    <th>100%</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hiệu suất -->
                    <div class="tab-pane fade" id="hr-stats-performance" role="tabpanel" aria-labelledby="hr-stats-performance-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Phân bố điểm đánh giá hiệu suất</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="performanceChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Điểm hiệu suất trung bình theo phòng ban</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="departmentPerformanceChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Top 5 nhân viên xuất sắc</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px">#</th>
                                                    <th>Nhân viên</th>
                                                    <th>Phòng ban</th>
                                                    <th>Điểm</th>
                                                    <th style="width: 40%">Đánh giá</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Nguyễn Văn A</td>
                                                    <td>Kinh doanh</td>
                                                    <td>98</td>
                                                    <td>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-success" style="width: 98%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Trần Thị B</td>
                                                    <td>Kỹ thuật</td>
                                                    <td>96</td>
                                                    <td>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-success" style="width: 96%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Lê Văn C</td>
                                                    <td>IT</td>
                                                    <td>95</td>
                                                    <td>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-success" style="width: 95%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>4.</td>
                                                    <td>Phạm Thị D</td>
                                                    <td>Marketing</td>
                                                    <td>94</td>
                                                    <td>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-success" style="width: 94%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>5.</td>
                                                    <td>Hoàng Văn E</td>
                                                    <td>Tài chính</td>
                                                    <td>92</td>
                                                    <td>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-success" style="width: 92%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Thống kê mục tiêu KPI</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Đạt mục tiêu</span>
                                                        <span class="info-box-number">87%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Đang thực hiện</span>
                                                        <span class="info-box-number">9%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Không đạt</span>
                                                        <span class="info-box-number">4%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-info"><i class="fas fa-trophy"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Vượt chỉ tiêu</span>
                                                        <span class="info-box-number">45%</span>
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
                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Báo cáo nhân sự tổng hợp</span>
                                <div class="mt-2">
                                    <a href="#" class="btn btn-sm btn-light">
                                        <i class="fas fa-download"></i> Tải về
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-gradient-success">
                            <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Báo cáo KPI Q1/2025</span>
                                <div class="mt-2">
                                    <a href="#" class="btn btn-sm btn-light">
                                        <i class="fas fa-download"></i> Tải về
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-gradient-warning">
                            <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Báo cáo chi phí nhân sự</span>
                                <div class="mt-2">
                                    <a href="#" class="btn btn-sm btn-light">
                                        <i class="fas fa-download"></i> Tải về
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-gradient-danger">
                            <span class="info-box-icon"><i class="fas fa-user-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Báo cáo chuyên cần Q1/2025</span>
                                <div class="mt-2">
                                    <a href="#" class="btn btn-sm btn-light">
                                        <i class="fas fa-download"></i> Tải về
                                    </a>
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
<script>
    $(function () {
        // Date range picker
        $('#dateRange').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        
        // Initialize select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        
        // Employee trend chart
        var employeeTrendCtx = document.getElementById('employeeTrendChart').getContext('2d');
        var employeeTrendChart = new Chart(employeeTrendCtx, {
            type: 'line',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
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
                        data: [136, 138, 140, 145, 152, 0, 0, 0, 0, 0, 0, 0]
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
                        data: [5, 4, 6, 8, 15, 0, 0, 0, 0, 0, 0, 0]
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
                        data: [3, 2, 4, 3, 7, 0, 0, 0, 0, 0, 0, 0]
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        
        // Department pie chart
        var departmentPieCtx = document.getElementById('departmentPieChart').getContext('2d');
        var departmentPieChart = new Chart(departmentPieCtx, {
            type: 'pie',
            data: {
                labels: ['Kỹ thuật', 'Kinh doanh', 'Nhân sự', 'Marketing', 'Tài chính', 'Hành chính', 'IT', 'Pháp lý'],
                datasets: [
                    {
                        data: [42, 30, 12, 18, 15, 10, 20, 5],
                        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d', '#6f42c1', '#fd7e14']
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
            }
        });
        
        // Gender chart
        var genderCtx = document.getElementById('genderChart').getContext('2d');
        var genderChart = new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Nam', 'Nữ'],
                datasets: [
                    {
                        data: [85, 67],
                        backgroundColor: ['#007bff', '#dc3545']
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
            }
        });
        
        // Age chart
        var ageCtx = document.getElementById('ageChart').getContext('2d');
        var ageChart = new Chart(ageCtx, {
            type: 'bar',
            data: {
                labels: ['18-25', '26-30', '31-35', '36-40', '41-50', '50+'],
                datasets: [
                    {
                        label: 'Số lượng',
                        backgroundColor: '#17a2b8',
                        data: [35, 42, 38, 22, 12, 3]
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        
        // Education chart
        var educationCtx = document.getElementById('educationChart').getContext('2d');
        var educationChart = new Chart(educationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Đại học', 'Cao đẳng', 'Trung cấp', 'Sau đại học', 'Khác'],
                datasets: [
                    {
                        data: [93, 24, 12, 18, 5],
                        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#6f42c1', '#6c757d']
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
            }
        });
        
        // Tenure chart
        var tenureCtx = document.getElementById('tenureChart').getContext('2d');
        var tenureChart = new Chart(tenureCtx, {
            type: 'bar',
            data: {
                labels: ['< 1 năm', '1-2 năm', '2-3 năm', '3-5 năm', '> 5 năm'],
                datasets: [
                    {
                        label: 'Số lượng',
                        backgroundColor: '#28a745',
                        data: [38, 45, 32, 25, 12]
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        
        // Attendance chart
        var attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        var attendanceChart = new Chart(attendanceCtx, {
            type: 'line',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4'],
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
                        data: [95.2, 96.1, 96.4, 95.8]
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
                        data: [4.8, 4.5, 4.0, 4.2]
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
                        data: [3.8, 3.2, 3.1, 4.0]
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
        
        // Salary trend chart
        var salaryTrendCtx = document.getElementById('salaryTrendChart').getContext('2d');
        var salaryTrendChart = new Chart(salaryTrendCtx, {
            type: 'bar',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4'],
                datasets: [
                    {
                        label: 'Chi phí lương (triệu VND)',
                        backgroundColor: '#17a2b8',
                        borderColor: '#17a2b8',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: [2250, 2320, 2380, 2414]
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
        
        // Salary cost chart
        var salaryCostCtx = document.getElementById('salaryCostChart').getContext('2d');
        var salaryCostChart = new Chart(salaryCostCtx, {
            type: 'pie',
            data: {
                labels: ['Lương cơ bản', 'Phụ cấp', 'Thưởng', 'Làm thêm giờ', 'Khác'],
                datasets: [
                    {
                        data: [65, 12, 15, 5, 3],
                        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d']
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
            }
        });
        
        // Performance chart
        var performanceCtx = document.getElementById('performanceChart').getContext('2d');
        var performanceChart = new Chart(performanceCtx, {
            type: 'bar',
            data: {
                labels: ['Xuất sắc (90-100)', 'Tốt (80-89)', 'Khá (70-79)', 'Trung bình (60-69)', 'Cần cải thiện (<60)'],
                datasets: [
                    {
                        label: 'Số lượng nhân viên',
                        backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#fd7e14', '#dc3545'],
                        data: [32, 65, 42, 10, 3]
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        
        // Department performance chart
        var departmentPerformanceCtx = document.getElementById('departmentPerformanceChart').getContext('2d');
        var departmentPerformanceChart = new Chart(departmentPerformanceCtx, {
            type: 'horizontalBar',
            data: {
                labels: ['Kỹ thuật', 'Kinh doanh', 'Nhân sự', 'Marketing', 'Tài chính', 'Hành chính', 'IT', 'Pháp lý'],
                datasets: [
                    {
                        label: 'Điểm trung bình',
                        backgroundColor: '#28a745',
                        data: [82, 87, 84, 81, 83, 79, 86, 85]
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    xAxes: [{
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