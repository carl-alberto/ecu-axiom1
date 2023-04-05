<div class="wrap">
	<h1>Multi Site Initialization</h1>

	<p>No harm will be done to a site if this is run repeatadly.</p>
	<p>This will:
	<ul>
		<li>Network Activates Plugins.</li>
		<li>Site Activates Plugins for a root site.</li>
		<li>Site Roles, ITCS Support Users, and site options for a root site.</li>
	</ul>
	</p>

  	<?php echo $form->get_message(); ?>

  	<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">

	  	<input type="hidden" name="action" value="init_multisite_form" />

		<?php echo wp_nonce_field( 'multisite_init', 'multisite-init' ); ?>

		<p><input class="button button-primary" type="submit" value="Initialize"></p>

	</form>
	
	<?php echo $form->render_results();	?>
	<?php echo $form->render_errors();	?>
</div>