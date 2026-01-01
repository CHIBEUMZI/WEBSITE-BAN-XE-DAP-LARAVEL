<?php

namespace Tests\Unit\Client;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Client\MaintenanceClientController;

class MaintenanceStoreTest extends TestCase
{
    protected $user;
    protected $controller;

    protected $productSku = '6918068022060';
    protected $createdProductSku = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = $this->app->make(MaintenanceClientController::class);
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    protected function tearDown(): void
    {
        DB::table('maintenances')->whereIn('customer_name', [
            'Nguyễn Văn A',
            'Nguyễn Văn B',
        ])->delete();

        if ($this->createdProductSku) {
            DB::table('products')->where('sku', $this->createdProductSku)->delete();
            $this->createdProductSku = null;
        }

        if ($this->user) {
            $this->user->delete();
            $this->user = null;
        }

        parent::tearDown();
    }

    /** @test */
    public function test_store_maintenance_validate_fail()
    {
        $request = Request::create('/maintenance', 'POST', []);

        $this->expectException(ValidationException::class);

        $this->controller->store($request);
    }

    /** @test */
    public function test_store_maintenance_product_sku_not_exist()
    {
        DB::table('maintenances')->where([
            'customer_name' => 'Nguyễn Văn A',
            'phone' => '0909123456',
            'product_sku' => '0000000000000',
        ])->delete();

        $request = Request::create('/maintenance', 'POST', [
            'customer_name'     => 'Nguyễn Văn A',
            'phone'            => '0909123456',
            'product_sku'       => '0000000000000',
            'issue_description' => 'Xe hỏng phanh',
            'preferred_date'    => '2025-01-01',
            'address'           => 'Hồ Chí Minh',
        ]);

        $response = $this->controller->store($request);

        $this->assertTrue($response->isRedirect());

        $this->assertDatabaseMissing('maintenances', [
            'customer_name' => 'Nguyễn Văn A',
            'phone' => '0909123456',
            'product_sku' => '0000000000000',
        ]);
    }

    /** @test */
    public function test_store_maintenance_success_when_sku_exists()
    {
        DB::table('products')->where('sku', $this->productSku)->delete();

        DB::table('products')->insert([
            'name'           => 'Xe đạp thể thao',
            'sku'            => $this->productSku,
            'category'       => 'Xe đạp',
            'original_price' => 1200000,
            'price'          => 1000000,
            'stock'          => 10,
            'brand'          => 'Giant',
            'discount'       => 0,
            'description'    => 'Sản phẩm test',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $this->createdProductSku = $this->productSku;

        $request = Request::create('/maintenance', 'POST', [
            'customer_name'     => 'Nguyễn Văn B',
            'phone'            => '0987654321',
            'email'            => 'b@gmail.com',
            'product_sku'       => $this->productSku,
            'issue_description' => 'Xe bị kẹt xích',
            'preferred_date'    => '2025-01-05',
            'address'          => 'Đà Nẵng',
        ]);

        $response = $this->controller->store($request);

        $this->assertTrue($response->isRedirect());
        $this->assertDatabaseHas('maintenances', [
            'customer_name' => 'Nguyễn Văn B',
            'product_sku'   => $this->productSku,
        ]);
    }
}
