<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
     use HasFactory;
     protected $fillable = [
        'name',
        'slug',
     ];
    /**
     * توليد الـ slug تلقائياً عند حفظ التصنيف إذا لم يُرسل بشكل صريح.
     */
    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * العلاقة مع المقالات: التصنيف يحتوي على عدة مقالات.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
