<?php $banner = get_field('banner_image'); ?>
<?php if(get_field('expand_posts', 'option')): ?>
    <div class="blog-post expanded">
        <?php if($banner): ?>
            <img src="<?php echo $banner['sizes']['banner_xs']; ?>" alt="<?php echo $banner['alt'];?>" class="img-stretch" />
        <?php endif; ?>
        <div class="content">
            <h2><?php h1_title(get_the_id(), false); ?></h2>
              <?php if(get_field('secondary_title')): ?>
                <h2 class="secondary-title"><?php the_field('secondary_title'); ?></h2>
              <?php endif; ?>
              <?php if(!get_field('hide_post_meta', 'option')): ?>
                  <div class="post-meta">
                    <div class="posted">Published <?php the_date('M d, Y'); ?> by <?php the_featured_author(get_the_id(), true); ?></div>
                    <div class="cats">Filed under: <?php the_category(); ?></div>
                  </div>
              <?php endif; ?>
            <?php the_content(); ?>
        </div>
    </div>
<?php else: $url = filter_var(get_field('external_post'), FILTER_VALIDATE_URL) ? filter_var(get_field('external_post'), FILTER_VALIDATE_URL) : get_the_permalink(); ?>
    <a href="<?php echo $url; ?>" class="blog-post" style="display:block;">
      <?php if($banner): ?>
      <div class="row">
        <div class="col-md-5">
          <img src="<?php echo $banner['sizes']['banner_xs']; ?>" alt="<?php echo $banner['alt'];?>" class="img-stretch" />
        </div>
        <div class="col-md-7">
      <?php endif; ?>
          <div class="blog-meta">
            <?php echo get_the_date('M d, Y'); ?><?php the_featured_author($post->ID); ?>
          </div>
          <div class="blog-content">
            <h2><?php h1_title(get_the_id(), false); ?></h2>
            <p><?php gp_excerpt($post->ID, '... <i class="fa fa-chevron-right" aria-hidden="true"></i>'); ?></p>
          </div>
        <?php if($banner): ?>
        </div>
      </div>
    <?php endif; ?>
    </a>
<?php endif; ?>
