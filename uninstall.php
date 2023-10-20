<?php
/**
 * Uninstall
 *
 * Php Version 7.2.10
 *
 * @category Plugin
 * @package  DraftPress_CustomImageSizes
 * @author   Draft <contact@draftpress.com>
 * @license  GNU General Public License 2
 * (https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
 * @link     https://draftpress.com/
 */


// If uninstall not called from WordPress exit
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete all existence of this plugin
$settings_name = 'nnr_custom_image_sizes_settings';

if (! is_multisite()) {
    // Delete blog options
    delete_option($settings_name);
} else {
    // Delete site options
    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        delete_option($settings_name);
    }

    restore_current_blog();
}
