<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\PostController;
use App\Http\Controllers\Api\Blog\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Blog\Admin\PostController as AdminPostController;
use App\Http\Controllers\DiggingDeeperController; // Додайте цей імпорт

Route::group(['prefix' => 'blog'], function () {
    Route::apiResource('posts', PostController::class)->names('blog.posts');
});

Route::group(['prefix' => 'admin/blog'], function () {
    $methods = ['index', 'store', 'update'];

    Route::apiResource('categories', AdminCategoryController::class)
        ->only($methods)
        ->names('blog.admin.categories');

    Route::apiResource('posts', AdminPostController::class)
        ->except(['show'])
        ->names('blog.admin.posts');
});

// Виносимо DiggingDeeper окремо, щоб уникнути конфлікту namespace
Route::group(['prefix' => 'digging_deeper'], function () {
    Route::get('process-video', [DiggingDeeperController::class, 'processVideo'])
        ->name('digging_deeper.processVideo');

    Route::get('prepare-catalog', [DiggingDeeperController::class, 'prepareCatalog'])
        ->name('digging_deeper.prepareCatalog');
});
