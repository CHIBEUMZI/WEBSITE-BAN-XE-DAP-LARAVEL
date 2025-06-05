<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;



class CartClientController extends Controller
{
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);

        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $id)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        return view('client.cart.index', compact('cartItems'));
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $request->id)->first();

            if ($cartItem) {
                $cartItem->quantity = $request->quantity;
                $cartItem->save();
            }
        }

        return redirect()->route('cart.index')->with('success', 'Cập nhật số lượng thành công!');
    }

    public function remove($id)
    {
        Cart::where('user_id', Auth::id())->where('product_id', $id)->delete();
        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function buyCart()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.home')->with('error', 'Giỏ hàng trống.');
        }

        return view('client.cart.checkout', compact('cartItems'));
    }

    public function processBuyCart(Request $request)
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng đang trống.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'payment_method' => 'required|string|max:500',
        ]);

        $totalAmount = 0;

        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->withErrors(['quantity' => "Sản phẩm {$item->product->name} không đủ hàng."]);
            }

            $totalAmount += $item->product->price * $item->quantity;
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'Chưa thanh toán',
            'status' => 'Đang xử lí',
            'shipping_address' => $validated['customer_address'],
            'shipping_fee' => 0,
            'note' => null,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            $item->product->stock -= $item->quantity;
            $item->product->save();
        }

        // Xóa giỏ hàng sau khi đặt
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('client.home')->with('success', 'Đặt hàng thành công! Đơn hàng của bạn đang được xử lý.');
    }
}
