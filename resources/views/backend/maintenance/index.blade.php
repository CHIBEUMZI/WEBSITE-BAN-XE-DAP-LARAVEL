@extends('backend.dashboard.layout')
@section('content')
    <div class="search-bar mb-4">
        <form action="{{ route('AdminMaintenance.index') }}" method="GET">
            <input type="text" name="keyword" class="search-input form-control" placeholder="🔍 Tìm kiếm bằng tên khách hàng, số điện thoại, SKU..." value="{{ request('keyword') }}">
        </form>
    </div>
    <table class="table align-middle mb-0 bg-white">
        <thead class="bg-light">
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Product SKU</th>
                <th>Issue description</th>
                <th>preferred date</th>
                <th>Address</th>
                <th>Trạng thái</th>
                <th>Nhân viên</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($maintenances) && is_iterable($maintenances))
                @foreach ($maintenances as $key => $val)
                    <tr>
                        <td class="text-center">{{ $val->id }}</td>
                        <td>{{ $val->customer_name }}</td>
                        <td>{{ $val->phone }}</td>
                        <td>{{ $val->email }}</td>
                        <td>{{ $val->product_sku }}</td>
                        <td>{{ $val->issue_description }}</td>
                        <td>{{ $val->preferred_date }}</td>
                        <td>{{ $val->address }}</td>
                        <td>{{ $val->status }}</td>
                        <td>
                            <form action="{{ route('AdminMaintenance.assignEmployee', $val->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="employee_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">-- Chọn nhân viên --</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ $val->employee_id == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td>
                        {{-- Nút thay đổi trang thái bảo trì--}}
                        @if($val->status ==='Đang xử lý')
                                <form action="{{ route('maintenance.comfirm', $val->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="action" value="pending">
                                    <button type="submit" class="btn btn-primary btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn xác nhận đã hoàn thành không?')">
                                        Đã hoàn thành
                                    </button>
                                </form>
                                <form action="{{ route('maintenance.comfirm', $val->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="action" value="cancel">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn hủy yêu cầu này không không?')">
                                        Hủy yêu cầu
                                    </button>
                                </form>
                                @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">Không có dữ liệu người dùng</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="mt-3 d-flex justify-content-center">
        <div class="w-auto">
            {{ $maintenances->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
<style>
.search-input {
        max-width: 500px;
    }
.pagination {
    font-size: 13px;
    padding: 0;
    margin: 0;
    justify-content: center;
}

.page-item {
    margin: 0 2px;
}

.page-link {
    padding: 4px 10px;
    font-size: 12px;
    border-radius: 6px;
    color: #333;
    border: 1px solid #dee2e6;
}

.page-link:hover {
    background-color: #f0f0f0;
    color: #000;
}

.page-item.active .page-link {
    background-color: #cce5ff;
    border-color: #b8daff;
    color: #000;
}
.main-content {
      margin-left: 220px;
      padding: 30px;
      transition: all 0.3s ease;
    }
</style>
