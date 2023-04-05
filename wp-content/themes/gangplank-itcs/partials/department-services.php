<?php $term = get_queried_object();
$args = array(
    'posts_per_page' => -1,
    'post_type' => 'service',
    'orderby' => 'name',
    'order' => 'ASC',
    'tax_query' => array(
        array(
            'taxonomy' => 'department',
            'field' => 'ID',
            'terms' => $term->term_id,
        )
    )
);
$posts = get_posts($args);
if($posts > 0): ?>
    <h2 class="widget-title">Services</h2>
    <ul>
        <?php foreach($posts as $post):?>
            <li><a href="<?php echo get_the_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
        <?php endforeach;?>
    </ul>

<?php endif;
