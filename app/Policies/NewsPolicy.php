<?php

namespace App\Policies;

use App\Models\NewsPost;
use App\Models\User;

class NewsPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, NewsPost $newsPost): bool
    {
        return $newsPost->status === 'published' || $user->hasPermissionTo('manage news');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage news');
    }

    public function update(User $user, NewsPost $newsPost): bool
    {
        return $user->hasPermissionTo('manage news');
    }

    public function delete(User $user, NewsPost $newsPost): bool
    {
        return $user->hasPermissionTo('manage news');
    }
}
