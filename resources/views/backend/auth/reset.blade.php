<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đặt lại mật khẩu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #9A616D;
      font-family: Arial, sans-serif;
    }

    .reset-password-container {
      background-color: #fff;
      border-radius: 1rem;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
      padding: 30px;
    }

    h3 {
      text-align: center;
      margin-bottom: 25px;
    }

    .error-message {
      color: red;
      font-size: 13px;
    }
  </style>
</head>

<body>
  <section class="vh-100 d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="reset-password-container">
            <h3> Đặt lại mật khẩu</h3>

            <form action="{{ route('password.update.simple', $user->id) }}" method="POST">
              @csrf

              <!-- Mật khẩu mới -->
              <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu mới:</label>
                <input type="password" id="password" name="password"
                      class="form-control" required placeholder="Nhập mật khẩu mới">

                @if(isset($errors) && $errors->has('password'))
                  <div class="error-message">{{ $errors->first('password') }}</div>
                @endif
              </div>

              <!-- Nhập lại mật khẩu -->
              <div class="mb-3">
                <label for="password_confirmation" class="form-label"> Nhập lại mật khẩu:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Nhập lại mật khẩu">
              </div>

              <!-- Nút cập nhật -->
              <div class="d-grid">
                <button type="submit" class="btn btn-success" onclick="this.innerText='Đang xử lý...'"> Cập nhật mật khẩu</button>
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
