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
<div class="max-w-3xl mx-auto py-12 px-4 bg-white rounded shadow">
    <h1 class="text-3xl font-bold mb-6 text-blue-700">Mua hàng: {{ $product->name }}</h1>

    <div class="flex mb-6">
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-48 h-48 object-cover rounded mr-6">
        <div>
            <p class="text-xl font-semibold mb-2">Giá: <span class="text-red-600">{{ number_format($product->price, 0, ',', '.') }} VND</span></p>
            <p class="mb-4">Mô tả: {{ $product->description ?? 'Không có mô tả' }}</p>
        </div>
    </div>

    <form action="{{route('product.processBuy' , ['id' => $product->id]) }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="quantity" class="block font-semibold mb-1">Số lượng:</label>
            <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity', $quantity ?? 1) }}" required class="w-20 border rounded px-2 py-1">
        </div>

        <div>
            <label for="customer_name" class="block font-semibold mb-1">Họ và tên:</label>
            <input type="text" id="customer_name" name="customer_name" required class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label for="customer_phone" class="block font-semibold mb-1">Số điện thoại:</label>
            <input type="tel" id="customer_phone" name="customer_phone" required class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label for="customer_address" class="block font-semibold mb-1">Địa chỉ nhận hàng:</label>
            <textarea id="customer_address" name="customer_address" rows="3" required class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <p class="text-xl font-semibold mb-2">Tổng tiền: <span class="text-red-600" id="totalPrice">{{ number_format($product->price * $quantity, 0, ',', '.') }} VND</span></p>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Xác nhận mua</button>
    </form>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const quantityInput = document.getElementById('quantity');
        const totalPriceElement = document.getElementById('totalPrice');
        const unitPrice = {{ $product->price }};

        function updateTotal() {
            const qty = parseInt(quantityInput.value) || 1;
            const total = qty * unitPrice;

            // Định dạng số có dấu chấm (.) ngăn cách hàng nghìn
            const formatted = new Intl.NumberFormat('vi-VN').format(total) + ' VND';
            totalPriceElement.textContent = formatted;
        }

        quantityInput.addEventListener('input', updateTotal);
    });
</script>
</div>
 @include('client.component.footer')
</body>
</html>
