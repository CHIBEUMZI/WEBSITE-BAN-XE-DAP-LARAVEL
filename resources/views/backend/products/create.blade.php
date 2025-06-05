@extends('backend.dashboard.layout')
@section('content')

<div class="container mt-4">
    <h3>Thêm sản phẩm mới</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" id="name" name="name">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Danh mục</label>
            <input type="text" class="form-control" id="category" name="category" >
            @error('category') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="original_price" class="form-label">Giá gốc (VNĐ)</label>
            <input type="number" class="form-control" id="original_price" name="original_price">
            @error('original_price') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá bán (VNĐ)</label>
            <input type="number" class="form-control" id="price" name="price" >
            @error('price') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Tồn kho</label>
            <input type="number" class="form-control" id="stock" name="stock" >
            @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="brand" class="form-label">Thương hiệu</label>
            <input type="text" class="form-control" id="brand" name="brand" >
            @error('brand') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="sku" class="form-label">Mã sản phẩm</label>
            <input type="text" class="form-control" id="sku" name="sku" >
            @error('sku') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="discount" class="form-label">Giảm giá (%)</label>
            <input type="text" class="form-control" id="discount" name="discount">
            @error('discount') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả sản phẩm</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Ảnh sản phẩm</label>
            <input type="file" class="form-control" id="image" name="image">
            @error('image') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
        <a href="{{ route('products.index') }}" class="btn btn-success">Quay lại</a>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const originalPriceInput = document.getElementById('original_price');
        const discountInput = document.getElementById('discount');
        const priceInput = document.getElementById('price');

        function calculatePrice() {
            const originalPrice = parseFloat(originalPriceInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;

            if (discount >= 0 && discount <= 100) {
                const discountedPrice = originalPrice * (1 - discount / 100);
                priceInput.value = Math.round(discountedPrice);
            }
        }

        originalPriceInput.addEventListener('input', calculatePrice);
        discountInput.addEventListener('input', calculatePrice);
    });
</script>

@endsection
