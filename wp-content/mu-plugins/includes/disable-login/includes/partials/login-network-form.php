<div class="wrap">
		
	<h1>Disable Login for MultiSite</h1>

	<?php echo $form->get_message(); ?>
	
	<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">

	<input type="hidden" name="action" value="network_login_form" />

	<?php echo wp_nonce_field( 'wp_management_network_login', 'wp-management-network-login' ); ?>
	
	<fieldset id="wp_login_enabled">
		<legend>
			<ul>
				<li>Prevent all users from logging into the admin area except for ITCS Support and Super Admins for all sites in this multisite.</li>
				<li>Adds a default message to the login screen for all sites in this multisite. </li>
			</ul>
		</legend></p>
		<p>Is login disabled?
		<p>
			<label id="wp_login_enabled_yes" class="checkbox">
				<input type="radio" value="1" name="disable_login" <?php checked( $form->get_disabled(), 1 ); ?>>Yes
			</label>
		</p>
		<p>
			<label id="wp_login_enabled_no" class="checkbox">
				<input type="radio" value="0" name="disable_login" <?php checked( $form->get_disabled(), 0 ); ?>>No
			</label>
		</p>

	</fieldset>

	<p class="submit"><input type="submit" class="button button-primary" value="Save"></p>

	</form>

</div>