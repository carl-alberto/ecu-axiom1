<?php function itcs_departments( $atts ){
    ob_start();
    $args = array(
        'taxonomy' => 'department',
        'hide_empty' => false
    );
    $terms = get_terms($args); ?>
    <?php foreach($terms as $term):
        if($term->parent == 0): ?>
        <div class="department">
            <h3><?php echo $term->name; ?></h3>
                <p><?php echo $term->description; ?></p>
                <?php if($meta = get_field('additional_meta', $term)): ?>
                    <p class="meta">
                        <?php echo $meta; ?>
                    </p>
                <?php endif; ?>
                <a href="<?php echo get_term_link($term); ?>" class="btn-ribbon">Learn More About <?php echo $term->name; ?></a>
        </div>
        <?php endif;
    endforeach;

    $output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode( 'itcs-departments', 'itcs_departments' );
