<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * اختبار استعراض جميع التصنيفات للعموم.
     */
    public function test_anyone_can_list_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * اختبار منع المستخدم العادي من إنشاء تصنيف (403 Forbidden).
     */
    public function test_normal_user_cannot_create_category(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/categories', [
                'name' => 'New Category',
            ]);

        $response->assertStatus(403);
    }

    /**
     * اختبار سماح الأدمن بإنشاء تصنيف جديد (201 Created).
     */
    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/categories', [
                'name' => 'Technology',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'Technology',
                    'slug' => 'technology',
                ],
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Technology',
        ]);
    }
}
