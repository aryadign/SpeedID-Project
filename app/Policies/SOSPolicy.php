<?php

namespace App\Policies;

use App\Models\SOSRequest;
use App\Models\User;

class SOSPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage sos') || true;
    }

    public function view(User $user, SOSRequest $sOSRequest): bool
    {
        return $user->hasPermissionTo('manage sos') || $user->id === $sOSRequest->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, SOSRequest $sOSRequest): bool
    {
        return $user->hasPermissionTo('manage sos');
    }

    public function delete(User $user, SOSRequest $sOSRequest): bool
    {
        return $user->hasPermissionTo('manage sos');
    }
}
