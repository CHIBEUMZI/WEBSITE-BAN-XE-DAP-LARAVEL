<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ProductClientController extends Controller
{
    public function index(Request $request)
    {
        // Bắt đầu truy vấn
    $categories = Product::select('category')->distinct()->pluck('category');

    $categoryProducts = [];

    foreach ($categories as $category) {
        $pageName = 'category' . md5($category); // Tránh lỗi tên URL nếu có dấu cách, tiếng Việt

        $products = Product::where('category', $category)
            ->paginate(4, ['*'], $pageName);

        $categoryProducts[$category] = $products;
    }

    return view('client.home.index', compact('categories', 'categoryProducts'));

    }

    public function find(Request $request)
    {
        // Bắt đầu truy vấn
        $query = Product::query();

        // Kiểm tra nếu có từ khóa tìm kiếm (search)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%");
            });
        }

        // Kiểm tra nếu có danh mục (category)
        if ($request->filled('category')) {
            $category = $request->query('category');
            $query->where('category', $category);
        }

        // Lấy danh sách sản phẩm theo các điều kiện đã chọn
        $products = $query->paginate(4);
        return view('client.component.search', compact('products'));
        }

        public function details($id)
        {
            $product = Product::findOrFail($id);
            return view('client.products.index', compact('product'));
        }

        public function buy(Request $request, $id)
        {
            $product = Product::findOrFail($id);
            $quantity = $request->input('quantity', 1);
            return view('client.products.buy', compact('product','quantity'));
        }


    public function processBuy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'payment_method' => 'required|string|max:50',
        ]);

        if ($product->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Số lượng sản phẩm không đủ'])->withInput();
        }

        $totalAmount = $product->price * $validated['quantity'];

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'Chưa thanh toán',
            'status' => 'Đang xử lý',
            'shipping_address' => $validated['customer_address'],
            'shipping_fee' => 0,
            'note' => null,
        ]);

        // Chi tiết đơn hàng
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'price' => $product->price,
        ]);

        if ($validated['payment_method'] === 'MoMo') {
            return $this->redirectToMomo($order);
        }
        if ($validated['payment_method'] === 'VNPay') {
        return redirect()->route('vnpay.payment', ['order_id' => $order->id]);
        }

        // Thanh toán khi nhận
        $product->stock -= $validated['quantity'];
        $product->save();

        $order->update([
            'payment_status' => 'Chưa thanh toán',
            'status' => 'Đang xử lý',
        ]);

        return redirect()->route('client.home')->with('success', 'Mua hàng thành công!');
    }


public function vnpay_payment(Request $request)
{
    $order = Order::findOrFail($request->order_id);

    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = route('client.home');
    $vnp_TmnCode = "0TJ8WWM6";
    $vnp_HashSecret = "OOPXO13LMQNNCRB6SMNBI5N3UZ8UD8XR";

    $vnp_TxnRef = $order->id;
    $vnp_Amount = $order->total_amount * 100;
    $vnp_Locale = 'vn';
    $vnp_IpAddr = $request->ip(); // tốt hơn $_SERVER
    $now = Carbon::now('Asia/Ho_Chi_Minh');

    $vnp_CreateDate = $now->format('YmdHis');
    $vnp_ExpireDate = $now->copy()->addMinutes(15)->format('YmdHis');

    $inputData = [
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => $vnp_CreateDate,
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => "Thanh toán GD: " . $vnp_TxnRef,
        "vnp_OrderType" => "other",
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
        "vnp_ExpireDate" => $vnp_ExpireDate,
    ];

    ksort($inputData);
    $hashdata = urldecode(http_build_query($inputData));
    $query = http_build_query($inputData);

    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    $vnp_Url .= "?" . $query . "&vnp_SecureHash=" . $vnpSecureHash;

    return redirect($vnp_Url);
}


    // Chuyển hướng đến MoMo
    private function redirectToMomo($order)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán đơn hàng #" . $order->id;
        $amount = (string) $order->total_amount;
        $orderId = $order->id . '_' . now()->timestamp;
        $redirectUrl = route('momo.success');
        $ipnUrl = route('momo.ipn');
        $extraData = "";
        $requestId = now()->timestamp . '';
        $requestType = "payWithATM";

        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "BikeShop",
            "storeId" => "MoMoTestStore",
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

    // Xử lý sau khi thanh toán MoMo thành công
    public function momoSuccess(Request $request)
    {
    logger('MoMo success data:', $request->all());

    if ($request->input('resultCode') === '0') {
        $orderIdParts = explode('_', $request->input('orderId'));
        $orderId = $orderIdParts[0] ?? null;

        // Load cả items và product liên quan
        $order = Order::with('items.product')->find($orderId);

        if (!$order) {
            return redirect()->route('client.home')->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Cập nhật trạng thái đơn hàng
        $order->update([
            'payment_status' => 'Đã thanh toán',
            'status' => 'Đang xử lý'
        ]);

        // Trừ kho sản phẩm
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->stock -= $item->quantity;
                $product->save();
            }
        }

        return redirect()->route('client.home')->with('success', 'Thanh toán MoMo thành công!');
    }

    return redirect()->route('client.home')->with('error', 'Thanh toán thất bại hoặc bị huỷ.');
    }

    // Gửi POST request đến MoMo
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

}

