@extends('layouts_employees.employee')

@section('title', 'Hồ sơ cá nhân')

@section('page-title', 'Hồ sơ cá nhân')

@section('breadcrumb')
    <li class="breadcrumb-item active">Hồ sơ cá nhân</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    @php
                        $employee = Auth::guard('employee')->user();
                        $avatar = $employee->HinhAnh ? asset('storage/employees/'.$employee->HinhAnh) : asset('img/default-avatar.png');
                    @endphp
                    <img class="profile-user-img img-fluid img-circle" src="{{ $avatar }}" alt="Ảnh đại diện">
                </div>

                <h3 class="profile-username text-center">{{ $employee->TenNV }}</h3>

                <p class="text-muted text-center">{{ $employee->ChucVu->TenCV ?? 'Nhân viên' }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Mã nhân viên</b> <a class="float-right">{{ $employee->MaNV }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $employee->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Điện thoại</b> <a class="float-right">{{ $employee->DienThoai }}</a>
                    </li>
                </ul>

                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#avatarModal">
                    <i class="fas fa-camera mr-1"></i> Thay đổi ảnh đại diện
                </button>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Thông tin liên hệ</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Địa chỉ</strong>

                <p class="text-muted">{{ $employee->DiaChi }}</p>

                <hr>

                <strong><i class="fas fa-phone mr-1"></i> Điện thoại</strong>

                <p class="text-muted">{{ $employee->DienThoai }}</p>

                <hr>

                <strong><i class="fas fa-id-card mr-1"></i> CCCD</strong>

                <p class="text-muted">{{ $employee->CCCD }}</p>

                <hr>

                <strong><i class="fas fa-birthday-cake mr-1"></i> Ngày sinh</strong>

                <p class="text-muted">{{ date('d/m/Y', strtotime($employee->NgaySinh)) }}</p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Cài đặt tài khoản</a></li>
                    <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Hoạt động gần đây</a></li>
                    <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <!-- Settings Tab -->
                    <div class="active tab-pane" id="settings">
                        <form class="form-horizontal">
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">Họ tên</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputName" placeholder="Họ tên" value="{{ $employee->TenNV }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputEmail" placeholder="Email" value="{{ $employee->email }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPhone" class="col-sm-2 col-form-label">Điện thoại</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputPhone" placeholder="Điện thoại" value="{{ $employee->DienThoai }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputAddress" class="col-sm-2 col-form-label">Địa chỉ</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="inputAddress" placeholder="Địa chỉ">{{ $employee->DiaChi }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Mật khẩu mới</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="inputPassword" placeholder="Mật khẩu mới">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPasswordConfirm" class="col-sm-2 col-form-label">Xác nhận mật khẩu</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="inputPasswordConfirm" placeholder="Xác nhận mật khẩu">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Cập nhật thông tin</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.tab-pane -->
                    
                    <!-- Activity Tab -->
                    <div class="tab-pane" id="activity">
                        <!-- Post -->
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="{{ $avatar }}" alt="user image">
                                <span class="username">
                                    <a href="#">{{ $employee->TenNV }}</a>
                                </span>
                                <span class="description">Check-in - 7:30 AM hôm nay</span>
                            </div>
                            <!-- /.user-block -->
                            <p>
                                Đã check-in vào lúc 7:30 AM ngày {{ date('d/m/Y') }}.
                            </p>
                        </div>
                        <!-- /.post -->

                        <!-- Post -->
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="{{ $avatar }}" alt="user image">
                                <span class="username">
                                    <a href="#">{{ $employee->TenNV }}</a>
                                </span>
                                <span class="description">Nộp báo cáo - 3 ngày trước</span>
                            </div>
                            <!-- /.user-block -->
                            <p>
                                Đã nộp báo cáo tháng 4/2025 cho trưởng phòng.
                            </p>
                        </div>
                        <!-- /.post -->

                        <!-- Post -->
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="{{ $avatar }}" alt="user image">
                                <span class="username">
                                    <a href="#">{{ $employee->TenNV }}</a>
                                </span>
                                <span class="description">Đơn nghỉ phép - 1 tuần trước</span>
                            </div>
                            <!-- /.user-block -->
                            <p>
                                Đã nộp đơn xin nghỉ phép ngày 15/04/2025.
                            </p>
                            <p>
                                <span class="badge badge-success">Đã được duyệt</span>
                            </p>
                        </div>
                        <!-- /.post -->
                    </div>
                    <!-- /.tab-pane -->

                    <!-- Timeline Tab -->
                    <div class="tab-pane" id="timeline">
                        <!-- The timeline -->
                        <div class="timeline timeline-inverse">
                            <!-- timeline time label -->
                            <div class="time-label">
                                <span class="bg-danger">
                                    10 Feb. 2025
                                </span>
                            </div>
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-trophy bg-warning"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="far fa-clock"></i> 4 tháng trước</span>

                                    <h3 class="timeline-header"><a href="#">Khen thưởng</a> nhân viên xuất sắc tháng 2</h3>

                                    <div class="timeline-body">
                                        Bạn đã được khen thưởng nhân viên xuất sắc tháng 2/2025 với thành tích hoàn thành xuất sắc công việc.
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <!-- timeline time label -->
                            <div class="time-label">
                                <span class="bg-success">
                                    01 Jan. 2025
                                </span>
                            </div>
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-user bg-info"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="far fa-clock"></i> 5 tháng trước</span>

                                    <h3 class="timeline-header border-0"><a href="#">Thăng chức</a> lên vị trí mới
                                    </h3>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-calendar-check bg-primary"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="far fa-clock"></i> 6 tháng trước</span>

                                    <h3 class="timeline-header"><a href="#">Hoàn thành dự án</a> XYZ</h3>

                                    <div class="timeline-body">
                                        Đã hoàn thành dự án XYZ đúng tiến độ và đạt kết quả tốt.
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            <div>
                                <i class="far fa-clock bg-gray"></i>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<!-- Modal for Avatar Change -->
<div class="modal fade" id="avatarModal" tabindex="-1" role="dialog" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">Thay đổi ảnh đại diện</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="avatarFile">Chọn ảnh</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="avatarFile" accept="image/*">
                                <label class="custom-file-label" for="avatarFile">Chọn file</label>
                            </div>
                        </div>
                    </div>
                    <div class="preview text-center">
                        <img id="preview-avatar" src="{{ $avatar }}" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function() {
    // Preview avatar image before upload
    $("#avatarFile").change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-avatar').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
            $('.custom-file-label').text(this.files[0].name);
        }
    });
});
</script>
@endpush