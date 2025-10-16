# 🔧 دليل حل مشاكل Runtime Errors

## 🚨 **ما هو Runtime Error؟**

Runtime Error هو خطأ يحدث أثناء تشغيل التطبيق، وليس أثناء التطوير. هذه الأخطاء غالباً ما تكون بسبب:
- منطق خاطئ في الكود
- معاملات غير صحيحة
- استدعاء دوال على قيم `null`

## 🔍 **المشكلة التي تم حلها:**

### **الخطأ الأصلي:**
```
Call Stack
endDate
src\components\date-range-picker\useDateRangePicker.js (32:55)
GeneralAnalyticsPage
src\pages\dashboard\analytics\index.js (191:50)
```

### **السبب الجذري:**
خطأ منطقي في Controllers الـ Analytics:

```php
// ❌ الكود الخاطئ (قبل الإصلاح)
$customStartDate = $customStartDate == null ? Carbon::parse($customStartDate) : null;
$customEndDate = $customEndDate == null ? Carbon::parse($customEndDate) : null;
```

**المشكلة:** عندما يكون `$customStartDate == null`، يحاول الكود تحليل `null` باستخدام `Carbon::parse(null)`، مما يسبب خطأ runtime.

### **الحل المُطبق:**
```php
// ✅ الكود الصحيح (بعد الإصلاح)
$customStartDate = $customStartDate != null ? Carbon::parse($customStartDate) : null;
$customEndDate = $customEndDate != null ? Carbon::parse($customEndDate) : null;
```

## 📁 **الملفات التي تم إصلاحها:**

1. **`app/Http/Controllers/App/V1/AppAnalyticsController.php`**
   - الدالة: `appGetGeneralCounts()`
   - الدالة: `appGetTopLearners()`

2. **`app/Http/Controllers/Mobile/V1/MobileAnalyticsController.php`**
   - الدالة: `mobileGetGeneralCounts()`

## 🧪 **اختبار الإصلاح:**

```bash
php scripts/test_analytics_fix.php
```

## 🔍 **أنواع Runtime Errors الشائعة:**

### 1️⃣ **Null Pointer Exceptions**
```php
// ❌ خطأ
$user = null;
echo $user->name; // خطأ runtime

// ✅ صحيح
$user = null;
echo $user ? $user->name : 'غير محدد';
```

### 2️⃣ **Invalid Date Parsing**
```php
// ❌ خطأ
Carbon::parse(null); // خطأ runtime

// ✅ صحيح
$date = $dateString ? Carbon::parse($dateString) : null;
```

### 3️⃣ **Array Access on Non-Arrays**
```php
// ❌ خطأ
$data = null;
echo $data['key']; // خطأ runtime

// ✅ صحيح
$data = null;
echo $data['key'] ?? 'القيمة الافتراضية';
```

## 🛠️ **أدوات التشخيص:**

### **1. Laravel Logs**
```bash
tail -f storage/logs/laravel.log
```

### **2. Debug Mode**
```php
// في .env
APP_DEBUG=true
```

### **3. Error Handling**
```php
try {
    // الكود الذي قد يسبب خطأ
    $result = Carbon::parse($dateString);
} catch (Exception $e) {
    // التعامل مع الخطأ
    Log::error('خطأ في تحليل التاريخ: ' . $e->getMessage());
    return null;
}
```

## 🔧 **خطوات التشخيص:**

### **الخطوة 1: تحديد مصدر الخطأ**
- تحقق من Call Stack
- ابحث عن السطر المحدد في الخطأ
- راجع الكود في ذلك الموقع

### **الخطوة 2: تحليل المعاملات**
- تحقق من القيم المُمررة
- تأكد من أن القيم ليست `null` عند الحاجة
- استخدم `var_dump()` أو `dd()` للتحقق

### **الخطوة 3: إضافة حماية**
- استخدم `null coalescing operator` (`??`)
- أضف فحوصات `null` قبل استخدام القيم
- استخدم `try-catch` للتعامل مع الأخطاء

## 💡 **أفضل الممارسات:**

### **1. Null Safety**
```php
// ✅ دائماً تحقق من null
if ($value !== null) {
    $result = Carbon::parse($value);
}
```

### **2. Default Values**
```php
// ✅ استخدم قيم افتراضية
$date = $dateString ?: now()->format('Y-m-d');
```

### **3. Type Checking**
```php
// ✅ تحقق من نوع البيانات
if (is_string($dateString) && !empty($dateString)) {
    $date = Carbon::parse($dateString);
}
```

### **4. Error Logging**
```php
// ✅ سجل الأخطاء للتحليل
try {
    $result = riskyOperation();
} catch (Exception $e) {
    Log::error('خطأ في العملية: ' . $e->getMessage(), [
        'context' => $context,
        'parameters' => $parameters
    ]);
    throw $e;
}
```

## 🚀 **منع الأخطاء المستقبلية:**

### **1. Code Review**
- راجع الكود قبل النشر
- ابحث عن استخدامات محتملة لـ `null`
- تأكد من التعامل مع جميع الحالات

### **2. Testing**
```php
// اختبار الحالات الحدية
public function test_date_parsing_with_null()
{
    $result = $this->parseDate(null);
    $this->assertNull($result);
}
```

### **3. Static Analysis**
```bash
# استخدام أدوات التحليل الثابت
composer require --dev psalm/psalm
./vendor/bin/psalm
```

## 📚 **مراجع مفيدة:**

- [Laravel Error Handling](https://laravel.com/docs/errors)
- [PHP Null Safety](https://www.php.net/manual/en/language.operators.nullsafe.php)
- [Carbon Documentation](https://carbon.nesbot.com/docs/)

---

**آخر تحديث**: 2025-10-13  
**النسخة**: 1.0  
**الحالة**: ✅ تم حل المشكلة
