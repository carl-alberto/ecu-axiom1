<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="footer-branding-left">
                <?php footer_contact(); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="footer-branding-right">
                <p>
                    &copy; <?php echo date('Y'); ?> |
                    <a href="https://<?php echo getenv('TOPSITE_ENV'); ?>/terms" class="reset">Terms of Use</a>
                </p>
                <p class="mt-1">
                    <a href="https://accessibility.ecu.edu/" class="reset">Accessibility</a> |
                    <a href="https://accessibility.ecu.edu/report-an-accessibility-barrier/?referrer=<?php bloginfo('wpurl'); ?>" class="reset">Report a Barrier</a>
                    <div id="google_translate_element"></div>
                </p>
            </div>
        </div>
    </div>
</div>