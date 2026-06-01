<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description'])]
class NewsCategory extends Model
{
    public function newsPosts(): HasMany
    {
        return $this->hasMany(NewsPost::class, 'category_id');
    }
}
