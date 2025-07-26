@extends('backend.dashboard.layout')
@section('content')

<!-- Bộ lọc -->
<form method="GET" action="{{ route('dashboard.index') }}" class="mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Từ ngày:</label>
            <input type="date" name="from" value="{{ request('from') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Đến ngày:</label>
            <input type="date" name="to" value="{{ request('to') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Năm:</label>
            <input type="number" name="year" value="{{ request('year') }}" placeholder="VD: 2025"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="flex items-end">
            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                Lọc
            </button>
        </div>
    </div>
</form>

<!-- Thống kê nhanh -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-r from-green-600 to-green-400 text-white p-6 rounded-2xl shadow-md">
        <h6 class="text-sm">💵 Doanh thu hôm nay</h6>
        <h3 class="text-2xl font-bold mt-2">{{ number_format($todayRevenue ?? 0, 0, ',', '.') }} VNĐ</h3>
    </div>

    <div class="bg-gradient-to-r from-blue-600 to-blue-300 text-white p-6 rounded-2xl shadow-md">
        <h6 class="text-sm">🚴‍♂️ Số lượng xe đã bán hôm nay</h6>
        <h3 class="text-2xl font-bold text-center mt-2">{{ $todayProduct ?? 'Đang cập nhật' }}</h3>
    </div>
</div>

<!-- Biểu đồ -->
<h3 class="text-xl font-bold text-gray-800 mb-6">📊 Thống kê</h3>

<h5 class="text-lg font-semibold text-gray-700 mt-8 mb-3">💰 Doanh thu theo ngày ({{ $from }} - {{ $to }})</h5>
@if (!empty($dailyRevenue))
    <div class="bg-white p-4 rounded-xl shadow-md">
        <canvas id="dailyChart" height="100"></canvas>
    </div>
@else
    <p class="text-gray-500 italic">Không có dữ liệu doanh thu theo ngày.</p>
@endif

<h5 class="text-lg font-semibold text-gray-700 mt-8 mb-3">📅 Doanh thu theo tháng theo từng năm ({{ $year }})</h5>
@if (!empty($monthlyRevenue))
    <div class="bg-white p-4 rounded-xl shadow-md">
        <canvas id="revenueChart" height="100"></canvas>
    </div>
@else
    <p class="text-gray-500 italic">Không có dữ liệu doanh thu theo tháng.</p>
@endif

<h5 class="text-lg font-semibold text-gray-700 mt-8 mb-3">📦 Biểu đồ tồn kho sản phẩm</h5>
@if (!empty($inventory))
    <div class="bg-white p-4 rounded-xl shadow-md">
        <canvas id="inventoryChart" height="100"></canvas>
    </div>
@else
    <p class="text-gray-500 italic">Không có dữ liệu tồn kho.</p>
@endif

<h5 class="text-lg font-semibold text-gray-700 mt-8 mb-3">🗂️ Phân loại sản phẩm</h5>
@if (!empty($categoryDistribution))
    <div class="bg-white p-4 rounded-xl shadow-md max-w-xl mx-auto">
        <canvas id="categoryChart" height="400"></canvas>
    </div>
@else
    <p class="text-gray-500 italic">Không có dữ liệu phân loại.</p>
@endif

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const inventoryLabels = @json(array_keys($inventory ?? []));
    const inventoryData = @json(array_values($inventory ?? []));

    const categoryLabels = @json(array_keys($categoryDistribution ?? []));
    const categoryData = @json(array_values($categoryDistribution ?? []));

    const dailyLabels = @json(array_keys($dailyRevenue ?? []));
    const dailyData = @json(array_values($dailyRevenue ?? []));

    const monthLabels = @json(array_map(fn($m) => 'Tháng ' . $m, array_keys($monthlyRevenue ?? [])));
    const monthlyData = @json(array_values($monthlyRevenue ?? []));

    if (inventoryData.length > 0) {
        new Chart(document.getElementById('inventoryChart'), {
            type: 'bar',
            data: {
                labels: inventoryLabels,
                datasets: [{
                    label: 'Số lượng tồn',
                    data: inventoryData,
                    backgroundColor: '#0d6efd'
                }]
            }
        });
    }

    if (categoryData.length > 0) {
        new Chart(document.getElementById('categoryChart'), {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1']
                }]
            }
        });
    }

    if (dailyData.length > 0) {
        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Doanh thu theo ngày',
                    data: dailyData,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            }
        });
    }

    if (monthlyData.length > 0) {
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Doanh thu theo tháng',
                    data: monthlyData,
                    backgroundColor: '#198754'
                }]
            }
        });
    }
</script>
@endsection
