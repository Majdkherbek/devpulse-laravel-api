<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * عرض جميع التعليقات الخاصة بمقال محدد (متاح للجميع).
     */
    public function index(Post $post): AnonymousResourceCollection
    {
        $comments = $post->comments()->with('user')->latest()->get();

        return CommentResource::collection($comments);
    }

    /**
     * إضافة تعليق جديد على مقال محدد.
     */
    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['post_id'] = $post->id;

        $comment = Comment::create($data);

        return (new CommentResource($comment->load('user')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * تعديل تعليق محدد مع التحقق من الملكية عبر Policy.
     */
    public function update(UpdateCommentRequest $request, Comment $comment): CommentResource
    {
        $comment->update($request->validated());

        return new CommentResource($comment->load('user'));
    }

    /**
     * حذف تعليق محدد مع التحقق من الملكية عبر Policy.
     */
    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.',
        ], 200);
    }
}
