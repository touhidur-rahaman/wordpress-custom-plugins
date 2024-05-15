<?php

function dfm_custom_menu_page()
{
	add_menu_page(
		'DFM Admin Menu Setting',    // Page title
		'DFM Admin Menu Setting',    // Menu title
		'manage_options', // Capability required to access the menu
		'dfm-custom-menu',    // Menu slug
		'dfm_custom_menu_callback', // Callback function to render the menu page
		'dashicons-admin-generic', // Menu icon (optional)
		99                 // Menu position (optional)
	);
}
add_action('admin_menu', 'dfm_custom_menu_page');

// Callback function to render the menu page
function dfm_custom_menu_callback()
{
	$dfm_custom_admin_logo = get_option('dfm_custom_admin_logo');
	$dfm_custom_admin_color = get_option('dfm_custom_admin_color');
	$dfm_custom_login_header_color = get_option('dfm_custom_login_header_color');
	
?>
	<div class="wrap">
		<h1>Custom Input Field Settings</h1>
		<form id="custom-input-form" method="post" enctype="multipart/form-data">
			<?php settings_fields('custom_input_field'); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="dfm_custom_admin_logo">File Upload:</label></th>
					<td>
						<?php
						if ($dfm_custom_admin_logo) {
							echo '<img src="' . esc_url($dfm_custom_admin_logo) . '" id="preview-image" style="max-width: 100px; max-height: 100px;" />';
						}
						?>
						<input type="hidden" name="dfm_custom_admin_logo" id="dfm_custom_admin_logo" value="<?php echo esc_attr($dfm_custom_admin_logo); ?>" />
						<button class="button button-secondary" id="upload_image_button">Upload/Select Image</button>
						<p class="description">Upload or select an image from the media library.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="dfm_custom_admin_color">Select Color:</label></th>
					<td>
						<input type="color" name="dfm_custom_admin_color" id="dfm_custom_admin_color" value="<?php echo esc_attr($dfm_custom_admin_color); ?>" />
						<p class="description">Select a color for the admin menu.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="dfm_custom_login_header_color">Select Login Menu Header BG:</label></th>
					<td>
						<input type="color" name="dfm_custom_login_header_color" id="dfm_custom_login_header_color" value="<?php echo esc_attr($dfm_custom_login_header_color); ?>" />
						<p class="description">Select a color for the Login Menu Header BG.</p>
					</td>
				</tr>
			</table>
			<?php submit_button('Save Changes'); ?>
		</form>
	</div>

	<script>
	jQuery(document).ready(function($) {
		$('#upload_image_button').click(function(e) {
			e.preventDefault();
			var custom_uploader = wp.media({
				title: 'Choose Image',
				button: {
					text: 'Select'
				},
				multiple: false,
				library: {
					type: 'image'
				}
			});

			custom_uploader.on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$('#dfm_custom_admin_logo').val(attachment.url);
				$('#preview-image').attr('src', attachment.url);
			});

			custom_uploader.open();
		});

		$('#custom-input-form').submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			formData += '&action=save_dfm_custom_settings'; // Add action parameter
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: formData,
				success: function(response) {
					// Update UI or show success message
					alert('Settings saved successfully!');
				},
				error: function(xhr, status, error) {
					alert('Error occurred while saving settings.');
				}
			});
		});
	});
	</script>
<?php
}


add_action('wp_ajax_save_dfm_custom_settings', 'save_dfm_custom_settings_callback');

function save_dfm_custom_settings_callback() {
    if (isset($_POST['dfm_custom_admin_logo']) && isset($_POST['dfm_custom_admin_color'])&& isset($_POST['dfm_custom_login_header_color'])) {
        $new_logo_value = sanitize_text_field($_POST['dfm_custom_admin_logo']);
        $new_color_value = sanitize_hex_color($_POST['dfm_custom_admin_color']);
        $new_login_header_color_value = sanitize_hex_color($_POST['dfm_custom_login_header_color']);
        update_option('dfm_custom_admin_logo', $new_logo_value);
        update_option('dfm_custom_admin_color', $new_color_value);
        update_option('dfm_custom_login_header_color', $new_login_header_color_value);
        echo 'success'; // Send success response
    } else {
        echo 'error'; // Send error response if data is not received
    }
    wp_die(); // Always remember to terminate the script using wp_die()
}
