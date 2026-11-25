<!--
  Kashida Hide my theme
  Developed by Kashida Media Services
-->
<p align="center">
  <img src="screen/Hide%20My%20Theme%20Plugin%20Banner.png" alt="Kashida Hide my theme banner" width="100%">
</p>

<h1 align="center">
  <img src="screen/Kashida%20Hide%20My%20Theme%20Icon.png" alt="Kashida Hide my theme icon" width="88"><br>
  Kashida Hide my theme
</h1>

<p align="center">
  <strong>Customize selected public WordPress theme identity details without editing the original theme files.</strong>
</p>

<p align="center">
  <a href="https://wordpress.org/plugins/kashida-hide-my-theme/">
    <img src="https://img.shields.io/wordpress/plugin/v/kashida-hide-my-theme" alt="WordPress Plugin Version">
  </a>
  <a href="https://wordpress.org/plugins/kashida-hide-my-theme/">
    <img src="https://img.shields.io/wordpress/plugin/dt/kashida-hide-my-theme" alt="WordPress Plugin Downloads">
  </a>
  <img src="https://img.shields.io/badge/WordPress-6.0%2B-21759B?logo=wordpress&logoColor=white" alt="WordPress 6.0+">
  <img src="https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white" alt="PHP 7.4+">
  <img src="https://img.shields.io/badge/License-GPLv2%2B-blue" alt="GPLv2 or later">
</p>

<p align="center">
  <a href="https://wordpress.org/plugins/kashida-hide-my-theme/"><strong>WordPress Plugin Directory</strong></a>
  &nbsp; • &nbsp;
  <a href="https://wordpress.org/support/plugin/kashida-hide-my-theme/"><strong>WordPress Support</strong></a>
  &nbsp; • &nbsp;
  <a href="https://github.com/mrwan-bg/kashida-hide-my-theme/issues"><strong>GitHub Issues</strong></a>
  &nbsp; • &nbsp;
  <a href="https://kashida.ly/"><strong>Kashida Media Services</strong></a>
</p>

---

## Overview

**Kashida Hide my theme** lets WordPress administrators customize selected public theme identity details and screenshot endpoints from the dashboard. It is designed to preserve the original theme files, directory names, stylesheet/template identifiers, site design, and theme update mechanisms.

The plugin supports active child themes and their parent themes where the server rewrite configuration and file paths allow it.

## Features

- **Custom Theme Identity** — Configure the public Theme Name, Version, Author, Description, and Theme URI.
- **Custom Screenshot and Identity Icon** — Select replacement images for supported WordPress administration displays.
- **Child and Parent Theme Handling** — Apply the configured identity to the active child theme and its parent theme.
- **Public Stylesheet Metadata Replacement** — Use Apache/LiteSpeed internal rewrite rules to serve a generated metadata copy for requests to the exact original `style.css` URL.
- **Original CSS Preservation** — Keep the browser-visible stylesheet URL unchanged and copy CSS rules after the original metadata comment without modification.
- **Public Screenshot Endpoint** — Replace the supported screenshot endpoint for active child and parent theme directories with the selected screenshot.
- **Verification Tool** — Run an optional authenticated admin-side loopback test for the public stylesheet metadata and selected screenshot.
- **Update-Friendly Design** — Do not edit original theme files, rename theme directories, or change stylesheet/template identifiers.
- **Controlled Rewrite Updates** — Synchronize managed rewrite rules on activation, theme switches, and settings changes—not on every admin request.

## Feature Screenshots

### Plugin overview
<p align="center">
  <img src="screen/screen%20a%20(1).png" alt="Kashida Hide my theme plugin overview" width="100%">
</p>

### Custom theme identity
<p align="center">
  <img src="screen/screen%20a%20(2).png" alt="Custom theme identity settings" width="100%">
</p>

### Supported identity controls
<p align="center">
  <img src="screen/screen%20a%20(3).png" alt="Theme identity controls" width="100%">
</p>

### Lightweight operation
<p align="center">
  <img src="screen/screen%20a%20(4).png" alt="Plugin operation and compatibility" width="100%">
</p>

## Requirements and Compatibility

| Requirement | Details |
|---|---|
| WordPress | 6.0 or later |
| PHP | 7.4 or later |
| Web server | Apache or LiteSpeed with `mod_rewrite` enabled and a writable `.htaccess` file |
| Theme setup | Active child theme and parent theme, subject to server/path support |
| License | GPLv2 or later |

**Not supported:** Nginx-only configurations and WordPress Multisite/network activation for this rewrite method. Custom theme roots or uploads directories outside the site's document root may not be supported automatically.

## Important Limitations

Kashida Hide my theme does **not** guarantee complete theme concealment. The original theme directory name remains visible in the `style.css` URL. CSS fingerprints, HTML signatures, plugin data, cached resources, and hard-coded asset paths may also reveal the original theme.

Public metadata replacement requires Apache or LiteSpeed with `mod_rewrite` and write access to `.htaccess`. If rewrite rules cannot be installed, the original stylesheet is served unchanged. A first-change backup is saved as `.htaccess.khm-bak` and intentionally retained after uninstall for manual recovery.

Actual behavior can also depend on filesystem permissions, server configuration, CDN/cache layers, and theme directory layout. Back up `.htaccess` before enabling the feature and use the built-in verification tool after configuration.

## Installation

**Recommended: install from the official WordPress Plugin Directory.**

1. In your WordPress dashboard, open **Plugins → Add New Plugin**.
2. Search for **Kashida Hide my theme**.
3. Select **Install Now**, then activate the plugin.
4. Open **Tools → Hide my theme**.
5. Configure the theme identity and screenshot, then save your settings.
6. Review the rewrite status and run the built-in public metadata verification tool.

You can also visit the [official WordPress.org plugin page](https://wordpress.org/plugins/kashida-hide-my-theme/) for installation and download options.

## Plugin Information

| Property | Value |
|---|---|
| Plugin name | Kashida Hide my theme |
| WordPress.org version | 1.5.6 |
| Developer | Kashida Media Services |
| Author | Mrwan elGobee |
| WordPress.org slug | `kashida-hide-my-theme` |
| Text domain | `kashida-hide-my-theme` |
| Requires WordPress | 6.0 or later |
| Requires PHP | 7.4 or later |
| Tested up to | WordPress 7.1 |
| License | GPLv2 or later |

## Support

- **WordPress.org support forum:** [Get support](https://wordpress.org/support/plugin/kashida-hide-my-theme/)
- **Bug reports and feature requests:** [Open a GitHub Issue](https://github.com/mrwan-bg/kashida-hide-my-theme/issues)
- **Plugin listing and updates:** [WordPress Plugin Directory](https://wordpress.org/plugins/kashida-hide-my-theme/)

When reporting a technical issue, include your WordPress version, PHP version, web server type, parent/child theme setup, and the result of the plugin's public metadata verification tool. Do not include passwords, private keys, or other sensitive data.

## Developer

**Kashida Media Services**  
Developer: **Mrwan elGobee**

- Website: [https://kashida.ly/](https://kashida.ly/)
- GitHub repository: [mrwan-bg/kashida-hide-my-theme](https://github.com/mrwan-bg/kashida-hide-my-theme)

---

<p align="center">
  <strong>Kashida Hide my theme</strong><br>
  Your website. Your public identity.
</p>
<p align="center">
  Developed by <a href="https://kashida.ly/">Kashida Media Services</a>
</p>

---

# النسخة العربية

## نبذة عن الإضافة

**Kashida Hide my theme** إضافة لووردبريس تتيح تخصيص بعض بيانات هوية القالب الظاهرة للعامة ونقاط عرض صورة القالب، من خلال لوحة التحكم، دون تعديل ملفات القالب الأصلية أو تغيير تصميم الموقع أو آلية تحديث القالب.

تدعم الإضافة القالب الابن النشط والقالب الأب المرتبط به، متى سمحت إعدادات إعادة الكتابة ومسارات الملفات على الخادم بذلك.

## المميزات الرئيسية

- **تخصيص هوية القالب:** تحديد اسم القالب وإصداره واسم المؤلف والوصف ورابط القالب الظاهر للعامة.
- **صورة وأيقونة مخصصتان:** اختيار صور بديلة في واجهات إدارة ووردبريس المدعومة.
- **دعم القالب الابن والأب:** تطبيق بيانات الهوية المخصصة على القالب الابن النشط والقالب الأب.
- **استبدال بيانات style.css العامة:** استخدام قواعد إعادة كتابة داخلية في Apache وLiteSpeed لتقديم نسخة مولّدة من بيانات الملف عند طلب عنوان `style.css` الأصلي المحدد.
- **الحفاظ على CSS الأصلي:** الإبقاء على عنوان ملف التنسيق الظاهر في المتصفح ونسخ قواعد CSS الموجودة بعد تعليق البيانات الوصفية الأصلي دون تعديل.
- **استبدال نقطة عرض صورة القالب:** استخدام الصورة المحددة في إعدادات الإضافة لنقاط عرض الصورة المدعومة للقالب الابن والأب النشطين.
- **أداة التحقق:** اختبار اختياري من لوحة الإدارة، يتطلب المصادقة، للتحقق من بيانات ملف التنسيق والصورة العامة.
- **الحفاظ على ملفات القالب وتحديثاته:** لا تعدّل الإضافة ملفات القالب الأصلية أو أسماء مجلداته أو معرّفات stylesheet/template.
- **إدارة قواعد إعادة الكتابة:** تتم مزامنة القواعد عند التفعيل وتغيير القالب وحفظ الإعدادات، وليس مع كل طلب إداري.

## صور توضيحية للمميزات

### نظرة عامة على الإضافة
<p align="center">
  <img src="screen/screen%20a%20(1).png" alt="نظرة عامة على إضافة Kashida Hide my theme" width="100%">
</p>

### تخصيص هوية القالب
<p align="center">
  <img src="screen/screen%20a%20(2).png" alt="إعدادات تخصيص هوية القالب" width="100%">
</p>

### عناصر الهوية المدعومة
<p align="center">
  <img src="screen/screen%20a%20(3).png" alt="خيارات التحكم في بيانات هوية القالب" width="100%">
</p>

### خفة التشغيل والحفاظ على القالب
<p align="center">
  <img src="screen/screen%20a%20(4).png" alt="خفة الإضافة والتوافق" width="100%">
</p>

## المتطلبات والتوافق

| المتطلب | التفاصيل |
|---|---|
| ووردبريس | 6.0 أو أحدث |
| PHP | 7.4 أو أحدث |
| خادم الويب | Apache أو LiteSpeed مع تفعيل `mod_rewrite` وإمكانية الكتابة إلى `.htaccess` |
| القوالب | القالب الابن النشط والقالب الأب، وفق دعم إعدادات الخادم والمسارات |
| الترخيص | GPLv2 أو أحدث |

**غير مدعوم:** خوادم Nginx فقط، وWordPress Multisite أو التفعيل على مستوى الشبكة باستخدام طريقة إعادة الكتابة هذه. وقد لا تكون مجلدات القوالب المخصصة أو مجلدات uploads الموجودة خارج جذر مستندات الموقع مدعومة تلقائياً.

## ملاحظات وقيود مهمة

لا تضمن الإضافة إخفاء هوية القالب بالكامل؛ إذ يظل اسم مجلد القالب الأصلي ظاهراً في عنوان ملف `style.css`. وقد تكشف بصمات CSS أو HTML وبيانات الإضافات والملفات المخزنة مؤقتاً ومسارات الأصول الثابتة عن القالب الأصلي.

يتطلب استبدال البيانات العامة خادماً يعمل بـ Apache أو LiteSpeed مع تفعيل `mod_rewrite` وإمكانية الكتابة إلى ملف `.htaccess`. إذا تعذّر تثبيت قواعد إعادة الكتابة، فسيُقدَّم ملف التنسيق الأصلي دون تغيير. تحتفظ الإضافة بنسخة احتياطية عند أول تعديل باسم `.htaccess.khm-bak`، ولا تحذفها عند إزالة الإضافة، لإتاحة الاستعادة اليدوية.

قد يعتمد الأداء الفعلي أيضاً على صلاحيات الملفات وإعدادات الخادم وطبقات CDN والتخزين المؤقت وطريقة تنظيم مجلدات القالب. يُنصح بإنشاء نسخة احتياطية من `.htaccess` قبل تفعيل الميزة واستخدام أداة التحقق المدمجة بعد الإعداد.

## التثبيت

**الطريقة الموصى بها: التثبيت من مستودع إضافات ووردبريس الرسمي.**

1. من لوحة تحكم ووردبريس، انتقل إلى **إضافات ← أضف إضافة جديدة**.
2. ابحث عن **Kashida Hide my theme**.
3. اضغط **تنصيب الآن** ثم فعّل الإضافة.
4. انتقل إلى **أدوات ← Hide my theme**.
5. أدخل بيانات هوية القالب واختر الصورة المخصصة، ثم احفظ الإعدادات.
6. راجع حالة قواعد إعادة الكتابة وشغّل أداة التحقق من البيانات العامة.

يمكنك أيضاً زيارة [صفحة الإضافة الرسمية على WordPress.org](https://wordpress.org/plugins/kashida-hide-my-theme/) للحصول على خيارات التثبيت والتنزيل.

## معلومات الإضافة

| الخاصية | التفاصيل |
|---|---|
| اسم الإضافة | Kashida Hide my theme |
| الإصدار المنشور على WordPress.org | 1.5.6 |
| المطوّر | Kashida Media Services |
| المؤلف | Mrwan elGobee |
| معرّف الإضافة | `kashida-hide-my-theme` |
| Text Domain | `kashida-hide-my-theme` |
| الحد الأدنى لووردبريس | 6.0 |
| الحد الأدنى لـ PHP | 7.4 |
| تم الاختبار حتى | WordPress 7.1 |
| الترخيص | GPLv2 أو أحدث |

## الدعم

- **منتدى دعم WordPress.org:** [الحصول على الدعم](https://wordpress.org/support/plugin/kashida-hide-my-theme/)
- **الإبلاغ عن مشكلة أو طلب ميزة:** [فتح تذكرة على GitHub](https://github.com/mrwan-bg/kashida-hide-my-theme/issues)
- **صفحة الإضافة والتحديثات:** [مستودع إضافات ووردبريس](https://wordpress.org/plugins/kashida-hide-my-theme/)

عند الإبلاغ عن مشكلة تقنية، يرجى ذكر إصدار ووردبريس وPHP ونوع خادم الويب وإعداد القالب الابن/الأب ونتيجة أداة التحقق من البيانات العامة. لا ترسل كلمات المرور أو المفاتيح الخاصة أو أي بيانات حساسة.

## المطوّر

**Kashida Media Services**  
المطوّر: **Mrwan elGobee**

- الموقع الإلكتروني: [https://kashida.ly/](https://kashida.ly/)
- مستودع GitHub: [mrwan-bg/kashida-hide-my-theme](https://github.com/mrwan-bg/kashida-hide-my-theme)

---

<p align="center">
  <strong>Kashida Hide my theme</strong><br>
  موقعك الإلكتروني. هويتك العامة.
</p>
<p align="center">
  تطوير <a href="https://kashida.ly/">Kashida Media Services</a>
</p>
