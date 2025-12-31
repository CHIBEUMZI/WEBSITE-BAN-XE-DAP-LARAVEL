<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductStoreTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    // =========================
    // TC1: Validate fail
    // =========================
    public function test_store_product_validate_fail()
    {
        $response = $this->post(route('products.store'), [
            'name' => '',
            'category' => '',
            'original_price' => 100000,
            'price' => 200000,
            'stock' => -5,
            'brand' => '',
            'sku' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'name',
            'category',
            'stock',
            'brand',
            'sku',
        ]);

        $this->assertDatabaseCount('products', 0);
    }

    // =========================
    // TC3: Dữ liệu hợp lệ + không có ảnh
    // =========================
    public function test_store_product_without_image()
    {
        $response = $this->post(route('products.store'), [
            'name' => 'Xe đạp thể thao',
            'category' => 'Xe đạp',
            'original_price' => 1000000,
            'price' => 900000,
            'stock' => 10,
            'brand' => 'Giant',
            'sku' => 'XD001',
        ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Xe đạp thể thao',
            'image' => null,
        ]);
    }

    // =========================
    // TC2: Dữ liệu hợp lệ + có ảnh
    // =========================
    public function test_store_product_with_image()
    {
        Storage::fake('public');

        // KHÔNG dùng fake()->image()
        $file = UploadedFile::fake()->create(
            'product.jpg',
            500,
            'image/jpeg'
        );

        $response = $this->post(route('products.store'), [
            'name' => 'Xe đạp địa hình',
            'category' => 'Xe đạp',
            'original_price' => 1500000,
            'price' => 1300000,
            'stock' => 5,
            'brand' => 'Trek',
            'sku' => 'XD002',
            'image' => $file,
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertTrue(
            Storage::disk('public')->exists(
                'images/products/' . $file->hashName()
            )
        );

        $this->assertDatabaseHas('products', [
            'name' => 'Xe đạp địa hình',
        ]);
    }

    // // =========================
    // // TC4: Price = original_price
    // // =========================
    // public function test_store_product_price_equal_original_price()
    // {
    //     $response = $this->post(route('products.store'), [
    //         'name' => 'Xe đạp giá gốc',
    //         'category' => 'Xe đạp',
    //         'original_price' => 500000,
    //         'price' => 500000,
    //         'stock' => 3,
    //         'brand' => 'Asama',
    //         'sku' => 'XD010',
    //     ]);

    //     $response->assertRedirect(route('products.index'));

    //     $this->assertDatabaseHas('products', [
    //         'name' => 'Xe đạp giá gốc',
    //         'price' => 500000,
    //     ]);
    // }

    // // =========================
    // // TC5: Valid discount
    // // =========================
    // public function test_store_product_with_valid_discount()
    // {
    //     $response = $this->post(route('products.store'), [
    //         'name' => 'Xe đạp giảm giá',
    //         'category' => 'Xe đạp',
    //         'original_price' => 800000,
    //         'price' => 700000,
    //         'stock' => 5,
    //         'brand' => 'Thống Nhất',
    //         'sku' => 'XD020',
    //         'discount' => 20,
    //     ]);

    //     $response->assertRedirect(route('products.index'));

    //     $this->assertDatabaseHas('products', [
    //         'name' => 'Xe đạp giảm giá',
    //         'discount' => 20,
    //     ]);
    // }

    // // =========================
    // // TC6: Invalid discount
    // // =========================
    // public function test_store_product_discount_invalid()
    // {
    //     $response = $this->post(route('products.store'), [
    //         'name' => 'Xe đạp lỗi discount',
    //         'category' => 'Xe đạp',
    //         'original_price' => 800000,
    //         'price' => 700000,
    //         'stock' => 5,
    //         'brand' => 'Martin',
    //         'sku' => 'XD021',
    //         'discount' => 150,
    //     ]);

    //     $response->assertStatus(302);
    //     $response->assertSessionHasErrors(['discount']);

    //     $this->assertDatabaseCount('products', 0);
    // }
}
