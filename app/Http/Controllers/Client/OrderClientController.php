<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderClientController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                    ->with('orderItems.product')
                    ->orderBy('order_date', 'desc')
                    ->get();

        return view('client.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')
                    ->where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        return view('client.orders.show', compact('order'));
    }

    public function cancel($id)
    {
    $order = Order::findOrFail($id);

    if ($order->status == 'Đã giao' || $order->status == 'Đã hủy') {
        return redirect()->back()->with('error', 'Không thể hủy đơn hàng này.');
    }

    $order->status = 'Chờ xác nhận hủy';
    $order->save();

    return redirect()->back()->with('success', 'Đã gửi yêu cầu hủy đơn. Vui lòng chờ xác nhận từ quản trị viên.');
    }
}
