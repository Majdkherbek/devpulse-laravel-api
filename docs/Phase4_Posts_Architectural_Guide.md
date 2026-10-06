# 📚 دليل التوثيق المعماري والتطبيقي: وحدة المقالات (Phase 4 - Posts & Images Module)

---

## 📌 1. المقدمة ونظرة عامة (Overview)

تُمثل وحدة المقالات (**Posts Module**) المحرك الرئيسي والمحتوى الأساسي لنظام DevPulse API. تربط هذه الوحدة كافة أجزاء النظام (المستخدمين الكُتّاب، التصنيفات، التعليقات، والوسوم)، مع توفير معالجة متقدمة لرفع واستبدال وحذف الصور وتخزينها، وتوفير آليات الترقيم (Pagination) والتصفية والبحث الرشيق.

---

## 🧠 2. التسلسل المنطقي والنظري (Theoretical & Logical Flow)

### 🔄 دورة حياة طلب المقال (Post Request Lifecycle)

```text
 Client (Bruno / Web / Mobile)
            │
            ▼
      [ routes/api.php ]  ───► التوجيه والفصل بين القراءة العامة والكتابة المحمية
            │
            ▼
    [ auth:sanctum Middleware ] ──► حماية إنشاء/تعديل/حذف المقالات
            │
            ▼
   [ Store / Update Request ] ──► فحص الصلاحيات بـ Policy والتحقق من الصور والبيانات
            │
            ▼
     [ PostController ]      ──► تنسيق عمليات الرفع وتخزين الصور وتحديث الروابط
            │
            ▼
       [ Post Model ]        ──► التعامل مع قاعدة البيانات والعلاقات وتوليد الـ Slug
            │
            ▼
     [ PostResource ]        ──► تشكيل روابط الصور والتغليف العكسي لمنع N+1
            │
            ▼
      JSON Response (200, 201, 403)
```

---

## 🔬 3. المقارنة العملية: النظرية مقابل التطبيق (Theory vs. Implementation)

| المفهوم النظري (Theory Concept) | التطبيق العملي في الكود (Practical Implementation) | الهدف والسبب الهندسي (Engineering Rationale) |
| :--- | :--- | :--- |
| **Foreign Key Cascading** | `database/migrations/..._create_posts_table.php`<br>`$table->foreignId('user_id')->constrained()->cascadeOnDelete();` | ضمان سلامة البيانات وحذف مقالات الكاتب تلقائياً عند حذف حسابه دون إبقاء بيانات معلقة (Orphan Rows). |
| **File Storage & Garbage Collection** | `app/Http/Controllers/Api/PostController.php`<br>`Storage::disk('public')->delete($post->image);` | تنظيف السيرفر وحذف الصورة القديمة تلقائياً عند التعديل أو الحذف لحماية الهاردسك من الملفات التالفة. |
| **Policy Authorization Integration** | `app/Http/Requests/Post/UpdatePostRequest.php`<br>`$this->user()?->can('update', $post);` | دمج الـ Policy بـ Form Request لحظر أي مستخدم لا يملك المقال بـ `403 Forbidden` في أول نقطة دفاع. |
| **N+1 Query Prevention** | `app/Http/Resources/PostResource.php`<br>`new UserResource($this->whenLoaded('user'));` | إرجاع الكاتب والتصنيف فقط إذا تم تحميلهما مسبقاً لمنع الاستعلامات المكررة المنهكة لقاعدة البيانات. |
| **Public Read & Protected Write** | `routes/api.php`<br>`GET /posts` (Public) vs `POST/PUT/DELETE /posts` (Sanctum) | السماح لأي زائر بقرءاة المقالات، وحصر الكتابة والتعديل للمستخدمين المسجلين المالكين فقط. |

---

## 🛠️ 4. تفاصيل الملفات المعدلة بالشرح التطبيقي

### 1️⃣ قاعدة البيانات والعلاقات (`create_posts_table.php`)
* **الهدف**: إنشاء هيكل المقالات وربطها بالمستخدمين والتصنيفات والصور.
```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('category_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('body');
    $table->string('image')->nullable();
    $table->timestamps();
});
```

---

### 2️⃣ النموذج والعلاقات (`Post.php`)
* **الهدف**: تمثيل المقال برمجياً وأتمتة الـ Slug وربط العلاقات مع الكاتب والتصنيف والوسوم.
```php
protected $fillable = ['user_id', 'category_id', 'title', 'slug', 'body', 'image'];

public function user(): BelongsTo { return $this->belongsTo(User::class); }
public function category(): BelongsTo { return $this->belongsTo(Category::class); }
```

---

### 3️⃣ طبقة الحماية والتحقق من الملفات (`StorePostRequest.php` & `UpdatePostRequest.php`)
* **الهدف**: التأكد من نوع الحجم الأقصى للصور والتحقق من ملكية المقال عبر الـ Policy.
```php
public function rules(): array {
    return [
        'category_id' => 'required|exists:categories,id',
        'title'       => 'required|string|max:255',
        'body'        => 'required|string',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ];
}
```

---

### 4️⃣ طبقة إخراج البيانات وتحويل روابط الصور (`PostResource.php`)
* **الهدف**: تحويل مسار الصورة المخزن إلى رابط URL كامل قابل للفتح المباشر.
```php
'image_url' => $this->image ? asset('storage/' . $this->image) : null,
'author'    => new UserResource($this->whenLoaded('user')),
'category'  => new CategoryResource($this->whenLoaded('category')),
```

---

### 5️⃣ المتحكم والبحث وإدارة الصور (`PostController.php`)
* **الهدف**: معالجة البحث، الترقيم، الرفع، الحذف، والربط مع `Storage`.
```php
if ($request->hasFile('image')) {
    if ($post->image) {
        Storage::disk('public')->delete($post->image);
    }
    $data['image'] = $request->file('image')->store('posts', 'public');
}
```

---

## 📊 5. مصفوفة نتائج الاختبار والتحقق (Testing Matrix)

تم تنفيذ اختبارات آلية ومباشرة وكانت نتائجها كالتالي:

| المسار (Endpoint) | نوع الطلب (Method) | الشرط / المستخدم | النتيجة المتوقعة (Expected Status) | النتيجة العملية المتحققة |
| :--- | :--- | :--- | :--- | :--- |
| `/api/posts` | `POST` | مستخدم مسجل (`Author 1`) | `201 Created` | تم إضافة المقال وتوليد الـ Slug وإسناد الكاتب تلقائياً |
| `/api/posts?search=Laravel` | `GET` | زائر عام (Public) | `200 OK` | إرجاع المقالات المنسجمة مع كلمة البحث بـ Pagination |
| `/api/posts/1` | `PUT` | مستخدم آخر غير المالك (`Author 2`) | `403 Forbidden` | تم الحظر بفضل `UpdatePostRequest` و `PostPolicy` |
| `/api/posts/1` | `PUT` | مالك المقال الأصلي (`Author 1`) | `200 OK` | تم تحديث بيانات المقال واستبدال الصورة بنجاح |
| `/api/posts/1` | `DELETE` | مالك المقال الأصلي (`Author 1`) | `200 OK` | تم حذف المقال وحذف ملف صورته من مجلد `storage` |

---

## 🎯 الخلاصة الهندسية

بناء وحدة المقالات بهذه المعمارية يضمن:
1. **أداء عالي (High Performance)**: حماية السيرفر من N+1 Queries باستعمال `whenLoaded()`.
2. **أمان الملفات (Secure Storage)**: التحقق الصارم من امتداد وحجم الصور وتفريغ المجلدات من الملفات المهملة.
3. **حماية الملكية (Ownership Enforcement)**: منع أي مستخدم من التلاعب بمقالات غيره بـ `403 Forbidden`.
