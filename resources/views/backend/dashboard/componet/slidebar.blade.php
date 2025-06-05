<div class="sidebar d-flex flex-column justify-content-between">
    <div>
      <h5>Trang Quản Lý</h5>
      <a href="{{route('dashboard.index')}}"><i class="fas fa-home"></i> Trang chủ</a>
      <a href="{{route('products.index')}}"><i class="fas fa-box"></i> Sản phẩm</a>
      <a href="{{route('orders.index')}}"><i class="fas fa-receipt"></i> Đơn hàng</a>
      <a href="{{route('user.index')}}"><i class="fas fa-users"></i> Người Dùng</a>
      <a href="{{ route('AdminMaintenance.index') }}"><i class="fas fa-tools"></i> Bảo dưỡng xe</a>
      <a href="{{route('employees.index')}}"><i class="fas fa-user-tie"></i> Nhân viên</a>
      {{-- <a href="#"><i class="fas fa-chart-bar"></i> Báo cáo</a> --}}
    </div>
    <a href="{{ route('auth.logout') }}" class="logout-btn d-flex align-items-center justify-content-center gap-2">
      <span>Đăng xuất</span><i class="fas fa-sign-out-alt"></i>
    </a> 
</div>
<style>
   body {
      background-color: #f3f3f4;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
    }

    .sidebar {
      width: 220px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #2f4050;
      padding: 20px;
      color: #fff;
      z-index: 1000;
    }

    .sidebar h5 {
      font-weight: bold;
      margin-bottom: 30px;
      text-align: center;
      color: #fff;
    }

    .sidebar a {
      text-decoration: none;
      color: #a7b1c2;
      display: block;
      padding: 10px 15px;
      transition: 0.2s;
      border-radius: 10px;
    }

    .sidebar a:hover,
    .sidebar a.active {
      color: #fff;
      background-color: rgba(255, 255, 255, 0.1);
      font-weight: bold;
    }

    .logout-btn {
      display: inline-block;
      padding: 20px 10px;
      background-color: transparent;
      color: #fff;
      border: 1px solid #fff;
      border-radius: 30px;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .logout-btn:hover {
      background-color: red;
      color: #2f4050;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
</style>