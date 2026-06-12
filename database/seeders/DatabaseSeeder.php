<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Додаємо дані напряму для тесту
        DB::table('blog_categories')->insert([
            ['id' => 1, 'title' => 'Тестова категорія 1', 'slug' => 'test-1', 'parent_id' => 0],
            ['id' => 2, 'title' => 'Тестова категорія 2', 'slug' => 'test-2', 'parent_id' => 1],
        ]);
    }
}
