<?php get_header();
  if (have_posts()) : while (have_posts()) : the_post();
  $type = isset($wp_query->query['faqs']) ? 'faq' : 'tutorial';
  $args = array(
      'posts_per_page' => 100,
      'post_type' => $type,
      'orderby' => 'title',
      'order' => 'ASC',
      'meta_key' => 'service',
      'meta_value' => $post->ID
  );
  $posts = get_posts($args);
  ?>
  <div class="container">
    <main id="main">
        <?php breadcrumbs(); ?>
        <?php h1_title(); ?>
        <div class="accordion" id="faq-accordion">
            <?php $counter = 0; $total = count($posts); foreach($posts as $post): ?>
                <div class="card">
                  <div class="card-header" id="heading-<?php echo $counter; ?>">
                    <h2 class="mb-0">
                      <button class="btn btn-link toggle-accordion" type="button" data-toggle="collapse" data-target="#collapse-<?php echo $counter; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $counter; ?>">
                        <?php echo $post->post_title; ?>
                      </button>
                  </h2>
                  </div>

                  <div id="collapse-<?php echo $counter; ?>" class="collapse" aria-labelledby="heading-<?php echo $counter; ?>" data-parent="#faq-accordion">
                    <div class="card-body">
                      <?php echo apply_filters('the_content',$post->post_content); ?>
                      <div class="actions-wrap">
                          <p>Direct Link</p>
                          <div class="actions">
                              <input id="tut-<?php echo $counter; ?>" type="text" class="form-control" value="<?php echo get_the_permalink($post->ID); ?>" readonly aria-label="<?php echo $post->post_title; ?> URL">
                              <button class="btn clipboard" type="button" data-clipboard-target="#tut-<?php echo $counter; ?>" ><span class="fa fa-copy"></span></button>
                              <a href="<?php echo get_the_permalink($post->ID); ?>" class="btn" target="_blank"><span class="fa fa-external-link"></span></a>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
            <?php $counter++; endforeach; ?>
        </div>
    </main>
  </div>
  <?php endwhile; endif;
get_footer(); ?>
