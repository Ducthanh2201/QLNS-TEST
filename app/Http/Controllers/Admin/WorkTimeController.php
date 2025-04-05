<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Position;
use App\Models\TimeKeeping;
use App\Models\WorkType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WorkTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $employeeId = $request->input('employee_id');
        $positionId = $request->input('position_id');
        $status = $request->input('status');
        
        // Đầu tiên, cập nhật tất cả bản ghi có giờ làm nhưng trạng thái là vắng mặt
        DB::table('bangcong')
            ->where('TrangThai', TimeKeeping::ATTENDANCE_ABSENT)
            ->whereRaw('(Giovao > 0 OR Phutvao > 0 OR GioRa > 0 OR PhutRa > 0)')
            ->update([
                'TrangThai' => DB::raw('CASE 
                    WHEN ((GioRa * 60 + PhutRa) - (Giovao * 60 + Phutvao)) > 9 * 60 
                        OR ((GioRa < Giovao) AND ((GioRa + 24) * 60 + PhutRa) - (Giovao * 60 + Phutvao) > 9 * 60) 
                    THEN 4
                    WHEN Giovao > 8 OR (Giovao = 8 AND Phutvao > 15) THEN 2
                    WHEN (GioRa < 17 OR (GioRa = 17 AND PhutRa < 0)) AND (GioRa > 9 OR (GioRa = 9 AND PhutRa > 0)) THEN 3
                    ELSE 1
                END')
            ]);
        
        // Lấy danh sách bảng công với các filter
        $timeKeepingsQuery = TimeKeeping::with(['employee.position', 'workType'])
            ->when($employeeId, function($query) use ($employeeId) {
                return $query->where('MaNV', $employeeId);
            })
            ->when($positionId, function($query) use ($positionId) {
                return $query->whereHas('employee', function($q) use ($positionId) {
                    $q->where('IDCV', $positionId);
                });
            })
            ->when($status !== null, function($query) use ($status) {
                return $query->where('TrangThai', $status);
            })
            ->where('Nam', $year)
            ->where('Thang', $month)
            ->where('TrangThai', '!=', TimeKeeping::STATUS_DELETED)
            ->orderBy('Ngay', 'asc');
        
        $timeKeepings = $timeKeepingsQuery->paginate(15);
        
        // Thêm các thuộc tính được tính toán cho mỗi bản ghi
        foreach($timeKeepings as $timeKeeping) {
            $timeKeeping->formattedDate = $timeKeeping->Nam . '-' . str_pad($timeKeeping->Thang, 2, '0', STR_PAD_LEFT) . '-' . str_pad($timeKeeping->Ngay, 2, '0', STR_PAD_LEFT);
            $timeKeeping->formattedTimeIn = str_pad($timeKeeping->Giovao, 2, '0', STR_PAD_LEFT) . ':' . str_pad($timeKeeping->Phutvao, 2, '0', STR_PAD_LEFT);
            $timeKeeping->formattedTimeOut = str_pad($timeKeeping->GioRa, 2, '0', STR_PAD_LEFT) . ':' . str_pad($timeKeeping->PhutRa, 2, '0', STR_PAD_LEFT);
            
            // Tính số giờ làm việc
            $startTime = $timeKeeping->Giovao * 60 + $timeKeeping->Phutvao;
            $endTime = $timeKeeping->GioRa * 60 + $timeKeeping->PhutRa;
            
            if ($endTime < $startTime) {
                // Trường hợp ca đêm, qua ngày mới
                $endTime += 24 * 60;
            }
            
            $minutes = $endTime - $startTime;
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            
            $timeKeeping->workHours = [
                'hours' => $hours,
                'minutes' => $mins,
                'total_minutes' => $minutes,
                'formatted' => $hours . 'h' . ($mins > 0 ? ' ' . $mins . 'm' : '')
            ];
            
            // QUAN TRỌNG: Tự động sửa trạng thái nếu có giờ làm việc mà trạng thái vẫn là vắng mặt
            if ($minutes > 0 && $timeKeeping->TrangThai == TimeKeeping::ATTENDANCE_ABSENT) {
                // Xác định trạng thái dựa trên thời gian làm việc
                $newStatus = TimeKeeping::ATTENDANCE_ONTIME; // Mặc định là đúng giờ
                
                if ($minutes > 9 * 60) { // Nếu làm việc hơn 9 tiếng
                    $newStatus = TimeKeeping::ATTENDANCE_OVERTIME;
                } else if ($timeKeeping->Giovao > 8 || ($timeKeeping->Giovao == 8 && $timeKeeping->Phutvao > 15)) {
                    $newStatus = TimeKeeping::ATTENDANCE_LATE;
                } else if (($timeKeeping->GioRa < 17 || ($timeKeeping->GioRa == 17 && $timeKeeping->PhutRa < 0)) && 
                          ($timeKeeping->GioRa > 9 || ($timeKeeping->GioRa == 9 && $timeKeeping->PhutRa > 0))) {
                    $newStatus = TimeKeeping::ATTENDANCE_EARLY_LEAVE;
                }
                
                // Cập nhật trạng thái trong cơ sở dữ liệu
                TimeKeeping::where('MABC', $timeKeeping->MABC)
                    ->update(['TrangThai' => $newStatus]);
                
                // Cập nhật trạng thái cho đối tượng hiện tại
                $timeKeeping->TrangThai = $newStatus;
                
                // Thêm log để theo dõi
                \Illuminate\Support\Facades\Log::info("Đã tự động cập nhật trạng thái chấm công ID #{$timeKeeping->MABC} từ 'Vắng mặt' sang '{$newStatus}'");
            }
            
            // Thêm trạng thái hiển thị
            switch($timeKeeping->TrangThai) {
                case TimeKeeping::ATTENDANCE_ONTIME:
                    $timeKeeping->attendanceStatusText = 'Đúng giờ';
                    $timeKeeping->attendanceStatusClass = 'bg-success';
                    break;
                case TimeKeeping::ATTENDANCE_LATE:
                    $timeKeeping->attendanceStatusText = 'Đi muộn';
                    $timeKeeping->attendanceStatusClass = 'bg-warning';
                    break;
                case TimeKeeping::ATTENDANCE_EARLY_LEAVE:
                    $timeKeeping->attendanceStatusText = 'Về sớm';
                    $timeKeeping->attendanceStatusClass = 'bg-warning';
                    break;
                case TimeKeeping::ATTENDANCE_OVERTIME:
                    $timeKeeping->attendanceStatusText = 'Làm thêm giờ';
                    $timeKeeping->attendanceStatusClass = 'bg-info';
                    break;
                case TimeKeeping::ATTENDANCE_ABSENT:
                    $timeKeeping->attendanceStatusText = 'Vắng mặt';
                    $timeKeeping->attendanceStatusClass = 'bg-danger';
                    break;
                default:
                    $timeKeeping->attendanceStatusText = 'Không xác định';
                    $timeKeeping->attendanceStatusClass = 'bg-secondary';
            }
        }
        
        // Thống kê
        $statistics = [
            'total_hours' => DB::table('bangcong')
                ->where('Nam', $year)
                ->where('Thang', $month)
                ->where('TrangThai', '!=', TimeKeeping::STATUS_DELETED)
                ->when($employeeId, function($query) use ($employeeId) {
                    return $query->where('MaNV', $employeeId);
                })
                ->when($positionId, function($query) use ($positionId) {
                    return $query->whereExists(function($q) use ($positionId) {
                        $q->select(DB::raw(1))
                            ->from('nhanvien')
                            ->whereColumn('nhanvien.MaNV', 'bangcong.MaNV')
                            ->where('nhanvien.IDCV', $positionId);
                    });
                })
                ->selectRaw('SUM(CASE 
                    WHEN GioRa < Giovao THEN (GioRa + 24) * 60 + PhutRa - (Giovao * 60 + Phutvao)
                    ELSE GioRa * 60 + PhutRa - (Giovao * 60 + Phutvao)
                END) / 60 as total_hours')
                ->value('total_hours') ?? 0,
                
            'ontime_count' => TimeKeeping::where('Nam', $year)
                ->where('Thang', $month)
                ->where('TrangThai', TimeKeeping::ATTENDANCE_ONTIME)
                ->when($employeeId, function($query) use ($employeeId) {
                    return $query->where('MaNV', $employeeId);
                })
                ->when($positionId, function($query) use ($positionId) {
                    return $query->whereHas('employee', function($q) use ($positionId) {
                        $q->where('IDCV', $positionId);
                    });
                })
                ->count(),
                
            'late_early_count' => TimeKeeping::whereIn('TrangThai', [TimeKeeping::ATTENDANCE_LATE, TimeKeeping::ATTENDANCE_EARLY_LEAVE])
                ->where('Nam', $year)
                ->where('Thang', $month)
                ->when($employeeId, function($query) use ($employeeId) {
                    return $query->where('MaNV', $employeeId);
                })
                ->when($positionId, function($query) use ($positionId) {
                    return $query->whereHas('employee', function($q) use ($positionId) {
                        $q->where('IDCV', $positionId);
                    });
                })
                ->count(),
                
            'absent_count' => TimeKeeping::where('TrangThai', TimeKeeping::ATTENDANCE_ABSENT)
                ->where('Nam', $year)
                ->where('Thang', $month)
                ->when($employeeId, function($query) use ($employeeId) {
                    return $query->where('MaNV', $employeeId);
                })
                ->when($positionId, function($query) use ($positionId) {
                    return $query->whereHas('employee', function($q) use ($positionId) {
                        $q->where('IDCV', $positionId);
                    });
                })
                ->count(),
        ];
        
        // Các dữ liệu cho form và bộ lọc
        $employees = Employee::where('TrangThai', Employee::STATUS_ACTIVE)->orderBy('TenNV')->get();
        $positions = Position::where('TrangThai', Position::STATUS_ACTIVE)->orderBy('TenCV')->get();
        $workTypes = WorkType::where('TrangThai', WorkType::STATUS_ACTIVE)->orderBy('TenLC')->get();
        
        // Kiểm tra có bản ghi nào không chính xác không
        $invalidRecords = DB::table('bangcong')
            ->where('Nam', $year)
            ->where('Thang', $month)
            ->where('TrangThai', TimeKeeping::ATTENDANCE_ABSENT)
            ->whereRaw('(Giovao > 0 OR Phutvao > 0 OR GioRa > 0 OR PhutRa > 0)')
            ->count();

        if ($invalidRecords > 0) {
            // Thêm thông báo cảnh báo
            session()->flash('warning', "Có {$invalidRecords} bản ghi có trạng thái không chính xác. <a href='" . route('admin.worktime.fix-all') . "' class='btn btn-warning btn-sm ml-2'><i class='fas fa-sync'></i> Sửa ngay</a>");
        }

        return view('admin.worktime', compact(
            'timeKeepings', 
            'employees', 
            'positions', 
            'workTypes', 
            'month', 
            'year', 
            'statistics'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('TrangThai', Employee::STATUS_ACTIVE)->orderBy('TenNV')->get();
        $workTypes = WorkType::where('TrangThai', WorkType::STATUS_ACTIVE)->orderBy('TenLC')->get();
        
        // Ngày hiện tại
        $today = Carbon::now();
        $date = $today->format('Y-m-d');
        $time = $today->format('H:i');
        
        return view('admin.worktime_create', compact('employees', 'workTypes', 'date', 'time'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate đầu vào
        $validated = $request->validate([
            'MaNV' => 'required|exists:nhanvien,MaNV',
            'date' => 'required|date',
            'timeIn' => 'required|string',
            'timeOut' => 'required|string',
            'IDLC' => 'required|exists:loaicong,IDLC',
            'status' => 'required|integer|in:' . implode(',', [
                TimeKeeping::ATTENDANCE_ONTIME,
                TimeKeeping::ATTENDANCE_LATE,
                TimeKeeping::ATTENDANCE_EARLY_LEAVE,
                TimeKeeping::ATTENDANCE_OVERTIME,
                TimeKeeping::ATTENDANCE_ABSENT
            ]),
        ]);
        
        // Chuyển đổi ngày giờ thành các thành phần riêng lẻ
        $date = Carbon::parse($validated['date']);
        
        // Xử lý giờ vào
        $timeIn = Carbon::parse($validated['timeIn']);
        $giovao = (int) $timeIn->format('H');
        $phutvao = (int) $timeIn->format('i');
        
        // Xử lý giờ ra
        $timeOut = Carbon::parse($validated['timeOut']);
        $giora = (int) $timeOut->format('H');
        $phutra = (int) $timeOut->format('i');
        
        // Tự động tính toán trạng thái dựa trên giờ vào và giờ ra
        if ($validated['status'] == TimeKeeping::ATTENDANCE_ABSENT && 
            ($giovao > 0 || $phutvao > 0 || $giora > 0 || $phutra > 0)) {
            // Nếu có giờ vào và giờ ra nhưng trạng thái đang là vắng mặt, 
            // cập nhật lại trạng thái thành đúng giờ
            $validated['status'] = TimeKeeping::ATTENDANCE_ONTIME;
            
            // Hoặc tính toán dựa trên thời gian làm việc và giờ vào/ra
            $startTime = $giovao * 60 + $phutvao;
            $endTime = $giora * 60 + $phutra;
            
            // Nếu ca đêm
            if ($endTime < $startTime) {
                $endTime += 24 * 60;
            }
            
            $workMinutes = $endTime - $startTime;
            
            if ($workMinutes > 9 * 60) { // Nếu làm việc hơn 9 tiếng
                $validated['status'] = TimeKeeping::ATTENDANCE_OVERTIME;
            } else if ($giovao > 8 || ($giovao == 8 && $phutvao > 15)) { // Vào sau 8:15
                $validated['status'] = TimeKeeping::ATTENDANCE_LATE;
            } else if (($giora < 17 || ($giora == 17 && $phutra < 0)) && 
                       ($giora > 9 || ($giora == 9 && $phutra > 0))) { // Ra trước 17:00 và sau 9:00
                $validated['status'] = TimeKeeping::ATTENDANCE_EARLY_LEAVE;
            } else {
                $validated['status'] = TimeKeeping::ATTENDANCE_ONTIME;
            }
        }
        
        // Kiểm tra xem đã có bản ghi nào cho ngày và nhân viên này chưa
        $existingRecord = TimeKeeping::where('Nam', $date->year)
            ->where('Thang', $date->month)
            ->where('Ngay', $date->day)
            ->where('MaNV', $validated['MaNV'])
            ->where('TrangThai', '!=', TimeKeeping::STATUS_DELETED)
            ->first();
            
        if ($existingRecord) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã tồn tại bản ghi chấm công cho nhân viên này vào ngày ' . $date->format('d/m/Y'));
        }
        
        // Tạo bản ghi mới
        try {
            // Xác định trạng thái dựa trên giờ vào/ra
            $startTime = $giovao * 60 + $phutvao;
            $endTime = $giora * 60 + $phutra;
            if ($endTime < $startTime) {
                $endTime += 24 * 60;
            }
            $minutes = $endTime - $startTime;
            
            // Nếu có thời gian làm việc nhưng trạng thái đang là vắng mặt, tự động cập nhật
            if ($minutes > 0 && $validated['status'] == TimeKeeping::ATTENDANCE_ABSENT) {
                if ($minutes > 9 * 60) {
                    $validated['status'] = TimeKeeping::ATTENDANCE_OVERTIME;
                } else if ($giovao > 8 || ($giovao == 8 && $phutvao > 15)) {
                    $validated['status'] = TimeKeeping::ATTENDANCE_LATE;
                } else if (($giora < 17 || ($giora == 17 && $phutra < 0)) && 
                          ($giora > 9 || ($giora == 9 && $phutra > 0))) {
                    $validated['status'] = TimeKeeping::ATTENDANCE_EARLY_LEAVE;
                } else {
                    $validated['status'] = TimeKeeping::ATTENDANCE_ONTIME;
                }
            }
            
            TimeKeeping::create([
                'Nam' => $date->year,
                'Thang' => $date->month,
                'Ngay' => $date->day,
                'Giovao' => $giovao,
                'Phutvao' => $phutvao,
                'GioRa' => $giora,
                'PhutRa' => $phutra,
                'MaNV' => $validated['MaNV'],
                'IDLC' => $validated['IDLC'],
                'TrangThai' => $validated['status'],
                'TrangThaiChamCong' => isset($validated['TrangThaiChamCong']) ? 
                    $validated['TrangThaiChamCong'] : 
                    TimeKeeping::MANUAL_ATTENDANCE
            ]);
            
            return redirect()->route('admin.worktime.index', [
                'month' => $date->month,
                'year' => $date->year
            ])->with('success', 'Đã thêm bản ghi chấm công thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi khi tạo bản ghi chấm công: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimeKeeping $timeKeeping)
    {
        $employees = Employee::where('TrangThai', Employee::STATUS_ACTIVE)->orderBy('TenNV')->get();
        $workTypes = WorkType::where('TrangThai', WorkType::STATUS_ACTIVE)->orderBy('TenLC')->get();
        
        // Định dạng ngày và giờ cho form
        $date = Carbon::createFromDate($timeKeeping->Nam, $timeKeeping->Thang, $timeKeeping->Ngay)->format('Y-m-d');
        $timeIn = str_pad($timeKeeping->Giovao, 2, '0', STR_PAD_LEFT) . ':' . str_pad($timeKeeping->Phutvao, 2, '0', STR_PAD_LEFT);
        $timeOut = str_pad($timeKeeping->GioRa, 2, '0', STR_PAD_LEFT) . ':' . str_pad($timeKeeping->PhutRa, 2, '0', STR_PAD_LEFT);
        
        return view('admin.worktime_edit', compact('timeKeeping', 'employees', 'workTypes', 'date', 'timeIn', 'timeOut'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimeKeeping $timeKeeping)
    {
        // Validate đầu vào - tương tự như phương thức store
        $validated = $request->validate([
            'MaNV' => 'required|exists:nhanvien,MaNV',
            'date' => 'required|date',
            'timeIn' => 'required|string',
            'timeOut' => 'required|string',
            'IDLC' => 'required|exists:loaicong,IDLC',
            'status' => 'required|integer|in:' . implode(',', [
                TimeKeeping::ATTENDANCE_ONTIME,
                TimeKeeping::ATTENDANCE_LATE,
                TimeKeeping::ATTENDANCE_EARLY_LEAVE,
                TimeKeeping::ATTENDANCE_OVERTIME,
                TimeKeeping::ATTENDANCE_ABSENT
            ]),
        ]);
        
        // Chuyển đổi ngày giờ thành các thành phần riêng lẻ
        $date = Carbon::parse($validated['date']);
        
        // Xử lý giờ vào
        $timeIn = Carbon::parse($validated['timeIn']);
        $giovao = (int) $timeIn->format('H');
        $phutvao = (int) $timeIn->format('i');
        
        // Xử lý giờ ra
        $timeOut = Carbon::parse($validated['timeOut']);
        $giora = (int) $timeOut->format('H');
        $phutra = (int) $timeOut->format('i');
        
        // Tự động tính toán trạng thái dựa trên giờ vào và giờ ra
        if ($validated['status'] == TimeKeeping::ATTENDANCE_ABSENT && 
            ($giovao > 0 || $phutvao > 0 || $giora > 0 || $phutra > 0)) {
            // Nếu có giờ vào và giờ ra nhưng trạng thái đang là vắng mặt, 
            // cập nhật lại trạng thái thành đúng giờ
            $validated['status'] = TimeKeeping::ATTENDANCE_ONTIME;
            
            // Hoặc tính toán dựa trên thời gian làm việc và giờ vào/ra
            $startTime = $giovao * 60 + $phutvao;
            $endTime = $giora * 60 + $phutra;
            
            // Nếu ca đêm
            if ($endTime < $startTime) {
                $endTime += 24 * 60;
            }
            
            $workMinutes = $endTime - $startTime;
            
            if ($workMinutes > 9 * 60) { // Nếu làm việc hơn 9 tiếng
                $validated['status'] = TimeKeeping::ATTENDANCE_OVERTIME;
            } else if ($giovao > 8 || ($giovao == 8 && $phutvao > 15)) { // Vào sau 8:15
                $validated['status'] = TimeKeeping::ATTENDANCE_LATE;
            } else if (($giora < 17 || ($giora == 17 && $phutra < 0)) && 
                       ($giora > 9 || ($giora == 9 && $phutra > 0))) { // Ra trước 17:00 và sau 9:00
                $validated['status'] = TimeKeeping::ATTENDANCE_EARLY_LEAVE;
            } else {
                $validated['status'] = TimeKeeping::ATTENDANCE_ONTIME;
            }
        }
        
        // Kiểm tra xem đã có bản ghi nào khác cho ngày và nhân viên này chưa
        $existingRecord = TimeKeeping::where('Nam', $date->year)
            ->where('Thang', $date->month)
            ->where('Ngay', $date->day)
            ->where('MaNV', $validated['MaNV'])
            ->where('MABC', '!=', $timeKeeping->MABC)
            ->where('TrangThai', '!=', TimeKeeping::STATUS_DELETED)
            ->first();
            
        if ($existingRecord) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã tồn tại bản ghi chấm công khác cho nhân viên này vào ngày ' . $date->format('d/m/Y'));
        }
        
        // Cập nhật bản ghi
        try {
            $timeKeeping->update([
                'Nam' => $date->year,
                'Thang' => $date->month,
                'Ngay' => $date->day,
                'Giovao' => $giovao,
                'Phutvao' => $phutvao,
                'GioRa' => $giora,
                'PhutRa' => $phutra,
                'MaNV' => $validated['MaNV'],
                'IDLC' => $validated['IDLC'],
                'TrangThai' => $validated['status'],
                'TrangThaiChamCong' => TimeKeeping::MANUAL_ATTENDANCE // Chấm công thủ công khi cập nhật
            ]);
            
            return redirect()->route('admin.worktime.index', [
                'month' => $date->month,
                'year' => $date->year
            ])->with('success', 'Đã cập nhật bản ghi chấm công thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi khi cập nhật bản ghi chấm công: ' . $e->getMessage());
        }
    }

    /**
     * Xóa mềm bản ghi (cập nhật trạng thái)
     */
    public function destroy(TimeKeeping $timeKeeping)
    {
        try {
            $timeKeeping->update(['TrangThai' => TimeKeeping::STATUS_DELETED]);
            return redirect()->route('admin.worktime.index', [
                'month' => $timeKeeping->Thang,
                'year' => $timeKeeping->Nam
            ])->with('success', 'Đã xóa bản ghi chấm công thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi xóa bản ghi: ' . $e->getMessage());
        }
    }

    /**
     * Lưu cấu hình giờ làm
     */
    public function saveConfig(Request $request)
    {
        // Xử lý lưu cấu hình giờ làm
        // Lưu vào bảng cấu hình hoặc session
        
        return redirect()->route('admin.worktime.index')
            ->with('success', 'Đã lưu cấu hình giờ làm thành công!');
    }

    /**
     * Tạo lịch làm việc tự động
     */
    public function generate(Request $request)
    {
        // Validate đầu vào
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2000,2100',
            'employees' => 'required|array',
            'employees.*' => 'exists:nhanvien,MaNV',
            'IDLC' => 'required|exists:loaicong,IDLC',
            'Giovao' => 'required|integer|between:0,23',
            'Phutvao' => 'required|integer|between:0,59',
            'GioRa' => 'required|integer|between:0,23',
            'PhutRa' => 'required|integer|between:0,59',
            'workdays' => 'required|array',
            'workdays.*' => 'integer|between:1,7',
        ]);
        
        $month = (int) $validated['month'];
        $year = (int) $validated['year'];
        
        // Lấy tất cả các ngày trong tháng
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $workdays = $validated['workdays'];
        $addedCount = 0;
        
        // Tạo bản ghi cho mỗi nhân viên và mỗi ngày làm việc trong tháng
        foreach ($validated['employees'] as $employeeId) {
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::createFromDate($year, $month, $day);
                $dayOfWeek = $date->dayOfWeek; // 0 (Chủ nhật) đến 6 (Thứ 7)
                
                // Chuyển đổi dayOfWeek từ 0-6 sang 7,1-6 (7: CN, 1: T2, ..., 6: T7)
                $dayOfWeek = ($dayOfWeek == 0) ? 7 : $dayOfWeek;
                
                // Kiểm tra xem ngày này có phải là ngày làm việc không
                if (in_array($dayOfWeek, $workdays)) {
                    // Kiểm tra xem đã có bản ghi nào cho ngày và nhân viên này chưa
                    $existingRecord = TimeKeeping::where('Nam', $year)
                        ->where('Thang', $month)
                        ->where('Ngay', $day)
                        ->where('MaNV', $employeeId)
                        ->where('TrangThai', '!=', TimeKeeping::STATUS_DELETED)
                        ->first();
                        
                    if (!$existingRecord) {
                        // Tạo bản ghi mới
                        TimeKeeping::create([
                            'Nam' => $year,
                            'Thang' => $month,
                            'Ngay' => $day,
                            'Giovao' => $validated['Giovao'],
                            'Phutvao' => $validated['Phutvao'],
                            'GioRa' => $validated['GioRa'],
                            'PhutRa' => $validated['PhutRa'],
                            'MaNV' => $employeeId,
                            'IDLC' => $validated['IDLC'],
                            'TrangThai' => TimeKeeping::ATTENDANCE_ONTIME,
                            'TrangThaiChamCong' => TimeKeeping::AUTO_ATTENDANCE // Chấm công tự động
                        ]);
                        
                        $addedCount++;
                    }
                }
            }
        }
        
        return redirect()->route('admin.worktime.index', [
            'month' => $month,
            'year' => $year
        ])->with('success', "Đã tạo $addedCount bản ghi chấm công tự động!");
    }

    /**
     * Xuất dữ liệu ra file Excel
     */
    public function export(Request $request)
    {
        // Xử lý xuất dữ liệu ra file Excel
        
        return redirect()->route('admin.worktime.index')
            ->with('success', 'Đã xuất dữ liệu thành công!');
    }

    /**
     * Cập nhật trạng thái qua AJAX
     */
    public function updateStatus(Request $request)
    {
        $id = $request->input('id');
        $autoFix = $request->input('auto_fix', false);
        
        $timeKeeping = TimeKeeping::findOrFail($id);
        
        // Tính thời gian làm việc
        $startTime = $timeKeeping->Giovao * 60 + $timeKeeping->Phutvao;
        $endTime = $timeKeeping->GioRa * 60 + $timeKeeping->PhutRa;
        
        if ($endTime < $startTime) {
            $endTime += 24 * 60;
        }
        
        $minutes = $endTime - $startTime;
        
        $newStatus = $timeKeeping->TrangThai;
        
        // Chỉ cập nhật nếu có sai sót: có giờ làm nhưng trạng thái là vắng mặt
        if ($minutes > 0 && $timeKeeping->TrangThai == TimeKeeping::ATTENDANCE_ABSENT && $autoFix) {
            if ($minutes > 9 * 60) {
                $newStatus = TimeKeeping::ATTENDANCE_OVERTIME;
            } else if ($timeKeeping->Giovao > 8 || ($timeKeeping->Giovao == 8 && $timeKeeping->Phutvao > 15)) {
                $newStatus = TimeKeeping::ATTENDANCE_LATE;
            } else if (($timeKeeping->GioRa < 17 || ($timeKeeping->GioRa == 17 && $timeKeeping->PhutRa < 0)) && 
                      ($timeKeeping->GioRa > 9 || ($timeKeeping->GioRa == 9 && $timeKeeping->PhutRa > 0))) {
                $newStatus = TimeKeeping::ATTENDANCE_EARLY_LEAVE;
            } else {
                $newStatus = TimeKeeping::ATTENDANCE_ONTIME;
            }
            
            $timeKeeping->update(['TrangThai' => $newStatus]);
        }
        
        // Chuẩn bị thông tin để trả về
        $statusText = '';
        $statusClass = '';
        
        switch($newStatus) {
            case TimeKeeping::ATTENDANCE_ONTIME:
                $statusText = 'Đúng giờ';
                $statusClass = 'bg-success';
                break;
            case TimeKeeping::ATTENDANCE_LATE:
                $statusText = 'Đi muộn';
                $statusClass = 'bg-warning';
                break;
            case TimeKeeping::ATTENDANCE_EARLY_LEAVE:
                $statusText = 'Về sớm';
                $statusClass = 'bg-warning';
                break;
            case TimeKeeping::ATTENDANCE_OVERTIME:
                $statusText = 'Làm thêm giờ';
                $statusClass = 'bg-info';
                break;
            case TimeKeeping::ATTENDANCE_ABSENT:
                $statusText = 'Vắng mặt';
                $statusClass = 'bg-danger';
                break;
            default:
                $statusText = 'Không xác định';
                $statusClass = 'bg-secondary';
        }
        
        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'statusText' => $statusText,
            'statusClass' => $statusClass
        ]);
    }

    /**
     * Sửa tất cả trạng thái không chính xác
     */
    public function fixAllStatuses()
    {
        // Sửa tất cả bản ghi có giờ làm việc nhưng trạng thái là vắng mặt
        $updated = DB::update("
            UPDATE bangcong 
            SET TrangThai = CASE 
                WHEN ((GioRa * 60 + PhutRa) - (Giovao * 60 + Phutvao)) > 9 * 60 
                    OR ((GioRa < Giovao) AND ((GioRa + 24) * 60 + PhutRa) - (Giovao * 60 + Phutvao) > 9 * 60) 
                THEN 4  -- Làm thêm giờ
                WHEN Giovao > 8 OR (Giovao = 8 AND Phutvao > 15) THEN 2  -- Đi muộn
                WHEN (GioRa < 17 OR (GioRa = 17 AND PhutRa < 0)) AND (GioRa > 9 OR (GioRa = 9 AND PhutRa > 0)) THEN 3  -- Về sớm
                ELSE 1  -- Đúng giờ
            END
            WHERE TrangThai = 0  -- Vắng mặt
            AND (Giovao > 0 OR Phutvao > 0 OR GioRa > 0 OR PhutRa > 0)  -- Có giờ làm việc
        ");
        
        return redirect()->route('admin.worktime.index')
            ->with('success', "Đã cập nhật {$updated} bản ghi có trạng thái không chính xác.");
    }
}
