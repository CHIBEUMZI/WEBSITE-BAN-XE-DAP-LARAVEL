<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Order;

class GeminiController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');
        $apiKey = env('GEMINI_API_KEY');

        // ✅ Prompt cải tiến: yêu cầu chỉ trả về JSON
        $prompt = <<<EOD
        Bạn hãy phân tích câu sau và trích xuất:
        - loại sản phẩm (category)
        - thương hiệu nếu có (brand)
        - mức giá tối đa nếu có (max_price, đơn vị đồng Việt Nam)
        - mức giảm giá nếu có (discount, tính bằng phần trăm, ví dụ: 10 cho 10%)

        Chỉ trả về JSON hợp lệ, không thêm lời giải thích hay văn bản khác.

        Ví dụ: {"category": "xe đạp địa hình", "brand": "Giant", "max_price": 5000000, "discount": 10}

        Câu: "$userMessage"
        EOD;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
            'contents' => [[
                'parts' => [['text' => $prompt]]
            ]]
        ]);

        if ($response->failed()) {
            $body = $response->body();
            Log::error('Lỗi từ Gemini API', ['response' => $body]);

            return response()->json([
                'reply' => 'Xin lỗi, lỗi API Gemini.',
                'error' => $body
            ], 500);
        }

        // ✅ Trích xuất nội dung phản hồi
        $resultText = $response['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

        // ✅ Log nội dung phản hồi để kiểm tra
        Log::info('Phản hồi từ Gemini:', ['text' => $resultText]);

        // ✅ Dùng biểu thức chính quy đa dòng, làm sạch JSON
        preg_match('/\{.*\}/s', $resultText, $matches);
        $jsonText = $matches[0] ?? '{}';

        $params = json_decode($jsonText, true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($params['category'])) {
            Log::error('Phân tích JSON từ Gemini thất bại', [
                'raw_text' => $resultText,
                'json_text' => $jsonText,
                'error' => json_last_error_msg()
            ]);

            return response()->json(['reply' => 'Xin lỗi, tôi chưa hiểu rõ yêu cầu của bạn.']);
        }

        // ✅ Tìm sản phẩm theo category và max_price
        $query = Product::query();
        if (!empty($params['category'])) {
            $query->where('category', 'like', '%' . $params['category'] . '%');
        }
        if (!empty($params['max_price'])) {
            $query->where('price', '<=', $params['max_price']);
        }
        if (!empty($params['discount'])) {
            $query->where('discount', '>=', $params['discount']);
        }
        if (!empty($params['brand'])) {
            $query->where('brand', 'like', '%' . $params['brand'] . '%');
        }
        $products = $query->limit(5)->get();

        if ($products->isEmpty()) {
            return response()->json(['reply' => 'Không tìm thấy sản phẩm phù hợp.']);
        }

        $reply = "Tôi gợi ý bạn các sản phẩm sau:\n";
        foreach ($products as $product) {
            $discountText = $product->discount ? " - Giảm {$product->discount}%" : "";
            $reply .= "- {$product->name} ({$product->price} đ{$discountText}) của hãng {$product->brand}\n";

        }
        return response()->json(['reply' => $reply]);
    }
}
