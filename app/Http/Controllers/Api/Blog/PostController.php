<?php

namespace App\Http\Controllers\Api\Blog;

use App\Models\BlogPost; // Додали модель

class PostController extends BaseController // Змінили Controller на BaseController
{
    public function index()
    {
        $items = BlogPost::all();

        return $items;
    }

    // ... інші методи залишаємо як є
}
