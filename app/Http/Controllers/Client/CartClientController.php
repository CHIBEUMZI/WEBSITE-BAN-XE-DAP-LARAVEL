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
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'customer_address_detail' => 'required|string|max:255',
            'payment_method' => 'required|string|max:50',
        ]);
        $fullAddress = $validated['customer_address_detail'] . ', ' . $validated['ward'] . ', ' . $validated['district'] . ', ' . $validated['province'];
        $totalAmount = 0;

        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->withErrors(['quantity' => "Sản phẩm {$item->product->name} không đủ hàng."]);
            }

            $totalAmount += $item->product->price * $item->quantity;
        }

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'Chưa thanh toán',
            'status' => 'Chờ thanh toán',
            'shipping_address' => $fullAddress,
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
        }

        if ($validated['payment_method'] === 'MoMo') {
            return $this->redirectToMomo($order);
        }

        // Nếu thanh toán khi nhận hàng (COD)
        foreach ($cartItems as $item) {
            $item->product->stock -= $item->quantity;
            $item->product->save();
        }

        Cart::where('user_id', Auth::id())->delete();

        $order->update([
            'payment_status' => 'Chưa thanh toán',
            'status' => 'Đang xử lý',
        ]);

        return redirect()->route('client.home')->with('success', 'Đặt hàng thành công! Đơn hàng của bạn đang được xử lý.');
    }

    private function redirectToMomo($order)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán đơn hàng #" . $order->id;
        $amount = (string) $order->total_amount;
        $orderId = $order->id . '_' . time(); // phải unique
        $redirectUrl = route('momo.cart.success');
        $ipnUrl = route('momo.cart.ipn');
        $extraData = "";
        $requestId = time() . "";
        $requestType = "payWithATM";

        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "BikeShop",
            'storeId' => "MoMoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        if (isset($jsonResult['payUrl'])) {
            return redirect($jsonResult['payUrl']);
        }

        return redirect()->route('client.home')->with('error', 'Không thể kết nối đến MoMo.');
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
    public function momoSuccess(Request $request)
    {
    if ($request->resultCode == 0) {
        // Xử lý đơn hàng sau khi thanh toán thành công
        $orderId = explode('_', $request->orderId)[0];

        $order = Order::find($orderId);

        if ($order) {
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->stock -= $item->quantity;
                $product->save();
            }

            Cart::where('user_id', Auth::id())->delete();

            $order->update([
                'payment_status' => 'Đã thanh toán',
                'status' => 'Đang xử lý',
            ]);

            return redirect()->route('client.home')->with('success', 'Thanh toán MoMo thành công. Đơn hàng đang được xử lý.');
        }
    }

    return redirect()->route('client.home')->with('error', 'Thanh toán MoMo thất bại hoặc bị huỷ.');
    }



}
