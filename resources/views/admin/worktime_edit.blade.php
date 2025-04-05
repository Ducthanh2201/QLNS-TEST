@extends('layouts.admin')

@section('title', 'Chỉnh sửa giờ làm')

@section('page-title', 'Chỉnh sửa giờ làm')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.worktime.index') }}">Giờ làm</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Thông tin giờ làm</h3>
            </div>
            <form action="{{ route('admin.worktime.update', $timeKeeping->MABC) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="MaNV">Nhân viên <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('MaNV') is-invalid @enderror" id="MaNV" name="MaNV" required>
                                    <option value="">-- Chọn nhân viên --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->MaNV }}" {{ old('MaNV', $timeKeeping->MaNV) == $employee->MaNV ? 'selected' : '' }}>
                                            {{ $employee->TenNV }} - {{ $employee->position->TenCV ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('MaNV')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="date">Ngày <span class="text-danger">*</span></label>
                                <div class="input-group date" id="datePicker" data-target-input="nearest">
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $date) }}" required/>
                                </div>
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="IDLC">Loại công <span class="text-danger">*</span></label>
                                <select class="form-control @error('IDLC') is-invalid @enderror" id="IDLC" name="IDLC" required>
                                    <option value="">-- Chọn loại công --</option>
                                    @foreach($workTypes as $workType)
                                        <option value="{{ $workType->IDLC }}" {{ old('IDLC', $timeKeeping->IDLC) == $workType->IDLC ? 'selected' : '' }}>
                                            {{ $workType->TenLC }} (Hệ số: {{ $workType->HeSo }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('IDLC')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="timeIn">Giờ vào <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="time" class="form-control @error('timeIn') is-invalid @enderror" id="timeIn" name="timeIn" value="{{ old('timeIn', $timeIn) }}" required/>
                                </div>
                                @error('timeIn')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="timeOut">Giờ ra <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="time" class="form-control @error('timeOut') is-invalid @enderror" id="timeOut" name="timeOut" value="{{ old('timeOut', $timeOut) }}" required/>
                                </div>
                                @error('timeOut')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">Trạng thái chấm công <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="{{ \App\Models\TimeKeeping::ATTENDANCE_ONTIME }}" {{ old('status', $timeKeeping->TrangThai) == \App\Models\TimeKeeping::ATTENDANCE_ONTIME ? 'selected' : '' }}>
                                        Đúng giờ
                                    </option>
                                    <option value="{{ \App\Models\TimeKeeping::ATTENDANCE_LATE }}" {{ old('status', $timeKeeping->TrangThai) == \App\Models\TimeKeeping::ATTENDANCE_LATE ? 'selected' : '' }}>
                                        Đi muộn
                                    </option>
                                    <option value="{{ \App\Models\TimeKeeping::ATTENDANCE_EARLY_LEAVE }}" {{ old('status', $timeKeeping->TrangThai) == \App\Models\TimeKeeping::ATTENDANCE_EARLY_LEAVE ? 'selected' : '' }}>
                                        Về sớm
                                    </option>
                                    <option value="{{ \App\Models\TimeKeeping::ATTENDANCE_OVERTIME }}" {{ old('status', $timeKeeping->TrangThai) == \App\Models\TimeKeeping::ATTENDANCE_OVERTIME ? 'selected' : '' }}>
                                        Làm thêm giờ
                                    </option>
                                    <option value="{{ \App\Models\TimeKeeping::ATTENDANCE_ABSENT }}" {{ old('status', $timeKeeping->TrangThai) == \App\Models\TimeKeeping::ATTENDANCE_ABSENT ? 'selected' : '' }}>
                                        Vắng mặt
                                    </option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="TrangThaiChamCong">Phương thức chấm công</label>
                                <select class="form-control" id="TrangThaiChamCong" name="TrangThaiChamCong">
                                    <option value="{{ \App\Models\TimeKeeping::MANUAL_ATTENDANCE }}" {{ old('TrangThaiChamCong', $timeKeeping->TrangThaiChamCong) == \App\Models\TimeKeeping::MANUAL_ATTENDANCE ? 'selected' : '' }}>Chấm công thủ công</option>
                                    <option value="{{ \App\Models\TimeKeeping::AUTO_ATTENDANCE }}" {{ old('TrangThaiChamCong', $timeKeeping->TrangThaiChamCong) == \App\Models\TimeKeeping::AUTO_ATTENDANCE ? 'selected' : '' }}>Chấm công tự động</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="workHoursCalculation" class="alert alert-info">
                            Tổng thời gian làm việc: <strong id="totalWorkHours">{{ $timeKeeping->workHours['hours'] }} giờ {{ $timeKeeping->workHours['minutes'] > 0 ? $timeKeeping->workHours['minutes'] . ' phút' : '' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.worktime.index') }}" class="btn btn-default float-right">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Hiển thị thông báo nếu có lỗi
        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
        
        // Khởi tạo Select2
        $('.select2').select2({
            placeholder: 'Chọn nhân viên...',
            allowClear: true,
            width: '100%'
        });
        
        // Tính tổng thời gian làm việc khi thay đổi giờ vào/ra
        function calculateWorkHours() {
            const timeIn = $('#timeIn').val();
            const timeOut = $('#timeOut').val();
            
            if (timeIn && timeOut) {
                // Chuyển đổi thành phút từ 00:00
                function timeToMinutes(time) {
                    const [hours, minutes] = time.split(':').map(Number);
                    return hours * 60 + (minutes || 0);
                }
                
                let startMinutes = timeToMinutes(timeIn);
                let endMinutes = timeToMinutes(timeOut);
                
                // Nếu giờ ra nhỏ hơn giờ vào, cộng thêm 24h (ca đêm)
                if (endMinutes < startMinutes) {
                    endMinutes += 24 * 60;
                }
                
                const totalMinutes = endMinutes - startMinutes;
                const hours = Math.floor(totalMinutes / 60);
                const minutes = totalMinutes % 60;
                
                $('#totalWorkHours').text(hours + ' giờ ' + (minutes > 0 ? minutes + ' phút' : ''));
                
                // Tự động cập nhật trạng thái dựa vào giờ làm
                if (totalMinutes > 0) {
                    // Chuyển thời gian thành đối tượng Date để so sánh dễ dàng hơn
                    const standardStartTime = new Date();
                    standardStartTime.setHours(8, 15, 0); // 8:15 AM
                    
                    const standardEndTime = new Date();
                    standardEndTime.setHours(17, 0, 0); // 5:00 PM
                    
                    const inTime = new Date();
                    const [inHours, inMinutes] = timeIn.split(':').map(Number);
                    inTime.setHours(inHours, inMinutes, 0);
                    
                    const outTime = new Date();
                    const [outHours, outMinutes] = timeOut.split(':').map(Number);
                    outTime.setHours(outHours, outMinutes, 0);
                    
                    // Reset ngày để chỉ so sánh giờ và phút
                    const today = new Date();
                    standardStartTime.setFullYear(today.getFullYear(), today.getMonth(), today.getDate());
                    standardEndTime.setFullYear(today.getFullYear(), today.getMonth(), today.getDate());
                    inTime.setFullYear(today.getFullYear(), today.getMonth(), today.getDate());
                    outTime.setFullYear(today.getFullYear(), today.getMonth(), today.getDate());
                    
                    // Xác định trạng thái dựa vào giờ vào/ra
                    if (totalMinutes > 9 * 60) { 
                        // Nếu làm việc hơn 9 tiếng
                        $('#status').val({{ \App\Models\TimeKeeping::ATTENDANCE_OVERTIME }});
                    } else if (inTime > standardStartTime) { 
                        // Nếu vào sau 8:15
                        $('#status').val({{ \App\Models\TimeKeeping::ATTENDANCE_LATE }});
                    } else if (outTime < standardEndTime && outTime.getHours() >= 9) { 
                        // Nếu ra trước 17:00 và sau 9:00
                        $('#status').val({{ \App\Models\TimeKeeping::ATTENDANCE_EARLY_LEAVE }});
                    } else {
                        // Đúng giờ
                        $('#status').val({{ \App\Models\TimeKeeping::ATTENDANCE_ONTIME }});
                    }
                } else {
                    // Không có thời gian làm việc = vắng mặt
                    $('#status').val({{ \App\Models\TimeKeeping::ATTENDANCE_ABSENT }});
                }
            }
        }
        
        // Gọi hàm khi trang tải xong - giữ nguyên giá trị đã có
        // Chỉ tính toán khi thay đổi giờ vào/ra
        $('#timeIn, #timeOut').on('change', calculateWorkHours);
        
        // Gắn sự kiện khi chọn loại công
        $('#IDLC').on('change', function() {
            const loaiCongId = $(this).val();
            const currentTimeIn = $('#timeIn').val();
            const currentTimeOut = $('#timeOut').val();
            
            // Chỉ đề xuất thay đổi giờ nếu người dùng chưa nhập hoặc đã xóa giờ trước đó
            if (loaiCongId == '3' && (!currentTimeIn || !currentTimeOut)) { // Giả sử IDLC=3 là làm thêm chủ nhật
                if (confirm('Bạn muốn cập nhật giờ làm cho loại công này?')) {
                    // Đặt giờ làm mặc định là 8 tiếng
                    if (!currentTimeIn) {
                        $('#timeIn').val('08:00');
                    }
                    if (!currentTimeOut) {
                        $('#timeOut').val('17:00');
                    }
                    calculateWorkHours();
                }
            } else if (loaiCongId == '2' && (!currentTimeIn || !currentTimeOut)) { // Giả sử IDLC=2 là làm thêm giờ
                if (confirm('Bạn muốn cập nhật giờ làm thêm giờ?')) {
                    // Đề xuất giờ làm thêm sau giờ hành chính
                    if (!currentTimeIn) {
                        $('#timeIn').val('17:00');
                    }
                    if (!currentTimeOut) {
                        $('#timeOut').val('20:00');
                    }
                    calculateWorkHours();
                }
            }
        });
        
        // Ngăn form submit nếu giờ vào/ra không hợp lệ hoặc có sự không nhất quán
        $('form').on('submit', function(e) {
            const timeIn = $('#timeIn').val();
            const timeOut = $('#timeOut').val();
            
            if (!timeIn || !timeOut) {
                e.preventDefault();
                toastr.error('Vui lòng nhập đầy đủ giờ vào và giờ ra');
                return false;
            }
            
            // Kiểm tra giờ vào/ra hợp lệ
            function timeToMinutes(time) {
                const [hours, minutes] = time.split(':').map(Number);
                return hours * 60 + (minutes || 0);
            }
            
            const startMinutes = timeToMinutes(timeIn);
            let endMinutes = timeToMinutes(timeOut);
            
            if (endMinutes < startMinutes) {
                endMinutes += 24 * 60;
            }
            
            const totalMinutes = endMinutes - startMinutes;
            
            // Nếu có giờ làm việc nhưng trạng thái vẫn là vắng mặt
            if (totalMinutes > 0 && $('#status').val() == {{ \App\Models\TimeKeeping::ATTENDANCE_ABSENT }}) {
                if (!confirm('Nhân viên có giờ làm việc nhưng trạng thái là vắng mặt. Bạn có muốn tiếp tục?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            return true;
        });
        
        // Hiển thị thông tin về trạng thái chấm công hiện tại
        function showAttendanceStatusInfo() {
            const status = $('#status').val();
            let statusText = '';
            let statusClass = '';
            
            switch(parseInt(status)) {
                case {{ \App\Models\TimeKeeping::ATTENDANCE_ONTIME }}:
                    statusText = 'Đúng giờ';
                    statusClass = 'alert-success';
                    break;
                case {{ \App\Models\TimeKeeping::ATTENDANCE_LATE }}:
                    statusText = 'Đi muộn';
                    statusClass = 'alert-warning';
                    break;
                case {{ \App\Models\TimeKeeping::ATTENDANCE_EARLY_LEAVE }}:
                    statusText = 'Về sớm';
                    statusClass = 'alert-warning';
                    break;
                case {{ \App\Models\TimeKeeping::ATTENDANCE_OVERTIME }}:
                    statusText = 'Làm thêm giờ';
                    statusClass = 'alert-info';
                    break;
                case {{ \App\Models\TimeKeeping::ATTENDANCE_ABSENT }}:
                    statusText = 'Vắng mặt';
                    statusClass = 'alert-danger';
                    break;
                default:
                    statusText = 'Không xác định';
                    statusClass = 'alert-secondary';
            }
            
            // Nếu chưa có, thêm một thẻ để hiển thị thông tin
            if ($('#statusInfo').length === 0) {
                $('#workHoursCalculation').after('<div id="statusInfo" class="alert mt-2"></div>');
            }
            
            $('#statusInfo').attr('class', 'alert mt-2 ' + statusClass).text('Trạng thái hiện tại: ' + statusText);
        }
        
        // Hiển thị thông tin trạng thái khi trang tải và khi thay đổi
        showAttendanceStatusInfo();
        $('#status').on('change', showAttendanceStatusInfo);
        
        // Hiển thị cảnh báo khi thay đổi nhân viên
        $('#MaNV').on('change', function() {
            if (confirm('Thay đổi nhân viên sẽ ảnh hưởng đến dữ liệu chấm công. Bạn có chắc chắn?')) {
                // Có thể thêm logic gọi API để lấy thông tin về ca làm của nhân viên
            } else {
                // Khôi phục giá trị cũ
                $(this).val('{{ $timeKeeping->MaNV }}').trigger('change.select2');
            }
        });
    });
</script>
@endpush