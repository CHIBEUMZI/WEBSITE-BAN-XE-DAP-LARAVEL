<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .forgot-password-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h3 {
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="forgot-password-container">
        <h3>🔐 Quên mật khẩu</h3>
        <form action="{{ route('password.check') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">📧 Email:</label>
                <input type="email" name="email" class="form-control" required placeholder="Nhập email của bạn">
                @error('email') 
                    <small class="text-danger">{{ $message }}</small> 
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">📱 Số điện thoại:</label>
                <input type="text" name="phone" class="form-control" required placeholder="Nhập số điện thoại">
                @error('phone') 
                    <small class="text-danger">{{ $message }}</small> 
                @enderror
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">🔒 Xác nhận</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS (tùy chọn nếu dùng tính năng nâng cao) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
