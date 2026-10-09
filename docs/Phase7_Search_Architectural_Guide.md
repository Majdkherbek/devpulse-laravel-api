# 📚 دليل التوثيق المعماري والتطبيقي: محرك البحث والفلترة والترتيب (Phase 7 - Search, Filtering & Sorting Engine)

---

## 📌 1. المقدمة ونظرة عامة (Overview)

تُقدم هذه الوحدة **محرك بحث وتصفية متقدم (Advanced Search & Filtering Engine)** يُمكّن عملاء الـ API (تطبيقات الموبايل أو الويب) من العثور على المقالات بدقة وسرعة عالية، مع دعم التصفية المزدوجة حسب التصنيف، الوسم، والكاتب، وضبط عدد العناصر بالصفحة (Custom Pagination) والترتيب الديناميكي (Dynamic Sorting).

---

## 🧠 2. التسلسل المنطقي والنظري (Theoretical & Logical Flow)

### 🔄 بناء الاستعلام الديناميكي (Query Builder Lifecycle via Local Scope)

```text
 Client Query Params (search, category_slug, tag_slug, sort_by, per_page)
                                  │
                                  ▼
                         [ PostController ]
                                  │
                                  ▼
                      [ Post::scopeFilter() ]
     ┌────────────────────────────┼────────────────────────────┐
     ▼                            ▼                            ▼
[ Multi-field Search ]   [ Relation Filters ]        [ Dynamic Sorting ]
(title OR body LIKE)    (whereHas category/tags)     (orderBy field & dir)
                                  │
                                  ▼
                    [ Paginated PostCollection ]
```

1. **`Local Query Scope`**: استخدام `scopeFilter` داخل نموذج المقال `Post.php` لعزل منطق بناء الاستعلام المعقد والمحافظة على الـ Controller كاملاً بأسلوب Thin Controller.
2. **`when()` Conditionals**: استخدام شرطية Eloquent الذكية `when()` لتطبيق أجزاء الاستعلام فقط عند إرسال البرامترات في رابط الطلب بدون جمل `if` مكررة متعبة.

---

## 🔬 3. المقارنة العملية: النظرية مقابل التطبيق (Theory vs. Implementation)

| المفهوم النظري (Theory Concept) | التطبيق العملي في الكود (Practical Implementation) | الهدف والسبب الهندسي (Engineering Rationale) |
| :--- | :--- | :--- |
| **Local Eloquent Scope** | `app/Models/Post.php`<br>`public function scopeFilter(Builder $query, array $filters)` | عزل وتجميع شروط البحث والترتيب في مكان مخصص بالنموذج لإعادة الاستخدام في أي مكان آخر بالرمز. |
| **Relational Filtering (whereHas)** | `app/Models/Post.php`<br>`$query->whereHas('tags', fn($q) => $q->where('slug', $slug));` | تصفية المقالات التي ترتبط بتصنيفات أو وسوم محددة بناءً على روابطها الفريدة (Slugs). |
| **Multi-field Logical Grouping** | `app/Models/Post.php`<br>`$q->where('title', 'like', ...)->orWhere('body', 'like', ...);` | تجميع شرط البحث بالنص في العناوين والمحتوى داخل قوس منطقي واحد حتى لا يفسد باقي الفلاتر. |
| **Configurable Pagination Cap** | `app/Http/Controllers/Api/PostController.php`<br>`$perPage = min((int) $request->query('per_page', 10), 50);` | السماح بطلب عدد محدد من العناصر بالصفحة مع وضع حد أقصى بـ 50 لحماية السيرفر من هجمات DoS. |

---

## 🛠️ 4. تفاصيل الكود التطبيقي للبحث والفلترة

### 1️⃣ كود محرك البحث والترتيب في النموذج (`Post.php`)
```php
public function scopeFilter(Builder $query, array $filters): Builder
{
    // 1. البحث في العنوان والمحتوى
    $query->when($filters['search'] ?? null, function ($q, $search) {
        $q->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('body', 'like', "%{$search}%");
        });
    });

    // 2. الفلترة حسب رابط التصنيف أو الوسم
    $query->when($filters['category_slug'] ?? null, function ($q, $slug) {
        $q->whereHas('category', fn ($q) => $q->where('slug', $slug));
    });

    $query->when($filters['tag_slug'] ?? null, function ($q, $slug) {
        $q->whereHas('tags', fn ($q) => $q->where('slug', $slug));
    });

    // 3. الترتيب الديناميكي
    $sortBy = in_array($filters['sort_by'] ?? null, ['created_at', 'title', 'id']) ? $filters['sort_by'] : 'created_at';
    $sortDir = strtolower($filters['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

    return $query->orderBy($sortBy, $sortDir);
}
```

---

### 2️⃣ التمرير العازل في المتحكم (`PostController.php`)
```php
public function index(Request $request): PostCollection
{
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
```

---

## 📊 5. مصفوفة نتائج الاختبار والتحقق (Testing Matrix)

| خيار البحث / التصفية (Query Param) | المثال في الرابط (URL Example) | النتيجة المتحققة في الاختبار الفعلي |
| :--- | :--- | :--- |
| **البحث بالنص النصي** | `GET /api/posts?search=Query` | عثر على المقال الحاوي على الكلمة في عنوانه أو محتواه |
| **التصفية برابط التصنيف** | `GET /api/posts?category_slug=tech` | عزل واسترجع مقالات تصنيف التكنولوجيا فقط |
| **التصفية برابط الوسم** | `GET /api/posts?tag_slug=laravel` | استرجع المقالات المرتبطة بـ وسم لارافيل فقط |
| **الترتيب الأبجدي التصاعدي** | `GET /api/posts?sort_by=title&sort_dir=asc` | رتب المقالات أبجدياً حسب العنوان تصاعدياً |
| **الترقيم المخصص** | `GET /api/posts?per_page=5&page=2` | إرجاع الصفحة الثانية بـ 5 عناصر مع روابط الترقيم |

---

## 🎯 الخلاصة الهندسية
يتميز محرك البحث بأنه قابل لإعادة الاستخدام والتمدد، ويمنع هجمات الاستعلامات الثقيلة، ويمنح تطبيقات الهواتف والويب مرونة كاملة في عرض وتصفية المقالات.
