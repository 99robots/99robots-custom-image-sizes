<?php
/*
Plugin Name: Custom Image Sizes by 99 Robots
plugin URI:
Description:
version: 1.0.0
Author: 99 Robots
Author URI: https://99robots.com
License: GPL2
*/

// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists('NNR_Custom_Image_Sizes') ) :

// Set default timezone

date_default_timezone_set(timezone_name_from_abbr(null, (int) get_option('gmt_offset') * 3600 , true));

register_activation_hook( __FILE__, 							array('NNR_Custom_Image_Sizes', 'register_activation'));
add_action('init', 												array('NNR_Custom_Image_Sizes', 'init'));
add_filter('plugin_action_links_' . plugin_basename(__FILE__),  array('NNR_Custom_Image_Sizes', 'settings_link'));
add_action('admin_menu', 										array('NNR_Custom_Image_Sizes', 'menu'));
add_filter('image_size_names_choose', 							array('NNR_Custom_Image_Sizes', 'show_custom_sizes'));

/**
 * NNR_Custom_Image_Sizes class.
 */
class NNR_Custom_Image_Sizes {

	/**
	 * prefix
	 *
	 * (default value: 'nnr_custom_image_sizes_')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	static $prefix = 'nnr_custom_image_sizes_';

	/**
	 * prefix_dash
	 *
	 * (default value: 'nnr-cis-')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	static $prefix_dash = 'nnr-cis-';

	/**
	 * text_domain
	 *
	 * (default value: '99robots-custom-image-sizes')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	static $text_domain = '99robots-custom-image-sizes';

	/**
	 * settings_page
	 *
	 * (default value: 'nnr-cis-settings-page')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	static $settings_page = 'nnr-cis-settings-page';

	/**
	 * Runs on the init hook
	 *
	 * @since 1.0.0
	 */
	static function init() {

		load_plugin_textdomain(self::$text_domain, false, basename( dirname( __FILE__ ) ) . '/languages' );

		// Add Image Sizes

		$settings = self::get_settings();

		foreach ( $settings as $size ) {

			$crop = false;

			if ( $size['crop'] == 'true' ) {
				$crop = true;
			} else {
				$crop = explode('_', $size['crop']);
			}

			add_image_size( $size['name'], (int) $size['width'], (int) $size['height'], $crop );

		}
	}

	/**
	 * Show the custom image sizes
	 *
	 * @access public
	 * @static
	 * @param mixed $sizes
	 * @return void
	 */
	static function show_custom_sizes( $sizes ) {

		$settings = self::get_settings();

		$new_sizes = array();

		foreach ( $settings as $size ) {

			$new_sizes[$size['name']] = __($size['name'], self::$text_domain);

		}

	    return array_merge( $sizes, $new_sizes );
	}

	/**
	 * Performs tasks needed upon activation
	 *
	 * @since 1.0.0
	 */
	static function register_activation() {

		// Check if multisite, if so then save as site option

		if ( function_exists('is_multisite') && is_multisite() ) {
			update_site_option(self::$prefix . 'version', NNROBOTS_CUSTOM_IMAGE_SIZES_VERSION_NUM);
		} else {
			update_option(self::$prefix . 'version', NNROBOTS_CUSTOM_IMAGE_SIZES_VERSION_NUM);
		}
	}

	/**
	 * Hooks to 'plugin_action_links_' filter
	 *
	 * @since 1.0.0
	 */
	static function settings_link($links) {
		$settings_link = '<a href="' . get_admin_url() . 'options-general.php?page=' . self::$settings_page . '">' . __('Settings', self::$text_domain) . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	/**
	 * Hooks intot the 'admin_menu' hook to show the settings page
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static function menu() {

		 $settings_page_load = add_submenu_page(
	    	'options-general.php',
	    	__('Custom Image Sizes', self::$text_domain),
	    	__('Custom Image Sizes', self::$text_domain),
	    	'manage_options',
	    	self::$settings_page,
	    	array('NNR_Custom_Image_Sizes', 'settings')
	    );
	    add_action("admin_print_scripts-$settings_page_load" , array('NNR_Custom_Image_Sizes', 'inline_scripts'));
	}

	/**
	 * Hooks into the 'admin_print_scripts-$page' to inlcude the scripts for the settings page
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static function inline_scripts() {

		// Style

		wp_enqueue_style(self::$prefix . 'settings_css', 	NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_URL . '/css/settings.css');
		wp_enqueue_style(self::$prefix . 'bootstrap_css', 	NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_URL . '/css/nnr-bootstrap.min.css');
		wp_enqueue_style(self::$prefix . 'fontawesome_css', NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_URL . '/css/font-awesome.min.css');

		// Script

		wp_enqueue_script(self::$prefix . 'bootstrap_js', 	NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_URL . '/js/bootstrap.min.js');
		wp_enqueue_script(self::$prefix . 'settings_js', 	NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_URL . '/js/settings.js', array('jquery', 'jquery-ui-sortable'));
		wp_localize_script(self::$prefix . 'settings_js', 'nnr_cis_settings_data', array(
			'prefix'	=> self::$prefix_dash,
		) );

	}

	/**
	 * This is the main function for the settings page
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static function settings() {

		// Get the settings

		$settings = self::get_settings();

		// Get all the image sizes registered

		global $_wp_additional_image_sizes;

        $sizes = array();
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        // Create the full array with sizes and crop info
        foreach( $get_intermediate_image_sizes as $_size ) {

	        $custom_name = array();

	        foreach ( $settings as $size ) {
		        $custom_name[] = $size['name'];
	        }

            if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

                $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
                $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
                $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );

            } else if ( isset( $_wp_additional_image_sizes[ $_size ] ) && !in_array($_size, $custom_name)) {

                $sizes[ $_size ] = array(
                        'width' 	=> $_wp_additional_image_sizes[ $_size ]['width'],
                        'height' 	=> $_wp_additional_image_sizes[ $_size ]['height'],
                        'crop' 		=> $_wp_additional_image_sizes[ $_size ]['crop']
                );

            }

        }

		// Save data and check nonce

		if (isset($_POST['submit']) && check_admin_referer(self::$prefix . 'settings')) {

			$new_settings = array();

			$name = isset($_POST[self::$prefix_dash . 'name']) ? $_POST[self::$prefix_dash . 'name'] : array();
			$width = isset($_POST[self::$prefix_dash . 'width']) ? $_POST[self::$prefix_dash . 'width'] : array();
			$height = isset($_POST[self::$prefix_dash . 'height']) ? $_POST[self::$prefix_dash . 'height'] : array();
			$crop = isset($_POST[self::$prefix_dash . 'crop']) ? $_POST[self::$prefix_dash . 'crop'] : array();

			for ($i = 0; $i < count($name); $i++) {

				if ( isset($name[$i]) && $name[$i] != '' &&
					 isset($width[$i]) && $width[$i] != '' &&
					 isset($height[$i]) && $height[$i] != '' ) {

					$new_settings[] = array(
						'name' 				=> stripcslashes(sanitize_text_field($name[$i])),
						'width' 			=> stripcslashes(sanitize_text_field($width[$i])),
						'height' 			=> stripcslashes(sanitize_text_field($height[$i])),
						'crop' 				=> stripcslashes(sanitize_text_field($crop[$i])),
					);
				}
			}

			self::update_settings($new_settings);

			?>
			<script type="text/javascript">
				window.location= "<?php echo get_admin_url(); ?>options-general.php?page=<?php echo self::$settings_page; ?>";
			</script>
			<?php

		}

		include_once('views/settings.php');

	}

	/**
	 * Get the settings
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static function get_settings() {

		// Get the setting from a multisite

		if ( function_exists('is_multisite') && is_multisite() ) {
			$settings = get_site_option(self::$prefix . 'settings');
		} else {
			$settings = get_option(self::$prefix . 'settings');
		}

		// Check if the setting is not set

		if ( $settings === false ) {
			$settings = array();
		}

		return $settings;
	}

	/**
	 * Update the settings
	 *
	 * @access public
	 * @static
	 * @param mixed $settings
	 * @return void
	 */
	static function update_settings($settings) {

		// Get the setting from a multisite

		if ( function_exists('is_multisite') && is_multisite() ) {
			$result = update_site_option(self::$prefix . 'settings', $settings);
		} else {
			$result = update_option(self::$prefix . 'settings', $settings);
		}

		return $result;
	}
}

// Item Name

if ( !defined('NNROBOTS_CUSTOM_IMAGE_SIZES_ITEM_NAME') ) {
	define('NNROBOTS_CUSTOM_IMAGE_SIZES_ITEM_NAME', 'Custom Image Sizes');
}

// Plugin Name

if ( !defined('NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_NAME') ) {
	define('NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
}

// Plugin directory

if ( !defined('NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_DIR') ) {
	define('NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_DIR', plugin_dir_path(__FILE__) );
}

// Plugin url

if ( !defined('NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_URL') ) {
	define('NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_URL', plugins_url() . '/' . NNROBOTS_CUSTOM_IMAGE_SIZES_PLUGIN_NAME);
}

// Plugin verison

if ( !defined('NNROBOTS_CUSTOM_IMAGE_SIZES_VERSION_NUM') ) {
	define('NNROBOTS_CUSTOM_IMAGE_SIZES_VERSION_NUM', '1.0.0');
}

endif;