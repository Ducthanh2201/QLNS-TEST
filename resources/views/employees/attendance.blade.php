@extends('layouts_employees.employee')

@section('title', 'Lịch sử chấm công')

@section('page-title', 'Lịch sử chấm công')

@section('breadcrumb')
    <li class="breadcrumb-item active">Lịch sử chấm công</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lịch sử chấm công tháng {{ $month }}/{{ $year }}</h3>
                <div class="card-tools">
                    <form action="{{ route('employee.attendance') }}" method="GET" class="form-inline">
                        <div class="input-group input-group-sm">
                            <select name="month" class="form-control mr-1">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                                @endfor
                            </select>
                            <select name="year" class="form-control mr-1">
                                @for($i = date('Y') - 2; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->
            
            <!-- Summary -->
            <div class="card-body border-bottom">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="far fa-calendar-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Đi làm đúng giờ</span>
                                <span class="info-box-number">{{ $workdays }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="far fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Đi trễ/Về sớm</span>
                                <span class="info-box-number">{{ $latedays + $earlydays }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-calendar-minus"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Nghỉ phép</span>
                                <span class="info-box-number">{{ $leavedays }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-calendar-times"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Vắng mặt</span>
                                <span class="info-box-number">{{ $absentdays }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Giờ vào</th>
                                <th>Giờ ra</th>
                                <th>Tổng giờ</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($attendances->count() > 0)
                                @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->Ngay }}/{{ $attendance->Thang }}/{{ $attendance->Nam }}</td>
                                    <td>
                                        @if($attendance->Giovao && $attendance->Phutvao !== null)
                                            {{ sprintf('%02d:%02d', $attendance->Giovao, $attendance->Phutvao) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->GioRa && $attendance->PhutRa !== null)
                                            {{ sprintf('%02d:%02d', $attendance->GioRa, $attendance->PhutRa) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $attendance->work_hours['formatted'] ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $attendance->attendance_status_class }}">
                                            {{ $attendance->attendance_status_text }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->GhiChu ?? '-' }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">Không có dữ liệu chấm công trong tháng này</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{ $attendances->links() }}
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection