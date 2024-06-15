<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Random\RandomException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws RandomException
     */
    public function run(): void
    {
        $categories = ['Electronics', 'Health & Beauty', 'Home & Kitchen'];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }

        $products = [
            ['name' => 'EcoBreeze Air Purifier'],
            ['name' => 'SunGlow Face Cream'],
            ['name' => 'AquaSonic Toothbrush'],
            ['name' => 'FlexiGrip Yoga Mat'],
            ['name' => 'NanoTech Phone Case'],
            ['name' => 'PureWave Water Bottle',],
            ['name' => 'VelvetTouch Hand Cream'],
            ['name' => 'SkyLoom Bed Sheets'],
            ['name' => 'PowerPulse Fitness Tracker'],
            ['name' => 'NatureBlend Protein Powder'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $categories = Category::all();

        Product::query()->lazy()->each(function ($product) use ($categories) {
            $randomCategories = $categories->random(random_int(0, 3))->pluck('id');
            $product->categories()->attach($randomCategories);
        });

    }
}
