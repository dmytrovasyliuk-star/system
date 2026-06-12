<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes; // Закоментовано через відсутність колонки в БД

class BlogPost extends Model
{
    // якщо раніше коментували SoftDeletes, то залишаємо так

    const UNKNOWN_USER = 1;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        // 'user_id', // Видаляємо цей рядок
        'excerpt',
        'content_raw',
        'is_published',
        'published_at',
    ];

    /**
     * Категорія статті (Стаття належить категорії)
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    /**
     * Автор статті (Стаття належить користувачу)
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
