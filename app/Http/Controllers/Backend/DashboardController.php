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
        ->selectRaw('MONTH(order_date) as month, SUM(total_amount) as total')
        ->whereYear('order_date', $year)
        ->groupByRaw('MONTH(order_date)')
        ->pluck('total', 'month')
        ->toArray();

    // Doanh thu theo ngày trong khoảng
    $dailyRevenue = [];
    if ($from && $to) {
        $start = Carbon::parse($from)->startOfDay();
        $end = Carbon::parse($to)->endOfDay();       

        $dailyRevenue = DB::table('orders')
            ->selectRaw('DATE(order_date) as day, SUM(total_amount) as total')
            ->whereBetween('order_date', [$start, $end])
            ->groupByRaw('DATE(order_date)')
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
        ->whereDate('order_date', now()->toDateString())
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