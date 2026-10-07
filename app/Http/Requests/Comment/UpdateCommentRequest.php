<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    /**
     * التحقق من ملكية التعليق عبر الربط التلقائي مع CommentPolicy.
     */
    public function authorize(): bool
    {
        $comment = $this->route('comment');

        return $comment && $this->user()?->can('update', $comment);
    }

    /**
     * شروط التحقق عند تعديل التعليق.
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string|max:1000',
        ];
    }
}
