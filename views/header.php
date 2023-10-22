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
$settingRepeaterAddNewClass
    = 'nnr-heading-button-left btn btn-success ';
$settingRepeaterAddNewClass
    .= self::$prefix_dash . 'repeater-add-new';

$settingRepeaterContainerClass
    = self::$prefix_dash .'repeater-container';
$settingRepeaterItemCropClass
    = self::$prefix_dash .'repeater-item-crop';
$settingRepeaterItemActionCls
    = self::$prefix_dash . 'repeater-item-action';
$settingRepeaterRemoveClass
    = self::$prefix_dash. 'repeater-remove fa fa-trash-o';
$settingRepeaterItemClass
    = self::$prefix_dash . 'repeater-item';
$settingRepeaterItemNameCls
    = self::$prefix_dash . 'repeater-item-name';

$settingRepeaterItemWidthCls
    = self::$prefix_dash . 'repeater-item-width';

$settingRepeaterItemHeightCls
    = self::$prefix_dash . 'repeater-item-height';

?>

<!-- Header -->
<div class="nnr-header">
    <div class="nnr-logo"></div>
    <div class="nnr-product-details">
        <span class="nnr-product-name">
            <?php
            esc_html_e(
                'Custom Image Sizes',
                '99robots-custom-image-sizes'
            ); ?></span>
        <span class="nnr-product-version">
            <?php echo Wps_Custom_sizes()->getVersion() ?>
        </span>
    </div>
    <a href="http://draftpress.com/products" 
    target="_blank">
    <button class="nnr-header-button pull-right">
        <?php esc_html_e('More Products', '99robots-custom-image-sizes') ?>
    </button></a>
</div>