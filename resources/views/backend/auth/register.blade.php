<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký tài khoản</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    body {
      background-color: #9A616D;
      font-family: Arial, sans-serif;
    }

    .error-message {
      color: red;
      font-size: 13px;
    }

    .card {
      border-radius: 1rem;
    }

    .form-control:focus {
      box-shadow: none;
      border-color: #9A616D;
    }
  </style>
</head>

<body>
  <section class="vh-100">
    <div class="container py-5 h-100">
      <div class="row justify-content-center align-items-center h-100">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-primary text-white text-center">
              <h4 class="mb-0">Đăng ký tài khoản</h4>
            </div>
            <div class="card-body">

              <!-- Thông báo thành công -->
              @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
              @endif

              <form action="{{ route('user.register') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Họ tên -->
                <div class="mb-3">
                  <label for="name" class="form-label">Họ tên</label>
                  <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                  @error('name')
                  <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Số điện thoại -->
                <div class="mb-3">
                  <label for="phone" class="form-label">Số điện thoại</label>
                  <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                  @error('phone')
                  <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Ngày sinh -->
                <div class="mb-3">
                  <label for="birthday" class="form-label">Ngày sinh</label>
                  <input type="date" class="form-control" id="birthday" name="birthday" value="{{ old('birthday') }}" required>
                  @error('birthday')
                  <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Địa chỉ -->
                <div class="mb-3">
                  <label for="address" class="form-label">Địa chỉ</label>
                  <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                  @error('address')
                  <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                  @error('email')
                  <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Mật khẩu -->
                <div class="mb-3">
                  <label for="password" class="form-label">Mật khẩu</label>
                  <input type="password" class="form-control" id="password" name="password" required>
                  @error('password')
                  <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Xác nhận mật khẩu -->
                <div class="mb-3">
                  <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                  <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <!-- Ảnh đại diện -->
                <div class="mb-3">
                  <label for="image" class="form-label">Ảnh đại diện (tuỳ chọn)</label>
                  <input type="file" class="form-control" id="image" name="image">
                  @error('image')
                  <div class="error-message">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Nút đăng ký -->
                <button type="submit" class="btn btn-primary w-100" onclick="this.innerText='Đang xử lý...'">Đăng ký</button>
              </form>

              <!-- Chuyển hướng -->
              <div class="text-center mt-3">
                <p class="text-muted">Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>
