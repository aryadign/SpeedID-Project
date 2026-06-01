<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'district_id'])]
class Subdistrict extends Model
{
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function newsPosts(): HasMany
    {
        return $this->hasMany(NewsPost::class);
    }
}
