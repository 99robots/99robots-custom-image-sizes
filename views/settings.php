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
					<?php
					esc_html_e(
						'Settings',
						'99robots-custom-image-sizes'
					)
					?>
					<small>
						<a class="<?php echo esc_attr( $setting_repeater_add_new_class ); ?>">
						<i class="fa fa-plus fa-lg"></i> 
						<?php
						esc_html_e(
							'Add New',
							'99robots-custom-image-sizes'
						)
						?>
						</a>
					</small>
				</h1>
				
				<table class="table table-responsive table-striped">
					<thead>
						<th>
						<?php
						esc_html_e(
							'Name',
							'99robots-custom-image-sizes'
						);
						?>
						</th>
						<th>
						<?php
						esc_html_e(
							'Width',
							'99robots-custom-image-sizes'
						);
						?>
						</th>
						<th>
						<?php
						esc_html_e(
							'Height',
							'99robots-custom-image-sizes'
						);
						?>
						</th>
						<th>
						<?php
						esc_html_e(
							'Crop',
							'99robots-custom-image-sizes'
						);
						?>
						</th>
						<th>
						<?php
						esc_html_e(
							'Source',
							'99robots-custom-image-sizes'
						);
						?>
						</th>
					</thead>

					<tbody class="<?php echo esc_attr( $setting_repeater_container_class ); ?>">
					<?php foreach ( $sizes as $key => $size ) { ?>
						<tr class="<?php echo esc_attr( $setting_repeater_item_class ); ?>">
						<td class="<?php echo esc_attr( $setting_repeater_item_name_cls ); ?>">
							<?php echo esc_html( $key ); ?>
						</td>
						<td class="<?php echo esc_attr( $setting_repeater_item_width_cls ); ?>">
							<?php echo esc_html( $size['width'] ); ?> px
						</td>
						<td class="<?php echo esc_attr( $setting_repeater_item_height_cls ); ?>">
							<?php echo esc_html( $size['height'] ); ?> px
						</td>
						<td class="<?php echo esc_attr( $setting_repeater_item_crop_class ); ?>">
							<?php
							$crop = esc_html__( 'No', '99robots-custom-image-sizes' );

							if ( $size['crop'] ) {
								$crop = esc_html__( 'Yes', '99robots-custom-image-sizes' );
							} elseif ( is_array( $size['crop'] ) ) {
								$crop = implode( ' ', $size['crop'] );
							}

							echo esc_html( $crop );
							?>
						</td>
						<td class="<?php echo esc_attr( $setting_repeater_item_action_class ); ?>">
							<?php echo esc_html( $size['source'] ); ?>
						</td>
						</tr>
					<?php } ?>

					<?php foreach ( $settings as $key => $size ) { ?>
						<tr class="<?php echo esc_attr( $setting_repeater_item_class ); ?>">
						<td class="<?php echo esc_attr( $setting_repeater_item_name_cls ); ?>">
							<input type="text" class="form-control input-sm" name="<?php echo esc_attr( self::$prefix_dash ); ?>name[]"
							value="<?php echo esc_attr( $size['name'] ); ?>" />
						</td>
						<td class="<?php echo esc_attr( $setting_repeater_item_width_cls ); ?>">
							<input type="text" class="form-control input-sm" name="<?php echo esc_attr( self::$prefix_dash ); ?>width[]"
							value="<?php echo esc_attr( $size['width'] ); ?>" />
						</td>
						<td class="<?php echo esc_attr( $setting_repeater_item_height_cls ); ?>">
							<input type="text" class="form-control input-sm" name="<?php echo esc_attr( self::$prefix_dash ); ?>height[]"
							value="<?php echo esc_attr( $size['height'] ); ?>" />
						</td>
						<td class="<?php echo esc_attr( $setting_repeater_item_crop_class ); ?>">
							<select class="form-control input-sm" name="<?php echo esc_attr( self::$prefix_dash ); ?>crop[]">
							<?php
							$crop_values = array(
								'false'         => 'No',
								'true'          => 'Yes',
								'left_top'      => 'Left Top',
								'center_top'    => 'Center Top',
								'right_top'     => 'Right Top',
								'left_center'   => 'Left Center',
								'center_center' => 'Center Center',
								'right_center'  => 'Right Center',
								'left_bottom'   => 'Left Bottom',
								'center_bottom' => 'Center Bottom',
								'right_bottom'  => 'Right Bottom',
							);
							foreach ( $crop_values as $value => $label ) {
								$selected = selected( $value, $size['crop'], true );
								echo '<option value="' . esc_attr( $value ) . '"' . esc_attr( $selected ) . '>' . esc_html( $label ) . '</option>';
							}
							?>
							</select>
						</td>
						<td class="<?php echo esc_attr( $setting_repeater_item_action_class ); ?>">
							<i class="<?php echo esc_attr( $setting_repeater_remove_class ); ?>"></i>
						</td>
						</tr>
					<?php } ?>
					</tbody>

				</table>

				<?php wp_nonce_field( self::$prefix . 'settings' ); ?>
				<p>
					<button class="btn btn-info" type="submit" 
					name="submit"><i class="fa fa-hdd-o fa-lg"></i> 
					<?php esc_html_e( 'Save', '99robots-custom-image-sizes' ); ?>
				</button>
				</p>

			</form>
			<?php
			$settings_modal_id
				= self::$prefix_dash . 'delete-image-size-modal';
			?>
			<div class="modal fade" id="<?php echo esc_attr( $settings_modal_id ); ?>">
				<div class="modal-dialog" style="margin-top: 10vh;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" 
							data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">
								<?php
								esc_html_e(
									'Are you sure?',
									'99robots-custom-image-sizes'
								)
								?>
							</h4>
						</div>
						<div class="modal-body">
							<p>
							<?php
							esc_html_e(
								'Are you sure you want to 
							delete this image size? If so, all future image 
							uploads will not have this custom image size generated.',
								'99robots-custom-image-sizes'
							)
							?>
							</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" 
							data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-danger 
							<?php echo esc_html( self::$prefix_dash ); ?>delete-image-size" 
							data-dismiss="modal">
								<?php
								esc_html_e(
									'Delete',
									'99robots-custom-image-sizes'
								);
								?>
							</button>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

		</div>

		<?php require_once 'sidebar.php'; ?>

	</div>

	<?php require_once 'footer.php'; ?>

</div>
