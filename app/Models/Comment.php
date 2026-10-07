<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'body',
    ];

    /**
     * علاقة التعليق بصاحبه: التعليق ينتمي لمستخدم واحد.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة التعليق بالمقال: التعليق ينتمي لمقال واحد.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
