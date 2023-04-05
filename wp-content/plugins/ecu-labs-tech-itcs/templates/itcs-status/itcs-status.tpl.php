<hr />
<div class="ecu_itcs-container itcs-container itcs-content">
    <?php if (empty($issues)): ?>
        <h2>
            <i class="fa fa-check-circle" aria-hidden="true"></i> All Systems Operational 
        </h2>
        <div class="well">
            <div class="ecu-status">
                All major ITCS services are operating normally. If you are experiencing problems or wish 
                to report an outage, please contact the ECU IT Help Desk by phone at 252-328-9866 or via the Web at <a href="https://ithelp.ecu.edu">https://ithelp.ecu.edu</a>.
            </div>
        </div>
    <?php else: ?>
        <h2><i class="fa fa-exclamation-circle"  aria-hidden="true"></i> Known Issues </h2>
        <?php foreach ($issues as $issue): ?>
            <div class="well">
                <div class="ecu-status">
                    <h3 class="ecu-status-title"><?php echo esc_html($issue->title); ?></h3>
                    Posted: <?php echo mysql2date('F d', $issue->date_submitted); ?><br><br>
                    <div class="ecu-status-description"><?php echo \ECU\ITCSSTATUS\itcs_strip_tags($issue->body); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2><i class="fa fa-calendar" aria-hidden="true"></i> Maintenance </h2>
    <div class="well">
        <div class="ecu-status">
    <?php if (empty($maintenances)): ?>
           There is no maintenance scheduled at this time.
    <?php else: ?>
        <?php foreach ($maintenances as $maintenance): ?>
            <h3 class="ecu-status-title"><?php echo esc_html($maintenance->title); ?></h3>
            Posted: <?php echo mysql2date('F d', $maintenance->date_submitted); ?><br><br>
            <div class="ecu-status-description"><?php echo \ECU\ITCSSTATUS\itcs_strip_tags($maintenance->body); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
            <h3 class="ecu-status-title">IT Systems Maintenance Schedule</h3>
            <div class="ecu-status-description">Please keep in mind that regular maintenance on all computing systems is performed on Sundays between the hours of 5:00 am - 12:00 pm. If there are other times that systems will be down, notification will be sent through ITCS Notifications.</div>
    </div>
</div>

