@extends('client.layout')

@section('content')
<section class="bg-cover bg-center bg-no-repeat py-12" style="background-image: url('{{ asset('images/Banner/banner-summer.jpg') }}');">
  <div class="bg-white/80 py-4 rounded-xl max-w-7xl mx-auto px-4 shadow-lg">
    {{-- Tiêu đề --}}
    <div class="text-center mb-8">
      <h2 class="text-4xl font-bold text-orange-600 drop-shadow tracking-wide">
        🔥 BÃO SALE CUỐI HÈ – SĂN DEAL CỰC CHÁY 🔥
      </h2>
      <p class="text-gray-700 mt-2 text-sm">Chớp cơ hội sở hữu sản phẩm yêu thích với giá cực tốt!</p>

      <div class="inline-block mt-4 bg-red-600 text-white px-6 py-2 text-lg font-bold rounded-full shadow">
        DEAL HOT HÔM NAY
      </div>
    </div>

    {{-- Danh sách sản phẩm --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @if(isset($topsales))
        @forelse($topsales as $product)
          <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 relative group">
            {{-- Badge giảm giá --}}
            <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded z-10">
              -{{ $product->discount }}%
            </div>

            {{-- Hình ảnh --}}
            <div class="overflow-hidden rounded-t-xl bg-gray-50">
              <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                   class="w-full h-48 object-contain group-hover:scale-105 transition-transform duration-300">
            </div>

            {{-- Thông tin --}}
            <div class="p-4 text-center">
              <h3 class="text-base font-semibold text-gray-800 mb-1 truncate">{{ $product->name }}</h3>
              <p class="text-sm text-gray-500 mb-2">{{ $product->brand }} | {{ $product->category }}</p>
              <div class="flex justify-center items-center space-x-2">
                <span class="text-red-600 font-bold text-lg">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                <span class="line-through text-gray-400 text-sm">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
              </div>
              <a href="{{ route('product.details', ['id' => $product->id]) }}"
                 class="mt-4 inline-block bg-gradient-to-r from-orange-500 to-red-500 text-white text-sm font-semibold px-5 py-2 rounded-full shadow hover:brightness-110 transition-all">
                Mua ngay
              </a>
            </div>
          </div>
        @empty
          <p class="col-span-full text-center text-gray-500 text-lg py-8">
            Không có sản phẩm nào giảm giá từ 15% trở lên.
          </p>
        @endforelse
      @endif
    </div>
  </div>
</section>



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
