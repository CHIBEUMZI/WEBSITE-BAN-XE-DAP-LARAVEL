<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function __construct()
    {
        
    }
    public function index(){
        // $template = 'backend.dashboard.home.index' ;

         $monthlyRevenue = DB::table('orders')
         ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
         ->groupByRaw('MONTH(created_at)')
         ->pluck('total', 'month')
         ->toArray();
        
     // Tồn kho theo loại xe
     $inventory = DB::table('products')
         ->select('category', DB::raw('SUM(stock) as quantity'))
         ->groupBy('category')
         ->pluck('quantity', 'category')
         ->toArray();

     // Tỷ lệ loại sản phẩm
     $categoryDistribution = DB::table('products')
         ->select('category', DB::raw('COUNT(*) as count'))
         ->groupBy('category')
         ->pluck('count', 'category')
         ->toArray();

         $todayRevenue = DB::table('orders')
         ->whereDate('created_at', now()->toDateString())  // Lọc theo ngày hôm nay
         ->sum('total_amount');

         $todayProduct = DB::table('order_items')
         ->whereDate('created_at', now()->toDateString())  // Lọc theo ngày hôm nay
         ->sum('quantity');

     return view('backend.dashboard.home.index', compact('monthlyRevenue', 'inventory', 'categoryDistribution','todayRevenue','todayProduct'));
    }

   
}

?>