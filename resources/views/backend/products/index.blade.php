@extends('backend.dashboard.layout')

@section('content')

<!-- Tìm kiếm -->
<div class="mb-4">
    <form action="{{ route('products.index') }}" method="GET" class="max-w-xl">
        <input type="text" name="keyword"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="🔍 Tìm kiếm..." value="{{ request('keyword') }}">
    </form>
</div>

<!-- Nút thêm sản phẩm -->
<a href="{{ route('products.create') }}"
    class="inline-block mb-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
    + Thêm sản phẩm
</a>

<!-- Bảng sản phẩm -->
<div class="overflow-x-auto bg-white rounded-xl shadow">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-indigo-50 text-gray-700 font-semibold text-left">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Tên sản phẩm</th>
                <th class="px-4 py-3">Danh mục</th>
                <th class="px-4 py-3">Thương hiệu</th>
                <th class="px-4 py-3">SKU</th>
                <th class="px-4 py-3">Giảm giá</th>
                <th class="px-4 py-3">Giá</th>
                <th class="px-4 py-3">Tồn kho</th>
                <th class="px-4 py-3">Hành động</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @if(isset($products) && is_iterable($products))
                @foreach ($products as $val)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-2 text-center">{{ $val->id }}</td>
                        <td class="px-4 py-3 flex items-center gap-3">
                            <img src="{{ asset('storage/' . $val->image) }}" alt="" class="w-16 h-16 rounded-xl object-cover">
                            <div>
                                <p class="font-medium">{{ $val->name }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $val->category }}</td>
                        <td class="px-4 py-3">{{ $val->brand }}</td>
                        <td class="px-4 py-3">{{ $val->sku }}</td>
                        <td class="px-4 py-3">{{ $val->discount }}%</td>
                        <td class="px-4 py-3 text-green-600 font-semibold">{{ number_format($val->price, 0, ',', '.') }}đ</td>
                        <td class="px-4 py-3">{{ $val->stock }}</td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="{{ route('products.edit', $val->id) }}"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm px-3 py-1 rounded-lg">
                                    Sửa
                                </a>
                                <form action="{{ route('products.destroy', $val->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xoá không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded-lg">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-center py-4 text-gray-500 italic">Không có dữ liệu sản phẩm</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Phân trang -->
<div class="mt-6 flex justify-center">
    {{ $products->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
</div>

@endsection
