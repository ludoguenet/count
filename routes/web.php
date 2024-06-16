<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('simple-count', function () {
    ray()->showQueries();

    return [
      'Electronics' => Product::query()->whereHas('categories', function ($query) {
          $query->where('name', 'Electronics');
      })->count(),
        'Health' => Product::query()->whereHas('categories', function ($query) {
          $query->where('name', 'Health');
      })->count(),
        'Home' => Product::query()->whereHas('categories', function ($query) {
          $query->where('name', 'Home');
      })->count(),
    ];
});

Route::get('filter', function () {
    ray()->showQueries();

    $products = Product::with('categories')->get();

    return [
        'Electronics' => $products->filter(fn ($product) => $product->categories->contains('name', 'Electronics'))->count(),
        'Health' => $products->filter(fn ($product) => $product->categories->contains('name', 'Health'))->count(),
        'Home' => $products->filter(fn ($product) => $product->categories->contains('name', 'Home'))->count(),
    ];
});

Route::get('categories', function () {
    ray()->showQueries();

    $categories = Category::query()->withCount('products')->get()->keyBy('name');

    return [
      'Electronics' => $categories['Electronics']->products_count,
      'Health' => $categories['Health']->products_count,
      'Home' => $categories['Home']->products_count,
    ];
});

Route::get('raw', function () {
    ray()->showQueries();

    $db = DB::select("SELECT
        COUNT(CASE WHEN category_id = 1 THEN 1 END) AS electronics_count,
        COUNT(CASE WHEN category_id = 2 THEN 1 END) AS health_count,
        COUNT(CASE WHEN category_id = 3 THEN 1 END) AS home_count
    FROM category_product")[0];

    return [
        'Electronics' => $db->electronics_count,
        'Health' => $db->health_count,
        'Home' => $db->home_count,
    ];
});
