<div class="wrap">

	<h1>Site Initialization</h1>
	<p>Be aware that this will reset some site options site to the default if you init options.  Be sure this is what you want!</p>
	<p>This will:
		<ul>
			<li>Set site options for things like comments.  This will overwrite any changes made to these options.</li>
			<li>Create roles or reset their permissions.</li>
			<li>Add ITCS Support users to site.</li>
			<li>Add Creative Service users to site.</li>
		</ul>
	</p>

	<?php echo $form->get_message(); ?>

	<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">

	<input type="hidden" name="action" value="init_site_form" />

	<p><label for="site_id">Select Site</label></p>
	<p><select name="site_id" id="site_id">

	<?php 
		echo '<option value="0" ' . selected( $form->get_site(), 0, false ) . ' >All Sites</option>';
		$sites = $form->get_all_blogs();

		foreach($sites as $site) {
			echo '<option value="' . esc_attr($site->blog_id) . '" ' . selected( $form->get_site(), $site->blog_id, false ) . '>' . esc_attr($site->domain . $site->path) . '</option>';
		}
	?>

	</select></p>

	<?php echo wp_nonce_field( 'site_init', 'site-init' ); ?>

	<p><input type="checkbox" id="plugins" name="plugins"  
	<?php 
		if ( $form->get_plugins() ) {
			echo ' checked';
		}
	?>
	>
  	<label for="plugins">Configure Site Plugins</label></p>

 	<p><input type="checkbox" id="widgets" name="widgets"  
	<?php
		if ( $form->get_widgets() ) {
			echo ' checked';
		}
	?>
	>
  	<label for="widgets">Configure Site Widgets</label></p>

 	<p><input type="checkbox" id="options" name="options"  
  	<?php
		if ( $form->get_options() ) {
			echo ' checked';
		}
	?>
	>
  	<label for="options">Configure Site Options</label></p>

	<p><input type="checkbox" id="cron" name="cron"  
	<?php
		if ( $form->get_cron() ) {
			echo ' checked';
		}
	?>
	>
  	<label for="cron">Configure WP Cron</label></p>

 	<p><input type="checkbox" id="roles" name="roles"  
	<?php
		if ( $form->get_roles() ) {
			echo ' checked';
		}
	?>
	>
  	<label for="roles">Configure Roles</label></p>

 	<p><input type="checkbox" id="itcs" name="itcs"  
	<?php
		if ( $form->get_itcs() ) {
			echo ' checked';
		}
	?>
	>
  	<label for="itcs">Configure ITCS Users</label></p>

	<p><input type="checkbox" id="cs" name="cs"  
  	<?php
		if ( $form->get_cs() ) {
			echo ' checked';
		}
	?>
	>
  	<label for="cs">Configure Creative Services Users</label></p>

	<p><input class="button button-primary" type="submit" value="Initialize"></p>

	</form>

	<?php echo $form->render_results();	?>
	<?php echo $form->render_errors();	?>
</div>