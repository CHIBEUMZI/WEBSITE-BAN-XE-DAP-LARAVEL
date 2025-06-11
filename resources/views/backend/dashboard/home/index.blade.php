@extends('backend.dashboard.layout')
@section('content')

<form method="GET" action="{{ route('dashboard.index') }}" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <label>Từ ngày:</label>
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-md-3">
            <label>Đến ngày:</label>
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col-md-3">
            <label>Năm:</label>
            <input type="number" name="year" class="form-control" value="{{ request('year') }}" placeholder="VD: 2025">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">Lọc</button>
        </div>
    </div>
</form>

<div class="row mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="card p-4 text-white" style="background: linear-gradient(135deg, #198754, #28a745); border-radius: 16px;">
            <div>
                <h6>💵 Doanh thu hôm nay</h6>
                <h3>{{ number_format($todayRevenue ?? 0, 0, ',', '.') }} VNĐ</h3>
            </div>
        </div>
    </div>

    <div class="col-md-5 col-lg-3">
        <div class="card p-4 text-white" style="background: linear-gradient(135deg, #0d6efd, #0dcaf0); border-radius: 16px;">
            <div>
                <h6>💵 Số lượng xe đã bán hôm nay</h6>
                <h3 style="text-align: center">{{ $todayProduct ?? 'Đang cập nhật' }}</h3>
            </div>
        </div>
    </div>
</div>

<h3 class="mb-4">📊 Thống kê</h3>
<h5 class="mt-5 mb-4">💰 Doanh thu theo ngày ({{ $from }} - {{ $to }})</h5>
@if (!empty($dailyRevenue))
    <canvas id="dailyChart" height="100"></canvas>
@else
    <p>Không có dữ liệu doanh thu theo ngày.</p>
@endif

<h5 class="mt-5 mb-4">📅 Doanh thu theo tháng theo từng năm({{ $year }})</h5>
@if (!empty($monthlyRevenue))
    <canvas id="revenueChart" height="100"></canvas>
@else
    <p>Không có dữ liệu doanh thu theo tháng.</p>
@endif
<h5 class="mt-5 mb-4">📦 Biểu đồ tồn kho sản phẩm</h5>
@if (!empty($inventory))
    <canvas id="inventoryChart" height="100"></canvas>
@else
    <p>Không có dữ liệu tồn kho.</p>
@endif

<h5 class="mt-5">🗂️ Phân loại sản phẩm</h5>
@if (!empty($categoryDistribution))
    <canvas id="categoryChart" class="mt-2"></canvas>
@else
    <p>Không có dữ liệu phân loại.</p>
@endif


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
<style>
    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    canvas {
      background-color: #fff;
      border-radius: 16px;
      padding: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    #categoryChart {
      max-width: 500px;
      height: 500px !important;
      margin: 0 auto;
    }
    .search-bar {
      margin-bottom: 30px;
    }
    .search-input {
      width: 100%;
      max-width: 400px;
      padding: 8px 16px;
      border: 1px solid #ced4da;
      border-radius: 30px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection