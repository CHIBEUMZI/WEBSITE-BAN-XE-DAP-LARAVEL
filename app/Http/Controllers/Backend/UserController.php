<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
    
        // Tìm kiếm theo tên hoặc email
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%")
                  ->orWhere('role', 'like', "%$keyword%");
            });
        }
    
        $users = $query->paginate(10)->appends($request->all()); // giữ query string khi phân trang
        return view('backend.user.index' ,compact('users'));
    }

    public function changeRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->action === 'upgrade' && $user->role === 'User') {
            $user->role = 'Admin';
        } elseif ($request->action === 'downgrade' && $user->role === 'Admin') {
            $user->role = 'User';
        }

        $user->save();

        return redirect()->back()->with('success', 'Đã thay đổi vai trò người dùng.');
    }


   
}

?>