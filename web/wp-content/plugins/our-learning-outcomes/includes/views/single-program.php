<?php
/*
 * Template Name: Program
 * Template Post Type: program
 */
get_header();
    if (have_posts()) : while (have_posts()) : the_post();?>
        <main id="main">
            <div class="container">
                    <?php
                        $college_id = get_post_meta($post->ID, 'college_selector', true);
                        echo do_shortcode('[learning_outcomes post_type="'.get_post_type().'" selected="/'.get_post_type().'/'.get_post_field( 'post_name').'" college_id="'.$college_id.'"/]');
                    ?>
                <div id="lo-content-wrap">
                    <div class="container">
                        <div class="row">
                            <?php echo '<h1>' . get_the_title($college_id) . '</h1><hr>';?>
                        </div>
                        <div class="row">
                            <?php the_title('<h2>','</h2>'); ?>
                        </div>
                        <div class="lo-program-purpose">
                            <h3>Program Purpose</h3>
                            <hr />
                            <?php
                                $pp = get_post_meta($post->ID, 'pp_editor', true);
                                echo wpautop($pp);
                            ?>
                        </div>
                        <br />
                        <div class="lo-learning-outcome">
                            <h3>Learning Outcomes</h3>
                            <hr />
                            <?php
                                $lo = get_post_meta($post->ID, 'lo_editor', true);
                                echo wpautop($lo);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <?php endwhile; endif;
get_footer();
?>