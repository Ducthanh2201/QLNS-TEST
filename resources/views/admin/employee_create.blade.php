@extends('layouts.admin')

@section('title', 'Thêm nhân viên mới')

@section('page-title', 'Thêm nhân viên mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Nhân sự</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Thông tin nhân viên</h3>
            </div>
            <!-- /.card-header -->
            
            <!-- form start -->
            <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="TenNV">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('TenNV') is-invalid @enderror" id="TenNV" name="TenNV" value="{{ old('TenNV') }}" placeholder="Nhập họ và tên">
                                @error('TenNV')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Nhập email">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="Password">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('Password') is-invalid @enderror" id="Password" name="Password" placeholder="Nhập mật khẩu">
                                @error('Password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label>Giới tính <span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <div class="custom-control custom-radio mr-3">
                                        <input class="custom-control-input" type="radio" id="GioiTinh1" name="GioiTinh" value="1" {{ old('GioiTinh') == '1' ? 'checked' : '' }}>
                                        <label for="GioiTinh1" class="custom-control-label">Nam</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="GioiTinh0" name="GioiTinh" value="0" {{ old('GioiTinh') == '0' ? 'checked' : '' }}>
                                        <label for="GioiTinh0" class="custom-control-label">Nữ</label>
                                    </div>
                                </div>
                                @error('GioiTinh')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="NgaySinh">Ngày sinh <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('NgaySinh') is-invalid @enderror" id="NgaySinh" name="NgaySinh" value="{{ old('NgaySinh') }}">
                                @error('NgaySinh')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DienThoai">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('DienThoai') is-invalid @enderror" id="DienThoai" name="DienThoai" value="{{ old('DienThoai') }}" placeholder="Nhập số điện thoại">
                                @error('DienThoai')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="CCCD">Căn cước công dân <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('CCCD') is-invalid @enderror" id="CCCD" name="CCCD" value="{{ old('CCCD') }}" placeholder="Nhập số CCCD">
                                @error('CCCD')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="DiaChi">Địa chỉ <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('DiaChi') is-invalid @enderror" id="DiaChi" name="DiaChi" rows="2" placeholder="Nhập địa chỉ">{{ old('DiaChi') }}</textarea>
                                @error('DiaChi')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="IDCV">Chức vụ <span class="text-danger">*</span></label>
                                <select class="form-control @error('IDCV') is-invalid @enderror" id="IDCV" name="IDCV">
                                    <option value="">-- Chọn chức vụ --</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->IDCV }}" {{ old('IDCV') == $position->IDCV ? 'selected' : '' }}>
                                            {{ $position->TenCV }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('IDCV')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="HinhAnh">Hình ảnh</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('HinhAnh') is-invalid @enderror" id="HinhAnh" name="HinhAnh" accept="image/*">
                                        <label class="custom-file-label" for="HinhAnh">Chọn file</label>
                                    </div>
                                </div>
                                @error('HinhAnh')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="mt-2">
                                    <img id="previewImg" src="{{ asset('img/default-avatar.jpg') }}" alt="Preview" style="max-width: 100px; max-height: 100px; display: block;" class="img-thumbnail">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Lưu thông tin
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Preview image before upload
        $('#HinhAnh').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => { 
                $('#previewImg').attr('src', e.target.result); 
            }
            reader.readAsDataURL(this.files[0]); 
        });
        
        // BS Custom File Input
        bsCustomFileInput.init();
    });
</script>
@endpush