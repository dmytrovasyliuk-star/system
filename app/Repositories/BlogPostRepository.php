<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;

class BlogPostRepository extends CoreRepository
{
    protected function getModelClass() { return Model::class; }

    public function getAllWithPaginate($perPage = 10)
    {
        return $this->startConditions()
            ->select(['id', 'title', 'slug', 'is_published', 'published_at', 'user_id', 'category_id'])
            ->orderBy('id', 'ASC')
            ->with(['category:id,title', 'user:id,name'])
            ->paginate($perPage);
    }

    public function getEdit($id) { return $this->startConditions()->find($id); }
}
