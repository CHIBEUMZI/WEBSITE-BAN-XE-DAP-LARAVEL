@extends('backend.dashboard.layout')
@section('content')
    <div class="search-bar">
        <input type="text" class="search-input" placeholder="🔍 Tìm kiếm...">
    </div>
    <div class="row mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="card p-4 text-white" style="background: linear-gradient(135deg, #198754, #28a745); border-radius: 16px;">
                <div>
                    <h6>💵 Doanh thu hôm nay</h6>
                    <h3>
                        @isset($todayRevenue)
                            {{ number_format($todayRevenue, 0, ',', '.') }} VNĐ
                        @else
                            Không có dữ liệu
                        @endisset
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-5 col-lg-3">
            <div class="card p-4 text-white" style="background: linear-gradient(135deg, #0d6efd, #0dcaf0); border-radius: 16px;">
                <div>
                    <h6>💵 Số lượng xe đã bán hôm nay</h6>
                    <h3 style="text-align: center">@isset($todayProduct)
                            {{ $todayProduct }}
                        @else
                            Đang cập nhật 
                        @endisset</h3>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-4">📊 Thống kê</h3>

    {{-- Biểu đồ tồn kho sản phẩm --}}
    <h5 class="mt-4">📦 Biểu đồ tồn kho sản phẩm</h5>
    @isset($inventory)
        <canvas id="inventoryChart" height="100"></canvas>
    @else
        <p>Không có dữ liệu tồn kho.</p>
    @endisset

    {{-- Biểu đồ phân loại sản phẩm --}}
    <h5 class="mt-5">🗂️ Phân loại sản phẩm</h5>
    @isset($categoryDistribution)
        <canvas id="categoryChart" class="mt-2"></canvas>
    @else
        <p>Không có dữ liệu phân loại.</p>
    @endisset

    {{-- Biểu đồ doanh thu theo tháng --}}
    <h5 class="mt-5">💰 Doanh thu theo tháng</h5>
    @isset($monthlyRevenue)
        <canvas id="revenueChart" height="100" class="mt-2"></canvas>
    @else
        <p>Không có dữ liệu doanh thu theo tháng.</p>
    @endisset
{{-- Scripts --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  @isset($monthlyRevenue)
    const monthlyRevenue = @json(array_values($monthlyRevenue));
    const monthLabels = @json(array_map(fn($m) => 'Tháng ' . $m, array_keys($monthlyRevenue)));
  @else
    const monthlyRevenue = [];
    const monthLabels = [];
  @endisset

  @isset($inventory)
    const inventoryLabels = @json(array_keys($inventory));
    const inventoryData = @json(array_values($inventory));
  @else
    const inventoryLabels = [];
    const inventoryData = [];
  @endisset

  @isset($categoryDistribution)
    const categoryLabels = @json(array_keys($categoryDistribution));
    const categoryData = @json(array_values($categoryDistribution));
  @else
    const categoryLabels = [];
    const categoryData = [];
  @endisset

  // Biểu đồ tồn kho
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

  // Biểu đồ phân loại sản phẩm
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

  // Biểu đồ doanh thu theo tháng
  if (monthlyRevenue.length > 0) {
    new Chart(document.getElementById('revenueChart'), {
      type: 'line',
      data: {
        labels: monthLabels,
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: monthlyRevenue,
          borderColor: '#198754',
          backgroundColor: 'rgba(25, 135, 84, 0.2)',
          fill: true,
          tension: 0.3
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