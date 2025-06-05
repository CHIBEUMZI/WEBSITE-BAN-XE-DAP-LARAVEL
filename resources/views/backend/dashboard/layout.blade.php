<!DOCTYPE html>
<html lang="vi">
<head>
  @include('backend.dashboard.componet.head')
  <style>
    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        position: relative;
        width: 100%;
        height: auto;
        padding: 10px;
      }
      .main-content {
        margin-left: 0;
        padding: 20px;
      }
    }
    .main-content {
      margin-left: 240px;
      padding: 30px;
      transition: all 0.3s ease;
    }
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
    }
    .wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .content {
      flex: 1;
    }
  </style>
</head>
<body>

  <div class="wrapper">
    <div class="content">
      @include('backend.dashboard.componet.slidebar')
      <div class="main-content">
        @yield('content')
        </div>
    </div>
    @include('backend.dashboard.componet.footer')
  </div>
<!-- Chart Script -->
@include('backend.dashboard.componet.script')
</body>
</html>
