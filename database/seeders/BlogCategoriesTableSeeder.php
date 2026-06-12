<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('blog_categories')->insert([
            ['id' => 1, 'title' => 'Перша категорія', 'slug' => 'persha-kategoriya', 'parent_id' => 0],
            ['id' => 2, 'title' => 'Друга категорія', 'slug' => 'druga-kategoriya', 'parent_id' => 1],
        ]);
    }
}
