<?php function itcs_logins( $atts ){
    ob_start();
    if($logins = get_field('itcs_logins', 'option')): ?>
        <div id="itcs-logins">
            <div class="row">
                <?php foreach($logins as $login):?>
                    <div class="col-6 col-md-2">
                        <a href="<?php echo $login['link']; ?>" class="login" target="_blank" aria-label="<?php echo $login['name']; ?>">
                            <div class="login-img">
                                <img src="<?php echo $login['icon']; ?>" alt="<?php echo $login['name']; ?>">
                            </div>
                            <!-- <h2><?php //echo $login['name']; ?></h2> -->
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif;
    $output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode( 'itcs-logins', 'itcs_logins' );
