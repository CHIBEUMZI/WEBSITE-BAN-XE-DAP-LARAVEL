@extends('backend.dashboard.layout')

@section('content')

<!-- Tìm kiếm -->
<div class="mb-4">
    <form action="{{ route('orders.index') }}" method="GET" class="max-w-xl">
        <input type="text" name="keyword"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="🔍 Tìm kiếm..." value="{{ request('keyword') }}">
    </form>
</div>

<!-- Bảng đơn hàng -->
<div class="overflow-x-auto bg-white shadow rounded-xl">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-indigo-50 text-gray-700 font-semibold text-left">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">User ID</th>
                <th class="px-4 py-3">Ngày đặt</th>
                <th class="px-4 py-3">Tổng tiền</th>
                <th class="px-4 py-3">Trạng thái</th>
                <th class="px-4 py-3">Địa chỉ giao</th>
                <th class="px-4 py-3">Thanh toán</th>
                <th class="px-4 py-3">Xác nhận</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @if(isset($orders) && is_iterable($orders))
                @foreach ($orders as $val)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center">{{ $val->id }}</td>
                        <td class="px-4 py-3">{{ $val->user_id }}</td>
                        <td class="px-4 py-3">{{ $val->order_date }}</td>
                        <td class="px-4 py-3 text-green-600 font-semibold">{{ number_format($val->total_amount, 0, ',', '.') }} đ</td>
                        <td class="px-4 py-3">                            
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $val->status === 'Đang xử lý' ? 'bg-yellow-100 text-yellow-600' :
                                   ($val->status === 'Giao hàng' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600') }}">
                                {{ $val->status }}
                            </span></td>
                        <td class="px-4 py-3">{{ $val->shipping_address }}</td>
                        <td class="px-4 py-3">{{ $val->payment_status }}</td>
                        <td class="px-4 py-3 space-x-1">
                            @if($val->status == 'Đang xử lý')
                                <form action="{{ route('orders.confirm_cancel', $val->id) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Bạn có chắc muốn xác nhận giao đơn hàng này?')">
                                    @csrf
                                    <input type="hidden" name="action" value="delivered">
                                    <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded-lg">
                                        Giao hàng
                                    </button>
                                </form>
                            @elseif($val->status == 'Chờ xác nhận hủy')
                                <form action="{{ route('orders.confirm_cancel', $val->id) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Bạn có chắc muốn xác nhận hủy đơn hàng này không?')">
                                    @csrf
                                    <input type="hidden" name="action" value="cancel">
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded-lg">
                                        Hủy đơn
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center py-4 text-gray-500 italic">Không có dữ liệu đơn hàng</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Phân trang -->
<div class="mt-6 flex justify-center">
    {{ $orders->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
</div>

@endsection
