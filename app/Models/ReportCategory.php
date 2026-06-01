<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'is_active'])]
class ReportCategory extends Model
{
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'category_id');
    }
}
