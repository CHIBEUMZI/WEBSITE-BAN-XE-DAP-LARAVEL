<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UserClientController extends Controller
{
    public function register(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'birthday' => 'required|date',
            'address' => 'required|string|min:5',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // cần field password_confirmation
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Lưu ảnh nếu có
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/users', 'public');
        }

        // Tạo user mới
        User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'birthday' => $validated['birthday'],
            'address' => $validated['address'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'User', // hoặc để null nếu chưa cần phân quyền
            'image' => $imagePath,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký tài khoản thành công!');
    }

    public function show()
    {
    $user = Auth::user(); // Lấy thông tin user đang đăng nhập
    return view('client.users.index', compact('user'));
    }

  public function update(Request $request, $id)
{
    // Validate dữ liệu
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'day' => 'nullable|integer',
        'month' => 'nullable|integer',
        'year' => 'nullable|integer',
        'address' => 'nullable|string|max:255',
        'image' => 'nullable|image|max:10240', // 10MB
    ]);

    $user = User::findOrFail($id);

    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->phone = $validated['phone'];
    $user->address = $validated['address'];

    // Xử lý ngày sinh nếu có đủ 3 phần
    if ($request->day && $request->month && $request->year) {
        $user->birthday = Carbon::createFromDate($request->year, $request->month, $request->day);
    }

    // Xử lý ảnh nếu có upload
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('avatars', 'public');
        $user->image = $path;
    }

    $user->save();

    return redirect()->route('client.home')->with('success', 'Cập nhật thông tin thành công.');
}


}
