@extends('layouts.admin')

@section('title', 'Quản lý nhân sự')

@section('page-title', 'Quản lý nhân sự')

@section('breadcrumb')
    <li class="breadcrumb-item active">Nhân sự</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Thành công!</h5>
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Lỗi!</h5>
            {{ session('error') }}
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách nhân viên</h3>
                <div class="card-tools">
                    <form action="{{ route('admin.employees.index') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" id="searchInput" class="form-control float-right" 
                                placeholder="Tìm kiếm nhân viên..." value="{{ request('search') }}">
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
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <a href="{{ route('admin.employees.create') }}" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Thêm nhân viên
                        </a>
                        <button class="btn btn-primary ml-2" id="exportExcel">
                            <i class="fas fa-file-excel"></i> Xuất Excel
                        </button>
                        <button class="btn btn-danger ml-2" id="exportPdf">
                            <i class="fas fa-file-pdf"></i> Xuất PDF
                        </button>
                    </div>
                    <div>
                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-default">Hiển thị trạng thái</button>
                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item" href="{{ route('admin.employees.index') }}">Tất cả nhân viên</a>
                                <a class="dropdown-item" href="{{ route('admin.employees.index', ['status' => 0]) }}">Nhân viên nghỉ làm</a>
                                <a class="dropdown-item" href="{{ route('admin.employees.index', ['status' => 1]) }}">Nhân viên đang làm việc</a>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default">Lọc theo chức vụ</button>
                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item" href="{{ route('admin.employees.index') }}">Tất cả</a>
                                @foreach ($positions = \App\Models\Position::all() as $position)
                                    <a class="dropdown-item" href="{{ route('admin.employees.index', ['position' => $position->IDCV]) }}">
                                        {{ $position->TenCV }}
                                    </a>
                                @endforeach
                            </div>
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
                                <th>Chức vụ</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Trạng thái</th>
                                <th style="width: 150px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees ?? [] as $index => $employee)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="check{{ $employee->MaNV }}">
                                        <label for="check{{ $employee->MaNV }}" class="custom-control-label"></label>
                                    </div>
                                </td>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($employee->HinhAnh)
                                        <img src="{{ asset('nhanvien/'.$employee->HinhAnh) }}" alt="Avatar" class="img-circle" width="40" onerror="this.src='{{ asset('img/default-avatar.jpg') }}'">
                                    @else
                                        <img src="{{ asset('img/default-avatar.jpg') }}" alt="Avatar" class="img-circle" width="40">
                                    @endif
                                </td>
                                <td>{{ $employee->TenNV }}</td>
                                <td>{{ $employee->MaNV }}</td>
                                <td>{{ $employee->position ? $employee->position->TenCV : 'N/A' }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->DienThoai }}</td>
                                <td>
                                    @if($employee->TrangThai == 1)
                                        <span class="badge badge-success">Đang làm việc</span>
                                    @elseif($employee->TrangThai == 0)
                                        <span class="badge badge-secondary">Nghỉ làm</span>
                                    @elseif($employee->TrangThai == 2)
                                        <span class="badge badge-danger">Đã xóa</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm view-btn" data-id="{{ $employee->MaNV }}" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.employees.edit', $employee->MaNV) }}" class="btn btn-primary btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.employees.toggle-status', $employee->MaNV) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-{{ $employee->TrangThai == 1 ? 'warning' : 'success' }} btn-sm" title="{{ $employee->TrangThai == 1 ? 'Vô hiệu hóa' : 'Kích hoạt' }}">
                                                <i class="fas fa-{{ $employee->TrangThai == 1 ? 'user-slash' : 'user-check' }}"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $employee->MaNV }}" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{ $employees->appends(request()->query())->links() }}
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
                <!-- Nội dung chi tiết nhân viên sẽ được load bằng AJAX -->
                <div id="employee-detail-content"></div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <a href="#" id="edit-employee-link" class="btn btn-primary">Chỉnh sửa</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Modal xác nhận xóa nhân viên -->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Xác nhận xóa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa nhân viên này không?</p>
                <p class="font-italic text-muted">Lưu ý: Nhân viên sẽ được đánh dấu là đã xóa và không hiển thị trong danh sách. Bạn có thể khôi phục lại từ dữ liệu gốc sau này.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
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
        // Xử lý khi click vào nút xem chi tiết
        $('.view-btn').on('click', function() {
            var employeeId = $(this).data('id');
            $('#edit-employee-link').attr('href', '{{ route("admin.employees.index") }}/' + employeeId + '/edit');
            
            // Load dữ liệu nhân viên vào modal
            $.ajax({
                url: '{{ route("admin.employees.index") }}/' + employeeId,
                type: 'GET',
                dataType: 'html',
                success: function(data) {
                    $('#employee-detail-content').html(data);
                    $('#employeeDetailModal').modal('show');
                },
                error: function() {
                    alert('Có lỗi xảy ra khi tải thông tin nhân viên!');
                }
            });
        });
        
        // Xử lý khi click vào nút xóa
        $('.delete-btn').on('click', function() {
            var employeeId = $(this).data('id');
            $('#deleteForm').attr('action', '{{ route("admin.employees.index") }}/' + employeeId);
            $('#deleteModal').modal('show');
        });
        
        // Chọn/bỏ chọn tất cả
        $('#checkAll').on('click', function() {
            if($(this).is(':checked')) {
                $('tbody input[type="checkbox"]').prop('checked', true);
            } else {
                $('tbody input[type="checkbox"]').prop('checked', false);
            }
        });
        
        // Tìm kiếm
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        // Xuất Excel
        $('#exportExcel').on('click', function() {
            window.location.href = '{{ route("admin.employees.index") }}?export=excel';
        });
        
        // Xuất PDF
        $('#exportPdf').on('click', function() {
            window.location.href = '{{ route("admin.employees.index") }}?export=pdf';
        });
    });
</script>
@endpush