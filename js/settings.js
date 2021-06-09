jQuery(function($){

	// Add a new one

	$('.' + nnr_cis_settings_data.prefix + 'repeater-add-new').on("click", function(){

		var new_row = '<tr class="' + nnr_cis_settings_data.prefix + 'repeater-item">' +
			'<td class="' + nnr_cis_settings_data.prefix + 'repeater-item-name"><input type="text" class="form-control input-sm" name="' + nnr_cis_settings_data.prefix + 'name[]" value="New Image Size"/></td>' +
			'<td class="' + nnr_cis_settings_data.prefix + 'repeater-item-width"><input type="text" class="form-control input-sm" name="' + nnr_cis_settings_data.prefix + 'width[]" value="300"/></td>' +
			'<td class="' + nnr_cis_settings_data.prefix + 'repeater-item-height"><input type="text" class="form-control input-sm" name="' + nnr_cis_settings_data.prefix + 'height[]" value="300"/></td>' +
			'<td class="' + nnr_cis_settings_data.prefix + 'repeater-item-crop"><select class="form-control input-sm" name="' + nnr_cis_settings_data.prefix + 'crop[]">' +
				'<option value="false">No</option>' +
				'<option value="true">Yes</option>' +
				'<option value="left_top">Left Top</option>' +
				'<option value="center_top">Center Top</option>' +
				'<option value="right_top">Right Top</option>' +
				'<option value="left_center">Left Center</option>' +
				'<option value="center_center">Center Center</option>' +
				'<option value="right_center">Right Center</option>' +
				'<option value="left_bottom">Left Bottom</option>' +
				'<option value="center_bottom">Center Bottom</option>' +
				'<option value="right_bottom">Right Bottom</option>' +
			'</select></td>' +
			'<td class="' + nnr_cis_settings_data.prefix + 'repeater-item-action"><i class="' + nnr_cis_settings_data.prefix + 'repeater-remove fa fa-trash-o"></i></td>' +
		'</tr>';

		$("." + nnr_cis_settings_data.prefix + "repeater-container").append(new_row);

	});

	// Remove one

	$('.' + nnr_cis_settings_data.prefix + 'repeater-remove').on('click', function(){

		$('#' + nnr_cis_settings_data.prefix + 'delete-image-size-modal').modal();

		var delete_image_size = $(this);

		$('.' + nnr_cis_settings_data.prefix + 'delete-image-size').on("click", function(){
			delete_image_size.parent().parent().remove();
		});

		//$(this).parent().parent().remove();

	});

	//$("." + nnr_cis_settings_data.prefix + "repeater-container").sortable();

});