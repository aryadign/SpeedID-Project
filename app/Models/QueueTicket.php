<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'service_slot_id', 'queue_number', 'booking_code', 'qr_code', 'estimated_wait', 'status', 'called_at', 'completed_at', 'cancelled_at'])]
class QueueTicket extends Model
{
    protected function casts(): array
    {
        return [
            'estimated_wait' => 'integer',
            'called_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceSlot(): BelongsTo
    {
        return $this->belongsTo(ServiceSlot::class);
    }
}
