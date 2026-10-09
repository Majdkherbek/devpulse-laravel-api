# 📚 دليل التوثيق المعماري والتطبيقي: جودة الـ API ومعالجة الاستثناءات الموحدة (Phase 8 - API Quality & Global Exception Handling)

---

## 📌 1. المقدمة ونظرة عامة (Overview)

تُمثل هذه المرحلة معيار الجودة والاحترافية الرفيعة للـ API. تضمن معالجة الاستثناءات الموحدة (**Global Exception Handling**) إرجاع استجابات JSON نظيفة وموحدة الهيكل لجميع الأخطاء (مثل أخطاء العنصر غير الموجود `404 Not Found` أو التحقق `422` أو الأمان `401` و `403`) دون تسريب أي تفاصيل تقنية حساسة من السيرفر.

---

## 🧠 2. التسلسل المنطقي والنظري (Theoretical & Logical Flow)

### 🔄 معالجة الاستثناءات المركزية في لارافيل 12 (Centralized Exception Pipeline)

```text
 Client Request (e.g. GET /api/posts/99999)
                  │
                  ▼
          [ Router Dispatch ] ──► ModelNotFoundException (Post not found)
                  │
                  ▼
     [ bootstrap/app.php Pipeline ]
                  │
                  ▼
  [ $exceptions->render(NotFoundHttpException) ] ──► فحص هل الطلب ينتمي للـ API (api/*)
                  │
                  ▼
       JSON Response (404 Not Found)
      {"message": "Resource not found."}
```

---

## 🔬 3. المقارنة العملية: النظرية مقابل التطبيق (Theory vs. Implementation)

| المفهوم النظري (Theory Concept) | التطبيق العملي في الكود (Practical Implementation) | الهدف والسبب الهندسي (Engineering Rationale) |
| :--- | :--- | :--- |
| **API Exception Isolation** | `bootstrap/app.php`<br>`$exceptions->shouldRenderJsonWhen(fn($request) => $request->is('api/*'))` | عزل مسارات الـ API وضمان عدم إرجاع صفحات HTML للموبايل أو الويب عند حدوث خطأ. |
| **Global 404 Standard Handling** | `bootstrap/app.php`<br>`$exceptions->render(function(NotFoundHttpException $e) ...)` | إرجاع رسالة موحدة وقصيرة `{"message": "Resource not found."}` بـ `404` عند طلب عنصر غير موجود. |
| **Consistent Status Codes** | استخدام `200`, `201`, `401`, `403`, `404`, `422` في جميع الـ Controllers | الالتزام الصارم بمعايير RESTful API لسهولة التعامل من قبل مطوري الـ Frontend. |

---

## 🛠️ 4. تفاصيل الكود التطبيقي للـ Exception Handling

### كود المعالجة الموحدة في `bootstrap/app.php`
```php
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
    )
    ->withExceptions(function (Exceptions $exceptions): void {
        // 1. إجبار لارافيل على إرجاع الاستجابات بصيغة JSON لجميع مسارات الـ API
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // 2. المعالجة المركزية لخطأ العنصر غير الموجود (404 Resource Not Found)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Resource not found.',
                ], 404);
            }
        });
    })->create();
```

---

## 📊 5. جدول كود الاستجابات القياسي في المشروع (HTTP Status Codes Matrix)

| كود الاستجابة (Status Code) | الحالة والمفهوم (HTTP Status) | متى تُستخدم في DevPulse API؟ |
| :--- | :--- | :--- |
| **`200 OK`** | نجاح الطلب وإرجاع البيانات | عند جلب البيانات بنجاح أو تعديلها أو حذفها |
| **`201 Created`** | نجاح إنشاء عنصر جديد | عند تسجيل حساب جديد، أو إنشاء مقال، أو تصنيف، أو تعليق |
| **`401 Unauthorized`** | عدم وجود Token أو انتهائه | عند محاولة الدخول لمسار محمي بدون Bearer Token |
| **`403 Forbidden`** | عدم امتلاك الصلاحية (Policy) | عند محاولة مستخدم عادي تعديل مقال غيره أو إضافة تصنيف |
| **`404 Not Found`** | عدم وجود العنصر في السيرفر | عند طلب مقال أو تصنيف برقم id غير موجود بجدول البيانات |
| **`422 Unprocessable`** | فشل شروط المدخلات | عند إرسال بريد مسجل سابقاً أو عدم إرفاق الحقول المطلوبة |

---

## 🎯 الخلاصة الهندسية
بهذه التهيئة، تكتسب الـ API حماية هشة وتضمن استقرار التطبيقات المستهلكة لها بالاستجابات القياسية المتوقعة دائماً.
