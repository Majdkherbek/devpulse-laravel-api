<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * اختبار استعراض تعليقات مقال محدد للعموم.
     */
    public function test_anyone_can_list_comments_of_a_post(): void
    {
        $post = Post::factory()->create();
        Comment::factory()->count(2)->create(['post_id' => $post->id]);

        $response = $this->getJson('/api/posts/' . $post->id . '/comments');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /**
     * اختبار إضافة تعليق بنجاح من مستخدم مسجل.
     */
    public function test_authenticated_user_can_add_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/posts/' . $post->id . '/comments', [
                'body' => 'Great article!',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'body' => 'Great article!',
                ],
            ]);

        $this->assertDatabaseHas('comments', [
            'body' => 'Great article!',
        ]);
    }

    /**
     * اختبار حظر تعديل تعليق يملكه مستخدم آخر عبر Policy (403 Forbidden).
     */
    public function test_user_cannot_update_others_comment(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $comment = Comment::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser, 'sanctum')
            ->putJson('/api/comments/' . $comment->id, [
                'body' => 'Hacked Comment',
            ]);

        $response->assertStatus(403);
    }
}
