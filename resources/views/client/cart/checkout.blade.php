<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xe Đạp Việt Nam</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  @include('backend.dashboard.componet.head')
</head>
<body>
  @include('client.component.header')

  <div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Thông tin giao hàng</h2>

    <div class="max-w-2xl mx-auto p-6 bg-white rounded shadow mt-6">
      <h2 class="text-xl font-semibold mb-4">Sản phẩm trong giỏ hàng</h2>

      @foreach ($cartItems as $item)
        <div class="flex items-center justify-between border-b py-2">
          <div>
            <p class="font-medium">{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</p>
            <p>Số lượng: {{ $item->quantity }}</p>
            <p>Giá: {{ number_format($item->product->price ?? 0, 0, ',', '.') }}₫</p>
          </div>
          @if (!empty($item->product->image))
            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
          @endif
        </div>
      @endforeach
    </div>

    <form action="{{ route('product.processBuyCart') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label>Họ và tên</label>
        <input type="text" name="customer_name" class="w-full border p-2" required>
      </div>
      <div class="mb-3">
        <label>Số điện thoại</label>
        <input type="text" name="customer_phone" class="w-full border p-2" required>
      </div>
      <div class="mb-3">
        <label>Địa chỉ giao hàng</label>
        <textarea name="customer_address" class="w-full border p-2" required></textarea>
      </div>
      <div class="mb-3 mt-4">
        <label class="block mb-1 font-medium">Hình thức thanh toán</label>
        <div class="space-y-2">
          <label class="flex items-center">
            <input type="radio" name="payment_method" value="COD" class="mr-2" required>
            Thanh toán khi nhận hàng (COD)
          </label>
          <label class="flex items-center">
            <input type="radio" name="payment_method" value="Bank Transfer" class="mr-2" required>
            Chuyển khoản ngân hàng
          </label>
          <label class="flex items-center">
            <input type="radio" name="payment_method" value="Momo" class="mr-2" required>
            Ví điện tử (Momo)
          </label>
          <label class="flex items-center">
            <input type="radio" name="payment_method" value="Zalopay" class="mr-2" required>
            Ví điện tử (Zalopay)
          </label>
        </div>
      </div>
      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Xác nhận thanh toán</button>
    </form>
  </div>
   @include('client.component.footer')
</body>
</html>
