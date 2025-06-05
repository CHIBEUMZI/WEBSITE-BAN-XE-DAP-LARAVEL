@extends('backend.dashboard.layout')
@section('content')
<div class="container mt-4">
<form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Tên nhân viên</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $employee->name) }}">
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Số điện thoại</label>
        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label for="position" class="form-label">Vị trí</label>
        <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $employee->position) }}">
        @error('position') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Địa chỉ</label>
        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $employee->address) }}">
        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Ảnh nhân viên</label>
        <input type="file" class="form-control" id="image" name="image">
        @error('image') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật nhân viên</button>
    <a href="{{route('employees.index')}}" class="btn btn-success">Quay lại</a>
</form>
</div>
@endsection