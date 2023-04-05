<div class="itcs_alert_shortcode ecu_itcs_alert_shortcode">
    <?php if (!empty($issues)): ?>
        <div class="alert alert-danger" role="alert" style="background-color:transparent; border:none">
            <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i><strong> System Status: </strong><a href="<?php echo $href; ?>">View details</a>
        </div>
    <?php else: ?>        
        <div class="alert alert-success" role="alert" style="background-color:transparent; border:none">
            <i class="fa fa-check-circle fa-lg" aria-hidden="true"></i><strong> System Status: </strong><a href="<?php echo $href; ?>">View maintenance</a>
        </div>
    <?php endif; ?>
</div>