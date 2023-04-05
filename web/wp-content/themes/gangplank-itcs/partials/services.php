<div class="col-md-6">
    <div class="service">
        <a href="<?php echo get_the_permalink($post->ID); ?>">
            <h2><?php echo $post->post_title; ?></h2>
        </a>
        <p><?php echo $post->post_excerpt; ?></p>
        <?php if($actions = get_field('actions', $post->ID)): if(is_array($actions)):?>
            <div class="actions">
                <?php if(count($actions) == 1): ?>
                    <?php foreach($actions as $link):
                        if($link['link']['title'] && $link['link']['url']):?>
                            <a href="<?php echo $link['link']['url']; ?>" target="<?php echo $link['link']['target']; ?>">
                                <?php echo $link['icon'] ? '<span class="fa ' . $link['icon'] . '"></span>' : '<span class="fa fa-link"></span>'; ?>
                                <?php echo $link['link']['title']; ?>
                            </a>
                    <?php endif;
                endforeach; ?>
                <?php else: ?>
                    <div class="row">
                        <?php foreach($actions as $link):
                            if($link['link']['title'] && $link['link']['url']):?>
                            <div class="col-sm-6">
                                <a href="<?php echo $link['link']['url']; ?>" target="<?php echo $link['link']['target']; ?>">
                                    <?php echo $link['icon'] ? '<span class="fa ' . $link['icon'] . '"></span>' : '<span class="fa fa-link"></span>'; ?>
                                    <?php echo $link['link']['title']; ?>
                                </a>
                            </div>
                        <?php endif;
                    endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; endif; ?>
    </div>
</div>
