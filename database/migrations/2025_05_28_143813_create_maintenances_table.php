<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Liên kết đến bảng users
            $table->string('customer_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_sku');
            $table->text('issue_description');
            $table->date('preferred_date')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['Đang xử lý', 'Hoàn thành', 'Hủy'])->default('Đang xử lý');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null'); // Liên kết đến bảng employees
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
}

