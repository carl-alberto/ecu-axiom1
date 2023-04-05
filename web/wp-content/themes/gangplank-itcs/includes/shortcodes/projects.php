<?php function itcs_projects( $atts ){
    ob_start(); ?>
    <div class="row">
        <?php $args = array(
            'taxonomy' => 'goals',
            'post_type' => 'project',
            'hide_empty' => false
        );
        $terms = get_terms($args);
        foreach($terms as $term):?>
            <div class="col-md-6 col-lg-4 my-3">
                <div class="project-term">
                    <div class="term-content">
                        <?php if($icon = get_field('icon', $term)): ?>
                            <div class="goal-icon"><?php echo $icon; ?></div>
                        <?php endif; ?>
                        <h2 class="text-center" data-mh="project-name"><?php echo $term->name; ?></h2>
                        <hr />
                        <p data-mh="project-description"><?php the_field('description', $term); ?></p>
                    </div>
                    <!-- <div class="btn-wrap text-center"><a href="<?php //echo get_term_link($term->term_id); ?>" class="btn btn-ribbon">Projects / Initiatives</a></div> -->
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php $output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode( 'itcs-projects', 'itcs_projects' );
