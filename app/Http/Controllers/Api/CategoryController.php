<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * عرض جميع التصنيفات (متاح للجميع).
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::latest()->get();

        return CategoryResource::collection($categories);
    }

    /**
     * إنشاء تصنيف جديد (متاح للمدير فقط).
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * عرض تصنيف محدد (متاح للجميع).
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    /**
     * تعديل تصنيف محدد (متاح للمدير فقط).
     */
    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    /**
     * حذف تصنيف محدد (متاح للمدير فقط).
     */
    public function destroy(Request $request, Category $category): JsonResponse
    {
        if (! $request->user()?->is_admin) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.',
        ], 200);
    }
}
