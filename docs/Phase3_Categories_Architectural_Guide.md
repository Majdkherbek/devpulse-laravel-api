# 📚 دليل التوثيق المعماري والتطبيقي: وحدة التصنيفات (Phase 3 - Categories Module)

---

## 📌 1. المقدمة ونظرة عامة (Overview)

تُعتبر وحدة التصنيفات (**Categories Module**) اللبنة الأساسية الهيكلية لنظام إدارة المحتوى والـ API. وظيفتها تصنيف المقالات والمحتوى برمجياً إلى مجموعات منطقية (مثل: *تقنية، رياضة، اقتصاد*).

الهدف الهندسي من هذه المرحلة ليس مجرد كتابة استعلامات قاعدة بيانات (CRUD)، بل تطبيق **معمارية الطبقات النظيفة (Clean Layered Architecture)** وتوزيع المسؤوليات بدقة مع ضبط الأمان والتصاريح.

---

## 🧠 2. التسلسل المنطقي والنظري (Theoretical & Logical Flow)

### 🔄 دورة حياة الطلب (Request Lifecycle)
عندما يصل طلب متعلق بالتصنيفات إلى الـ API، يمر بالتسلسل الهيكلي التالي:

```text
 Client (Bruno / Web / Mobile)
            │
            ▼
      [ routes/api.php ]  ───► التوجيه والفصل بين المسارات العامة والمحمية
            │
            ▼
    [ auth:sanctum Middleware ] ──► التأكد من الهوية (لعمليات الكتابة فقط)
            │
            ▼
   [ Store / Update Request ] ──► فحص الصلاحيات (Admin Only) والتحقق من البيانات (Validation)
            │
            ▼
    [ CategoryController ]   ──► استقبال الطلب والتنسيق بدون Business Logic معقد
            │
            ▼
      [ Category Model ]     ──► التعامل مع قاعدة البيانات وتوليد الـ Slug تلقائياً
            │
            ▼
    [ CategoryResource ]     ──► تشكيل وتنسيق مخرجات الـ JSON
            │
            ▼
      JSON Response (200, 201, 403)
```

---

### 🛡️ فلسفة الأمان والملكية في الوحدة (Security Architecture)
تعتمد الوحدة على مبدأ **"القراءة العامة / الكتابة المقيدة" (Public Read / Restricted Write)**:

* **العموم (Public User)**: يُسمح لأي شخص بطلب قائمة التصنيفات أو تفاصيل تصنيف محدد دون الحاجة لتسجيل دخول أو إرسال Token.
* **المدير (Admin Only)**: تتطلب عمليات الإضافة والتعديل والحذف تسجيل الدخول وتوفر شرط `is_admin === true`. يُحظر أي مستخدم عادي من تغيير هيكلية التصنيفات بـ **`403 Forbidden`**.

---

## 🔬 3. المقارنة العملية: النظرية مقابل التطبيق (Theory vs. Implementation)

يقارن الجدول التالي بين **المفاهيم الهندسية النظرية** و**كيف تم ترجمتها عملياً في كود المشروع**:

| المفهوم النظرية (Theory Concept) | التطبيق العملي في الكود (Practical Implementation) | الهدف والسبب الهندسي (Engineering Rationale) |
| :--- | :--- | :--- |
| **Schema Definition & Uniqueness** | `database/migrations/..._create_categories_table.php`<br>`$table->string('name')->unique();`<br>`$table->string('slug')->unique();` | ضمان عدم تكرار أسماء التصنيفات أو روابطها على مستوى قاعدة البيانات (Data Integrity). |
| **Model Lifecycle Events (Booted)** | `app/Models/Category.php`<br>`static::creating(...)` & `static::updating(...)`<br>`$category->slug = Str::slug($category->name);` | أتمتة توليد الـ Slug الصديق لمحركات البحث تلقائياً من الاسم دون إجبار المستخدم على إدخاله. |
| **Early Authorization Defense** | `app/Http/Requests/Category/StoreCategoryRequest.php`<br>`public function authorize(): bool {`<br>`return (bool) $this->user()?->is_admin; }` | حظر غير المدراء في أول طبقة (Request Layer) وقبل وصول الطلب للـ Controller وإهدار الموارد. |
| **Explicit JSON Resource Transformation** | `app/Http/Resources/CategoryResource.php`<br>`'id' => $this->id, 'name' => $this->name, 'slug' => $this->slug` | توحيد شكل استجابة الـ API ومنع تسريب البيانات الحساسة أو الحقول الداكنة في قاعدة البيانات. |
| **Thin Controllers Architecture** | `app/Http/Controllers/Api/CategoryController.php`<br>استخدام `validated()` وإعادة `CategoryResource`. | إبقاء الـ Controller كـ Orchestrator يوجه حركة الطلب والاستجابة فقط دون تعقيد. |
| **Granular Route Protection** | `routes/api.php`<br>فصل `Route::get` للعموم، ووضع `Route::post/put/delete` داخل `auth:sanctum`. | ضمان حماية العناوين الحساسة وحظر الوصول غير المصرح به على مستوى موجه المسارات. |

---

## 🛠️ 4. تفاصيل الملفات المعدلة بالشرح التطبيقي

### 1️⃣ قاعدة البيانات والـ Migration (`create_categories_table.php`)
* **الهدف**: بناء الهيكل التخزيني بالتزام القيود.
* **الكود التطبيقي**:
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->string('slug')->unique();
    $table->timestamps();
});
```

---

### 2️⃣ النموذج والأتمتة الذكية (`Category.php`)
* **الهدف**: ربط البيانات بالـ ORM وأتمتة الـ Slug.
* **الكود التطبيقي**:
```php
protected $fillable = ['name', 'slug'];

protected static function booted(): void
{
    static::creating(function (Category $category) {
        if (empty($category->slug)) {
            $category->slug = Str::slug($category->name);
        }
    });
}
```

---

### 3️⃣ طبقة التحقق والحماية الفورية (`StoreCategoryRequest.php` & `UpdateCategoryRequest.php`)
* **الهدف**: التحقق من فرادة الاسم ورتبة الأدمن.
* **الكود التطبيقي**:
```php
public function authorize(): bool
{
    return (bool) $this->user()?->is_admin;
}

public function rules(): array
{
    return [
        'name' => 'required|string|max:255|unique:categories,name',
        'slug' => 'nullable|string|max:255|unique:categories,slug',
    ];
}
```

---

### 4️⃣ طبقة إخراج الـ API القياسي (`CategoryResource.php`)
* **الهدف**: تحديد مخرجات الـ JSON بدقة واحترافية.
* **الكود التطبيقي**:
```php
public function toArray(Request $request): array
{
    return [
        'id'         => $this->id,
        'name'       => $this->name,
        'slug'       => $this->slug,
        'created_at' => $this->created_at?->toIso8601String(),
    ];
}
```

---

### 5️⃣ الموجه والمسارات المنظمة (`routes/api.php`)
* **الهدف**: تنظيم الصلاحيات بين العامة والمحمية.
* **الكود التطبيقي**:
```php
// مسارات عامة (Public)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

// مسارات محمية بـ Sanctum للأدمن (Protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});
```

---

## 📊 5. مصفوفة نتائج الاختبار والتحقق (Testing Matrix)

تم اختبار كافة المسارات في البيئة العملية والتحقق من الأكواد المرجعة:

| المسار (Endpoint) | نوع الطلب (Method) | نوع المستخدم (User Role) | النتيجة المتوقعة (Expected Status) | الاستجابة المرجعة (Response) |
| :--- | :--- | :--- | :--- | :--- |
| `/api/categories` | `GET` | زائر عام (Public) | `200 OK` | مصفوفة التصنيفات بصيغة `CategoryResource` |
| `/api/categories` | `POST` | زائر غير مسجل (Guest) | `401 Unauthorized` | `{"message": "Unauthenticated."}` |
| `/api/categories` | `POST` | مستخدم عادي (`is_admin = false`) | `403 Forbidden` | `{"message": "This action is unauthorized."}` |
| `/api/categories` | `POST` | مدير النظام (`is_admin = true`) | `201 Created` | بيانات التصنيف الجديد مع الـ `slug` المولد |
| `/api/categories/1` | `DELETE` | مستخدم عادي (`is_admin = false`) | `403 Forbidden` | `{"message": "This action is unauthorized."}` |
| `/api/categories/1` | `DELETE` | مدير النظام (`is_admin = true`) | `200 OK` | `{"message": "Category deleted successfully."}` |

---

## 🎯 الخلاصة الهندسية

بهذا التصميم، نضمن أن وحدة التصنيفات:
1. **آمنة بنسبة 100%**: لا يمكن عبث أو تعديل تصنيف إلا بواسطة مدير النظام.
2. **سريعة وخفيفة (Performant & Clean)**: تفصل الصلاحيات والـ Validation في طبقات مخصصة قبل الوصول للـ Database.
3. **جاهزة للتوسع (Extensible)**: سهولة ربطها مستقبلاً بوحدة المقالات (Posts) عبر علاقة `hasMany`.
