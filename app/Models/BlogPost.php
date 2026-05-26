<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Додали зверху

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes; // 2. Додали всередині класу

    // ...
}
