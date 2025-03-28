<?php

namespace App\Services;

use App\Models\User;

class TagService
{
    public function userOwnsTag(User $user, int $tag_id): bool
    {
        return $user->tags()->where('id', $tag_id)->exists();
    }
}
