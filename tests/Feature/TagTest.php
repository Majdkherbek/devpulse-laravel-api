<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /**
     * اختبار استعراض جميع الوسوم للعموم.
     */
    public function test_anyone_can_list_tags(): void
    {
        Tag::factory()->count(3)->create();

        $response = $this->getJson('/api/tags');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * اختبار حظر المستخدم العادي من إنشاء وسم جديد (403 Forbidden).
     */
    public function test_normal_user_cannot_create_tag(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/tags', [
                'name' => 'New Tag',
            ]);

        $response->assertStatus(403);
    }

    /**
     * اختبار سماح الأدمن بإنشاء وسم جديد (201 Created).
     */
    public function test_admin_can_create_tag(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/tags', [
                'name' => 'Laravel',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'Laravel',
                    'slug' => 'laravel',
                ],
            ]);
    }
}
