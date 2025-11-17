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

  <style>
    body {
      font-family: 'Montserrat', sans-serif;
    }

    .error-message {
      color: red;
      font-size: 12px;
      display: block;
      min-height: 14px;
      margin-top: 4px;
    }
  </style>
</head>

<body>
  <section class="vh-100" style="background-color: #9A616D;">
    <div class="container py-5 h-100">
      <div class="row justify-content-center align-items-center h-100">
        <div class="col-xl-10">

          <div class="card shadow-lg">
            <div class="row g-0">

              <!-- Image -->
              <div class="col-md-6 col-lg-5 d-none d-md-block" style="margin-top: 100px;">
                <img src="img/bikeLogin.webp" alt="login form" class="img-fluid"
                     style="border-radius: 1rem 0 0 1rem;" />
              </div>

              <!-- Form -->
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form id="loginForm" action="{{ route('auth.login') }}" method="POST">
                    @csrf

                    <div class="d-flex align-items-center mb-3 pb-1">
                      <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                      <span class="h1 fw-bold mb-0">ĐAM MÊ BẤT TẬN</span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-2">Đăng nhập tài khoản</h5>

                    <!-- Email -->
                    <div class="mb-3">
                      <label class="form-label">Email</label>
                      <input type="text" class="form-control" id="email" name="email"
                             placeholder="Nhập email" value="{{ old('email') }}">
                      <span class="error-message">
                        @if ($errors->has('email')) {{ $errors->first('email') }} @endif
                      </span>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                      <label class="form-label">Mật khẩu</label>
                      <input type="password" class="form-control" id="password" name="password"
                             placeholder="Nhập mật khẩu">
                      <span class="error-message">
                        @if ($errors->has('password')) {{ $errors->first('password') }} @endif
                      </span>
                    </div>

                    <!-- Button -->
                    <div class="mb-3">
                      <button class="btn btn-dark btn-lg btn-block" type="submit">Đăng nhập</button>
                    </div>

                    <div class="mt-3">
                      <a class="small text-muted" href="{{ route('password.form') }}">Quên mật khẩu?</a>
                      <p class="mb-2 mt-2" style="color: #393f81;">Chưa có tài khoản?
                        <a href="{{ route('register.form') }}" style="color: #393f81;">Đăng ký ngay</a>
                      </p>
                    </div>

                  </form>

                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- Validation Script -->
  <script>
    document.getElementById("loginForm").addEventListener("submit", function (e) {

      // Clear all old error messages
      document.querySelectorAll(".error-message").forEach(el => el.innerText = "");

      let valid = true;

      // Validate email
      const email = document.getElementById("email");
      const emailError = email.parentElement.querySelector(".error-message");

      if (email.value.trim() === "") {
        emailError.innerText = "Vui lòng nhập email";
        valid = false;
      }

      // Validate password
      const password = document.getElementById("password");
      const passwordError = password.parentElement.querySelector(".error-message");

      if (password.value.trim() === "") {
        passwordError.innerText = "Vui lòng nhập mật khẩu";
        valid = false;
      }

      // Stop form submit if invalid
      if (!valid) e.preventDefault();
    });
  </script>

</body>

</html>
