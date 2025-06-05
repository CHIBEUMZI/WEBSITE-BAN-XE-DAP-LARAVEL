@extends('backend.dashboard.layout')
@section('content')
<div class="search-bar mb-4">
    <form action="{{ route('orders.index') }}" method="GET">
        <input type="text" name="keyword" class="search-input form-control" placeholder="🔍 Tìm kiếm..." value="{{ request('keyword') }}">
    </form>
</div>
    {{-- <a href="{{route('orders.create')}}" class="btn btn-sm btn-info">Add orders</a> --}}
    <table class="table align-middle mb-0 bg-white">
        <thead class="bg-light">
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Shipping Address</th>
                <th>Comfirm</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($orders) && is_iterable($orders))
                @foreach ($orders as $key => $val)
                    <tr>
                        <td class="text-center">{{ $val->id }}</td>
                        <td>{{ $val->user_id }}</td>
                        <td>{{ $val->order_date }}</td>
                        <td>{{ number_format($val->total_amount, 0, ',', '.') }} VND</td>
                        <td>{{ $val->status }}</td>
                        <td>{{ $val->shipping_address }}</td>
                        <td>
                            @if($val->status == 'Đang xử lí')
                                <form action="{{ route('orders.confirm_cancel', $val->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="action" value="delivered">
                                    <button type="submit" class="btn btn-primary btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn nâng cấp tài khoản này lên Admin không?')">
                                        Xác nhận giao
                                    </button>
                                </form>
                            @elseif($val->status =='Chờ xác nhận hủy')
                                <form action="{{ route('orders.confirm_cancel', $val->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="action" value="cancel">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn hạ cấp tài khoản này xuống User không?')">
                                        Xác nhận hủy
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">Không có dữ liệu đơn hàng</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="mt-3 d-flex justify-content-center">
        <div class="w-auto">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
<style>
    .search-input {
        max-width: 500px;
    }
</style>
