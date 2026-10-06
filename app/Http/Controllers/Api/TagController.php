<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    /**
     * عرض جميع الوسوم (متاح للجميع).
     */
    public function index(): AnonymousResourceCollection
    {
        $tags = Tag::latest()->get();

        return TagResource::collection($tags);
    }

    /**
     * إنشاء وسم جديد (متاح لمدير النظام فقط).
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $tag = Tag::create($request->validated());

        return (new TagResource($tag))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * عرض وسم محدد (متاح للجميع).
     */
    public function show(Tag $tag): TagResource
    {
        return new TagResource($tag);
    }

    /**
     * تعديل وسم محدد (متاح لمدير النظام فقط).
     */
    public function update(UpdateTagRequest $request, Tag $tag): TagResource
    {
        $tag->update($request->validated());

        return new TagResource($tag);
    }

    /**
     * حذف وسم محدد (متاح لمدير النظام فقط).
     */
    public function destroy(Request $request, Tag $tag): JsonResponse
    {
        if (! $request->user()?->is_admin) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully.',
        ], 200);
    }
}
