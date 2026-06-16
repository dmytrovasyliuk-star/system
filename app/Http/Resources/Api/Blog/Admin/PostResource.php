<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Додаємо перевірку: якщо $this->resource порожній, не намагаємось читати дані
        if (!$this->resource) {
            return [];
        }

        return [
            'id'             => $this->id,
            'title'          => $this->title ?? 'Без назви',
            'slug'           => $this->slug ?? '',
            'is_published'   => (bool) ($this->is_published ?? false),
            'date_published' => $this->published_at
                ? Carbon::parse($this->published_at)->format('Y-m-d H:i:s')
                : 'Дата не встановлена',
            'user_id'        => $this->user_id,
            'category_id'    => $this->category_id,
        ];
    }
}
