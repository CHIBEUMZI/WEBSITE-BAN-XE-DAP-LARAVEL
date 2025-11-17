@extends('backend.dashboard.layout')

@section('content')

<!-- Tìm kiếm -->
<div class="mb-4">
    <form action="{{ route('user.index') }}" method="GET" class="max-w-xl">
        <input type="text" name="keyword"
               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
               placeholder="🔍 Tìm kiếm..." value="{{ request('keyword') }}">
    </form>
</div>

<!-- Bảng người dùng -->
<div class="w-full overflow-x-auto rounded-xl shadow bg-white">
    <table class="min-w-[1000px] w-full text-sm text-left text-gray-700">
        <thead class="bg-indigo-50 text-gray-700 font-semibold">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Tên</th>
                <th class="px-4 py-3">SĐT</th>
                <th class="px-4 py-3">Ngày sinh</th>
                <th class="px-4 py-3">Địa chỉ</th>
                <th class="px-4 py-3">Quyền</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Tạo lúc</th>
                <th class="px-4 py-3">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @if(isset($users) && is_iterable($users))
                @foreach ($users as $val)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center">{{ $val->id }}</td>
                        <td class="px-4 py-3 flex items-center gap-3">
                        <img src="{{ !empty($val->image) ? asset('storage/' . $val->image) : asset('images/Avatar/default.jpg') }}" 
                            alt="Ảnh"
                            class="w-10 h-10 rounded-full object-cover" />
                            <div>
                                <p class="font-medium">{{ $val->name }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $val->phone }}</td>
                        <td class="px-4 py-3">{{ $val->birthday }}</td>
                        <td class="px-4 py-3">{{ $val->address }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $val->role === 'Admin' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                {{ $val->role }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $val->email }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $val->created_at }}</td>
                        <td class="px-4 py-3 space-y-1 min-w-[120px]">
                            @if($val->role == 'User')
                                <form action="{{ route('users.changeRole', $val->id) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn nâng cấp tài khoản này lên Admin không?')">
                                    @csrf
                                    <input type="hidden" name="action" value="upgrade">
                                    <button type="submit"
                                            class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded-lg">
                                        Nâng cấp
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('users.changeRole', $val->id) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn hạ cấp tài khoản này xuống User không?')">
                                    @csrf
                                    <input type="hidden" name="action" value="downgrade">
                                    <button type="submit"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded-lg">
                                        Hạ cấp
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center py-4 text-gray-500 italic">Không có dữ liệu người dùng</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Phân trang -->
<div class="mt-6 flex justify-center">
    {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
</div>

@endsection
