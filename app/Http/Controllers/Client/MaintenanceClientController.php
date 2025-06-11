<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class MaintenanceClientController extends Controller
{

    public function index(){
        return view('client.maintenance.create');
    }

    public function store(Request $request)
    {
    // Validate các trường thông thường trước
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'nullable|email',
        'product_sku' => 'required|string',
        'issue_description' => 'required|string',
        'preferred_date' => 'required|date',
        'address' => 'required|string',
    ]);

    // Kiểm tra tồn tại sku trong bảng products
    $product = Product::where('sku', $request->product_sku)->first();

    if (!$product) {
        // Nếu không có sku này thì quay lại trang trước với lỗi rõ ràng
        return redirect()->back()
            ->withInput()
            ->withErrors(['product_sku' => 'Mã sản phẩm (SKU) không tồn tại trong hệ thống. Vui lòng kiểm tra lại.']);
    }

    // Nếu có sku thì tiếp tục tạo Maintenance
    Maintenance::create([
        'user_id' => auth()->id(),
        'customer_name' => $request->customer_name,
        'phone' => $request->phone,
        'email' => $request->email,
        'product_id' => $product->id,
        'product_sku' => $product->sku,
        'issue_description' => $request->issue_description,
        'preferred_date' => $request->preferred_date,
        'address' => $request->address,
        'status' => 'Đang xử lý',
        'employee_id' => null,
    ]);

    return redirect()->route('client.home')->with('success', 'Gửi yêu cầu bảo trì thành công!');
    }

    public function show()
    {
        $maintenances = Maintenance::with(['product', 'employee'])
                        ->where('user_id', Auth::id()) 
                        ->get();

        return view('client.maintenance.show', compact('maintenances'));
    }

    public function details($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        return view('client.maintenance.detail', compact('maintenance'));
    }
}
