<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
    
        // Tìm kiếm theo tên hoặc email
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhere('category', 'like', "%$keyword%")
                  ->orWhere('sku', 'like', "%$keyword%");
            });
        }
        $products = $query->paginate(10)->appends($request->all());
        return view('backend.products.index',compact('products'));
    }

    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'category' => 'required|string|max:255',
            'original_price' => 'required|integer|min:0',
            'price' => 'required|integer|min:0|lte:original_price',
            'stock' => 'required|integer|min:0',
            'brand' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'discount' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/products', 'public');
        }

        Product::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'original_price' => $validated['original_price'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'brand' => $validated['brand'],
            'sku' => $validated['sku'],
            'discount' => $validated['discount'] ?? '',
            'description' => $validated['description'] ?? '',
            'image' => $imagePath,
        ]);

    
        // Chuyển hướng về trang danh sách sản phẩm với thông báo thành công
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm!');
    }
    
    public function create()
    {
        return view('backend.products.create');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'original_price' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'brand' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'discount' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['name', 'category', 'original_price', 'price', 'stock', 'brand','sku', 'discount', 'description']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/products', 'public');
            $data['image'] = $imagePath;
        }

        $product = Product::findOrFail($id);
        $product->update($data);

    
        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }


    public function edit($id){
        $product = Product::findOrFail($id);
        return view('backend.products.edit', compact('product'));
    }


    public function destroy($id)
    {
    $product = Product::findOrFail($id);
    $product->delete();

    return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công.');
    }
}
?>