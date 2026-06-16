<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\SoftDeletes; // Розкоментуй, якщо увімкнеш deleted_at в БД

class BlogPost extends Model
{
    use HasFactory;
    // use SoftDeletes; // Розкоментуй, якщо контролер/репозиторій викликає метод withTrashed()

    const UNKNOWN_USER = 1;

    /**
     * Явно вказуємо назву таблиці в базі даних.
     * Якщо твоя таблиця називається просто 'posts', зміни значення на 'posts'.
     */
    protected $table = 'blog_posts';

    /**
     * Атрибути, які можна масово заповнювати (Mass Assignable).
     */
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'user_id',
        'excerpt',
        'content_raw',
        'is_published',
        'published_at',
    ];

    /**
     * Категорія статті (Стаття належить одній категорії)
     * * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    /**
     * Автор статті (Стаття належить одному користувачу)
     * * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
