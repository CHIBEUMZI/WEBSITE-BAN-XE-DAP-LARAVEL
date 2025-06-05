<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Xe Đạp Việt Nam</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  @include('backend.dashboard.componet.head')
</head>
<body>
@include('client.component.header')

<div class="max-w-4xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6">Giỏ hàng của bạn</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($cartItems->count() > 0)
    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Sản phẩm</th>
                <th class="p-2">Giá</th>
                <th class="p-2">Số lượng</th>
                <th class="p-2">Tổng</th>
                <th class="p-2">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($cartItems as $item)
                @php 
                    $subtotal = $item->product->price * $item->quantity;
                    $total += $subtotal;
                @endphp
                <tr class="border-t">
                    <td class="p-2 flex items-center gap-2">
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-16 h-16 object-cover" />
                        {{ $item->product->name }}
                    </td>
                    <td class="p-2">{{ number_format($item->product->price, 0, ',', '.') }} VND</td>
                    <td class="p-2">
                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->product->id }}">
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 border rounded px-2 py-1">
                            <button type="submit" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Cập nhật</button>
                        </form>
                    </td>
                    <td class="p-2">{{ number_format($subtotal, 0, ',', '.') }} VND</td>
                    <td class="p-2">
                        <a href="{{ route('cart.remove', $item->product->id) }}" class="text-red-600 hover:underline">Xóa</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="border-t bg-gray-50 font-bold">
                <td colspan="3" class="p-2 text-right">Tổng cộng:</td>
                <td class="p-2">{{ number_format($total, 0, ',', '.') }} VND</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-6">
        <a href="{{ route('client.home') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Tiếp tục mua sắm</a>
        <a href="{{ route('cart.buy') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 ml-2">Thanh toán</a>
    </div>
    @else
        <p class="text-gray-600">Giỏ hàng của bạn đang trống.</p>
    @endif
</div>
 @include('client.component.footer')
</body>
</html>
