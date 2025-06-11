<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        
    }
    public function index(Request $request) {
    $from = $request->input('from');
    $to = $request->input('to');
    $year = $request->input('year', now()->year); // mặc định là năm hiện tại

    // Doanh thu theo tháng trong năm
    $monthlyRevenue = DB::table('orders')
        ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
        ->whereYear('created_at', $year)
        ->groupByRaw('MONTH(created_at)')
        ->pluck('total', 'month')
        ->toArray();

    // Doanh thu theo ngày trong khoảng
    $dailyRevenue = [];
    if ($from && $to) {
        $start = Carbon::parse($from)->startOfDay();
        $end = Carbon::parse($to)->endOfDay();       

        $dailyRevenue = DB::table('orders')
            ->selectRaw('DATE(created_at) as day, SUM(total_amount) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'day')
            ->toArray();
    }

    $inventory = DB::table('products')
        ->select('category', DB::raw('SUM(stock) as quantity'))
        ->groupBy('category')
        ->pluck('quantity', 'category')
        ->toArray();

    $categoryDistribution = DB::table('products')
        ->select('category', DB::raw('COUNT(*) as count'))
        ->groupBy('category')
        ->pluck('count', 'category')
        ->toArray();

    $todayRevenue = DB::table('orders')
        ->whereDate('created_at', now()->toDateString())
        ->sum('total_amount');

    $todayProduct = DB::table('order_items')
        ->whereDate('created_at', now()->toDateString())
        ->sum('quantity');

    return view('backend.dashboard.home.index', compact(
        'monthlyRevenue', 'inventory', 'categoryDistribution', 
        'todayRevenue', 'todayProduct', 'dailyRevenue', 
        'from', 'to', 'year'
    ));
}


   
}

?>