<!-- Sidebar -->
<aside class="fixed top-0 left-0 h-full w-64 bg-white shadow-xl z-50 flex flex-col justify-between py-6 px-4">
  <div>
    <h2 class="text-xl font-bold text-indigo-600 flex items-center gap-2 mb-4">
      <i class="fas fa-smile text-indigo-500"></i> Trang quản lý
    </h2>
    <nav class="space-y-2">
      <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl text-gray-700 hover:bg-indigo-100 transition">
        <i class="fas fa-home text-indigo-500"></i> <span>Trang chủ</span>
      </a>
      <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl text-gray-700 hover:bg-indigo-100 transition">
        <i class="fas fa-box text-indigo-500"></i> <span>Sản phẩm</span>
      </a>
      <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl text-gray-700 hover:bg-indigo-100 transition">
        <i class="fas fa-receipt text-indigo-500"></i> <span>Đơn hàng</span>
      </a>
      <a href="{{ route('user.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl text-gray-700 hover:bg-indigo-100 transition">
        <i class="fas fa-users text-indigo-500"></i> <span>Người dùng</span>
      </a>
      <a href="{{ route('AdminMaintenance.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl text-gray-700 hover:bg-indigo-100 transition">
        <i class="fas fa-tools text-indigo-500"></i> <span>Bảo dưỡng xe</span>
      </a>
      <a href="{{ route('employees.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl text-gray-700 hover:bg-indigo-100 transition">
        <i class="fas fa-user-tie text-indigo-500"></i> <span>Nhân viên</span>
      </a>
    </nav>
  </div>

  <!-- Đăng xuất -->
  <a href="{{ route('auth.logout') }}"
     class="bg-red-500 text-white px-6 py-3 rounded-full text-center hover:bg-red-600 transition font-semibold flex items-center justify-center gap-2 mt-6">
    <i class="fas fa-sign-out-alt"></i> Đăng xuất
  </a>
</aside>
