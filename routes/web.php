<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/simple-count', function () {
    ray()->showQueries();

    return [
        'Electronics' => Product::query()->whereHas('categories', function ($query) {
            $query->where('categories.name', 'Electronics');
        })->count(),
        'Health & Beauty' => Product::query()->whereHas('categories', function ($query) {
            $query->where('categories.name', 'Health & Beauty');
        })->count(),
        'Home & Kitchen' => Product::query()->whereHas('categories', function ($query) {
            $query->where('categories.name', 'Home & Kitchen');
        })->count(),
    ];
});

Route::get('/filter', function () {
    ray()->showQueries();

    $products = Product::query()->with('categories')->get();

    return [
        'Electronics' => $products->filter(fn ($product) => $product->categories->contains('name', 'Electronics'))->count(),
        'Health & Beauty' => $products->filter(fn ($product) => $product->categories->contains('name', 'Health & Beauty'))->count(),
        'Home & Kitchen' => $products->filter(fn ($product) => $product->categories->contains('name', 'Home & Kitchen'))->count(),
    ];
});

Route::get('/categories', function () {
    ray()->showQueries();

    $categories = Category::query()->withCount('products')->get()->keyBy('name');

    return [
        'Electronics' => $categories['Electronics']->products_count,
        'Health & Beauty' => $categories['Health & Beauty']->products_count,
        'Home & Kitchen' => $categories['Home & Kitchen']->products_count,
    ];
});

Route::get('/raw', function () {
    ray()->showQueries();

    $categories = DB::select("SELECT
         COUNT(CASE WHEN category_id = 1 THEN 1 END) AS electronics,
         COUNT(CASE WHEN category_id = 2 THEN 1 END) AS health,
         COUNT(CASE WHEN category_id = 3 THEN 1 END) AS kitchen
    FROM category_product")[0];

    return [
        'Electronics' => $categories->electronics,
        'Health & Beauty' => $categories->health,
        'Home & Kitchen' => $categories->kitchen,
    ];
});
