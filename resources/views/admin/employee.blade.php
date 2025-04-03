@extends('layouts.admin')

@section('title', 'Quản lý nhân sự')

@section('page-title', 'Quản lý nhân sự')

@section('breadcrumb')
    <li class="breadcrumb-item active">Nhân sự</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách nhân viên</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Tìm kiếm nhân viên...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <button class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Thêm nhân viên
                        </button>
                        <button class="btn btn-primary ml-2">
                            <i class="fas fa-file-excel"></i> Xuất Excel
                        </button>
                        <button class="btn btn-danger ml-2">
                            <i class="fas fa-file-pdf"></i> Xuất PDF
                        </button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default">Lọc theo phòng ban</button>
                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="#">Tất cả</a>
                            <a class="dropdown-item" href="#">Kỹ thuật</a>
                            <a class="dropdown-item" href="#">Kinh doanh</a>
                            <a class="dropdown-item" href="#">Nhân sự</a>
                            <a class="dropdown-item" href="#">Marketing</a>
                            <a class="dropdown-item" href="#">Tài chính</a>
                            <a class="dropdown-item" href="#">Hành chính</a>
                            <a class="dropdown-item" href="#">IT</a>
                            <a class="dropdown-item" href="#">Pháp lý</a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 20px">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="checkAll">
                                        <label for="checkAll" class="custom-control-label"></label>
                                    </div>
                                </th>
                                <th style="width: 40px">#</th>
                                <th>Ảnh</th>
                                <th>Họ và Tên</th>
                                <th>Mã NV</th>
                                <th>Phòng ban</th>
                                <th>Chức vụ</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Trạng thái</th>
                                <th style="width: 150px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="check1">
                                        <label for="check1" class="custom-control-label"></label>
                                    </div>
                                </td>
                                <td>1</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg" alt="Avatar" class="img-circle" width="40">
                                </td>
                                <td>Nguyễn Văn A</td>
                                <td>NV001</td>
                                <td>Kỹ thuật</td>
                                <td>Trưởng phòng</td>
                                <td>a@example.com</td>
                                <td>0901234567</td>
                                <td><span class="badge bg-success">Đang làm việc</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="check2">
                                        <label for="check2" class="custom-control-label"></label>
                                    </div>
                                </td>
                                <td>2</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user8-128x128.jpg" alt="Avatar" class="img-circle" width="40">
                                </td>
                                <td>Trần Thị B</td>
                                <td>NV002</td>
                                <td>Kinh doanh</td>
                                <td>Nhân viên</td>
                                <td>b@example.com</td>
                                <td>0901234568</td>
                                <td><span class="badge bg-success">Đang làm việc</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="check3">
                                        <label for="check3" class="custom-control-label"></label>
                                    </div>
                                </td>
                                <td>3</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user3-128x128.jpg" alt="Avatar" class="img-circle" width="40">
                                </td>
                                <td>Lê Văn C</td>
                                <td>NV003</td>
                                <td>Nhân sự</td>
                                <td>Trưởng phòng</td>
                                <td>c@example.com</td>
                                <td>0901234569</td>
                                <td><span class="badge bg-success">Đang làm việc</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="check4">
                                        <label for="check4" class="custom-control-label"></label>
                                    </div>
                                </td>
                                <td>4</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user4-128x128.jpg" alt="Avatar" class="img-circle" width="40">
                                </td>
                                <td>Phạm Thị D</td>
                                <td>NV004</td>
                                <td>Marketing</td>
                                <td>Nhân viên</td>
                                <td>d@example.com</td>
                                <td>0901234570</td>
                                <td><span class="badge bg-warning">Tạm nghỉ</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="check5">
                                        <label for="check5" class="custom-control-label"></label>
                                    </div>
                                </td>
                                <td>5</td>
                                <td>
                                    <img src="https://adminlte.io/themes/v3/dist/img/user5-128x128.jpg" alt="Avatar" class="img-circle" width="40">
                                </td>
                                <td>Hoàng Văn E</td>
                                <td>NV005</td>
                                <td>Tài chính</td>
                                <td>Nhân viên</td>
                                <td>e@example.com</td>
                                <td>0901234571</td>
                                <td><span class="badge bg-danger">Đã nghỉ việc</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

<!-- Modal xem chi tiết nhân viên -->
<div class="modal fade" id="employeeDetailModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chi tiết nhân viên</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg" alt="Avatar" class="img-circle" width="150">
                        <h4 class="mt-3">Nguyễn Văn A</h4>
                        <p class="text-muted">Trưởng phòng Kỹ thuật</p>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:30%">Mã nhân viên:</th>
                                    <td>NV001</td>
                                </tr>
                                <tr>
                                    <th>Họ và tên:</th>
                                    <td>Nguyễn Văn A</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>a@example.com</td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại:</th>
                                    <td>0901234567</td>
                                </tr>
                                <tr>
                                    <th>Ngày sinh:</th>
                                    <td>01/01/1990</td>
                                </tr>
                                <tr>
                                    <th>Giới tính:</th>
                                    <td>Nam</td>
                                </tr>
                                <tr>
                                    <th>Địa chỉ:</th>
                                    <td>123 Đường ABC, Quận 1, TP. Hồ Chí Minh</td>
                                </tr>
                                <tr>
                                    <th>Ngày vào làm:</th>
                                    <td>01/01/2020</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái:</th>
                                    <td><span class="badge bg-success">Đang làm việc</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary">Chỉnh sửa</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@push('scripts')
<script>
    $(function () {
        // Xử lý khi click vào nút xem chi tiết
        $('.btn-info').on('click', function() {
            $('#employeeDetailModal').modal('show');
        });
        
        // Chọn/bỏ chọn tất cả
        $('#checkAll').on('click', function() {
            if($(this).is(':checked')) {
                $('tbody input[type="checkbox"]').prop('checked', true);
            } else {
                $('tbody input[type="checkbox"]').prop('checked', false);
            }
        });
    });
</script>
@endpush