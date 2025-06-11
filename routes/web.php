<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\MaintenanceController;
use App\Http\Controllers\Client\ProductClientController;
use App\Http\Controllers\Client\UserClientController;
use App\Http\Controllers\Client\CartClientController;
use App\Http\Controllers\Client\OrderClientController;
use App\Http\Controllers\Client\MaintenanceClientController;


use App\Models\Product;
  
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// ROTE BACKEND 
Route::get('dashboard/index', [DashboardController::class,'index'])->name
('dashboard.index')->middleware('logout');

// Admin
Route::middleware(['auth','logout'])->group(function () {
    Route::get('dashboard/index', [DashboardController::class,'index'])->name('dashboard.index');

    // USER
    Route::get('user/index', [UserController::class,'index'])->name('user.index');
    Route::post('/users/{id}/change-role', [UserController::class, 'changeRole'])->name('users.changeRole');


    // MAINTENANCEMAINTENANCE
    Route::get('/maintenances/index',[MaintenanceController::class, 'index'])->name('AdminMaintenance.index');
    Route::put('/maintenance/{id}/assign',[MaintenanceController::class, 'assignEmployee'])->name('AdminMaintenance.assignEmployee');
    Route::post('/maintenance/{id}/comfirm',[MaintenanceController::class, 'comfirm'])->name('maintenance.comfirm');


    // PRODUCT
    Route::get('products/index', [ProductController::class,'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // EMPLOYEE
    Route::get('employees/index', [EmployeeController::class,'index'])->name('employees.index');
    Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('employees/store', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    Route::get('orders/index', [OrderController::class,'index'])->name('orders.index');
    Route::post('orders/{order}/confirm-cancel', [OrderController::class, 'confirmCancel'])->name('orders.confirm_cancel');

});

Route::get('login', [AuthController::class,'index'])->name
('login')->middleware('guest');
Route::post('login', [AuthController::class,'login'])->name('auth.login');
Route::get('logout', [AuthController::class,'logout'])->name('auth.logout');
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/home', [ProductClientController::class, 'index'])->name('client.home')->middleware('auth');



// CLIENT PRODUCT 
Route::get('/find', [ProductClientController::class, 'find'])->name('client.products.index');
Route::get('/details/{id}', [ProductClientController::class, 'details'])->name('product.details');
Route::get('/buy/{id}', [ProductClientController::class, 'buy'])->name('product.buy');
Route::post('/buy/{id}', [ProductClientController::class, 'processBuy'])->name('product.processBuy');



// CLIENT CART
Route::post('/cart/add/{id}', [CartCLientController::class, 'add'])->name('cart.add');
// Hiển thị giỏ hàng
Route::get('/cart', [CartClientController::class, 'index'])->name('cart.index');

// Cập nhật số lượng
Route::post('/cart/update', [CartClientController::class, 'update'])->name('cart.update');

// Xóa sản phẩm khỏi giỏ
Route::get('/cart/remove/{id}', [CartClientController::class, 'remove'])->name('cart.remove');

// Xử lí mua hàng
Route::get('/buy-cart', [CartClientController::class, 'buyCart'])->name('cart.buy');
Route::post('/process-buy-cart', [CartClientController::class, 'processBuyCart'])->name('product.processBuyCart');

// Thông tin khách hàng
Route::get('/profile',[UserClientController::class,'show'])->name('profile.show');
Route::put('/profile-update/{id}',[UserClientController::class,'update'])->name('profile.update');

// Đơn hàng
Route::get('/purchase-order', [OrderClientController::class, 'index'])->name('client.orders');
Route::get('/purchase-orders/{id}', [OrderClientController::class, 'show'])->name('client.orders.show');
Route::patch('/purchase-orders/{order}/cancel',[OrderClientController::class, 'cancel'])->name('client.orders.cancel');

// Bảo trì 
Route::get('/maintenance', [MaintenanceClientController::class,'index'])->name('maintenances.index');
Route::post('/maintenance', [MaintenanceClientController::class,'store'])->name('maintenances.store');
Route::get('/maintenance/show', [MaintenanceClientController::class,'show'])->name('show.progess');
Route::get('/maintenance/{id}/details', [MaintenanceClientController::class,'details'])->name('show.details');

// Đăng ký
Route::view('/register', 'backend.auth.register')->name('register.form');
Route::post('/register', [UserClientController::class, 'register'])->name('user.register');


// Momo callback khi thanh toán xong
Route::get('/momo/success', [ProductClientController::class, 'momoSuccess'])->name('momo.success');
// IPN từ MoMo (notify server về trạng thái)
Route::post('/momo-ipn', [ProductClientController::class, 'momoIpn'])->name('momo.ipn');

// MoMo Của giỏ hàng
Route::get('/momo/cart-success', [CartClientController::class, 'momoSuccess'])->name('momo.cart.success');
Route::post('/momo/cart-ipn', [CartClientController::class, 'momoCartIpn'])->name('momo.cart.ipn');