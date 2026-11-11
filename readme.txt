=== Kashida Hide my theme ===
Contributors: kashida
Tags: theme, branding, administration, theme-identity
Requires at least: 6.0
Requires PHP: 7.4
Tested up to: 7.1
Stable tag: 1.5.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Customize public theme identity without changing theme files, design, licensing, or updates.

== Description ==
Kashida Hide my theme provides a theme-agnostic public metadata replacement method designed to avoid changing the front-end design or functionality.

Features:
* Custom public Theme Name, Version, Author, Description and Theme URI.
* Custom screenshot and identity icon in supported WordPress administration displays.
* Generates a public style.css copy for the active child theme and parent theme.
* Replaces the public screenshot endpoint for active child and parent theme directories with the screenshot selected in plugin settings.
* Verifies the public style.css metadata response with an optional authenticated admin-side loopback test.
* Applies the custom identity consistently to both active child and parent theme entries in the WordPress Themes screen.
* Apache/LiteSpeed internally maps requests for the exact original style.css URL to the generated metadata copy.
* The browser-visible stylesheet URL stays unchanged.
* CSS rules after the original leading metadata comment are copied without modification.
* Original theme files, directory names, stylesheet/template identifiers and update mechanisms remain unchanged.
* No output buffering, HTML rewriting, CSS/JS URL rewriting, font/image URL rewriting, or direct theme-directory blocking.

Important limitations:
The original theme directory name remains visible in the style.css URL and may still be recognized by scanners. CSS fingerprints and other public signatures may also identify the theme. This plugin replaces the metadata header only; it does not promise complete concealment. The method requires Apache/LiteSpeed mod_rewrite and a writable .htaccess file. Custom theme roots or uploads directories outside the site's document root may not be supported automatically. If the rewrite rules cannot be installed, the original style.css is served unchanged.

== Languages ==
* Arabic translation is loaded automatically when the WordPress locale is Arabic (ar).
* The plugin interface remains English for sites/users using other locales.
* Translation files are included in the `languages` directory.

== Installation ==
1. Upload the `kashida-hide-my-theme` folder to `/wp-content/plugins/`.
2. Activate the plugin.
3. Open Tools > Hide my theme.
4. Configure the theme identity and save.
5. Verify the rewrite status. Back up .htaccess before enabling direct-path blocking.

== Changelog ==
= 1.5.1 =
* Fixed Apache/LiteSpeed rewrite ordering by placing the managed metadata block before WordPress front-controller rules.
* Replaces only the plugin-managed .htaccess block and preserves unrelated directives.
* Improves parent theme screenshot endpoint handling where earlier rewrite order allowed WordPress routing to intercept the request.

= 1.5.0 =
* Rebuilds managed rewrite rules immediately after settings are saved.
* Extends WordPress admin identity display to both active child and parent theme entries.
* Adds public metadata verification for active and parent style.css endpoints.
* Replaces theme screenshot endpoints even when the original theme folder does not contain a screenshot file.
* Omits vendor-specific text-domain and parent-template metadata from generated public stylesheet headers while preserving legal license and compatibility fields.

= 1.4.1 =
* Fixed public theme screenshot replacement for child and parent theme directory screenshot files.
* Added safe copies of the selected screenshot in uploads and exact Apache/LiteSpeed rules for screenshot endpoints.
* No front-end design assets or page-builder output are rewritten.

= 1.4.0 =
* Added public style.css metadata replacement with configurable Theme Name, Version, Author, Description and Theme URI.
* Added exact internal Apache/LiteSpeed rewrite rules for active child and parent theme style.css requests only.
* Preserved original stylesheet URLs and CSS rules.
* Removed front-end HTML buffering and all CSS/JS/image/font URL rewriting.
* Original theme files and update identifiers remain untouched.

= 1.3.1 =
* Disabled front-end URL rewriting and output buffering to restore theme compatibility.
* Retained safe admin-only theme identity customization.
