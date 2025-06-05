<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

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
        $products = $query->paginate(3);
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

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
        ]);

        // Kiểm tra tồn kho (dùng stock thay vì quantity)
        if ($product->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Số lượng sản phẩm không đủ'])->withInput();
        }

        // Tính tổng tiền
        $totalAmount = $product->price * $validated['quantity'];

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'payment_method' => 'COD',
            'payment_status' => 'Pending',
            'status' => 'Processing',
            'shipping_address' => $validated['customer_address'],
            'shipping_fee' => 0,
            'note' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo chi tiết đơn hàng
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'price' => $product->price,
        ]);

        // Cập nhật tồn kho
        $product->stock -= $validated['quantity'];
        $product->save();

        return redirect()->route('client.home')->with('success', 'Mua hàng thành công! Đơn hàng của bạn đang được xử lý.');
    }

}

