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
                <h3 class="card-title" id="form-title">Thêm chức vụ mới</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.positions.store') }}" method="POST" id="position-form">
                @csrf
                <div id="method-field"></div>
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
                    
                    <div class="form-group">
                        <label for="TenCV">Tên chức vụ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('TenCV') is-invalid @enderror" id="TenCV" name="TenCV" placeholder="Nhập tên chức vụ" value="{{ old('TenCV') }}" required>
                        @error('TenCV')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="status-active" name="TrangThai" value="1" {{ old('TrangThai', 1) == 1 ? 'checked' : '' }}>
                            <label for="status-active" class="custom-control-label">Kích hoạt</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="status-inactive" name="TrangThai" value="0" {{ old('TrangThai') == 0 ? 'checked' : '' }}>
                            <label for="status-inactive" class="custom-control-label">Không kích hoạt</label>
                        </div>
                        @error('TrangThai')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Lưu lại</button>
                    <button type="button" class="btn btn-default float-right" id="btn-reset">Hủy bỏ</button>
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
                    <form action="{{ route('admin.positions.index') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Tìm kiếm..." value="{{ $search ?? '' }}">
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
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Tên chức vụ</th>
                            <th>Số nhân viên</th>
                            <th style="width: 100px">Trạng thái</th>
                            <th style="width: 120px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($positions as $position)
                            <tr>
                                <td>{{ $position->IDCV }}</td>
                                <td>{{ $position->TenCV }}</td>
                                <td>{{ $position->employee_count }}</td>
                                <td>
                                    @if ($position->TrangThai == \App\Models\Position::STATUS_ACTIVE)
                                        <span class="badge bg-success">Kích hoạt</span>
                                    @else
                                        <span class="badge bg-secondary">Không kích hoạt</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary edit-position" 
                                            data-id="{{ $position->IDCV }}" 
                                            data-name="{{ $position->TenCV }}" 
                                            data-status="{{ $position->TrangThai }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.positions.toggle-status', $position->IDCV) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info" title="{{ $position->TrangThai ? 'Vô hiệu hóa' : 'Kích hoạt' }}">
                                                <i class="fas fa-{{ $position->TrangThai ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger delete-position" 
                                            data-id="{{ $position->IDCV }}" 
                                            data-name="{{ $position->TenCV }}"
                                            data-employee-count="{{ $position->employee_count }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{ $positions->links('pagination::bootstrap-4') }}
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
                <p>Bạn có chắc chắn muốn xóa chức vụ <strong id="delete-position-name"></strong>?</p>
                <p class="text-danger"><strong>Lưu ý:</strong> Chức vụ chỉ có thể bị xóa khi không có nhân viên nào đang sử dụng.</p>
                <div id="employee-warning" class="alert alert-warning">
                    Chức vụ này đang được sử dụng bởi <strong id="employee-count"></strong> nhân viên. Không thể xóa!
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy bỏ</button>
                <form id="delete-form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirm-delete">Xác nhận xóa</button>
                </form>
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
    // Hiển thị thông báo qua session flash
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    // Kiểm tra nếu có phần tử biểu đồ tồn tại
    var chartElement = document.getElementById('positionChart');
    if (chartElement) {
        try {
            // Chuẩn bị dữ liệu cho biểu đồ
            var labels = [];
            var dataValues = [];
            
            @if(isset($chart_data) && count($chart_data) > 0)
            // Lấy dữ liệu từ PHP và chuyển sang JavaScript
            @foreach($chart_data as $item)
            labels.push("{{ $item->TenCV }}");
            dataValues.push({{ $item->employee_count }});
            @endforeach
            @endif
            
            // Chart initialization
            var ctx = chartElement.getContext('2d');
            var positionChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#6f42c1', '#fd7e14', '#17a2b8', '#6610f2'],
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                }
            });
        } catch (e) {
            console.error("Lỗi khởi tạo biểu đồ:", e);
        }
    }

    // Edit position event
    $('.edit-position').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var status = $(this).data('status');
        
        // Chuyển đổi form sang chế độ cập nhật
        $('#form-title').text('Cập nhật chức vụ');
        $('#position-form').attr('action', '{{ route("admin.positions.index") }}/' + id);
        $('#method-field').html('<input type="hidden" name="_method" value="PUT">');
        
        // Điền dữ liệu vào form
        $('#TenCV').val(name);
        if (status == 1) {
            $('#status-active').prop('checked', true);
        } else {
            $('#status-inactive').prop('checked', true);
        }
        
        // Scroll to form
        $('html, body').animate({
            scrollTop: $('#position-form').offset().top - 100
        }, 500);
    });

    // Reset form button
    $('#btn-reset').on('click', function() {
        // Reset form
        $('#position-form').attr('action', '{{ route("admin.positions.store") }}');
        $('#method-field').html('');
        $('#form-title').text('Thêm chức vụ mới');
        $('#position-form')[0].reset();
    });

    // Delete position event
    $('.delete-position').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var employeeCount = $(this).data('employee-count');
        
        $('#delete-position-name').text(name);
        $('#delete-form').attr('action', '{{ route("admin.positions.index") }}/' + id);
        
        // Kiểm tra số lượng nhân viên
        if (employeeCount > 0) {
            $('#employee-count').text(employeeCount);
            $('#employee-warning').show();
            $('#confirm-delete').prop('disabled', true);
        } else {
            $('#employee-warning').hide();
            $('#confirm-delete').prop('disabled', false);
        }
        
        $('#deletePositionModal').modal('show');
    });
});
</script>
@endpush