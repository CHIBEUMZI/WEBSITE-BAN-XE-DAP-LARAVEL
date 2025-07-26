<!DOCTYPE html>
<html lang="vi">
<head>
  @include('backend.dashboard.componet.head') {{-- Nếu bạn dùng Tailwind với Vite --}}
</head>
<body class="bg-gray-100 font-sans">

  <div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('backend.dashboard.componet.slidebar')

    {{-- Nội dung chính + Footer --}}
    <div class="ml-64 flex flex-col flex-1">
      <main class="p-6 flex-grow">
        @yield('content')
      </main>

      @include('backend.dashboard.componet.footer')
    </div>
  </div>

  @include('backend.dashboard.componet.script')
</body>
</html>
