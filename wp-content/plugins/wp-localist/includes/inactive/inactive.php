<?php

// Adds wp_localist shortcode. This will replace the actual shortcode when the API has been disabled
add_shortcode('wp_localist', 'localist_inactive_shortcode');

// wp_localist callback function, simply displays message stating Localist is down
function localist_inactive_shortcode() {
    return "<p>Localist is currently under maintenance and will be back up shortly.</p>";
}