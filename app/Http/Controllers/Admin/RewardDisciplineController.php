<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardPenalty;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RewardDisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get active employees
        $employees = Employee::with('department')
            ->where('TrangThai', Employee::STATUS_ACTIVE)
            ->get();
            
        // Get all reward and discipline records with employees
        $rewardDisciplines = RewardPenalty::with('employee.department')
            ->latest('Ngay')
            ->get();
            
        // Separate rewards and penalties
        $rewards = RewardPenalty::with('employee.department')
            ->rewards()
            ->latest('Ngay')
            ->get();
            
        $penalties = RewardPenalty::with('employee.department')
            ->penalties()
            ->latest('Ngay')
            ->get();
            
        return view('admin.reward_discipline', compact('employees', 'rewardDisciplines', 'rewards', 'penalties'));
    }

    /**
     * Store a new reward record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeReward(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:nhanvien,MaNV',
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date_format:d/m/Y',
        ]);
        
        try {
            DB::beginTransaction();
            
            $rewardPenalty = new RewardPenalty();
            $rewardPenalty->MaNV = $validated['employee_id'];
            $rewardPenalty->TieuDe = $validated['title'];
            $rewardPenalty->NoiDung = $validated['description'] ?? '';
            $rewardPenalty->SoTien = $validated['amount'];
            $rewardPenalty->Ngay = Carbon::createFromFormat('d/m/Y', $validated['date'])->startOfDay();
            $rewardPenalty->{'LoaiKT/KL'} = RewardPenalty::TYPE_REWARD;
            // Thay đổi cách tạo SoKTKL để phù hợp với kiểu integer
            $rewardPenalty->SoKTKL = intval(date('YmdHis')) % 1000000; // Lấy 6 số cuối
            
            $rewardPenalty->save();
            
            DB::commit();
            
            return redirect()->route('admin.reward-discipline.index')
                ->with('success', 'Thêm khen thưởng thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Lỗi khi thêm khen thưởng: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store a new discipline record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePenalty(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:nhanvien,MaNV',
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date_format:d/m/Y',
        ]);
        
        try {
            DB::beginTransaction();
            
            $rewardPenalty = new RewardPenalty();
            $rewardPenalty->MaNV = $validated['employee_id'];
            $rewardPenalty->TieuDe = $validated['title'];
            $rewardPenalty->NoiDung = $validated['description'] ?? '';
            $rewardPenalty->SoTien = $validated['amount'];
            $rewardPenalty->Ngay = Carbon::createFromFormat('d/m/Y', $validated['date'])->startOfDay();
            $rewardPenalty->{'LoaiKT/KL'} = RewardPenalty::TYPE_PENALTY;
            // Thay đổi cách tạo SoKTKL để phù hợp với kiểu integer
            $rewardPenalty->SoKTKL = intval(date('YmdHis')) % 1000000; // Lấy 6 số cuối
            
            $rewardPenalty->save();
            
            DB::commit();
            
            return redirect()->route('admin.reward-discipline.index')
                ->with('success', 'Thêm kỷ luật thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Lỗi khi thêm kỷ luật: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified reward.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:nhanvien,MaNV',
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date_format:d/m/Y',
        ]);
        
        try {
            DB::beginTransaction();
            
            $rewardPenalty = RewardPenalty::findOrFail($id);
            $rewardPenalty->MaNV = $validated['employee_id'];
            $rewardPenalty->TieuDe = $validated['title'];
            $rewardPenalty->NoiDung = $validated['description'] ?? '';
            $rewardPenalty->SoTien = $validated['amount'];
            $rewardPenalty->Ngay = Carbon::createFromFormat('d/m/Y', $validated['date'])->startOfDay();
            
            $rewardPenalty->save();
            
            DB::commit();
            
            $typeText = $rewardPenalty->isReward() ? 'khen thưởng' : 'kỷ luật';
            
            return redirect()->route('admin.reward-discipline.index')
                ->with('success', "Cập nhật $typeText thành công!");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Lỗi khi cập nhật: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get reward/discipline details for editing
     */
    public function show($id)
    {
        $rewardPenalty = RewardPenalty::with('employee.department')->findOrFail($id);
        return response()->json($rewardPenalty);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $rewardPenalty = RewardPenalty::findOrFail($id);
            $typeText = $rewardPenalty->isReward() ? 'khen thưởng' : 'kỷ luật';
            
            $rewardPenalty->delete();
            
            return redirect()->route('admin.reward-discipline.index')
                ->with('success', "Xóa $typeText thành công!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi khi xóa: ' . $e->getMessage());
        }
    }
}