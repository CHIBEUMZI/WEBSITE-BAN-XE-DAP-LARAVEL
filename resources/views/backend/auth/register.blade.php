<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Đăng ký tài khoản</h4>
                </div>
                <div class="card-body">
                    <!-- Thông báo thành công -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Hiển thị lỗi validate -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ tên</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="birthday" class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" name="birthday" value="{{ old('birthday') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" name="address" value="{{ old('address') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh đại diện (tuỳ chọn)</label>
                            <input type="file" class="form-control" name="image">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
