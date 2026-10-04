<?php

namespace App\Http\Requests\Category;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
 { /** * السماح فقط للمدير (Admin) بتعديل التصنيف. */
      public function authorize(): bool
      {
        return (bool) $this->user()?->is_admin;
      }
    /**
     * شروط التحقق عند التعديل (مع استثناء اسم التصنيف الحالي من شرط الفرادة unique).
     */
    public function rules(): array
    {
        $categoryId = $this->route('category');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($categoryId)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($categoryId)],
        ];
    }
}
