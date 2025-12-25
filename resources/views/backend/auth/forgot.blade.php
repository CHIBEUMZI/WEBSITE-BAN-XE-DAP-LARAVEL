<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quên mật khẩu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #9A616D;
      font-family: Arial, sans-serif;
    }

    .forgot-password-container {
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
      padding: 30px;
    }

    .error-message {
      color: red;
      font-size: 13px;
    }

    h3 {
      text-align: center;
      margin-bottom: 25px;
    }
  </style>
</head>
<body>

<section class="vh-100 d-flex align-items-center justify-content-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="forgot-password-container">
          <h3>Quên mật khẩu</h3>

          <form action="{{ route('password.check') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label">Email:</label>
              <input type="email" id="email" name="email" class="form-control"
                    required placeholder="Nhập email của bạn" value="{{ old('email') }}">

              @if(isset($errors) && $errors->has('email'))
                <div class="error-message">{{ $errors->first('email') }}</div>
              @endif
            </div>

            <!-- Số điện thoại -->
            <div class="mb-3">
              <label for="phone" class="form-label">Số điện thoại:</label>
              <input type="text" id="phone" name="phone" class="form-control"
                    required placeholder="Nhập số điện thoại" value="{{ old('phone') }}">

              @if(isset($errors) && $errors->has('phone'))
                <div class="error-message">{{ $errors->first('phone') }}</div>
              @endif
            </div>

            <!-- Nút xác nhận -->
            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-primary" onclick="this.innerText='Đang xử lý...'">Xác nhận</button>
            </div>

          </form>

          <!-- Quay lại đăng nhập -->
          <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none">← Quay lại đăng nhập</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
