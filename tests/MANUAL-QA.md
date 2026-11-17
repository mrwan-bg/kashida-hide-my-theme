# Kashida Hide My Theme — Manual QA Checklist

Run these checks on a disposable staging WordPress installation. Back up `.htaccess` before testing.

## Environment matrix
- [ ] WordPress 6.0 and the latest supported WordPress release.
- [ ] PHP 7.4 and a current PHP 8.x release.
- [ ] Apache with `mod_rewrite`.
- [ ] LiteSpeed with `mod_rewrite`.
- [ ] Confirm that an Nginx-only environment is reported as unsupported without modifying server configuration.

## Theme scenarios
- [ ] Active parent theme: enable replacement and verify the public `style.css` metadata.
- [ ] Active child theme: verify both child and parent public endpoints.
- [ ] Child theme with an optional custom display name left empty: verification must use the actual theme name and pass when rewrite rules are active.
- [ ] Child theme: confirm the generated public stylesheet does not contain a `Template:` header or disclose the parent theme directory slug in its metadata header.
- [ ] Switch the active theme and confirm rules are rebuilt for the newly active theme.
- [ ] Update a theme and confirm generated metadata is rebuilt.

## Rewrite and filesystem safety
- [ ] Confirm only the exact theme `style.css` and supported screenshot endpoints are mapped.
- [ ] Confirm original theme files are byte-for-byte unchanged.
- [ ] Confirm normal CSS, JavaScript, fonts, images, page-builder output, and content URLs are unaffected.
- [ ] Confirm unrelated `.htaccess` directives and WordPress rewrite rules are preserved.
- [ ] Confirm the first-change backup `.htaccess.khm-bak` is created and not overwritten.
- [ ] Test unwritable/missing `.htaccess`, uploads directory, and generated-file permissions; verify a clear failure status and no fatal error.
- [ ] Disable the feature and confirm only the plugin-managed rewrite block is removed.
- [ ] Deactivate the plugin and confirm its managed rules are removed where filesystem permissions allow.
- [ ] Uninstall and confirm generated files/options are removed while the recovery backup is retained.

## Admin and security
- [ ] Confirm only users with `manage_options` can save settings or run metadata verification.
- [ ] Confirm verification requires a valid authenticated POST nonce.
- [ ] Confirm media selections reject non-image attachments and attachments the user cannot edit.
- [ ] Confirm Multisite and network activation are reported unsupported and the shared `.htaccess` is not modified.

## Release checks
- [ ] Run WordPress Plugin Check against the release ZIP.
- [ ] Run PHP syntax checks on all PHP files.
- [ ] Confirm POT/PO message IDs match the strings used by the plugin.
- [ ] Compile the Arabic PO file into MO and verify Arabic strings on an Arabic-locale site.
- [ ] Install, activate, configure, disable, deactivate, and uninstall the actual release ZIP.
