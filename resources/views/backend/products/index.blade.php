@extends('backend.dashboard.layout')
@section('content')
<div class="search-bar mb-4">
    <form action="{{ route('products.index') }}" method="GET">
        <input type="text" name="keyword" class="search-input form-control" placeholder="🔍 Tìm kiếm..." value="{{ request('keyword') }}">
    </form>
</div>
    <a href="{{route('products.create')}}" class="btn btn-sm btn-info">Add Products</a>
    <table class="table align-middle mb-0 bg-white">
        <thead class="bg-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Brand</th>
                <th>SKU</th>
                <th>Discount</th>
                <th>Price</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($products) && is_iterable($products))
                @foreach ($products as $key => $val)
                    <tr>
                        <td class="text-center">{{ $val->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $val->image) }}" alt="" style="width: 100px; height: 100px" class="rounded-rectangle" />
                                <div class="ms-3">
                                    <p class="fw-bold mb-1">{{ $val->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $val->category }}</td>
                        <td>{{ $val->brand }}</td>
                        <td>{{ $val->sku }}</td>
                        <td>{{ $val->discount }}</td>
                        <td>{{ $val->price }}</td>
                        <td>{{ $val->stock }}</td>
                        <td>
                            <a href="{{ route('products.edit', $val->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('products.destroy', $val->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xoá không?')">Delete</button>
                            </form>
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
            {{ $products->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
<style>
    .search-input {
        max-width: 500px;
    }
</style>
