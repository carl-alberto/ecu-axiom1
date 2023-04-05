  
 <div class="wrap">

  <h1>Create a New Site</h1>

  <p>No harm will be done to a site that already exists.</p>

   <?php echo $form->get_message(); ?>

  <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">

  <input type="hidden" name="action" value="create_site_form" />

    <div id="ecu-admin-admin-page">
    <table class="form-table">
      <tbody>
        <tr class="form-field form-required">
          <th scope="row"><label for="pirate_id">Admin Pirate ID*</label></th>
          <td>
            <input name="pirate_id" type="text" class="regular-text" id="pirate_id" value="<?php echo $form->get_pirate_id(); ?>">
          </td>
        </tr>
        
        <tr class="form-field">
          <th scope="row"><label for="site_domain">Site URL*</label></th>
          <td>
            <?php
              $site_url = $form->get_site_domain();
              if(!empty($form->get_site_path())) {
                $site_url .= '/' . $form->get_site_path();
              }
            ?>
            <input name="site_url" type="text" class="regular-text" id="site_url" value="<?php echo $site_url; ?>">
            <p class="description" id="site_title-desc">You must include the full site url.  ie. subdomain.ecu.edu or subdomain.ecu.edu/siteA
            </p>
          </td>
        </tr>

        <tr class="form-field">
          <th scope="row"><label for="site_title">Site Title*</label></th>
          <td>
            <input name="site_title" type="text" class="regular-text" id="site_title" value="<?php echo $form->get_site_title(); ?>">
          </td>
        </tr>
        <tr class="form-field">
          <th cope="row"><label for="notify_admin">Notify User</label></th>
          <td>
            <input type="checkbox" id="notify_admin">
            <p class="description" id="notify_admin-desc">Emails admin user new WP blog url</p>
          </td>
        </tr>
      </tbody>
    </table>

    <?php echo wp_nonce_field( 'create_site', 'create-site' ); ?>

    <p class="submit"><input type="submit" name="ecu_new_blog" id="ecu_new_blog" class="button button-primary" value="Create Site"></p>

    </div>
  </form>

  <?php echo $form->render_results(); ?>
  <?php echo $form->render_errors(); ?>
 
 </div>