<?php

namespace App\Policies;

use App\Models\QueueTicket;
use App\Models\User;

class QueuePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage queues') || true;
    }

    public function view(User $user, QueueTicket $queueTicket): bool
    {
        return $user->hasPermissionTo('manage queues') || $user->id === $queueTicket->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, QueueTicket $queueTicket): bool
    {
        return $user->hasPermissionTo('manage queues');
    }

    public function delete(User $user, QueueTicket $queueTicket): bool
    {
        return $user->hasPermissionTo('manage queues');
    }
}
