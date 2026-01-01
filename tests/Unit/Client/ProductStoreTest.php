<?php

namespace Tests\Unit\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Backend\ProductController;
use Illuminate\Validation\ValidationException;

class ProductStoreTest extends TestCase
{
    protected $user;
    protected $controller;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = $this->app->make(ProductController::class);
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    protected function tearDown(): void
    {
        if ($this->product) {
            $this->product->delete();
            $this->product = null;
        }

        if ($this->user) {
            $this->user->delete();
            $this->user = null;
        }

        parent::tearDown();
    }

    // =========================
    // TC1: Validate fail
    // =========================
    public function test_store_product_validate_fail()
    {
        $request = Request::create('/products/store', 'POST', [
            'name' => '',
            'category' => '',
            'original_price' => 100000,
            'price' => 200000,
            'stock' => -5,
            'brand' => '',
            'sku' => '',
        ]);

        $this->expectException(ValidationException::class);

        $this->controller->store($request);
    }

    // =========================
    // TC2: Hợp lệ – không có ảnh
    // =========================
    public function test_store_product_without_image()
    {
        $request = Request::create('/products/store', 'POST', [
            'name' => 'Xe đạp thể thao',
            'category' => 'Xe đạp',
            'original_price' => 1000000,
            'price' => 900000,
            'stock' => 10,
            'brand' => 'Giant',
            'sku' => 'XD001',
        ]);

        $response = $this->controller->store($request);

        $this->assertTrue($response->isRedirect());

        $this->product = Product::where('sku', 'XD001')->first();

        $this->assertNotNull($this->product);
        $this->assertEquals('Xe đạp thể thao', $this->product->name);
        $this->assertTrue($this->product->image === null || $this->product->image === '');
    }

    // =========================
    // TC3: Hợp lệ – có ảnh
    // =========================
    public function test_store_product_with_image()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $request = Request::create('/products/store', 'POST', [
            'name' => 'Xe đạp địa hình',
            'category' => 'Xe đạp',
            'original_price' => 1500000,
            'price' => 1300000,
            'stock' => 5,
            'brand' => 'Trek',
            'sku' => 'XD002',
        ], [], [
            'image' => $file,
        ]);

        $response = $this->controller->store($request);

        $this->assertTrue($response->isRedirect());

        $this->product = Product::where('sku', 'XD002')->first();
        $this->assertNotNull($this->product);
        $this->assertNotEmpty($this->product->image);

        Storage::disk('public')
            ->assertExists('images/products/' . $file->hashName());
    }
}
