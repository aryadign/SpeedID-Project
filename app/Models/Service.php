<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['institution_id', 'name', 'description', 'duration', 'daily_quota', 'is_active'])]
class Service extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'daily_quota' => 'integer',
            'duration' => 'integer',
        ];
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function serviceSlots(): HasMany
    {
        return $this->hasMany(ServiceSlot::class);
    }
}
