@extends('client.layout')

@section('content')
<section class="py-12 bg-gray-100">
    @if(isset($categories) && $categories->isNotEmpty())
        @foreach ($categories as $category)
            <div class="max-w-7xl mx-auto px-4 mb-5">
                <h2 class="text-3xl font-bold mb-6 text-blue-700 border-b-2 border-blue-300 pb-2">
                    {{ $category }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($categoryProducts[$category] as $product)
                        <div class="relative bg-white p-4 rounded-xl shadow-sm group hover:shadow-lg hover:scale-105 transform transition duration-300 text-center flex flex-col justify-between h-full">
                            {{-- Ribbon SALE --}}
                            @if($product->discount > 0)
                                <div class="absolute top-0 left-0 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-br-lg z-10">
                                    SALE {{ $product->discount }}%
                                </div>
                            @endif

                            <div>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                     class="mb-4 w-full h-48 object-cover rounded-lg group-hover:opacity-90 transition duration-300">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                                <div class="mt-2 min-h-[50px]">
                                    <p class="text-red-600 font-bold text-lg">
                                        {{ number_format($product->price, 0, ',', '.') }} VND
                                    </p>
                                    @if($product->discount > 0)
                                        <p class="text-gray-500 line-through text-sm">
                                            {{ number_format($product->original_price, 0, ',', '.') }} VND
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('product.details', ['id' => $product->id]) }}"
                               class="mt-4 bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-sm font-medium px-4 py-2 rounded-full hover:from-indigo-600 hover:to-blue-600 transition-all shadow-md">
                                Mua ngay
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Tailwind Pagination --}}
                <div class="mt-10 flex justify-center">
                    {{ $categoryProducts[$category]->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endforeach
    @else
        <p class="text-center text-gray-500 py-10 text-xl">Không có sản phẩm nào.</p>
    @endif
</section>
@endsection
