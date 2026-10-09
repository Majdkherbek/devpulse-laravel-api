<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * اختبار إنشاء مقال بنجاح بـ Token.
     */
    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/posts', [
                'category_id' => $category->id,
                'title'       => 'My First Test Post',
                'body'        => 'This is the detailed body content of the test post.',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'My First Test Post',
                    'slug'  => 'my-first-test-post',
                ],
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'My First Test Post',
        ]);
    }

    /**
     * اختبار حظر تعديل مقال يملكه مستخدم آخر عبر Policy (403 Forbidden).
     */
    public function test_user_cannot_update_others_post(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'user_id'     => $owner->id,
            'category_id' => $category->id,
            'title'       => 'Original Title',
        ]);

        $response = $this->actingAs($otherUser, 'sanctum')
            ->putJson('/api/posts/' . $post->id, [
                'title' => 'Hacked Title',
            ]);

        $response->assertStatus(403);
    }

    /**
     * اختبار سماح المالك بتعديل مقاله بنجاح (200 OK).
     */
    public function test_owner_can_update_own_post(): void
    {
        $owner = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'user_id'     => $owner->id,
            'category_id' => $category->id,
            'title'       => 'Original Title',
        ]);

        $response = $this->actingAs($owner, 'sanctum')
            ->putJson('/api/posts/' . $post->id, [
                'title' => 'Updated Title',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Updated Title',
                ],
            ]);
    }

    /**
     * اختبار البحث والفلترة بالنص.
     */
    public function test_can_search_posts_by_keyword(): void
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();

        Post::factory()->create([
            'user_id'     => $user->id,
            'category_id' => $category->id,
            'title'       => 'Laravel 12 Masterclass',
        ]);

        Post::factory()->create([
            'user_id'     => $user->id,
            'category_id' => $category->id,
            'title'       => 'Python Basics',
        ]);

        $response = $this->getJson('/api/posts?search=Laravel');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Laravel 12 Masterclass');
    }
}
