@extends('backend.dashboard.layout')
@section('content')
    <div class="search-bar mb-4">
        <form action="{{ route('user.index') }}" method="GET">
            <input type="text" name="keyword" class="search-input form-control" placeholder="🔍 Tìm kiếm..." value="{{ request('keyword') }}">
        </form>
    </div>
    <table class="table align-middle mb-0 bg-white">
        <thead class="bg-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Birthday</th>
                <th>Address</th>
                <th>Role</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($users) && is_iterable($users))
                @foreach ($users as $key => $val)
                    <tr>
                        <td class="text-center">{{ $val->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $val->image) }}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                                <div class="ms-3">
                                    <p class="fw-bold mb-1">{{ $val->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $val->phone }}</td>
                        <td>{{ $val->birthday }}</td>
                        <td>{{ $val->address }}</td>
                        <td>{{ $val->role }}</td>
                        <td>{{ $val->email }}</td>
                        <td>{{ $val->created_at }}</td>
                        <td>{{ $val->updated_at }}</td>
                        <td>
                        {{-- Nút thay đổi role tài khoản --}}
                            @if($val->role == 'User')
                                <form action="{{ route('users.changeRole', $val->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="action" value="upgrade">
                                    <button type="submit" class="btn btn-primary btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn nâng cấp tài khoản này lên Admin không?')">
                                        Nâng cấp tài khoản
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('users.changeRole', $val->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="action" value="downgrade">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn hạ cấp tài khoản này xuống User không?')">
                                        Hạ cấp tài khoản
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
            {{ $users->links() }}
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
