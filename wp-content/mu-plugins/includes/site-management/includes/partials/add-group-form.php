<div id="wrap" class="wrap">
	<h1>Add Users from an AD Group</h1>

	<p>This will do nothing if the user is already on the site.  If you don't want to add sub groups then be sure to set that to no.</p>

	<?php echo $form->get_message(); ?>

	<form method="post" name="add_groups" action="<?php echo admin_url('admin-post.php'); ?>">

	<input type="hidden" name="action" value="add_group_form" />

	<p><label for="ad_groups">AD Groups, Seperated with a Comma:</label></p>
	<p><input size="40" placeholder="group_one,group_two,group_three,..." name="ad_groups" value="<?php echo esc_attr($form->get_ad_groups()); ?>"></p>

	<p><label for="recursive">Add users from sub groups:</label></p>
	<p><select id="recursive" name="recursive" >
		<option value="1" <?php selected($form->get_recursive());?>>Yes</option>
		<option value="0" <?php selected($form->get_recursive(), false);?>>No</option>
	</select></p>

	<p><label for="role">Role:</label></p>    	
	<p><select name="role" />			
	<?php
		$wp_roles = $form->get_all_roles();
		foreach($wp_roles as $r) {
			echo '<option value="' . esc_attr($r) . '" ' . selected( $form->get_role(), $r) . '>' . esc_attr($r) . '</option>';
		}
	?>
	</select></p>

	<p><label for="blogs">Blogs:</label></p>			
	<p><select multiple="multiple" id="blogs" name="blogs[]" />	
	<?php
		$sites = $form->get_all_blogs();
		$blogs = $form->get_blogs();	
		echo '<option value="0" ' . selected( in_array(0, $blogs)) . '>All Blogs</option>';	
		foreach($sites as $site) {
			echo '<option value="' . esc_attr($site->blog_id) . '" ' . selected( in_array($site->blog_id, $blogs) ) . '>' . esc_attr($site->domain . $site->path) . '</option>';
		}
	?>
	</select></p>
	
	<?php wp_nonce_field( 'wp_add_group', 'wp-add-group' ); ?>

	<p><input class="button button-primary" type="submit" value="Add Users"></p>
	   
	</form>

    <?php echo $form->render_results();	?>

</div>

<script type="text/javascript">

	jQuery(document).ready(function($){
		$("#blogs").select2();
	});

</script>