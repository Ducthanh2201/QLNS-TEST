@extends('layouts.admin')

@section('title', 'Khen thưởng & Kỷ luật')

@section('page-title', 'Quản lý Khen thưởng & Kỷ luật')

@section('breadcrumb')
    <li class="breadcrumb-item active">Khen thưởng & Kỷ luật</li>
@endsection

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <style>
        .badge-reward {
            background-color: #28a745;
        }
        .badge-discipline {
            background-color: #dc3545;
        }
        .nav-tabs-custom .nav-item .nav-link.active {
            font-weight: bold;
            border-top: 3px solid #007bff;
        }
        .reward-amount {
            color: #28a745;
            font-weight: bold;
        }
        .discipline-amount {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs nav-tabs-custom" id="reward-discipline-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="all-tab" data-toggle="pill" href="#all" role="tab" aria-controls="all" aria-selected="true">
                            <i class="fas fa-list mr-1"></i> Tất cả
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="reward-tab" data-toggle="pill" href="#reward" role="tab" aria-controls="reward" aria-selected="false">
                            <i class="fas fa-award mr-1"></i> Khen thưởng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="discipline-tab" data-toggle="pill" href="#discipline" role="tab" aria-controls="discipline" aria-selected="false">
                            <i class="fas fa-gavel mr-1"></i> Kỷ luật
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="reward-discipline-tabContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="mb-3">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-add-reward">
                                <i class="fas fa-plus-circle mr-1"></i> Thêm khen thưởng
                            </button>
                            <button class="btn btn-danger" data-toggle="modal" data-target="#modal-add-discipline">
                                <i class="fas fa-plus-circle mr-1"></i> Thêm kỷ luật
                            </button>
                        </div>
                        
                        <table id="reward-discipline-table" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="15%">Nhân viên</th>
                                    <th width="10%">Loại</th>
                                    <th width="15%">Tiêu đề</th>
                                    <th width="25%">Mô tả</th>
                                    <th width="10%">Số tiền</th>
                                    <th width="10%">Ngày</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rewardDisciplines as $item)
                                <tr>
                                    <td>{{ $item->ID }}</td>
                                    <td>
                                        <div class="user-panel d-flex">
                                            <div class="image">
                                                <img src="{{ $item->employee && $item->employee->AnhDaiDien ? asset('storage/' . $item->employee->AnhDaiDien) : asset('img/default-avatar.jpg') }}" class="img-circle" alt="User Image" style="width: 35px; height: 35px;">
                                            </div>
                                            <div class="info">
                                                {{ $item->employee ? $item->employee->TenNV : 'N/A' }}<br>
                                                <small>{{ $item->employee && $item->employee->department ? $item->employee->department->TenPB : 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge {{ $item->type_class }}">{{ $item->type_name }}</span></td>
                                    <td>{{ $item->TieuDe }}</td>
                                    <td>{{ $item->NoiDung }}</td>
                                    <td class="{{ $item->isReward() ? 'reward-amount' : 'discipline-amount' }}">{{ $item->formatted_amount }}</td>
                                    <td>{{ $item->formatted_date }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info edit-reward-discipline" data-id="{{ $item->ID }}" data-toggle="modal" data-target="{{ $item->isReward() ? '#modal-edit-reward' : '#modal-edit-discipline' }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-reward-discipline" data-id="{{ $item->ID }}" data-toggle="modal" data-target="#modal-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="reward" role="tabpanel" aria-labelledby="reward-tab">
                        <div class="mb-3">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-add-reward">
                                <i class="fas fa-plus-circle mr-1"></i> Thêm khen thưởng
                            </button>
                        </div>
                        
                        <table id="reward-table" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="15%">Nhân viên</th>
                                    <th width="20%">Tiêu đề</th>
                                    <th width="30%">Mô tả</th>
                                    <th width="10%">Số tiền</th>
                                    <th width="10%">Ngày</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rewards as $item)
                                <tr>
                                    <td>{{ $item->ID }}</td>
                                    <td>
                                        <div class="user-panel d-flex">
                                            <div class="image">
                                                <img src="{{ $item->employee && $item->employee->AnhDaiDien ? asset('storage/' . $item->employee->AnhDaiDien) : asset('img/default-avatar.jpg') }}" class="img-circle" alt="User Image" style="width: 35px; height: 35px;">
                                            </div>
                                            <div class="info">
                                                {{ $item->employee ? $item->employee->TenNV : 'N/A' }}<br>
                                                <small>{{ $item->employee && $item->employee->department ? $item->employee->department->TenPB : 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->TieuDe }}</td>
                                    <td>{{ $item->NoiDung }}</td>
                                    <td class="reward-amount">{{ $item->formatted_amount }}</td>
                                    <td>{{ $item->formatted_date }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info edit-reward-discipline" data-id="{{ $item->ID }}" data-toggle="modal" data-target="#modal-edit-reward">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-reward-discipline" data-id="{{ $item->ID }}" data-toggle="modal" data-target="#modal-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="discipline" role="tabpanel" aria-labelledby="discipline-tab">
                        <div class="mb-3">
                            <button class="btn btn-danger" data-toggle="modal" data-target="#modal-add-discipline">
                                <i class="fas fa-plus-circle mr-1"></i> Thêm kỷ luật
                            </button>
                        </div>
                        
                        <table id="discipline-table" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="15%">Nhân viên</th>
                                    <th width="20%">Tiêu đề</th>
                                    <th width="30%">Mô tả</th>
                                    <th width="10%">Số tiền</th>
                                    <th width="10%">Ngày</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penalties as $item)
                                <tr>
                                    <td>{{ $item->ID }}</td>
                                    <td>
                                        <div class="user-panel d-flex">
                                            <div class="image">
                                                <img src="{{ $item->employee && $item->employee->AnhDaiDien ? asset('storage/' . $item->employee->AnhDaiDien) : asset('img/default-avatar.jpg') }}" class="img-circle" alt="User Image" style="width: 35px; height: 35px;">
                                            </div>
                                            <div class="info">
                                                {{ $item->employee ? $item->employee->TenNV : 'N/A' }}<br>
                                                <small>{{ $item->employee && $item->employee->department ? $item->employee->department->TenPB : 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->TieuDe }}</td>
                                    <td>{{ $item->NoiDung }}</td>
                                    <td class="discipline-amount">{{ $item->formatted_amount }}</td>
                                    <td>{{ $item->formatted_date }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info edit-reward-discipline" data-id="{{ $item->ID }}" data-toggle="modal" data-target="#modal-edit-discipline">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-reward-discipline" data-id="{{ $item->ID }}" data-toggle="modal" data-target="#modal-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm khen thưởng -->
<div class="modal fade" id="modal-add-reward">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Thêm khen thưởng</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.reward-discipline.store-reward') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="employee_id">Nhân viên <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="employee_id" name="employee_id" required>
                            <option value="">-- Chọn nhân viên --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->MaNV }}">{{ $employee->MaNV }} - {{ $employee->TenNV }} - {{ $employee->department ? $employee->department->TenPB : 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reward_title">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="reward_title" name="title" placeholder="Nhập tiêu đề khen thưởng" required>
                    </div>
                    <div class="form-group">
                        <label for="reward_description">Mô tả</label>
                        <textarea class="form-control" id="reward_description" name="description" rows="3" placeholder="Nhập mô tả chi tiết"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="reward_amount">Số tiền <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₫</span>
                            </div>
                            <input type="number" class="form-control" id="reward_amount" name="amount" placeholder="Nhập số tiền thưởng" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reward_date">Ngày <span class="text-danger">*</span></label>
                        <div class="input-group date" id="reward_date_picker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" id="reward_date" name="date" data-target="#reward_date_picker" required value="{{ date('d/m/Y') }}">
                            <div class="input-group-append" data-target="#reward_date_picker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Thêm kỷ luật -->
<div class="modal fade" id="modal-add-discipline">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Thêm kỷ luật</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.reward-discipline.store-penalty') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="discipline_employee_id">Nhân viên <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="discipline_employee_id" name="employee_id" required>
                            <option value="">-- Chọn nhân viên --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->MaNV }}">{{ $employee->MaNV }} - {{ $employee->TenNV }} - {{ $employee->department ? $employee->department->TenPB : 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="discipline_title">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="discipline_title" name="title" placeholder="Nhập tiêu đề kỷ luật" required>
                    </div>
                    <div class="form-group">
                        <label for="discipline_description">Mô tả</label>
                        <textarea class="form-control" id="discipline_description" name="description" rows="3" placeholder="Nhập mô tả chi tiết"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="discipline_amount">Số tiền phạt <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₫</span>
                            </div>
                            <input type="number" class="form-control" id="discipline_amount" name="amount" placeholder="Nhập số tiền phạt" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="discipline_date">Ngày <span class="text-danger">*</span></label>
                        <div class="input-group date" id="discipline_date_picker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" id="discipline_date" name="date" data-target="#discipline_date_picker" required value="{{ date('d/m/Y') }}">
                            <div class="input-group-append" data-target="#discipline_date_picker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa khen thưởng -->
<div class="modal fade" id="modal-edit-reward">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">Sửa khen thưởng</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-reward-form" action="{{ route('admin.reward-discipline.update', 0) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_reward_employee_id">Nhân viên <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="edit_reward_employee_id" name="employee_id" required>
                            <option value="">-- Chọn nhân viên --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->MaNV }}">{{ $employee->MaNV }} - {{ $employee->TenNV }} - {{ $employee->department ? $employee->department->TenPB : 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_reward_title">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_reward_title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_reward_description">Mô tả</label>
                        <textarea class="form-control" id="edit_reward_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_reward_amount">Số tiền <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₫</span>
                            </div>
                            <input type="number" class="form-control" id="edit_reward_amount" name="amount" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_reward_date">Ngày <span class="text-danger">*</span></label>
                        <div class="input-group date" id="edit_reward_date_picker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" id="edit_reward_date" name="date" data-target="#edit_reward_date_picker" required>
                            <div class="input-group-append" data-target="#edit_reward_date_picker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-info">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa kỷ luật -->
<div class="modal fade" id="modal-edit-discipline">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">Sửa kỷ luật</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-discipline-form" action="{{ route('admin.reward-discipline.update', 0) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_discipline_employee_id">Nhân viên <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="edit_discipline_employee_id" name="employee_id" required>
                            <option value="">-- Chọn nhân viên --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->MaNV }}">{{ $employee->MaNV }} - {{ $employee->TenNV }} - {{ $employee->department ? $employee->department->TenPB : 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_discipline_title">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_discipline_title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_discipline_description">Mô tả</label>
                        <textarea class="form-control" id="edit_discipline_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_discipline_amount">Số tiền phạt <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₫</span>
                            </div>
                            <input type="number" class="form-control" id="edit_discipline_amount" name="amount" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_discipline_date">Ngày <span class="text-danger">*</span></label>
                        <div class="input-group date" id="edit_discipline_date_picker" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" id="edit_discipline_date" name="date" data-target="#edit_discipline_date_picker" required>
                            <div class="input-group-append" data-target="#edit_discipline_date_picker" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-info">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa -->
<div class="modal fade" id="modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Xác nhận xóa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="delete-form" action="{{ route('admin.reward-discipline.destroy', 0) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa mục này không?</p>
                    <p class="text-danger"><small>Hành động này không thể hoàn tác.</small></p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables & Plugins -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<script>
    $(function () {
        // Setup CSRF token cho tất cả ajax requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Initialize DataTables
        $("#reward-discipline-table, #reward-table, #discipline-table").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "language": {
                "search": "Tìm kiếm:",
                "lengthMenu": "Hiển thị _MENU_ mục",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                "infoEmpty": "Hiển thị 0 đến 0 của 0 mục",
                "infoFiltered": "(lọc từ _MAX_ mục)",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Sau",
                    "previous": "Trước"
                }
            }
        });
        
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });
        
        // Initialize Datetime pickers
        $('#reward_date_picker, #discipline_date_picker, #edit_reward_date_picker, #edit_discipline_date_picker').datetimepicker({
            format: 'L'
        });
        
        // Hàm truy cập an toàn cho các thuộc tính có ký tự đặc biệt
        function getProperty(obj, key) {
            return obj[key] || obj['LoaiKT/KL'] || null;
        }
        
        // Hàm để kiểm tra loại khen thưởng/kỷ luật
        function isReward(item) {
            return getProperty(item, 'LoaiKT/KL') == 1;
        }
        
        // Edit reward
        $('.edit-reward-discipline').click(function() {
            const id = $(this).data('id');
            const isReward = $(this).data('target') === '#modal-edit-reward';
            const form = isReward ? '#edit-reward-form' : '#edit-discipline-form';
            
            // Update form action URL
            $(form).attr('action', "{{ url('admin/reward-discipline') }}/" + id);
            
            // Fetch reward/discipline data
            $.ajax({
                url: "{{ url('admin/reward-discipline') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (isReward) {
                        $('#edit_reward_employee_id').val(response.MaNV).trigger('change');
                        $('#edit_reward_title').val(response.TieuDe);
                        $('#edit_reward_description').val(response.NoiDung);
                        $('#edit_reward_amount').val(response.SoTien);
                        $('#edit_reward_date').val(response.formatted_date);
                    } else {
                        $('#edit_discipline_employee_id').val(response.MaNV).trigger('change');
                        $('#edit_discipline_title').val(response.TieuDe);
                        $('#edit_discipline_description').val(response.NoiDung);
                        $('#edit_discipline_amount').val(response.SoTien);
                        $('#edit_discipline_date').val(response.formatted_date);
                    }
                },
                error: function(xhr) {
                    toastr.error('Có lỗi xảy ra khi tải dữ liệu!');
                    console.error(xhr.responseText);
                }
            });
        });
        
        // Delete confirmation
        $('.delete-reward-discipline').click(function() {
            const id = $(this).data('id');
            $('#delete-form').attr('action', "{{ url('admin/reward-discipline') }}/" + id);
        });
    });
</script>
@endpush