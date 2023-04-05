<?php /* Template Name: Ribbon Template */ ?>
<?php get_header(); global $post; ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <main id="main">
    <div class="ribbon-content">
      <div class="container">
        <?php h1_title(); ?>
        <?php the_content(); ?>
      </div>
    </div>
    <?php if(($ribbons = get_field('ribbons')) && (! post_password_required())):
        if(is_array($ribbons)):
          foreach($ribbons as $ribbon):
            if($callout = $ribbon['callout']){
              $callout_order = $ribbon['callout_position'] == 'right' ? 'order-lg-12' : 'order-lg-1';
              $content_order = $ribbon['callout_position'] == 'right' ? 'order-lg-1' : 'order-lg-12';
            }?>
      <section aria-label="<?php echo $ribbon['ribbon_description']; ?>" role="complementary" class="ribbon <?php echo $ribbon['color_scheme']; ?>">
        <div class="container">
          <?php if($callout): ?>
              <div class="row">
                <div class="col-lg-3 <?php echo $callout_order; ?>">
                  <div class="ribbon-callout">
                    <?php echo callout_tags($ribbon['callout_content']); ?>
                  </div>
                </div>
              <div class="col-lg-9 <?php echo $content_order; ?>">
                <?php echo $ribbon['ribbon_content']; // output ribbon content ?>
              </div>
          <?php else: // no callout ?>
            <?php echo $ribbon['ribbon_content']; // output ribbon content
          endif; ?>
        </div>
        <div class="clearfix"></div>
      </section>
      <?php endforeach; // ribbon
      endif;
    endif; // ribbons ?>
  </main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
