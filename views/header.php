<?php
/**
 * Header for the plugin.
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

?>

<?php
$setting_repeater_add_new_class     = 'nnr-heading-button-left btn btn-success ' . self::$prefix_dash . 'repeater-add-new';
$setting_repeater_container_class   = self::$prefix_dash . 'repeater-container';
$setting_repeater_item_crop_class   = self::$prefix_dash . 'repeater-item-crop';
$setting_repeater_item_action_class = self::$prefix_dash . 'repeater-item-action';
$setting_repeater_remove_class      = self::$prefix_dash . 'repeater-remove fa fa-trash-o';
$setting_repeater_item_class        = self::$prefix_dash . 'repeater-item';
$setting_repeater_item_name_cls     = self::$prefix_dash . 'repeater-item-name';
$setting_repeater_item_width_cls    = self::$prefix_dash . 'repeater-item-width';
$setting_repeater_item_height_cls   = self::$prefix_dash . 'repeater-item-height';
?>

<!-- Header -->
<div class="nnr-header">
	<div class="nnr-logo"></div>
	<div class="nnr-product-details">
		<span class="nnr-product-name">
			<?php esc_html_e( 'Custom Image Sizes', '99robots-custom-image-sizes' ); ?>
		</span>
		<span class="nnr-product-version">
			<?php echo esc_html( NNR_Custom_Image_Sizes::get_version() ); ?>
		</span>
	</div>
	<a href="http://draftpress.com/products" target="_blank">
		<button class="nnr-header-button pull-right">
			<?php esc_html_e( 'More Products', '99robots-custom-image-sizes' ); ?>
		</button>
	</a>
</div>
