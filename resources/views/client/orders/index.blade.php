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
 @include('chatbot')
  <div class="max-w-4xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-extrabold text-gray-800 mb-8 font-sans">Đơn hàng của tôi</h2>

    @if($orders->isEmpty())
      <p class="text-gray-600 text-lg">Bạn chưa có đơn hàng nào.</p>
    @else
      <div class="space-y-6">
        @foreach($orders as $order)
          <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-300 font-sans">
            <h4 class="text-blue-600 text-xl font-semibold mb-2">Đơn hàng #{{ $order->id }} - {{ $order->order_date->format('d/m/Y') }}</h4>
            <p class="text-gray-700 mb-1">Trạng thái: {{ $order->status }}</p>
            <p class="text-gray-700 mb-3">Tổng tiền: {{ number_format($order->total_amount, 0, ',', '.') }} VND</p>
            <a href="{{ route('client.orders.show', $order->id) }}" class="no-underline inline-block bg-blue-600 text-white px-5 py-2 rounded-md font-semibold hover:bg-blue-700 transition-colors duration-300">
              Chi tiết
            </a>
          </div>
        @endforeach
      </div>
    @endif
  </div>
   @include('client.component.footer')
</body>
</html>
