<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'body',
        'image',
    ];

    /**
     * توليد الـ slug تلقائياً من عنوان المقال عند الإنشاء أو التعديل.
     */
    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::updating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    /**
     * Local Scope لمحرك البحث والفلترة والترتيب المتقدم.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        // 1. البحث بالنص في العنوان أو محتوى المقال
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        });

        // 2. التصفية حسب معرّف أو رابط التصنيف
        $query->when($filters['category_id'] ?? null, function ($q, $categoryId) {
            $q->where('category_id', $categoryId);
        });

        $query->when($filters['category_slug'] ?? null, function ($q, $categorySlug) {
            $q->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        });

        // 3. التصفية حسب معرّف أو رابط الوسم
        $query->when($filters['tag_id'] ?? null, function ($q, $tagId) {
            $q->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
        });

        $query->when($filters['tag_slug'] ?? null, function ($q, $tagSlug) {
            $q->whereHas('tags', fn ($q) => $q->where('tags.slug', $tagSlug));
        });

        // 4. التصفية حسب الكاتب
        $query->when($filters['user_id'] ?? null, function ($q, $userId) {
            $q->where('user_id', $userId);
        });

        // 5. الترتيب الديناميكي (Sorting)
        $sortBy = in_array($filters['sort_by'] ?? null, ['created_at', 'title', 'id']) ? $filters['sort_by'] : 'created_at';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortBy, $sortDir);
    }

    /**
     * علاقة المقال بالكاتب (المستخدم): المقال ينتمي لمستخدم واحد.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة المقال بالتصنيف: المقال ينتمي لتصنيف واحد.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * علاقة المقال بالتعليقات: المقال يحتوي على عدة تعليقات.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * علاقة المقال بالوسوم: المقال يرتبط بعدة وسوم (Many-to-Many).
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
