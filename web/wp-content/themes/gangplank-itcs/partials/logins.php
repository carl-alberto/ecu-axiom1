<?php
if($logins = get_field('itcs_logins', 'option')): ?>
    <div id="itcs-logins">
        <div class="row">
            <?php foreach($logins as $login):?>
                <div class="col-6 col-md-2">
                    <a href="<?php echo $login['link']; ?>" class="login" target="_blank" alt="Log in to <?php echo $login['name']; ?>" aria-label="<?php echo $login['name']; ?>">
                        <div class="login-img">
                            <img src="<?php echo $login['icon']; ?>">
                        </div>
                        <!-- <h2><?php //echo $login['name']; ?></h2> -->
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif;
