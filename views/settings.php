<div class="nnr-wrap">

	<?php require_once('header.php'); ?>

	<div class="nnr-container">

		<div class="nnr-content">

			<form method="post">

				<h1 id="nnr-heading"><?php _e('Settings', self::$text_domain); ?>
					<small>
						<a class="nnr-heading-button-left btn btn-success <?php echo self::$prefix_dash; ?>repeater-add-new"><i class="fa fa-plus fa-lg"></i> <?php _e('Add New', self::$text_domain); ?></a>
					</small>
				</h1>

				<table class="table table-responsive table-striped">
					<thead>
						<th><?php _e('Name', self::$text_domain); ?></th>
						<th><?php _e('Width', self::$text_domain); ?></th>
						<th><?php _e('Height', self::$text_domain); ?></th>
						<th><?php _e('Crop', self::$text_domain); ?></th>
						<th><?php _e('Source', self::$text_domain); ?></th>
					</thead>

					<tbody class="<?php echo self::$prefix_dash; ?>repeater-container">

						<?php foreach( $sizes as $key => $size ) { ?>

							<tr class="<?php echo self::$prefix_dash; ?>repeater-item">
								<td class="<?php echo self::$prefix_dash; ?>repeater-item-name"><?php echo $key; ?></td>
								<td class="<?php echo self::$prefix_dash; ?>repeater-item-width"><?php echo $size['width']; ?> px</td>
								<td class="<?php echo self::$prefix_dash; ?>repeater-item-height"><?php echo $size['height']; ?> px</td>
								<td class="<?php echo self::$prefix_dash; ?>repeater-item-crop"><?php

									$crop = __('No', self::$text_domain);

									if ( $size['crop'] ) {
										$crop = __('Yes', self::$text_domain);
									} else if ( is_array($size['crop']) ) {
										$crop = implode(' ', $size['crop']);
									}

									echo $crop;
								?></td>
								<td class="<?php echo self::$prefix_dash; ?>repeater-item-action"><?php echo $size['source']; ?></td>
							</tr>

						<?php } ?>

						<?php foreach( $settings as $key => $size ) { ?>

							<tr class="<?php echo self::$prefix_dash; ?>repeater-item">
								<td class="<?php echo self::$prefix_dash; ?>repeater-item-name">
									<input type="text" class="form-control input-sm" name="<?php echo self::$prefix_dash; ?>name[]" value="<?php echo $size['name']; ?>"/>
								</td>
								<td class="<?php echo self::$prefix_dash; ?>repeater-item-width">
									<input type="text" class="form-control input-sm" name="<?php echo self::$prefix_dash; ?>width[]" value="<?php echo $size['width']; ?>"/>
								</td>
								<td class="<?php echo self::$prefix_dash; ?>repeater-item-height">
									<input type="text" class="form-control input-sm" name="<?php echo self::$prefix_dash; ?>height[]" value="<?php echo $size['height']; ?>"/>
								</td>
								<td class="<?php echo self::$prefix_dash; ?>repeater-item-crop">
									<select class="form-control input-sm" name="<?php echo self::$prefix_dash; ?>crop[]">
										<option value="false" <?php selected('false', $size['crop'], true); ?>><?php _e('No', self::$text_domain); ?></option>
										<option value="true" <?php selected('true', $size['crop'], true); ?>><?php _e('Yes', self::$text_domain); ?></option>
										<option value="left_top" <?php selected('left_top', $size['crop'], true); ?>><?php _e('Left Top', self::$text_domain); ?></option>
										<option value="center_top" <?php selected('center_top', $size['crop'], true); ?>><?php _e('Center Top', self::$text_domain); ?></option>
										<option value="right_top" <?php selected('right_top', $size['crop'], true); ?>><?php _e('Right Top', self::$text_domain); ?></option>
										<option value="left_center" <?php selected('left_center', $size['crop'], true); ?>><?php _e('Left Center', self::$text_domain); ?></option>
										<option value="center_center" <?php selected('center_center', $size['crop'], true); ?>><?php _e('Center Center', self::$text_domain); ?></option>
										<option value="right_center" <?php selected('right_center', $size['crop'], true); ?>><?php _e('Right Center', self::$text_domain); ?></option>
										<option value="left_bottom" <?php selected('left_bottom', $size['crop'], true); ?>><?php _e('Left Bottom', self::$text_domain); ?></option>
										<option value="center_bottom" <?php selected('center_bottom', $size['crop'], true); ?>><?php _e('Center Bottom', self::$text_domain); ?></option>
										<option value="right_bottom" <?php selected('right_bottom', $size['crop'], true); ?>><?php _e('Right Bottom', self::$text_domain); ?></option>
									</select>
								</td>
								<td class="<?php echo self::$prefix_dash; ?>repeater-item-action">
									<i class="<?php echo self::$prefix_dash; ?>repeater-remove fa fa-trash-o"></i>
								</td>
							</tr>

						<?php } ?>

					</tbody>
				</table>

				<?php wp_nonce_field(self::$prefix . 'settings'); ?>

				<p>
					<button class="btn btn-info" type="submit" name="submit"><i class="fa fa-hdd-o fa-lg"></i> <?php _e('Save', self::$text_domain); ?></button>
				</p>

			</form>

			<div class="modal fade" id="<?php echo self::$prefix_dash; ?>delete-image-size-modal">
				<div class="modal-dialog" style="margin-top: 10vh;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"><?php _e('Are you sure?', self::$text_domain); ?></h4>
						</div>
						<div class="modal-body">
							<p><?php _e('Are you sure you want to delete this image size? If so, all future image uploads will not have this custom image size generated.', self::$text_domain); ?></p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-danger <?php echo self::$prefix_dash; ?>delete-image-size" data-dismiss="modal"><?php _e('Delete', self::$text_domain); ?></button>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

		</div>

		<?php require_once('sidebar.php'); ?>

	</div>

	<?php require_once('footer.php'); ?>

</div>