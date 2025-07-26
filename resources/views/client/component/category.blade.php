<div class="flex flex-wrap justify-center gap-3 my-4">
    @php
        $categories = [
            ['name' => 'Xe Đạp Nữ', 'value' => 'Xe đạp nữ', 'image' => 'xe-dap-nu.jpg'],
            ['name' => 'Xe Đạp Địa Hình', 'value' => 'Xe đạp địa hình', 'image' => 'xe-dap-dia-hinh.jpg'],
            ['name' => 'Xe Đạp Đua', 'value' => 'Xe đạp đua', 'image' => 'xe-dap-dua.jpg'],
            ['name' => 'Xe Đạp Phố', 'value' => 'Xe đạp thể thao đường phố', 'image' => 'xe-dap-pho.jpg'],
            ['name' => 'Fixed Gear', 'value' => 'Xe đạp fixed gear', 'image' => 'fixed-gear.jpg'],
            ['name' => 'Xe Đạp Học Sinh', 'value' => 'Xe đạp trẻ em', 'image' => 'xe-dap-hoc-sinh.jpg'],
            ['name' => 'Xe Đạp Gấp', 'value' => 'Xe đạp gấp', 'image' => 'xe-dap-gap.jpg'],
            ['name' => 'Trợ Lực Điện', 'value' => 'Trợ lực điện', 'image' => 'tro-luc-dien.jpg'],
        ];
    @endphp

    @foreach ($categories as $category)
        @php
            $isActive = request('category') === $category['value'];
        @endphp

        <a href="{{ route('client.products.index', ['category' => $category['value']]) }}"
           class="group w-[130px] lg:w-[150px] text-center rounded border p-2
                  transition-all duration-300 hover:scale-105 hover:shadow-md
                  {{ $isActive ? 'border-red-500 ring-1 ring-blue-300' : 'border-gray-200 hover:border-red-500' }}">
            <div class="overflow-hidden">
                <img src="{{ asset('images/categories/' . $category['image']) }}"
                     alt="{{ $category['name'] }}"
                     class="w-full h-14 object-contain mx-auto mb-2 transition-transform duration-300 group-hover:scale-110">
            </div>
            <p class="text-[11px] font-medium text-gray-700 leading-tight">{{ $category['name'] }}</p>
        </a>
    @endforeach
</div>
