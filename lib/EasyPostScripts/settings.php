<?php
/**
 *	Easy Post Settings
 *	Adapted for Easy Post from: http://pippinsplugins.com/stripe-integration-part-1-building-the-settings-and-a-simple-payment-form/
 */

function easy_post_settings_setup() {
	add_options_page('Easy Post Settings', 'Easy Post Settings', 'manage_options', 'easy-post-settings', 'easy_post_render_options_page');
}
add_action('admin_menu', 'easy_post_settings_setup');
 
function easy_post_render_options_page() {
	global $easypost_options;
	?>
	<div class="wrap">
		<h2><?php _e('Easy Post Settings', 'litton_bags'); ?></h2>
		<form method="post" action="options.php">
 
			<?php settings_fields('easypost_settings_group'); ?>
 
			<table class="form-table">
				<tbody>
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Test Mode', 'litton_bags'); ?>
						</th>
						<td>
							<input id="easypost_settings[test_mode]" name="easypost_settings[test_mode]" type="checkbox" value="1" <?php checked(1, $easypost_options['test_mode']); ?> />
							<label class="description" for="easypost_settings[test_mode]"><?php _e('Check this to run test mode.', 'litton_bags'); ?></label>
						</td>
					</tr>
				</tbody>
			</table>	
 
			<h3 class="title"><?php _e('API Keys', 'litton_bags'); ?></h3>
			<table class="form-table">
				<tbody>
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Live Secret', 'litton_bags'); ?>
						</th>
						<td>
							<input id="easypost_settings[live_secret_key]" name="easypost_settings[live_secret_key]" type="text" class="regular-text" value="<?php echo $easypost_options['live_secret_key']; ?>"/>
							<label class="description" for="easypost_settings[live_secret_key]"><?php _e('Paste your live secret key.', 'litton_bags'); ?></label>
						</td>
					</tr>

					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Test Secret', 'litton_bags'); ?>
						</th>
						<td>
							<input id="easypost_settings[test_secret_key]" name="easypost_settings[test_secret_key]" type="text" class="regular-text" value="<?php echo $easypost_options['test_secret_key']; ?>"/>
							<label class="description" for="easypost_settings[test_secret_key]"><?php _e('Paste your test secret key.', 'litton_bags'); ?></label>
						</td>
					</tr>

				</tbody>
			</table>

			<h3 class="title"><?php _e('Shipping Options', 'litton_bags'); ?></h3>
			<table class="form-table">
				<tbody>
					<!-- Company Name -->
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Company Name', 'litton_bags'); ?>
						</th>
						<td>
							<input id="easypost_settings[company_name]" name="easypost_settings[company_name]" type="text" class="regular-text" value="<?php echo $easypost_options['company_name']; ?>"/>
							<label class="description" for="easypost_settings[company_name]"><?php _e('Your official company name.', 'litton_bags'); ?></label>
						</td>
					</tr>
					<!-- Street 1 -->
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Street 1', 'litton_bags'); ?>
						</th>
						<td>
							<input id="easypost_settings[street_one]" name="easypost_settings[street_one]" type="text" class="regular-text" value="<?php echo $easypost_options['street_one']; ?>"/>
							<label class="description" for="easypost_settings[street_one]"><?php _e('The street in your address.', 'litton_bags'); ?></label>
						</td>
					</tr>
					<!-- City -->
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('City', 'litton_bags'); ?>
						</th>
						<td>
							<input id="easypost_settings[city]" name="easypost_settings[city]" type="text" class="regular-text" value="<?php echo $easypost_options['city']; ?>"/>
							<label class="description" for="easypost_settings[city]"><?php _e('The city in your address.', 'litton_bags'); ?></label>
						</td>
					</tr>
					<!-- State -->
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('State', 'litton_bags'); ?>
						</th>
						<td>
							<input id="easypost_settings[state]" name="easypost_settings[state]" type="text" class="regular-text" value="<?php echo $easypost_options['state']; ?>"/>
							<label class="description" for="easypost_settings[state]"><?php _e('The state in your address. PLEASE use abbr (e.g. HI, AK, CA, etc.)', 'litton_bags'); ?></label>
						</td>
					</tr>
					<!-- Zip -->
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Zip Code', 'litton_bags'); ?>
						</th>
						<td>
							<input id="easypost_settings[zip_code]" name="easypost_settings[zip_code]" type="text" class="regular-text" value="<?php echo $easypost_options['zip_code']; ?>"/>
							<label class="description" for="easypost_settings[zip_code]"><?php _e('The zip code in your address.', 'litton_bags'); ?></label>
						</td>
					</tr>
				</tbody>
			</table>

			<h3 class="title"><?php _e('Email Options', 'litton_bags'); ?></h3>
			<table class="form-table">
				<tbody>
					<!-- Shipping Confirmation Email -->
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Shipping Confirmation Email', 'litton_bags'); ?>
						</th>
						<td>
							<input id="easypost_settings[shipping_confirmation_email]" name="easypost_settings[shipping_confirmation_email]" type="text" class="regular-text" value="<?php echo $easypost_options['shipping_confirmation_email']; ?>"/>
							<label class="description" for="easypost_settings[shipping_confirmation_email]"><?php _e('Email address to notify when a product has been purchased and is ready for shipping.', 'litton_bags'); ?></label>
						</td>
					</tr>
				<tbody>
			</table>
 
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Options', 'litton_bags'); ?>" />
			</p>
 
		</form>
	<?php
}
 
function litton_bags_register_easypost_settings() {
	// creates our settings in the options table
	register_setting('easypost_settings_group', 'easypost_settings');
}
add_action('admin_init', 'litton_bags_register_easypost_settings');

?>