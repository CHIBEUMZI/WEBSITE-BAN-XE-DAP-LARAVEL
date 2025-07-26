<!-- TOP BAR -->
<header class="bg-white-900 text-white py-3">
  <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
    <div class="flex flex-wrap justify-center md:justify-start items-center gap-x-6 gap-y-2 text-sm">
      <a href="#" class="flex items-center hover:text-blue-200 transition-colors duration-200">
        <i class="fas fa-map-marker-alt mr-2"></i>
        <span>TÌM ĐỊA CHỈ CỬA HÀNG</span>
      </a>
      <a href="tel:18009473" class="flex items-center hover:text-blue-200 transition-colors duration-200">
        <i class="fas fa-phone-alt mr-2"></i>
        <span>TƯ VẤN: 1800 9473 (8:30–21:00)</span>
      </a>
      <a href="tel:18009063" class="flex items-center hover:text-blue-200 transition-colors duration-200">
        <i class="fas fa-tools mr-2"></i>
        <span>BẢO HÀNH: 1800 9063 (9:00–17:00)</span>
      </a>
    </div>

    <div class="flex flex-wrap justify-center md:justify-end items-center gap-2 text-xs font-semibold">
      <a href="https://xedien.vn" class="bg-green-500 px-3 py-1 rounded-full text-white hover:bg-green-600 transition-colors duration-200" target="_blank" rel="noopener noreferrer">
        🚲 XEDIEN.COM
      </a>
      <span class="bg-red-600 px-3 py-1 rounded-full text-white">
        FREESHIP NỘI THÀNH
      </span>
      <span class="bg-yellow-500 px-3 py-1 rounded-full text-white">
        GIAO NHANH 48H 📞 1800 9473
      </span>
    </div>
  </div>
</header>
<!-- MAIN NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary py-3 shadow-sm sticky-top z-50">
  <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center no-underline" href="{{ route('client.home') }}">
      <img src="{{ asset('images/Logo/Logo.png') }}" alt="Logo" width="45" height="45" class="me-2">
      <span class="fw-bold fs-4 text-white">xedap.com</span>
    </a>
    <!-- Search -->
    <form method="GET" action="{{ route('client.products.index') }}" class="modern-search-bar flex-grow-1" role="search">
      <div class="search-box">
        {{-- <select name="category" class="category-select">
          <option disabled selected>Danh mục</option>
          <option value="Xe đạp thể thao đường phố" {{ request('category') === 'Xe đạp thể thao đường phố' ? 'selected' : '' }}>Xe đạp thể thao đường phố</option>
          <option value="Xe đạp địa hình" {{ request('category') === 'Xe đạp địa hình' ? 'selected' : '' }}>Xe đạp địa hình</option>
          <option value="Xe đạp đua" {{ request('category') === 'Xe đạp đua' ? 'selected' : '' }}>Xe đạp đua</option>
          <option value="Xe đạp gấp" {{ request('category') === 'Xe đạp gấp' ? 'selected' : '' }}>Xe đạp gấp</option>
          <option value="Xe đạp nữ" {{ request('category') === 'Xe đạp nữ' ? 'selected' : '' }}>Xe đạp nữ</option>
          <option value="Xe đạp trẻ em" {{ request('category') === 'Xe đạp trẻ em' ? 'selected' : '' }}>Xe đạp trẻ em</option>
          <option value="Khung sườn" {{ request('category') === 'Khung sườn' ? 'selected' : '' }}>Khung sườn</option>
          <option value="Xe đạp fixed gear" {{ request('category') === 'Xe đạp fixed gear' ? 'selected' : '' }}>Xe đạp fixed gear</option>
        </select> --}}

        <input type="search" name="search" value="{{ request('search') }}" class="search-input" placeholder="Tìm kiếm sản phẩm...">
        <button type="submit" class="search-button">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </form>

    <!-- Icons + User -->
<div class="flex items-center gap-6 ms-4">
  <!-- Giỏ hàng -->
  <a href="{{ route('cart.index') }}" class="relative text-white hover:text-blue-200 transition" title="Giỏ hàng">
    <i class="bi bi-cart3 text-2xl"></i>
    @if($cartCount > 0)
    <span class="absolute -top-2 -right-3 bg-red-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
      {{ $cartCount > 99 ? '99+' : $cartCount }}
    </span>
    @endif
  </a>

  <!-- Bảo trì -->
  <a href="{{ route('show.progess') }}" class="relative text-white hover:text-blue-200 transition" title="Yêu cầu bảo trì">
    <i class="bi bi-tools text-2xl"></i>
    @if($maintenanceCount > 0)
    <span class="absolute -top-2 -right-3 bg-green-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
      {{ $maintenanceCount > 99 ? '99+' : $maintenanceCount }}
    </span>
    @endif
  </a>

  <!-- Avatar + Dropdown -->
  <div class="relative">
    <button id="userMenuButton" class="flex items-center gap-2 focus:outline-none">
      <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/default-avatar.png') }}"
        alt="Avatar"
        class="w-9 h-9 rounded-full object-cover border-2 border-white shadow" />
      <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
        <path d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" />
      </svg>
    </button>

    <!-- Dropdown -->
    <div id="userMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl z-50 text-gray-700 ring-1 ring-gray-200 transition-all origin-top-right">
      <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 transition no-underline">
        <i class="bi bi-person-circle text-blue-500 text-lg"></i>
        <span>Thông tin của tôi</span>
      </a>
      <a href="{{ route('client.orders') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 transition no-underline">
        <i class="bi bi-bag-check text-green-500 text-lg"></i>
        <span>Đơn mua</span>
      </a>
      <a href="{{ route('maintenances.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 transition no-underline">
        <i class="bi bi-tools text-yellow-500 text-lg"></i>
        <span>Yêu cầu bảo trì</span>
      </a>
      <a href="{{ route('auth.logout') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 text-red-600 transition no-underline">
        <i class="bi bi-box-arrow-right text-lg"></i>
        <span>Đăng xuất</span>
      </a>
    </div>
  </div>
</div>

  </div>
</nav>
<script>
  const userMenuButton = document.getElementById("userMenuButton");
  const userMenu = document.getElementById("userMenu");

  userMenuButton.addEventListener("click", (e) => {
    e.stopPropagation();
    const isHidden = userMenu.classList.contains("hidden");

    if (isHidden) {
      userMenu.classList.remove("hidden");
      requestAnimationFrame(() => {
        userMenu.classList.remove("opacity-0", "scale-95");
        userMenu.classList.add("opacity-100", "scale-100");
      });
    } else {
      userMenu.classList.add("opacity-0", "scale-95");
      userMenu.classList.remove("opacity-100", "scale-100");
      setTimeout(() => {
        userMenu.classList.add("hidden");
      }, 200);
    }
  });

  document.addEventListener("click", (e) => {
    if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
      userMenu.classList.add("opacity-0", "scale-95");
      userMenu.classList.remove("opacity-100", "scale-100");
      setTimeout(() => {
        userMenu.classList.add("hidden");
      }, 200);
    }
  });
</script>


<!-- STYLE -->
<style>
  .clip-diagonal {
    clip-path: polygon(10% 0, 100% 0, 90% 100%, 0% 100%);
  }
  .modern-search-bar {
    background-color: #0d6efd;
    padding: 8px;
    border-radius: 10px;
    max-width: 600px;
    width: 100%;
    margin: 0 auto;
  }
a {
  text-decoration: none !important;
}

  .search-box {
    display: flex;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    align-items: center;
  }

  .category-select {
    border: none;
    padding: 10px 15px;
    background-color: transparent;
    font-weight: bold;
    font-size: 14px;
    outline: none;
    cursor: pointer;
  }

  .search-input {
    flex: 1;
    border: none;
    padding: 10px 15px;
    font-size: 14px;
    outline: none;
    color: #333;
  }

  .search-button {
    background: transparent;
    border: none;
    padding: 0 15px;
    color: #999;
    font-size: 16px;
    cursor: pointer;
  }

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

  .dropdown-item {
    display: block;
    padding: 8px 16px;
    text-decoration: none;
    color: #333;
  }

  .dropdown-item:hover {
    background-color: #f8f9fa;
  }
</style>
