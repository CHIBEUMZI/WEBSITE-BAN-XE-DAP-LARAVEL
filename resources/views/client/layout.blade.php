<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xe Đạp Việt Nam</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @include('backend.dashboard.componet.head')
</head>
<body class="bg-white font-sans">

  <!-- HEADER -->
  <header>
  @include('client.component.header')
   <!-- Banner -->
  <section class="py-10 bg-gradient-to-r from-blue-100 to-blue-300">
    @include('client.component.banner')
  </section>
  </header>
 @include('client.component.chatbot')
  <!-- Content -->
  @yield('content')

  {{-- FOOTER --}}
  @include('client.component.footer')
  <!-- Chart Script -->
@include('backend.dashboard.componet.script')
</body>
</html>
