<div id="wrap" class="wrap">
    <h1>Intranet Settings</h1>

    <?php echo $form->get_message(); ?>

    <form method="post" name="add_users" action="<?php echo admin_url('admin-post.php'); ?>" >

    <input type="hidden" name="action" value="intranet_form" />
        
    <p>
    <fieldset id="wp_intranet_enabled">
        <legend>Is intranet enabled?</legend>
        <p><label id="wp_intranet_enabled_no" class="checkbox">
            <input type="radio" value="0" name="wp_intranet[enabled]"  <?php checked( !$form->get_enabled() ); ?>>No
        </label>
        </p>
        <p><label id="wp_intranet_enabled_yes" class="checkbox">
            <input type="radio" value="1" name="wp_intranet[enabled]"   <?php checked( $form->get_enabled() ); ?>>Yes
        </label></p>
    </fieldset>
    
    <p>
    <legend>If enabled then restrict the site to:</legend></p>
     <p><label class="checkbox" for="wp_intranet_option_1">
        <input id="wp_intranet_option_1" type="radio" name="wp_intranet[type]" value="1" <?php checked( $form->get_type() == 1 ); ?> />
        Any Valid Pirate ID 
    </label>
   </p>
     <p><label class="checkbox" for="wp_intranet_option_2">
        <input id="wp_intranet_option_2" type="radio" name="wp_intranet[type]" value="2" <?php checked( $form->get_type() == 2 ); ?> />
        Site Members
     <p></label>
   </p>
     <p><label class="checkbox" for="wp_intranet_option_4">
        <input id="wp_intranet_option_4" type="radio" name="wp_intranet[type]" value="4"  <?php checked( $form->get_type() == 4 );  ?> />
        Students
    </label>
    </p>
     <p><label class="checkbox" for="wp_intranet_option_5">
        <input id="wp_intranet_option_5" type="radio" name="wp_intranet[type]" value="5" <?php checked( $form->get_type() == 5 ); ?> />
        Faculty &amp; Staff
    </label>
    </p>
    <p><label class="checkbox" for="wp_intranet_option_3">
    <input id="wp_intranet_option_3" type="radio" name="wp_intranet[type]" value="3" <?php checked( $form->get_type() == 3 ); ?> />
    Specific AD Group(s) and/or User(s): 
    </label>
        <p><label class="checkbox" for="wp_intranet_option_3_group" style="margin-left:35px;">Group(s)  </label><input size="40" placeholder="group_one, group_two,..." id="wp_intranet_option_3_group" name="wp_intranet[groups]" type="text" 
            value="<?php echo esc_attr($form->get_ad_groups()); ?>" /></p>
        <p><label class="checkbox" for="wp_intranet_option_3_account" style="margin-left:25px;">Account(s)  </label><input size="40" placeholder="user_one, user_two,..." id="wp_intranet_option_3_account" name="wp_intranet[accounts]" type="text" 
            value="<?php echo esc_attr($form->get_ad_accounts()); ?>" /></p>
    </p>
    <p>

    <?php wp_nonce_field( 'wp_intranet_management', 'wp-intranet-management' ); ?>
    
    </p><br />
    <p><input class="button button-primary" type="submit" value="Save"></p>
       
    </form>

</div>