<nav class="navbar navbar-expand-lg navbar-dark bg-primary py-3 shadow-sm">
  <div class="container d-flex justify-content-between align-items-center">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="{{ route('client.home') }}">
      <img src="{{ asset('images/Logo/Logo.png') }}" alt="Logo" width="45" height="45" class="me-2">
      <span class="fw-bold fs-4 text-white">xedap.com</span>
    </a>

  <!-- Form tìm kiếm -->
  <form method="GET" action="{{ route('client.products.index') }}" class="modern-search-bar flex-grow-1" role="search">
    <div class="search-box">
      <!-- Dropdown danh mục -->
      <select name="category" class="category-select">
        <option disabled selected>Danh mục</option>
        <option value="Xe đạp thể thao đường phố" {{ request('category') === 'Xe đạp thể thao đường phố' ? 'selected' : '' }}>Xe đạp thể thao đường phố</option>
        <option value="Xe đạp địa hình" {{ request('category') === 'Xe đạp địa hình' ? 'selected' : '' }}>Xe đạp địa hình</option>
        <option value="Xe đạp đua" {{ request('category') === 'Xe đạp đua' ? 'selected' : '' }}>Xe đạp đua</option>
        <option value="Xe đạp gấp" {{ request('category') === 'Xe đạp gấp' ? 'selected' : '' }}>Xe đạp gấp</option>
        <option value="Xe đạp nữ" {{ request('category') === 'Xe đạp nữ' ? 'selected' : '' }}>Xe đạp nữ</option>
        <option value="Xe đạp trẻ em" {{ request('category') === 'Xe đạp trẻ em' ? 'selected' : '' }}>Xe đạp trẻ em</option>
        <option value="Khung sườn" {{ request('category') === 'Khung sườn' ? 'selected' : '' }}>Khung sườn</option>
        <option value="Xe đạp fixed gear" {{ request('category') === 'Xe đạp fixed gear' ? 'selected' : '' }}>Xe đạp fixed gear</option>
      </select>

      <!-- Input tìm kiếm -->
      <input type="search" name="search" value="{{ request('search') }}" class="search-input" placeholder="Tìm kiếm sản phẩm...">

      <!-- Nút tìm kiếm -->
      <button type="submit" class="search-button">
        <i class="fas fa-search"></i>
      </button>
    </div>
  </form>
    <!-- Icon & Menu -->
    <div class="d-flex align-items-center gap-4 ms-4">
      <!-- Giỏ hàng -->
      <a href="{{ route('cart.index') }}" class="text-white position-relative me-2" title="Giỏ hàng">
        <i class="bi bi-cart3 fs-5"></i>
        @if($cartCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger text-white" style="min-width: 20px; height: 20px; font-size: 0.75rem;">
          {{ $cartCount > 99 ? '99+' : $cartCount }}
        </span>
        @endif
      </a>

      <!-- Yêu cầu bảo trì -->
      <a href="{{ route('show.progess') }}" class="text-white position-relative me-22" title="Yêu cầu bảo trì">
        <i class="bi bi-tools fs-5"></i>
        @if($maintenanceCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-success text-white" style="min-width: 20px; height: 20px; font-size: 0.75rem;">
          {{ $maintenanceCount > 99 ? '99+' : $maintenanceCount }}
        </span>
        @endif
      </a>
    <div class="relative">
    <button id="userMenuButton" class="flex items-center gap-2 px-3 py-2 rounded-md focus:outline-none">
        <!-- Avatar -->
        <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/default-avatar.png') }}"
            alt="Avatar"
            class="w-8 h-8 rounded-full object-cover border border-gray-300">

        <!-- Tên người dùng -->
        {{-- <span class="text-sm font-medium text-white">
            {{ Auth::user()->name }}
        </span> --}}

        <!-- Mũi tên dropdown -->
        <svg class="w-4 h-4 text-white-500" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" />
        </svg>
    </button>
      <!-- Dropdown Menu -->
      <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 text-black">
        <a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-gray-100">Thông tin của tôi</a>
        <a href="{{ route('client.orders') }}" class="block px-4 py-2 hover:bg-gray-100">Đơn mua</a>
        <a href="{{ route('maintenances.index') }}" class="block px-4 py-2 hover:bg-gray-100">Yêu cầu bảo trì</a>
        <a href="{{ route('auth.logout') }}" class="block px-4 py-2 hover:bg-gray-100">Đăng xuất</a>
      </div>
    </div>
  </div>
  <script>
    const userMenuButton = document.getElementById("userMenuButton");
    const userMenu = document.getElementById("userMenu");

    userMenuButton.addEventListener("click", () => {
      userMenu.classList.toggle("hidden");
    });

    // Ẩn dropdown khi click bên ngoài
    document.addEventListener("click", (e) => {
      if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
        userMenu.classList.add("hidden");
      }
    });
  </script>
  </div>
</nav>

<style>
/* .btn-orange {
  background-color: #ff6600;
  color: white;
  border: none;
  transition: background-color 0.2s ease;
}

.btn-orange:hover {
  background-color: #e65500;
  color: white;
}

.search-group input:focus {
  box-shadow: none;
  border-color: #ced4da;
}

.search-group input {
  border: 1px solid #ced4da;
  border-right: none;
}

.search-group button {
  border: 1px solid #ced4da;
  border-left: none;
}

.navbar a.text-white:hover,
.navbar .btn-light:hover {
  opacity: 0.9;
  transform: scale(1.02);
}

.dropdown-menu a {
  text-decoration: none;
  color: #333;
}

.dropdown-menu a:hover {
  background-color: #f8f9fa;
  color: #000;
}

.show-on-hover {
  display: none;
  position: absolute;
  right: 0;
  background-color: #fff;
  min-width: 180px;
  z-index: 1000;
}

#userMenuButton:focus + #userMenu,
#userMenuButton:active + #userMenu {
  display: block;
}
.navbar a.text-white:hover i {
  transform: scale(1.2);
  transition: transform 0.2s ease;
}
#userMenu a {
  text-decoration: none; 
} */
/* Thanh tìm kiếm hiện đại */
.modern-search-bar {
  background-color: #0d6efd; /* Nền xanh */
  padding: 10px;
  border-radius: 10px;
  max-width: 600px;
  margin: 0 auto;
}
.search-box {
  display: flex;
  background-color: #fff;
  border-radius: 10px;
  overflow: hidden;
  align-items: center;
}
a{
  text-decoration: none !important;
}
/* Dropdown danh mục gọn gàng */
.category-select {
  border: none;
  padding: 10px 15px;
  background-color: transparent;
  font-weight: bold;
  font-size: 14px;
  appearance: none;
  outline: none;
  cursor: pointer;
}

/* Giao diện ô tìm kiếm */
.search-input {
  flex: 1;
  border: none;
  padding: 10px 15px;
  font-size: 14px;
  outline: none;
  color: #333;
}

/* Icon tìm kiếm */
.search-button {
  background: transparent;
  border: none;
  padding: 0 15px;
  color: #999;
  font-size: 16px;
  cursor: pointer;
}

/* Responsive */
@media (max-width: 576px) {
  .modern-search-bar {
    padding: 6px;
  }

  .search-box {
    flex-direction: column;
    align-items: stretch;
  }

  .category-select,
  .search-input,
  .search-button {
    width: 100%;
    border-bottom: 1px solid #ddd;
  }

  .search-button {
    border-bottom: none;
    text-align: right;
  }
}

</style>
