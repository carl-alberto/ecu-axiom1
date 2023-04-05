<div <?php post_class(['post-entry']); ?>>
    <?php if ( has_post_banner() ): ?>
        <div class="row">
            <div class="col-3 col-lg-2">
                <div class="post-thumbnail">
                    <?php the_post_banner( 'thumbnail', [ 'class' => 'img-fluid']); ?>
                </div>
            </div>
            <div class="col-9 col-lg-10">
            <?php endif; ?>
                <div class="post-headline">
                    <a href="<?php $external = get_meta( 'ecu_spark' ); echo $external ? $external : get_the_permalink(); ?>" class="block-link">
                        <h2 class="h5">
                            <?php print_title( get_the_id(), true, '', '' ); ?>
                        </h2>
                    </a>
                    <?php the_excerpt(); ?>
                    <p class="date"><span class="far fa-calendar mr-2"></span><?php echo get_the_date( 'n.j.Y' ); ?></p>
                </div>
            <?php if( has_post_banner() ): ?>
            </div>
        </div>
    <?php endif; ?>
</div>