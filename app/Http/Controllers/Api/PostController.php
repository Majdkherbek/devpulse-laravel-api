<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * عرض قائمة المقالات مع الترقيم والفلترة وحل مشكلة N+1.
     */
    public function index(Request $request): PostCollection
    {
        $query = Post::with(['user', 'category', 'tags'])->latest();

        // فلترة المقالات حسب التصنيف إن وُجد
        if ($request->has('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        // البحث بالنص في عنوان المقال
        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('title', 'like', "%{$search}%");
        }

        $posts = $query->paginate(10);

        return new PostCollection($posts);
    }

    /**
     * إنشاء مقال جديد مع دعم رفع الصور والوسوم.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $data = $request->validated();

        // معالجة رفع صورة المقال إن وُجدت
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        // إسناد معرّف المستخدم الكاتب تلقائياً من المستخدم المسجّل
        $data['user_id'] = $request->user()->id;

        $post = Post::create($data);

        // ربط الوسوم بالمقال إن وُجدت
        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return (new PostResource($post->load(['user', 'category', 'tags'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * عرض تفاصيل مقال محدد.
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post->load(['user', 'category', 'tags']));
    }

    /**
     * تعديل مقال مع دعم استبدال الصورة القديمة والتحقق عبر Policy.
     */
    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        $data = $request->validated();

        // معالجة استبدال صورة المقال وحذف القديمة من المجلد
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        // تحديث الوسوم المرتبطة بالمقال
        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return new PostResource($post->load(['user', 'category', 'tags']));
    }

    /**
     * حذف مقال مع حذف صورته من المجلد والتحقق من الملكية عبر Policy.
     */
    public function destroy(Request $request, Post $post): JsonResponse
    {
        Gate::authorize('delete', $post);

        // حذف ملف الصورة من المجلد عند حذف المقال
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully.',
        ], 200);
    }
}
