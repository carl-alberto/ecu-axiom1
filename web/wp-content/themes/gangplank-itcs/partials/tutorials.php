<?php
global $post;
$args = array(
    'posts_per_page' => 100,
    'post_type' => 'tutorial',
    'meta_key' => 'service',
    'meta_value' => $post->ID,
);
$tuts = get_posts($args);
if(count($tuts) > 0): ?>
    <a href="#tutorials"></a>
    <h2>Tutorials</h2>
        <ul id="tutorials">
        <?php foreach($tuts as $tut):?>
            <li><a href="<?php echo get_the_permalink($tut->ID); ?>"><?php echo $tut->post_title; ?></a></li>
        <?php endforeach;?>
        </ul>
<?php endif;
