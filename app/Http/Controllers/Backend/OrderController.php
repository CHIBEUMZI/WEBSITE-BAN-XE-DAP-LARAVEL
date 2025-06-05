<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();
    
        // Tìm kiếm theo tên hoặc email
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('user_id', 'like', "%$keyword%")
                  ->orWhere('order_date', 'like', "%$keyword%")
                  ->orWhere('status', 'like', "%$keyword%");
            });
        }
        $orders = $query->paginate(10)->appends($request->all());
        return view('backend.orders.index',compact('orders'));
    }

    public function confirmCancel(Request $request,$id)
    {
        $order = Order::findOrFail($id);

        if ($request->action === 'delivered' && $order->status === 'Đang xử lí') {
            $order->status = 'Giao hàng';
        } elseif ($request->action === 'cancel' && $order->status === 'Chờ xác nhận hủy') {
            $order->status = 'Đã hủy';
        }

        $order->save();

        return redirect()->back()->with('success', 'Đã xác nhận thành công.');
    }

}
