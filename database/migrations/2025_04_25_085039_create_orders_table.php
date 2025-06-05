<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // người đặt hàng
            $table->dateTime('order_date')->default(now()); // ngày đặt hàng
            $table->decimal('total_amount', 10, 2); // tổng tiền đơn hàng
            $table->string('payment_method')->default('cash'); // phương thức thanh toán
            $table->string('payment_status')->default('unpaid'); // trạng thái thanh toán
            $table->string('status')->default('pending'); // trạng thái đơn hàng
            $table->text('shipping_address'); // địa chỉ giao hàng
            $table->decimal('shipping_fee', 10, 2)->default(0); // phí vận chuyển
            $table->text('note')->nullable(); // ghi chú (tùy chọn)
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

