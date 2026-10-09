# 📚 دليل التوثيق المعماري والتطبيقي: جناح الاختبارات الآلية (Phase 9 - Automated Testing Suite)

---

## 📌 1. المقدمة ونظرة عامة (Overview)

تُعتبر **الاختبارات الآلية (Automated Testing)** صمام الأمان الحقيقي للـ Backend. تضمن هذه الوحدة اختبار جميع الـ Endpoints والسياسات والـ Models تلقائياً في بيئة منعزلة فائقة السرعة بذاكرة مؤقتة (SQLite In-Memory Database)، مما يضمن عدم انكسار أي ميزة برمجية عند إضافة خصائص جديدة مستقبلاً (Regression Protection).

---

## 🧠 2. التسلسل المنطقي والنظري (Theoretical & Logical Flow)

### 🔄 بيئة تنفيذ الاختبارات (Isolated Testing Pipeline)

```text
 Terminal Command: php artisan test
               │
               ▼
      [ phpunit.xml Config ] ──► تفعيل SQLite In-Memory (:memory:)
               │
               ▼
     [ RefreshDatabase Trait ] ──► إنشاء جداول البيانات في الذاكرة وإعادة مسحها لكل اختبار
               │
               ▼
  [ Model Factories & Assertions ] ──► إنشاء بيانات وهمية وفحص الـ JSON ورسائل الأمان (403, 201, 200)
```

1. **`RefreshDatabase Trait`**: عزل كل دالة اختبار؛ حيث تقوم بتشغيل المايجريشن تلقائياً على قاعدة بيانات SQLite السريعة ومسحها فور انتهاء الاختبار لتجنب تلوث البيانات بين التجارب.
2. **`Model Factories`**: استخدام المصانع البرمجية (`User::factory()`, `Post::factory()`) لتوليد بيانات وهمية آمنة ودقيقة فورياً.

---

## 🔬 3. المقارنة العملية: النظرية مقابل التطبيق (Theory vs. Implementation)

| المفهوم النظري (Theory Concept) | التطبيق العملي في الكود (Practical Implementation) | الهدف والسبب الهندسي (Engineering Rationale) |
| :--- | :--- | :--- |
| **In-Memory SQLite Testing** | `phpunit.xml`<br>`<env name="DB_CONNECTION" value="sqlite"/>` | إنجاز كافة الاختبارات في ثانية واحدة (1.07s) دون التأثير على قاعدة بيانات MySQL المباشرة. |
| **Sanctum Authentication Mocking** | `$this->actingAs($user, 'sanctum')` | محاكاة تسجيل دخول مستخدم مسجل مسبقاً بـ Token لاختبار المسارات المحمية فورياً. |
| **Status Code & Json Assertions** | `$response->assertStatus(201)->assertJson(...)` | التأكد الصارم من مطابقة كود الاستجابة وهيكل الـ JSON لمعايير الـ API. |
| **Forbidden Authorization Tests** | `test_normal_user_cannot_create_category()` | التأكد التلقائي من نجاح سياسات الأمان والحظر بـ `403 Forbidden` للمستخدم غير المصرح. |

---

## 🛠️ 4. تفاصيل جناح الاختبارات المنفذة (19 Feature Tests)

### 1️⃣ اختبارات المصادقة والهوية (`tests/Feature/AuthTest.php`)
* **`test_user_can_register_successfully`**: اختبر إنشاء حساب جديد والتأكد من إرجاع `201` وتوليد Token.
* **`test_user_can_login_with_correct_credentials`**: اختبر الدخول بكلمة المرور الصحيحة.
* **`test_login_fails_with_invalid_credentials`**: اختبر رفض كلمة المرور الخاطئة بـ `422`.

### 2️⃣ اختبارات التصنيفات والوسوم (`CategoryTest.php` & `TagTest.php`)
* **`test_anyone_can_list_categories`**: اختبر استعراض التصنيفات للعموم.
* **`test_normal_user_cannot_create_category`**: اختبر حظر المستخدم العادي من إضافة تصنيف بـ `403`.
* **`test_admin_can_create_category`**: اختبر نجاح إضافة التصنيف من الأدمن بـ `201`.

### 3️⃣ اختبارات المقالات والصلاحيات (`PostTest.php`)
* **`test_authenticated_user_can_create_post`**: اختبر إنشاء مقال جديد بنجاح.
* **`test_user_cannot_update_others_post`**: اختبر حظر تعديل مقال مستخدم آخر عبر الـ Policy بـ `403`.
* **`test_can_search_posts_by_keyword`**: اختبر البحث والفلترة بالنص المباشر.

### 4️⃣ اختبارات التعليقات (`CommentTest.php`)
* **`test_authenticated_user_can_add_comment`**: اختبر إضافة تعليق بنجاح على مقال.
* **`test_user_cannot_update_others_comment`**: اختبر حظر تعديل تعليقات الآخرين بـ `403`.

---

## 📊 5. مصفوفة النتيجة الإجمالية لجناح الاختبارات

```text
   PASS  Tests\Unit\ExampleTest (1 test)
   PASS  Tests\Feature\AuthTest (4 tests)
   PASS  Tests\Feature\CategoryTest (3 tests)
   PASS  Tests\Feature\CommentTest (3 tests)
   PASS  Tests\Feature\ExampleTest (1 test)
   PASS  Tests\Feature\PostTest (4 tests)
   PASS  Tests\Feature\TagTest (3 tests)

  Tests:    19 passed (45 assertions)
  Duration: 1.07s
```

---

## 🎯 الخلاصة الهندسية
أصبح النظام الآن محمياً بـ **19 اختبار آلي** و **45 تأكيداً برمجياً** تُنفّذ بضغطة زر في ثانية واحدة لضمان جودة واستقرار الكود 100%.
