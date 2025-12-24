<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;


class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();
    
        // Tìm kiếm theo tên hoặc email
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhere('phone', 'like', "%$keyword%")
                  ->orWhere('position', 'like', "%$keyword%");
            });
        }
        $employees = $query->paginate(10)->appends($request->all());
        return view('backend.employees.index',compact('employees'));
    }

    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate(
            [
                'name'     => 'required|string|min:3|max:50',
                'phone'    => 'required|regex:/^[0-9]{10}$/|unique:employees,phone',
                'position' => 'required|string|max:255',
                'address'  => 'required|string|max:255',
                'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'phone.unique' => 'Số điện thoại này đã tồn tại trong hệ thống.',
                'phone.regex'  => 'Số điện thoại phải gồm đúng 10 chữ số.',
            ]
        );
    
        // Lưu ảnh nếu có
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Nếu người dùng tải lên ảnh, lưu vào thư mục 'public/images/employees'
            $imagePath = $request->file('image')->store('images/employees', 'public');
        }
    
        employee::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'position' => $validated['position'],
            'address' => $validated['address'],
            'image' => $imagePath, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);
    
        // Chuyển hướng về trang danh sách sản phẩm với thông báo thành công
        return redirect()->route('employees.index')->with('success', 'Nhân viên đã được thêm!');
    }
    
    public function create()
    {
        return view('backend.employees.create');
    }

    // public function update(Request $request, $id){
    //     $request->validate([
    //         'name' => 'required',
    //         'phone' => 'required',
    //         'position' => 'required',
    //         'address' => 'required',
    //         'image' => 'required',
    //         'update_at' =>now(),
    //     ]);
    //     $employee = employee::findOrFail($id);
    //     $employee->update($request->all());

    // return redirect()->route('employees.index')->with('success', 'Cập nhật sản phẩm thành công.');
    // }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'position' => 'required',
            'address' => 'required',
            'image' => 'required',
            'update_at' =>now(),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $employee = employee::findOrFail($id);
        $employee->update($request->all());
        $data = $request->only(['name', 'phone', 'position', 'address']);
    
        // Nếu có ảnh mới được upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/employees', 'public');
            $data['image'] = $imagePath;
        }
    
        $employee->update($data);
    
        return redirect()->route('employees.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }


    public function edit($id){
        $employee = employee::findOrFail($id);
        return view('backend.employees.edit', compact('employee'));
    }


    public function destroy($id)
    {
    $employee = employee::findOrFail($id);
    $employee->delete();

    return redirect()->route('employees.index')->with('success', 'Xóa sản phẩm thành công.');
    }
}
