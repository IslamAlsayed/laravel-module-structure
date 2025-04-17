# 📦 تحويل مجلد إلى وحدة ونقله وعرض هيكلته

هذا المشروع يتيح لك **تحويل أي مجلد إلى "وحدة"** يتم نسخها إلى مجلد `Modules/`، مع عرض هيكل الملفات والمجلدات بشكل مرئي بعد النقل.  
✅ يتم كل هذا من خلال واجهة بسيطة تعتمد على **نموذج إدخال (Form)**، دون الحاجة إلى تعديل أي جزء في الكود.

---

## ✅ المتطلبات

- خادم ويب يدعم PHP (مثل Apache أو Nginx مع PHP).
- صلاحيات كتابة على المجلد الوجهة `Modules/`.
- PHP 7.0 أو أحدث.

---

## 🚀 كيفية الاستخدام

### 1. رفع المشروع

- قم بتحميل أو استنساخ الملفات إلى مجلد داخل خادم الويب المحلي (مثل `htdocs` في XAMPP أو `www` في Laragon).
- تأكد أن مجلد `Modules/` موجود وقابل للكتابة.

### 2. استخدام النموذج (Form)

- افتح الصفحة في المتصفح عبر الرابط:  
  `http://localhost/index.php`
- ستجد نموذج يحتوي على:

  - **المجلد المستهدف**: المسار الكامل للمجلد الذي تريد نسخه (مثلاً: `C:/Users/eslam/Desktop/demo`).
  - **اسم الوحدة**: اسم جديد سيتم استخدامه لإنشاء مجلد داخل `Modules/` (مثلاً: `BlogModule`).

- اضغط على زر **"نقل الملفات"** وسيقوم السكربت تلقائيًا بـ:
  - إنشاء مجلد جديد داخل `Modules/`.
  - نسخ جميع الملفات والمجلدات بداخله.
  - عرض هيكل الملفات المنقولة بشكل مرتب وهرمي.

---

## 📂 شكل العرض بعد النقل

بعد نجاح العملية، سيتم عرض هيكل المجلد باستخدام رموز توضيحية:

- 📁 مجلد
- 📄 ملف

#### مثال

هيكل الحجم: Orders

- 📁 Database
  - 📁 Migratio
    - 📄 2024_03_01_175602_create_order_items_table.php
    - 📄 2024_03_01_175702_create_orders_table.php
    - 📄 2024_03_01_175902_create_invoices_table.php
- 📁 Entities
  - 📄 Invoice.php
  - 📄 Order.php
  - 📄 OrderItem.php
- 📁 Events
  - 📄 SendMessage.php
- 📁 Http
  - 📁 Controllers
    - 📄 InvoiceController.php
    - 📄 OrderController.php
    - 📄 OrderItemController.php
  - 📁 Middleware
    - 📄 Kernel.php
    - 📄 SessionExpired.php
    - 📄 SetLocale.php
  - 📁 Requests
    - 📄 LoginRequestcls.php
- 📁 Mail
  - 📄 ReservationFailed.php
  - 📄 ReservationReminder.php
- 📁 Notifications
  - 📄 OrderStatusNotification.php
- 📁 Other
  - 📄 Orders_Module.zip
- 📁 Repositories
  - 📄 GeneralRepository.php
- 📁 Resources
  - 📁 assets
    - 📁 css
      - 📄 addOrder.css
      - 📄 addOrder.css
    - 📁 images
      - 📄 image.jpg
      - 📄 image2.jpg
      - 📄 photo.png
      - 📄 photo.webp
  - 📁 views
    - 📁 invoices
      - 📄 index.blade.php
      - 📄 read.blade.php
    - 📁 orders
      - 📄 add.blade.php
      - 📄 index.blade.php
      - 📄 show.blade.php
    - 📁 products
      - 📄 add.blade.php
      - 📄 index.blade.php
      - 📄 show.blade.php
- 📁 Providers
  - 📄 ModuleNameServiceProvider.php
  - 📄 RouteServiceProvider.php
- 📁 Services
  - 📄 GeneralServices.php
- 📁 Routes
  - 📄 api.php
  - 📄 web.php
- 📁 Tests
  - 📁 Feature
  - 📁 Unit
- composer.json
- module.json
- package.json
- vite.config.js

يعرض السكربت هيكل المجلدات بشكل متداخل بعمق يصل إلى 10 مستويات (يمكن تعديله داخل الكود إذا رغبت، لكن ليس مطلوبًا).

---

## 🧠 ملاحظات مهمة

- ✅ لا تحتاج إلى تعديل أي سطر في الكود — كل شيء يتم من خلال النموذج فقط.
- تأكد من صلاحيات الوصول للمجلدات والملفات.
- يتم **الكتابة فوق الملفات** إذا كانت موجودة مسبقًا بنفس الاسم داخل الوجهة.
- السكربت لا يدعم التعامل مع الملفات الكبيرة جدًا أو التي تحتوي على مشاكل صلاحيات معقدة.

---

## 🛠️ المساهمة في المشروع

هل لديك أفكار أو تحسينات؟  
نرحب بمساهماتك! يمكنك:

- فتح Issue بالمشكلة أو الاقتراح.
- عمل Pull Request لتحسين الكود.

---

## 📬 تواصل معي

- 📧 **البريد الإلكتروني**: [eslamalsayed8133@gmail.com](mailto:eslamalsayed8133@gmail.com)
- 💼 **لينكدإن**: [IslamAlsayed](https://www.linkedin.com/in/islam-alsayed7)

---

> ✨ تم تطوير هذا المشروع لتسهيل تحويل أي مجلد إلى وحدة برمجية منظمة بنقرة واحدة.
