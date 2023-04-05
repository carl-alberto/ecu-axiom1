<?php get_header();
    if (have_posts()) : while (have_posts()) : the_post(); $meta = get_post_meta( get_the_id() ); ?>
        <div class="container">
            <main id="main">

                <h1>
                    <?php the_title(); ?><?php if( !empty( $meta['counselor_title'][0] ) ){
                        echo ', <span>' . $meta['counselor_title'][0] . '</span>';
                    }?>
                </h1>
                <?php if( has_post_thumbnail() || !empty( $meta['counselor_email'][0] ) || !empty( $meta['counselor_phone'][0] ) ): ?>
                    <div class="post-img-wrap">
                        <?php if( has_post_thumbnail() ) the_post_thumbnail( 'medium' ); ?>
                        <?php if( !empty( $meta['counselor_appointment'][0] ) ): ?>
                            <a href="<?php echo $meta['counselor_appointment'][0]; ?>" class="mail-btn mb-2">
                                <span><i class="fa fa-calendar-plus-o" aria-hidden="true"></i></span>
                                Schedule Appointment
                            </a>
                        <?php endif; ?>
                        <?php if( !empty( $meta['counselor_email'][0] ) ): ?>
                            <a href="mailto:<?php echo $meta['counselor_email'][0]; ?>" class="mail-btn mb-2">
                                <span><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                <?php echo $meta['counselor_email'][0]; ?>
                            </a>
                        <?php endif; ?>
                        <?php if( !empty( $meta['counselor_phone'][0] ) ): ?>
                            <a href="tel:<?php echo $meta['counselor_phone'][0]; ?>" class="mail-btn">
                                <span><i class="fa fa-phone" aria-hidden="true"></i></span>
                                <?php echo $meta['counselor_phone'][0]; ?>
                            </a>
                        <?php endif; ?>
                        <?php if( $schools = wp_get_post_terms( get_the_id(), 'counselor-school' ) ){
                            echo '<div class="counselor-schools"><strong class="d-block">High Schools Served</strong><ul>';
                            foreach( $schools as $school){
                                echo "<li>{$school->name}</li>";
                            }
                            echo '</ul></div>';
                        }; ?>
                    </div>
                <?php endif ?>
                <?php the_content(); ?>
            </main>
        </div>
    <?php endwhile; endif;
get_footer(); ?>
