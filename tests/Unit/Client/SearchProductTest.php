<?php

namespace Tests\Unit\Client;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\Client\ProductClientController;
use App\Models\Product;

class SearchProductTest extends TestCase
{
    /**
     * TC1: Không search, không category
     */
    public function test_find_without_filters()
    {
        $request = Request::create('/find', 'GET');

        $controller = $this->app->make(ProductClientController::class);

        $response = $controller->find($request);
        $data = $response->getData();

        $productsFromController = $data['products']->pluck('id')->toArray();

        $expected = Product::paginate(4)->pluck('id')->toArray();

        $this->assertEquals($expected, $productsFromController);
    }

    /**
     * TC2: Có search, không category
     */
    public function test_find_with_search_only()
    {
        $search = 'xe';

        $request = Request::create('/find', 'GET', [
            'search' => $search
        ]);

        $controller = $this->app->make(ProductClientController::class);

        $response = $controller->find($request);
        $data = $response->getData();

        $productsFromController = $data['products']->pluck('id')->toArray();

        $expected = Product::where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('category', 'like', "%{$search}%");
                    })->paginate(4)
                      ->pluck('id')
                      ->toArray();

        $this->assertEquals($expected, $productsFromController);
    }

    /**
     * TC3: Không search, có category
     */
    public function test_find_with_category_only()
    {
        $category = 'Xe Đạp Địa Hình';

        $request = Request::create('/find', 'GET', [
            'category' => $category
        ]);

        $controller = $this->app->make(ProductClientController::class);

        $response = $controller->find($request);
        $data = $response->getData();

        $productsFromController = $data['products']->pluck('id')->toArray();

        $expected = Product::where('category', $category)
                    ->paginate(4)
                    ->pluck('id')
                    ->toArray();

        $this->assertEquals($expected, $productsFromController);
    }
}
