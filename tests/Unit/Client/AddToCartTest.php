<?php

namespace Tests\Unit\Client;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Client\CartClientController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddToCartTest extends TestCase
{
    protected $user;
    protected $product;
    protected $cartItem;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Controller
        $this->controller = $this->app->make(CartClientController::class);

        // Tạo user mặc định cho test
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    protected function tearDown(): void
    {
        // Xoá cart item nếu có
        if ($this->cartItem) {
            $this->cartItem->delete();
            $this->cartItem = null;
        }

        // Xoá product nếu có
        if ($this->product) {
            $this->product->delete();
            $this->product = null;
        }

        // Xoá user
        if ($this->user) {
            $this->user->delete();
            $this->user = null;
        }

        parent::tearDown();
    }

    /**
     * TC1 – C₁: 1 → 12
     * Product không tồn tại → findOrFail() ném exception.
     */
    public function test_tc1_product_not_found()
    {
        $request = Request::create('/cart/add/9999', 'POST', [
            'quantity' => 1,
        ]);

        $this->expectException(ModelNotFoundException::class);

        $this->controller->add($request, 9999);
    }

    /**
     * TC2 – C₂: 1 → 2 → 3 → 4 → 12
     * quantity < 1 → lỗi số lượng không hợp lệ.
     */
    public function test_tc2_quantity_less_than_one()
    {
        $this->product = Product::factory()->create([
            'stock'          => 10,
            'price'          => 2000000,
            'original_price' => 2500000,
        ]);

        $request = Request::create("/cart/add/{$this->product->id}", 'POST', [
            'quantity' => 0,
        ]);

        $response = $this->controller->add($request, $this->product->id);

        $this->assertTrue($response->isRedirect());
        $this->assertEquals('Số lượng phải lớn hơn 0.', session('error'));

        $this->assertDatabaseMissing('carts', [
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }

    /**
     * TC3 – C₃: 1 → 2 → 3 → 5 → 6(F) → 7 → 12
     * Vượt quá số lượng tồn kho → báo lỗi tồn kho.
     */
    public function test_tc3_over_stock_error()
    {
        $this->product = Product::factory()->create([
            'stock'          => 5,
            'price'          => 2000000,
            'original_price' => 2500000,
        ]);

        // Tạo cart hiện tại có 3 sản phẩm
        $this->cartItem = Cart::create([
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
            'quantity'   => 3,
        ]);

        $request = Request::create("/cart/add/{$this->product->id}", 'POST', [
            'quantity' => 3,
        ]);

        $response = $this->controller->add($request, $this->product->id);

        $this->assertTrue($response->isRedirect());
        $this->assertStringContainsString(
            'Số lượng trong kho không đủ',
            session('error')
        );

        // Kiểm tra số lượng không thay đổi
        $this->assertDatabaseHas('carts', [
            'id'       => $this->cartItem->id,
            'quantity' => 3,
        ]);
    }

    /**
     * TC4 – C₄: 1 → 2 → 3 → 5 → 6(T) → 8(F) → 10 → 11 → 12
     * Chưa có cartItem, đủ tồn kho → tạo mới.
     */
    public function test_tc4_create_new_cart_item()
    {
        $this->product = Product::factory()->create([
            'stock'          => 10,
            'price'          => 2000000,
            'original_price' => 2500000,
        ]);

        $request = Request::create("/cart/add/{$this->product->id}", 'POST', [
            'quantity' => 2,
        ]);

        $response = $this->controller->add($request, $this->product->id);

        $this->assertTrue($response->isRedirect());
        $this->assertEquals('Đã thêm sản phẩm vào giỏ hàng!', session('success'));

        $this->cartItem = Cart::where('user_id', $this->user->id)
                              ->where('product_id', $this->product->id)
                              ->first();

        $this->assertNotNull($this->cartItem);
        $this->assertEquals(2, $this->cartItem->quantity);
    }

    /**
     * TC5 – C₅: 1 → 2 → 3 → 5 → 6(T) → 8(T) → 9 → 11 → 12
     * Đã có cartItem và còn đủ tồn kho → cập nhật số lượng.
     */
    public function test_tc5_update_cart_item()
    {
        $this->product = Product::factory()->create([
            'stock'          => 10,
            'price'          => 2000000,
            'original_price' => 2500000,
        ]);

        $this->cartItem = Cart::create([
            'user_id'    => $this->user->id,
            'product_id' => $this->product->id,
            'quantity'   => 3,
        ]);

        $request = Request::create("/cart/add/{$this->product->id}", 'POST', [
            'quantity' => 2,
        ]);

        $response = $this->controller->add($request, $this->product->id);

        $this->assertTrue($response->isRedirect());
        $this->assertEquals('Đã thêm sản phẩm vào giỏ hàng!', session('success'));

        $this->cartItem->refresh();

        $this->assertEquals(5, $this->cartItem->quantity);
    }
}
