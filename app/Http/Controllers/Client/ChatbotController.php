<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatbotResponse; 
use App\Models\Product; 
use App\Models\Order; 

class ChatbotController extends Controller
{
public function sendMessage(Request $request)
{
    $userMessage = strtolower($request->input('message'));

    // 1. Tìm trong bảng chatbot_responses
    $response = ChatbotResponse::where('question', 'like', '%' . $userMessage . '%')->first();
    if ($response) {
        return response()->json(['reply' => $response->answer]);
    }

    // 2. Lọc theo giá: "giá dưới 5 triệu", "giá dưới 5tr", "giá dưới 5000000"
    else if (str_contains($userMessage, 'giá dưới')) {
        $priceLimit = $this->parsePriceString($userMessage);

        if ($priceLimit) {
            $products = Product::where('price', '<', $priceLimit)->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'reply' => 'Hiện tại không có sản phẩm nào dưới ' . number_format($priceLimit, 0, ',', '.') . ' VND.'
                ]);
            }

            $names = $products->pluck('name')->toArray();
            return response()->json([
                'reply' => 'Các sản phẩm dưới ' . number_format($priceLimit, 0, ',', '.') . ' VND:<br>' . implode('<br>', $names)
            ]);
        }
    }
    else if (str_contains($userMessage, 'giá trên')) {
    $priceMin = $this->parsePriceString($userMessage);

    if ($priceMin) {
        $products = Product::where('price', '>', $priceMin)->get();

        if ($products->isEmpty()) {
            return response()->json([
                'reply' => 'Hiện tại không có sản phẩm nào trên ' . number_format($priceMin, 0, ',', '.') . ' VND.'
            ]);
        }

        $names = $products->pluck('name')->toArray();
        return response()->json([
            'reply' => 'Các sản phẩm trên ' . number_format($priceMin, 0, ',', '.') . ' VND:<br>' . implode('<br>', $names)
        ]);
    }
    }

    // 3. Tìm theo hãng: "hãng X", "sản phẩm hãng Y"
    else if (str_contains($userMessage, 'hãng')) {
        preg_match('/hãng ([a-zA-Z0-9\s]+)/u', $userMessage, $matches);
        if (isset($matches[1])) {
            $brand = trim($matches[1]);
            $products = Product::where('brand', 'like', '%' . $brand . '%')->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'reply' => 'Hiện không có sản phẩm nào thuộc hãng ' . $brand . '.'
                ]);
            }

            $names = $products->pluck('name')->toArray();
            return response()->json([
                'reply' => 'Các sản phẩm của hãng ' . $brand . ':<br>' . implode('<br>', $names)
            ]);
        }
    }

    // 4. Hỏi giá sản phẩm cụ thể: "giá sản phẩm ABC như nào"
    else if(preg_match('/(giá (sản phẩm|của)?\s*(.+?)\s*(bao nhiêu|như thế nào|\?|\z))/u', $userMessage, $matches)) {
        $productName = trim($matches[3]);
        $product = Product::where('name', 'like', '%' . $productName . '%')->first();

        if ($product) {
            return response()->json([
                'reply' => 'Giá của sản phẩm "' . $product->name . '" là ' . number_format($product->price, 0, ',', '.') . ' VND.'
            ]);
        } else {
            return response()->json([
                'reply' => 'Xin lỗi, tôi không tìm thấy thông tin về sản phẩm "' . $productName . '".'
            ]);
        }
    }

// 5. Lọc theo giảm giá: "giảm giá trên 20%", "giảm từ 10%", "giảm trên 30%"
        else if (preg_match('/(giảm|giảm giá)(\s*(trên|từ))?\s*(\d+)%/u', $userMessage, $matches)) {
            $discountValue = (int)$matches[4];

            $products = Product::where('discount', '>=', $discountValue)->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'reply' => 'Hiện tại không có sản phẩm nào được giảm giá từ ' . $discountValue . '%.'
                ]);
            }

            $names = $products->pluck('name')->toArray();
            return response()->json([
                'reply' => 'Các sản phẩm đang được giảm giá từ ' . $discountValue . '% trở lên:<br>' . implode('<br>', $names)
            ]);
        }


    // 6. Câu hỏi chung: "sản phẩm nào đang giảm giá?"
    else if (str_contains($userMessage, 'đang giảm giá')) {
        $products = Product::where('discount', '>', 0)->get();

        if ($products->isEmpty()) {
            return response()->json([
                'reply' => 'Hiện tại không có sản phẩm nào đang giảm giá.'
            ]);
        }

        $names = $products->pluck('name')->toArray();
        return response()->json([
            'reply' => 'Các sản phẩm đang giảm giá: ' . implode(', ', $names)
        ]);
    }
// 7. Đơn hàng đang xử lý
    else if (str_contains($userMessage, 'đơn hàng đang xử lý') || str_contains($userMessage, 'đơn hàng đang được xử lý')) {
        $orders = Order::where('status', 'Đang xử lý')->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'reply' => 'Hiện tại không có đơn hàng nào đang được xử lý.'
            ]);
        }

        $orderList = $orders->map(function ($order) {
            return 'Mã đơn: ' . $order->id;
        })->toArray();

        return response()->json([
            'reply' => 'Các đơn hàng đang được xử lý:<br>' . implode('<br>', $orderList)
        ]);
    }
    // 8. Đơn hàng đã hủy
else if (str_contains($userMessage, 'đơn hàng đã hủy') || str_contains($userMessage, 'đơn hàng bị hủy')) {
    $orders = Order::where('status', 'Đã hủy')->get(); // 'cancelled' là trạng thái bạn dùng trong DB

    if ($orders->isEmpty()) {
        return response()->json([
            'reply' => 'Hiện tại không có đơn hàng nào bị hủy.'
        ]);
    }

    $orderList = $orders->map(function ($order) {
        return 'Mã đơn: ' . $order->id . ' - Khách hàng: ' . $order->customer_name;
    })->toArray();

    return response()->json([
        'reply' => 'Các đơn hàng đã bị hủy:<br>' . implode('<br>', $orderList)
    ]);
}
else if (preg_match('/(xem|chi tiết) đơn hàng (\d+)/u', $userMessage, $matches)) {
    $orderId = $matches[2];
    $order = Order::find($orderId);

    if ($order) {
        return response()->json([
            'reply' => 'Chi tiết đơn hàng #' . $order->id . ':<br>' .
                       'Ngày đặt: ' . $order->order_date . '<br>' .
                       'Trạng thái: ' . $order->status . '<br>' .
                       'Tổng tiền: ' . number_format($order->total_amount, 0, ',', '.') . ' VND'
        ]);
    } else {
        return response()->json(['reply' => 'Không tìm thấy đơn hàng với mã #' . $orderId]);
    }
}

    // 7. Câu trả lời mặc định
    return response()->json([
        'reply' => 'Xin lỗi, tôi không hiểu câu hỏi của bạn. Bạn có thể hỏi rõ hơn không?'
    ]);
}


    private function parsePriceString($priceString)
    {
        // Chuẩn hóa chuỗi: bỏ dấu chấm, dấu phẩy và viết thường
        $normalized = strtolower(str_replace([',', '.'], '', $priceString));

        // Chuyển các đơn vị tiền tệ về số
        $normalized = preg_replace_callback('/(\d+)\s*(triệu|tr|million|m|nghìn|k|ngàn)/u', function ($matches) {
            $number = (int)$matches[1];
            switch ($matches[2]) {
                case 'triệu':
                case 'tr':
                case 'million':
                case 'm':
                    return $number * 1000000;
                case 'nghìn':
                case 'ngàn':
                case 'k':
                    return $number * 1000;
                default:
                    return $number;
            }
        }, $normalized);

        // Tìm số tiền
        preg_match('/(\d{4,})/', $normalized, $matches);

        return isset($matches[1]) ? (int)$matches[1] : null;
    }
    

}