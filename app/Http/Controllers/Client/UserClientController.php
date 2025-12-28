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
// Đã xóa: use App\Http\Requests\UpdateProfileRequest; 

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
        $user = Auth::user();
        return view('client.users.index', compact('user'));
    }

    /**
     * Cập nhật thông tin profile của người dùng, sử dụng validation trực tiếp.
     */
    public function update(Request $request, $id) 
    {
        // 1. Validation cơ bản (Đã điều chỉnh cho TC2, TC3, TC4)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email', // TC3: Email sai định dạng (kiểm tra email)
            'phone' => 'nullable|string|max:20|regex:/^[0-9]+$/', // TC4: SĐT không hợp lệ (regex chỉ chấp nhận số)
            'day' => 'nullable|integer',
            'month' => 'nullable|integer',
            'year' => 'nullable|integer',
            'address' => 'required|string|max:255', // TC2: Địa chỉ bị bỏ trống (thay từ nullable thành required)
            'image' => 'nullable|image|max:10240', 
        ]);
        
        $user = Auth::user();
        if ($user->id != $id) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Kiểm tra Logic Nghiệp vụ: Ngày sinh không được là ngày trong tương lai (Cho TC5)
        if ($request->day && $request->month && $request->year) {
            try {
                $birthday = Carbon::createFromDate($request->year, $request->month, $request->day);
                
                // Logic cho TC5: Kiểm tra ngày sinh trong tương lai
                if ($birthday->isFuture()) {
                    return redirect()->back()->withErrors(['birthday' => 'Ngày sinh không thể là ngày trong tương lai.']);
                }
                
                // Nếu ngày sinh hợp lệ (không phải tương lai, không phải 30/02), lưu
                $user->birthday = $birthday;

            } catch (\Exception $e) {
                 // Xử lý lỗi ngày tháng không hợp lệ (ví dụ: 30/02)
                 return redirect()->back()->withErrors(['birthday' => 'Ngày sinh không hợp lệ. Vui lòng kiểm tra lại.']);
            }
        } else {
             // Nếu để trống (nullable) và không gửi day/month/year, không cập nhật birthday
        }

        // 3. Cập nhật dữ liệu (TC1: Cập nhật thành công)
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];

        // Xử lý ảnh nếu có upload
        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('image')->store('avatars', 'public');
            $user->image = $path;
        }

        $user->save();

        return redirect()->route('client.home')->with('success', 'Cập nhật thông tin thành công.');
    }

    // ... (Các hàm showForm, checkInfo, showResetForm, updatePassword giữ nguyên)
    
    public function showForm()
    {
        return view('backend.auth.forgot');
    }

    public function checkInfo(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
                    ->where('phone', $request->phone)
                    ->first();

        if ($user) {
            // chuyển tới form đổi mật khẩu
            return redirect()->route('password.reset.form', ['user' => $user->id]);
        } else {
            return back()->withErrors(['email' => 'Email hoặc số điện thoại không chính xác.']);
        }
    }

    public function showResetForm($id)
    {
        $user = User::findOrFail($id);
        return view('backend.auth.reset', compact('user'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công!');
    }
}