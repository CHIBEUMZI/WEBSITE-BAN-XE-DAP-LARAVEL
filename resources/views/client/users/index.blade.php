<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xe Đạp Việt Nam - Thông tin cá nhân</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 p-6">
@include('client.users.UserCSS')

<form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
  @csrf
  @method('PUT')

  @php
    use Carbon\Carbon;
    $birthdate = $user->birthday ? Carbon::parse($user->birthday) : null;
  @endphp

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-4">
      <div>
        <label class="block font-semibold">Tên</label>
        <input type="text" name="name" value="{{ $user->name }}" class="w-full p-2 border rounded" />
      </div>

      <div>
        <label class="block font-semibold">Email</label>
        <input type="email" name="email" value="{{ $user->email }}" class="w-full p-2 border rounded" />
      </div>

      <div>
        <label class="block font-semibold">Số điện thoại</label>
        <input type="text" name="phone" value="{{ $user->phone }}" placeholder="Nhập số điện thoại" class="w-full p-2 border rounded" />
      </div>
      <div>
        <label class="block font-semibold">Sinh nhật</label>
        <div class="flex gap-4 mt-2">
          <select name="day" class="border p-2 rounded">
            @for ($i = 1; $i <= 31; $i++)
              <option value="{{ $i }}" {{ $birthdate && $birthdate->day == $i ? 'selected' : '' }}>Ngày {{ $i }}</option>
            @endfor
          </select>
          <select name="month" class="border p-2 rounded">
            @for ($i = 1; $i <= 12; $i++)
              <option value="{{ $i }}" {{ $birthdate && $birthdate->month == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
            @endfor
          </select>
          <select name="year" class="border p-2 rounded">
            @for ($i = now()->year; $i >= 1900; $i--)
              <option value="{{ $i }}" {{ $birthdate && $birthdate->year == $i ? 'selected' : '' }}>Năm {{ $i }}</option>
            @endfor
          </select>
        </div>
      </div>

      <div>
        <label class="block font-semibold">Địa chỉ</label>
        <textarea name="address" rows="3" class="w-full p-2 border rounded" placeholder="Nhập địa chỉ">{{ $user->address }}</textarea>
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Lưu thay đổi</button>
    </div>

    <div class="space-y-4 text-center">
      <img src="{{ $user->image ? asset('storage/' . $user->image) : 'https://via.placeholder.com/120/0070f0/ffffff?text=AVT' }}" alt="Avatar" class="w-32 h-32 rounded-full mx-auto object-cover border">
      <div>
        <input type="file" name="image" class="block w-full text-sm text-gray-500
        file:mr-4 file:py-2 file:px-4
        file:rounded-full file:border-0
        file:text-sm file:font-semibold
        file:bg-blue-50 file:text-blue-700
        hover:file:bg-blue-100"/>
        <p class="text-sm text-gray-500 mt-2">Dung lượng tối đa 10 MB. Định dạng .JPEG, .PNG</p>
      </div>
    </div>
  </div>
</form>

</body>
</html>
