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
<body class="bg-gray-50 text-gray-800">
  @include('client.component.header')
 @include('chatbot')
  <div class="max-w-7xl mx-auto py-10 px-4 grid grid-cols-1 md:grid-cols-2 gap-10">
    <!-- Hình ảnh sản phẩm -->
    <div>
      <div class="relative">
        <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
            class="w-full rounded-2xl object-contain max-h-[600px]" />
        {{-- <button id="prevBtn" class="absolute left-2 top-1/2 bg-white rounded-full p-2 shadow hover:bg-gray-100 transition duration-200 hover:scale-110">
          <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button id="nextBtn" class="absolute right-2 top-1/2 bg-white rounded-full p-2 shadow hover:bg-gray-100 transition duration-200 hover:scale-110">
          <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" /></svg>
        </button> --}}
      </div>
    </div>

    <!-- Thông tin sản phẩm -->
    <div>
        <div class="flex items-center gap-2 mb-4">
        <span class="inline-flex items-center gap-2 bg-blue-600 text-white text-base font-semibold px-4 py-1.5 rounded-full shadow-md">
            🏷️ Giảm {{ $product->discount }}%
        </span>
        </div>

      <h1 class="text-3xl font-bold text-blue-800 mb-4">{{ $product->name }}</h1>

      <div class="flex items-center gap-4 mb-4">
        <span class="line-through text-gray-400 text-lg">{{ number_format($product->original_price, 0, ',', '.') }} VND</span>
        <span class="text-2xl font-extrabold text-green-600">{{ number_format($product->price, 0, ',', '.') }} VND</span>
      </div>

      <p class="mb-2">Thương hiệu: <a href="#" class="text-blue-600 hover:underline">{{ $product->brand }}</a></p>
      <p class="mb-6">Nhóm sản phẩm: <a href="#" class="text-blue-600 hover:underline">{{ $product->category }}</a></p>

      <div class="border-l-4 border-blue-600 bg-blue-50 p-4 rounded-lg mb-6">
        <h3 class="font-semibold text-blue-700 mb-2 text-sm">🎁 KHUYẾN MÃI KHI MUA XE ĐẠP</h3>
        <ul class="list-disc list-inside text-gray-700 text-sm mb-2">
          <li>Miễn phí ship giao hàng khi mua xe đạp (không áp dụng với sản phẩm giảm từ 50%)</li>
        </ul>
        <p class="text-gray-500 italic text-xs">* Không áp dụng đồng thời các chương trình khuyến mãi khác</p>
      </div>

      <p class="mb-2">Mã sản phẩm: <span class="font-semibold">{{ $product->sku }}</span></p>

      @if($product->stock == 0)
        <p class="mb-6 text-red-600 font-semibold">Hết hàng</p>
      @else
        <p class="mb-2">Số lượng còn lại: <span class="font-semibold">{{ $product->stock }}</span></p>
        <p class="mb-6 text-green-600 font-semibold">✅ Còn hàng</p>

        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-4">
          @csrf
          <div class="flex items-center gap-2">
            <button type="button" onclick="decrementQty()" class="bg-gray-200 hover:bg-gray-300 text-xl px-3 py-1 rounded shadow">
              −
            </button>
            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                   class="w-16 text-center border rounded shadow-sm focus:ring-2 focus:ring-blue-400"
                   oninput="validateQuantity()" onchange="validateQuantity()" />
            <button type="button" onclick="incrementQty()" class="bg-gray-200 hover:bg-gray-300 text-xl px-3 py-1 rounded shadow">
              +
            </button>
          </div>

          <button id="addToCartBtn" type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow-lg transition">
            Thêm vào giỏ hàng
          </button>
        </form>

        <form action="{{ route('product.buy', $product->id) }}" method="GET" class="mt-3">
          <input type="hidden" id="buyQuantity" name="quantity" value="1">
          <button id="buyNowBtn" type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow-lg transition">
            MUA NGAY
          </button>
        </form>
      @endif
    </div>

    <!-- Mô tả -->
    <div class="md:col-span-2 mt-10">
      <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Mô tả sản phẩm</h2>
      <div class="prose max-w-none text-gray-700 leading-relaxed">
        {!! $product->description !!}
      </div>
    </div>
  </div>

  @include('backend.dashboard.componet.script')

  <script>
    function updateBuyQuantity() {
      document.getElementById('buyQuantity').value = document.getElementById('quantity').value;
    }

    function decrementQty() {
      const qtyInput = document.getElementById('quantity');
      let val = parseInt(qtyInput.value);
      if (val > 1) qtyInput.value = val - 1;
      validateQuantity();
      updateBuyQuantity();
    }

    function incrementQty() {
      const qtyInput = document.getElementById('quantity');
      let val = parseInt(qtyInput.value);
      qtyInput.value = val + 1;
      validateQuantity();
      updateBuyQuantity();
    }

    function validateQuantity() {
      const qty = parseInt(document.getElementById('quantity').value);
      const maxStock = {{ $product->stock }};
      const addBtn = document.getElementById('addToCartBtn');
      const buyBtn = document.getElementById('buyNowBtn');

      const isValid = qty > 0 && qty <= maxStock;

      addBtn.disabled = !isValid;
      buyBtn.disabled = !isValid;

      addBtn.classList.toggle('opacity-50', !isValid);
      buyBtn.classList.toggle('opacity-50', !isValid);
    }
  </script>

  @include('client.component.footer')
</body>
</html>
