<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
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
     * السماح بتحديث التعليق فقط إذا كان المستخدم هو صاحب التعليق.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * السماح بحذف التعليق فقط إذا كان المستخدم هو صاحب التعليق.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
}
