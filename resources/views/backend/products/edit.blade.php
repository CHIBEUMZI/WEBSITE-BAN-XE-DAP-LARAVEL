@extends('backend.dashboard.layout')
@section('content')
<div class="container mt-4">
    <h3>Chỉnh sửa sản phẩm</h3>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Danh mục</label>
            <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $product->category) }}">
            @error('category') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="original_price" class="form-label">Giá gốc (VNĐ)</label>
            <input type="number" class="form-control" id="original_price" name="original_price" value="{{ old('original_price', $product->original_price) }}">
            @error('original_price') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá bán (VNĐ)</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}">
            @error('price') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Tồn kho</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock) }}">
            @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="brand" class="form-label">Thương hiệu</label>
            <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand', $product->brand) }}">
            @error('brand') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="brand" class="form-label">Thương hiệu</label>
            <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
            @error('sku') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="discount" class="form-label">Giảm giá (%)</label>
            <input type="text" class="form-control" id="discount" name="discount" value="{{ old('discount', $product->discount) }}">
            @error('discount') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả sản phẩm</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Ảnh sản phẩm</label>
            <input type="file" class="form-control" id="image" name="image">
            @if($product->image)
                <p class="mt-2">Ảnh hiện tại: <img src="{{ asset('storage/images/' . $product->image) }}" alt="" width="100"></p>
            @endif
            @error('image') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
        <a href="{{ route('products.index') }}" class="btn btn-success">Quay lại</a>
    </form>
</div>
@endsection
