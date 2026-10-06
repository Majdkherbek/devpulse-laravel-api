<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    /**
     * السماح فقط لمدير النظام (Admin) بإضافة وسوم جديدة.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    /**
     * شروط التحقق عند إنشاء وسم جديد.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:tags,name',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
        ];
    }
}
