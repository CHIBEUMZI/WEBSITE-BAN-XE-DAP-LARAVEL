@extends('client.layout')

@section('content')
@if(isset($products))
    @if($products->isEmpty())
        <p class="text-center py-12 text-gray-500 text-lg">Không tìm thấy sản phẩm nào.</p>
    @else
        <section class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl font-bold mb-8 text-blue-700">
                    Kết quả tìm kiếm cho: 
                    <span class="italic text-gray-700">
                        {{ request('search') ? '"' . request('search') . '"' : 'Tất cả sản phẩm' }}
                    </span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div class="relative bg-white p-4 rounded-lg shadow-md hover:shadow-xl hover:scale-105 transform transition duration-300 text-center cursor-pointer flex flex-col justify-between h-full">
                            {{-- Ribbon SALE --}}
                            @if($product->discount > 0)
                                <div class="absolute top-0 left-0 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-br-lg z-10">
                                    SALE {{ $product->discount }}%
                                </div>
                            @endif

                            <div>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="mb-4 w-full h-48 object-cover rounded">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                                <div class="mt-2 min-h-[50px]">
                                    <p class="text-red-600 font-bold text-lg">{{ number_format($product->price, 0, ',', '.') }} VND</p>
                                    @if($product->discount > 0)
                                        <p class="text-gray-500 line-through text-sm">{{ number_format($product->original_price, 0, ',', '.') }} VND</p>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('product.details', ['id' => $product->id]) }}" class="mt-3 inline-block bg-blue-600 text-white text-sm px-3 py-1.5 rounded-full hover:bg-blue-700 transition">
                                Mua ngay
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $products->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </section>
    @endif
@endif
<style>
  a{
  text-decoration: none !important;
  }
</style>

@endsection
