<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
{
    /**
     * السماح فقط لمدير النظام (Admin) بتعديل الوسوم.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    /**
     * شروط التحقق عند تعديل وسم موجود.
     */
    public function rules(): array
    {
        $tagId = $this->route('tag')?->id ?? $this->route('tag');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('tags', 'name')->ignore($tagId)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tags', 'slug')->ignore($tagId)],
        ];
    }
}
