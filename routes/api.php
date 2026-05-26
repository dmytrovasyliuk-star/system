<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\PostController;
use App\Http\Controllers\Api\Blog\Admin\CategoryController as AdminCategoryController;

Route::group(['prefix' => 'blog'], function () {
    Route::apiResource('posts', PostController::class)->names('blog.posts');
});

Route::group(['prefix' => 'admin/blog'], function () {
    $methods = ['index', 'store', 'update'];
    Route::apiResource('categories', AdminCategoryController::class)
        ->only($methods)
        ->names('blog.admin.categories');
});
