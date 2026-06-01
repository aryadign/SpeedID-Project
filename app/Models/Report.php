<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'category_id', 'title', 'description', 'latitude', 'longitude', 'anonymous', 'status', 'rejection_reason', 'tracking_code', 'verified_at', 'processed_at', 'completed_at'])]
class Report extends Model
{
    protected function casts(): array
    {
        return [
            'anonymous' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'verified_at' => 'datetime',
            'processed_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ReportCategory::class, 'category_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(ReportMedia::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ReportComment::class);
    }
}
