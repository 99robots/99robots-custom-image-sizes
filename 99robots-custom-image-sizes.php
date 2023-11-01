<?php
/**
 * Plugin Name: Custom Image Sizes by DraftPress
 * Plugin URI: https://wordpress.org/plugins/custom-image-sizes-by-draftpress/
 * Description: Custom Image Sizes by DraftPress is a quick
 * and simple way for you to add your own image sizes to your WordPress site.
 * Version: 1.2.10
 * Requires at least: 4.5
 * Tested up to: 6.3.2
 * Requires PHP: 5.6
 * Author: DraftPress
 * Author URI: https://draftpress.com/
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 99robots-custom-image-sizes
 * Domain Path: /languages
 * Php Version 7.2.10
 *
 * @category Plugin
 * @package  DraftPress_CustomImageSizes
 * @author   Draft <support@draftpress.com>
 * @license  GNU General Public License 2
 * (https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
 * @link     https://draftpress.com/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}


/**
 * NNR_Custom_Image_Sizes class.
 *
 * @category Class
 * @package  Custom_Image_Sizes_By_DraftPress
 * @author   Draft <support@draftpress.com>
 * @license  GNU General Public License 2
 * (https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
 * @link     https://draftpress.com/products/
 */
class NNR_Custom_Image_Sizes {

	/**
	 * NNR_Custom_Image_Sizes version.
	 *
	 * @var string
	 */
	public $version = '1.2.10';

	/**
	 * The single instance of the class.
	 *
	 * @var NNR_Custom_Image_Sizes
	 */
	protected static $instance = null;

	/**
	 * Plugin url.
	 *
	 * @var string
	 */
	private $plugin_url = null;

	/**
	 * Plugin path.
	 *
	 * @var string
	 */
	private $plugin_dir = null;

	/**
	 * Setting manager.
	 *
	 * @var WPIS_Settings
	 */
	public $settings = null;


	/**
	 * Prefix
	 *
	 * (default value: 'nnr_custom_image_sizes_')
	 *
	 * @var string
	 */
	private static $prefix = 'nnr_custom_image_sizes_';

	/**
	 * Prefix Dash
	 *
	 * (default value: 'nnr-cis-')
	 *
	 * @var string
	 */
	private static $prefix_dash = 'nnr-cis-';

	/**
	 * Settings Page
	 *
	 * (default value: 'nnr-cis-settings-page')
	 *
	 * @var string
	 */
	private static $settings_page = 'nnr-cis-settings-page';


	/**
	 * Cloning is forbidden.
	 *
	 * @return void
	 */
	public function __clone() {
		wc_doing_it_wrong(
			__FUNCTION__,
			__( 'Cheatin&#8217; huh?', '99robots-custom-image-sizes' ),
			$this->version
		);
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @return void
	 */
	public function __wakeup() {
		wc_doing_it_wrong(
			__FUNCTION__,
			__( 'Cheatin&#8217; huh?', '99robots-custom-image-sizes' ),
			$this->version
		);
	}

	/**
	 * Main NNR_Custom_Image_Sizes instance.
	 *
	 * Ensure only one instance is loaded or can be loaded.
	 *
	 * @return NNR_Custom_Image_Sizes
	 */
	public static function instance() {
		if ( is_null( self::$instance )
			&& ! ( self::$instance instanceof NNR_Custom_Image_Sizes )
		) {
			self::$instance = new NNR_Custom_Image_Sizes();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	/**
	 * NNR_Custom_Image_Sizes constructor.
	 */
	private function __construct() {
	}

	/**
	 * Add hooks to begin.
	 *
	 * @return void
	 */
	private function hooks() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_filter(
			'plugin_action_links_' . plugin_basename( __FILE__ ),
			array( $this, 'plugin_links' )
		);
		add_action( 'admin_menu', array( $this, 'register_pages' ) );
		add_filter( 'image_size_names_choose', array( $this, 'show_custom_sizes' ) );
	}


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters(
			'plugin_locale',
			get_locale(),
			'99robots-custom-image-sizes'
		);

		load_textdomain(
			'99robots-custom-image-sizes',
			WP_LANG_DIR .
				'/custom-image-sizes-by-99-robots/custom-image-sizes-by-99-robots-' .
				$locale .
				'.mo'
		);

		load_plugin_textdomain(
			'99robots-custom-image-sizes',
			false,
			$this->plugin_dir() . '/languages/'
		);
	}


	/**
	 * Runs on the init hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		// Add Image Sizes.
		$settings = $this->get_settings();

		foreach ( $settings as $size ) {
			$crop = false;
			if ( 'true' === $size['crop'] ) {
				$crop = true;
			} else {
				$crop = explode( '_', $size['crop'] );
			}

			add_image_size(
				$size['name'],
				intval( $size['width'] ),
				intval( $size['height'] ),
				$crop
			);
		}
	}


	/**
	 * Hooks to 'plugin_action_links_' filter.
	 *
	 * @param array $links The existing plugin action links.
	 *
	 * @return array The modified plugin action links.
	 */
	public function plugin_links( $links ) {
		$settings_link
			= '<a href="' .
			get_admin_url() .
			'options-general.php?page=' .
			self::$settings_page .
			'">' .
			esc_html__( 'Settings', '99robots-custom-image-sizes' ) .
			'</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Hooks intot the 'admin_menu' hook to show the settings page
	 *
	 * @return void
	 */
	public function register_pages() {
		$settings_page_load = add_submenu_page(
			'options-general.php',
			esc_html__( 'Custom Image Sizes', '99robots-custom-image-sizes' ),
			esc_html__( 'Custom Image Sizes', '99robots-custom-image-sizes' ),
			'manage_options',
			self::$settings_page,
			array( $this, 'render_settings' )
		);
		add_action( "load-$settings_page_load", array( $this, 'enqueque_scripts' ) );
	}

	/**
	 * Hooks into the 'admin_print_scripts-$page'
	 * to inlcude the scripts for the settings page
	 *
	 * @return void
	 */
	public function enqueque_scripts() {
		// Style.
		wp_enqueue_style(
			self::$prefix . 'settings_css',
			$this->plugin_url() . 'css/settings.css',
			array(),
			'1.0.0'
		);
		wp_enqueue_style(
			self::$prefix . 'bootstrap_css',
			$this->plugin_url() . 'css/nnr-bootstrap.min.css',
			array(),
			'1.0.0'
		);
		wp_enqueue_style(
			self::$prefix . 'fontawesome_css',
			$this->plugin_url() . 'css/font-awesome.min.css',
			array(),
			'1.0.0'
		);

		// Script.
		wp_enqueue_script(
			self::$prefix . 'bootstrap_js',
			$this->plugin_url() . 'js/bootstrap.min.js',
			array(),
			'1.0.0', // Replace with the version number of your script.
			true // Set to true to load the script in the footer.
		);
		wp_enqueue_script(
			self::$prefix . 'settings_js',
			$this->plugin_url() . 'js/settings.js',
			array( 'jquery', 'jquery-ui-sortable' ),
			'1.0.0', // Replace with the version number of your script.
			true // Set to true to load the script in the footer.
		);
		wp_localize_script(
			self::$prefix . 'settings_js',
			'nnr_cis_settings_data',
			array(
				'prefix' => self::$prefix_dash,
			)
		);
	}


	/**
	 * Show the custom image sizes
	 *
	 * @param mixed $sizes An array of custom image sizes.
	 *
	 * @return array
	 */
	public function show_custom_sizes( $sizes ) {
		$settings = $this->get_settings();

		$new_sizes = array();
		foreach ( $settings as $size ) {
			$new_sizes[ $size['name'] ] = $size['name'];
		}

		return array_merge( $sizes, $new_sizes );
	}

	/**
	 * This is the main function for the settings page
	 *
	 * @return void
	 */
	public function render_settings() {
		global $_wp_additional_image_sizes;

		$settings = $this->get_settings();

		// Get all the image sizes registered.
		$sizes                        = array();
		$names                        = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();

		// Create the full array with sizes and crop info.
		foreach ( $get_intermediate_image_sizes as $_size ) {
			$custom_name = array();
			foreach ( $settings as $size ) {
				$custom_name[] = $size['name'];
			}
			$images_sizes
				= array(
					'thumbnail',
					'medium',
					'medium_large',
					'large',
				);
			if ( in_array( $_size, $images_sizes, true )
			) {
				$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
				$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
				$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
				$sizes[ $_size ]['source'] = esc_html__(
					'WP Core',
					'99robots-custom-image-sizes'
				);

				$names[] = $_size;
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] )
				&& ! in_array( $_size, $custom_name, true )
			) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
					'source' => esc_html__(
						'Active Theme or Plugin',
						'99robots-custom-image-sizes'
					),
				);

				$names[] = $_size;
			}
		}

		// Save data and check nonce.
		if ( isset( $_POST['submit'] )
			&& check_admin_referer( self::$prefix . 'settings' )
		) {

			$new_settings = array();
			$name         = isset( $_POST[ self::$prefix_dash . 'name' ] )
			? array_map(
				'sanitize_text_field',
				wp_unslash( $_POST[ self::$prefix_dash . 'name' ] )
			) : array();
			$width        = isset( $_POST[ self::$prefix_dash . 'width' ] )
			? array_map(
				'sanitize_text_field',
				wp_unslash( $_POST[ self::$prefix_dash . 'width' ] )
			) : array();
			$height       = isset( $_POST[ self::$prefix_dash . 'height' ] )
			? array_map(
				'sanitize_text_field',
				wp_unslash( $_POST[ self::$prefix_dash . 'height' ] )
			) : array();
			$crop         = isset( $_POST[ self::$prefix_dash . 'crop' ] )
			? array_map(
				'sanitize_text_field',
				wp_unslash( $_POST[ self::$prefix_dash . 'crop' ] )
			) : array();

			$count = count( $name );
			for ( $i = 0; $i < $count; $i++ ) {
				$name_a = sanitize_text_field( $name[ $i ] );
				$name_b = stripcslashes( $name_a );
				if ( isset( $name[ $i ] ) && '' !== $name[ $i ]
					&& isset( $width[ $i ] ) && '' !== $width[ $i ]
					&& isset( $height[ $i ] ) && '' !== $height[ $i ]
				) {
					// Check if name has not been taken already.
					if ( ! in_array( $name_b, $names, true ) ) {
						$new_settings[] = array(
							'name'   =>
							stripcslashes( sanitize_text_field( $name[ $i ] ) ),
							'width'  =>
							stripcslashes( sanitize_text_field( $width[ $i ] ) ),
							'height' =>
							stripcslashes( sanitize_text_field( $height[ $i ] ) ),
							'crop'   => stripcslashes( sanitize_text_field( $crop[ $i ] ) ),
						);

						$names[] = stripcslashes( sanitize_text_field( $name[ $i ] ) );
					}
				}
			}

			self::update_settings( $new_settings );
			$settings_page_url = get_admin_url() .
				'options-general.php?page=' .
				self::$settings_page;
			?>

			<script type="text/javascript">
				window.location= "<?php echo esc_url( $settings_page_url ); ?>";
			</script>
			<?php
		}

		include_once 'views/settings.php';
	}


	/**
	 * Get the settings
	 *
	 * @return string
	 */
	public function get_settings() {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$settings = get_site_option( self::$prefix . 'settings' );
		} else {
			$settings = get_option( self::$prefix . 'settings' );
		}

		// Check if the setting is not set.
		if ( false === $settings ) {
			$settings = array();
		}

		return $settings;
	}

	/**
	 * Update the settings
	 *
	 * @param mixed $settings The new settings to update.
	 *
	 * @return string
	 */
	public function update_settings( $settings ) {
		// Get the setting from a multisite.
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$result = update_site_option( self::$prefix . 'settings', $settings );
		} else {
			$result = update_option( self::$prefix . 'settings', $settings );
		}

		return $result;
	}

	/**
	 * Get plugin directory.
	 *
	 * @return string
	 */
	public function plugin_dir() {
		if ( is_null( $this->plugin_dir ) ) {
			$this->plugin_dir
				= untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/';
		}

		return $this->plugin_dir;
	}

	/**
	 * Get plugin url.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function plugin_url() {
		if ( is_null( $this->plugin_url ) ) {
			$this->plugin_url = untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/';
		}

		return $this->plugin_url;
	}


	/**
	 * Get plugin version
	 *
	 * @return string
	 */
	public function get_version(): string {
		return $this->version;
	}
}
// call the class.
NNR_Custom_Image_Sizes::instance();



