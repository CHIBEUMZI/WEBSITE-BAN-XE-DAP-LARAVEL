<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Xe Đạp Việt Nam</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  @include('backend.dashboard.componet.head')
</head>
<body class="bg-gray-50 text-gray-800">

  @include('client.component.header')

  <!-- Giới thiệu và bảng giá -->
<div class="max-w-4xl mx-auto px-4 py-6">
  <p class="mb-2 text-justify text-base leading-7">
    Ngày nay, việc tìm một cửa hàng sửa chữa xe đạp không phải là điều dễ dàng. Là chuỗi cửa hàng bán lẻ xe đạp lớn nhất cả nước, 
    <strong>XEDAP.VN</strong> với đội ngũ kỹ thuật viên lâu năm, nhiều kinh nghiệm, hiện cung cấp tất cả các dịch vụ (Full) dành cho xe đạp, đặc biệt là các dòng xe đạp thể thao: 
    bảo dưỡng - bảo trì, lắp ráp, build xe mới, sửa chữa, thay thế, nâng cấp phụ tùng.
  </p>

  <p class="mb-2 text-justify text-base leading-7">
    Quý khách có nhu cầu sử dụng một trong các dịch vụ trên vui lòng liên hệ và mang xe đến các showroom trực thuộc tại Hồ Chí Minh, Hà Nội, Đà Nẵng, Bình Dương,
    Đồng Nai, Tây Ninh, Bà Rịa, Bình Định, Quảng Ngãi, Quảng Nam với hơn 50 cửa hàng trên toàn quốc.
  </p>

  <p class="mb-2 text-justify text-base leading-7">
    <strong>Dịch vụ sửa xe tận nhà</strong> phục vụ từ Thứ 2 đến Chủ nhật.
    <br>
    <strong>Liên hệ ngay hotline:</strong> 
    <span class="text-blue-600 font-semibold">1800 9473</span> (8h30 – 17h30)
  </p>

  <p class="mb-2 text-lg font-semibold text-center">BẢNG GIÁ FULL GÓI DỊCH VỤ</p>

  <p class="mb-4 text-justify text-base leading-7">
    Tùy vào tình trạng thực tế của chiếc xe đạp, Quý khách có thể lựa chọn gói bảo dưỡng phù hợp. Khi mang xe đến cửa hàng hoặc sử dụng dịch vụ sửa xe tận nhà, 
    kỹ thuật viên sẽ tư vấn chi tiết. Dưới đây là bảng thông tin các gói bảo dưỡng để Quý khách tham khảo.
  </p>

  <img src="{{ asset('images/Maintenance/banggia.jpg') }}" alt="Bảng giá dịch vụ bảo dưỡng xe đạp"
       class="w-full h-auto max-w-4xl mx-auto rounded-xl shadow-md mb-6">

  <p class="mb-2 text-base leading-7 text-justify">
    <strong class="text-red-500">Một số lưu ý:</strong><br><br>
    1. Nếu Quý khách không thể mang xe đến cửa hàng để bảo dưỡng hoặc sửa chữa, XEDAP.COM có hỗ trợ dịch vụ 
    <strong>SỬA CHỮA TẬN NHÀ</strong> với phí dịch vụ chỉ <strong>50.000 VNĐ</strong> (chưa bao gồm chi phí sửa chữa). Chi phí cụ thể sẽ được thông báo trước.<br><br>
    2. Giá dịch vụ trên là tiền công thực hiện, chưa bao gồm VAT và các chi phí liên quan khác.
  </p>

  <img src="{{ asset('images/Maintenance/suatannha.jpg') }}" alt="Banner dịch vụ sửa xe tận nhà"
       class="w-full h-auto max-w-4xl mx-auto rounded-xl shadow-md mb-6">
</div>


<!-- Form bảo trì -->
<div class="max-w-xl mx-auto p-8 bg-white rounded-3xl shadow-xl mt-16 mb-20">
  <h2 class="text-3xl font-bold mb-8 text-gray-900 text-center">Gửi Yêu Cầu Bảo Trì Xe Đạp</h2>

  <form action="{{ route('maintenances.store') }}" method="POST" class="space-y-6">
    @csrf

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Tên khách hàng</label>
      <input type="text" name="customer_name"
             class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
             required />
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
      <input type="text" name="phone"
             class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
             required />
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
      <input type="email" name="email"
             class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" />
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Mã sản phẩm</label>
      <input type="text" name="product_sku"
             class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" />
      @error('product_sku')
      <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
      @enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả vấn đề</label>
      <textarea name="issue_description" rows="5"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required></textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Ngày mong muốn bảo trì</label>
      <input type="date" name="preferred_date"
             class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
             required />
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
      <input type="text" name="address"
             class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
             required />
    </div>

    <div class="text-center pt-4">
      <button type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-xl transition duration-200">
        Gửi yêu cầu
      </button>
    </div>
  </form>
</div>


  @include('client.component.footer')

</body>
</html>
