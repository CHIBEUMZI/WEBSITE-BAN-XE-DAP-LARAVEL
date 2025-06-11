<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Xe Đạp Việt Nam - Chi tiết yêu cầu bảo trì</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  @include('backend.dashboard.componet.head')
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

  @include('client.component.header')

 <main class="container mx-auto px-4 py-8 flex-grow">
  <h2 class="text-3xl font-semibold text-blue-700 mb-6 border-b-2 border-blue-400 pb-2">
    Yêu cầu bảo trì
  </h2>
        <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl mx-auto mb-6">
      <!-- Thông tin khách hàng -->
      <section class="mb-4">
        <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-300 pb-2">Thông tin khách hàng</h3>
        <p><span class="font-semibold">Tên:</span> {{ $maintenance->customer_name }}</p>
        <p><span class="font-semibold">Số điện thoại:</span> {{ $maintenance->phone }}</p>
        <p><span class="font-semibold">Email:</span> {{ $maintenance->email }}</p>
        <p><span class="font-semibold">Địa chỉ:</span> {{ $maintenance->address }}</p>
      </section>

      <!-- Thông tin sản phẩm -->
      <section class="mb-4">
        <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-300 pb-2">Thông tin sản phẩm</h3>
        <p><span class="font-semibold">Sản phẩm:</span> {{ $maintenance->product->name ?? 'Không có thông tin' }}</p>
        <p><span class="font-semibold">Mã SKU:</span> {{ $maintenance->product_sku }}</p>
      </section>

      <!-- Thông tin bảo trì -->
      <section class="mb-4">
        {{-- <a href="{{route('product.processBuy' , ['id' => $maintenance->id]) }}">Xem chi tiết</a> --}}
        <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-300 pb-2">Thông tin bảo trì</h3>
        <p><span class="font-semibold">Mô tả sự cố:</span> {{ $maintenance->issue_description }}</p>
        <p><span class="font-semibold">Ngày mong muốn:</span> {{ $maintenance->preferred_date ? $maintenance->preferred_date->format('d/m/Y') : 'Không có' }}</p>
        <p><span class="font-semibold">Trạng thái:</span>
          <span class="
            px-3 py-1 rounded-full text-white
            @switch($maintenance->status)
              @case('Đang xử lý') bg-yellow-500 @break
              @case('Hoàn thành') bg-green-600 @break
              @case('Hủy') bg-red-600 @break
              @default bg-gray-400
            @endswitch
          ">
            @switch($maintenance->status)
              @case('Đang xử lý') Đang xử lý @break
              @case('Hoàn thành') Đã hoàn thành @break
              @case('Hủy') Yêu cầu bị hủy @break
              @default Không rõ
            @endswitch
          </span>
        </p>
      </section>

      <!-- Nhân viên phụ trách -->
      <section>
        <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-300 pb-2">Nhân viên phụ trách</h3>
        <p><span class="font-semibold">Tên nhân viên:</span> {{ $maintenance->employee->name ?? 'Chưa được phân công' }}</p>
        <p><span class="font-semibold">Số điện thoại nhân viên:</span> {{ $maintenance->employee->phone ?? '-' }}</p>
      </section>
    </div>
</main>
  @include('client.component.footer')

</body>
</html>