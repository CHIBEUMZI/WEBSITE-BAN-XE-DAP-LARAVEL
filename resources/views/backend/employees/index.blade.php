@extends('backend.dashboard.layout')

@section('content')
<!-- Tìm kiếm -->
<div class="mb-4">
    <form action="{{ route('employees.index') }}" method="GET" class="max-w-md">
        <input
            type="text"
            name="keyword"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="🔍 Tìm kiếm..."
            value="{{ request('keyword') }}">
    </form>
</div>

<!-- Thêm mới -->
 <button>
<a href="{{ route('employees.create') }}"
    id="add_employee_btn"
    class="inline-block mb-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition">
    ➕ Thêm nhân viên
</a>
</button
<!-- Bảng nhân viên -->
<div class="overflow-x-auto bg-white shadow rounded-xl">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-indigo-50">
            <tr class="text-left text-gray-700 font-semibold">
                <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">Tên</th>
                <th class="px-6 py-3">Số điện thoại</th>
                <th class="px-6 py-3">Chức vụ</th>
                <th class="px-6 py-3">Địa chỉ</th>
                <th class="px-6 py-3">Hành động</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @if(isset($employees) && is_iterable($employees))
                @foreach ($employees as $val)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-center">{{ $val->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('storage/' . $val->image) }}"
                                     alt="Ảnh"
                                     class="w-16 h-16 object-cover rounded-xl border border-gray-300" />
                                <span class="font-medium">{{ $val->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $val->phone }}</td>
                        <td class="px-6 py-4">{{ $val->position }}</td>
                        <td class="px-6 py-4">{{ $val->address }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('employees.edit', $val->id) }}"
                               class="inline-block text-sm bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded mr-1">
                                ✏️ Sửa
                            </a>
                            <form action="{{ route('employees.destroy', $val->id) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Bạn có chắc muốn xoá không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-sm bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                    🗑️ Xoá
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500 italic">Không có dữ liệu nhân viên</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Phân trang -->
<div class="mt-6 flex justify-center">
    {{ $employees->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
</div>
@endsection
