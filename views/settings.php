<?php
/**
 * Settings page for the plugin.
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

<div class="nnr-wrap">

    <?php require_once 'header.php'; ?>

    <div class="nnr-container">

        <div class="nnr-content">

            <form method="post">

                <h1 id="nnr-heading">
                    <?php esc_html_e(
                        'Settings',
                        '99robots-custom-image-sizes'
                    ) ?>
                    <small>
                        <a class="<?php echo $settingRepeaterAddNewClass; ?>">
                        <i class="fa fa-plus fa-lg"></i> 
                        <?php esc_html_e(
                            'Add New',
                            '99robots-custom-image-sizes'
                        ) ?></a>
                    </small>
                </h1>
                
                <table class="table table-responsive table-striped">
                    <thead>
                        <th><?php esc_html_e(
                            'Name',
                            '99robots-custom-image-sizes'
                        ); ?></th>
                        <th><?php esc_html_e(
                            'Width',
                            '99robots-custom-image-sizes'
                        ); ?></th>
                        <th><?php esc_html_e(
                            'Height',
                            '99robots-custom-image-sizes'
                        ); ?></th>
                        <th><?php esc_html_e(
                            'Crop',
                            '99robots-custom-image-sizes'
                        ); ?></th>
                        <th><?php esc_html_e(
                            'Source',
                            '99robots-custom-image-sizes'
                        ); ?></th>
                    </thead>

                    <tbody class="<?php echo $settingRepeaterContainerClass; ?>">

                        <?php foreach ($sizes as $key => $size) { ?>
                            <tr class="<?php echo $settingRepeaterItemClass;?>">
                                <td 
                                class="<?php echo $settingRepeaterItemNameCls; ?>">
                                <?php echo $key; ?></td>
                                <td 
                                class="<?php echo $settingRepeaterItemWidthCls; ?>">
                                <?php echo $size['width']; ?> px</td>
                                <td 
                                class="<?php echo $settingRepeaterItemHeightCls; ?>">
                                <?php echo $size['height']; ?> px</td>
                                <td class="
                                <?php echo $settinsRepeaterItemCropClass; ?>">
                                <?php

                                $crop = esc_html__(
                                    'No',
                                    '99robots-custom-image-sizes'
                                );

                                if ($size['crop']) {
                                    $crop = esc_html__(
                                        'Yes',
                                        '99robots-custom-image-sizes'
                                    );
                                } elseif (is_array($size['crop'])) {
                                    $crop = implode(' ', $size['crop']);
                                }

                                echo $crop;
                                ?>
                            </td>
                                <td 
                                class="<?php echo $settingRepeaterItemActionCls; ?>">
                                <?php echo $size['source']; ?></td>
                            </tr>

                        <?php } ?>

                        <?php foreach ($settings as $key => $size) { ?>
                            <tr class="<?php echo $settingRepeaterItemClass; ?>">
                                <td 
                                class="<?php echo $settingRepeaterItemNameCls; ?>">
                                    <input type="text" class="form-control input-sm" 
                                    name="<?php echo self::$prefix_dash ?>name[]" 
                                    value="<?php echo $size['name']; ?>"/>
                                </td>
                                <td 
                                class="<?php echo $settingRepeaterItemWidthCls; ?>">
                                    <input type="text" class="form-control input-sm" 
                                    name="<?php echo self::$prefix_dash ?>width[]" 
                                    value="<?php echo $size['width']; ?>"/>
                                </td>
                                <td 
                                class="<?php echo $settingRepeaterItemHeightCls; ?>">
                                    <input type="text" class="form-control input-sm" 
                                    name="<?php echo self::$prefix_dash ?>height[]" 
                                    value="<?php echo $size['height']; ?>"/>
                                </td>
                                <td 
                                class="<?php echo $settingRepeaterItemCropClass;?>">
                                    <select class="form-control input-sm" 
                                    name="<?php echo self::$prefix_dash ?>crop[]">
                                        <option value="false" 
                                        <?php selected(
                                            'false',
                                            $size['crop'],
                                            true
                                        ); ?>>
                                            <?php esc_html_e(
                                                'No',
                                                '99robots-custom-image-sizes'
                                            ); ?>
                                        </option>
                                        <option value="true" 
                                        <?php selected(
                                            'true',
                                            $size['crop'],
                                            true
                                        ); ?>>
                                            <?php esc_html_e(
                                                'Yes',
                                                '99robots-custom-image-sizes'
                                            ); ?>
                                        </option>
                                        <option value="left_top" 
                                        <?php selected(
                                            'left_top',
                                            $size['crop'],
                                            true
                                        ); ?>>
                                            <?php esc_html_e(
                                                'Left Top',
                                                '99robots-custom-image-sizes'
                                            ); ?>
                                        </option>
                                        <option value="center_top" 
                                        <?php selected(
                                            'center_top',
                                            $size['crop'],
                                            true
                                        ); ?>>
                                            <?php esc_html_e(
                                                'Center Top',
                                                '99robots-custom-image-sizes'
                                            ); ?>
                                        </option>
                                        <option value="right_top" 
                                        <?php selected(
                                            'right_top',
                                            $size['crop'],
                                            true
                                        ); ?>>
                                            <?php esc_html_e(
                                                'Right Top',
                                                '99robots-custom-image-sizes'
                                            ); ?>
                                        </option>
                                        <option value="left_center" 
                                        <?php selected(
                                            'left_center',
                                            $size['crop'],
                                            true
                                        ); ?>>
                                            <?php esc_html_e(
                                                'Left Center',
                                                '99robots-custom-image-sizes'
                                            ); ?>
                                        </option>
                                        <option value="center_center" 
                                        <?php selected(
                                            'center_center',
                                            $size['crop'],
                                            true
                                        ); ?>>
                                            <?php esc_html_e(
                                                'Center Center',
                                                '99robots-custom-image-sizes'
                                            ); ?>
                                        </option>
                                        <option value="right_center" 
                                        <?php selected(
                                            'right_center',
                                            $size['crop'],
                                            true
                                        ); ?>>
                                            <?php esc_html_e(
                                                'Right Center',
                                                '99robots-custom-image-sizes'
                                            ); ?>
                                        </option>
                                        <option value="left_bottom" 
                                        <?php selected(
                                            'left_bottom',
                                            $size['crop'],
                                            true
                                        ); ?>>
                                            <?php esc_html_e(
                                                'Left Bottom',
                                                '99robots-custom-image-sizes'
                                            ); ?>
                                        </option>
                                        <option value="center_bottom" 
                                        <?php selected(
                                            'center_bottom',
                                            $size['crop'],
                                            true
                                        ); ?>>
                                            <?php esc_html_e(
                                                'Center Bottom',
                                                '99robots-custom-image-sizes'
                                            ); ?>
                                        </option>
                                        <option value="right_bottom" 
                                        <?php selected(
                                            'right_bottom',
                                            $size['crop'],
                                            true
                                        ); ?>>
                                            <?php esc_html_e(
                                                'Right Bottom',
                                                '99robots-custom-image-sizes'
                                            ); ?>
                                        </option>
                                    </select>
                                </td>
                                <td 
                                class="<?php echo $settingRepeaterItemActionCls; ?>">
                                    <i 
                                    class="<?php echo $settingRepeaterRemoveClass?>">
                                    </i>
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>
                </table>

                <?php wp_nonce_field(self::$prefix . 'settings') ?>
                <p>
                    <button class="btn btn-info" type="submit" 
                    name="submit"><i class="fa fa-hdd-o fa-lg"></i> 
                    <?php esc_html_e('Save', '99robots-custom-image-sizes') ?>
                </button>
                </p>

            </form>
            <?php
            $settingsModalId
                = self::$prefix_dash .'delete-image-size-modal';
            ?>
            <div class="modal fade" id="<?php echo $settingsModalId; ?>">
                <div class="modal-dialog" style="margin-top: 10vh;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" 
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">
                                <?php esc_html_e(
                                    'Are you sure?',
                                    '99robots-custom-image-sizes'
                                ) ?>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <p><?php esc_html_e(
                                'Are you sure you want to 
							delete this image size? If so, all future image 
							uploads will not have this custom image size generated.',
                                '99robots-custom-image-sizes'
                            ) ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" 
                            data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger 
                            <?php echo self::$prefix_dash ?>delete-image-size" 
                            data-dismiss="modal">
                                <?php
                                esc_html_e(
                                    'Delete',
                                    '99robots-custom-image-sizes'
                                ); ?>
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </div>

        <?php require_once 'sidebar.php' ?>

    </div>

    <?php require_once 'footer.php' ?>

</div>
