<div class="wrap">
		
	<h1>Disable Login for this Site</h1>
		
	

	<?php

	$disable_network_login = get_site_option('wp-site-management-network-disable-login', false, false);

	if($disable_network_login):

	?>

	<div class="update-nag">Login has been disabled for the multisite.   If you wish to change this then you need to go the <a href="<?php echo admin_url('network/settings.php?page=disable-login'); ?>">network settings</a>.</div>

	<?php else:
	
	echo $form->get_message(); ?>
	
	<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">

	<input type="hidden" name="action" value="site_login_form" />

	<?php echo wp_nonce_field( 'wp_management_login', 'wp-management-login' ); ?>
	
	<fieldset id="wp_login_enabled">
		<legend>
			<ul>
				<li>Prevent all users from logging into the admin area except for ITCS Support and Super Admins for this site only.</li>
				<li>Adds a default message to the login screen for this site only. </li>
			</ul>
		</legend>
		<p>Is login disabled?</p>
		<p>
			<label id="wp_login_enabled_yes" class="checkbox">
				<input type="radio" value="1" name="disable_login"   <?php checked( $form->get_disabled(), 1 ); ?>>Yes
			</label>
		</p>
		<p>
			<label id="wp_login_enabled_no" class="checkbox">
				<input type="radio" value="0" name="disable_login"  <?php checked( $form->get_disabled(), 0 ); ?>>No
			</label>
		</p>

	</fieldset>

	<p class="submit"><input type="submit" class="button button-primary" value="Save"></p>

	</form>

	<?php endif; ?>
</div>