<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xe Đạp Việt Nam</title>
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>

  <!-- Alpine.js -->
  <script src="//unpkg.com/alpinejs" defer></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    @include('backend.dashboard.componet.head')
</head>
<body class="bg-white font-sans">
  <!-- HEADER -->
  @include('client.component.header')
  @include('client.component.banner')
  @include('client.component.category')

  @include('client.component.chatbot')

  <!-- Nội dung -->
  @yield('content')

  <!-- FOOTER -->
  @include('client.component.footer')

  @include('backend.dashboard.componet.script')
</body>

</body>
</html>
