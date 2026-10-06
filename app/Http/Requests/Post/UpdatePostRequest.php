<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * التحقق من صلاحية المستخدم لتعديل هذا المقال تحديداً عبر Policy.
     */
    public function authorize(): bool
    {
        $post = $this->route('post');

        return $post && $this->user()?->can('update', $post);
    }

    /**
     * شروط التحقق عند تعديل المقال.
     */
    public function rules(): array
    {
        $postId = $this->route('post')?->id ?? $this->route('post');

        return [
            'category_id' => 'sometimes|required|exists:categories,id',
            'title'       => 'sometimes|required|string|max:255',
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($postId)],
            'body'        => 'sometimes|required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
        ];
    }
}
