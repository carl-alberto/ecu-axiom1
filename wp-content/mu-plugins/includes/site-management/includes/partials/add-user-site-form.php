<div id="wrap" class="wrap">
	<h1>Add Users</h1>

	<p>This will do nothing if the user is already on the site.</p>

	<?php echo $form->get_message(); ?>

	<form method="post" name="add_users_site" action="<?php echo admin_url('admin-post.php'); ?>">

	<input type="hidden" name="action" value="add_user_site_form" />

	<div id="pirateid">
	    <p><label for="pirate_ids">Pirate Ids, Separated with a Comma:</label></p>
	    <p><input size="40" placeholder="pirateid1,pirateid2,pirateid3,..." name="pirate_ids" value="<?php echo esc_attr($form->get_pirate_ids()); ?>"></p>
	</div>

	<p><label for="role">Role:</label></p>    	
	<p><select name="role" />			
	<?php
		$wp_roles = $form->get_all_roles();
		foreach($wp_roles as $r) {
			echo '<option value="' . esc_attr($r) . '" ' . selected( $form->get_role(), $r) . '>' . esc_attr($r) . '</option>';
		}
	?>
	</select></p>

	<input type="hidden" name="blogs[]" value="<?php echo get_current_blog_id(); ?>">
	<?php
		wp_nonce_field( 'wp_add_user_site', 'wp-add-user-site' );
	?>
<br />
	<p><input class="button button-primary" type="submit" value="Add Users"></p>
	   
	</form>

    <?php echo $form->render_results();	?>
    <?php echo $form->render_errors(); ?>

</div>