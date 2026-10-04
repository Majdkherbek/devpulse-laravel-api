<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * إذا كان المستخدم مديراً، امنحه الصلاحية الكلية فوراً.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->is_admin) {
            return true;
        }

        return null;
    }

    /**
     * السماح بتحديث المقال فقط إذا كان المستخدم هو صاحب المقال.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * السماح بحذف المقال فقط إذا كان المستخدم هو صاحب المقال.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}
