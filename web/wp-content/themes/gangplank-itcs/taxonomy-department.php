<?php get_header();?>
    <main id="main">
        <div class="container">
            <?php get_template_part('partials/components/header-meta');?>
            <?php
                $queried_term = get_queried_object();
                $args = array(
                    'taxonomy' => 'department',
                    'hide_empty' => false,
                    'parent' => $queried_term->term_id
                );
                $terms = get_terms($args);

                the_field('content', $queried_term);
                if($terms): ?>
                    <h2>Our Teams</h2>
                    <?php foreach($terms as $term): ?>
                        <ul>
                            <li><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?></a></li>
                        </ul>
                    <?php endforeach;
                endif;
            ?>
            </div>
    </main>
<?php get_footer(); ?>
