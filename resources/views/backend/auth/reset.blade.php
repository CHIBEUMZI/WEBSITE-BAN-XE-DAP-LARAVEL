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
            background-color: #f8f9fa;
        }
        .reset-password-container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }
        h3 {
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="reset-password-container">
        <h3>🔒 Đặt lại mật khẩu</h3>
        <form action="{{ route('password.update.simple', $user->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">🔑 Mật khẩu mới:</label>
                <input type="password" name="password" class="form-control" required placeholder="Nhập mật khẩu mới">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">🔁 Nhập lại mật khẩu:</label>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="Nhập lại mật khẩu">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success">✅ Cập nhật mật khẩu</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
