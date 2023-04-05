<?php global $post; $meta = get_post_meta( $post->ID );?>

    <div class="form-group">
        <label for="custom_sidebar">Selector</label>
        <select 
            class="form-control"
            name="custom_sidebar"
            id="sidebar">
            <?php if(isset($meta["custom_sidebar"])): ?>
                <option value="<?php echo $meta["custom_sidebar"][0]; ?>" selected><?php echo get_the_title($meta["custom_sidebar"][0]); ?></option>
            <?php endif; ?>
        </select>
        <small class="form-text text-muted">Displays selected sidebar on page</small>
    </div>

    <div class="form-group">
        <label for="sidebar_location">Location</label><br />
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-primary 
            <?php if(isset($meta["sidebar_location"][0]) && $meta["sidebar_location"][0]) echo "active" ?>">
                <input 
                    type="radio" 
                    name="sidebar_location" 
                    value="true" 
                    autocomplete="off"
                    <?php if(isset($meta["sidebar_location"][0]) && $meta["sidebar_location"][0]) echo "checked" ?>
                    > Left
            </label>
            <label class="btn btn-primary
            <?php if(!isset($meta["sidebar_location"]) || !$meta["sidebar_location"][0]) echo "active" ?>">
                <input 
                    type="radio" 
                    name="sidebar_location" 
                    value="false" 
                    autocomplete="off"
                    <?php if(!isset($meta["sidebar_location"]) || !$meta["sidebar_location"][0]) echo "checked" ?>> Right
            </label>
        </div><br />
        <small>Displays sidebar on chosen location</small>
    </div>