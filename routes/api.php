<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\PostController;
use App\Http\Controllers\Api\Blog\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Blog\Admin\PostController as AdminPostController;

Route::group(['prefix' => 'blog'], function () {
    Route::apiResource('posts', PostController::class)->names('blog.posts');
});

Route::group(['prefix' => 'admin/blog'], function () {
    $methods = ['index', 'store', 'update'];

    // Маршрути категорій
    Route::apiResource('categories', AdminCategoryController::class)
        ->only($methods)
        ->names('blog.admin.categories');

    // Маршрути статей (постів)
    Route::apiResource('posts', AdminPostController::class)
        ->except(['show'])
        ->names('blog.admin.posts');
});
