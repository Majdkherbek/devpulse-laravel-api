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
     * عرض قائمة المقالات مع محرك البحث والفلترة والترتيب والترقيم المتقدم.
     */
    public function index(Request $request): PostCollection
    {
        // تحديد عدد العناصر في الصفحة مع وضع حد أقصى بـ 50 للحماية
        $perPage = min((int) $request->query('per_page', 10), 50);

        $posts = Post::with(['user', 'category', 'tags'])
            ->filter($request->only([
                'search', 'category_id', 'category_slug',
                'tag_id', 'tag_slug', 'user_id',
                'sort_by', 'sort_dir',
            ]))
            ->paginate($perPage)
            ->withQueryString();

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
