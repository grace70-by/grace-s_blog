<?php

namespace App\Policies;

use App\Models\Publication;
use App\Models\User;

class PublicationPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Publication $publication): bool
    {
        if ($publication->isPublished()) {
            return true;
        }

        return $user?->isAdmin() ?? false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Publication $publication): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Publication $publication): bool
    {
        return $user->isAdmin();
    }
}
