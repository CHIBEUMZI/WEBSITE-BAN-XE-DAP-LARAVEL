<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MaintenanceStoreTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        // Tắt toàn bộ middleware
        $this->withoutMiddleware();

        // Giả lập user đăng nhập
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /** @test */
    public function store_maintenance_validate_fail()
    {
        $response = $this->post(route('maintenances.store'), []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'customer_name',
            'phone',
            'product_sku',
            'issue_description',
            'preferred_date',
            'address',
        ]);

        $this->assertDatabaseCount('maintenances', 0);
    }

    /** @test */
    public function store_maintenance_product_sku_not_exist()
    {
        $response = $this->post(route('maintenances.store'), [
            'customer_name' => 'Nguyễn Văn A',
            'phone' => '0909123456',
            'product_sku' => '0000000000000',
            'issue_description' => 'Xe hỏng phanh',
            'preferred_date' => '2025-01-01',
            'address' => 'Hồ Chí Minh',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['product_sku']);

        $this->assertDatabaseCount('maintenances', 0);
    }

    /** @test */
    public function store_maintenance_success_when_sku_exists()
    {
        // ✅ Tạo product ĐẦY ĐỦ cột NOT NULL
        DB::table('products')->insert([
            'name' => 'Xe đạp thể thao',
            'sku' => '6918068022060',
            'category' => 'Xe đạp',
            'original_price' => 1200000,
            'price' => 1000000,
            'stock' => 10,
            'brand' => 'Giant',
            'discount' => 0,
            'description' => 'Sản phẩm test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post(route('maintenances.store'), [
            'customer_name' => 'Nguyễn Văn B',
            'phone' => '0987654321',
            'email' => 'b@gmail.com',
            'product_sku' => '6918068022060',
            'issue_description' => 'Xe bị kẹt xích',
            'preferred_date' => '2025-01-05',
            'address' => 'Đà Nẵng',
        ]);

        $response->assertRedirect(route('client.home'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('maintenances', [
            'customer_name' => 'Nguyễn Văn B',
            'product_sku' => '6918068022060',
            'status' => 'Đang xử lý',
        ]);
    }
}
