<!DOCTYPE html>
<html lang="en" x-data>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Xe Đạp Việt Nam</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  @include('backend.dashboard.componet.head')
</head>
<body>
  @include('client.component.header')

  <div class="max-w-5xl mx-auto p-6">
    <h2 class="mt-8 mb-4 text-2xl font-semibold text-gray-800">Chi tiết đơn hàng #{{ $order->id }}</h2>
    <p class="text-gray-700 mb-1">Ngày đặt: {{ $order->order_date->format('d/m/Y H:i') }}</p>
    <p class="text-gray-700 mb-1">Trạng thái: {{ $order->status }}</p>
    <p class="text-gray-700 mb-1">Địa chỉ giao hàng: {{ $order->shipping_address }}</p>
    <p class="text-gray-700 mb-4">Phương thức thanh toán: {{ $order->payment_method }}</p>
    @if($order->status != 'Đã hủy' && $order->status != 'Giao hàng')
  <form action="{{ route('client.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');" class="mt-6">
    @csrf
    @method('PATCH')
    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded">
      Hủy đơn hàng
    </button>
  </form>
@endif

<table class="min-w-full border border-gray-300 border-collapse rounded-md overflow-hidden">
  <thead class="bg-blue-600 text-white">
    <tr>
      <th class="py-3 px-5 text-left">Sản phẩm</th>
      <th class="py-3 px-5 text-left">Số lượng</th>
      <th class="py-3 px-5 text-left">Đơn giá</th>
      <th class="py-3 px-5 text-left">Thành tiền</th>
    </tr>
  </thead>
  <tbody>
    @foreach($order->orderItems as $item)
    <tr class="even:bg-gray-100 hover:bg-blue-100">
        <td class="p-2 flex items-center gap-2">
            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-16 h-16 object-cover" />
            {{ $item->product->name }}
        </td>
        <td class="py-3 px-5">{{ $item->quantity }}</td>
        <td class="py-3 px-5">{{ number_format($item->price, 0, ',', '.') }} VND</td>
        <td class="py-3 px-5">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND</td>
    </tr>
    @endforeach
  </tbody>
</table>

    <p class="mt-4 text-xl font-bold text-blue-600">
      Tổng cộng: {{ number_format($order->total_amount, 0, ',', '.') }} VND
    </p>
  </div>
   @include('client.component.footer')
</body>
</html>
