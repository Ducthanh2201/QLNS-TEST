@extends('layouts.admin')

@section('title', 'Quản lý chức vụ')

@section('page-title', 'Quản lý chức vụ')

@section('breadcrumb')
    <li class="breadcrumb-item active">Chức vụ</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Thêm/Sửa chức vụ -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Thêm chức vụ mới</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form>
                <div class="card-body">
                    <div class="form-group">
                        <label for="positionName">Tên chức vụ</label>
                        <input type="text" class="form-control" id="positionName" placeholder="Nhập tên chức vụ">
                    </div>
                    <div class="form-group">
                        <label for="positionCode">Mã chức vụ</label>
                        <input type="text" class="form-control" id="positionCode" placeholder="Nhập mã chức vụ">
                    </div>
                    <div class="form-group">
                        <label for="departmentSelect">Phòng ban áp dụng</label>
                        <select class="form-control" id="departmentSelect">
                            <option value="0">Tất cả phòng ban</option>
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
                    <div class="form-group">
                        <label for="positionDescription">Mô tả chức vụ</label>
                        <textarea class="form-control" id="positionDescription" rows="3" placeholder="Nhập mô tả chức vụ"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="positionLevel">Cấp bậc</label>
                        <input type="number" class="form-control" id="positionLevel" placeholder="Nhập cấp bậc" min="1" max="10">
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="positionActive" checked>
                            <label class="custom-control-label" for="positionActive">Kích hoạt</label>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                    <button type="reset" class="btn btn-default float-right">Hủy bỏ</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
    <div class="col-md-8">
        <!-- Danh sách chức vụ -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách chức vụ</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Tìm kiếm...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Tên chức vụ</th>
                            <th>Mã</th>
                            <th>Phòng ban</th>
                            <th>Cấp bậc</th>
                            <th>Số nhân viên</th>
                            <th style="width: 100px">Trạng thái</th>
                            <th style="width: 120px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>Giám đốc</td>
                            <td><span class="badge bg-primary">GD</span></td>
                            <td>Tất cả</td>
                            <td>10</td>
                            <td>1</td>
                            <td><span class="badge bg-success">Kích hoạt</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary edit-position" data-id="1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-position" data-id="1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Phó giám đốc</td>
                            <td><span class="badge bg-primary">PGD</span></td>
                            <td>Tất cả</td>
                            <td>9</td>
                            <td>2</td>
                            <td><span class="badge bg-success">Kích hoạt</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary edit-position" data-id="2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-position" data-id="2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Trưởng phòng</td>
                            <td><span class="badge bg-primary">TP</span></td>
                            <td>Tất cả</td>
                            <td>8</td>
                            <td>8</td>
                            <td><span class="badge bg-success">Kích hoạt</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary edit-position" data-id="3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-position" data-id="3">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>Phó phòng</td>
                            <td><span class="badge bg-primary">PP</span></td>
                            <td>Tất cả</td>
                            <td>7</td>
                            <td>10</td>
                            <td><span class="badge bg-success">Kích hoạt</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary edit-position" data-id="4">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-position" data-id="4">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>5.</td>
                            <td>Trưởng nhóm</td>
                            <td><span class="badge bg-primary">TN</span></td>
                            <td>Tất cả</td>
                            <td>6</td>
                            <td>15</td>
                            <td><span class="badge bg-success">Kích hoạt</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary edit-position" data-id="5">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-position" data-id="5">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>6.</td>
                            <td>Nhân viên</td>
                            <td><span class="badge bg-primary">NV</span></td>
                            <td>Tất cả</td>
                            <td>5</td>
                            <td>116</td>
                            <td><span class="badge bg-success">Kích hoạt</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary edit-position" data-id="6">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-position" data-id="6">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>7.</td>
                            <td>Thực tập sinh</td>
                            <td><span class="badge bg-primary">TTS</span></td>
                            <td>Tất cả</td>
                            <td>1</td>
                            <td>8</td>
                            <td><span class="badge bg-success">Kích hoạt</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary edit-position" data-id="7">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-position" data-id="7">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>8.</td>
                            <td>Cố vấn</td>
                            <td><span class="badge bg-primary">CV</span></td>
                            <td>Ban giám đốc</td>
                            <td>9</td>
                            <td>2</td>
                            <td><span class="badge bg-secondary">Không kích hoạt</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary edit-position" data-id="8">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-position" data-id="8">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
            </div>
        </div>
        <!-- /.card -->
        
        <!-- Biểu đồ phân bố nhân viên theo chức vụ -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Phân bố nhân viên theo chức vụ
                </h3>
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
                <canvas id="positionChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>

<!-- Modal Xóa chức vụ -->
<div class="modal fade" id="deletePositionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác nhận xóa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa chức vụ này?</p>
                <p class="text-danger"><strong>Lưu ý:</strong> Hành động này có thể ảnh hưởng đến dữ liệu nhân viên hiện tại.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy bỏ</button>
                <button type="button" class="btn btn-danger confirm-delete">Xác nhận xóa</button>
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
        // Chart initialization
        var ctx = document.getElementById('positionChart').getContext('2d');
        var positionChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Giám đốc', 'Phó giám đốc', 'Trưởng phòng', 'Phó phòng', 'Trưởng nhóm', 'Nhân viên', 'Thực tập sinh', 'Cố vấn'],
                datasets: [
                    {
                        data: [1, 2, 8, 10, 15, 116, 8, 2],
                        backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#6f42c1', '#fd7e14'],
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
            }
        });

        // Edit position event
        $('.edit-position').on('click', function() {
            var id = $(this).data('id');
            // Fill form with position data (in a real app, this would fetch data via AJAX)
            $('#positionName').val($(this).closest('tr').find('td:nth-child(2)').text());
            $('#positionCode').val($(this).closest('tr').find('td:nth-child(3)').text().trim());
            // Update form title to indicate editing
            $('.card-title:first').text('Chỉnh sửa chức vụ');
        });

        // Delete position event
        $('.delete-position').on('click', function() {
            var id = $(this).data('id');
            $('#deletePositionModal').modal('show');
            // Store ID for the confirm delete button
            $('.confirm-delete').data('id', id);
        });

        // Confirm delete event
        $('.confirm-delete').on('click', function() {
            var id = $(this).data('id');
            // In a real app, this would send an AJAX request to delete the position
            // For now, just close the modal and show success message
            $('#deletePositionModal').modal('hide');
            // Show success message with SweetAlert or Toast
            $(document).Toasts('create', {
                class: 'bg-success',
                title: 'Xóa thành công',
                body: 'Đã xóa chức vụ thành công!'
            });
        });
    });
</script>
@endpush