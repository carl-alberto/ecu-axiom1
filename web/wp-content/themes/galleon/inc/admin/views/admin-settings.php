<?php $options = get_site_options(); ?>

    <div class="container">
        <form action="<?php echo admin_url('admin-post.php'); ?>" method="post" id="settings-form">

        <!-- Begin Default Template Section -->

        <section id="template-section">
            <h2>Site Settings</h2>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Default Page Template</label>
                        <select 
                            class="form-control"
                            name="ecu_default_template">
                            <option 
                                value="page.php"
                                <?php echo $options["ecu_default_template"] == "page.php" ? "selected" : ""; ?>>
                                Full Width
                            </option>
                            <option 
                                value="page-sidebar.php"
                                <?php echo $options["ecu_default_template"] == "page-sidebar.php" ? "selected" : ""; ?>>
                                Sidebar
                            </option>
                        </select>
                        <small class="form-text text-muted">Default template applied to new pages; can be changed on a per page basis.</small>
                    </div>
                </div>
                <?php if(current_user_can('editor')): ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ecu_hide_nav">Hide Site Navigation</label><br />
                            <?php $ecu_hide_nav = $options["ecu_hide_nav"]; ?>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-primary <?php echo $ecu_hide_nav ? "active" : ""; ?>">
                                    <input 
                                        type="radio" 
                                        name="ecu_hide_nav" 
                                        value="true" 
                                        autocomplete="off"
                                        <?php echo $ecu_hide_nav ? "checked" : ""; ?>> Yes
                                </label>
                                <label class="btn btn-primary <?php echo $ecu_hide_nav ? "" : "active"; ?>">
                                    <input 
                                        type="radio" 
                                        name="ecu_hide_nav" 
                                        value="false" 
                                        autocomplete="off"
                                        <?php echo $ecu_hide_nav ? "" : "checked"; ?>> No
                                </label>
                            </div><br />
                            <small>Hides the site navigation bar.</small>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="ecu_back_to_top">Enable Back to Top</label><br />
                        <?php $ecu_back_to_top = $options["ecu_back_to_top"]; ?>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-primary <?php echo $ecu_back_to_top ? "active" : ""; ?>">
                                <input 
                                    type="radio" 
                                    name="ecu_back_to_top" 
                                    value="true" 
                                    autocomplete="off"
                                    <?php echo $ecu_back_to_top ? "checked" : ""; ?>> Yes
                            </label>
                            <label class="btn btn-primary <?php echo $ecu_back_to_top ? "" : "active"; ?>">
                                <input 
                                    type="radio" 
                                    name="ecu_back_to_top" 
                                    value="false" 
                                    autocomplete="off"
                                    <?php echo $ecu_back_to_top ? "" : "checked"; ?>> No
                            </label>
                        </div><br />
                        <small>Displays back to top button on pages</small>
                    </div>
                </div>
            </div>
        </section>

            <!-- End Default Template Section -->
            <!-- Begin Address Section -->

            <section id="address-section">
                <h2>Address</h2>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ecu_location">Location Name</label>
                            <input
                                class="form-control"
                                name="ecu_location"
                                placeholder="Location Name" 
                                type="text" 
                                value="<?php echo $options["ecu_location"]; ?>" 
                            />
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="ecu_address">Street Address</label>
                            <input
                                class="form-control"
                                name="ecu_address"
                                placeholder="Street Address" 
                                type="text" 
                                value="<?php echo $options["ecu_address"]; ?>"
                            />
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="form-group">
                            <label for="ecu_city">City</label>
                            <input
                                class="form-control"
                                name="ecu_city"
                                placeholder="City" 
                                type="text" 
                                value="<?php echo $options["ecu_city"]; ?>" 
                            />
                        </div>
                    </div>
                    <div class="col-3 col-md-3">
                        <div class="form-group">
                            <label for="ecu_state">State</label>
                            <input
                                class="form-control"
                                name="ecu_state"
                                placeholder="State" 
                                type="text" 
                                value="<?php echo $options["ecu_state"]; ?>" 
                                disabled
                            />
                        </div>  
                    </div>
                    <div class="col-3 col-md-4">
                        <div class="form-group">
                            <label for="ecu_zip">Zip</label>
                            <input
                                class="form-control"
                                name="ecu_zip"
                                placeholder="Zip" 
                                type="text" 
                                value="<?php echo $options["ecu_zip"]; ?>" 
                                maxlength="5"
                            />
                            <small>Limit 5 characters. Numbers only.</small>
                        </div>
                    </div>
                </div>
            </section>

            <!-- End Address Section -->

            <section id="contact-section">
                <h2>Contact</h2>
                <div class="row">
                    <div class="col-md-5">
                        <label for="ecu_phone">Phone Number</label>
                        <input
                            class="form-control"
                            name="ecu_phone"
                            placeholder="Phone Number" 
                            type="text" 
                            maxlength="10"
                            value="<?php echo $options["ecu_phone"]; ?>" 
                        />
                        <small>Limit 10 characters. Numbers only. Formatting is applied on frontend.</small>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ecu_contact_page">Contact Page</label>
                            <select 
                                class="form-control"
                                name="ecu_contact_page"
                                id="ecu_contact_page"
                                data-selected="<?php echo $options["ecu_contact_page"]; ?>">
                                <option value="<?php echo $options["ecu_contact_page"]; ?>" selected><?php echo get_the_title( $options["ecu_contact_page"] ); ?></option>
                            </select>
                            <small class="form-text text-muted">This will set the Contact Us link in the site footer.</small>
                        </div>
                    </div>
                </div>
            </section>

            <section id="post-section">
                <h2>Archive &amp; Post Settings</h2>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ecu_hide_post_meta">Hide Post Meta</label><br />
                            <?php $ecu_hide_post_meta = $options["ecu_hide_post_meta"] === "1" ? true : false; ?>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-primary <?php echo $ecu_hide_post_meta ? "active" : ""; ?>">
                                    <input 
                                        type="radio" 
                                        name="ecu_hide_post_meta" 
                                        value="true" 
                                        autocomplete="off"
                                        <?php echo $ecu_hide_post_meta ? "checked" : ""; ?>> Yes
                                </label>
                                <label class="btn btn-primary <?php echo $ecu_hide_post_meta ? "" : "active"; ?>">
                                    <input 
                                        type="radio" 
                                        name="ecu_hide_post_meta" 
                                        value="false" 
                                        autocomplete="off"
                                        <?php echo $ecu_hide_post_meta ? "" : "checked"; ?>> No
                                </label>
                            </div><br />
                            <small>Hides the date, author and categories for posts</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ecu_archive_sidebar">Archive Sidebar</label>
                            <select 
                                class="form-control"
                                name="ecu_archive_sidebar"
                                id="ecu_archive_sidebar"
                                data-selected="<?php echo $options["ecu_archive_sidebar"]; ?>">
                                <option value="<?php echo $options["ecu_archive_sidebar"]; ?>" selected><?php echo get_the_title( $options["ecu_archive_sidebar"] ); ?></option>
                            </select>
                            <small class="form-text text-muted">Sets the default sidebar for archive pages.</small>
                        </div>
                    </div>
                </div>
            </section>

            <section id="misc-settings">
                <h2>Misc Settings</h2>
                <p>This section will be removed in production, only used for testing</p>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ecu_second_level_nav">Second Level Nav</label><br />
                            <?php $ecu_second_level_nav = $options["ecu_second_level_nav"] === "1" ? true : false; ?>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-primary <?php echo $ecu_second_level_nav ? "active" : ""; ?>">
                                    <input 
                                        type="radio" 
                                        name="ecu_second_level_nav" 
                                        value="true" 
                                        autocomplete="off"
                                        <?php echo $ecu_second_level_nav ? "checked" : ""; ?>> Yes
                                </label>
                                <label class="btn btn-primary <?php echo $ecu_second_level_nav ? "" : "active"; ?>">
                                    <input 
                                        type="radio" 
                                        name="ecu_second_level_nav" 
                                        value="false" 
                                        autocomplete="off"
                                        <?php echo $ecu_second_level_nav ? "" : "checked"; ?>> No
                                </label>
                            </div><br />
                            <small>Displays second level navigation</small>
                        </div>
                    </div>
                </div>
            </section>
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <input type="hidden" name="action" value="site_settings_post">
                    <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
