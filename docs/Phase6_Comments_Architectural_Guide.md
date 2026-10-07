# 📚 دليل التوثيق المعماري والتطبيقي: وحدة التعليقات والصلاحيات (Phase 6 - Comments & Authorization Module)

---

## 📌 1. المقدمة ونظرة عامة (Overview)

تُقدم وحدة التعليقات (**Comments Module**) نظام التفاعل المباشر بين المستخدمين والمقالات. تتيح هذه الوحدة إضافة التعليقات على المقالات المحددة، وعرضها، مع تطبيق حماية صارمة تمنع تعديل أو حذف التعليق إلا بواسطة **صاحب التعليق الأصلي (Owner)** أو **مدير النظام (Admin)** عبر `CommentPolicy`.

---

## 🧠 2. التسلسل المنطقي والنظري (Theoretical & Logical Flow)

### 🔄 المسارات المتداخلة والحماية (Nested Routes & Authorization Flow)

```text
 [ GET /api/posts/{post}/comments ] ──► عرض تعليقات مقال محدد (Public Read)
            │
            ▼
 [ POST /api/posts/{post}/comments ] ──► إنشاء تعليق جديد مرتبط بالمقال والكاتب المسجل
            │
            ▼
 [ PUT /api/comments/{comment} ]    ──► فحص الملكية بـ CommentPolicy ──► 200 OK أو 403 Forbidden
            │
            ▼
 [ DELETE /api/comments/{comment} ] ──► فحص الملكية بـ CommentPolicy ──► حذف التعليق
```

---

## 🔬 3. المقارنة العملية: النظرية مقابل التطبيق (Theory vs. Implementation)

| المفهوم النظري (Theory Concept) | التطبيق العملي في الكود (Practical Implementation) | الهدف والسبب الهندسي (Engineering Rationale) |
| :--- | :--- | :--- |
| **Nested Resource Route** | `routes/api.php`<br>`POST /api/posts/{post}/comments` | الربط الهيكلي الواضح للتعليق بالمقال المعني في عنوان الطلب. |
| **Cascade On Delete Foreign Keys** | `database/migrations/..._create_comments_table.php`<br>`$table->foreignId('post_id')->cascadeOnDelete();` | حذف تعليقات المقال تلقائياً في حالة حذف المقال الأصلي لحماية اتساق قاعدة البيانات. |
| **Policy Ownership Enforcement** | `app/Http/Requests/Comment/UpdateCommentRequest.php`<br>`$this->user()?->can('update', $comment);` | حظر أي مستخدم يحاول التلاعب بتعليقات غيره بـ `403 Forbidden` في طبقة الـ Request. |
| **Eager Loading Protection** | `app/Http/Controllers/Api/CommentController.php`<br>`$post->comments()->with('user')->get();` | حماية النظام من مشكلة N+1 Queries بجلب بيانات كاتب التعليق باستعلام واحد مجمع. |

---

## 🛠️ 4. تفاصيل الملفات المعدلة بالشرح التطبيقي

### 1️⃣ قاعدة بيانات التعليقات (`create_comments_table.php`)
```php
Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('post_id')->constrained()->cascadeOnDelete();
    $table->text('body');
    $table->timestamps();
});
```

### 2️⃣ نموذج التعليق والعلاقات (`Comment.php`)
```php
protected $fillable = ['user_id', 'post_id', 'body'];

public function user(): BelongsTo { return $this->belongsTo(User::class); }
public function post(): BelongsTo { return $this->belongsTo(Post::class); }
```

### 3️⃣ متحكم التعليقات والـ Policy (`CommentController.php`)
```php
public function destroy(Request $request, Comment $comment): JsonResponse
{
    Gate::authorize('delete', $comment);
    $comment->delete();
    return response()->json(['message' => 'Comment deleted successfully.'], 200);
}
```

---

## 📊 5. مصفوفة نتائج الاختبار والتحقق (Testing Matrix)

| المسار (Endpoint) | نوع الطلب (Method) | نوع المستخدم | النتيجة المتوقعة | النتيجة العملية المتحققة |
| :--- | :--- | :--- | :--- | :--- |
| `/api/posts/1/comments` | `POST` | مستخدم مسجل (`Commenter 1`) | `201 Created` | تم إنشاء التعليق وإسناد `post_id` و `user_id` تلقائياً |
| `/api/comments/1` | `PUT` | مستخدم آخر غير المالك (`Commenter 2`) | `403 Forbidden` | تم الحظر بفضل `CommentPolicy` |
| `/api/comments/1` | `PUT` | مالك التعليق الأصلي (`Commenter 1`) | `200 OK` | تم تعديل نص التعليق بنجاح |
| `/api/posts/1/comments` | `GET` | زائر عام (Public) | `200 OK` | إرجاع قائمة تعليقات المقال مع بيانات الكاتب |

---

## 🎯 الخلاصة الهندسية
تتميز وحدة التعليقات بتطبيق كامل لـ RESTful API Best Practices واستخدام الـ Policy لحماية خصوصية تفاعلات المستخدمين.
