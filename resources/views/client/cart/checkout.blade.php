<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xe Đạp Việt Nam - Thanh toán</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">

  @include('client.component.header')

  <form action="{{ route('product.processBuyCart') }}" method="POST">
    @csrf
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Thanh Toán Đơn Hàng</h1>

      <div class="flex flex-col lg:flex-row lg:space-x-8">
        <!-- Giao hàng -->
        <div class="lg:w-2/3 bg-white p-6 rounded-lg shadow-md mb-8 lg:mb-0">
          <h2 class="text-2xl font-semibold text-gray-700 mb-6">Thông tin giao hàng & Sản phẩm</h2>

          <!-- Giỏ hàng -->
          <div class="mb-8 border-b pb-4">
            <h3 class="text-xl font-semibold text-gray-600 mb-4">Sản phẩm trong giỏ hàng</h3>
            @if($cartItems->isEmpty())
              <p class="text-red-500 font-semibold text-center">Giỏ hàng của bạn đang trống.</p>
            @else
              @foreach ($cartItems as $item)
                <div class="flex items-center justify-between border-b border-gray-200 py-3 last:border-b-0">
                  <div class="flex items-center space-x-4">
                    @if (!empty($item->product->image))
                      <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded-lg shadow-sm">
                    @endif
                    <div>
                      <p class="font-medium text-gray-800">{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</p>
                      <p class="text-sm text-gray-600">Số lượng: {{ $item->quantity }}</p>
                      <p class="text-md font-semibold text-green-700">{{ number_format($item->product->price ?? 0, 0, ',', '.') }}₫</p>
                    </div>
                  </div>
                </div>
              @endforeach
            @endif
          </div>

          <!-- Thông tin người nhận -->
          <h3 class="text-xl font-semibold text-gray-600 mb-4">Thông tin người nhận</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
              <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
              <input type="text" id="customer_name" name="customer_name" required class="w-full border p-3 rounded-md">
            </div>
            <div>
              <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
              <input type="text" id="customer_phone" name="customer_phone" required class="w-full border p-3 rounded-md">
            </div>
          </div>

          <!-- Địa chỉ -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
              <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Tỉnh/Thành phố</label>
              <select id="province" name="province" required class="w-full border p-3 rounded-md">
                <option value="">-- Chọn tỉnh/thành --</option>
              </select>
            </div>
            <div>
              <label for="district" class="block text-sm font-medium text-gray-700 mb-1">Quận/Huyện</label>
              <select id="district" name="district" required class="w-full border p-3 rounded-md">
                <option value="">-- Chọn quận/huyện --</option>
              </select>
            </div>
            <div>
              <label for="ward" class="block text-sm font-medium text-gray-700 mb-1">Phường/Xã</label>
              <select id="ward" name="ward" required class="w-full border p-3 rounded-md">
                <option value="">-- Chọn phường/xã --</option>
              </select>
            </div>
          </div>

          <div class="mb-6">
            <label for="customer_address_detail" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ chi tiết</label>
            <input type="text" id="customer_address_detail" name="customer_address_detail" required class="w-full border p-3 rounded-md">
          </div>
        </div>

        <!-- Thanh toán -->
        <div class="lg:w-1/3 bg-white p-6 rounded-lg shadow-md">
          <h2 class="text-2xl font-semibold text-gray-700 mb-6">Hình thức thanh toán</h2>
          @php $oldPayment = old('payment_method'); @endphp
          <div class="space-y-4 mb-6">
            @foreach(['COD' => '🛵 Thanh toán khi nhận hàng', 'VNPay' => '🏦 Qua VNPay', 'MoMo' => '📱 Ví MoMo', 'Zalopay' => '💰 Ví ZaloPay'] as $method => $label)
              <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="radio" name="payment_method" value="{{ $method }}" class="form-radio h-5 w-5 text-blue-600" required {{ $oldPayment == $method ? 'checked' : '' }}>
                <span class="ml-3 text-lg text-gray-800">{{ $label }}</span>
              </label>
            @endforeach
          </div>

          <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 text-xl transition">
            Xác nhận thanh toán
          </button>
        </div>
      </div>
    </div>
  </form>

  @include('client.component.footer')

  <!-- Địa chỉ script -->
 <script>
document.addEventListener("DOMContentLoaded", function () {
    const provinceSelect = document.getElementById("province");
    const districtSelect = document.getElementById("district");
    const wardSelect = document.getElementById("ward");

    fetch("https://provinces.open-api.vn/api/?depth=1")
        .then(res => res.json())
        .then(data => {
            data.forEach(province => {
                const opt = document.createElement("option");
                opt.value = province.name; // ✅ lưu tên thay vì code
                opt.textContent = province.name;
                provinceSelect.appendChild(opt);
            });
        });

    provinceSelect.addEventListener("change", function () {
        const selectedProvince = this.options[this.selectedIndex].text;

        districtSelect.innerHTML = '<option value="">-- Chọn quận/huyện --</option>';
        wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';

        // Tìm mã tỉnh từ tên để gọi API đúng
        fetch("https://provinces.open-api.vn/api/?depth=2")
            .then(res => res.json())
            .then(data => {
                const province = data.find(p => p.name === selectedProvince);
                if (province) {
                    province.districts.forEach(district => {
                        const opt = document.createElement("option");
                        opt.value = district.name; // ✅ lưu tên
                        opt.textContent = district.name;
                        districtSelect.appendChild(opt);
                    });
                }
            });
    });

    districtSelect.addEventListener("change", function () {
        const selectedDistrict = this.options[this.selectedIndex].text;

        wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';

        // Tìm xã trong quận đã chọn
        fetch("https://provinces.open-api.vn/api/?depth=3")
            .then(res => res.json())
            .then(data => {
                const province = data.find(p => p.name === provinceSelect.value);
                if (!province) return;

                const district = province.districts.find(d => d.name === selectedDistrict);
                if (!district) return;

                district.wards.forEach(ward => {
                    const opt = document.createElement("option");
                    opt.value = ward.name; // ✅ lưu tên
                    opt.textContent = ward.name;
                    wardSelect.appendChild(opt);
                });
            });
    });
});
</script>
</body>
</html>
