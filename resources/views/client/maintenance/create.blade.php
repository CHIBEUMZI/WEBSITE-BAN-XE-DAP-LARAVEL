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
<body class="bg-white text-gray-800">

  @include('client.component.header')
  @include('client.component.chatbot')
  <!-- Giới thiệu và bảng giá -->
<div class="max-w-7xl mx-auto px-4 py-10 flex flex-col lg:flex-row gap-8">
  <!-- Cột trái: Giới thiệu -->
  <div class="lg:w-2/3 w-full bg-white rounded-2xl p-6 space-y-5">
    <p class="text-justify text-gray-700 leading-7">
      Ngày nay, việc tìm một cửa hàng sửa chữa xe đạp không phải là điều dễ dàng. Là chuỗi cửa hàng bán lẻ xe đạp lớn nhất cả nước, 
      <strong class="text-blue-600">XEDAP.com</strong> với đội ngũ kỹ thuật viên lâu năm, nhiều kinh nghiệm, hiện cung cấp tất cả các dịch vụ (Full) dành cho xe đạp, đặc biệt là các dòng xe đạp thể thao: 
      bảo dưỡng - bảo trì, lắp ráp, build xe mới, sửa chữa, thay thế, nâng cấp phụ tùng.
    </p>

    <p class="text-justify text-gray-700 leading-7">
      Quý khách có nhu cầu sử dụng một trong các dịch vụ trên vui lòng liên hệ và mang xe đến các showroom trực thuộc tại 
      <strong>Hồ Chí Minh, Hà Nội, Đà Nẵng, Bình Dương, Đồng Nai, Tây Ninh, Bà Rịa, Bình Định, Quảng Ngãi, Quảng Nam</strong> 
      với hơn 50 cửa hàng trên toàn quốc.
    </p>

    <p class="text-gray-700 leading-7">
      <strong>Dịch vụ sửa xe tận nhà</strong> phục vụ từ Thứ 2 đến Chủ nhật. <br>
      <strong>Liên hệ ngay hotline:</strong> 
      <span class="text-blue-600 font-semibold">0327266555</span> (8h30 – 17h30)
    </p>

    <h3 class="text-lg font-semibold text-center text-gray-800">BẢNG GIÁ FULL GÓI DỊCH VỤ</h3>

    <p class="text-justify text-gray-700 leading-7">
      Tùy vào tình trạng thực tế của chiếc xe đạp, Quý khách có thể lựa chọn gói bảo dưỡng phù hợp. Khi mang xe đến cửa hàng hoặc sử dụng dịch vụ sửa xe tận nhà, 
      kỹ thuật viên sẽ tư vấn chi tiết. Dưới đây là bảng thông tin các gói bảo dưỡng để Quý khách tham khảo.
    </p>

    <img src="{{ asset('images/Maintenance/banggia.jpg') }}" alt="Bảng giá dịch vụ"
         class="rounded-xl w-full h-auto">

    <p class="text-gray-700 leading-7">
      <strong class="text-red-500">Lưu ý:</strong><br>
      - Nếu Quý khách không thể mang xe đến cửa hàng, XEDAP.COM có hỗ trợ dịch vụ 
      <strong>SỬA CHỮA TẬN NHÀ</strong> với phí dịch vụ chỉ <strong>50.000 VNĐ</strong> (chưa bao gồm chi phí sửa chữa). <br>
      - Giá dịch vụ là tiền công, chưa bao gồm VAT và các chi phí khác.
    </p>

    <img src="{{ asset('images/Maintenance/suatannha.jpg') }}" alt="Banner dịch vụ"
         class="rounded-xl w-full h-auto">
  </div>

  <!-- Cột phải: Form -->
  <div class="lg:w-1/3 w-full bg-white rounded-2xl p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Gửi Yêu Cầu Bảo Trì Xe Đạp</h2>

    <form action="{{ route('maintenances.store') }}" method="POST" class="space-y-4">
      @csrf

      @foreach ([
        ['Tên khách hàng', 'customer_name', 'text'],
        ['Số điện thoại', 'phone', 'text'],
        ['Email', 'email', 'email'],
        ['Mã sản phẩm', 'product_sku', 'text'],
        ['Ngày mong muốn bảo trì', 'preferred_date', 'date'],
        ['Địa chỉ', 'address', 'text']
      ] as [$label, $name, $type])
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
        <input type="{{ $type }}" name="{{ $name }}"
               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
               required />
        @error($name)
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
      </div>
      @endforeach

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả vấn đề</label>
        <textarea name="issue_description" rows="4"
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  required></textarea>
      </div>

      <div class="text-center pt-2">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-xl transition duration-200">
          Gửi yêu cầu
        </button>
      </div>
    </form>
  </div>
</div>


  @include('client.component.footer')

</body>
</html>
