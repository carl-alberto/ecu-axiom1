<?php global $post; $meta = get_post_meta( $post->ID ); $isHome = $post->ID === ( int ) get_option( 'page_on_front' ) ? true : false; ?>
<div class="form-group">
    <label>Slideshow</label>
    <button type="button" class="btn btn-sm btn-block btn-primary" data-toggle="modal" data-target="#editSlider">
        Slideshow Options
    </button>
</div>
<div class="modal fade" id="editSlider" tabindex="-1" role="dialog" aria-labelledby="editSliderTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="editSliderTitle">Slideshow Options</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="hero_type">Hero Type</label><br />
                    <select id="hero_type" name="hero_type" class="form-control">
                        <option value="">None</option>
                        <option value="image">Image</option>
                        <?php if( $isHome ): ?>
                            <option value="video">Video</option>
                        <option value="posts">Posts</option>
                        <option value="slideshow">Slideshow</option>
                    </select>
                </div>
                <div id="width" class="form-group d-none">
                    <label for="hero_width">Hero Width</label><br />
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
                <div id="image" class="slide-group d-none">
                <?php $image = wp_get_attachment_url( $meta['hero_image'][0] ); ?>
                    <div class="form-group">
                        <label for="hero_image">Hero Image</label><br />
                        <img id="hero_image_preview" class="<?php echo $image ? '' : 'd-none'; ?>" src="<?php echo $image; ?>">
                        <input type="button" class="btn btn-sm btn-primary upload_media" data-type="image" data-count="single" value="Select Image" />
                        <input type="hidden" name="hero_image" id="hero_image" class="image_attachment_id" value="<?php echo $meta['hero_image'][0]; ?>">
                    </div>
                </div>
                <div id="video" class="slide-group d-none">
                    <?php $video = wp_get_attachment_url( $meta['hero_video'][0] ); ?>
                    <div class="form-group">
                        <label for="hero_video">Hero Video</label><br />
                        <video id="hero_video_preview" class="<?php echo $video ? '' : 'd-none'; ?>" src="<?php echo $video ?>" autoplay muted loop ></video>
                        <input type="button" class="btn btn-sm btn-primary upload_media" data-type="video" data-count="single" value="Select Video" />
                        <input type="hidden" name="hero_video" id="hero_video" class="video_attachment_id" value="<?php echo $meta['hero_video'][0]; ?>">
                    </div>
                </div>
                <div id="posts" class="slide-group d-none">
                    <div class="form-group">
                        <label for="hero_category">Category</label>
                        <select
                            class="form-control"
                            name="hero_category"
                            id="hero_category">
                            <?php foreach( get_terms([ 'hide_empty' => false ]) as $term ): $selected = $meta['hero_category'][0] == $term->term_id ? 'selected' : false; ?>
                                <option value="<?php echo $term->term_id; ?>" <?php echo $selected; ?>><?php echo $term->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="hero_caption">Caption Placement</label>
                        <select
                            class="form-control"
                            name="hero_caption"
                            id="hero_caption">
                            <option value="top-left">Top Left</option>
                            <option value="top-right">Top Right</option>
                            <option value="bottom-left">Bottom Left</option>
                            <option value="bottom-right">Bottom Right</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="hero_post_count">Number of Posts</label>
                        <select
                            class="form-control"
                            name="hero_post_count"
                            id="hero_post_count"
                        >
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                        </select>
                        <small class="form-text text-muted">Only displays posts that have a banner image. To display specific posts select the slideshow hero type.</small>
                    </div>
                </div>
                <div id="slideshow" class="slide-group d-none">
                    <div id="slides">
                        <?php $slide_count = absint ( $meta['hero_slide_count'][0] ); for($i = 0; $i < 5; $i++):
                            $post_slide = $meta["hero_slide_{$i}_type"][0] === 'post-field' ? true : false;
                            $slide_value = $post_slide ? $meta["hero_slide_{$i}_post"][0] : $meta["hero_slide_{$i}_image"][0]; ?>

                            <div id="slide_<?php echo $i; ?>" data-id="<?php echo $i; ?>" class="slide <?php echo $i < $slide_count ? '' : 'd-none'; ?>">

                                <!-- Delete Slide -->
                                <button class="btn btn-sm btn-danger remove_slide"><span class="fas fa-times"></span></button>

                                <!-- Slide Type -->
                                <div class="form-group">
                                    <label>Slide Type</label>
                                    <select id="slide_<?php echo $i; ?>_type" name="hero_slide_<?php echo $i; ?>_type" class="form-control slide-type">
                                        <option value="image-field" <?php echo !$post_slide ? 'selected' : ''; ?>>Image</option>
                                        <option value="post-field" <?php echo $post_slide ? 'selected' : ''; ?>>Post</option>
                                    </select>
                                </div>

                                <!-- Image Slide -->
                                <div class="image-field slide-field <?php echo $post_slide ? 'd-none' : ''; ?>">

                                    <!-- Image Field -->
                                    <div class="form-group">
                                        <label for="hero_<?php echo $i; ?>_image">Slide Image</label><br />
                                        <img id="hero_<?php echo $i; ?>_image_preview" class="<?php echo $slide_value ? '' : 'd-none';?>" src="<?php echo $slide_value ? wp_get_attachment_url(  $slide_value ) : ''; ?>">
                                        <input type="button" class="btn btn-sm btn-primary upload_media" value="Select Image" />
                                        <input type="hidden" name="hero_slide_<?php echo $i; ?>_image" id="hero_<?php echo $i; ?>_image" value="<?php echo $slide_value ? $slide_value : ''; ?>">
                                    </div>

                                    <!-- Caption Title -->
                                    <div class="form-group">
                                        <label for="hero_slide_<?php echo $i; ?>_title">Caption Title</label>
                                        <input type="text" class="form-control" name="hero_slide_<?php echo $i; ?>_title" id="hero_slide_<?php echo $i; ?>_title" value="<?php echo $meta["hero_slide_{$i}_title"][0]; ?>">
                                    </div>

                                    <!-- Image Caption -->
                                    <div class="form-group">
                                        <label for="hero_<?php echo $i; ?>_caption">Slide Caption</label>
                                        <textarea
                                            class="form-control"
                                            name="hero_slide_<?php echo $i; ?>_caption"
                                            id="hero_slide_<?php echo $i; ?>_caption"
                                            rows="3"><?php echo $meta["hero_slide_{$i}_caption"][0]; ?></textarea>
                                    </div>

                                </div> <!-- / Image Slide -->

                                <!-- Post Slide -->
                                <div class="post-field slide-field <?php echo !$post_slide ? 'd-none' : ''; ?>">

                                    <!-- Post Field -->
                                    <div class="form-group">
                                        <label for="hero_<?php echo $i; ?>_post">Slide Post</label><br />
                                        <select
                                            id="hero_<?php echo $i; ?>_post"
                                            class="form-control post-select"
                                            name="hero_slide_<?php echo $i; ?>_post">
                                            <?php if($post_slide && $slide_value): ?>
                                                <option value="<?php echo $slide_value; ?>" selected><?php echo get_the_title( $slide_value ); ?></option>
                                            <?php endif; ?>
                                        </select>
                                        <small class="form-text text-muted">Post requires banner image to display.</small>
                                    </div>

                                </div> <!-- / Post Slide -->

                                <!-- Caption Position -->
                                <div class="form-group">
                                    <label for="hero_slide_<?php echo $i; ?>_caption_position">Caption Position</label>
                                    <select
                                        class="form-control"
                                        name="hero_slide_<?php echo $i; ?>_caption_position"
                                        id="hero_slide_<?php echo $i; ?>_caption_position">
                                            <?php $cap = $meta[ "hero_slide_{$i}_caption_position" ][0]; ?>
                                            <option value="top-left" <?php if($cap === 'top-left') echo 'selected'; ?>>Top Left</option>
                                            <option value="top-right" <?php if($cap === 'top-right') echo 'selected'; ?>>Top Right</option>
                                            <option value="bottom-left" <?php if($cap === 'bottom-left') echo 'selected'; ?>>Bottom Left</option>
                                            <option value="bottom-right" <?php if($cap === 'bottom-right') echo 'selected'; ?>>Bottom Right</option>
                                    </select>
                                </div> <!-- / Caption Position -->

                            </div>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="hero_slide_count" id="hero_slide_count" value="<?php echo $meta["hero_slide_count"][0] ? $meta["hero_slide_count"][0] : 0; ?>">
                    <button class="btn btn-sm btn-success mt-3" id="new_slide">Add Slide</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-sm btn-primary">Apply</button>
            </div>
        </div>
    </div>
</div>