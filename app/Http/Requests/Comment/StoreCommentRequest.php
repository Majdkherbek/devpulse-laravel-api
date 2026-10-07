<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * أي مستخدم مسجل دخوله يحق له إضافة تعليق.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * شروط التحقق عند إضافة تعليق.
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string|max:1000',
        ];
    }
}
