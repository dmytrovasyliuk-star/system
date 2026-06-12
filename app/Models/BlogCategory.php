<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;
    const ROOT = 1;

    public function parentCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

    public function getParentTitleAttribute()
    {
        return $this->parentCategory->title ?? ($this->isRoot() ? 'Корінь' : '???');
    }

    public function isRoot()
    {
        return $this->id === BlogCategory::ROOT;
    }
    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'description',
    ];
}
