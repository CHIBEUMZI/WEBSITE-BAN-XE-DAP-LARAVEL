<?php

namespace Tests\Unit\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ViewProductTest extends TestCase
{
    use WithoutMiddleware; // ⭐ QUAN TRỌNG

    /**
     * C1 – Đăng nhập + sản phẩm tồn tại
     */
    public function test_C1_view_product_detail_success()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/details/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }


    /**
     * C2 – Sản phẩm không tồn tại
     */
    public function test_C3_product_not_found()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/details/999999');

        $response->assertStatus(404);
    }
}
