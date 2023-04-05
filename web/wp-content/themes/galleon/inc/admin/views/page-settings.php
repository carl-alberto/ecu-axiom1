<?php global $site_options; global $post; $meta = get_post_meta( $post->ID );
if( $post->ID !== get_option( 'page_on_front' ) ):
    if( $post->post_type == 'post' ): ?>
<div class="form-group">
    <label for="hero_image">Banner Image</label><br />
    <img id="hero_image_preview" class="img-thumbnail" src="<?php echo wp_get_attachment_url( $meta['hero_image'][0] ); ?>">
    <input type="button" class="btn btn-sm btn-primary upload_media" data-type="image" data-count="single" value="Select Image" />
    <input type="hidden" name="hero_image" id="hero_image" class="image_attachment_id" value="<?php echo $meta['hero_image'][0]; ?>">
</div>
<?php endif; ?>
<div class="form-group">
    <label for="hero_width">Banner Width</label><br />
    <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-sm btn-primary
        <?php if(!isset($meta["hero_width"]) || !$meta["hero_width"][0]) echo "active" ?>">
            <input type="radio" name="hero_width" value="false" autocomplete="off" <?php if(!isset($meta["hero_width"]) || !$meta["hero_width"][0]) echo "checked" ?>> Full Width
        </label>
        <label class="btn btn-sm btn-primary
        <?php if(isset($meta["hero_width"][0]) && $meta["hero_width"][0]) echo "active" ?>">
            <input type="radio" name="hero_width" value="true" autocomplete="off" <?php if(isset($meta["hero_width"][0]) && $meta["hero_width"][0]) echo "checked" ?>> Content Width
        </label>
    </div>
</div>
<?php endif; ?>
<div class="form-group">
    <label for="location">Alternate Title</label>
    <input
        class="form-control"
        name="h1_title"
        placeholder="Enter Title"
        type="text"
        value="<?php echo isset($meta['h1_title']) ? $meta['h1_title'][0] : ''; ?>"
    />
    <small>Changes the display name for the page</small>
</div>
<?php if(get_current_screen()->id == 'post'):?>
    <div class="form-group">
        <label for="location">Secondary Title</label>
        <input
            class="form-control"
            name="subtitle"
            placeholder="Enter Secondary Title"
            type="text"
            value="<?php echo isset($meta['subtitle']) ? $meta['subtitle'][0] : ''; ?>"
        />
        <small>Displays subtitle beneath post title</small>
    </div>
    <div class="form-group">
        <label for="location">External Post</label>
        <input
            class="form-control"
            name="external"
            placeholder="https://www.example.com/post"
            type="text"
            value="<?php echo isset($meta['external']) ? $meta['external'][0] : ''; ?>"
        />
        <small>Hyperlinks external post in archive pages</small>
    </div>
<?php endif; ?>
<?php if( $post->ID == get_option( 'page_on_front' ) ): ?>
<div class="form-group">
    <label for="hide_title">Hide Title</label><br />
    <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-sm btn-primary
        <?php if(isset($meta["hide_title"][0]) && $meta["hide_title"][0]) echo "active" ?>">
            <input type="radio" name="hide_title" value="true" autocomplete="off" <?php if(isset($meta["hide_title"][0]) && $meta["hide_title"][0]) echo "checked" ?>> Yes
        </label>
        <label class="btn btn-sm btn-primary
        <?php if(!isset($meta["hide_title"]) || !$meta["hide_title"][0]) echo "active" ?>">
            <input type="radio" name="hide_title" value="false" autocomplete="off" <?php if(!isset($meta["hide_title"]) || !$meta["hide_title"][0]) echo "checked" ?>> No
        </label>
    </div><br />
    <small>Hides page title. Available on homepage only.</small>
</div>
<?php endif; ?>
<?php
// Sets up labels depending on whether sitewide back to top is enabled / disabled
if($site_options->back_to_top) {
    $btt_label = 'Disable';
    $btt_desc = 'Disables back to top button on this page.';
} else {
    $btt_label = 'Enable';
    $btt_desc = 'Enables back to top button this page.';
}

?>
<div class="form-group">
    <label for="back_to_top"><?php echo $btt_label; ?> Back to Top</label><br />
    <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-sm btn-primary
        <?php if(isset($meta["back_to_top"][0]) && $meta["back_to_top"][0]) echo "active" ?>">
            <input
                type="radio"
                name="back_to_top"
                value="true"
                autocomplete="off"
                <?php if(isset($meta["back_to_top"][0]) && $meta["back_to_top"][0]) echo "checked" ?>
                > Yes
        </label>
        <label class="btn btn-sm btn-primary
        <?php if(!isset($meta["back_to_top"]) || !$meta["back_to_top"][0]) echo "active" ?>">
            <input
                type="radio"
                name="back_to_top"
                value="false"
                autocomplete="off"
                <?php if(!isset($meta["back_to_top"]) || !$meta["back_to_top"][0]) echo "checked" ?>> No
        </label>
    </div><br />
    <small><?php echo $btt_desc; ?></small>
</div>