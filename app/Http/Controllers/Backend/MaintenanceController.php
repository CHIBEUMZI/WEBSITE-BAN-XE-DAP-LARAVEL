<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Employee;

class MaintenanceController extends Controller
{
    public function index(Request $request){
        $query = Maintenance::query();
    
        // Tìm kiếm theo tên hoặc email
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('customer_name', 'like', "%$keyword%")
                  ->orWhere('phone', 'like', "%$keyword%")
                  ->orWhere('product_sku', 'like', "%$keyword%");
            });
        }
        $employees = Employee::where('position', 'Nhân viên kỹ thuật')->get();
        $maintenances = $query->paginate(10)->appends($request->all()); // giữ query string khi phân trang
        return view('backend.maintenance.index' ,compact('maintenances','employees'));
    }

    public function assignEmployee(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        $maintenance = Maintenance::findOrFail($id);
        $maintenance->employee_id = $request->employee_id;
        $maintenance->save();

        return redirect()->back()->with('success', 'Cập nhật nhân viên thành công!');
    }

    public function comfirm(Request $request,$id){
                $maintenance = Maintenance::findOrFail($id);

        if ($request->action === 'pending' && $maintenance->status === 'Đang xử lý') {
            $maintenance->status = 'Hoàn thành';
        } elseif ($request->action === 'cancel' && $maintenance->status === 'Đang xử lý') {
            $maintenance->status = 'Hủy';
        }

        $maintenance->save();

        return redirect()->back()->with('success', 'Đã xác nhận thành công.');
    }
    
}
