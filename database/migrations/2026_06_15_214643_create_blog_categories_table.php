<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->timestamps();
            $table->softDeletes(); // Це знадобиться, якщо ви плануєте використовувати SoftDeletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
