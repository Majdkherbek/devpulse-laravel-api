# 📚 دليل التوثيق المعماري والتطبيقي: وحدة الوسوم والعلاقات المتعددة (Phase 5 - Tags & Many-to-Many Module)

---

## 📌 1. المقدمة ونظرة عامة (Overview)

تُقدم وحدة الوسوم (**Tags Module**) ميزة التصنيف غير الهرمي المفتوح (Flat Tagging) للمقالات. تتيح هذه الوحدة ربط المقال الواحد بعدة وسوم (مثل: `Laravel`, `PHP`, `Backend`) وربط الوسم الواحد عدة مقالات عبر **العلاقة متعدد إلى متعدد (Many-to-Many Relationship)** والجدول الوسيط `post_tag`.

---

## 🧠 2. التسلسل المنطقي والنظري (Theoretical & Logical Flow)

### 🔄 جدول الربط الوسيط والعلاقة المتعددة (Pivot Table Lifecycle)

```text
 [ Post Model ]  ◄────►  [ Pivot Table: post_tag ]  ◄────►  [ Tag Model ]
  (id, title)             (post_id, tag_id)                 (id, name)
```

1. **`sync([$tag1, $tag2])`**: دالة ذكية تقوم بمقارنة الوسوم المرسلة بالوسوم المسجلة في جدول `post_tag`؛ تقوم بإضافة الوسوم الجديدة وحذف الوسوم التي أزيلت دون تكرار.
2. **الأمان والحماية**: إدارة وإنشاء وتعديل الوسوم محصورة بـ **مدير النظام فقط (Admin Only)** لحماية الوسوم من الفوضى والتكرار.

---

## 🔬 3. المقارنة العملية: النظرية مقابل التطبيق (Theory vs. Implementation)

| المفهوم النظري (Theory Concept) | التطبيق العملي في الكود (Practical Implementation) | الهدف والسبب الهندسي (Engineering Rationale) |
| :--- | :--- | :--- |
| **Pivot Table Composite Key** | `database/migrations/..._create_post_tag_table.php`<br>`$table->primary(['post_id', 'tag_id']);` | تسريع الاستعلامات ومنع تكرار نفس الوسم مع نفس المقال برمجياً على مستوى قاعدة البيانات. |
| **BelongsToMany Relationship** | `app/Models/Post.php` & `Tag.php`<br>`public function tags() { return $this->belongsToMany(Tag::class); }` | تمكين Eloquent من استعلام الوسوم المرفقة تلقائياً عبر الجدول الوسيط. |
| **Atomic Pivot Syncing** | `app/Http/Controllers/Api/PostController.php`<br>`$post->tags()->sync($data['tags']);` | الربط والتزامن الذكي للوسوم مع المقال بطلب واحد وبدون استعلامات يدوية معقدة. |
| **Admin Authorization Protection** | `app/Http/Requests/Tag/StoreTagRequest.php`<br>`return (bool) $this->user()?->is_admin;` | ضمان عدم قدرة أي مستخدم عادي على إنشاء أو تعديل الوسوم الأساسية بـ `403 Forbidden`. |

---

## 🛠️ 4. تفاصيل الملفات المعدلة بالشرح التطبيقي

### 1️⃣ الجدول الوسيط (`create_post_tag_table.php`)
```php
Schema::create('post_tag', function (Blueprint $table) {
    $table->foreignId('post_id')->constrained()->cascadeOnDelete();
    $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
    $table->primary(['post_id', 'tag_id']);
});
```

### 2️⃣ نموذج الوسم والأتمتة (`Tag.php`)
```php
protected $fillable = ['name', 'slug'];

public function posts(): BelongsToMany {
    return $this->belongsToMany(Post::class);
}
```

### 3️⃣ ربط التزامن في المقال (`PostController.php`)
```php
if (isset($data['tags'])) {
    $post->tags()->sync($data['tags']);
}
```

---

## 📊 5. مصفوفة نتائج الاختبار والتحقق (Testing Matrix)

| المسار (Endpoint) | نوع الطلب (Method) | نوع المستخدم | النتيجة المتوقعة (Expected Status) | النتيجة العملية المتحققة |
| :--- | :--- | :--- | :--- | :--- |
| `/api/tags` | `POST` | مدير النظام (`is_admin = true`) | `201 Created` | تم إضافة الوسم وتوليد الـ `slug` تلقائياً |
| `/api/tags` | `POST` | مستخدم عادي (`is_admin = false`) | `403 Forbidden` | تم حظر المستخدم بفضل `StoreTagRequest` |
| `/api/posts` (مع `tags: [1, 2]`) | `POST` | مستخدم مسجل | `201 Created` | تم إنشاء المقال وربط وسوم `post_tag` تلقائياً |
| `/api/tags` | `GET` | زائر عام (Public) | `200 OK` | إرجاع قائمة الوسوم منسقة بـ `TagResource` |

---

## 🎯 الخلاصة الهندسية
تكتمل الآن ميزة الوسوم بربط مرن وآمن يمنع التكرار ويدعم التوسع للربط المتقدم في البحث والفلترة مستقبلاً.
