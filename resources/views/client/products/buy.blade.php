<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Đơn Hàng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/vietnam-provinces@1.0.1/dist/vietnam-provinces.min.js"></script>
    @include('backend.dashboard.componet.head')
</head>
<body class="bg-gray-100">
    @include('client.component.header')

    <form action="{{ route('product.processBuy', ['id' => $product->id]) }}" method="POST" class="max-w-6xl mx-auto py-10 px-4 bg-white rounded shadow-md">
        @csrf
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Thanh Toán Đơn Hàng</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Trái: Thông tin sản phẩm + người nhận -->
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold mb-3">Thông tin giao hàng & Sản phẩm</h2>

                <!-- Sản phẩm -->
                <div class="border rounded p-4 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Sản phẩm trong giỏ hàng</h3>
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded">
                        <div>
                            <p class="font-medium">{{ $product->name }}</p>
                            <label class="block mt-2">Số lượng:
                                <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity', $quantity ?? 1) }}" class="w-20 border rounded px-2 py-1 ml-2">
                            </label>
                            <p class="text-green-600 font-semibold mt-2" id="totalPrice">{{ number_format($product->price * ($quantity ?? 1), 0, ',', '.') }}đ</p>
                        </div>
                    </div>
                </div>

                <!-- Người nhận -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold">Thông tin người nhận</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="customer_name" placeholder="Họ và tên" required class="w-full border rounded px-3 py-2">
                        <input type="tel" name="customer_phone" placeholder="Số điện thoại" required class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select id="province" name="province" required class="w-full border rounded px-3 py-2">
                            <option value="">-- Chọn tỉnh/thành --</option>
                        </select>
                        <select id="district" name="district" required class="w-full border rounded px-3 py-2">
                            <option value="">-- Chọn quận/huyện --</option>
                        </select>
                        <select id="ward" name="ward" required class="w-full border rounded px-3 py-2">
                            <option value="">-- Chọn phường/xã --</option>
                        </select>
                    </div>

                    <input type="text" name="customer_address_detail" placeholder="Địa chỉ chi tiết (số nhà, tên đường...)" required class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <!-- Phải: Phương thức thanh toán -->
            <div class="border rounded p-6 bg-gray-50">
                <h3 class="text-lg font-semibold mb-4">Hình thức thanh toán</h3>
                @php $oldPayment = old('payment_method'); @endphp
                <div class="space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="payment_method" value="COD" required {{ $oldPayment == 'COD' ? 'checked' : '' }}>
                        🛵 Thanh toán khi nhận hàng
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="payment_method" value="VNPay" required {{ $oldPayment == 'VNPay' ? 'checked' : '' }}>
                        🏦 Qua VNPay
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="payment_method" value="MoMo" required {{ $oldPayment == 'MoMo' ? 'checked' : '' }}>
                        📱 Ví MoMo
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="payment_method" value="Zalopay" required {{ $oldPayment == 'Zalopay' ? 'checked' : '' }}>
                        💰 Ví ZaloPay
                    </label>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded font-semibold hover:bg-green-700 transition">Xác nhận thanh toán</button>
                </div>
            </div>
        </div>
    </form>

    @include('client.component.footer')

    <!-- Script Tính Tổng -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const quantityInput = document.getElementById('quantity');
            const totalPriceElement = document.getElementById('totalPrice');
            const unitPrice = {{ $product->price }};

            function updateTotal() {
                const qty = parseInt(quantityInput.value) || 1;
                const total = qty * unitPrice;
                totalPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
            }

            quantityInput.addEventListener('input', updateTotal);
        });
    </script>

    <!-- Script Địa Lý -->
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
                        opt.value = province.name;
                        opt.textContent = province.name;
                        provinceSelect.appendChild(opt);
                    });
                });

            provinceSelect.addEventListener("change", function () {
                const selectedProvince = this.options[this.selectedIndex].text;
                districtSelect.innerHTML = '<option value="">-- Chọn quận/huyện --</option>';
                wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';

                fetch("https://provinces.open-api.vn/api/?depth=2")
                    .then(res => res.json())
                    .then(data => {
                        const province = data.find(p => p.name === selectedProvince);
                        if (province) {
                            province.districts.forEach(district => {
                                const opt = document.createElement("option");
                                opt.value = district.name;
                                opt.textContent = district.name;
                                districtSelect.appendChild(opt);
                            });
                        }
                    });
            });

            districtSelect.addEventListener("change", function () {
                const selectedDistrict = this.options[this.selectedIndex].text;
                wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';

                fetch("https://provinces.open-api.vn/api/?depth=3")
                    .then(res => res.json())
                    .then(data => {
                        const province = data.find(p => p.name === provinceSelect.value);
                        if (!province) return;

                        const district = province.districts.find(d => d.name === selectedDistrict);
                        if (!district) return;

                        district.wards.forEach(ward => {
                            const opt = document.createElement("option");
                            opt.value = ward.name;
                            opt.textContent = ward.name;
                            wardSelect.appendChild(opt);
                        });
                    });
            });
        });
    </script>
</body>
</html>
