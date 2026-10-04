<?php
namespace App\Http\Requests\Category;
use Illuminate\Foundation\Http\FormRequest;
class StoreCategoryRequest extends FormRequest
{ /** * السماح فقط للمدير (Admin) بإضافة تصنيف جديد. */
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }
/**
 * شروط التحقق من بيانات إضافة التصنيف.
 */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ];
    }
}
