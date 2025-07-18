<!DOCTYPE html>
<html lang="vi">

<head>
  <base href="{{ env('APP_URL') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng nhập - Quản lý cửa hàng xe đạp</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- MDBootstrap CSS -->
  <link href="bootstrap/css/mdb.min.css" rel="stylesheet">

  <!-- MDBootstrap JS -->
  <script src="bootstrap/js/mdb.min.js"></script>

  <!-- Chart.js (nếu cần cho thống kê) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      font-family: 'Montserrat', sans-serif;
    }

    .card {
      border-radius: 1rem;
    }

    .form-control {
      padding: 10px 12px;
      font-size: 15px;
    }

    label {
      font-weight: 500;
      margin-bottom: 4px;
    }

    .error-message {
      color: red;
      font-size: 12px;
      font-family: Arial, Helvetica, sans-serif;
    }

    input[type="password"] {
      font-family: Arial, sans-serif;
    }

    .btn-block {
      width: 100%;
    }
  </style>
</head>

<body>
  <section class="vh-100" style="background-color: #9A616D;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card shadow-lg">
            <div class="row g-0">
              <!-- Hình ảnh bên trái -->
              <div class="col-md-6 col-lg-5 d-none d-md-block" style="margin-top: 100px;">
                <img src="img/bikeLogin.webp" alt="login form" class="img-fluid"
                  style="border-radius: 1rem 0 0 1rem;" />
              </div>

              <!-- Form đăng nhập -->
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">
                  <form action="{{ route('auth.login') }}" method="POST">
                    @csrf

                    <div class="d-flex align-items-center mb-3 pb-1">
                      <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                      <span class="h1 fw-bold mb-0">ĐAM MÊ BẤT TẬN</span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-2" style="letter-spacing: 1px;">Đăng nhập tài khoản</h5>

                    <!-- Email -->
                    <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input type="text" class="form-control" id="email" name="email"
                        placeholder="Nhập email" value="{{ old('email') }}" required>
                      @if ($errors->has('email'))
                        <span class="error-message">{{ $errors->first('email') }}</span>
                      @endif
                    </div>

                    <!-- Mật khẩu -->
                    <div class="mb-3">
                      <label for="password" class="form-label">Mật khẩu</label>
                      <input type="password" class="form-control" id="password" name="password"
                        placeholder="Nhập mật khẩu" required>
                      @if ($errors->has('password'))
                        <span class="error-message">{{ $errors->first('password') }}</span>
                      @endif
                    </div>

                    <!-- Nút đăng nhập -->
                    <div class="mb-3">
                      <button class="btn btn-dark btn-lg btn-block" type="submit"
                        onclick="this.innerText='Đang đăng nhập...'">Đăng nhập</button>
                    </div>

                    <!-- Link phụ -->
                    <div class="mt-3">
                      <a class="small text-muted" href="{{ route('password.form') }}">Quên mật khẩu?</a>
                      <p class="mb-2 mt-2" style="color: #393f81;">Chưa có tài khoản?
                        <a href="{{ route('register.form') }}" style="color: #393f81;">Đăng ký ngay</a>
                      </p>
                    </div>
                  </form>
                </div>
              </div> <!-- end form -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>
