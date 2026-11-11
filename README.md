<!--
  Kashida Hide My Theme
  Developed by Kashida Media Services
-->
<p align="center">
  <img src="screen/Hide%20My%20Theme%20Plugin%20Banner.png" alt="Kashida Hide My Theme — WordPress theme identity protection" width="100%">
</p>

<h1 align="center">
  <img src="screen/Kashida%20Hide%20My%20Theme%20Icon.png" alt="Kashida Hide My Theme icon" width="88"><br>
  Kashida Hide My Theme
</h1>

<p align="center">
  <strong>Customize the public identity of your WordPress theme without modifying the original theme files.</strong>
</p>

<p align="center">
  <a href="https://github.com/mrwan-bg/kashida-hide-my-theme/releases">
    <img src="https://img.shields.io/badge/Version-1.5.1-F15B40" alt="Version 1.5.1">
  </a>
  <img src="https://img.shields.io/badge/WordPress-6.0%2B-21759B?logo=wordpress&logoColor=white" alt="WordPress 6.0+">
  <img src="https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white" alt="PHP 7.4+">
  <img src="https://img.shields.io/badge/License-GPL--2.0--or--later-blue" alt="GPL-2.0-or-later">
</p>

<p align="center">
  <a href="https://kashida.ly/"><strong>Kashida Media Services</strong></a>
  &nbsp; • &nbsp;
  <a href="https://github.com/mrwan-bg/kashida-hide-my-theme/releases"><strong>Releases</strong></a>
  &nbsp; • &nbsp;
  <a href="https://github.com/mrwan-bg/kashida-hide-my-theme/issues"><strong>Support & Issues</strong></a>
</p>

---

## Overview

**Kashida Hide My Theme** is a lightweight WordPress plugin that lets administrators replace selected public theme identity metadata with custom information.

Set your own theme name, version, author, description, website URL, and screenshot. The plugin is designed to work with different WordPress themes, including parent and child theme setups, while leaving the original theme files, stylesheet/template identifiers, and vendor update mechanisms in place.

## Features

- **Custom Theme Identity** — Configure the public Theme Name, Version, Author, Description, and Theme URI.
- **Custom Theme Screenshot** — Select a replacement screenshot for supported public theme screenshot endpoints.
- **Parent & Child Theme Handling** — Apply the configured identity to the active child theme and its parent theme.
- **Public Stylesheet Metadata Replacement** — Internally map requests for the exact original `style.css` URL to a generated stylesheet copy containing the custom metadata.
- **Original CSS Preservation** — Keep the browser-visible stylesheet URL unchanged and preserve the CSS rules following the metadata header.
- **WordPress Admin Identity** — Customize the theme name and screenshot shown in supported WordPress administration views.
- **Public Metadata Verification** — Check the public stylesheet metadata and screenshot endpoints for the active and parent themes.
- **Apache / LiteSpeed Support** — Uses plugin-managed rewrite rules for the supported endpoints.
- **Update-Friendly Design** — Does not rename theme directories or change stylesheet/template identifiers used by the theme update process.
- **Arabic Translation** — Includes Arabic PO/MO translation files and a POT template.

## Feature Overview

<p align="center">
  <img src="screen/screen%20a%20(1).png" alt="Kashida Hide My Theme feature overview: theme and plugin information privacy, detection reduction, and custom replacements" width="100%">
</p>

## Custom Theme Identity

Configure your replacement theme identity and screenshot. The public response can show your selected details instead of the original theme metadata.

<p align="center">
  <img src="screen/screen%20a%20(2).png" alt="Custom theme identity settings and before-and-after public theme details" width="100%">
</p>

## Theme Information Privacy Controls

Choose which supported theme and site details should be concealed or replaced, including theme information and generator metadata.

<p align="center">
  <img src="screen/screen%20a%20(3).png" alt="Before-and-after theme information privacy controls" width="100%">
</p>

## Lightweight & Safe Operation

The plugin is designed to avoid modifying the original theme source files, page layouts, CSS/JavaScript assets, or page-builder output.

<p align="center">
  <img src="screen/screen%20a%20(4).png" alt="Lightweight operation and compatibility of Kashida Hide My Theme" width="100%">
</p>

## Installation

1. Download the latest plugin ZIP from the [Releases](https://github.com/mrwan-bg/kashida-hide-my-theme/releases) page.
2. In WordPress, go to **Plugins → Add New Plugin → Upload Plugin**.
3. Select the ZIP file and click **Install Now**.
4. Activate **Kashida Hide my theme**.
5. Open **Tools → Hide my theme**.
6. Configure the custom theme identity and screenshot, then save.
7. Run **Verify public metadata now** to check the supported public endpoints.

## Compatibility

| Requirement | Details |
|---|---|
| WordPress | 6.0 or later |
| PHP | 7.4 or later |
| Web server | Apache / LiteSpeed with supported rewrite and `.htaccess` access |
| Theme setup | Designed for parent themes and child themes |
| Page builders | Avoids rewriting normal front-end asset URLs and builder output |

Actual behavior depends on server rewrite configuration, filesystem permissions, caching layers, CDN behavior, and theme directory layout. Always use the built-in public metadata verification after configuration.

## Important Limitations

This plugin replaces selected metadata exposed through the exact endpoints it supports. It cannot guarantee that every external theme detector will fail to identify the original theme. Detection services may also inspect theme directory paths, CSS/HTML fingerprints, distinctive assets, or cached scan results.

The original theme directory name remains visible in the stylesheet URL and may still reveal the theme. This plugin does not rename theme folders, edit original theme files, change vendor update identifiers, or replace the theme's licensing and update connection.

The rewrite method requires the supported Apache/LiteSpeed configuration. If the required rewrite rules cannot be installed, the original stylesheet response may remain visible. Caches and CDNs may also need to be purged before retesting.

## Plugin Information

| Property | Value |
|---|---|
| Plugin | Kashida Hide my theme |
| Current version | 1.5.1 |
| Developer | Kashida Media Services |
| Author | Mrwan elGobee |
| Website | [kashida.ly](https://kashida.ly/) |
| Requires WordPress | 6.0+ |
| Requires PHP | 7.4+ |
| Text Domain | `kashida-hide-my-theme` |
| License | GPL-2.0-or-later |

## Repository Structure

```text
kashida-hide-my-theme/
├── assets/
│   ├── admin.css
│   ├── admin.js
│   ├── kashida-logo.svg
│   └── kashida-mark.svg
├── languages/
│   ├── kashida-hide-my-theme-ar.mo
│   ├── kashida-hide-my-theme-ar.po
│   └── kashida-hide-my-theme.pot
├── screen/
│   ├── Kashida Hide My Theme Icon.png
│   ├── Hide My Theme Plugin Banner.png
│   ├── screen a (1).png
│   ├── screen a (2).png
│   ├── screen a (3).png
│   └── screen a (4).png
├── kashida-hide-my-theme.php
├── readme.txt
└── README.md
```

## Support

For bug reports, compatibility questions, or feature requests, open a [GitHub Issue](https://github.com/mrwan-bg/kashida-hide-my-theme/issues).

Please include your WordPress version, PHP version, web server type, parent/child theme setup, and the result from the plugin's public metadata verification tool.

## Developer

**Kashida Media Services**  
Developer: **Mrwan elGobee**

- Website: [https://kashida.ly/](https://kashida.ly/)
- GitHub: [@mrwan-bg](https://github.com/mrwan-bg)

---

<p align="center">
  <strong>Kashida Hide My Theme</strong><br>
  Your website. Your public identity.
</p>

<p align="center">
  Developed by <a href="https://kashida.ly/">Kashida Media Services</a>
</p>


---

# النسخة العربية

## نبذة عن الإضافة

**Kashida Hide My Theme** إضافة لووردبريس تتيح لك تخصيص بيانات الهوية العامة للقالب (Theme) التي تظهر في بعض الاستجابات والواجهات العامة للموقع، واستبدالها ببيانات تختارها بنفسك.

يمكنك تحديد اسم القالب، والإصدار، واسم المؤلف، والوصف، ورابط الموقع، وصورة القالب. صُممت الإضافة للعمل مع قوالب ووردبريس المختلفة، بما في ذلك القوالب الرئيسية والفرعية (Parent / Child Themes)، دون الحاجة إلى تعديل ملفات القالب الأصلي أو تغيير معرّفاته المستخدمة في آلية التحديث.

## المميزات الرئيسية

- **هوية مخصصة للقالب:** تحديد اسم القالب والإصدار والمؤلف والوصف ورابط القالب الذي تريد إظهاره.
- **صورة مخصصة للقالب:** اختيار صورة بديلة تظهر عبر نقاط الوصول المدعومة لصورة القالب.
- **دعم القوالب الرئيسية والفرعية:** تطبيق بيانات الهوية المخصصة على القالب النشط والقالب الرئيسي المرتبط به، وفق آلية الإضافة.
- **استبدال بيانات style.css العامة:** معالجة طلبات ملف `style.css` المحدد لتقديم بيانات تعريف مخصصة، مع الحفاظ على عنوان الملف الأصلي الظاهر للمتصفح.
- **الحفاظ على تنسيقات CSS:** الإبقاء على قواعد التنسيق الموجودة بعد ترويسة بيانات التعريف دون تغييرها.
- **تخصيص العرض في لوحة التحكم:** تغيير اسم القالب وصورته في مواضع الإدارة التي تدعمها الإضافة.
- **التحقق من البيانات العامة:** فحص استجابات بيانات ملف التنسيق وصورة القالب للقالب النشط والقالب الرئيسي.
- **التوافق مع Apache وLiteSpeed:** استخدام قواعد إعادة الكتابة التي تديرها الإضافة لنقاط الوصول المدعومة.
- **الحفاظ على آلية تحديث القالب:** لا تعيد الإضافة تسمية مجلد القالب ولا تغيّر معرّفات القالب المستخدمة في عملية التحديث.
- **الترجمة العربية:** تتضمن ملفات ترجمة عربية بصيغ PO وMO، بالإضافة إلى ملف POT.

## شرح صور ومميزات الإضافة

### 1. نظرة عامة على المميزات

توضح الصورة إمكانيات الإضافة في تخصيص بيانات القالب وتقليل المعلومات التعريفية الظاهرة، مع توفير خيارات لاستبدال البيانات بمعلوماتك الخاصة.

<p align="center">
  <img src="screen/screen%20a%20(1).png" alt="نظرة عامة على مميزات إضافة Kashida Hide My Theme" width="100%">
</p>

### 2. تخصيص هوية القالب

يمكنك إدخال بيانات الهوية البديلة، مثل اسم القالب والإصدار والمؤلف ورابط الموقع والوصف، واختيار صورة مخصصة للقالب لتظهر بدلاً من الصورة الأصلية في نقاط الوصول المدعومة.

<p align="center">
  <img src="screen/screen%20a%20(2).png" alt="تخصيص بيانات وصورة هوية القالب" width="100%">
</p>

### 3. التحكم في معلومات القالب والخصوصية

توضح الصورة فكرة التحكم في المعلومات التي تظهر عن الموقع، مثل اسم القالب وإصداره وبعض بيانات ووردبريس والإضافات ووسوم التعريف، بحسب الخيارات ونقاط الإخراج التي تدعمها الإضافة.

<p align="center">
  <img src="screen/screen%20a%20(3).png" alt="التحكم في معلومات القالب والبيانات التعريفية" width="100%">
</p>

### 4. خفيفة وآمنة على القالب

تعمل الإضافة كطبقة مستقلة لإدارة البيانات التعريفية المدعومة. وهي لا تتطلب تعديل ملفات القالب الأصلية أو تغيير تصميم الصفحات أو ملفات CSS وJavaScript الخاصة بالقالب، كما تهدف إلى عدم التدخل في مخرجات أدوات بناء الصفحات.

<p align="center">
  <img src="screen/screen%20a%20(4).png" alt="خفة الإضافة والتوافق مع القالب وووردبريس" width="100%">
</p>

## طريقة التثبيت والاستخدام

1. حمّل أحدث ملف ZIP من صفحة [الإصدارات Releases](https://github.com/mrwan-bg/kashida-hide-my-theme/releases).
2. من لوحة تحكم ووردبريس، انتقل إلى **إضافات ← أضف إضافة جديدة ← رفع إضافة**.
3. اختر ملف ZIP ثم اضغط **التنصيب الآن**.
4. فعّل إضافة **Kashida Hide my theme**.
5. انتقل إلى **أدوات ← Hide my theme**.
6. أدخل بيانات هوية القالب البديلة، واختر الصورة المخصصة، ثم احفظ الإعدادات.
7. استخدم خيار **Verify public metadata now** للتحقق من استجابات البيانات العامة المدعومة.

## التوافق والمتطلبات

| المتطلب | الإصدار / التفاصيل |
|---|---|
| ووردبريس | 6.0 أو أحدث |
| PHP | 7.4 أو أحدث |
| خادم الويب | Apache أو LiteSpeed مع دعم قواعد إعادة الكتابة وإمكانية الوصول إلى `.htaccess` |
| أنواع القوالب | مصممة لدعم القوالب الرئيسية والفرعية |
| أدوات بناء الصفحات | تتجنب إعادة كتابة روابط ملفات الواجهة الأمامية العادية ومخرجات أدوات البناء |

يعتمد نجاح الاستبدال على إعدادات إعادة الكتابة في الخادم، وصلاحيات الملفات، وأنظمة التخزين المؤقت، وشبكات CDN، وطريقة تنظيم ملفات القالب. يُنصح دائماً باستخدام أداة التحقق المدمجة بعد ضبط الإعدادات.

## ملاحظات وحدود مهمة

تستبدل الإضافة بيانات تعريفية محددة عبر نقاط الوصول التي تدعمها، لكنها **لا تضمن منع جميع أدوات فحص القوالب من اكتشاف القالب الأصلي بشكل كامل**. فقد تعتمد بعض أدوات الفحص على مسار مجلد القالب أو بصمات ملفات CSS وHTML أو الصور والملفات المميزة أو نتائج فحص مخزنة مسبقاً.

يبقى اسم مجلد القالب الأصلي ظاهراً ضمن رابط ملف التنسيق، وقد يكشف عن القالب المستخدم. لا تعيد الإضافة تسمية مجلد القالب، ولا تعدّل ملفاته الأصلية، ولا تغيّر معرّفات التحديث أو آلية الترخيص والاتصال بموقع الشركة المطوّرة للقالب.

تعتمد آلية إعادة الكتابة على إعدادات Apache أو LiteSpeed المدعومة. إذا تعذّر تثبيت قواعد إعادة الكتابة المطلوبة، فقد تستمر الاستجابة الأصلية لملف التنسيق في الظهور. وقد تحتاج أيضاً إلى مسح ذاكرة التخزين المؤقت وذاكرة CDN قبل إعادة الاختبار.

## معلومات الإضافة

| الخاصية | التفاصيل |
|---|---|
| اسم الإضافة | Kashida Hide my theme |
| الإصدار | 1.5.1 |
| المطوّر | Kashida Media Services |
| المؤلف | Mrwan elGobee |
| الموقع | [kashida.ly](https://kashida.ly/) |
| الحد الأدنى لووردبريس | 6.0 |
| الحد الأدنى لـ PHP | 7.4 |
| Text Domain | `kashida-hide-my-theme` |
| الترخيص | GPL-2.0-or-later |

## الدعم والملاحظات الفنية

للإبلاغ عن مشكلة أو طلب ميزة، يرجى فتح [تذكرة دعم على GitHub](https://github.com/mrwan-bg/kashida-hide-my-theme/issues)، مع إرفاق المعلومات التالية لتسهيل التشخيص:

- إصدار ووردبريس.
- إصدار PHP.
- نوع خادم الويب.
- توضيح ما إذا كان الموقع يستخدم قالباً رئيسياً أو قالباً فرعياً.
- نتيجة أداة التحقق من البيانات العامة داخل الإضافة.

## المطوّر

**Kashida Media Services**  
المطوّر: **Mrwan elGobee**

- الموقع الإلكتروني: [https://kashida.ly/](https://kashida.ly/)
- GitHub: [@mrwan-bg](https://github.com/mrwan-bg)

---

<p align="center">
  <strong>Kashida Hide My Theme</strong><br>
  موقعك الإلكتروني. هويتك العامة.
</p>

<p align="center">
  تطوير <a href="https://kashida.ly/">Kashida Media Services</a>
</p>
