<?php
/**
 * Plugin Name: Kashida Hide my theme
 * Plugin URI: https://kashida.ly/
 * Description: Replace public theme stylesheet metadata safely while preserving original theme files, asset URLs, layouts, and updates.
 * Version: 1.5.1
 * Author: Kashida Media Services
 * Author URI: https://kashida.ly/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: kashida-hide-my-theme
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KHM_VERSION', '1.5.1' );
define( 'KHM_FILE', __FILE__ );
define( 'KHM_URL', plugin_dir_url( __FILE__ ) );

final class Kashida_Hide_My_Theme {

	const OPTION = 'khm_settings';
	const MARKER = 'Kashida Hide my theme';

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Load the plugin translation according to the WordPress site locale.
		add_action( 'plugins_loaded', array( $this, 'load_site_locale_translation' ), 20 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'sync_rewrite_rules' ), 40 );
		add_action( 'update_option_' . self::OPTION, array( $this, 'on_settings_updated' ), 10, 2 );
		add_action( 'after_switch_theme', array( $this, 'sync_rewrite_rules' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'dashboard_widget' ) );

		add_filter( 'wp_prepare_themes_for_js', array( $this, 'filter_themes_for_admin' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( KHM_FILE ), array( $this, 'plugin_action_links' ) );
	}


	/**
	 * Load this plugin's translation using the site locale, not the admin user's
	 * personal locale. Arabic sites receive Arabic strings; other locales use
	 * the original English strings. WordPress.org can still package language
	 * files normally without requiring a manual plugin text-domain loader.
	 */
	public function load_site_locale_translation() {
		$domain = 'kashida-hide-my-theme';
		$locale = function_exists( 'get_locale' ) ? (string) get_locale() : 'en_US';
		$is_arabic = ( 0 === strpos( strtolower( $locale ), 'ar' ) );

		// Clear a translation loaded earlier under a different locale.
		unload_textdomain( $domain );

		if ( $is_arabic ) {
			$mofile = plugin_dir_path( KHM_FILE ) . 'languages/' . $domain . '-ar.mo';
			if ( is_readable( $mofile ) ) {
				load_textdomain( $domain, $mofile );
			}
		}

		// Stop just-in-time loading from replacing the site-language choice
		// with an individual administrator's profile language.
		add_filter( 'override_load_textdomain', array( $this, 'keep_site_locale_translation' ), 10, 3 );
	}

	/**
	 * Keep this plugin's translation fixed to the WordPress site locale.
	 *
	 * @param bool   $override Whether to short-circuit loading.
	 * @param string $domain   Text domain.
	 * @param string $mofile   Translation file path.
	 * @return bool
	 */
	public function keep_site_locale_translation( $override, $domain, $mofile ) {
		if ( 'kashida-hide-my-theme' === $domain ) {
			return true;
		}
		return $override;
	}


	/**
	 * Rebuild the managed metadata rules after Settings API has persisted changes.
	 *
	 * @param mixed $old_value Previous settings.
	 * @param mixed $new_value New settings.
	 */
	public function on_settings_updated( $old_value, $new_value ) {
		if ( current_user_can( 'manage_options' ) ) {
			$this->sync_rewrite_rules();
		}
	}

	public static function deactivate() {
		self::write_rewrite_rules( array() );
	}


	private function defaults() {
		return array(
			'theme_name'        => '',
			'theme_version'     => '1.0.0',
			'theme_author'      => 'Kashida Media Services',
			'theme_description' => 'A professional WordPress theme identity by Kashida Media Services.',
			'theme_uri'         => 'https://kashida.ly/',
			'screenshot_id'     => 0,
			'icon_id'           => 0,
			'enabled'           => 1,
		);
	}

	private function settings() {
		$value = get_option( self::OPTION, array() );
		return wp_parse_args( is_array( $value ) ? $value : array(), $this->defaults() );
	}

	/**
	 * The settings apply to whichever theme is active right now.
	 * No theme name, directory, vendor, or slug is hard-coded or bound
	 * to the theme that happened to be active when settings were saved.
	 */
	private function active_profile() {
		$s = $this->settings();
		return ! empty( $s['enabled'] );
	}

	public function admin_menu() {
		add_management_page(
			__( 'Kashida Hide my theme', 'kashida-hide-my-theme' ),
			__( 'Hide my theme', 'kashida-hide-my-theme' ),
			'manage_options',
			'kashida-hide-my-theme',
			array( $this, 'settings_page' )
		);
	}

	public function register_settings() {
		register_setting(
			'khm_settings_group',
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => $this->defaults(),
			)
		);
	}

	public function sanitize_settings( $input ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return $this->settings();
		}

		$input = is_array( $input ) ? $input : array();
		return array(
			'theme_name'        => isset( $input['theme_name'] ) ? sanitize_text_field( $input['theme_name'] ) : '',
			'theme_version'     => isset( $input['theme_version'] ) ? sanitize_text_field( $input['theme_version'] ) : '1.0.0',
			'theme_author'      => isset( $input['theme_author'] ) ? sanitize_text_field( $input['theme_author'] ) : 'Kashida Media Services',
			'theme_description' => isset( $input['theme_description'] ) ? sanitize_textarea_field( $input['theme_description'] ) : '',
			'theme_uri'         => isset( $input['theme_uri'] ) ? esc_url_raw( $input['theme_uri'] ) : '',
			'screenshot_id'     => isset( $input['screenshot_id'] ) ? $this->validate_image( $input['screenshot_id'] ) : 0,
			'icon_id'           => isset( $input['icon_id'] ) ? $this->validate_image( $input['icon_id'] ) : 0,
			'enabled'            => ! empty( $input['enabled'] ) ? 1 : 0,
		);
	}

	private function validate_image( $id ) {
		$id = absint( $id );
		if ( ! $id || ! wp_attachment_is_image( $id ) || ! current_user_can( 'edit_post', $id ) ) {
			return 0;
		}
		return $id;
	}

	public function admin_assets( $hook ) {
		if ( 'tools_page_kashida-hide-my-theme' !== $hook ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_style( 'khm-admin', KHM_URL . 'assets/admin.css', array(), KHM_VERSION );
		wp_enqueue_script( 'khm-admin', KHM_URL . 'assets/admin.js', array( 'jquery' ), KHM_VERSION, true );
	}

	public function plugin_action_links( $links ) {
		array_unshift(
			$links,
			'<a href="' . esc_url( admin_url( 'tools.php?page=kashida-hide-my-theme' ) ) . '">' .
			esc_html__( 'Settings', 'kashida-hide-my-theme' ) . '</a>'
		);
		return $links;
	}

	private function image_url( $id ) {
		if ( ! $id || ! wp_attachment_is_image( $id ) ) {
			return '';
		}
		$url = wp_get_attachment_image_url( $id, 'full' );
		return $url ? esc_url( $url ) : '';
	}

	public function filter_themes_for_admin( $themes ) {
		if ( ! $this->active_profile() || ! is_array( $themes ) ) {
			return $themes;
		}

		$s = $this->settings();
		$slugs = array_unique( array_filter( array( get_stylesheet(), get_template() ) ) );
		$url = $this->image_url( absint( $s['screenshot_id'] ) );

		foreach ( $slugs as $slug ) {
			if ( ! isset( $themes[ $slug ] ) || ! is_array( $themes[ $slug ] ) ) {
				continue;
			}

			if ( $s['theme_name'] ) {
				$themes[ $slug ]['name'] = $s['theme_name'];
			}
			if ( $url ) {
				$themes[ $slug ]['screenshot'] = array( $url );
			}
		}

		return $themes;
	}

	/**
	 * Get the public URL path of WP_CONTENT_DIR relative to the site's home URL.
	 * Using the site's own origin avoids sending rewrite aliases to a CDN host.
	 *
	 * @return string Relative URL path, or empty when it cannot be mapped safely.
	 */

	/**
	 * Return a safe path relative to the WordPress home directory.
	 */
	private function relative_to_home( $path ) {
		if ( ! function_exists( 'get_home_path' ) && is_admin() ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		$home = function_exists( 'get_home_path' ) ? get_home_path() : ABSPATH;
		$real_home = realpath( $home );
		$real_path = realpath( $path );
		if ( ! $real_home || ! $real_path ) {
			return '';
		}
		$prefix = trailingslashit( wp_normalize_path( $real_home ) );
		$real_path = wp_normalize_path( $real_path );
		if ( 0 !== strpos( trailingslashit( $real_path ), $prefix ) ) {
			return '';
		}
		return ltrim( substr( $real_path, strlen( $prefix ) ), '/' );
	}

	/**
	 * Build a public style.css copy containing plugin-defined theme metadata.
	 * The CSS body is copied byte-for-byte; the original theme file is untouched.
	 *
	 * @param string $slug Theme directory slug.
	 * @param bool   $use_custom Whether to use the custom public identity.
	 * @return string Relative path to the generated file, or empty on failure.
	 */
	private function build_public_stylesheet( $slug, $use_custom = false ) {
		$source = trailingslashit( get_theme_root( $slug ) ) . $slug . '/style.css';
		if ( ! is_readable( $source ) ) {
			return '';
		}

		$uploads = wp_upload_dir();
		if ( ! empty( $uploads['error'] ) || empty( $uploads['basedir'] ) ) {
			return '';
		}

		$theme = wp_get_theme( $slug );
		$s = $this->settings();

		$metadata = array(
			'Theme Name'        => $use_custom && $s['theme_name'] ? $s['theme_name'] : $theme->get( 'Name' ),
			'Theme URI'         => $use_custom && $s['theme_uri'] ? $s['theme_uri'] : $theme->get( 'ThemeURI' ),
			'Author'            => $use_custom && $s['theme_author'] ? $s['theme_author'] : $theme->get( 'Author' ),
			'Author URI'        => $use_custom && $s['theme_uri'] ? $s['theme_uri'] : $theme->get( 'AuthorURI' ),
			'Description'       => $use_custom && $s['theme_description'] ? $s['theme_description'] : $theme->get( 'Description' ),
			'Version'           => $use_custom && $s['theme_version'] ? $s['theme_version'] : $theme->get( 'Version' ),
			// Keep legal and compatibility declarations accurate; omit vendor-specific
			// text domain, domain path, tags, and parent-template metadata.
			'License'           => $theme->get( 'License' ),
			'License URI'       => $theme->get( 'LicenseURI' ),
			'Requires at least' => $theme->get( 'RequiresWP' ),
			'Requires PHP'      => $theme->get( 'RequiresPHP' ),
		);

		$header = "/*\n";
		foreach ( $metadata as $label => $value ) {
			if ( is_scalar( $value ) && '' !== trim( (string) $value ) ) {
				$value = str_replace( array( "\r", "\n", "*/" ), array( ' ', ' ', '' ), (string) $value );
				$header .= $label . ': ' . $value . "\n";
			}
		}
		$header .= "*/\n";

		$token = substr( hash_hmac( 'sha256', $slug . '|' . ( $use_custom ? 'custom' : 'native' ), wp_salt( 'auth' ) ), 0, 20 );
		$directory = trailingslashit( $uploads['basedir'] ) . 'kashida-hide-my-theme/' . $token;
		if ( ! wp_mkdir_p( $directory ) ) {
			return '';
		}

		$target = trailingslashit( $directory ) . 'style.css';
		$css = file_get_contents( $source );
		if ( false === $css ) {
			return '';
		}

		// Strip only the initial theme metadata comment. Keep all CSS rules and source content.
		$body = preg_replace( '/\A(?:\xEF\xBB\xBF)?\s*\/\*[\s\S]*?\*\/\s*/', '', $css, 1 );
		if ( ! is_string( $body ) ) {
			return '';
		}

		$content = $header . "\n" . $body;
		if ( ! file_exists( $target ) || false === file_get_contents( $target ) || hash_file( 'sha256', $target ) !== hash( 'sha256', $content ) ) {
			if ( false === file_put_contents( $target, $content, LOCK_EX ) ) {
				return '';
			}
		}

		return $this->relative_to_home( $target );
	}

	/**
	 * Create a public copy of the selected theme screenshot.
	 * This image is used only by theme directories' screenshot.png metadata
	 * endpoint; it does not touch front-end layout or content images.
	 *
	 * @return string Relative path to the copied image, or empty on failure.
	 */
	private function build_public_screenshot() {
		$s = $this->settings();
		$attachment_id = absint( $s['screenshot_id'] );
		if ( ! $attachment_id || ! wp_attachment_is_image( $attachment_id ) ) {
			return '';
		}

		$source = get_attached_file( $attachment_id );
		if ( ! $source || ! is_readable( $source ) ) {
			return '';
		}

		$uploads = wp_upload_dir();
		if ( ! empty( $uploads['error'] ) || empty( $uploads['basedir'] ) ) {
			return '';
		}

		$mime = get_post_mime_type( $attachment_id );
		$extension = '';
		$allowed = array(
			'image/png'  => 'png',
			'image/jpeg' => 'jpg',
			'image/webp' => 'webp',
			'image/gif'  => 'gif',
		);
		if ( isset( $allowed[ $mime ] ) ) {
			$extension = $allowed[ $mime ];
		} else {
			$extension = strtolower( pathinfo( $source, PATHINFO_EXTENSION ) );
			if ( ! in_array( $extension, array( 'png', 'jpg', 'jpeg', 'webp', 'gif' ), true ) ) {
				return '';
			}
		}

		$token = substr( hash_hmac( 'sha256', 'screenshot|' . $attachment_id . '|' . (int) @filemtime( $source ), wp_salt( 'auth' ) ), 0, 20 );
		$directory = trailingslashit( $uploads['basedir'] ) . 'kashida-hide-my-theme/' . $token;
		if ( ! wp_mkdir_p( $directory ) ) {
			return '';
		}

		$target = trailingslashit( $directory ) . 'theme-screenshot.' . $extension;
		if ( ! file_exists( $target ) || (int) @filemtime( $target ) < (int) @filemtime( $source ) || filesize( $target ) !== filesize( $source ) ) {
			if ( ! copy( $source, $target ) ) {
				return '';
			}
		}

		return $this->relative_to_home( $target );
	}

	/**
	 * Create exact internal rewrite rules for theme metadata endpoints only.
	 * Only style.css metadata and the theme-directory screenshot are mapped;
	 * page assets, HTML, CSS imports, scripts, fonts and content images are untouched.
	 *
	 * @return array
	 */
	private function rewrite_lines() {
		$s = $this->settings();
		if ( empty( $s['enabled'] ) ) {
			return array();
		}

		$slugs = array_unique( array_filter( array( get_stylesheet(), get_template() ) ) );
		$rules = array( 'RewriteEngine On' );

		// Replace only theme-directory screenshot endpoints with the image selected
		// in Kashida settings. Map both child and parent theme paths because
		// external detectors may identify the parent theme's screenshot.
		$public_screenshot = $this->build_public_screenshot();
		if ( $public_screenshot ) {
			foreach ( $slugs as $slug ) {
				$theme_root = get_theme_root( $slug );
				$theme_dir  = trailingslashit( $theme_root ) . $slug;
				$theme_rel  = $this->relative_to_home( $theme_dir );
				if ( '' === $theme_rel ) {
					continue;
				}
				$screenshot_names = array( 'screenshot.png', 'screenshot.jpg', 'screenshot.jpeg', 'screenshot.webp', 'screenshot.gif' );
				foreach ( $screenshot_names as $screenshot_name ) {
					$rules[] = 'RewriteRule ^' . preg_quote( trailingslashit( $theme_rel ) . $screenshot_name, '#' ) . '$ ' . str_replace( '\\', '/', $public_screenshot ) . ' [L,NC]';
				}
			}
		}

		foreach ( $slugs as $slug ) {
			$theme_root = get_theme_root( $slug );
			$source = trailingslashit( $theme_root ) . $slug . '/style.css';
			$source_relative = $this->relative_to_home( $source );
			$target_relative = $this->build_public_stylesheet( $slug, true );

			if ( '' === $source_relative || '' === $target_relative ) {
				continue;
			}

			$source_rule = preg_quote( str_replace( '\\', '/', $source_relative ), '#' );
			$rules[] = 'RewriteRule ^' . $source_rule . '$ ' . str_replace( '\\', '/', $target_relative ) . ' [L,NC]';
		}

		return count( $rules ) > 1 ? $rules : array();
	}

	public function sync_rewrite_rules() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$lines = $this->rewrite_lines();
		$ok = self::write_rewrite_rules( $lines );
		update_option( 'khm_rewrite_status', $ok ? 'ok' : ( empty( $lines ) ? 'inactive' : 'failed' ), false );
	}

	/**
	 * Build a public URL for a theme metadata endpoint.
	 *
	 * @param string $slug Theme directory slug.
	 * @param string $file File name.
	 * @return string
	 */
	private function theme_public_file_url( $slug, $file ) {
		$root_uri = get_theme_root_uri( $slug );
		return trailingslashit( $root_uri ) . rawurlencode( $slug ) . '/' . rawurlencode( $file );
	}

	/**
	 * Verify that the public style.css endpoint actually returns our metadata.
	 * This is a best-effort loopback test; a CDN/WAF may block loopback requests.
	 *
	 * @return array
	 */
	private function verify_public_metadata() {
		if ( ! $this->active_profile() ) {
			return array( 'status' => 'inactive', 'message' => __( 'Metadata replacement is disabled.', 'kashida-hide-my-theme' ) );
		}

		$s = $this->settings();
		$expected_name = trim( (string) $s['theme_name'] );
		$results = array();

		foreach ( array_unique( array_filter( array( get_stylesheet(), get_template() ) ) ) as $slug ) {
			$url = add_query_arg( 'khm_verify', wp_generate_password( 8, false, false ), $this->theme_public_file_url( $slug, 'style.css' ) );
			$response = wp_remote_get(
				$url,
				array(
					'timeout'     => 8,
					'redirection' => 2,
					'headers'     => array( 'Cache-Control' => 'no-cache', 'Pragma' => 'no-cache' ),
					'sslverify'   => apply_filters( 'https_local_ssl_verify', false ),
				)
			);

			if ( is_wp_error( $response ) ) {
				$results[ $slug ] = array( 'ok' => false, 'message' => $response->get_error_message() );
				continue;
			}

			$code = (int) wp_remote_retrieve_response_code( $response );
			$body = (string) wp_remote_retrieve_body( $response );
			$has_name = '' !== $expected_name && false !== stripos( $body, 'Theme Name: ' . $expected_name );
			$style_ok = ( 200 === $code && $has_name );
			$screenshot_ok = null;
			$screenshot_message = __( 'No custom screenshot selected.', 'kashida-hide-my-theme' );

			$attachment_id = absint( $s['screenshot_id'] );
			if ( $attachment_id ) {
				$source_image = get_attached_file( $attachment_id );
				$source_hash = ( $source_image && is_readable( $source_image ) ) ? hash_file( 'sha256', $source_image ) : '';
				$screenshot_url = add_query_arg( 'khm_verify', wp_generate_password( 8, false, false ), $this->theme_public_file_url( $slug, 'screenshot.png' ) );
				$image_response = wp_remote_get(
					$screenshot_url,
					array(
						'timeout'     => 8,
						'redirection' => 2,
						'headers'     => array( 'Cache-Control' => 'no-cache', 'Pragma' => 'no-cache' ),
						'sslverify'   => apply_filters( 'https_local_ssl_verify', false ),
					)
				);

				if ( ! is_wp_error( $image_response ) ) {
					$image_code = (int) wp_remote_retrieve_response_code( $image_response );
					$image_body = (string) wp_remote_retrieve_body( $image_response );
					$screenshot_ok = ( 200 === $image_code && '' !== $source_hash && hash( 'sha256', $image_body ) === $source_hash );
					$screenshot_message = $screenshot_ok
						? __( 'The selected screenshot is served at the public theme screenshot endpoint.', 'kashida-hide-my-theme' )
						: __( 'The public screenshot endpoint does not match the selected image. A cache or rewrite rule may be interfering.', 'kashida-hide-my-theme' );
				} else {
					$screenshot_ok = false;
					$screenshot_message = $image_response->get_error_message();
				}
			}

			$results[ $slug ] = array(
				'ok'                => $style_ok && ( null === $screenshot_ok || $screenshot_ok ),
				'code'              => $code,
				'has_name'          => $has_name,
				'screenshot_ok'     => $screenshot_ok,
				'screenshot_message'=> $screenshot_message,
				'message'           => $style_ok
					? __( 'Custom metadata detected in the public response.', 'kashida-hide-my-theme' )
					: __( 'The public response did not contain the expected custom metadata.', 'kashida-hide-my-theme' ),
			);
		}

		$all_ok = ! empty( $results );
		foreach ( $results as $result ) {
			if ( empty( $result['ok'] ) ) {
				$all_ok = false;
				break;
			}
		}

		return array(
			'status'  => $all_ok ? 'verified' : 'unverified',
			'results' => $results,
			'message' => $all_ok
				? __( 'Public metadata replacement was verified for the active and parent theme endpoints.', 'kashida-hide-my-theme' )
				: __( 'One or more public endpoints could not be verified. Check rewrite rules, cache, CDN, and server configuration.', 'kashida-hide-my-theme' ),
		);
	}

	private static function write_rewrite_rules( $lines ) {
		if ( ! function_exists( 'get_home_path' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		global $wp_filesystem;

		$file = trailingslashit( get_home_path() ) . '.htaccess';

		// Use WordPress' filesystem abstraction so direct filesystem calls are
		// avoided and non-direct filesystem transports are respected.
		if ( ! $wp_filesystem && ! WP_Filesystem() ) {
			return false;
		}

		if ( ! $wp_filesystem || ! $wp_filesystem->exists( $file ) ) {
			return empty( $lines );
		}

		if ( ! $wp_filesystem->is_readable( $file ) || ! $wp_filesystem->is_writable( $file ) ) {
			return false;
		}

		$contents = $wp_filesystem->get_contents( $file );
		if ( false === $contents ) {
			return false;
		}

		$begin = '# BEGIN ' . self::MARKER;
		$end   = '# END ' . self::MARKER;

		// Remove the previous managed block without touching any other directives.
		$pattern = '/(?:\r?\n)?' . preg_quote( $begin, '/' ) . '\r?\n.*?\r?\n' . preg_quote( $end, '/' ) . '(?:\r?\n)?/s';
		$contents = preg_replace( $pattern, "\n", $contents, 1, $removed );
		if ( ! is_string( $contents ) ) {
			return false;
		}

		if ( ! empty( $lines ) ) {
			$block = $begin . "\n" . implode( "\n", array_map( 'strval', $lines ) ) . "\n" . $end . "\n";

			// Put KHM rules before WordPress' front-controller rules. This is
			// essential for theme screenshot endpoints that may otherwise be
			// routed to index.php before the KHM rule is evaluated.
			$wp_marker = '# BEGIN WordPress';
			$wp_position = strpos( $contents, $wp_marker );

			if ( false !== $wp_position ) {
				$contents = substr( $contents, 0, $wp_position ) . $block . "\n" . substr( $contents, $wp_position );
			} else {
				$contents = $block . "\n" . ltrim( $contents, "\r\n" );
			}
		}

		return $wp_filesystem->put_contents( $file, $contents, FS_CHMOD_FILE );
	}

	public function dashboard_widget() {
		if ( ! $this->active_profile() ) {
			return;
		}
		wp_add_dashboard_widget(
			'khm_theme_identity',
			__( 'Theme identity', 'kashida-hide-my-theme' ),
			array( $this, 'render_dashboard_widget' )
		);
	}

	public function render_dashboard_widget() {
		$s = $this->settings();
		$name = $s['theme_name'] ? $s['theme_name'] : wp_get_theme()->get( 'Name' );
		$icon = $this->image_url( absint( $s['icon_id'] ) );
		?>
		<div class="khm-dashboard-identity">
			<?php if ( $icon ) : ?><img src="<?php echo esc_url( $icon ); ?>" alt="" /><?php else : ?><span class="dashicons dashicons-admin-appearance" aria-hidden="true"></span><?php endif; ?>
			<div><strong><?php echo esc_html( $name ); ?></strong><small><?php esc_html_e( 'Active theme', 'kashida-hide-my-theme' ); ?></small></div>
		</div>
		<p><a href="<?php echo esc_url( admin_url( 'tools.php?page=kashida-hide-my-theme' ) ); ?>"><?php esc_html_e( 'Manage theme identity', 'kashida-hide-my-theme' ); ?></a></p>
		<?php
	}

	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$s = $this->settings();
		$name = $s['theme_name'];
		$version = $s['theme_version'];
		$author = $s['theme_author'];
		$description = $s['theme_description'];
		$theme_uri = $s['theme_uri'];
		$screenshot = absint( $s['screenshot_id'] );
		$icon = absint( $s['icon_id'] );
		$enabled = ! empty( $s['enabled'] );
		$screen_url = $this->image_url( $screenshot );
		$icon_url = $this->image_url( $icon );
		$status = get_option( 'khm_rewrite_status', 'inactive' );
		$verification = null;
		if ( isset( $_GET['khm_verify_now'] ) && '1' === sanitize_text_field( wp_unslash( $_GET['khm_verify_now'] ) ) ) {
			check_admin_referer( 'khm_verify_metadata' );
			$verification = $this->verify_public_metadata();
			update_option( 'khm_verification_result', $verification, false );
		} else {
			$verification = get_option( 'khm_verification_result', null );
		}
		$theme = wp_get_theme();
		?>
		<div class="wrap khm-wrap">
			<header class="khm-header">
				<div class="khm-brand"><img src="<?php echo esc_url( KHM_URL . 'assets/kashida-logo.svg' ); ?>" alt="<?php esc_attr_e( 'Kashida Media Services', 'kashida-hide-my-theme' ); ?>" /></div>
				<div><div class="khm-title"><h1><?php esc_html_e( 'Kashida Hide my theme', 'kashida-hide-my-theme' ); ?></h1><span>v<?php echo esc_html( KHM_VERSION ); ?></span></div><p><?php esc_html_e( 'Universal theme identity settings for any active WordPress theme.', 'kashida-hide-my-theme' ); ?></p></div>
			</header>

			<div class="notice notice-info"><p><strong><?php esc_html_e( 'Safe metadata mode:', 'kashida-hide-my-theme' ); ?></strong> <?php esc_html_e( 'Only the theme style.css metadata response and theme-directory screenshot endpoint are internally served from generated copies. Original URLs stay the same; CSS rules, page images, fonts, scripts, page-builder output and theme files are not modified.', 'kashida-hide-my-theme' ); ?></p></div>
			<?php if ( 'cleanup_required' === get_option( 'khm_rewrite_status' ) ) : ?>
				<div class="notice notice-error"><p><?php esc_html_e( 'The plugin could not remove its previous rewrite rules from .htaccess. Please remove the block between the Kashida Hide my theme markers manually after backing up the file.', 'kashida-hide-my-theme' ); ?></p></div>
			<?php endif; ?>
			<?php if ( isset( $_GET['settings-updated'] ) && 'true' === sanitize_text_field( wp_unslash( $_GET['settings-updated'] ) ) ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved. Check the rewrite status below before testing your public site.', 'kashida-hide-my-theme' ); ?></p></div>
			<?php endif; ?>

			<div class="khm-layout">
				<main class="khm-card">
					<div class="khm-section-head"><div><h2><?php esc_html_e( 'Theme identity', 'kashida-hide-my-theme' ); ?></h2><p><?php esc_html_e( 'These settings apply to the currently active theme and are not tied to a specific theme vendor or directory.', 'kashida-hide-my-theme' ); ?></p></div><span class="khm-pill <?php echo $enabled ? 'active' : ''; ?>"><?php echo $enabled ? esc_html__( 'Enabled', 'kashida-hide-my-theme' ) : esc_html__( 'Disabled', 'kashida-hide-my-theme' ); ?></span></div>
					<div class="khm-current"><span><?php esc_html_e( 'Active theme', 'kashida-hide-my-theme' ); ?></span><strong><?php echo esc_html( $theme->get( 'Name' ) ); ?></strong><code><?php echo esc_html( get_stylesheet() ); ?></code></div>

					<form method="post" action="options.php">
						<?php settings_fields( 'khm_settings_group' ); ?>
						<div class="khm-field"><label for="khm-name"><?php esc_html_e( 'Custom display name', 'kashida-hide-my-theme' ); ?></label><input id="khm-name" class="regular-text" maxlength="80" name="<?php echo esc_attr( self::OPTION ); ?>[theme_name]" value="<?php echo esc_attr( $name ); ?>" placeholder="<?php echo esc_attr( $theme->get( 'Name' ) ); ?>" /><p class="description"><?php esc_html_e( 'Used in supported WordPress administration displays and the public style.css metadata response.', 'kashida-hide-my-theme' ); ?></p></div>

						<div class="khm-field"><label for="khm-version"><?php esc_html_e( 'Public theme version', 'kashida-hide-my-theme' ); ?></label><input id="khm-version" class="regular-text" maxlength="30" name="<?php echo esc_attr( self::OPTION ); ?>[theme_version]" value="<?php echo esc_attr( $version ); ?>" placeholder="1.0.0" /></div>
						<div class="khm-field"><label for="khm-author"><?php esc_html_e( 'Public theme author', 'kashida-hide-my-theme' ); ?></label><input id="khm-author" class="regular-text" maxlength="80" name="<?php echo esc_attr( self::OPTION ); ?>[theme_author]" value="<?php echo esc_attr( $author ); ?>" /></div>
						<div class="khm-field"><label for="khm-description"><?php esc_html_e( 'Public theme description', 'kashida-hide-my-theme' ); ?></label><textarea id="khm-description" class="large-text" rows="3" maxlength="250" name="<?php echo esc_attr( self::OPTION ); ?>[theme_description]"><?php echo esc_textarea( $description ); ?></textarea></div>
						<div class="khm-field"><label for="khm-uri"><?php esc_html_e( 'Public theme website', 'kashida-hide-my-theme' ); ?></label><input id="khm-uri" class="regular-text" type="url" maxlength="200" name="<?php echo esc_attr( self::OPTION ); ?>[theme_uri]" value="<?php echo esc_attr( $theme_uri ); ?>" /></div>

						<div class="khm-field"><label><?php esc_html_e( 'Theme screenshot', 'kashida-hide-my-theme' ); ?></label><div class="khm-media"><div class="khm-preview" data-preview="screenshot"><?php if ( $screen_url ) : ?><img src="<?php echo esc_url( $screen_url ); ?>" alt="" /><?php else : ?><span class="dashicons dashicons-format-image"></span><?php esc_html_e( 'No image selected', 'kashida-hide-my-theme' ); ?><?php endif; ?></div><div><input type="hidden" id="khm-screenshot" name="<?php echo esc_attr( self::OPTION ); ?>[screenshot_id]" value="<?php echo esc_attr( $screenshot ); ?>" /><button type="button" class="button khm-select" data-target="khm-screenshot" data-preview="screenshot"><?php esc_html_e( 'Choose image', 'kashida-hide-my-theme' ); ?></button> <button type="button" class="button-link-delete khm-remove" data-target="khm-screenshot" data-preview="screenshot"><?php esc_html_e( 'Remove', 'kashida-hide-my-theme' ); ?></button><p class="description"><?php esc_html_e( 'Used in the WordPress Themes screen and public theme screenshot endpoint (child and parent theme where available). Recommended ratio: 4:3.', 'kashida-hide-my-theme' ); ?></p></div></div></div>

						<div class="khm-field"><label><?php esc_html_e( 'Theme identity icon', 'kashida-hide-my-theme' ); ?></label><div class="khm-media"><div class="khm-preview khm-preview-icon" data-preview="icon"><?php if ( $icon_url ) : ?><img src="<?php echo esc_url( $icon_url ); ?>" alt="" /><?php else : ?><span class="dashicons dashicons-admin-appearance"></span><?php esc_html_e( 'No icon selected', 'kashida-hide-my-theme' ); ?><?php endif; ?></div><div><input type="hidden" id="khm-icon" name="<?php echo esc_attr( self::OPTION ); ?>[icon_id]" value="<?php echo esc_attr( $icon ); ?>" /><button type="button" class="button khm-select" data-target="khm-icon" data-preview="icon"><?php esc_html_e( 'Choose icon', 'kashida-hide-my-theme' ); ?></button> <button type="button" class="button-link-delete khm-remove" data-target="khm-icon" data-preview="icon"><?php esc_html_e( 'Remove', 'kashida-hide-my-theme' ); ?></button><p class="description"><?php esc_html_e( 'Displayed in the Theme identity dashboard widget, not as the WordPress menu icon or site favicon.', 'kashida-hide-my-theme' ); ?></p></div></div></div>

						<div class="khm-subsection"><h2><?php esc_html_e( 'Public metadata replacement', 'kashida-hide-my-theme' ); ?></h2><p><?php esc_html_e( 'The plugin internally serves generated style.css metadata and the selected theme screenshot for the active child and parent theme, while preserving original URLs and CSS rules. No page HTML or design asset URL is rewritten.', 'kashida-hide-my-theme' ); ?></p>
							<label class="khm-check"><input type="checkbox" name="<?php echo esc_attr( self::OPTION ); ?>[enabled]" value="1" <?php checked( $enabled ); ?> /> <span><strong><?php esc_html_e( 'Enable public metadata replacement', 'kashida-hide-my-theme' ); ?></strong><small><?php esc_html_e( 'When disabled, original public theme metadata is served. WordPress theme files and update identifiers remain unchanged.', 'kashida-hide-my-theme' ); ?></small></span></label>
						</div>
						<div class="khm-submit"><button class="button button-primary button-hero" type="submit"><?php esc_html_e( 'Save changes', 'kashida-hide-my-theme' ); ?></button></div>
					</form>
				</main>

				<aside class="khm-side">
					<section class="khm-card"><h2><?php esc_html_e( 'Metadata replacement status', 'kashida-hide-my-theme' ); ?></h2>
						<?php if ( 'ok' === $status ) : ?><p class="khm-result good"><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Plugin-managed style.css metadata rules are active.', 'kashida-hide-my-theme' ); ?></p>
						<?php elseif ( 'failed' === $status ) : ?><p class="khm-result bad"><span class="dashicons dashicons-warning"></span><?php esc_html_e( 'Could not write metadata rules. Check .htaccess permissions; the original theme remains unaffected.', 'kashida-hide-my-theme' ); ?></p>
						<?php else : ?><p class="khm-result neutral"><?php esc_html_e( 'Metadata replacement is inactive.', 'kashida-hide-my-theme' ); ?></p><?php endif; ?>
						<p class="description"><?php esc_html_e( 'Only exact style.css and theme screenshot metadata endpoints are internally mapped. Metadata rules run before WordPress front-controller rules; all other front-end URLs and theme files remain untouched.', 'kashida-hide-my-theme' ); ?></p>
						<p><a class="button button-secondary" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'khm_verify_now', '1', admin_url( 'tools.php?page=kashida-hide-my-theme' ) ), 'khm_verify_metadata' ) ); ?>"><?php esc_html_e( 'Verify public metadata now', 'kashida-hide-my-theme' ); ?></a></p>
						<?php if ( is_array( $verification ) ) : ?>
							<?php
							// Render diagnostic text at display time using the current site locale.
							// Older releases stored already-translated strings in the database,
							// which could leave Arabic messages visible after switching to English.
							$overall_message = ( isset( $verification['status'] ) && 'verified' === $verification['status'] )
								? __( 'Public metadata replacement was verified for the active and parent theme endpoints.', 'kashida-hide-my-theme' )
								: __( 'One or more public endpoints could not be verified. Check rewrite rules, cache, CDN, and server configuration.', 'kashida-hide-my-theme' );
							?>
							<p class="khm-result <?php echo ( isset( $verification['status'] ) && 'verified' === $verification['status'] ) ? 'good' : 'bad'; ?>"><?php echo esc_html( $overall_message ); ?></p>
							<?php if ( ! empty( $verification['results'] ) ) : ?><ul class="khm-list"><?php foreach ( $verification['results'] as $slug => $result ) : ?>
								<?php
								$metadata_message = ! empty( $result['has_name'] )
									? __( 'Custom metadata detected in the public response.', 'kashida-hide-my-theme' )
									: __( 'The public response did not contain the expected custom metadata.', 'kashida-hide-my-theme' );
								?>
								<li><strong><?php echo esc_html( $slug ); ?>:</strong> <?php echo esc_html( $metadata_message ); ?><?php if ( isset( $result['code'] ) ) : ?> (HTTP <?php echo esc_html( (string) $result['code'] ); ?>)<?php endif; ?>
								<?php if ( isset( $result['screenshot_ok'] ) && null !== $result['screenshot_ok'] ) : ?><br /><?php echo esc_html( ! empty( $result['screenshot_ok'] ) ? __( 'The selected screenshot is served at the public theme screenshot endpoint.', 'kashida-hide-my-theme' ) : __( 'The public screenshot endpoint does not match the selected image. A cache or rewrite rule may be interfering.', 'kashida-hide-my-theme' ) ); ?>
								<?php elseif ( isset( $result['screenshot_ok'] ) ) : ?><br /><?php esc_html_e( 'No custom screenshot selected.', 'kashida-hide-my-theme' ); ?><?php endif; ?></li>
							<?php endforeach; ?></ul><?php endif; ?>
						<?php endif; ?>
					</section>
					<section class="khm-card"><h2><?php esc_html_e( 'What this can and cannot hide', 'kashida-hide-my-theme' ); ?></h2><ul class="khm-list"><li><?php esc_html_e( 'Replaces public style.css theme metadata with the identity configured here.', 'kashida-hide-my-theme' ); ?></li><li><?php esc_html_e( 'Keeps the original stylesheet URL and CSS content unchanged.', 'kashida-hide-my-theme' ); ?></li><li><?php esc_html_e( 'Cannot guarantee concealment of CSS fingerprints, HTML signatures, plugin metadata, cached URLs, or hard-coded asset paths.', 'kashida-hide-my-theme' ); ?></li><li><?php esc_html_e( 'Does not rename theme folders, alter asset URLs, edit original theme files, or change update identifiers.', 'kashida-hide-my-theme' ); ?></li></ul></section>
					<section class="khm-card khm-warning"><h2><?php esc_html_e( 'Before enabling direct blocking', 'kashida-hide-my-theme' ); ?></h2><p><?php esc_html_e( 'Create a backup of .htaccess and test on staging. A third-party component that hard-codes /wp-content/themes/... may stop loading if direct blocking is enabled.', 'kashida-hide-my-theme' ); ?></p></section>
				</aside>
			</div>
			<footer class="khm-footer"><div><img src="<?php echo esc_url( KHM_URL . 'assets/kashida-mark.svg' ); ?>" alt="" /><strong>Kashida Media Services</strong> <?php esc_html_e( 'by Mrwan elGobee', 'kashida-hide-my-theme' ); ?></div><a href="https://kashida.ly/" target="_blank" rel="noopener noreferrer">kashida.ly</a></footer>
		</div>
		<?php
	}
}

Kashida_Hide_My_Theme::instance();
register_deactivation_hook( __FILE__, array( 'Kashida_Hide_My_Theme', 'deactivate' ) );
