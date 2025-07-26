@extends('backend.dashboard.layout')

@section('content')

<!-- Tìm kiếm -->
<div class="mb-4">
    <form action="{{ route('AdminMaintenance.index') }}" method="GET" class="max-w-xl">
        <input type="text"
            name="keyword"
            placeholder="🔍 Tìm kiếm bằng tên khách hàng, số điện thoại, SKU..."
            value="{{ request('keyword') }}"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </form>
</div>

<!-- Bảng dữ liệu bảo trì -->
<div class="w-full overflow-x-auto bg-white shadow rounded-xl">
    <table class="min-w-[1100px] w-full text-sm text-left text-gray-700 divide-y divide-gray-200">
        <thead class="bg-indigo-50 text-gray-700 font-semibold">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Khách hàng</th>
                <th class="px-4 py-3">SĐT</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">SKU</th>
                <th class="px-4 py-3">Mô tả lỗi</th>
                <th class="px-4 py-3">Ngày hẹn</th>
                <th class="px-4 py-3">Địa chỉ</th>
                <th class="px-4 py-3">Trạng thái</th>
                <th class="px-4 py-3">Nhân viên</th>
                <th class="px-4 py-3">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @if(isset($maintenances) && is_iterable($maintenances))
                @foreach ($maintenances as $val)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center">{{ $val->id }}</td>
                        <td class="px-4 py-3">{{ $val->customer_name }}</td>
                        <td class="px-4 py-3">{{ $val->phone }}</td>
                        <td class="px-4 py-3">{{ $val->email }}</td>
                        <td class="px-4 py-3">{{ $val->product_sku }}</td>
                        <td class="px-4 py-3">{{ $val->issue_description }}</td>
                        <td class="px-4 py-3">{{ $val->preferred_date }}</td>
                        <td class="px-4 py-3">{{ $val->address }}</td>
                        <td class="px-2 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $val->status === 'Đang xử lý' ? 'bg-yellow-100 text-yellow-600' :
                                   ($val->status === 'Hoàn thành' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600') }}">
                                {{ $val->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('AdminMaintenance.assignEmployee', $val->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="employee_id"
                                    class="w-full border border-gray-300 text-sm rounded-lg px-2 py-1 focus:ring-indigo-500"
                                    onchange="this.form.submit()">
                                    <option value="">-- Chọn nhân viên --</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ $val->employee_id == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3 space-y-1">
                            @if($val->status === 'Đang xử lý')
                                <form action="{{ route('maintenance.comfirm', $val->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn xác nhận đã hoàn thành không?')">
                                    @csrf
                                    <input type="hidden" name="action" value="pending">
                                    <button type="submit"
                                        class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded-lg">
                                        Hoàn thành
                                    </button>
                                </form>

                                <form action="{{ route('maintenance.comfirm', $val->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy yêu cầu này không?')">
                                    @csrf
                                    <input type="hidden" name="action" value="cancel">
                                    <button type="submit"
                                        class="w-full bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded-lg">
                                        Hủy
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="11" class="text-center py-4 text-gray-500 italic">Không có dữ liệu người dùng</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Phân trang -->
<div class="mt-6 flex justify-center">
    {{ $maintenances->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
</div>

@endsection
