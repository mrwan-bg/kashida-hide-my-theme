=== Kashida Hide my theme ===
Contributors: kashida
Tags: theme, branding, administration, theme-identity
Requires at least: 6.0
Requires PHP: 7.4
Tested up to: 7.1
Stable tag: 1.5.5
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
* Verifies the public style.css metadata response and selected screenshot with an optional authenticated admin-side loopback test.
* Applies the custom name, author, version, description and screenshot to both active child and parent theme entries in the WordPress Themes screen.
* Apache/LiteSpeed internally maps requests for the exact original style.css URL to the generated metadata copy.
* The browser-visible stylesheet URL stays unchanged.
* CSS rules after the original leading metadata comment are copied without modification.
* Original theme files, directory names, stylesheet/template identifiers and update mechanisms remain unchanged.
* No output buffering, HTML rewriting, CSS/JS URL rewriting, font/image URL rewriting, or direct theme-directory blocking.
* Rewrite rules are synchronized on activation, theme switches, and settings changes—not on every admin request.

Important limitations:
The original theme directory name remains visible in the style.css URL and may still be recognized by scanners. CSS fingerprints and other public signatures may also identify the theme. This plugin replaces the metadata header only; it does not promise complete concealment. Public metadata replacement requires Apache or LiteSpeed with mod_rewrite and a writable .htaccess file. Nginx-only servers and Multisite/network activation are not supported. Custom theme roots or uploads directories outside the site's document root may not be supported automatically. If rewrite rules cannot be installed, the original style.css is served unchanged. A first-change backup is saved as .htaccess.khm-bak and intentionally retained after uninstall for recovery.

== Languages ==
* Arabic translation is loaded automatically when the WordPress locale is Arabic (ar).
* The plugin interface remains English for sites/users using other locales.
* Translation files are included in the `languages` directory.

== Installation ==
1. Upload the `kashida-hide-my-theme` folder to `/wp-content/plugins/`.
2. Activate the plugin.
3. Open Tools > Hide my theme.
4. Configure the theme identity and save.
5. Review the rewrite status and use the built-in public metadata verification tool. Back up .htaccess before enabling the feature.

== Changelog ==

= 1.5.5 =
* Omit the parent-theme Template header from generated public stylesheets so the parent directory slug is not disclosed in the replacement metadata response.
* Verify the effective theme name when the optional custom display name is empty.
* Align public documentation with the implemented theme identity and stylesheet/screenshot endpoints; remove unsupported generator-metadata claims.
* Refresh the Arabic documentation and translation catalog metadata.
* Add a manual QA checklist and automated PHP syntax checks across supported PHP versions.

= 1.5.4 =
* Encapsulated uninstall cleanup in a static closure so its working variables are local rather than global-scope variables.
* Prefer WordPress.org language packs over the bundled translation file, with the bundled file retained as a fallback.
* Synchronize rewrite rules when the settings option is first created as well as when it is updated.
* Replace the transient-based .htaccess lock with an atomic option lock and stale-lock recovery.
* Keep Multisite uninstall cleanup scoped to the current site and avoid modifying the network-shared .htaccess file.
* Compare screenshot source and generated copies through the WordPress Filesystem API.
* Preserve the recovery backup and remove the plugin lock during uninstall.

= 1.5.3 =
* Resolved Plugin Check warnings for discouraged translation loading, unprefixed SSL hooks, admin query handling, and uninstall variable naming.
* Load bundled site-locale translations with load_textdomain() only when the matching MO file exists.
* Keep SSL certificate verification enabled by default.
* Removed the custom settings-updated GET notice; settings remain managed by the WordPress Settings API.

= 1.5.2 =
* Fixes plugin header URI metadata and adds a separate configurable public Author URI.
* Adds synchronization after theme updates and retains synchronization on activation, settings changes, and theme switches.
* Adds safe uninstall cleanup for plugin options, generated files, and the plugin-managed .htaccess block; the recovery backup is retained.
* Detects unsupported Nginx-only and Multisite environments and reports that rewrite-based metadata replacement is unavailable.
* Wraps rewrite rules in an mod_rewrite module check and adds a first-change .htaccess backup plus a short write lock.
* Uses POST with an authenticated nonce for the public metadata verification action.
* Restores normal WordPress translation loading so WordPress.org language packs are not blocked.
* Preserves a valid child-theme Template header and only removes an initial stylesheet comment when it contains Theme Name metadata.
* Supports additional screenshot file extensions and refreshes generated screenshot copies when the source changes.

= 1.5.1 =
* Avoids rewriting .htaccess when the managed rules have not changed.
* Synchronizes rewrite rules only on activation, settings updates, and theme switches.
* Uses the WordPress Filesystem API for generated stylesheet and screenshot files.
* Localizes media-library JavaScript strings through WordPress translation functions.
* Keeps the original Author URI separate from the configurable Theme URI.
* Corrects cleanup status reporting when managed rewrite rules cannot be removed.
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
