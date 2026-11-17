<?php
/**
 * Uninstall cleanup for Kashida Hide my theme.
 * The .htaccess backup is intentionally retained for manual recovery.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

( static function () {
	$khm_is_multisite = is_multisite();

	require_once ABSPATH . 'wp-admin/includes/file.php';
	global $wp_filesystem;

	if ( ! $wp_filesystem ) {
		WP_Filesystem();
	}

	if ( $wp_filesystem ) {
		if ( ! $khm_is_multisite ) {
			$khm_home = function_exists( 'get_home_path' ) ? get_home_path() : ABSPATH;
			$khm_htaccess = trailingslashit( $khm_home ) . '.htaccess';

			if ( $wp_filesystem->exists( $khm_htaccess ) && $wp_filesystem->is_readable( $khm_htaccess ) && $wp_filesystem->is_writable( $khm_htaccess ) ) {
				$khm_contents = $wp_filesystem->get_contents( $khm_htaccess );
	
				if ( false !== $khm_contents ) {
					$khm_begin   = '# BEGIN Kashida Hide my theme';
					$khm_end     = '# END Kashida Hide my theme';
					$khm_pattern = '/(?:\\r?\\n)?' . preg_quote( $khm_begin, '/' ) . '\\r?\\n.*?\\r?\\n' . preg_quote( $khm_end, '/' ) . '(?:\\r?\\n)?/s';
					$khm_clean   = preg_replace( $khm_pattern, '', $khm_contents, 1 );
	
					if ( is_string( $khm_clean ) ) {
						$khm_clean = preg_replace( "/\\n{3,}/", "\\n\\n", $khm_clean );
	
						if ( $khm_clean !== $khm_contents ) {
							$wp_filesystem->put_contents( $khm_htaccess, $khm_clean, FS_CHMOD_FILE );
						}
					}
				}
			}
		}

		$khm_uploads = wp_upload_dir();

		if ( empty( $khm_uploads['error'] ) && ! empty( $khm_uploads['basedir'] ) ) {
			$khm_generated = trailingslashit( $khm_uploads['basedir'] ) . 'kashida-hide-my-theme';

			if ( $wp_filesystem->is_dir( $khm_generated ) ) {
				$wp_filesystem->delete( $khm_generated, true, 'd' );
			}
		}
	}

	delete_option( 'khm_settings' );
	delete_option( 'khm_rewrite_status' );
	delete_option( 'khm_verification_result' );
	delete_option( 'khm_htaccess_write_lock' );
	delete_transient( 'khm_htaccess_write_lock' );
	if ( $khm_is_multisite ) {
		delete_site_option( 'khm_rewrite_status' );
	}
} )();
