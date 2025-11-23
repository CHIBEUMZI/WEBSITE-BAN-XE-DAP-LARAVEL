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
 @include('client.component.chatbot')
  <div class="max-w-7xl mx-auto py-10 px-4 grid grid-cols-1 lg:grid-cols-2 gap-8">
  <!-- Hình ảnh sản phẩm -->
  <div class="relative">
    <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
      class="w-full rounded-2xl object-contain max-h-[600px]" />
  </div>

  <!-- Thông tin sản phẩm -->
  <div class="space-y-4">
    <span class="inline-flex items-center bg-blue-600 text-white text-sm font-medium px-4 py-1.5 rounded-full">
      🏷️ Giảm {{ $product->discount }}%
    </span>

    <h1 class="text-2xl font-bold text-blue-800">{{ $product->name }}</h1>

    <div class="flex items-center gap-3">
      <span class="line-through text-gray-400">{{ number_format($product->original_price, 0, ',', '.') }} VND</span>
      <span class="text-2xl font-bold text-green-600">{{ number_format($product->price, 0, ',', '.') }} VND</span>
    </div>

    <p>Thương hiệu: <a href="#" class="text-blue-600 hover:underline">{{ $product->brand }}</a></p>
    <p>Nhóm sản phẩm: <a href="#" class="text-blue-600 hover:underline">{{ $product->category }}</a></p>
    <p>Mã sản phẩm: <span class="font-semibold">{{ $product->sku }}</span></p>

    <!-- Khuyến mãi -->
    <div class="border-l-4 border-blue-600 bg-blue-50 p-4 rounded-lg">
      <h3 class="text-sm font-semibold text-blue-700 mb-1">🎁 KHUYẾN MÃI</h3>
      <ul class="list-disc list-inside text-sm text-gray-700">
        <li>Miễn phí ship (trừ sản phẩm giảm giá trên 50%)</li>
      </ul>
      <p class="text-xs italic text-gray-500">* Không áp dụng cùng chương trình khác</p>
    </div>

    <!-- Trạng thái sản phẩm -->
    @if($product->stock == 0)
      <p class="text-red-600 font-semibold" id="outOfStockLabel">Hết hàng</p>
    @else
      <p>Số lượng còn lại: <span class="font-semibold">{{ $product->stock }}</span></p>
      <p class="text-green-600 font-semibold">✅ Còn hàng</p>

      <!-- Form thêm vào giỏ -->
      <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-4">
        @csrf
        <div class="flex items-center gap-2">
          <button type="button" onclick="decrementQty()" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">−</button>
          <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}"
            class="w-16 text-center border rounded focus:ring-blue-400"
            oninput="validateQuantity()" onchange="validateQuantity()" />
          <button type="button" onclick="incrementQty()" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">+</button>
        </div>

        <button id="addToCartBtn" type="submit"
          class="w-full bg-blue-700 hover:bg-blue-800 text-white py-3 font-semibold rounded-lg shadow flex items-center justify-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.3 2.3c-.6.6-.2 1.7.7 1.7H17a2 2 0 100 4 2 2 0 000-4z" /></svg>
          <span>Thêm vào giỏ hàng</span>
        </button>
      </form>

      <!-- Mua ngay -->
      <form action="{{ route('product.buy', $product->id) }}" method="GET" class="mt-2">
        <input type="hidden" id="buyQuantity" name="quantity" value="1">
        <button id="buyNowBtn" type="submit"
          class="w-full bg-green-600 hover:bg-green-700 text-white py-3 font-semibold rounded-lg shadow">
          MUA NGAY
        </button>
      </form>

      <!-- Trả góp -->
      <div class="mt-2">
        <button
          class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg shadow text-center text-sm font-medium">
          TRẢ GÓP 0% <br><span class="text-xs font-normal">Qua Visa/Master/JCB hoặc tại cửa hàng</span>
        </button>
      </div>
    @endif
  </div>

  <!-- Mô tả sản phẩm -->
  <div class="lg:col-span-2 mt-12">
    <h2 class="text-xl font-bold border-b pb-2 mb-4 text-gray-800">Mô tả sản phẩm</h2>
    <div class="prose max-w-none text-gray-700">
      {!! $product->description !!}
    </div>
  </div>

  <!-- Bình luận -->
  <div class="lg:col-span-2 mt-12">
    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Đánh giá & Bình luận</h2>
    <div class="space-y-6">
      <!-- Lặp qua các bình luận mẫu -->
      @foreach ([['name'=>'Nguyễn Văn A','days'=>'3 ngày trước','stars'=>'★★★★★','cmt'=>'Xe đạp đi rất êm, phù hợp với cả đi học và thể thao nhẹ.'],
                 ['name'=>'Trần Thị B','days'=>'1 tuần trước','stars'=>'★★★★☆','cmt'=>'Thiết kế đẹp, chắc chắn. Tuy nhiên yên xe hơi cứng với mình.'],
                 ['name'=>'Lê Quốc Cường','days'=>'2 tuần trước','stars'=>'★★★★★','cmt'=>'Đạp rất nhẹ, đổi số mượt. Dịch vụ tư vấn kỹ, rất hài lòng!']] as $comment)
      <div class="flex gap-4 p-4 bg-white rounded-lg shadow">
        <img src="https://i.pravatar.cc/60?u={{ $comment['name'] }}" alt="avatar" class="w-12 h-12 rounded-full">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <h4 class="font-semibold">{{ $comment['name'] }}</h4>
            <span class="text-sm text-gray-500">Đã mua {{ $comment['days'] }}</span>
          </div>
          <div class="text-yellow-400 mb-1">{{ $comment['stars'] }}</div>
          <p class="text-gray-700 text-sm">{{ $comment['cmt'] }}</p>
        </div>
      </div>
      @endforeach
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