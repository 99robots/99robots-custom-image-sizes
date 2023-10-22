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
 * @author   Draft <contact@draftpress.com>
 * @license  GNU General Public License 2
 * (https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
 * @link     https://draftpress.com/
 */


// If this file is called directly, abort.
if (!defined("WPINC")) {
    die();
}


/**
 * NNR_Custom_Image_Sizes class.
 *
 * @category Class
 * @package  Custom_Image_Sizes_By_DraftPress
 * @author   Draft <contact@draftpress.com>
 * @license  GNU General Public License 2
 * (https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
 * @link     https://draftpress.com/products/
 */
class NNR_Custom_Image_Sizes
{
    /**
     * NNR_Custom_Image_Sizes version.
     *
     * @var string
     */
    public $version = "1.2.9";

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
    private $_plugin_url = null;

    /**
     * Plugin path.
     *
     * @var string
     */
    private $_plugin_dir = null;

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
    static $prefix = "nnr_custom_image_sizes_";

    /**
     * Prefix Dash
     *
     * (default value: 'nnr-cis-')
     *
     * @var string
     */
    static $prefix_dash = "nnr-cis-";

    /**
     * Settings Page
     *
     * (default value: 'nnr-cis-settings-page')
     *
     * @var string
     */
    static $settings_page = "nnr-cis-settings-page";

    /**
     * Cloning is forbidden.
     *
     * @return void
     */
    public function __clone()
    {
        wc_doing_it_wrong(
            __FUNCTION__,
            __("Cheatin&#8217; huh?", "99robots-custom-image-sizes"),
            $this->version
        );
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @return void
     */
    public function __wakeup()
    {
        wc_doing_it_wrong(
            __FUNCTION__,
            __("Cheatin&#8217; huh?", "99robots-custom-image-sizes"),
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
    public static function instance()
    {
        if (is_null(self::$instance)
            && !(self::$instance instanceof NNR_Custom_Image_Sizes)
        ) {
            self::$instance = new NNR_Custom_Image_Sizes();
            self::$instance->_hooks();
        }

        return self::$instance;
    }

    /**
     * NNR_Custom_Image_Sizes constructor.
     */
    private function __construct()
    {
    }

    /**
     * Add hooks to begin.
     *
     * @return void
     */
    private function _hooks()
    {
        add_action("plugins_loaded", [$this, "loadPluginTextdomain"]);
        add_action("init", [$this, "init"]);
        add_filter(
            "plugin_action_links_" . plugin_basename(__FILE__),
            [$this, "pluginLinks"]
        );
        add_action("admin_menu", [$this, "registerPages"]);
        add_filter("image_size_names_choose", [$this, "showCustomSizes"]);
    }


    /**
     * Load the plugin text domain for translation.
     *
     * @return void
     */
    public function loadPluginTextdomain()
    {
        $locale = apply_filters(
            "plugin_locale",
            get_locale(),
            "99robots-custom-image-sizes"
        );

        load_textdomain(
            "99robots-custom-image-sizes",
            WP_LANG_DIR .
                "/custom-image-sizes-by-99-robots/custom-image-sizes-by-99-robots-" .
                $locale .
                ".mo"
        );

        load_plugin_textdomain(
            "99robots-custom-image-sizes",
            false,
            $this->pluginDir() . "/languages/"
        );
    }

    
    /**
     * Runs on the init hook.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init()
    {
        // Add Image Sizes
        $settings = $this->getSettings();

        foreach ($settings as $size) {
            $crop = false;
            if ("true" === $size["crop"]) {
                $crop = true;
            } else {
                $crop = explode("_", $size["crop"]);
            }

            add_image_size(
                $size["name"],
                intval($size["width"]),
                intval($size["height"]),
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
    public function pluginLinks($links)
    {
        $settings_link
            = '<a href="' .
            get_admin_url() .
            "options-general.php?page=" .
            self::$settings_page .
            '">' .
            esc_html__("Settings", "99robots-custom-image-sizes") .
            "</a>";
        array_unshift($links, $settings_link);

        return $links;
    }

    /**
     * Hooks intot the 'admin_menu' hook to show the settings page
     *
     * @return void
     */
    public function registerPages()
    {
        $settings_page_load = add_submenu_page(
            "options-general.php",
            esc_html__("Custom Image Sizes", "99robots-custom-image-sizes"),
            esc_html__("Custom Image Sizes", "99robots-custom-image-sizes"),
            "manage_options",
            self::$settings_page,
            [$this, "renderSettings"]
        );
        add_action("load-$settings_page_load", [$this, "enquequeScripts"]);
    }

    /**
     * Hooks into the 'admin_print_scripts-$page'
     * to inlcude the scripts for the settings page
     *
     * @return void
     */
    public function enquequeScripts()
    {
        // Style
        wp_enqueue_style(
            self::$prefix . "settings_css",
            $this->pluginUrl() . "css/settings.css"
        );
        wp_enqueue_style(
            self::$prefix . "bootstrap_css",
            $this->pluginUrl() . "css/nnr-bootstrap.min.css"
        );
        wp_enqueue_style(
            self::$prefix . "fontawesome_css",
            $this->pluginUrl() . "css/font-awesome.min.css"
        );

        // Script
        wp_enqueue_script(
            self::$prefix . "bootstrap_js",
            $this->pluginUrl() . "js/bootstrap.min.js"
        );
        wp_enqueue_script(
            self::$prefix . "settings_js",
            $this->pluginUrl() . "js/settings.js",
            ["jquery", "jquery-ui-sortable"]
        );
        wp_localize_script(
            self::$prefix . "settings_js",
            "nnr_cis_settings_data",
            [
                "prefix" => self::$prefix_dash,
            ]
        );
    }

    
    /**
     * Show the custom image sizes
     *
     * @param mixed $sizes An array of custom image sizes.
     *
     * @return void
     */
    public function showCustomSizes($sizes)
    {
        $settings = $this->getSettings();

        $new_sizes = [];
        foreach ($settings as $size) {
            $new_sizes[$size["name"]] = $size["name"];
        }

        return array_merge($sizes, $new_sizes);
    }

    /**
     * This is the main function for the settings page
     *
     * @return void
     */
    public function renderSettings()
    {
        global $_wp_additional_image_sizes;

        $settings = $this->getSettings();

        // Get all the image sizes registered
        $sizes = [];
        $names = [];
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        // Create the full array with sizes and crop info
        foreach ($get_intermediate_image_sizes as $_size) {
            $custom_name = [];
            foreach ($settings as $size) {
                $custom_name[] = $size["name"];
            }
            $imagesSizes
                = array(
                "thumbnail",
                "medium",
                "medium_large",
                "large");
            if (in_array($_size, $imagesSizes)
            ) {
                $sizes[$_size]["width"] = get_option($_size . "_size_w");
                $sizes[$_size]["height"] = get_option($_size . "_size_h");
                $sizes[$_size]["crop"] = (bool) get_option($_size . "_crop");
                $sizes[$_size]["source"] = esc_html__(
                    "WP Core",
                    "99robots-custom-image-sizes"
                );

                $names[] = $_size;
            } elseif (isset($_wp_additional_image_sizes[$_size])
                && !in_array($_size, $custom_name)
            ) {
                $sizes[$_size] = [
                    "width" => $_wp_additional_image_sizes[$_size]["width"],
                    "height" => $_wp_additional_image_sizes[$_size]["height"],
                    "crop" => $_wp_additional_image_sizes[$_size]["crop"],
                    "source" => esc_html__(
                        "Active Theme or Plugin",
                        "99robots-custom-image-sizes"
                    ),
                ];

                $names[] = $_size;
            }
        }

        // Save data and check nonce
        if (isset($_POST["submit"])
            && check_admin_referer(self::$prefix . "settings")
        ) {
            $new_settings = [];
            $name = isset($_POST[self::$prefix_dash . "name"])
                ? $_POST[self::$prefix_dash . "name"]
                : [];
            $width = isset($_POST[self::$prefix_dash . "width"])
                ? $_POST[self::$prefix_dash . "width"]
                : [];
            $height = isset($_POST[self::$prefix_dash . "height"])
                ? $_POST[self::$prefix_dash . "height"]
                : [];
            $crop = isset($_POST[self::$prefix_dash . "crop"])
                ? $_POST[self::$prefix_dash . "crop"]
                : [];

            for ($i = 0; $i < count($name); $i++) {
                $nameA = sanitize_text_field($name[$i]);
                $nameB = stripcslashes($nameA);
                if (isset($name[$i]) && "" !== $name[$i]
                    &&  isset($width[$i]) && "" !== $width[$i]
                    &&  isset($height[$i]) && "" !== $height[$i]
                ) {
                    // Check if name has not been taken already
                    if (!in_array($nameB, $names)) {
                        $new_settings[] = [
                            "name" =>
                            stripcslashes(sanitize_text_field($name[$i])),
                            "width" =>
                            stripcslashes(sanitize_text_field($width[$i])),
                            "height" =>
                            stripcslashes(sanitize_text_field($height[$i])),
                            "crop" => stripcslashes(sanitize_text_field($crop[$i])),
                        ];
            
                        $names[] = stripcslashes(sanitize_text_field($name[$i]));
                    }
                }
            }
            

            self::updateSettings($new_settings);
            $settings_page_url = get_admin_url() .
                "options-general.php?page=" .
                self::$settings_page;
            ?>
            <script type="text/javascript">
                window.location= 
                "<?php echo $settings_page_url; ?>";
            </script>
            <?php
        }

        include_once "views/settings.php";
    }

    // Helpers -----------------------------------------------------------

    /**
     * Get the settings
     *
     * @return void
     */
    public function getSettings()
    {
        if (function_exists("is_multisite") && is_multisite()) {
            $settings = get_site_option(self::$prefix . "settings");
        } else {
            $settings = get_option(self::$prefix . "settings");
        }

        // Check if the setting is not set
        if (false === $settings) {
            $settings = [];
        }

        return $settings;
    }

    /**
     * Update the settings
     *
     * @param mixed $settings The new settings to update
     *
     * @return void
     */
    public function updateSettings($settings)
    {
        // Get the setting from a multisite
        if (function_exists("is_multisite") && is_multisite()) {
            $result = update_site_option(self::$prefix . "settings", $settings);
        } else {
            $result = update_option(self::$prefix . "settings", $settings);
        }

        return $result;
    }

    /**
     * Get plugin directory.
     *
     * @return string
     */
    public function pluginDir()
    {
        if (is_null($this->_plugin_dir)) {
            $this->_plugin_dir
                = untrailingslashit(plugin_dir_path(__FILE__)) . "/";
        }

        return $this->_plugin_dir;
    }

    /**
     * Get plugin url.
     *
     * @since  1.0.0
     * @return string
     */
    public function pluginUrl()
    {
        if (is_null($this->_plugin_url)) {
            $this->_plugin_url = untrailingslashit(plugin_dir_url(__FILE__)) . "/";
        }

        return $this->_plugin_url;
    }

    /**
     * Get plugin version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}

/**
 * Main instance of NNR_Custom_Image_Sizes.
 *
 * Returns the main instance of NNR_Custom_Image_Sizes
 * to prevent the need to use globals.
 *
 * @return NNR_Custom_Image_Sizes
 */
function Wps_Custom_sizes()
{
    return NNR_Custom_Image_Sizes::instance();
}
// Init the plugin.
Wps_Custom_sizes();
