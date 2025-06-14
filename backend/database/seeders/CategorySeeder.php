<?php

namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'خانواده و سبک زندگی'],
            ['name' => 'کسب و کار و مارکتینگ'],
            ['name' => 'تکنولوژی و بازی'],
            ['name' => 'آموزشی،دانش آموزی'],
            ['name' => 'مد و استایل'],
            ['name' => 'هنر و سینما'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
