<div class="row">
    <div class="col-md-4 text-center">
        @if($employee->HinhAnh)
            <img src="{{ asset('nhanvien/'.$employee->HinhAnh) }}" alt="Avatar" class="img-circle" width="150" onerror="this.src='{{ asset('img/default-avatar.jpg') }}'">
        @else
            <img src="{{ asset('img/default-avatar.jpg') }}" alt="Avatar" class="img-circle" width="150">
        @endif
        <h4 class="mt-3">{{ $employee->TenNV }}</h4>
        <p class="text-muted">{{ $employee->position ? $employee->position->TenCV : 'Chưa xác định' }}</p>
    </div>
    <div class="col-md-8">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th style="width:30%">Mã nhân viên:</th>
                    <td>{{ $employee->MaNV }}</td>
                </tr>
                <tr>
                    <th>Họ và tên:</th>
                    <td>{{ $employee->TenNV }}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{{ $employee->email }}</td>
                </tr>
                <tr>
                    <th>Số điện thoại:</th>
                    <td>{{ $employee->DienThoai }}</td>
                </tr>
                <tr>
                    <th>Ngày sinh:</th>
                    <td>{{ $employee->NgaySinh ? $employee->NgaySinh->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                </tr>
                <tr>
                    <th>Giới tính:</th>
                    <td>{{ $employee->GioiTinh ? 'Nam' : 'Nữ' }}</td>
                </tr>
                <tr>
                    <th>Địa chỉ:</th>
                    <td>{{ $employee->DiaChi }}</td>
                </tr>
                <tr>
                    <th>CCCD:</th>
                    <td>{{ $employee->CCCD }}</td>
                </tr>
                <tr>
                    <th>Chức vụ:</th>
                    <td>{{ $employee->position ? $employee->position->TenCV : 'Chưa xác định' }}</td>
                </tr>
                <tr>
                    <th>Trạng thái:</th>
                    <td>
                        @if($employee->TrangThai == 1)
                            <span class="badge badge-success">Đang làm việc</span>
                        @elseif($employee->TrangThai == 0)
                            <span class="badge badge-secondary">Nghỉ làm</span>
                        @else
                            <span class="badge badge-danger">Đã xóa</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>